@props(['product', 'favorited' => false])

@php
    // Get all product images
    $allImages = $product->images;
    // Get the primary image (is_primary = true), or fall back to first image
    $primaryImage = $allImages->where('is_primary', true)->first() 
                    ?? $allImages->first();
    $imagePath = $primaryImage ? asset($primaryImage->path) : asset('images/placeholder.png');
    $isFavorited = $favorited ?? false;
    if (!$isFavorited && auth()->check()) {
        $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
    }
    // Prepare images data for JavaScript
    $imagesData = $allImages->map(fn($img) => asset($img->path))->values()->toArray();
@endphp

<div class="card" data-product-id="{{ $product->id }}">
    <a href="{{ route('products.show', $product) }}" class="card-img-wrap">
        <img src="{{ $imagePath }}" alt="{{ $product->name_en }}" class="card-main-img" id="card-img-{{ $product->id }}">
        
        @if(count($imagesData) > 1)
            <button type="button" class="card-nav card-nav-left" onclick="event.stopPropagation(); changeCardImage({{ $product->id }}, -1)">‹</button>
            <button type="button" class="card-nav card-nav-right" onclick="event.stopPropagation(); changeCardImage({{ $product->id }}, 1)">›</button>
            <div class="card-dots" id="card-dots-{{ $product->id }}">
                @foreach($imagesData as $index => $img)
                    <span class="card-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                @endforeach
            </div>
        @endif
        
        @if($product->tags)
            <div class="tag-row">
                @foreach(explode(',', $product->tags) as $tag)
                    <div class="tag-pill">{{ trim($tag) }}</div>
                @endforeach
            </div>
        @endif

        @auth
            @if($isFavorited)
                <form method="POST" action="{{ route('favorites.destroy', $product) }}" style="display: inline;" onclick="event.stopPropagation();">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="fav-btn faved" onclick="event.stopPropagation(); return true;">
                        ❤
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('favorites.store', $product) }}" style="display: inline;" onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="fav-btn" onclick="event.stopPropagation(); return true;">
                        ♡
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" 
               class="fav-btn" 
               onclick="event.stopPropagation(); return true;"
               style="text-decoration: none; color: inherit;">
                ♡
            </a>
        @endauth
    </a>

    <div class="card-body">
        <a href="{{ route('products.show', $product) }}" class="prod-name">
            {{ $product->name_en }}
        </a>

        <div class="row-between">
            <div class="rating">
                @for($i = 0; $i < floor($product->rating); $i++)
                    ★
                @endfor
                <span class="num">{{ number_format($product->rating, 1) }}</span>
            </div>

            <div class="price-text">
                @if($product->old_price && $product->old_price > $product->base_price)
                    <span class="old-price">EGP {{ number_format($product->old_price, 2) }}</span>
                    <span class="new-price">EGP {{ number_format($product->base_price, 2) }}</span>
                @else
                    EGP {{ number_format($product->base_price, 2) }}
                @endif
            </div>
        </div>

        <div class="prod-cat">{{ $product->category->name }}</div>

        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.products.edit', $product) }}" class="add-cart-btn" style="width: 100%; text-decoration: none; display: block; text-align: center; background: var(--text-main);">
                    Edit
                </a>
            @else
                @php
                    $defaultSize = $product->variants->first()->size ?? 'One Size';
                    $defaultColor = $product->variants->where('size', $defaultSize)->first()->color ?? null;
                @endphp
                <form method="POST" action="{{ route('cart.add') }}" style="display: inline; width: 100%;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="size" value="{{ $defaultSize }}">
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    @if($defaultColor)
                        <input type="hidden" name="color" value="{{ $defaultColor }}">
                    @endif
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-cart-btn" style="width: 100%;">
                        Add to cart
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="add-cart-btn" style="width: 100%; text-decoration: none; display: block; text-align: center;">
                Add to cart
            </a>
        @endauth
    </div>
</div>

@once
<script>
// Store product images data
window.productCardImages = window.productCardImages || {};
window.productCardIndex = window.productCardIndex || {};
</script>
@endonce

<script>
// Initialize this product's images
window.productCardImages[{{ $product->id }}] = @json($imagesData);
window.productCardIndex[{{ $product->id }}] = 0;

function changeCardImage(productId, direction) {
    const images = window.productCardImages[productId];
    if (!images || images.length <= 1) return;
    
    let currentIndex = window.productCardIndex[productId] || 0;
    currentIndex += direction;
    
    // Loop around
    if (currentIndex < 0) currentIndex = images.length - 1;
    if (currentIndex >= images.length) currentIndex = 0;
    
    window.productCardIndex[productId] = currentIndex;
    
    // Update image
    const img = document.getElementById('card-img-' + productId);
    if (img) {
        img.src = images[currentIndex];
    }
    
    // Update dots
    const dotsContainer = document.getElementById('card-dots-' + productId);
    if (dotsContainer) {
        dotsContainer.querySelectorAll('.card-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }
}
</script>
