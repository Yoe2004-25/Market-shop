@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-bell-fill text-warning"></i> Notifications</h3>
    <form action="{{ route('notifications.read-all') }}" method="POST">
        @csrf
        <button class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-check2-all"></i> Mark All as Read
        </button>
    </form>
</div>

<div class="card">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notif)
            <div class="list-group-item d-flex justify-content-between align-items-start
                        {{ $notif->read_at ? '' : 'bg-light' }}">
                <div class="flex-grow-1">
                    <p class="mb-1">{{ $notif->data['message'] ?? 'New notification' }}</p>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i>
                        {{ $notif->created_at->diffForHumans() }}
                    </small>
                </div>
                @if(!$notif->read_at)
                    <form action="{{ route('notifications.mark-read', $notif->id) }}"
                          method="POST" class="ms-2">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="list-group-item text-center text-muted py-4">
                <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                No notifications yet.
            </div>
        @endforelse
    </div>
</div>

<div class="mt-3">{{ $notifications->links() }}</div>

@endsection