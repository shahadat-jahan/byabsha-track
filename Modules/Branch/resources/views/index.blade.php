@extends('layouts.app')

@section('title', __('branch::branch.title'))

@push('styles')
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
        --branch-ink-900: #0f172a;
        --branch-ink-700: #334155;
        --branch-ink-500: #64748b;

    .btn-branch-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }

    .btn-branch-theme:hover,
    .btn-branch-theme:focus,
    .btn-branch-theme:active {
        text-decoration: none;
        outline: none;
    }

    .branch-shell {
        position: relative;
    }

    .branch-header {
        gap: 0.9rem;
    }

    .branch-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .branch-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--branch-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .branch-page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .btn-add-branch {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-add-branch:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
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

    /* Table */
    .table-custom { margin-bottom: 0; }

    .table-custom thead th {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover { background: #fbfdff; }

    .branch-name-cell {
        color: var(--branch-ink-900);
        font-weight: 700;
    }

    .branch-date-cell {
        color: var(--branch-ink-500);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.74rem;
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

    .action-btn {
        border-radius: 10px !important;
        padding: 0.32rem 0.5rem;
        border-width: 1px;
        font-size: 0.8rem;
    }

    .btn-outline-info {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }
    .btn-outline-info:hover { background: #0f766e; border-color: #0f766e; color: #fff; }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }
    .btn-outline-warning:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }
    .btn-outline-danger:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

    /* Mobile card view */
    .branch-mobile-cards { display: none; }

    .branch-mobile-card {
        background: #fff;
        border: 1px solid var(--branch-line);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .branch-mobile-card:last-child { margin-bottom: 0; }

    .branch-mobile-card .bmc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.65rem;
    }

    .branch-mobile-card .bmc-meta {
        font-size: 0.8rem;
        color: var(--branch-ink-500);
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .branch-mobile-card .bmc-actions {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.8rem;
        padding-top: 0.75rem;
        border-top: 1px solid #edf3f8;
    }

    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-state i { font-size: 2.5rem; color: #8aa0b6; display: block; margin-bottom: 0.65rem; }

    .btn-create-first {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }
    .btn-create-first:hover { color: #fff; }

    @media (max-width: 767.98px) {
        .branch-header { flex-direction: column; align-items: stretch !important; }
        .btn-branch-theme { width: 100%; justify-content: center; }
        .btn-add-branch { width: 100%; justify-content: center; }
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--branch-line);
        border-radius: 10px;
        padding: 0.35rem 1.8rem 0.35rem 0.75rem;
        font-size: 0.88rem;
        color: var(--branch-ink-700);
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--branch-line);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.88rem;
        color: var(--branch-ink-900);
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        outline: none;
        border-color: var(--branch-brand);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }
    .dataTables_info {
        font-size: 0.85rem;
        color: var(--branch-ink-500);
        padding-top: 1rem !important;
    }
    .dataTables_paginate {
        padding-top: 1rem !important;
    }
    .paginate_button.page-item.active .page-link {
        background-color: var(--branch-brand) !important;
        border-color: var(--branch-brand) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="branch-shell">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap branch-header">
        <div>
            <span class="branch-kicker"><i class="bi bi-diagram-3"></i>{{ __('branch::branch.title') }}</span>
            <h1 class="branch-title fw-bold">{{ __('branch::branch.title') }}</h1>
            <p class="text-muted mb-0">{{ __('branch::branch.subtitle') }}</p>
        </div>
        <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-branch-theme">
            <i class="bi bi-plus-circle me-1"></i>{{ __('branch::branch.add_new') }}
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title"><i class="bi bi-funnel"></i>{{ __('branch::branch.shop_filter') }}</h5>
    </div>
    <div class="p-3 p-md-4">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-8 col-md-9">
                <select id="shop_id" name="shop_id" class="form-select">
                    <option value="">{{ __('branch::branch.all_shops') }}</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ (string) $selectedShopId === (string) $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary fw-semibold flex-fill">
                    <i class="bi bi-search me-1 d-none d-sm-inline"></i>{{ __('app.apply_filters') }}
                </button>
                <button type="button" id="resetFilters" class="btn btn-outline-secondary" title="{{ __('app.back_to_list') }}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Branches Table Card --}}
<div class="content-card">
    <div class="table-responsive p-3">
        <table id="branchesTable" class="table table-custom w-100">
            <thead>
                <tr>
                    <th>{{ __('branch::branch.name') }}</th>
                    <th>{{ __('branch::branch.shop') }}</th>
                    <th>{{ __('branch::branch.location') }}</th>
                    <th>{{ __('branch::branch.phone') }}</th>
                    <th>{{ __('branch::branch.created_by') }}</th>
                    <th>{{ __('branch::branch.status') }}</th>
                    <th>{{ __('app.created_at') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#branchesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('branch.index') }}",
                data: function(d) {
                    d.shop_id = $('#shop_id').val();
                }
            },
            columns: [
                { data: 'name', name: 'name', render: function(data, type, row) {
                    return '<strong class="branch-name-cell">' + escapeHtml(data) + '</strong>';
                }},
                { data: 'shop_name', name: 'shop.name', defaultContent: '-' },
                { data: 'location', name: 'location', defaultContent: '-', render: function(data) {
                    return data ? escapeHtml(data) : '-';
                }},
                { data: 'phone', name: 'phone', defaultContent: '-', render: function(data) {
                    return data ? escapeHtml(data) : '-';
                }},
                { data: 'creator_name', name: 'creator.name', defaultContent: '-', render: function(data) {
                    return data ? escapeHtml(data) : '-';
                }},
                { data: 'status', name: 'is_active', orderable: false, searchable: false },
                { data: 'created_at_formatted', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[0, 'asc']],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                searchPlaceholder: "Search branches...",
                search: ""
            }
        });

        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        $('#resetFilters').on('click', function() {
            $('#shop_id').val('');
            table.ajax.reload();
        });
        
        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
</script>
@endpush
