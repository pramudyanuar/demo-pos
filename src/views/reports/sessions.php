<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Laporan Sesi Kasir</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID Sesi</th>
                    <th class="py-2 px-4 border-b">Kasir</th>
                    <th class="py-2 px-4 border-b">Waktu Mulai</th>
                    <th class="py-2 px-4 border-b">Waktu Selesai</th>
                    <th class="py-2 px-4 border-b text-right">Modal Awal</th>
                    <th class="py-2 px-4 border-b text-right">Total Penjualan</th>
                    <th class="py-2 px-4 border-b text-right">Uang Akhir</th>
                    <th class="py-2 px-4 border-b text-right">Selisih</th>
                    <th class="py-2 px-4 border-b">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                <?php 
                    $discrepancy = 0;
                    if ($session['end_time']) {
                        $expected_cash = $session['starting_cash'] + $session['total_sales'];
                        $discrepancy = $session['ending_cash'] - $expected_cash;
                    }
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">
                        <a href="/reports?session_id=<?= $session['id'] ?>" class="text-blue-600 hover:underline">
                            #<?= $session['id'] ?>
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($session['employee_name']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= date('d M Y H:i', strtotime($session['start_time'])) ?></td>
                    <td class="py-2 px-4 border-b text-center">
                        <?= $session['end_time'] ? date('d M Y H:i', strtotime($session['end_time'])) : '<span class="text-gray-400">Aktif</span>' ?>
                    </td>
                    <td class="py-2 px-4 border-b text-right">Rp <?= number_format($session['starting_cash']) ?></td>
                    <td class="py-2 px-4 border-b text-right">Rp <?= number_format($session['total_sales']) ?></td>
                    <td class="py-2 px-4 border-b text-right">Rp <?= number_format($session['ending_cash']) ?></td>
                    <td class="py-2 px-4 border-b text-right font-semibold <?= $discrepancy > 0 ? 'text-green-600' : ($discrepancy < 0 ? 'text-red-600' : '') ?>">
                        Rp <?= number_format($discrepancy) ?>
                    </td>
                    <td class="py-2 px-4 border-b text-sm text-gray-600"><?= htmlspecialchars($session['notes']) ?></td>
                </tr>
                <?php endforeach; ?>
                 <?php if (empty($sessions)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">Tidak ada data sesi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>