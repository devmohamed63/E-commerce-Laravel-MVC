@extends('layouts.app')

@section('content')
    <div class="products-header">
        <h2>My Orders</h2>
    </div>

    <div style="display: grid; gap: 1rem;">
        @forelse($orders as $order)
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Order #{{ $order->id }}</h3>
                            <p style="font-size: 0.85rem; color: var(--text-dim);">
                                {{ $order->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="pill" style="text-transform: capitalize;">{{ $order->status }}</span>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <p><strong>Total:</strong> EGP {{ number_format($order->total, 2) }}</p>
                        <p><strong>Payment:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card' }}</p>
                    </div>

                    <a href="{{ route('orders.show', $order) }}" class="add-cart-btn" style="text-decoration: none; display: inline-block; text-align: center;">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-box">You haven't placed any orders yet.</div>
        @endforelse
    </div>
@endsection

