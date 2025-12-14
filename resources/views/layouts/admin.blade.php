<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - L&N Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: inherit;">
                    <span>Admin - L&N</span>
                </a>
            </div>
            <div class="nav-right">
                <a href="{{ route('home') }}" class="cart-btn nav-link-btn">
                    View Store
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="cart-btn" style="color: #fff; font-weight: 500;">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container" style="margin-top: 2rem;">
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" 
               class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                Categories
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                Products
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                Orders
            </a>
        </nav>

        @if(session('success'))
            <div style="background: #10b981; color: white; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #ef4444; color: white; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

