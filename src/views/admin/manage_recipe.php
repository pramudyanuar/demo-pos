<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-1">Atur Resep untuk:</h2>
        <h3 class="text-xl text-gray-700 mb-4"><?= htmlspecialchars($product['name']) ?></h3>

        <?php if (isset($_GET['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <?= htmlspecialchars($_GET['success_message']) ?>
        </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Nama Bahan Baku</th>
                        <th class="py-2 px-4 border-b text-right">Jumlah Digunakan</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recipe_items)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Belum ada bahan baku di resep ini.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($recipe_items as $item): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($item['ingredient_name']) ?></td>
                        <td class="py-2 px-4 border-b text-right"><?= htmlspecialchars($item['quantity_used']) ?> <?= htmlspecialchars($item['unit']) ?></td>
                        <td class="py-2 px-4 border-b text-center">
                            <form action="/inventory/recipe/remove" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan ini dari resep?');">
                                <input type="hidden" name="recipe_id" value="<?= $item['recipe_id'] ?>">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Tambah Bahan Baku</h3>
        <form action="/inventory/recipe/add" method="POST" class="space-y-4">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

            <div>
                <label for="ingredient_id" class="block text-sm font-medium text-gray-700">Pilih Bahan Baku</label>
                <select name="ingredient_id" id="ingredient_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                    <option value="">-- Pilih Bahan --</option>
                    <?php foreach ($all_ingredients as $ingredient): ?>
                        <option value="<?= $ingredient['id'] ?>"><?= htmlspecialchars($ingredient['name']) ?> (Stok: <?= $ingredient['stock_quantity'] ?> <?= $ingredient['unit'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="quantity_used" class="block text-sm font-medium text-gray-700">Jumlah yang Digunakan</label>
                <input type="number" name="quantity_used" id="quantity_used" step="any" min="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                 <p class="text-xs text-gray-500 mt-1">Sesuaikan dengan satuan bahan baku (gram, ml, pcs, dll).</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Tambahkan ke Resep
            </button>
        </form>
    </div>
     <div class="md:col-span-3">
        <a href="/products/manage" class="text-indigo-600 hover:text-indigo-900">&larr; Kembali ke Manajemen Produk</a>
    </div>
</div>