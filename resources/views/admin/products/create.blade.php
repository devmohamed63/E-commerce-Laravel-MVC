@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Create Product</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" class="admin-form-grid" enctype="multipart/form-data">
                @csrf
                <!-- Row 1: Category, Name EN, Name AR -->
                <div class="admin-form-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Category *</label>
                        <select name="category_id" required class="select-full">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Name (English) *</label>
                        <input type="text" name="name_en" required class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="e.g., Classic White T-Shirt" value="{{ old('name_en') }}">
                        @error('name_en')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Name (Arabic)</label>
                        <input type="text" name="name_ar" class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="e.g., تي شيرت أبيض كلاسيكي" value="{{ old('name_ar') }}">
                        @error('name_ar')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Base Price, Old Price, Rating, Gender -->
                <div class="admin-form-grid" style="grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Base Price (EGP) *</label>
                        <input type="number" name="base_price" required step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('base_price') }}">
                        @error('base_price')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Old Price (EGP)</label>
                        <input type="number" name="old_price" step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('old_price') }}">
                        @error('old_price')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Rating (0-5)</label>
                        <input type="number" name="rating" step="0.1" min="0" max="5" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="4.5" value="{{ old('rating', 4.5) }}">
                        @error('rating')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Gender *</label>
                        <select name="gender" required class="select-full">
                            <option value="women" {{ old('gender', 'women') === 'women' ? 'selected' : '' }}>Women</option>
                            <option value="men" {{ old('gender') === 'men' ? 'selected' : '' }}>Men</option>
                            <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                        @error('gender')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Tags (full width) -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="New, Offer, Trending, Sale" value="{{ old('tags') }}">
                    <span class="admin-form-helper">Separate multiple tags with commas</span>
                    @error('tags')
                        <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sizes Selection -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Available Sizes *</label>
                    <div class="checkbox-grid" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                        @php
                            $availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                            $oldSizes = old('sizes', []);
                        @endphp
                        @foreach($availableSizes as $size)
                            <label class="checkbox-label" style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; padding: 0.45rem 0.9rem; background: var(--bg-body); border: 1px solid var(--border-col); border-radius: 0.5rem;">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                                       {{ in_array($size, $oldSizes) ? 'checked' : '' }}
                                       style="width: 1.1rem; height: 1.1rem; accent-color: var(--bg-accent);">
                                <span style="font-weight: 500; font-size: 0.95rem;">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                    <span class="admin-form-helper" style="font-size: 0.85rem; color: var(--text-dim); margin-top: 0.4rem; display: block;">
                        Select all sizes this product is available in
                    </span>
                    @error('sizes')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Colors Selection (Optional) -->
                <div class="admin-form-group">
                    <label class="admin-form-label">Available Colors</label>
                    <div class="checkbox-grid" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                        @php
                            $availableColors = ['Black', 'White', 'Beige', 'Navy', 'Red', 'Pink', 'Blue', 'Green', 'Gray', 'Brown'];
                            $oldColors = old('colors', []);
                        @endphp
                        @foreach($availableColors as $color)
                            <label class="checkbox-label" style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; padding: 0.45rem 0.9rem; background: var(--bg-body); border: 1px solid var(--border-col); border-radius: 0.5rem;">
                                <input type="checkbox" name="colors[]" value="{{ $color }}" 
                                       {{ in_array($color, $oldColors) ? 'checked' : '' }}
                                       style="width: 1.1rem; height: 1.1rem; accent-color: var(--bg-accent);">
                                <span style="font-weight: 500; font-size: 0.95rem;">{{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                    <span class="admin-form-helper" style="font-size: 0.85rem; color: var(--text-dim); margin-top: 0.4rem; display: block;">
                        Optional: Select available colors. If no color is selected, the product will have no color variants.
                    </span>
                    @error('colors')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Product Images by Color -->
                <div class="admin-form-group" id="imagesSection">
                    <label class="admin-form-label">Product Images</label>
                    <p style="font-size: 0.85rem; color: var(--text-dim); margin-bottom: 1rem;">
                        Upload images for each color. If no colors are selected, upload general product images.
                    </p>
                    
                    <!-- Default/General Images (shows when no colors selected) -->
                    <div id="generalImagesUpload" class="image-upload-section" style="margin-bottom: 1rem;">
                        <div style="font-weight: 500; margin-bottom: 0.5rem;">📷 General Images</div>
                        <div style="border: 2px dashed var(--border-color); border-radius: 0.75rem; padding: 1.5rem; text-align: center; background: var(--card-bg); cursor: pointer;" 
                             onclick="document.getElementById('generalImageInput').click()">
                            <input type="file" name="images[general][]" id="generalImageInput" multiple accept="image/*" 
                                   style="display: none;" onchange="previewColorImages(this, 'general')">
                            <p style="margin: 0; color: var(--text-dim);">Click to upload images</p>
                        </div>
                        <div id="preview-general" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;"></div>
                    </div>

                    <!-- Color-specific image uploads (populated by JavaScript) -->
                    <div id="colorImageUploads"></div>
                    
                    @error('images')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                    @error('images.*')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="add-cart-btn">Create Product</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const selectedColors = new Set();
        let imageCounter = 0;
        let allUploadedImages = {}; // Track all images by their unique ID
        
        // Listen for color checkbox changes
        document.querySelectorAll('input[name="colors[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedColors.add(this.value);
                } else {
                    selectedColors.delete(this.value);
                }
                updateColorImageUploads();
            });
        });

        function updateColorImageUploads() {
            const container = document.getElementById('colorImageUploads');
            const generalSection = document.getElementById('generalImagesUpload');
            
            container.innerHTML = '';
            
            if (selectedColors.size === 0) {
                generalSection.style.display = 'block';
            } else {
                generalSection.style.display = 'none';
                
                selectedColors.forEach(color => {
                    const colorId = color.replace(/\s+/g, '_');
                    const section = document.createElement('div');
                    section.className = 'image-upload-section';
                    section.style.marginBottom = '1rem';
                    section.innerHTML = `
                        <div style="font-weight: 500; margin-bottom: 0.5rem;">🎨 ${color} Images</div>
                        <div style="border: 2px dashed var(--border-color); border-radius: 0.75rem; padding: 1.5rem; text-align: center; background: var(--card-bg); cursor: pointer;" 
                             onclick="document.getElementById('imageInput_${colorId}').click()">
                            <input type="file" name="images[${color}][]" id="imageInput_${colorId}" multiple accept="image/*" 
                                   style="display: none;" onchange="previewColorImages(this, '${colorId}', '${color}')">
                            <p style="margin: 0; color: var(--text-dim);">Click to upload ${color} images</p>
                        </div>
                        <div id="preview-${colorId}" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;"></div>
                    `;
                    container.appendChild(section);
                });
            }
        }

        function previewColorImages(input, colorId, colorName) {
            const preview = document.getElementById('preview-' + colorId);
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    const uniqueId = `img_${colorId}_${imageCounter++}`;
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'position: relative; width: 100px; border-radius: 0.5rem; overflow: hidden; border: 2px solid var(--border-color); background: var(--card-bg);';
                        div.id = 'container_' + uniqueId;
                        
                        const isFirst = preview.children.length === 0 && !document.querySelector('input[name="primary_image_index"]:checked');
                        
                        div.innerHTML = `
                            <img src="${e.target.result}" style="width: 100%; height: 80px; object-fit: cover;">
                            <div style="font-size: 0.65rem; padding: 0.2rem; text-align: center; color: var(--text-dim);">
                                🎨 ${colorName || 'General'}
                            </div>
                            <label style="display: flex; align-items: center; justify-content: center; gap: 0.25rem; padding: 0.3rem; cursor: pointer; font-size: 0.7rem;" class="primary-label" id="label_${uniqueId}">
                                <input type="radio" name="primary_image_index" value="${colorId}_${index}" onchange="updatePrimaryStyle('${uniqueId}')" ${isFirst ? 'checked' : ''}>
                                Primary
                            </label>
                        `;
                        preview.appendChild(div);
                        
                        if (isFirst) {
                            updatePrimaryStyle(uniqueId);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function updatePrimaryStyle(selectedId) {
            // Reset all containers
            document.querySelectorAll('[id^="container_img_"]').forEach(container => {
                container.style.borderColor = 'var(--border-color)';
            });
            document.querySelectorAll('.primary-label').forEach(label => {
                label.style.background = 'transparent';
                label.style.color = 'var(--text-main)';
            });
            
            // Highlight selected
            const container = document.getElementById('container_' + selectedId);
            const label = document.getElementById('label_' + selectedId);
            if (container) {
                container.style.borderColor = 'var(--primary-color)';
            }
            if (label) {
                label.style.background = 'var(--primary-color)';
                label.style.color = 'white';
            }
        }
    </script>
@endsection
