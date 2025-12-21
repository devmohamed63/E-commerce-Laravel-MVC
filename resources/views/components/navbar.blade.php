<header class="navbar">
    <div class="navbar-inner">
        <div class="brand">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">
                <img src="/images/locally-logo.png" alt="Locally" style="width: 90px; height: 90px; object-fit: contain;">
            </a>
        </div>

        <div class="nav-right">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input
                    id="searchInput"
                    class="search-input"
                    placeholder="Search clothes, colors…"
                    value="{{ request('search') }}"
                />
            </div>

            <select id="sortSelect" class="sort-select">
                <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High → Low</option>
                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
            </select>

            @auth
                <button class="cart-btn" id="openCartBtn">
                    <span>Cart</span>
                    <span class="cart-count {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}" id="cartCount">{{ $cartCount ?? 0 }}</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="cart-btn nav-link-btn">
                    <span>Cart</span>
                </a>
            @endauth

            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="cart-btn nav-link-btn">
                        Admin
                    </a>
                @endif
                <a href="{{ route('favorites.index') }}" class="cart-btn nav-link-btn">
                    Favorites
                </a>
                <a href="{{ route('orders.index') }}" class="cart-btn nav-link-btn">
                    Orders
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="cart-btn nav-link-btn" style="background: var(--bg-accent); border: 0; cursor: pointer;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="cart-btn nav-link-btn">
                    Login
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="cart-btn nav-link-btn">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>

