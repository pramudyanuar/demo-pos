<?php
// public/index.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/core/Database.php';

// Simple Router
$requestUri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER['REQUEST_METHOD'];

function render_view($viewName, $layout, $data = []) {
    extract($data);
    $view = __DIR__ . '/../src/views/' . $viewName . '.php';
    require __DIR__ . '/../src/views/layouts/' . $layout . '.php';
}

$db = Database::getInstance()->getConnection();

// Fungsi untuk mengambil semua settings
function get_settings($db) {
    $settings = [];
    $stmt = $db->query("SELECT key, value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['key']] = $row['value'];
    }
    return $settings;
}

switch ($requestUri) {
    case '/':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        $stmt = $db->query("SELECT * FROM products WHERE is_active = 1 ORDER BY name ASC");
        $products = $stmt->fetchAll();
        $settings = get_settings($db);
        render_view('pos/index', 'app', [
            'title' => 'Kasir',
            'products' => $products,
            'settings' => $settings
        ]);
        break;

    case '/login':
        if ($method === 'GET') {
            render_view('auth/login', 'auth', ['title' => 'Login']);
        } elseif ($method === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM employees WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role_id'] = $user['role_id'];
                header('Location: /');
            } else {
                render_view('auth/login', 'auth', ['title' => 'Login', 'error' => 'Username atau password salah.']);
            }
        }
        break;

    case '/logout':
        session_destroy();
        header('Location: /login');
        break;

    case '/process-payment':
        if ($method === 'POST') {
            try {
                $db->beginTransaction();

                $cartData = json_decode($_POST['cart_data'], true);
                $totalAmount = floatval($_POST['total_amount']);
                $paymentMethod = $_POST['payment_method'];
                $employeeId = $_SESSION['user_id'];

                // 1. Masukkan ke tabel transactions
                $stmt = $db->prepare("INSERT INTO transactions (employee_id, final_amount, payment_method) VALUES (?, ?, ?)");
                $stmt->execute([$employeeId, $totalAmount, $paymentMethod]);
                $transactionId = $db->lastInsertId();

                // 2. Siapkan statement untuk mengurangi stok
                $updateProductStockStmt = $db->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $updateIngredientStockStmt = $db->prepare("UPDATE ingredients SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $getRecipeStmt = $db->prepare("SELECT * FROM product_recipes WHERE product_id = ?");

                // 3. Loop melalui item di keranjang
                $itemStmt = $db->prepare("INSERT INTO transaction_items (transaction_id, product_id, quantity, price_per_item, total_price) VALUES (?, ?, ?, ?, ?)");
                foreach ($cartData as $productId => $item) {
                    // Masukkan ke transaction_items
                    $itemStmt->execute([
                        $transactionId,
                        $productId,
                        $item['quantity'],
                        $item['price'],
                        $item['price'] * $item['quantity']
                    ]);

                    // Kurangi Stok
                    $productInfo = $db->query("SELECT is_recipe FROM products WHERE id = $productId")->fetch();

                    if ($productInfo['is_recipe'] == 1) {
                        // Jika produk adalah resep, kurangi stok bahan baku
                        $getRecipeStmt->execute([$productId]);
                        $recipeItems = $getRecipeStmt->fetchAll();
                        foreach ($recipeItems as $recipeItem) {
                            $quantityToReduce = $recipeItem['quantity_used'] * $item['quantity'];
                            $updateIngredientStockStmt->execute([$quantityToReduce, $recipeItem['ingredient_id']]);
                        }
                    } else {
                        // Jika produk biasa, kurangi stok produk itu sendiri
                        $updateProductStockStmt->execute([$item['quantity'], $productId]);
                    }
                }

                $db->commit();

                header("Location: /receipt?id=" . $transactionId);
                exit();

            } catch (Exception $e) {
                $db->rollBack();
                // Beri pesan error yang lebih informatif
                error_log("Gagal memproses transaksi: " . $e->getMessage());
                render_view('error/500', 'app', ['error_message' => $e->getMessage()]);
            }
        }
        break;

    case '/receipt':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        $transactionId = $_GET['id'] ?? null;
        if (!$transactionId) {
            echo "ID Transaksi tidak ditemukan.";
            exit();
        }

        $stmt = $db->prepare(
            "SELECT t.*, e.name as employee_name
             FROM transactions t
             JOIN employees e ON t.employee_id = e.id
             WHERE t.id = ?"
        );
        $stmt->execute([$transactionId]);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            echo "Transaksi tidak ditemukan.";
            exit();
        }

        $stmt = $db->prepare(
            "SELECT ti.*, p.name as product_name
             FROM transaction_items ti
             JOIN products p ON ti.product_id = p.id
             WHERE ti.transaction_id = ?"
        );
        $stmt->execute([$transactionId]);
        $items = $stmt->fetchAll();

        render_view('pos/receipt', 'receipt', [
            'title' => 'Struk Transaksi',
            'transaction' => $transaction,
            'items' => $items
        ]);
        break;

    case '/reports':
         if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');

        $startDateQuery = $startDate . ' 00:00:00';
        $endDateQuery = $endDate . ' 23:59:59';

        // Ambil detail transaksi (semua status untuk ditampilkan di tabel)
        $stmt = $db->prepare(
            "SELECT t.*, e.name as employee_name
             FROM transactions t
             JOIN employees e ON t.employee_id = e.id
             WHERE t.transaction_date BETWEEN ? AND ?
             ORDER BY t.transaction_date DESC"
        );
        $stmt->execute([$startDateQuery, $endDateQuery]);
        $transactions = $stmt->fetchAll();

        // Ambil summary (hanya dari transaksi yang statusnya 'Completed')
        $stmt = $db->prepare(
            "SELECT
                SUM(final_amount) as total_revenue,
                COUNT(id) as total_transactions,
                (SELECT SUM(quantity)
                 FROM transaction_items
                 WHERE transaction_id IN (
                    SELECT id FROM transactions
                    WHERE transaction_date BETWEEN ? AND ? AND status = 'Completed'
                 )) as total_items_sold
             FROM transactions
             WHERE transaction_date BETWEEN ? AND ? AND status = 'Completed'"
        );
        $stmt->execute([$startDateQuery, $endDateQuery, $startDateQuery, $endDateQuery]);
        $summary = $stmt->fetch();

        render_view('reports/sales', 'app', [
            'title' => 'Laporan Penjualan',
            'transactions' => $transactions,
            'summary' => $summary,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        break;

    case '/reports/transactions':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }

        $stmt = $db->query("SELECT * FROM transactions ORDER BY transaction_date DESC");
        $transactions = $stmt->fetchAll();

        render_view('reports/transactions', 'app', [
            'title' => 'Manajemen Transaksi',
            'transactions' => $transactions
        ]);
        break;

    case '/void-transaction':
        if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $transactionId = $_POST['transaction_id'];
            $stmt = $db->prepare("UPDATE transactions SET status = 'Voided' WHERE id = ? AND status = 'Completed'");
            $stmt->execute([$transactionId]);
            header('Location: /reports/transactions');
            exit();
        }
        http_response_code(403);
        echo "Akses Ditolak";
        break;

    case '/refund-transaction':
        if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $transactionId = $_POST['transaction_id'];
            $stmt = $db->prepare("UPDATE transactions SET status = 'Refunded' WHERE id = ? AND status = 'Completed'");
            $stmt->execute([$transactionId]);
            header('Location: /reports/transactions');
            exit();
        }
        http_response_code(403);
        echo "Akses Ditolak";
        break;

    case '/settings':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }

        if ($method === 'POST') {
            $tax_rate = floatval($_POST['tax_rate']) / 100;
            $discount_rate = floatval($_POST['discount_rate']) / 100;

            $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key = ?");
            $stmt->execute([$tax_rate, 'tax_rate']);
            $stmt->execute([$discount_rate, 'discount_rate']);

            header('Location: /settings?success=1');
            exit();
        }

        $settings = get_settings($db);
        render_view('admin/settings', 'app', [
            'title' => 'Pengaturan',
            'settings' => $settings
        ]);
        break;

    // --- MANAJEMEN PRODUK & RESEP ---

    case '/products/manage':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }
        $stmt = $db->query("SELECT * FROM products ORDER BY name ASC");
        $products = $stmt->fetchAll();
        render_view('admin/manage_products', 'app', [
            'title' => 'Manajemen Produk',
            'products' => $products
        ]);
        break;

    case '/inventory/products/toggle':
        if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $productId = $_POST['product_id'];
            $stmt = $db->prepare("UPDATE products SET is_active = 1 - is_active WHERE id = ?");
            $stmt->execute([$productId]);
            header('Location: /products/manage');
            exit();
        }
        http_response_code(403);
        echo "Akses Ditolak";
        break;

    case '/inventory/recipe/manage':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403); exit();
        }
        $productId = $_GET['product_id'] ?? null;
        if (!$productId) { echo "ID Produk tidak valid."; exit(); }

        $stmtProduct = $db->prepare("SELECT * FROM products WHERE id = ? AND is_recipe = 1");
        $stmtProduct->execute([$productId]);
        $product = $stmtProduct->fetch();

        if (!$product) { echo "Produk resep tidak ditemukan."; exit(); }

        $stmtRecipe = $db->prepare(
            "SELECT pr.id as recipe_id, pr.quantity_used, i.name as ingredient_name, i.unit
             FROM product_recipes pr
             JOIN ingredients i ON pr.ingredient_id = i.id
             WHERE pr.product_id = ?"
        );
        $stmtRecipe->execute([$productId]);
        $recipe_items = $stmtRecipe->fetchAll();

        $all_ingredients = $db->query("SELECT * FROM ingredients ORDER BY name ASC")->fetchAll();

        render_view('admin/manage_recipe', 'app', [
            'title' => "Atur Resep: " . htmlspecialchars($product['name']),
            'product' => $product,
            'recipe_items' => $recipe_items,
            'all_ingredients' => $all_ingredients
        ]);
        break;

    case '/inventory/recipe/add':
        if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $productId = $_POST['product_id'];
            $ingredientId = $_POST['ingredient_id'];
            $quantityUsed = $_POST['quantity_used'];

            $stmt = $db->prepare("INSERT INTO product_recipes (product_id, ingredient_id, quantity_used) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $ingredientId, $quantityUsed]);

            $message = urlencode("Bahan baku berhasil ditambahkan ke resep.");
            header("Location: /inventory/recipe/manage?product_id=$productId&success_message=$message");
            exit();
        }
        http_response_code(403);
        echo "Akses Ditolak";
        break;

    case '/inventory/recipe/remove':
         if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $recipeId = $_POST['recipe_id'];
            $productId = $_POST['product_id'];

            $stmt = $db->prepare("DELETE FROM product_recipes WHERE id = ?");
            $stmt->execute([$recipeId]);

            $message = urlencode("Bahan baku berhasil dihapus dari resep.");
            header("Location: /inventory/recipe/manage?product_id=$productId&success_message=$message");
            exit();
        }
        http_response_code(403);
        echo "Akses Ditolak";
        break;


    // --- MANAJEMEN INVENTARIS (BAHAN BAKU) ---

    case '/inventory/ingredients':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }
        $stmt = $db->query("SELECT * FROM ingredients ORDER BY name ASC");
        $ingredients = $stmt->fetchAll();
        render_view('admin/inventory_ingredients', 'app', [
            'title' => 'Stok Bahan Baku',
            'ingredients' => $ingredients
        ]);
        break;

    case '/inventory/ingredients/add':
        if ($method === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            try {
                $stmt = $db->prepare("INSERT INTO ingredients (name, stock_quantity, unit, low_stock_threshold) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['stock_quantity'], $_POST['unit'], $_POST['low_stock_threshold']]);
                
                $message = urlencode("Bahan baku '{$_POST['name']}' berhasil ditambahkan.");
                header("Location: /inventory/ingredients?success_message=$message");
                exit();
            } catch (Exception $e) {
                render_view('admin/add_ingredient', 'app', ['title' => 'Tambah Bahan Baku', 'error_message' => $e->getMessage()]);
            }
        } else {
             render_view('admin/add_ingredient', 'app', ['title' => 'Tambah Bahan Baku']);
        }
        break;


    default:
        http_response_code(404);
        render_view('error/404', 'app', ['title' => 'Halaman Tidak Ditemukan']);
        break;
}