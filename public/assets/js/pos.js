// public/assets/js/pos.js
document.addEventListener("DOMContentLoaded", () => {
  const productList = document.getElementById("product-list");
  const cartItemsContainer = document.getElementById("cart-items");
  const subtotalEl = document.getElementById("subtotal");
  const discountEl = document.getElementById("discount");
  const taxEl = document.getElementById("tax");
  const totalEl = document.getElementById("total");
  const payButton = document.getElementById("btn-pay");
  const paymentModal = document.getElementById("payment-modal");
  const modalTotalEl = document.getElementById("modal-total");
  const cancelPaymentBtn = document.getElementById("cancel-payment-btn");
  const paymentForm = document.getElementById("payment-form");
  const cartDataInput = document.getElementById("cart_data_input");
  const totalAmountInput = document.getElementById("total_amount_input");
  const discountRateText = document.getElementById("discount-rate-text");
  const taxRateText = document.getElementById("tax-rate-text");

  let cart = {}; // { productId: { name, price, quantity } }

  // Ambil tarif dari variabel global 'settings' yang di-render oleh PHP
  const DISCOUNT_RATE = settings.discount_rate;
  const TAX_RATE = settings.tax_rate;

  // Set teks persentase, format ke 2 angka di belakang koma jika perlu
  discountRateText.textContent = (DISCOUNT_RATE * 100)
    .toFixed(2)
    .replace(/\.00$/, "");
  taxRateText.textContent = (TAX_RATE * 100).toFixed(2).replace(/\.00$/, "");

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

  // Event listener untuk tombol di dalam keranjang
  cartItemsContainer.addEventListener("click", (e) => {
    const target = e.target;
    const cartItem = target.closest(".cart-item");
    if (!cartItem) return;

    const id = cartItem.dataset.id;

    if (target.classList.contains("btn-decrease")) {
      cart[id].quantity--;
      if (cart[id].quantity <= 0) {
        delete cart[id];
      }
    } else if (target.classList.contains("btn-increase")) {
      cart[id].quantity++;
    } else if (target.classList.contains("btn-remove")) {
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

    const discount = subtotal * DISCOUNT_RATE;
    const taxedAmount = subtotal - discount;
    const tax = taxedAmount * TAX_RATE;
    const total = taxedAmount + tax;

    subtotalEl.textContent = `Rp ${subtotal.toLocaleString()}`;
    discountEl.textContent = `- Rp ${discount.toLocaleString()}`;
    taxEl.textContent = `Rp ${tax.toLocaleString()}`;
    totalEl.textContent = `Rp ${total.toLocaleString()}`;

    totalAmountInput.value = total;
  }

  // Modal pembayaran
  payButton.addEventListener("click", () => {
    const total = parseFloat(totalAmountInput.value);
    if (isNaN(total) || total <= 0) return;

    modalTotalEl.textContent = `Rp ${total.toLocaleString()}`;
    cartDataInput.value = JSON.stringify(cart);

    paymentModal.classList.remove("hidden");
  });

  cancelPaymentBtn.addEventListener("click", (e) => {
    e.preventDefault();
    paymentModal.classList.add("hidden");
  });

  // Panggil updateCart di awal untuk memastikan tampilan sesuai
  updateCart();
});
