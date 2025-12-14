@extends('layouts.app')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="margin-bottom: 1.5rem;">Order #{{ $order->id }}</h1>

        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Order Details</h2>
                <div style="display: grid; gap: 0.75rem;">
                    <div><strong>Status:</strong> <span class="pill" style="text-transform: capitalize;">{{ $order->status }}</span></div>
                    <div><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</div>
                    <div><strong>Payment Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card' }}</div>
                    <div><strong>Customer:</strong> {{ $order->customer_name }}</div>
                    <div><strong>Phone:</strong> {{ $order->customer_phone }}</div>
                    <div><strong>Address:</strong> {{ $order->customer_address }}</div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Items</h2>
                <div style="display: grid; gap: 1rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-col);">
                            <img src="{{ asset($item->product_image) }}" 
                                 alt="{{ $item->product_name }}"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md);">
                            <div style="flex: 1;">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem;">{{ $item->product_name }}</h3>
                                <p style="font-size: 0.85rem; color: var(--text-dim);">
                                    Size: {{ $item->size }}{{ $item->color ? ' • Color: ' . $item->color : '' }}
                                </p>
                                <p style="font-size: 0.85rem; color: var(--text-dim);">
                                    Quantity: {{ $item->quantity }}
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-weight: 600;">EGP {{ number_format($item->price * $item->quantity, 2) }}</p>
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
    </div>
@endsection

