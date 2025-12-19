import './bootstrap';

// Price formatting
function formatPrice(n) {
    return new Intl.NumberFormat("en-EG", {
        style: "currency",
        currency: "EGP",
        minimumFractionDigits: 2
    }).format(n);
}

// Cart management
let cartData = null;
let isLoadingCart = false;

async function loadCart() {
    if (isLoadingCart) return; // Prevent multiple simultaneous loads
    isLoadingCart = true;

    try {
        const response = await fetch('/cart', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        cartData = await response.json();
        updateCartUI();
    } catch (error) {
        console.error('Error loading cart:', error);
    } finally {
        isLoadingCart = false;
    }
}

function updateCartUI() {
    if (!cartData) {
        console.log('No cart data available');
        return;
    }

    const container = document.getElementById('cartItemsContainer');
    const emptyMsg = document.getElementById('cartEmptyMsg');
    const cartCount = document.getElementById('cartCount');
    const subtotalVal = document.getElementById('subtotalVal');
    const shippingVal = document.getElementById('shippingVal');
    const totalVal = document.getElementById('totalVal');

    if (!container) {
        console.error('Cart container not found');
        return;
    }

    // Clear container
    container.innerHTML = '';

    if (!cartData.items || cartData.items.length === 0) {
        if (emptyMsg) {
            emptyMsg.classList.remove('hidden');
            container.appendChild(emptyMsg);
        }
        if (cartCount) {
            cartCount.classList.add('hidden');
        }
    } else {
        if (emptyMsg) {
            emptyMsg.classList.add('hidden');
        }
        if (cartCount) {
            cartCount.classList.remove('hidden');
            cartCount.textContent = cartData.count || 0;
        }

        // Add cart items
        cartData.items.forEach((item, index) => {
            if (!item) return;

            const itemDiv = document.createElement('div');
            itemDiv.className = 'cart-item';
            itemDiv.innerHTML = `
                <div class="cart-item-img">
                    <img src="${item.image || '/images/placeholder.png'}" alt="${item.name || 'Product'}" onerror="this.src='/images/placeholder.png'">
                </div>
                <div class="cart-item-main">
                    <div class="cart-item-top">${item.name || 'Product'}</div>
                    <div class="cart-item-sub">
                        ${formatPrice(item.price || 0)} • Size: ${item.size || 'One Size'}${item.color ? ' • Color: ' + item.color : ''}
                    </div>
                    <div class="qty-row">
                        <button class="qty-btn" onclick="updateCartQty(${index}, -1)">−</button>
                        <span>${item.quantity || 1}</span>
                        <button class="qty-btn" onclick="updateCartQty(${index}, 1)">+</button>
                    </div>
                </div>
                <div class="cart-item-side">
                    <div class="cart-item-price">${formatPrice(item.total || 0)}</div>
                    <button class="remove-btn" onclick="removeCartItem(${index})">Remove</button>
                </div>
            `;
            container.appendChild(itemDiv);
        });

        // Update totals
        if (subtotalVal) {
            subtotalVal.textContent = formatPrice(cartData.subtotal || 0);
        }
        if (shippingVal) {
            shippingVal.textContent = cartData.shipping === 0 ? 'Free' : formatPrice(cartData.shipping || 0);
        }
        if (totalVal) {
            totalVal.textContent = formatPrice(cartData.total || 0);
        }
    }
}

async function addToCartQuick(productId) {
    const product = await fetch(`/products/${productId}`).then(r => r.json());
    const defaultSize = product.variants?.[0]?.size || 'One Size';
    const defaultColor = product.variants?.[0]?.color || null;

    await addToCart(productId, defaultSize, defaultColor);
    openCart();
}

async function addToCart(productId, size, color) {
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                size: size,
                color: color,
                quantity: 1
            })
        });

        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
            return;
        }

        const data = await response.json();
        if (data.success) {
            await loadCart();
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
    }
}

