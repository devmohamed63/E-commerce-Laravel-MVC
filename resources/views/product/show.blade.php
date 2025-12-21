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
        <img id="pdImage" src="{{ asset($product->images->first()->path ?? 'images/placeholder.svg') }}" alt="{{ $product->name_en }}">
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
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.products.edit', $product) }}" class="add-cart-btn" style="width: 100%; text-decoration: none; display: block; text-align: center; background: var(--text-main);">
                    Edit Product
                </a>
            @else
                <button type="button" class="add-cart-btn" style="width: 100%;" id="addToCartBtn"
                    onclick="handleAddToCartFromDetail({{ $product->id }})">
                    Add to cart
                </button>
            @endif
        @else
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="add-cart-btn" style="width: 100%; text-decoration: none; display: block; text-align: center;">
                Add to cart
            </a>
        @endauth
    </div>
</div>

<script>
let currentImageIndex = 0;
// Group images by color
const imagesByColor = @json($product->images->groupBy('color')->map(function($images) {
    return $images->map(function($img) {
        return asset($img->path);
    })->values();
}));

// All images as fallback
const allImages = @json($product->images->map(function($img) { return asset($img->path); })->values());

let productImages = allImages;
let selectedSize = '{{ $sizes->first() }}';
let selectedColor = '{{ $colors->first() ?? "" }}';

// Initialize with first color's images if available
if (selectedColor && imagesByColor[selectedColor]) {
    productImages = imagesByColor[selectedColor];
}

function updateThumbnails() {
    const thumbsContainer = document.getElementById('pdThumbs');
    thumbsContainer.innerHTML = '';
    productImages.forEach((imgSrc, index) => {
        const thumb = document.createElement('img');
        thumb.src = imgSrc;
        thumb.alt = 'Thumbnail ' + (index + 1);
        thumb.className = 'pd-thumb' + (index === 0 ? ' active' : '');
        thumb.onclick = () => setImageIndex(index);
        thumbsContainer.appendChild(thumb);
    });
}

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
    
    // Update images based on selected color
    if (imagesByColor[color] && imagesByColor[color].length > 0) {
        productImages = imagesByColor[color];
    } else {
        productImages = allImages;
    }
    
    currentImageIndex = 0;
    updateThumbnails();
    setImageIndex(0);
}
</script>

@if($standalone)
@endsection
@endif
