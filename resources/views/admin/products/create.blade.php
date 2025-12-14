@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Create Product</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" class="admin-form-grid">
                @csrf
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
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Name (English) *</label>
                    <input type="text" name="name_en" required class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="e.g., Classic White T-Shirt" value="{{ old('name_en') }}">
                    @error('name_en')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Name (Arabic)</label>
                    <input type="text" name="name_ar" class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="e.g., تي شيرت أبيض كلاسيكي" value="{{ old('name_ar') }}">
                    @error('name_ar')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-grid admin-form-grid-2">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Base Price (EGP) *</label>
                        <input type="number" name="base_price" required step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('base_price') }}">
                        @error('base_price')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Old Price (EGP)</label>
                        <input type="number" name="old_price" step="0.01" min="0" 
                               class="input-full" style="padding-left: 0.75rem;" 
                               placeholder="0.00" value="{{ old('old_price') }}">
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
                               placeholder="4.5" value="{{ old('rating', 4.5) }}">
                        @error('rating')
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
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
                            <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="input-full" style="padding-left: 0.75rem;" 
                           placeholder="New, Offer, Trending, Sale" value="{{ old('tags') }}">
                    <span style="font-size: 0.8rem; color: var(--text-dim); margin-top: 0.25rem;">
                        Separate multiple tags with commas
                    </span>
                    @error('tags')
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
@endsection

