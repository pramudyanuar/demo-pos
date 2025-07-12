# 🚀 Cara Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal:

## 📁 1. Inisialisasi Database

Buka terminal dan jalankan perintah berikut:

    cd database
    php init.php

Perintah ini akan menginisialisasi database yang dibutuhkan oleh aplikasi.

## 🌐 2. Jalankan Server Lokal

Setelah database siap, jalankan server lokal dengan perintah berikut:

    cd ..
    cd public
    php -S localhost:8000

Setelah itu, buka browser dan akses aplikasi melalui alamat:

http://localhost:8000

## ✅ Persyaratan

- PHP 7.4 atau lebih baru
- SQLite (jika menggunakan SQLite sebagai database)
