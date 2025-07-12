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

    CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL,
        cost REAL NOT NULL,
        image TEXT
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
    SQL;

    // Eksekusi pembuatan tabel
    $db->exec($schema);
    echo "Struktur tabel berhasil dibuat.\n";

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

    // Masukkan Products
    $products = [
        ['Kopi Hitam', 12000, 5000, 'https://picsum.photos/seed/kopi_hitam/150'],
        ['Kopi Susu', 15000, 7000, 'https://picsum.photos/seed/kopi_susu/150'],
        ['Teh Manis', 8000, 3000, 'https://picsum.photos/seed/teh_manis/150'],
        ['Croissant', 18000, 9000, 'https://picsum.photos/seed/croissant/150'],
        ['Roti Bakar', 16000, 8000, 'https://picsum.photos/seed/roti_bakar/150'],
        ['Air Mineral', 5000, 2000, 'https://picsum.photos/seed/air_mineral/150'],
        ['Jus Jeruk', 15000, 7000, 'https://picsum.photos/seed/jus_jeruk/150'],
        ['Nasi Goreng', 25000, 12000, 'https://picsum.photos/seed/nasi_goreng/150']
    ];
    $stmt = $db->prepare("INSERT INTO products (name, price, cost, image) VALUES (?, ?, ?, ?)");
    foreach ($products as $product) {
        $stmt->execute($product);
    }
    echo "Data Products berhasil dimasukkan.\n";

    // Masukkan Settings
    $stmt = $db->prepare("INSERT INTO settings (key, value) VALUES (?, ?)");
    $stmt->execute(['tax_rate', '0.11']); // Pajak 11%
    $stmt->execute(['discount_rate', '0.10']); // Diskon 10%
    echo "Data Settings berhasil dimasukkan.\n";


    echo "\nDatabase berhasil dibuat dan diinisialisasi dengan benar.\n";

} catch (PDOException $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}