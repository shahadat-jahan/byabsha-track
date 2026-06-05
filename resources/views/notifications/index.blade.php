@extends('layouts.app')

@section('title', __('notifications.title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ __('notifications.title') }}</h1>
        <p class="page-subtitle">{{ __('notifications.subtitle') }}</p>
    </div>
    <div>
        <form action="{{ route('notifications.delete-all-read') }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('notifications.confirm_delete_all') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i> {{ __('notifications.delete_all_read') }}
            </button>
        </form>

        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-check-all"></i> {{ __('notifications.mark_all_read') }}
            </button>
        </form>
    </div>
</div>

<div class="content-card">
    @forelse($notifications as $notification)
        <div class="notification-item-full {{ $notification->isUnread() ? 'unread' : '' }}">
            <div class="d-flex gap-3">
                <div class="notification-icon-large">
                    <i class="{{ $notification->icon }}"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="notification-message-full mb-2">{{ $notification->message }}</p>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <div class="btn-group">
                            @if($notification->isUnread())
                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="{{ __('notifications.mark_as_read') }}">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('notifications.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('app.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-bell-slash" style="font-size: 4rem; color: #cbd5e1;"></i>
            <p class="text-muted mt-3">{{ __('notifications.no_notifications') }}</p>
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .notification-item-full {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    .notification-item-full:last-child {
        border-bottom: none;
    }

    .notification-item-full:hover {
        background: #f8fafc;
    }

    .notification-item-full.unread {
        background: #eff6ff;
    }

    .notification-item-full.unread:hover {
        background: #dbeafe;
    }

    .notification-icon-large {
        font-size: 2rem;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        flex-shrink: 0;
    }

    .notification-message-full {
        color: #64748b;
        line-height: 1.6;
    }
</style>
@endpush
