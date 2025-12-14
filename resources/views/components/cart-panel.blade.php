<div class="cart-panel-overlay" id="cartOverlay">
    <div class="cart-panel">
        <div class="cart-head">
            <span>Your Cart</span>
            <button class="close-cart-btn" id="closeCartBtn">✕</button>
        </div>
        <div class="cart-body" id="cartItemsContainer">
            <div class="cart-empty" id="cartEmptyMsg">Your cart is empty.</div>
        </div>
        <div class="cart-foot">
            <div class="sum-row">
                <span>Subtotal</span>
                <span id="subtotalVal">EGP 0.00</span>
            </div>
            <div class="sum-row">
                <span>Shipping</span>
                <span id="shippingVal">—</span>
            </div>
            <div class="sum-row total">
                <span>Total</span>
                <span id="totalVal">EGP 0.00</span>
            </div>
            <div class="payment-methods">
                <div class="payment-title">Payment method</div>
                <label class="payment-option">
                    <input type="radio" name="paymentMethod" value="cod" checked>
                    <span>Cash on delivery</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="paymentMethod" value="card">
                    <span>Card (Visa / MasterCard)</span>
                </label>
            </div>
            <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <button type="submit" class="checkout-btn" id="checkoutBtn">Checkout</button>
            </form>
        </div>
    </div>
</div>

