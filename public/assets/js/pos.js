// public/assets/js/pos.js
document.addEventListener("DOMContentLoaded", () => {
  const productList = document.getElementById("product-list");
  const cartItemsContainer = document.getElementById("cart-items");
  const subtotalEl = document.getElementById("subtotal");
  const totalEl = document.getElementById("total");
  const payButton = document.getElementById("btn-pay");
  const paymentModal = document.getElementById("payment-modal");
  const modalTotalEl = document.getElementById("modal-total");
  const cancelPaymentBtn = document.getElementById("cancel-payment-btn");
  const paymentForm = document.getElementById("payment-form");
  const cartDataInput = document.getElementById("cart_data_input");
  const totalAmountInput = document.getElementById("total_amount_input");

  let cart = {}; // { productId: { name, price, quantity } }

  // Menambahkan produk ke keranjang
  productList.addEventListener("click", (e) => {
    const card = e.target.closest(".product-card");
    if (!card) return;

    const id = card.dataset.id;
    const name = card.dataset.name;
    const price = parseFloat(card.dataset.price);

    if (cart[id]) {
      cart[id].quantity++;
    } else {
      cart[id] = { name, price, quantity: 1 };
    }
    updateCart();
  });

  // Event listener untuk tombol di dalam keranjang (tambah, kurang, hapus)
  cartItemsContainer.addEventListener("click", (e) => {
    const target = e.target;
    const id = target.closest(".cart-item").dataset.id;

    if (target.classList.contains("btn-increase")) {
      cart[id].quantity++;
    }
    if (target.classList.contains("btn-decrease")) {
      cart[id].quantity--;
      if (cart[id].quantity <= 0) {
        delete cart[id];
      }
    }
    if (target.classList.contains("btn-remove")) {
      delete cart[id];
    }
    updateCart();
  });

  function updateCart() {
    cartItemsContainer.innerHTML = "";
    let subtotal = 0;
    const productIds = Object.keys(cart);

    if (productIds.length === 0) {
      cartItemsContainer.innerHTML =
        '<p class="text-gray-400 text-center mt-10">Keranjang masih kosong</p>';
      payButton.disabled = true;
    } else {
      payButton.disabled = false;
      productIds.forEach((id) => {
        const item = cart[id];
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemEl = document.createElement("div");
        itemEl.className =
          "cart-item flex justify-between items-center p-2 border-b";
        itemEl.dataset.id = id;
        itemEl.innerHTML = `
                    <div>
                        <p class="font-semibold">${item.name}</p>
                        <p class="text-sm text-gray-500">Rp ${item.price.toLocaleString()}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="btn-decrease bg-gray-200 w-6 h-6 rounded-full">-</button>
                        <span>${item.quantity}</span>
                        <button class="btn-increase bg-gray-200 w-6 h-6 rounded-full">+</button>
                        <span class="w-20 text-right">Rp ${itemTotal.toLocaleString()}</span>
                        <button class="btn-remove text-red-500 hover:text-red-700">✖</button>
                    </div>
                `;
        cartItemsContainer.appendChild(itemEl);
      });
    }

    const total = subtotal; // Simpan pajak jika ada
    subtotalEl.textContent = `Rp ${subtotal.toLocaleString()}`;
    totalEl.textContent = `Rp ${total.toLocaleString()}`;
  }

  // Modal pembayaran
  payButton.addEventListener("click", () => {
    const total = Object.values(cart).reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    );
    modalTotalEl.textContent = `Rp ${total.toLocaleString()}`;

    // Populate form inputs
    cartDataInput.value = JSON.stringify(cart);
    totalAmountInput.value = total;

    paymentModal.classList.remove("hidden");
  });

  cancelPaymentBtn.addEventListener("click", (e) => {
    e.preventDefault();
    paymentModal.classList.add("hidden");
  });

  // Form submit ditangani oleh browser secara default (action="/process-payment" method="POST")
});
