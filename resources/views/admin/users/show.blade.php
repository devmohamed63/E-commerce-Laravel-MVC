@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary" style="padding: 0.5rem 0.75rem;">
                ← Back
            </a>
            <div>
                <h1 class="admin-page-title" style="margin: 0;">{{ $user->name }}</h1>
                <div class="admin-page-subtitle" style="margin-top: 0.25rem;">
                    {{ $user->email }}
                    @if($user->is_admin)
                        <span class="admin-badge" style="background: #7c3aed; color: white; font-size: 0.65rem; padding: 0.2rem 0.5rem; margin-left: 0.5rem;">Admin</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="admin-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="padding: 1.25rem;">
            <div class="admin-stat-label">Joined</div>
            <div class="admin-stat-value">{{ $user->created_at->format('M d, Y') }}</div>
        </div>
        <div class="card" style="padding: 1.25rem;">
            <div class="admin-stat-label">Total Orders</div>
            <div class="admin-stat-value">{{ $user->orders->count() }}</div>
        </div>
        <div class="card" style="padding: 1.25rem;">
            <div class="admin-stat-label">Total Spent</div>
            <div class="admin-stat-value">EGP {{ number_format($user->orders->sum('total'), 2) }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Recent Orders</h2>
            
            @forelse($user->orders as $order)
                <div class="admin-list-item" style="border-bottom: 1px solid #e5e7eb; padding: 0.75rem 0;">
                    <div class="admin-list-content">
                        <div class="admin-list-title">Order #{{ $order->id }}</div>
                        <div class="admin-list-subtitle">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span class="admin-badge {{ $order->status }}">{{ $order->status }}</span>
                        <div style="font-weight: 600;">EGP {{ number_format($order->total, 2) }}</div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn-secondary">View</a>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #6b7280; padding: 2rem;">
                    No orders yet
                </div>
            @endforelse
        </div>
    </div>
@endsection
