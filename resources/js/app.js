import './bootstrap';

// ==========================================
// PRICE FORMATTING
// ==========================================
function formatPrice(n) {
    return new Intl.NumberFormat("en-EG", {
        style: "currency",
        currency: "EGP",
        minimumFractionDigits: 2
    }).format(n);
}

// ==========================================
// CART MANAGEMENT - USING LOCALSTORAGE
// ==========================================
const CART_STORAGE_KEY = 'shopping_cart';

// Get cart from localStorage
function getCart() {
    try {
        const cart = localStorage.getItem(CART_STORAGE_KEY);
        return cart ? JSON.parse(cart) : [];
    } catch (error) {
        console.error('Error reading cart from localStorage:', error);
        return [];
    }
}

// Save cart to localStorage
function saveCart(cart) {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    } catch (error) {
        console.error('Error saving cart to localStorage:', error);
    }
}

// Calculate cart totals
function calculateCartTotals(cart) {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const shipping = subtotal > 100 ? 0 : 100;
    const total = subtotal + shipping;

    return { subtotal, shipping, total, count: cart.length };
}

// Load and display cart
function loadCart() {
    const cart = getCart();
    const totals = calculateCartTotals(cart);

    updateCartUI({
        items: cart,
        ...totals
    });
}

// Update cart UI
function updateCartUI(cartData) {
    const container = document.getElementById('cartItemsContainer');
    const emptyMsg = document.getElementById('cartEmptyMsg');
    const cartCount = document.getElementById('cartCount');
    const subtotalVal = document.getElementById('subtotalVal');
    const shippingVal = document.getElementById('shippingVal');
    const totalVal = document.getElementById('totalVal');

    if (!container) {
        return;
    }

    // Clear container
    container.innerHTML = '';

    if (!cartData.items || cartData.items.length === 0) {
        // Show empty message
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'cart-empty';
        emptyDiv.id = 'cartEmptyMsg';
        emptyDiv.textContent = 'Your cart is empty.';
        container.appendChild(emptyDiv);

        if (cartCount) {
            cartCount.classList.add('hidden');
        }
        if (subtotalVal) subtotalVal.textContent = formatPrice(0);
        if (shippingVal) shippingVal.textContent = '—';
        if (totalVal) totalVal.textContent = formatPrice(0);
    } else {
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
                    <img src="${item.image || ''}" alt="${item.name || 'Product'}" onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><rect fill=%22%23ddd%22 width=%22100%22 height=%22100%22/><text fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22>No Image</text></svg>'">
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
                    <div class="cart-item-price">${formatPrice((item.price || 0) * (item.quantity || 1))}</div>
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

// Add product to cart
async function addToCart(productId, size, color, quantity = 1) {
    console.log('addToCart called with:', { productId, size, color, quantity });

    try {
        // Fetch product details from server
        console.log('Fetching product from:', `/api/products/${productId}`);
        const response = await fetch(`/api/products/${productId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('Response status:', response.status);

        if (!response.ok) {
            throw new Error('Failed to fetch product');
        }

        const product = await response.json();
        console.log('Product received:', product);

        // Get current cart
        const cart = getCart();
        console.log('Current cart:', cart);

        // Create unique key for the item
        const itemKey = `${productId}_${size}_${color || 'none'}`;

        // Check if item already exists
        const existingIndex = cart.findIndex(item => item.key === itemKey);

        if (existingIndex !== -1) {
            // Update quantity
            cart[existingIndex].quantity += quantity;
        } else {
            // Add new item
            cart.push({
                key: itemKey,
                product_id: productId,
                name: product.name_en || product.name,
                image: product.image || '/images/placeholder.svg',
                price: product.base_price || product.price,
                size: size,
                color: color,
                quantity: quantity
            });
        }

        // Save to localStorage
        saveCart(cart);
        console.log('Cart saved:', cart);

        // Update UI
        loadCart();

        return { success: true };

    } catch (error) {
        console.error('Error adding to cart:', error);
        return { success: false, error: error.message };
    }
}

// Quick add to cart
async function addToCartQuick(productId) {
    try {
        const response = await fetch(`/api/products/${productId}`, {
            headers: { 'Accept': 'application/json' }
        });
        const product = await response.json();

        const defaultSize = product.variants?.[0]?.size || 'One Size';
        const defaultColor = product.variants?.[0]?.color || null;

        await addToCart(productId, defaultSize, defaultColor);
        openCart();
    } catch (error) {
        console.error('Error quick adding to cart:', error);
    }
}

// Update cart quantity - INSTANT UPDATE!
function updateCartQty(index, delta) {
    const cart = getCart();

    if (!cart[index]) return;

    const newQty = cart[index].quantity + delta;

    if (newQty < 1) {
        // Remove item if quantity becomes 0
        cart.splice(index, 1);
    } else {
        cart[index].quantity = newQty;
    }

    // Save and update UI immediately
    saveCart(cart);
    loadCart();
}

// Remove cart item - INSTANT UPDATE!
function removeCartItem(index) {
    const cart = getCart();

    if (!cart[index]) return;

    cart.splice(index, 1);

    // Save and update UI immediately
    saveCart(cart);
    loadCart();
}

// Clear entire cart
function clearCart() {
    saveCart([]);
    loadCart();
}

// Open cart panel
function openCart() {
    const overlay = document.getElementById('cartOverlay');
    if (overlay) {
        overlay.classList.add('active');
        loadCart();
    }
}

// Close cart panel
function closeCart() {
    const overlay = document.getElementById('cartOverlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
}

// Get cart count
function getCartCount() {
    return getCart().length;
}

// ==========================================
// FAVORITES
// ==========================================
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

// ==========================================
// PRODUCT DETAIL MODAL
// ==========================================
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

// ==========================================
// CUSTOMER FORM
// ==========================================
function openCustomerForm() {
    document.getElementById('customerFormOverlay').classList.add('active');
}

function closeCustomerForm() {
    document.getElementById('customerFormOverlay').classList.remove('active');
}

// ==========================================
// FILTER FUNCTIONS
// ==========================================
function filterByGender(gender) {
    const url = new URL(window.location);
    if (gender === 'all') {
        url.searchParams.delete('gender');
    } else {
        url.searchParams.set('gender', gender);
    }
    window.location.href = url.toString();
}

// ==========================================
// EVENT LISTENERS
// ==========================================
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

            // Get cart from localStorage
            const cart = getCart();
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            let customerData = {};
            try {
                const stored = localStorage.getItem('customerData');
                if (stored) {
                    customerData = JSON.parse(stored);
                }
            } catch (err) {
                console.error('Error reading customer data:', err);
            }

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
            formData.append('cart', JSON.stringify(cart));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }).then(response => {
                if (response.redirected) {
                    // Clear cart after successful checkout
                    clearCart();
                    localStorage.removeItem('customerData');
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            }).then(data => {
                if (data && data.errors) {
                    alert('Please fill in all required fields.');
                } else if (data && data.success) {
                    clearCart();
                    localStorage.removeItem('customerData');
                }
            });
        });
    }

    if (customerForm) {
        customerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const data = {
                name: this.customerName.value.trim(),
                phone: this.customerPhone.value.trim(),
                address: this.customerAddress.value.trim()
            };
            try {
                localStorage.setItem('customerData', JSON.stringify(data));
            } catch (err) {
                console.error('Error saving customer data:', err);
            }
            closeCustomerForm();
            document.getElementById('checkoutForm').dispatchEvent(new Event('submit'));
        });
    }

    // Load cart on page load
    loadCart();

    // Update cart count in navbar
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const count = getCartCount();
        if (count > 0) {
            cartCount.classList.remove('hidden');
            cartCount.textContent = count;
        }
    }
});

// ==========================================
// MAKE FUNCTIONS GLOBALLY AVAILABLE
// ==========================================
window.addToCartQuick = addToCartQuick;
window.addToCart = addToCart;
window.updateCartQty = updateCartQty;
window.removeCartItem = removeCartItem;
window.clearCart = clearCart;
window.openCart = openCart;
window.closeCart = closeCart;
window.getCart = getCart;
window.getCartCount = getCartCount;
window.toggleFavorite = toggleFavorite;
window.openProductDetail = openProductDetail;
window.closeProductDetail = closeProductDetail;
window.openCustomerForm = openCustomerForm;
window.closeCustomerForm = closeCustomerForm;
window.filterByGender = filterByGender;

// Handle add to cart from product card
window.handleAddToCart = async function (productId, size, color, button) {
    const originalText = button.textContent;
    button.textContent = 'Adding...';
    button.disabled = true;

    try {
        await addToCart(productId, size, color || null, 1);
        button.textContent = 'Added ✓';
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
        }, 1500);
        openCart();
    } catch (error) {
        console.error('Error adding to cart:', error);
        button.textContent = 'Error!';
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
        }, 1500);
    }
};

// Handle add to cart from product detail page
window.handleAddToCartFromDetail = async function (productId) {
    const button = document.getElementById('addToCartBtn');
    const originalText = button ? button.textContent : 'Add to cart';

    // Get selected size and color from the page
    const selectedSizeEl = document.querySelector('#pdSizes .size-pill.active');
    const selectedColorEl = document.querySelector('#pdColors .color-pill.active');

    const size = selectedSizeEl ? selectedSizeEl.textContent.trim() : 'One Size';
    const color = selectedColorEl ? selectedColorEl.textContent.trim() : null;

    if (button) {
        button.textContent = 'Adding...';
        button.disabled = true;
    }

    try {
        await addToCart(productId, size, color, 1);
        if (button) {
            button.textContent = 'Added ✓';
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 1500);
        }
        openCart();
    } catch (error) {
        console.error('Error adding to cart:', error);
        if (button) {
            button.textContent = 'Error!';
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 1500);
        }
    }
};

