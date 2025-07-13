<div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Mulai Sesi Kasir Baru</h2>
            <form method="POST" action="/session/start">
                <div class="mb-4">
                    <label for="starting_cash" class="block text-sm font-medium text-gray-700">Modal Awal (Tunai)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="starting_cash" id="starting_cash"
                            class="block w-full rounded-md border-gray-300 pl-10 pr-4 py-2 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="0.00" required step="1000">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah uang tunai yang ada di laci kasir saat memulai sesi.</p>
                </div>
                <div class="mt-6">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mulai Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>