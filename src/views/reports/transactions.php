<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Manajemen Transaksi</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Tanggal</th>
                    <th class="py-2 px-4 border-b">Total</th>
                    <th class="py-2 px-4 border-b">Metode Bayar</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $tx): ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center"><?= $tx['id'] ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= date('d M Y H:i', strtotime($tx['transaction_date'])) ?></td>
                    <td class="py-2 px-4 border-b text-right">Rp <?= number_format($tx['final_amount']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($tx['payment_method']) ?></td>
                    <td class="py-2 px-4 border-b text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $tx['status'] === 'Completed' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $tx['status'] === 'Voided' ? 'bg-red-100 text-red-800' : '' ?>
                            <?= $tx['status'] === 'Refunded' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                        ">
                            <?= htmlspecialchars($tx['status']) ?>
                        </span>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <?php if ($tx['status'] === 'Completed'): ?>
                        <form action="/void-transaction" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin membatalkan transaksi ini?');">
                            <input type="hidden" name="transaction_id" value="<?= $tx['id'] ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900">Void</button>
                        </form>
                        <form action="/refund-transaction" method="POST" class="inline ml-2" onsubmit="return confirm('Anda yakin ingin me-refund transaksi ini?');">
                            <input type="hidden" name="transaction_id" value="<?= $tx['id'] ?>">
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">Refund</button>
                        </form>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                 <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data transaksi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>