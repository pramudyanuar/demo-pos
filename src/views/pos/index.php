<div class="grid grid-cols-12 gap-4">
    <div class="col-span-12 lg:col-span-7 bg-white p-4 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Pilih Produk</h2>
        <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto">
            <?php foreach ($products as $product): ?>
            <div class="product-card border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-gray-100 hover:shadow-lg transition"
                 data-id="<?= $product['id'] ?>"
                 data-name="<?= htmlspecialchars($product['name']) ?>"
                 data-price="<?= $product['price'] ?>">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-24 h-24 object-cover mb-2 rounded">
                <h3 class="font-semibold text-center text-sm"><?= htmlspecialchars($product['name']) ?></h3>
                <p class="text-gray-600">Rp <?= number_format($product['price']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-5 bg-white p-4 rounded-lg shadow-md flex flex-col">
        <h2 class="text-xl font-bold mb-4">Keranjang</h2>
        <div id="cart-items" class="flex-grow min-h-[40vh] overflow-y-auto">
            <p class="text-gray-400 text-center mt-10">Keranjang masih kosong</p>
        </div>
        <div class="border-t mt-4 pt-4">
            <div class="flex justify-between text-lg">
                <span>Subtotal</span>
                <span id="subtotal">Rp 0</span>
            </div>
            <div class="flex justify-between text-lg font-bold mt-2">
                <span>Total</span>
                <span id="total">Rp 0</span>
            </div>
            <button id="btn-pay" class="w-full bg-blue-600 text-white p-4 rounded-lg mt-4 font-bold text-xl hover:bg-blue-700 disabled:bg-gray-400" disabled>
                BAYAR
            </button>
        </div>
    </div>
</div>

<div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3 text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Proses Pembayaran</h3>
        <div class="mt-2 px-7 py-3">
             <div class="text-left mb-4">
                <p class="text-xl font-bold">Total Tagihan:</p>
                <p id="modal-total" class="text-3xl font-bold text-blue-600">Rp 0</p>
             </div>
             <form id="payment-form" action="/process-payment" method="POST">
                <input type="hidden" name="cart_data" id="cart_data_input">
                <input type="hidden" name="total_amount" id="total_amount_input">

                <label for="payment_method" class="block text-sm font-medium text-gray-700 text-left">Metode Pembayaran</label>
                <select id="payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option>Tunai</option>
                    <option>Kartu Debit/Kredit</option>
                    <option>QRIS</option>
                </select>
        </div>
        <div class="items-center px-4 py-3">
            <button id="confirm-payment-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                Konfirmasi Pembayaran
            </button>
             <button id="cancel-payment-btn" class="mt-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300">
                Batal
            </button>
        </div>
        </form>
    </div>
  </div>
</div>
<script src="/assets/js/pos.js"></script>