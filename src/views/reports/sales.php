<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Laporan Penjualan</h2>

    <div class="mb-6">
        <form method="GET" action="/reports" class="flex items-end space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($startDate) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($endDate) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-800">Total Pendapatan</h3>
            <p class="text-2xl font-bold text-blue-900">Rp <?= number_format($summary['total_revenue'] ?? 0) ?></p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-green-800">Jumlah Transaksi</h3>
            <p class="text-2xl font-bold text-green-900"><?= $summary['total_transactions'] ?? 0 ?></p>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800">Produk Terjual</h3>
            <p class="text-2xl font-bold text-yellow-900"><?= $summary['total_items_sold'] ?? 0 ?></p>
        </div>
    </div>


    <h3 class="text-xl font-bold mb-2">Detail Transaksi</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID Transaksi</th>
                    <th class="py-2 px-4 border-b">Tanggal</th>
                    <th class="py-2 px-4 border-b">Kasir</th>
                    <th class="py-2 px-4 border-b">Total</th>
                    <th class="py-2 px-4 border-b">Metode Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $tx): ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center"><?= $tx['id'] ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= date('d M Y H:i', strtotime($tx['transaction_date'])) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($tx['employee_name']) ?></td>
                    <td class="py-2 px-4 border-b text-right">Rp <?= number_format($tx['final_amount']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($tx['payment_method']) ?></td>
                </tr>
                <?php endforeach; ?>
                 <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data untuk periode ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>