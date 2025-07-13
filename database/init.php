<?php

try {
    $dbFile = 'pos.sqlite';
    // Hapus file database lama jika ada, untuk memastikan inisialisasi bersih
    if (file_exists($dbFile)) {
        unlink($dbFile);
    }

    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Skema tabel
    $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    );

    CREATE TABLE IF NOT EXISTS employees (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role_id INTEGER,
        is_active INTEGER DEFAULT 1,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    );

    -- Modifikasi Tabel Products: Tambah kolom is_active
    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL,
        cost REAL NOT NULL,
        stock_quantity INTEGER NOT NULL DEFAULT 0,
        low_stock_threshold INTEGER NOT NULL DEFAULT 10,
        is_recipe INTEGER DEFAULT 0,
        image TEXT,
        is_active INTEGER DEFAULT 1 -- 1 untuk aktif (tampil di kasir), 0 untuk nonaktif
    );

    -- Tabel Baru: Bahan Baku (untuk resep)
    CREATE TABLE IF NOT EXISTS ingredients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        stock_quantity REAL NOT NULL DEFAULT 0,
        unit TEXT NOT NULL, -- Contoh: gram, ml, pcs
        low_stock_threshold REAL NOT NULL DEFAULT 100
    );

    -- Tabel Baru: Resep Produk (menghubungkan produk dengan bahan baku)
    CREATE TABLE IF NOT EXISTS product_recipes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        ingredient_id INTEGER NOT NULL,
        quantity_used REAL NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id),
        FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
    );

    CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        employee_id INTEGER NOT NULL,
        final_amount REAL NOT NULL,
        payment_method TEXT NOT NULL,
        status TEXT DEFAULT 'Completed',
        transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES employees(id)
    );

    CREATE TABLE IF NOT EXISTS transaction_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        transaction_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        price_per_item REAL NOT NULL,
        total_price REAL NOT NULL,
        FOREIGN KEY (transaction_id) REFERENCES transactions(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL
    );

    -- Tabel Baru: Pemasok (Supplier)
    CREATE TABLE IF NOT EXISTS suppliers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        contact_person TEXT,
        phone TEXT,
        email TEXT,
        address TEXT
    );

    -- Tabel Baru: Pembelian dari Pemasok
    CREATE TABLE IF NOT EXISTS purchases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        supplier_id INTEGER,
        purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        total_cost REAL,
        status TEXT NOT NULL, -- Contoh: Pending, Completed
        notes TEXT,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
    );

    -- Tabel Baru: Detail item pada Pembelian
    CREATE TABLE IF NOT EXISTS purchase_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        purchase_id INTEGER NOT NULL,
        item_id INTEGER NOT NULL, -- Bisa product_id atau ingredient_id
        item_type TEXT NOT NULL, -- 'product' or 'ingredient'
        quantity INTEGER NOT NULL,
        cost_per_item REAL NOT NULL,
        FOREIGN KEY (purchase_id) REFERENCES purchases(id)
    );
    SQL;

    // Eksekusi pembuatan tabel
    $db->exec($schema);
    echo "Struktur tabel berhasil dibuat/diperbarui dengan kolom is_active.\n";

    // Masukkan Roles
    $stmt = $db->prepare("INSERT INTO roles (name) VALUES (?)");
    $stmt->execute(['Manager']);
    $stmt->execute(['Kasir']);
    echo "Data Roles berhasil dimasukkan.\n";

    // Masukkan Employees
    $adminPassword = password_hash('password123', PASSWORD_DEFAULT);
    $kasirPassword = password_hash('password123', PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO employees (name, username, password, role_id) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Admin Manager', 'admin', $adminPassword, 1]);
    $stmt->execute(['Kasir Pagi', 'kasir1', $kasirPassword, 2]);
    echo "Data Employees berhasil dimasukkan.\n";

    // Masukkan Products dengan data stok awal dan status aktif
    $products = [
        ['Kopi Hitam', 12000, 5000, 50, 10, 1, 'https://picsum.photos/seed/kopi_hitam/150', 1],
        ['Kopi Susu', 15000, 7000, 50, 10, 1, 'https://picsum.photos/seed/kopi_susu/150', 1],
        ['Teh Manis', 8000, 3000, 100, 20, 1, 'https://picsum.photos/seed/teh_manis/150', 1],
        ['Croissant', 18000, 9000, 80, 15, 0, 'https://picsum.photos/seed/croissant/150', 1],
        ['Roti Bakar', 16000, 8000, 70, 15, 0, 'https://picsum.photos/seed/roti_bakar/150', 1],
        ['Air Mineral', 5000, 2000, 200, 50, 0, 'https://picsum.photos/seed/air_mineral/150', 1],
        ['Jus Jeruk', 15000, 7000, 60, 10, 1, 'https://picsum.photos/seed/jus_jeruk/150', 1],
        ['Nasi Goreng', 25000, 12000, 40, 10, 1, 'https://picsum.photos/seed/nasi_goreng/150', 1]
    ];
    $stmt = $db->prepare("INSERT INTO products (name, price, cost, stock_quantity, low_stock_threshold, is_recipe, image, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($products as $product) {
        $stmt->execute($product);
    }
    echo "Data Products berhasil dimasukkan.\n";

    // Masukkan Bahan Baku
    $ingredients = [
        ['Biji Kopi', 1000, 'gram', 200],
        ['Susu UHT', 5000, 'ml', 1000],
        ['Gula Pasir', 5000, 'gram', 1000],
        ['Daun Teh', 500, 'gram', 100],
        ['Buah Jeruk', 200, 'pcs', 50]
    ];
    $stmt = $db->prepare("INSERT INTO ingredients (name, stock_quantity, unit, low_stock_threshold) VALUES (?, ?, ?, ?)");
    foreach ($ingredients as $ingredient) {
        $stmt->execute($ingredient);
    }
    echo "Data Ingredients berhasil dimasukkan.\n";

    // Definisikan Resep
    $db->exec("INSERT INTO product_recipes (product_id, ingredient_id, quantity_used) VALUES (1, 1, 10), (1, 3, 15)");
    $db->exec("INSERT INTO product_recipes (product_id, ingredient_id, quantity_used) VALUES (2, 1, 10), (2, 2, 100), (2, 3, 15)");
    echo "Data Resep berhasil dimasukkan.\n";

    // Masukkan Settings
    $stmt = $db->prepare("INSERT INTO settings (key, value) VALUES (?, ?)");
    $stmt->execute(['tax_rate', '0.11']);
    $stmt->execute(['discount_rate', '0.10']);
    echo "Data Settings berhasil dimasukkan.\n";

    echo "\nDatabase berhasil dibuat dan diinisialisasi dengan benar.\n";

} catch (PDOException $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}