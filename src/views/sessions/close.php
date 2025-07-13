<div class="flex justify-center">
    <div class="w-full max-w-lg">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Tutup Sesi Kasir</h2>
            
            <div class="bg-gray-50 p-4 rounded-md mb-6 space-y-2">
                <h3 class="text-lg font-semibold border-b pb-2">Ringkasan Sesi</h3>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu Mulai:</span>
                    <span class="font-medium"><?= date('d M Y H:i', strtotime($session['start_time'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Modal Awal:</span>
                    <span class="font-medium">Rp <?= number_format($session['starting_cash']) ?></span>
                </div>
                <div class="flex justify-between text-blue-600">
                    <span class="font-bold">Perkiraan Uang di Laci:</span>
                    <span class="font-bold">Rp <?= number_format($expected_cash) ?></span>
                </div>
                 <p class="text-xs text-gray-500 pt-2">Perkiraan dihitung dari Modal Awal + Total Penjualan Tunai.</p>
            </div>

            <form method="POST" action="/session/close">
                <div class="mb-4">
                    <label for="ending_cash" class="block text-sm font-medium text-gray-700">Jumlah Uang Tunai Aktual</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="ending_cash" id="ending_cash"
                            class="block w-full rounded-md border-gray-300 pl-10 pr-4 py-2 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="0.00" required step="1000">
                    </div>
                     <p class="text-xs text-gray-500 mt-1">Hitung dan masukkan jumlah uang tunai fisik yang ada di laci kasir sekarang.</p>
                </div>
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" 
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"
                        placeholder="Contoh: Ada selisih karena..."></textarea>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        onclick="return confirm('Anda yakin ingin menutup sesi ini? Tindakan ini tidak dapat dibatalkan.')"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Konfirmasi dan Tutup Sesi
                    </button>
                     <a href="/" class="block text-center mt-4 text-sm text-gray-600 hover:text-gray-800">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>