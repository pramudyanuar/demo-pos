<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Manajemen Produk</h2>
        <a href="/inventory/products/add" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            + Tambah Produk Baru
        </a>
    </div>

    <?php if (isset($_GET['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_GET['success_message']) ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama Produk</th>
                    <th class="py-2 px-4 border-b">Tipe Produk</th>
                    <th class="py-2 px-4 border-b">Stok</th>
                    <th class="py-2 px-4 border-b">Tampil di Kasir</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($product['name']) ?></td>
                    <td class="py-2 px-4 border-b text-center">
                        <?php if ($product['is_recipe']): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Resep
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Produk Jadi
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <?= $product['is_recipe'] ? '-' : $product['stock_quantity'] ?>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                         <form action="/inventory/products/toggle" method="POST" class="inline">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="<?= $product['is_active'] ? 'bg-green-500' : 'bg-gray-300' ?> relative inline-flex h-6 w-11 items-center rounded-full">
                                <span class="<?= $product['is_active'] ? 'translate-x-6' : 'translate-x-1' ?> inline-block h-4 w-4 transform rounded-full bg-white transition"></span>
                            </button>
                        </form>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <?php if ($product['is_recipe']): ?>
                            <a href="/inventory/recipe/manage?product_id=<?= $product['id'] ?>" class="text-green-600 hover:text-green-900 font-semibold">Atur Resep</a>
                        <?php else: ?>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Update Stok</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                 <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>