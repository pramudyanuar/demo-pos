<div class="receipt-container bg-white p-6 rounded-lg shadow-lg">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">Nama Toko Anda</h1>
        <p class="text-sm">Jalan Alamat Toko No. 123, Kota Anda</p>
        <p class="text-sm">Telepon: (021) 1234 5678</p>
    </div>

    <div class="border-t border-b border-dashed py-2 mb-4 text-sm">
        <div class="flex justify-between">
            <span>No. Transaksi:</span>
            <span><?= htmlspecialchars($transaction['id']) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Tanggal:</span>
            <span><?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Kasir:</span>
            <span><?= htmlspecialchars($transaction['employee_name']) ?></span>
        </div>
    </div>

    <div>
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left py-1">Produk</th>
                    <th class="text-center py-1">Jml</th>
                    <th class="text-right py-1">Harga</th>
                    <th class="text-right py-1">Total</th>
                </tr>
            </thead>
            <tbody class="border-t border-b border-dashed">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="py-1"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="text-center py-1"><?= $item['quantity'] ?></td>
                    <td class="text-right py-1"><?= number_format($item['price_per_item']) ?></td>
                    <td class="text-right py-1"><?= number_format($item['total_price']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-2 border-t border-dashed text-sm">
        <div class="flex justify-between font-semibold">
            <span>Subtotal</span>
            <span>Rp <?= number_format($transaction['final_amount']) ?></span>
        </div>
        <div class="flex justify-between font-bold text-base mt-2">
            <span>TOTAL</span>
            <span>Rp <?= number_format($transaction['final_amount']) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Metode Bayar</span>
            <span><?= htmlspecialchars($transaction['payment_method']) ?></span>
        </div>
    </div>

    <div class="text-center mt-6 text-xs">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
    </div>

    <div class="no-print mt-8 flex justify-center gap-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            Cetak Struk
        </button>
        <a href="/" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
            Kembali ke POS
        </a>
    </div>
</div>