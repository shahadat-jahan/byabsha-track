@extends('layouts.app')

@section('title', __('product.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --product-ink-900: #0f172a;
        --product-ink-700: #334155;
        --product-ink-500: #64748b;
        --product-brand: #0f766e;
        --product-brand-deep: #155e75;
        --product-line: #d8e4ee;
    }

    .product-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--product-ink-900);
    }

    .product-shell::before {
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

    .product-header {
        gap: 0.9rem;
    }

    .product-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--product-brand);
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
        color: var(--product-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--product-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-product {
        background: linear-gradient(140deg, var(--product-brand), var(--product-brand-deep));
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

    .btn-add-product:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .btn-manage-fields {
        border-radius: 999px;
        padding: 0.62rem 1.05rem;
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid #9eb8cb;
        color: #1f3f58;
        background: #f7fbff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .btn-manage-fields:hover {
        color: #0f172a;
        border-color: #6f93b0;
        background: #ffffff;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--product-line);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .control-hub {
        padding: 0.9rem;
    }

    .control-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        align-items: end;
    }

    .control-row + .control-row {
        margin-top: 0.7rem;
        padding-top: 0.7rem;
        border-top: 1px dashed #deebf4;
    }

    .control-item {
        flex: 1 1 180px;
        min-width: 0;
    }

    .control-item.search-item {
        flex: 2 1 320px;
    }

    .compact-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #55708a;
        margin-bottom: 0.25rem;
        display: block;
    }

    .compact-input,
    .compact-select {
        min-height: 36px;
        font-size: 0.87rem;
        border-radius: 10px;
        border-color: #d4e2ee;
    }

    .compact-actions {
        display: flex;
        gap: 0.45rem;
        flex: 0 0 auto;
    }

    .compact-btn {
        min-height: 36px;
        padding: 0.42rem 0.78rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .shop-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.65rem;
    }

    .shop-card-link {
        display: block;
        text-decoration: none;
        border: 1px solid #d4e2ee;
        border-radius: 12px;
        padding: 0.72rem;
        background: #fbfdff;
        color: #243b53;
        transition: all 0.2s ease;
    }

    .shop-card-link:hover {
        border-color: #7aa4c4;
        background: #ffffff;
        transform: translateY(-1px);
        color: #102a43;
    }

    .shop-card-link.active {
        border-color: rgba(15, 118, 110, 0.45);
        background: rgba(15, 118, 110, 0.08);
        box-shadow: inset 0 0 0 1px rgba(15, 118, 110, 0.18);
    }

    .shop-card-name {
        font-weight: 700;
        font-size: 0.9rem;
        line-height: 1.2;
        margin-bottom: 0.34rem;
    }

    .shop-card-meta {
        font-size: 0.76rem;
        color: #577089;
    }

    .selected-shop-title {
        font-size: 0.86rem;
        font-weight: 800;
        color: #2f4d67;
        margin-bottom: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f7fbff !important;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.64rem 0.72rem;
        white-space: nowrap;
    }

    .table tbody td {
        border-color: #e7edf4;
        padding: 0.58rem 0.72rem;
        vertical-align: middle;
        font-size: 0.87rem;
    }

    .table tbody tr:hover {
        background: #fbfdff;
    }

    .product-name {
        font-weight: 700;
        color: #1e293b;
    }

    .shop-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .price-value {
        font-weight: 700;
        color: #0f172a;
    }

    .stock-badge {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .stock-low {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .stock-mid {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .stock-high {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .btn-group .btn {
        border-radius: 8px !important;
        margin-right: 0.2rem;
        padding: 0.26rem 0.42rem;
        border-width: 1px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-outline-primary {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }

    .btn-outline-primary:hover {
        background: #0f766e;
        border-color: #0f766e;
        color: #fff;
    }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }

    .btn-outline-warning:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }

    .empty-state {
        padding: 2.8rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.3rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .empty-state h3 {
        font-size: 1.15rem;
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        color: var(--product-ink-500);
        margin-bottom: 0.95rem;
    }

    .btn-empty-add {
        background: linear-gradient(140deg, var(--product-brand), var(--product-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .btn-empty-add:hover {
        color: #fff;
    }

    .content-card .pagination {
        margin-bottom: 0;
    }

    .filter-grid .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #4b637b;
    }

    .category-accordion-title {
        font-size: 0.86rem;
        font-weight: 700;
        color: #29445d;
        margin-bottom: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .product-category-accordion .accordion-item {
        border: 1px solid #dce8f3;
        border-radius: 12px !important;
        overflow: hidden;
        margin-bottom: 0.6rem;
    }

    .product-category-accordion .accordion-button {
        background: #f8fbff;
        color: #1f3f58;
        font-weight: 700;
        padding: 0.7rem 0.9rem;
        box-shadow: none;
    }

    .product-category-accordion .accordion-button:not(.collapsed) {
        background: #eef8ff;
        color: #0f766e;
    }

    .category-link {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
    }

    .category-count {
        font-size: 0.72rem;
        padding: 0.3rem 0.56rem;
        border-radius: 999px;
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.2);
    }

    @media (max-width: 767.98px) {
        .product-header {
            align-items: stretch !important;
        }

        .btn-add-product {
            width: 100%;
            justify-content: center;
        }

        .compact-actions {
            width: 100%;
        }

        .compact-btn {
            flex: 1;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="product-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap product-header">
    <div>
        <span class="product-kicker"><i class="bi bi-box-seam"></i>{{ __('product.title') }}</span>
        <h1 class="page-title display-font">{{ __('product.title') }}</h1>
        <p class="page-subtitle">{{ __('product.subtitle') }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('product.dynamic-fields.index') }}" class="btn-manage-fields">
            <i class="bi bi-sliders"></i> {{ __('product.manage_dynamic_fields') }}
        </a>
        <a href="{{ route('product.create', ['shop_id' => $selectedShopId]) }}" class="btn-add-product">
            <i class="bi bi-plus-circle"></i> {{ __('product.add_new') }}
        </a>
    </div>
</div>

<div class="content-card mb-3 p-3">
    <div class="selected-shop-title">{{ __('product.filter_shop') }}</div>
    @if($shops->count() > 0)
        <div class="shop-card-grid">
            @foreach($shops as $shop)
                <a href="{{ route('product.index', ['shop_id' => $shop->id]) }}"
                   class="shop-card-link {{ (int) ($selectedShopId ?? 0) === (int) $shop->id ? 'active' : '' }}">
                    <div class="shop-card-name">{{ $shop->name }}</div>
                    <div class="shop-card-meta">{{ number_format($shop->products_count) }} {{ __('product.title') }}</div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-muted small">{{ __('product.no_products_sub') }}</div>
    @endif
</div>

@if($selectedShopId)
<div class="content-card mb-3 control-hub">
    <div class="selected-shop-title">{{ $selectedShop?->name ?? __('product.filter_shop') }}</div>
    <form method="GET" action="{{ route('product.index') }}" class="control-row">
        <input type="hidden" name="shop_id" value="{{ $selectedShopId }}">
        <div class="control-item">
            <label for="categoryNameFilter" class="compact-label">{{ __('product.filter_category_name') }}</label>
            <select class="form-select compact-select" id="categoryNameFilter" name="category_id">
                <option value="">{{ __('product.all_categories_filter') }}</option>
                @foreach($categoryOptions as $category)
                    <option value="{{ $category->id }}" {{ (int) ($selectedCategoryId ?? 0) === (int) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="control-item search-item">
            <label for="searchFilter" class="compact-label">{{ __('product.search_filter') }}</label>
            <input
                type="text"
                id="searchFilter"
                name="search"
                class="form-control compact-input"
                list="searchSuggestions"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="{{ __('product.search_filter_placeholder') }}">
            <datalist id="searchSuggestions">
                @foreach($searchSuggestions as $suggestion)
                    <option value="{{ $suggestion }}"></option>
                @endforeach
            </datalist>
        </div>

        <div class="compact-actions">
            <button type="submit" class="btn btn-outline-primary compact-btn">
                <i class="bi bi-funnel"></i> {{ __('app.filter') }}
            </button>
            <a href="{{ route('product.index', ['shop_id' => $selectedShopId]) }}" class="btn btn-outline-secondary compact-btn">
                <i class="bi bi-x-circle"></i> {{ __('product.clear_filters') }}
            </a>
        </div>
    </form>
</div>

<div class="content-card">
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle mb-0 w-100" id="productDataTable">
            <thead class="table-light">
                <tr>
                    <th>{{ __('product.col_name') }}</th>
                    <th>{{ __('product.col_model_name') }}</th>
                    <th>{{ __('product.col_shop') }}</th>
                    <th>{{ __('product.col_category') }}</th>
                    <th>{{ __('product.col_brand') }}</th>
                    <th>{{ __('product.col_created_by') }}</th>
                    <th class="text-end">{{ __('product.col_purchase_price') }}</th>
                    <th class="text-center">{{ __('product.col_stock') }}</th>
                    <th class="text-center">{{ __('product.col_actions') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@else
<div class="content-card">
    <div class="empty-state">
        <i class="bi bi-shop-window"></i>
        <h3>{{ __('product.filter_shop') }}</h3>
        <p>{{ __('product.category_accordion_subtitle') }}</p>
    </div>
</div>
@endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.jQuery || !$('#productDataTable').length) {
            return;
        }

        var table = $('#productDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("product.index") }}',
                data: function (d) {
                    d.shop_id = '{{ $selectedShopId }}';
                    d.category_id = $('#categoryNameFilter').val();
                    d.search = $('#searchFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'model_name', name: 'model_name', orderable: false, searchable: false },
                { data: 'shop_name', name: 'shop.name', orderable: false },
                { data: 'category', name: 'category' },
                { data: 'brand', name: 'brand' },
                { data: 'creator_name', name: 'creator.name', orderable: false },
                { data: 'purchase_price', name: 'purchase_price', className: 'text-end' },
                { data: 'stock_quantity', name: 'stock_quantity', className: 'text-center', orderable: true, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            pageLength: 15,
            order: [[0, 'asc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: @json(__('product.search_filter_placeholder')),
            },
            dom: 'rtip',
        });

        // Dynamic reload on filter change to avoid page refresh
        $('form.control-row').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endpush
