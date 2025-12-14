@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Create Category</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-form-grid">
                @csrf
                <div class="admin-form-group">
                    <label class="admin-form-label">Category Name</label>
                    <input type="text" name="name" required 
                           class="input-full" 
                           style="padding-left: 0.75rem;"
                           placeholder="e.g., T-Shirts, Dresses, Shoes"
                           value="{{ old('name') }}">
                    @error('name')
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="add-cart-btn">Create Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

