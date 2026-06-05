@extends('layouts.app')

@section('title', __('user.show_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --user-show-ink-900: #0f172a;
        --user-show-ink-700: #334155;
        --user-show-ink-500: #64748b;
        --user-show-brand: #0f766e;
        --user-show-brand-deep: #155e75;
        --user-show-line: #d8e4ee;
    }

    .user-show-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--user-show-ink-900);
    }

    .user-show-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.19), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .user-show-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--user-show-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--user-show-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--user-show-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-head {
        border-radius: 999px;
        padding: 0.58rem 1.06rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .btn-head-edit {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
        background: #fff7ed;
    }

    .btn-head-edit:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    .btn-head-back {
        color: #475569;
        border-color: #cfdce8;
        background: #f8fbff;
    }

    .btn-head-back:hover {
        background: #e9f1f8;
        color: #1e293b;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--user-show-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        padding: 1.45rem;
    }

    .info-table {
        width: 100%;
        margin: 0;
    }

    .info-table th,
    .info-table td {
        padding: 0.78rem 0.2rem;
        border-bottom: 1px solid #e7edf4;
        vertical-align: middle;
    }

    .info-table tr:last-child th,
    .info-table tr:last-child td {
        border-bottom: 0;
    }

    .info-table th {
        width: 210px;
        color: var(--user-show-ink-500);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .info-table td {
        color: var(--user-show-ink-900);
        font-weight: 600;
    }

    .role-badge,
    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.36rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .role-superadmin {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.26);
    }

    .role-owner {
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.24);
    }

    .role-manager {
        background: rgba(217, 119, 6, 0.12);
        color: #d97706;
        border-color: rgba(217, 119, 6, 0.26);
    }

    .role-user {
        background: rgba(59, 130, 246, 0.12);
        color: #1d4ed8;
        border-color: rgba(59, 130, 246, 0.28);
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .status-deactive {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.28);
    }

    .deleted-meta {
        font-size: 0.85rem;
        color: var(--user-show-ink-500);
        margin-left: 0.4rem;
    }

    .danger-card {
        border: 1px solid rgba(239, 68, 68, 0.25);
        background: linear-gradient(180deg, #fff7f7 0%, #fff3f3 100%);
        border-radius: 18px;
        padding: 1.2rem;
    }

    .danger-title {
        color: #b91c1c;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.45rem;
    }

    .danger-text {
        color: #7f1d1d;
        margin-bottom: 0.9rem;
    }

    .danger-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }

    .btn-restore {
        background: #10b981;
        border-color: #10b981;
        color: #fff;
        border-radius: 999px;
        padding: 0.55rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .btn-restore:hover {
        color: #fff;
        background: #059669;
        border-color: #059669;
    }

    .btn-deactivate {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
        border-radius: 999px;
        padding: 0.55rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .btn-deactivate:hover {
        color: #fff;
        background: #b91c1c;
        border-color: #b91c1c;
    }

    @media (max-width: 768px) {
        .content-card {
            padding: 1.05rem;
            border-radius: 16px;
        }

        .info-table th,
        .info-table td {
            display: block;
            width: 100%;
            border-bottom: 0;
            padding: 0.35rem 0;
        }

        .info-table tr {
            border-bottom: 1px solid #e7edf4;
            display: block;
            padding: 0.5rem 0;
        }

        .info-table tr:last-child {
            border-bottom: 0;
        }

        .header-actions {
            width: 100%;
        }

        .btn-head {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="user-show-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="user-show-kicker"><i class="bi bi-person-vcard"></i>{{ __('user.show_title') }}</span>
        <h1 class="page-title display-font">{{ __('user.show_title') }}</h1>
        <p class="page-subtitle">{{ __('user.show_subtitle') }}</p>
    </div>
    <div class="header-actions">
        @if(!$user->trashed())
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-head btn-head-edit">
                <i class="bi bi-pencil"></i> {{ __('app.edit') }}
            </a>
        @endif
        <a href="{{ route('user.index') }}" class="btn btn-head btn-head-back">
            <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>
</div>

<div class="content-card">
    <table class="info-table">
        <tbody>
            <tr>
                <th>{{ __('user.name') }}</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>{{ __('user.email') }}</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>{{ __('user.role') }}</th>
                <td>
                    <span class="role-badge role-{{ $user->role }}">
                        {{ __('user.role_' . $user->role) }}
                    </span>
                </td>
            </tr>
            @if($user->isManager())
            <tr>
                <th>{{ __('user.approval_status') }}</th>
                <td>
                    @if($user->isPendingApproval())
                        <span class="status-badge" style="background:rgba(245,158,11,.14);color:#d97706;border:1px solid rgba(245,158,11,.3);">
                            <i class="bi bi-clock-history me-1"></i>{{ __('user.pending_approval') }}
                        </span>
                        <a href="{{ route('user.approve.form', $user->id) }}" class="btn btn-sm ms-2" style="border-radius:999px;padding:.3rem .8rem;font-size:.78rem;font-weight:700;background:rgba(217,119,6,.12);color:#d97706;border:1px solid rgba(217,119,6,.3);">
                            <i class="bi bi-person-check"></i> {{ __('user.approve_btn') }}
                        </a>
                    @else
                        <span class="status-badge status-active"><i class="bi bi-check-circle me-1"></i>{{ __('user.approved') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{ __('user.assigned_shop') }}</th>
                <td>{{ $user->assignedShop?->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>{{ __('user.assigned_branch') }}</th>
                <td>{{ $user->assignedBranch?->name ?? '—' }}</td>
            </tr>
            @endif
            <tr>
                <th>{{ __('user.col_status') }}</th>
                <td>
                    @if($user->trashed())
                        <span class="status-badge status-deactive">{{ __('user.deactive') }}</span>
                        <span class="deleted-meta">{{ __('user.deactivated_at') }}: {{ $user->deleted_at->format('M d, Y H:i') }}</span>
                    @else
                        <span class="status-badge status-active">{{ __('user.active') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{ __('user.created_at') }}</th>
                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @if($user->updated_at != $user->created_at)
            <tr>
                <th>{{ __('user.updated_at') }}</th>
                <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@if($user->trashed())
    <div class="danger-card mt-3">
        <h5 class="danger-title">{{ __('user.deactive_warning_title') }}</h5>
        <p class="danger-text">{{ __('user.deactive_warning_text') }}</p>
        <div class="danger-actions">
            <form action="{{ route('user.activate', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('user.confirm_activate') }}')">
                @csrf
                <button type="submit" class="btn btn-restore">
                    <i class="bi bi-arrow-counterclockwise"></i> {{ __('user.activate') }}
                </button>
            </form>
        </div>
    </div>
@elseif($user->id !== auth()->id())
    <div class="danger-card mt-3">
        <h5 class="danger-title">{{ __('user.deactive_warning_title') }}</h5>
        <p class="danger-text">{{ __('user.deactive_action_text') }}</p>
        <div class="danger-actions">
            <form action="{{ route('user.deactivate', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('user.confirm_deactivate') }}')">
                @csrf
                <button type="submit" class="btn btn-deactivate">
                    <i class="bi bi-person-x"></i> {{ __('user.deactivate') }}
                </button>
            </form>
        </div>
    </div>
@endif
</div>
@endsection
