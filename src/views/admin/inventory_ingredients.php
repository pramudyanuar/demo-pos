<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Stok Bahan Baku</h2>
        <a href="/inventory/ingredients/add" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            + Tambah Bahan Baku
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
                    <th class="py-2 px-4 border-b text-left">Nama Bahan Baku</th>
                    <th class="py-2 px-4 border-b">Stok Saat Ini</th>
                    <th class="py-2 px-4 border-b">Unit</th>
                    <th class="py-2 px-4 border-b">Ambang Batas Rendah</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ingredients as $item): ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= $item['stock_quantity'] ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($item['unit']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= $item['low_stock_threshold'] ?></td>
                    <td class="py-2 px-4 border-b text-center">
                        <?php if ($item['stock_quantity'] <= $item['low_stock_threshold']): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Stok Rendah
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aman
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Update Stok</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                 <?php if (empty($ingredients)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data bahan baku.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>