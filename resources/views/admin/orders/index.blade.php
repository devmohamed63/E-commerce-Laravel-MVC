@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Orders</h1>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @forelse($orders as $order)
                <div class="admin-list-item">
                    <div class="admin-list-content">
                        <div class="admin-list-title">Order #{{ $order->id }}</div>
                        <div class="admin-list-subtitle">
                            {{ $order->customer_name }} • {{ $order->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem;">
                        <div style="font-weight: 600; font-size: 1.1rem;">EGP {{ number_format($order->total, 2) }}</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; justify-content: flex-end;">
                            <span class="admin-badge {{ $order->status }}">{{ $order->status }}</span>
                            <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn-secondary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="admin-empty-state">
                    <div class="admin-empty-state-text">No orders yet</div>
                    <div class="admin-empty-state-subtext">Orders will appear here once customers start placing them</div>
                </div>
            @endforelse

            <div style="margin-top: 1.5rem;">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

