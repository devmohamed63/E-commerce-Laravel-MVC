@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="background: #10b981; color: white; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid">
            <div class="hero-left">
                <h1>New Season. Fresh Fits.</h1>
                <p>Minimal looks, statement details. Curated for everyday confidence.</p>
                
                <div class="hero-cta">
                    <a href="{{ route('home', ['tag' => 'New', 'sort' => 'featured']) }}" class="hero-btn-primary">Shop New Arrivals</a>
                    <a href="{{ route('home') }}" class="hero-btn-secondary">Browse All Products</a>
                </div>

                <div class="gender-tabs">
                    <button class="gender-tab-btn {{ request('gender') === 'women' ? 'active' : '' }}" 
                            onclick="filterByGender('women')">Women</button>
                    <button class="gender-tab-btn {{ request('gender') === 'men' ? 'active' : '' }}" 
                            onclick="filterByGender('men')">Men</button>
                    <button class="gender-tab-btn {{ request('gender', 'all') === 'all' ? 'active' : '' }}" 
                            onclick="filterByGender('all')">All</button>
                </div>

                <div class="category-chips">
                    @foreach($categories as $category)
                        <a href="{{ route('home', ['category' => $category->name]) }}" 
                           class="chip-btn {{ request('category') === $category->name ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hero-right">
                <div class="quick-card">
                    <div class="quick-card-title">Quick Find</div>

                    <form method="GET" action="{{ route('home') }}" id="filterForm">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="gender" value="{{ request('gender', 'all') }}">
                        <input type="hidden" name="sort" value="{{ request('sort', 'featured') }}">

                        <div style="position:relative;margin-bottom:.75rem;">
                            <span class="search-icon" style="left:.75rem;">🔍</span>
                            <input
                                name="search"
                                class="input-full"
                                style="padding-left:2rem;"
                                placeholder="Search products..."
                                value="{{ request('search') }}"
                            />
                        </div>

                        <div class="flex-row" style="margin-bottom:.75rem;">
                            <div class="select-block">
                                <select name="category" class="select-full" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ request('category') === $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="select-block">
                                <select name="sort" class="select-full" onchange="this.form.submit()">
                                    <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                                    <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low → High</option>
                                    <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High → Low</option>
                                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
                                </select>
                            </div>
                        </div>

                        <div class="size-box">
                            <div class="field-label">
                                <span>Size</span>
                            </div>
                            <div class="sizes-grid">
                                @php
                                    $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', 'One Size'];
                                    $selectedSizes = request('size', []);
                                    if (!is_array($selectedSizes)) {
                                        $selectedSizes = $selectedSizes ? [$selectedSizes] : [];
                                    }
                                @endphp
                                @foreach($sizes as $size)
                                    <label class="size-item">
                                        <input type="checkbox" 
                                               name="size[]" 
                                               value="{{ $size }}"
                                               {{ in_array($size, $selectedSizes) ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                        <span>{{ $size }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section>
        <div class="products-header">
            <h2>Products</h2>
            <div class="stats-row">
                <span class="pill">{{ $products->total() }} items</span>
                @if(request('category') || request('gender') !== 'all' || request('size') || request('search'))
                    <a href="{{ route('home') }}" class="clear-btn">
                        <span class="x-icon">✕</span>
                        <span>Clear Filters</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid-products">
            @forelse($products as $product)
                @php
                    $isFavorited = in_array($product->id, $favorites ?? []);
                @endphp
                <x-product-card :product="$product" :favorited="$isFavorited" />
            @empty
                <div class="empty-box">No items match your filters.</div>
            @endforelse
        </div>

        <div class="pagination-wrapper" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; flex-wrap: wrap;">
            @if ($products->onFirstPage())
                <span style="padding: 0.75rem 1.5rem; background: #f3f4f6; color: #9ca3af; border-radius: var(--radius-md); cursor: not-allowed;">« Previous</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" style="padding: 0.75rem 1.5rem; background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border-col); border-radius: var(--radius-md); text-decoration: none; transition: all 0.2s;">« Previous</a>
            @endif

            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if ($page == $products->currentPage())
                    <span style="padding: 0.75rem 1rem; background: var(--bg-accent); color: #fff; border-radius: var(--radius-md); min-width: 45px; text-align: center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 0.75rem 1rem; background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border-col); border-radius: var(--radius-md); text-decoration: none; min-width: 45px; text-align: center; transition: all 0.2s;">{{ $page }}</a>
                @endif
            @endforeach

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" style="padding: 0.75rem 1.5rem; background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border-col); border-radius: var(--radius-md); text-decoration: none; transition: all 0.2s;">Next »</a>
            @else
                <span style="padding: 0.75rem 1.5rem; background: #f3f4f6; color: #9ca3af; border-radius: var(--radius-md); cursor: not-allowed;">Next »</span>
            @endif
        </div>
    </section>
@endsection

