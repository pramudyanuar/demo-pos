<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Manajemen Sesi Kasir</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'no_active_session'): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p class="font-bold">Sesi Tidak Ditemukan</p>
            <p>Anda harus memulai sesi kasir terlebih dahulu sebelum dapat mengakses halaman kasir.</p>
        </div>
    <?php endif; ?>

     <?php if (isset($_GET['success']) && $_GET['success'] == 'session_closed'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>Sesi kasir telah berhasil ditutup.</p>
        </div>
    <?php endif; ?>

    <?php if ($active_session): ?>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p class="font-bold">Anda memiliki sesi yang sedang aktif.</p>
            <p>Mulai pada: <?= date('d M Y H:i', strtotime($active_session['start_time'])) ?></p>
            <p>Modal Awal: Rp <?= number_format($active_session['starting_cash']) ?></p>
            <div class="mt-4">
                <a href="/" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Lanjutkan ke Kasir</a>
                <a href="/session/close" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 ml-2">Tutup Sesi</a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center">
            <p class="text-gray-600 mb-4">Tidak ada sesi aktif. Silakan mulai sesi baru untuk memulai transaksi.</p>
            <a href="/session/start" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 text-lg font-semibold">
                Mulai Sesi Baru
            </a>
        </div>
    <?php endif; ?>

</div>