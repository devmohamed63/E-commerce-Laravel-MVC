@props(['product', 'favorited' => false])

@php
    $primaryImage = $product->images->first();
    $imagePath = $primaryImage ? asset($primaryImage->path) : asset('images/placeholder.png');
    $isFavorited = $favorited ?? false;
    if (!$isFavorited && auth()->check()) {
        $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
    }
@endphp

<div class="card">
    <div class="card-img-wrap" onclick="openProductDetail({{ $product->id }})">
        <img src="{{ $imagePath }}" alt="{{ $product->name_en }}">
        
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
    </div>

    <div class="card-body">
        <div class="prod-name" onclick="openProductDetail({{ $product->id }})">
            {{ $product->name_en }}
        </div>

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
                    🛒 Add to cart
                </button>
            </form>
        @else
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="add-cart-btn" style="width: 100%; text-decoration: none; display: block; text-align: center;">
                🛒 Add to cart
            </a>
        @endauth
    </div>
</div>