async function updateCartQty(index, delta) {
    const item = cartData.items[index];
    const newQty = item.quantity + delta;
    if (newQty < 1) return;

    try {
        const response = await fetch(`/cart/${encodeURIComponent(item.key)}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: newQty })
        });

        cartData = await response.json();
        updateCartUI();
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

async function removeCartItem(index) {
    const item = cartData.items[index];

    try {
        const response = await fetch(`/cart/${encodeURIComponent(item.key)}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        cartData = await response.json();
        updateCartUI();
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}

function openCart() {
    document.getElementById('cartOverlay').classList.add('active');
    loadCart();
}

function closeCart() {
    document.getElementById('cartOverlay').classList.remove('active');
}

// Favorites
async function toggleFavorite(productId, button) {
    if (!document.querySelector('meta[name="user-authenticated"]')) {
        window.location.href = '/login?redirect=' + window.location.pathname;
        return;
    }

    const isFavorited = button.classList.contains('faved');
    const url = `/favorites/${productId}`;
    const method = isFavorited ? 'DELETE' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        if (data.success) {
            button.classList.toggle('faved');
            button.textContent = data.favorited ? '❤' : '♡';
        }
    } catch (error) {
        console.error('Error toggling favorite:', error);
    }
}

// Product detail modal
async function openProductDetail(productId) {
    try {
        const response = await fetch(`/products/${productId}`, {
            headers: {
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const content = doc.querySelector('.product-detail-content');
        if (content) {
            document.getElementById('productDetailContent').innerHTML = content.innerHTML;
            document.getElementById('productDetailOverlay').classList.add('active');
        }
    } catch (error) {
        console.error('Error loading product:', error);
    }
}

function closeProductDetail() {
    document.getElementById('productDetailOverlay').classList.remove('active');
}

// Customer form
function openCustomerForm() {
    document.getElementById('customerFormOverlay').classList.add('active');
}

function closeCustomerForm() {
    document.getElementById('customerFormOverlay').classList.remove('active');
}

// Filter functions
function filterByGender(gender) {
    const url = new URL(window.location);
    if (gender === 'all') {
        url.searchParams.delete('gender');
    } else {
        url.searchParams.set('gender', gender);
    }
    window.location.href = url.toString();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function () {
    const openCartBtn = document.getElementById('openCartBtn');
    const closeCartBtn = document.getElementById('closeCartBtn');
    const cartOverlay = document.getElementById('cartOverlay');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const checkoutForm = document.getElementById('checkoutForm');
    const customerForm = document.getElementById('customerForm');

    if (openCartBtn) openCartBtn.addEventListener('click', openCart);
    if (closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
    if (cartOverlay) {
        cartOverlay.addEventListener('click', function (e) {
            if (e.target === cartOverlay) closeCart();
        });
    }

    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location);
                if (this.value) {
                    url.searchParams.set('search', this.value);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 500);
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const customerData = JSON.parse(sessionStorage.getItem('customerData') || '{}');
            if (!customerData.name || !customerData.phone || !customerData.address) {
                openCustomerForm();
                if (customerData.name) {
                    document.querySelector('[name="customerName"]').value = customerData.name;
                    document.querySelector('[name="customerPhone"]').value = customerData.phone;
                    document.querySelector('[name="customerAddress"]').value = customerData.address;
                }
                return;
            }

            const formData = new FormData();
            formData.append('customer_name', customerData.name);
            formData.append('customer_phone', customerData.phone);
            formData.append('customer_address', customerData.address);
            formData.append('payment_method', document.querySelector('input[name="paymentMethod"]:checked').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            }).then(data => {
                if (data && data.errors) {
                    alert('Please fill in all required fields.');
                }
            });
        });
    }

    if (customerForm) {
        customerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const data = {
                name: this.customerName.value,
                phone: this.customerPhone.value,
                address: this.customerAddress.value
            };
            sessionStorage.setItem('customerData', JSON.stringify(data));
            closeCustomerForm();
            document.getElementById('checkoutForm').dispatchEvent(new Event('submit'));
        });
    }

    // Load cart on page load
    loadCart();
});

// CSRF Token refresh helper
function refreshCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'same-origin'
        }).then(() => {
            // Token will be updated automatically by Laravel
            console.log('CSRF token refreshed');
        }).catch(err => {
            console.error('Error refreshing CSRF token:', err);
        });
    }
}

// Make functions globally available
window.addToCartQuick = addToCartQuick;
window.addToCart = addToCart;
window.updateCartQty = updateCartQty;
window.removeCartItem = removeCartItem;
window.openCart = openCart;
window.closeCart = closeCart;
window.toggleFavorite = toggleFavorite;
window.openProductDetail = openProductDetail;
window.closeProductDetail = closeProductDetail;
window.openCustomerForm = openCustomerForm;
window.closeCustomerForm = closeCustomerForm;
window.filterByGender = filterByGender;
window.refreshCsrfToken = refreshCsrfToken;
