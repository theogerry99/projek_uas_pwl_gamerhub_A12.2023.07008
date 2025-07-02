        let cart = []; // Array untuk menyimpan item di keranjang

        // Fungsi untuk memformat angka menjadi mata uang Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Fungsi untuk memperbarui tampilan keranjang
        function updateCartDisplay() {
            const cartItemsContainer = document.getElementById('cartItemsContainer');
            const cartItemCount = document.getElementById('cartItemCount');
            const cartTotalElement = document.getElementById('cartTotal');
            const emptyCartMessage = document.getElementById('emptyCartMessage');
            const cartHr = document.getElementById('cartHr');
            const cartTotalContainer = document.getElementById('cartTotalContainer');
            const checkoutButton = document.getElementById('checkoutButton');

            cartItemsContainer.innerHTML = ''; // Kosongkan kontainer keranjang
            let total = 0;

            if (cart.length === 0) {
                emptyCartMessage.style.display = 'block';
                cartHr.style.display = 'none';
                cartTotalContainer.style.display = 'none';
                checkoutButton.disabled = true;
            } else {
                emptyCartMessage.style.display = 'none';
                cartHr.style.display = 'block';
                cartTotalContainer.style.display = 'flex';
                checkoutButton.disabled = false;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    const cartItemHtml = `
                        <div class="cart-item d-flex justify-content-between align-items-center" data-id="${item.id}">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="${item.img}" alt="${item.name}" class="img-thumbnail" style="width: 80px;">
                                </div>
                                <div>
                                    <h6 class="mb-1">${item.name}</h6>
                                    <small class="text-muted">${formatRupiah(item.price)}/hari</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="input-group me-3" style="width: 120px;">
                                    <button class="btn btn-outline-secondary btn-decrease" type="button" data-id="${item.id}">-</button>
                                    <input type="text" class="form-control text-center cart-quantity" value="${item.quantity}" readonly>
                                    <button class="btn btn-outline-secondary btn-increase" type="button" data-id="${item.id}">+</button>
                                </div>
                                <span class="fw-bold me-3 item-subtotal">${formatRupiah(itemTotal)}</span>
                                <button class="btn btn-danger btn-sm btn-remove" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHtml);
                });
            }

            cartItemCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartTotalElement.textContent = formatRupiah(total);

            // Re-attach event listeners for quantity buttons and remove buttons
            attachCartEventListeners();
        }

        // Fungsi untuk menambahkan event listener ke tombol di keranjang
        function attachCartEventListeners() {
            document.querySelectorAll('.btn-increase').forEach(button => {
                button.onclick = (e) => {
                    const id = e.currentTarget.dataset.id;
                    const item = cart.find(i => i.id === id);
                    if (item) {
                        item.quantity++;
                        updateCartDisplay();
                    }
                };
            });

            document.querySelectorAll('.btn-decrease').forEach(button => {
                button.onclick = (e) => {
                    const id = e.currentTarget.dataset.id;
                    const item = cart.find(i => i.id === id);
                    if (item && item.quantity > 1) {
                        item.quantity--;
                        updateCartDisplay();
                    }
                };
            });

            document.querySelectorAll('.btn-remove').forEach(button => {
                button.onclick = (e) => {
                    const id = e.currentTarget.dataset.id;
                    cart = cart.filter(item => item.id !== id);
                    updateCartDisplay();
                };
            });
        }

document.addEventListener('DOMContentLoaded', function() {

    // Fungsi baru untuk menampilkan notifikasi dinamis
    function showDynamicNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        if (!container) return;

        // Buat elemen notifikasi baru
        const notif = document.createElement('div');
        notif.className = `notification-bar`;
        notif.setAttribute('data-type', type);
        notif.innerHTML = `<span>${message}</span><button class="notification-close" onclick="this.parentElement.remove()">&times;</button>`;

        // Tambahkan ke container
        container.appendChild(notif);

        // Hilangkan setelah beberapa detik
        setTimeout(() => {
            notif.classList.add('fade-out');
            notif.addEventListener('animationend', () => notif.remove());
        }, 4000); // Hilang setelah 4 detik
    }


    // Add to Cart Functionality (Backend-connected)
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;

            fetch('process/cart_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add&product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                // Ganti alert dengan notifikasi baru
                showDynamicNotification(data.message, data.success ? 'success' : 'error');

                if (data.success && data.cart_count !== undefined) {
                    const cartItemCount = document.getElementById('cartItemCount');
                    if(cartItemCount) {
                        cartItemCount.textContent = data.cart_count;
                        // Tambahkan efek animasi kecil saat jumlah diperbarui
                        cartItemCount.classList.add('animate__animated', 'animate__bounce');
                        cartItemCount.addEventListener('animationend', () => {
                            cartItemCount.classList.remove('animate__animated', 'animate__bounce');
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showDynamicNotification('Terjadi kesalahan saat menambahkan item.', 'error');
            });
        });
    });

    // ... (sisa kode script.js Anda) ...

});

            // Checkout Functionality
            document.getElementById('checkoutButton').addEventListener('click', function() {
                if (cart.length === 0) {
                    alert('Keranjang Anda kosong. Silakan tambahkan item terlebih dahulu.');
                    return;
                }

                let checkoutSummary = "Ringkasan Pesanan Anda:\n\n";
                let totalAmount = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    checkoutSummary += `- ${item.name} (x${item.quantity}) - ${formatRupiah(item.price)}/hari = ${formatRupiah(itemTotal)}\n`;
                    totalAmount += itemTotal;
                });

                checkoutSummary += `\nTotal Pembayaran: ${formatRupiah(totalAmount)}`;
                checkoutSummary += `\n\nTerima kasih telah berbelanja di GamerHub! (Ini adalah simulasi checkout)`;

                alert(checkoutSummary);
                
                // Opsional: Kosongkan keranjang setelah checkout
                cart = [];
                updateCartDisplay();
            });

            // Initial cart display update
            //updateCartDisplay();
    ;