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

switch ($requestUri) {
    case '/':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        $stmt = $db->query("SELECT * FROM products ORDER BY name ASC");
        $products = $stmt->fetchAll();
        render_view('pos/index', 'app', ['title' => 'Kasir', 'products' => $products]);
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

                // 1. Insert ke tabel transactions
                $stmt = $db->prepare("INSERT INTO transactions (employee_id, final_amount, payment_method) VALUES (?, ?, ?)");
                $stmt->execute([$employeeId, $totalAmount, $paymentMethod]);
                $transactionId = $db->lastInsertId();

                // 2. Insert ke tabel transaction_items
                $stmt = $db->prepare("INSERT INTO transaction_items (transaction_id, product_id, quantity, price_per_item, total_price) VALUES (?, ?, ?, ?, ?)");
                foreach ($cartData as $productId => $item) {
                    $stmt->execute([
                        $transactionId,
                        $productId,
                        $item['quantity'],
                        $item['price'],
                        $item['price'] * $item['quantity']
                    ]);
                }
                
                $db->commit();
                
                // Redirect ke halaman sukses atau kembali ke kasir dengan pesan
                echo "<script>alert('Transaksi Berhasil!'); window.location.href='/';</script>";

            } catch (Exception $e) {
                $db->rollBack();
                echo "Gagal memproses transaksi: " . $e->getMessage();
            }
        }
        break;

    case '/reports':
         if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { // Hanya manager
            http_response_code(403);
            echo "Akses Ditolak";
            exit();
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');

        $startDateQuery = $startDate . ' 00:00:00';
        $endDateQuery = $endDate . ' 23:59:59';
        
        // Ambil detail transaksi
        $stmt = $db->prepare(
            "SELECT t.*, e.name as employee_name 
             FROM transactions t
             JOIN employees e ON t.employee_id = e.id
             WHERE t.transaction_date BETWEEN ? AND ?
             ORDER BY t.transaction_date DESC"
        );
        $stmt->execute([$startDateQuery, $endDateQuery]);
        $transactions = $stmt->fetchAll();

        // Ambil summary
        $stmt = $db->prepare(
            "SELECT 
                SUM(final_amount) as total_revenue, 
                COUNT(id) as total_transactions,
                (SELECT SUM(quantity) FROM transaction_items WHERE transaction_id IN (SELECT id FROM transactions WHERE transaction_date BETWEEN ? AND ?)) as total_items_sold
             FROM transactions 
             WHERE transaction_date BETWEEN ? AND ?"
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

    default:
        http_response_code(404);
        echo "<h1>404 Page Not Found</h1>";
        break;
}