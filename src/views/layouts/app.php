<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Jika tidak ada sesi user dan halaman bukan login, redirect ke login
if (!isset($_SESSION['user_id']) && !str_contains($_SERVER['REQUEST_URI'], '/login')) {
    header('Location: /login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS System' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-white font-bold">POS Kasir</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="/" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Kasir</a>
                                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) : // Hanya Manager ?>
                                <a href="/reports" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Laporan</a>
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium flex items-center">
                                        <span>Inventaris</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-1"
                                         class="absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg" style="display: none;">
                                        <a href="/products/manage" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manajemen Produk</a>
                                        <a href="/inventory/ingredients" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Stok Bahan Baku</a>
                                    </div>
                                </div>
                                <a href="/reports/transactions" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Manajemen Transaksi</a>
                                <a href="/settings" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Pengaturan</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                       <div class="ml-4 flex items-center md:ml-6">
                            <span class="text-gray-300 mr-4">Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?>!</span>
                            <a href="/logout" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Logout</a>
                       </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900"><?= $title ?? 'Dashboard' ?></h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <?php include $view; ?>
            </div>
        </main>
    </div>
</body>
</html>