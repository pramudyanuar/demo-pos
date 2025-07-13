<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Tambah Produk Baru</h2>

    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($error_message) ?></span>
        </div>
    <?php endif; ?>

    <form action="/inventory/products/add" method="POST" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
            <input type="number" name="price" id="price" min="0" step="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="cost" class="block text-sm font-medium text-gray-700">Biaya (Modal)</label>
            <input type="number" name="cost" id="cost" min="0" step="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stok Awal</label>
            <input type="number" name="stock_quantity" id="stock_quantity" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Ambang Batas Stok Rendah</label>
            <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
         <div>
            <label for="image" class="block text-sm font-medium text-gray-700">URL Gambar (Opsional)</label>
            <input type="url" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
        </div>
        <div>
            <input type="hidden" name="is_recipe" value="0">
        </div>

        <div class="flex justify-end space-x-4">
             <a href="/inventory/products" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan Produk</button>
        </div>
    </form>
</div>