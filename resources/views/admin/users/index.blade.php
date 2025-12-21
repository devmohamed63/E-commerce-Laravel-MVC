@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Users</h1>
        <div class="admin-page-subtitle">{{ $users->total() }} registered users</div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @forelse($users as $user)
                <div class="admin-list-item">
                    <div class="admin-list-content">
                        <div class="admin-list-title" style="display: flex; align-items: center; gap: 0.5rem;">
                            {{ $user->name }}
                            @if($user->is_admin)
                                <span class="admin-badge" style="background: #7c3aed; color: white; font-size: 0.65rem; padding: 0.2rem 0.5rem;">Admin</span>
                            @endif
                        </div>
                        <div class="admin-list-subtitle">
                            {{ $user->email }} • Joined {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                        <a href="{{ route('admin.users.show', $user) }}" class="admin-btn-secondary">
                            View
                        </a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="admin-btn-secondary" style="background: {{ $user->is_admin ? '#dc2626' : '#059669' }}; color: white; border: none;">
                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="admin-empty-state">
                    <div class="admin-empty-state-text">No users yet</div>
                    <div class="admin-empty-state-subtext">Users will appear here once they register</div>
                </div>
            @endforelse

            <div style="padding: 1rem;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
