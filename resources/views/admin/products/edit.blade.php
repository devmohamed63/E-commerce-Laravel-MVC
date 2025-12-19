@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Edit Product</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" class="admin-form-grid" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="admin-form-group">
                    <label class="admin-form-label">Category *</label>
                    <select name="category_id" required class="select-full">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Name (English) *</label>
                    <input type="text" name="name_en" required class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="e.g., Classic White T-Shirt" value="{{ old('name_en', $product->name_en) }}">
                    @error('name_en')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Name (Arabic)</label>
                    <input type="text" name="name_ar" class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="e.g., تي شيرت أبيض كلاسيكي" value="{{ old('name_ar', $product->name_ar) }}">
                    @error('name_ar')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-grid admin-form-grid-2">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Base Price (EGP) *</label>
                        <input type="number" name="base_price" required step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('base_price', $product->base_price) }}">
                        @error('base_price')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Old Price (EGP)</label>
                        <input type="number" name="old_price" step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('old_price', $product->old_price) }}">
                        @error('old_price')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-grid admin-form-grid-2">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Rating (0-5)</label>
                        <input type="number" name="rating" step="0.1" min="0" max="5" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="4.5" value="{{ old('rating', $product->rating) }}">
                        @error('rating')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Gender *</label>
                        <select name="gender" required class="select-full">
                            <option value="women" {{ old('gender', $product->gender) === 'women' ? 'selected' : '' }}>Women</option>
                            <option value="men" {{ old('gender', $product->gender) === 'men' ? 'selected' : '' }}>Men</option>
                            <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                        @error('gender')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="New, Offer, Trending, Sale" value="{{ old('tags', $product->tags) }}">
                    <span style="font-size: 0.8rem; color: var(--text-dim); margin-top: 0.25rem;">
                        Separate multiple tags with commas
                    </span>
                    @error('tags')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sizes Selection -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Available Sizes *</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 0.5rem;">
                        @php
                            $availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                            $currentSizes = old('sizes', $product->variants->pluck('size')->unique()->toArray());
                        @endphp
                        @foreach($availableSizes as $size)
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 1rem; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 0.5rem; transition: all 0.2s;">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                                       {{ in_array($size, $currentSizes) ? 'checked' : '' }}
                                       style="width: 1rem; height: 1rem; accent-color: var(--primary-color);">
                                <span style="font-weight: 500;">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('sizes')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Colors Selection -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Available Colors</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 0.5rem;">
                        @php
                            $availableColors = ['Black', 'White', 'Beige', 'Navy', 'Red', 'Pink', 'Blue', 'Green', 'Gray', 'Brown'];
                            $currentColors = old('colors', $product->variants->pluck('color')->filter()->unique()->toArray());
                        @endphp
                        @foreach($availableColors as $color)
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 1rem; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 0.5rem; transition: all 0.2s;">
                                <input type="checkbox" name="colors[]" value="{{ $color }}" 
                                       {{ in_array($color, $currentColors) ? 'checked' : '' }}
                                       style="width: 1rem; height: 1rem; accent-color: var(--primary-color);">
                                <span style="font-weight: 500;">{{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('colors')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Current Images -->
                @if($product->images->count() > 0)
                <div class="admin-form-group">
                    <label class="admin-form-label">Current Images</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 0.5rem;">
                        @foreach($product->images as $image)
                            <div style="position: relative; width: 100px; height: 100px; border-radius: 0.5rem; overflow: hidden; border: 1px solid var(--border-color);">
                                <img src="{{ asset($image->path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @if($image->is_primary)
                                    <span style="position: absolute; bottom: 0; left: 0; right: 0; background: var(--primary-color); color: white; font-size: 0.7rem; text-align: center; padding: 0.15rem;">Primary</span>
                                @endif
                                <label style="position: absolute; top: 0.25rem; right: 0.25rem; cursor: pointer;">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" style="accent-color: #ef4444;">
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <span style="font-size: 0.8rem; color: var(--text-dim); margin-top: 0.5rem; display: block;">
                        Check images to delete them
                    </span>
                </div>
                @endif

                <!-- Add New Images -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Add New Images</label>
                    <div style="border: 2px dashed var(--border-color); border-radius: 0.75rem; padding: 2rem; text-align: center; background: var(--card-bg); cursor: pointer;" 
                         onclick="document.getElementById('imageInput').click()">
                        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" 
                               style="display: none;" onchange="previewImages(this)">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📷</div>
                        <p style="margin: 0; font-weight: 500; color: var(--text-main);">Click to upload images</p>
                        <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-dim);">PNG, JPG, WEBP up to 5MB each</p>
                    </div>
                    <div id="imagePreview" style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;"></div>
                    @error('images.*')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="add-cart-btn">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'position: relative; width: 100px; height: 100px; border-radius: 0.5rem; overflow: hidden; border: 1px solid var(--border-color);';
                        div.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection
