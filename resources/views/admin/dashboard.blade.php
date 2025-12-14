@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Dashboard</h1>
    </div>

    <div class="admin-stats-grid">
        <div class="admin-stat-card primary">
            <div class="admin-stat-label">Total Orders</div>
            <div class="admin-stat-value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="admin-stat-card warning">
            <div class="admin-stat-label">Pending Orders</div>
            <div class="admin-stat-value">{{ $stats['pending_orders'] }}</div>
        </div>
        <div class="admin-stat-card success">
            <div class="admin-stat-label">Total Revenue</div>
            <div class="admin-stat-value">EGP {{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="admin-stat-card danger">
            <div class="admin-stat-label">Total Products</div>
            <div class="admin-stat-value">{{ $stats['total_products'] }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Recent Orders</h2>
            <div style="display: grid; gap: 0;">
                @forelse($recentOrders as $order)
                    <div class="admin-list-item">
                        <div class="admin-list-content">
                            <div class="admin-list-title">Order #{{ $order->id }}</div>
                            <div class="admin-list-subtitle">
                                {{ $order->customer_name }} • {{ $order->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                            <div style="font-weight: 600; font-size: 1rem;">EGP {{ number_format($order->total, 2) }}</div>
                            <span class="admin-badge {{ $order->status }}">{{ $order->status }}</span>
                            <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-text">No orders yet</div>
                        <div class="admin-empty-state-subtext">Orders will appear here once customers start placing them</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

