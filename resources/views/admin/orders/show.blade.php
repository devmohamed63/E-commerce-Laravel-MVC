@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Order #{{ $order->id }}</h1>
        <span class="admin-badge {{ $order->status }}">{{ $order->status }}</span>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Update Status</h2>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <div class="admin-form-group">
                    <label class="admin-form-label">Order Status</label>
                    <select name="status" class="select-full">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="add-cart-btn">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Order Details</h2>
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                    <strong style="min-width: 140px;">Status:</strong>
                    <span class="admin-badge {{ $order->status }}">{{ $order->status }}</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <strong style="min-width: 140px;">Date:</strong>
                    <span>{{ $order->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <strong style="min-width: 140px;">Payment Method:</strong>
                    <span>{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card' }}</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <strong style="min-width: 140px;">Customer:</strong>
                    <span>{{ $order->customer_name }}</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <strong style="min-width: 140px;">Phone:</strong>
                    <span>{{ $order->customer_phone }}</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <strong style="min-width: 140px;">Address:</strong>
                    <span>{{ $order->customer_address }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Order Items</h2>
            <div style="display: grid; gap: 0;">
                @foreach($order->items as $item)
                    <div class="admin-list-item">
                        <img src="{{ asset($item->product_image) }}" 
                             alt="{{ $item->product_name }}"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md); flex-shrink: 0; margin-right: 1rem;">
                        <div class="admin-list-content">
                            <div class="admin-list-title">{{ $item->product_name }}</div>
                            <div class="admin-list-subtitle">
                                Size: {{ $item->size }}{{ $item->color ? ' • Color: ' . $item->color : '' }} • Qty: {{ $item->quantity }}
                            </div>
                        </div>
                        <div style="text-align: right; align-self: flex-start;">
                            <div style="font-weight: 600; font-size: 1rem;">EGP {{ number_format($item->price * $item->quantity, 2) }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-dim); margin-top: 0.25rem;">
                                EGP {{ number_format($item->price, 2) }} each
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="sum-row">
                <span>Subtotal</span>
                <span>EGP {{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="sum-row">
                <span>Shipping</span>
                <span>{{ $order->shipping > 0 ? 'EGP ' . number_format($order->shipping, 2) : 'Free' }}</span>
            </div>
            <div class="sum-row total">
                <span>Total</span>
                <span>EGP {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>
@endsection

