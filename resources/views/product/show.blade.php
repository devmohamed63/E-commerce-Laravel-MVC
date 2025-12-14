@php
    $standalone = $standalone ?? false;
@endphp

@if($standalone)
@extends('layouts.app')

@section('content')
@endif

<div class="product-detail-content">
    <div class="pd-image-wrap">
        <button type="button" id="pdPrev" class="pd-nav pd-nav-left" onclick="showPrevImage()">‹</button>
        <img id="pdImage" src="{{ asset($product->images->first()->path ?? 'images/placeholder.png') }}" alt="{{ $product->name_en }}">
        <button type="button" id="pdNext" class="pd-nav pd-nav-right" onclick="showNextImage()">›</button>
        <div id="pdThumbs" class="pd-thumbs">
            @foreach($product->images as $index => $image)
                <img src="{{ asset($image->path) }}" 
                     alt="{{ $product->name_en }} {{ $index + 1 }}"
                     class="pd-thumb {{ $index === 0 ? 'active' : '' }}"
                     onclick="setImageIndex({{ $index }})">
            @endforeach
        </div>
    </div>
    <div class="pd-info">
        <h2>{{ $product->name_en }}</h2>
        <div class="pd-rating-price">
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
        
        <div class="pd-sizes-block">
            <div class="field-label">
                <span>🪄</span>
                <span>Size</span>
            </div>
            <div id="pdSizes" class="card-size-row">
                @php
                    $sizes = $product->variants->pluck('size')->unique()->values();
                @endphp
                @foreach($sizes as $index => $size)
                    <button type="button" 
                            class="size-pill {{ $index === 0 ? 'active' : '' }}"
                            onclick="selectSize('{{ $size }}', this)">
                        {{ $size }}
                    </button>
                @endforeach
            </div>
        </div>

        @php
            $colors = $product->variants->pluck('color')->filter()->unique()->values();
        @endphp
        @if($colors->count() > 0)
            <div class="pd-colors-block">
                <div class="field-label">
                    <span>🎨</span>
                    <span>Color</span>
                </div>
                <div id="pdColors" class="card-color-row">
                    @foreach($colors as $index => $color)
                        <button type="button" 
                                class="color-pill {{ $index === 0 ? 'active' : '' }}"
                                onclick="selectColor('{{ $color }}', this)">
                            {{ $color }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        @auth
            <form method="POST" action="{{ route('cart.add') }}" style="display: inline; width: 100%;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size" id="selectedSize" value="{{ $sizes->first() }}">
                <input type="hidden" name="color" id="selectedColor" value="{{ $colors->first() ?? '' }}">
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
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

<script>
let currentImageIndex = 0;
const productImages = @json($product->images->map(function($img) { return asset($img->path); })->values());
let selectedSize = '{{ $sizes->first() }}';
let selectedColor = '{{ $colors->first() ?? "" }}';

function setImageIndex(index) {
    currentImageIndex = index;
    document.getElementById('pdImage').src = productImages[index];
    document.querySelectorAll('.pd-thumb').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
}

function showPrevImage() {
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    setImageIndex(currentImageIndex);
}

function showNextImage() {
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    setImageIndex(currentImageIndex);
}

function selectSize(size, button) {
    selectedSize = size;
    document.querySelectorAll('#pdSizes .size-pill').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    const sizeInput = document.getElementById('selectedSize');
    if (sizeInput) sizeInput.value = size;
}

function selectColor(color, button) {
    selectedColor = color;
    document.querySelectorAll('#pdColors .color-pill').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    const colorInput = document.getElementById('selectedColor');
    if (colorInput) colorInput.value = color;
}
</script>

@if($standalone)
@endsection
@endif
