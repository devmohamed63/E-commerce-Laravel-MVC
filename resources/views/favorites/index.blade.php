@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="background: #10b981; color: white; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div class="products-header">
        <h2>My Favorites</h2>
        <div class="stats-row">
            <span class="pill">{{ $favorites->count() }} items</span>
        </div>
    </div>

    <div class="grid-products">
        @forelse($favorites as $favorite)
            <x-product-card :product="$favorite->product" :favorited="true" />
        @empty
            <div class="empty-box">You haven't added any favorites yet.</div>
        @endforelse
    </div>
@endsection

