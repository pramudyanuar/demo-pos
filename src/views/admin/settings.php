<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Pengaturan Umum</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">Pengaturan berhasil disimpan.</span>
        </div>
    <?php endif; ?>

    <form method="POST" action="/settings" class="space-y-4">
        <div>
            <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tarif Pajak (%)</label>
            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" 
                   value="<?= htmlspecialchars(floatval($settings['tax_rate']) * 100) ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            <p class="text-xs text-gray-500 mt-1">Masukkan nilai dalam persen. Contoh: 11 untuk 11%.</p>
        </div>
        <div>
            <label for="discount_rate" class="block text-sm font-medium text-gray-700">Tarif Diskon (%)</label>
            <input type="number" name="discount_rate" id="discount_rate" step="0.01" min="0"
                   value="<?= htmlspecialchars(floatval($settings['discount_rate']) * 100) ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            <p class="text-xs text-gray-500 mt-1">Masukkan nilai dalam persen. Contoh: 10 untuk 10%.</p>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan Pengaturan</button>
    </form>
</div>