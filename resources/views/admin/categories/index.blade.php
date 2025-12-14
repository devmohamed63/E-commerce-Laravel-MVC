@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">
            New Category
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @forelse($categories as $category)
                <div class="admin-list-item">
                    <div class="admin-list-content">
                        <div class="admin-list-title">{{ $category->name }}</div>
                        <div class="admin-list-subtitle">
                            {{ $category->products_count }} {{ $category->products_count === 1 ? 'product' : 'products' }}
                        </div>
                    </div>
                    <div class="admin-list-actions">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn-secondary">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this category?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="admin-empty-state">
                    <div class="admin-empty-state-text">No categories yet</div>
                    <div class="admin-empty-state-subtext">
                        <a href="{{ route('admin.categories.create') }}" class="add-cart-btn" style="text-decoration: none; display: inline-block; margin-top: 1rem;">
                            Create Your First Category
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

