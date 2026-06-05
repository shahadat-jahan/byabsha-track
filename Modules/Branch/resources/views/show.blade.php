@extends('layouts.app')

@section('title', __('branch::branch.show_title'))

@push('styles')
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
        --branch-ink-900: #0f172a;
        --branch-ink-700: #334155;
        --branch-ink-500: #64748b;
    }

    .btn-branch-theme {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 28px rgba(15, 118, 110, 0.28);
    }

    .content-card {
        background: #fff;
        border: 1px solid var(--branch-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #e7edf4;
        background: #f7fbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .content-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--branch-ink-700);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i { color: var(--branch-brand); }

    .detail-item {
        padding: 0.85rem 0;
        border-bottom: 1px solid #edf3f8;
    }

    .detail-item:last-child { border-bottom: none; padding-bottom: 0; }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--branch-ink-500);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 0.95rem;
        color: var(--branch-ink-900);
        font-weight: 500;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 999px;
        padding: 0.32rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.26);
    }

    .status-inactive {
        background: rgba(100, 116, 139, 0.14);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.26);
    }

    .stat-box {
        background: #f7fbff;
        border: 1px solid #dce8f3;
        border-radius: 14px;
        padding: 1rem;
        text-align: center;
    }

    .stat-box .stat-num {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--branch-brand);
        line-height: 1;
    }

    .stat-box .stat-lbl {
        font-size: 0.75rem;
        color: var(--branch-ink-500);
        margin-top: 0.2rem;
    }

    @media (max-width: 767.98px) {
        .page-action-buttons { flex-direction: column; width: 100%; }
        .page-action-buttons .btn,
        .page-action-buttons .btn-branch-theme { width: 100%; justify-content: center; }
        .page-header-row { flex-direction: column; align-items: stretch !important; gap: 1rem; }
    }
</style>
@endpush

@section('content')
{{-- Page Header --}}
<div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3 page-header-row">
    <div>
        <h1 class="page-title mb-1">{{ $branch->name }}</h1>
        <p class="page-subtitle mb-0">
            <i class="bi bi-shop me-1"></i>{{ $branch->shop?->name ?? '-' }}
            &nbsp;&bull;&nbsp;
            @if($branch->is_active)
                <span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i>{{ __('branch::branch.active') }}</span>
            @else
                <span class="status-pill status-inactive"><i class="bi bi-dash-circle"></i>{{ __('branch::branch.inactive') }}</span>
            @endif
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap page-action-buttons">
        <a href="{{ route('branch.edit', $branch->id) }}" class="btn-branch-theme">
            <i class="bi bi-pencil"></i>{{ __('app.edit') }}
        </a>
        <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>{{ __('branch::branch.back') }}
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Left: Branch Info --}}
    <div class="col-lg-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-info-circle"></i>{{ __('branch::branch.show_title') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="detail-item">
                    <div class="detail-label">{{ __('branch::branch.shop') }}</div>
                    <div class="detail-value">{{ $branch->shop?->name ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('branch::branch.name') }}</div>
                    <div class="detail-value fw-bold">{{ $branch->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('branch::branch.status') }}</div>
                    <div class="detail-value">
                        @if($branch->is_active)
                            <span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i>{{ __('branch::branch.active') }}</span>
                        @else
                            <span class="status-pill status-inactive"><i class="bi bi-dash-circle"></i>{{ __('branch::branch.inactive') }}</span>
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('branch::branch.created_by') }}</div>
                    <div class="detail-value">{{ $branch->creator?->name ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('app.created_at') }}</div>
                    <div class="detail-value">{{ $branch->created_at?->format('F d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">{{ __('app.updated_at') }}</div>
                    <div class="detail-value">{{ $branch->updated_at?->format('F d, Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="content-card-title" style="color:#b91c1c">
                    <i class="bi bi-exclamation-triangle" style="color:#b91c1c"></i>Danger Zone
                </h5>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Deleting this branch is permanent and cannot be undone. All related data will be removed.</p>
                <form action="{{ route('branch.destroy', $branch->id) }}" method="POST"
                    onsubmit="return confirm('{{ __('branch::branch.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill w-100">
                        <i class="bi bi-trash me-1"></i>{{ __('app.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Contact & Location --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-telephone"></i>Contact & Location
                </h5>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label">{{ __('branch::branch.location') }}</div>
                            <div class="detail-value">
                                @if($branch->location)
                                    <i class="bi bi-geo-alt text-muted me-1"></i>{{ $branch->location }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label">{{ __('branch::branch.phone') }}</div>
                            <div class="detail-value">
                                @if($branch->phone)
                                    <a href="tel:{{ $branch->phone }}" class="text-decoration-none">
                                        <i class="bi bi-telephone text-muted me-1"></i>{{ $branch->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label">{{ __('branch::branch.email') }}</div>
                            <div class="detail-value">
                                @if($branch->email)
                                    <a href="mailto:{{ $branch->email }}" class="text-decoration-none">
                                        <i class="bi bi-envelope text-muted me-1"></i>{{ $branch->email }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-lightning"></i>Quick Actions
                </h5>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-outline-warning rounded-pill w-100">
                            <i class="bi bi-pencil me-1"></i>{{ __('app.edit') }} Branch
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('branch.index', ['shop_id' => $branch->shop_id]) }}" class="btn btn-outline-primary rounded-pill w-100">
                            <i class="bi bi-diagram-3 me-1"></i>All Branches
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('shop.show', $branch->shop_id) }}" class="btn btn-outline-secondary rounded-pill w-100">
                            <i class="bi bi-shop me-1"></i>View Shop
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('branch.create', ['shop_id' => $branch->shop_id]) }}" class="btn-branch-theme w-100 justify-content-center">
                            <i class="bi bi-plus-circle"></i>{{ __('branch::branch.add_new') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
