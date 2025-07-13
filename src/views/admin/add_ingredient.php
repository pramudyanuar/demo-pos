<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Tambah Bahan Baku Baru</h2>

    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($error_message) ?></span>
        </div>
    <?php endif; ?>

    <form action="/inventory/ingredients/add" method="POST" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Bahan Baku</label>
            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stok Awal</label>
            <input type="number" name="stock_quantity" id="stock_quantity" min="0" step="any" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="unit" class="block text-sm font-medium text-gray-700">Satuan (Unit)</label>
            <input type="text" name="unit" id="unit" required placeholder="Contoh: gram, ml, pcs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Ambang Batas Stok Rendah</label>
            <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="0" step="any" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>

        <div class="flex justify-end space-x-4">
             <a href="/inventory/ingredients" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan Bahan Baku</button>
        </div>
    </form>
</div>