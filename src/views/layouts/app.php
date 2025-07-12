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
                                <a href="/inventory" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Inventaris</a>
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