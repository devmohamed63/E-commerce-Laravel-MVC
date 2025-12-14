@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="admin-btn-primary">
            New Product
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @forelse($products as $product)
                <div class="admin-list-item">
                    @if($product->images->first())
                        <img src="{{ asset($product->images->first()->path) }}" 
                             alt="{{ $product->name_en }}"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md); flex-shrink: 0; margin-right: 1rem;">
                    @else
                        <div style="width: 80px; height: 80px; background: var(--bg-body); border-radius: var(--radius-md); flex-shrink: 0; margin-right: 1rem; display: flex; align-items: center; justify-content: center; color: var(--text-dim); font-size: 0.75rem; text-align: center; padding: 0.5rem;">
                            No Image
                        </div>
                    @endif
                    <div class="admin-list-content">
                        <div class="admin-list-title">{{ $product->name_en }}</div>
                        <div class="admin-list-subtitle">
                            {{ $product->category->name }} • EGP {{ number_format($product->base_price, 2) }}
                            @if($product->old_price && $product->old_price > $product->base_price)
                                <span style="text-decoration: line-through; opacity: 0.6; margin-left: 0.5rem;">
                                    EGP {{ number_format($product->old_price, 2) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="admin-list-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="admin-btn-secondary">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this product?')" style="display: inline;">
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
                    <div class="admin-empty-state-text">No products yet</div>
                    <div class="admin-empty-state-subtext">
                        <a href="{{ route('admin.products.create') }}" class="add-cart-btn" style="text-decoration: none; display: inline-block; margin-top: 1rem;">
                            Create Your First Product
                        </a>
                    </div>
                </div>
            @endforelse

            <div style="margin-top: 1.5rem;">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection

