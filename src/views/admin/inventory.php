<div class="space-y-6">
    <?php if (!empty($lowStockItems)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <p class="font-bold">Peringatan Stok Menipis!</p>
        <p>Item berikut memiliki stok di bawah ambang batas:</p>
        <ul class="list-disc ml-6 mt-2">
            <?php foreach ($lowStockItems as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?> (Sisa: <?= $item['stock_quantity'] ?> <?= htmlspecialchars($item['unit']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>


    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Daftar Stok Barang & Bahan Baku</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b">Nama Item</th>
                        <th class="py-2 px-4 border-b">Tipe</th>
                        <th class="py-2 px-4 border-b">Stok Saat Ini</th>
                        <th class="py-2 px-4 border-b">Ambang Batas Rendah</th>
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($item['type']) ?></td>
                        <td class="py-2 px-4 border-b text-center">
                            <?= $item['stock_quantity'] ?> <?= htmlspecialchars($item['unit']) ?>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <?= $item['low_stock_threshold'] ?> <?= htmlspecialchars($item['unit']) ?>
                        </td>
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
                     <?php if (empty($inventory)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data inventaris.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>