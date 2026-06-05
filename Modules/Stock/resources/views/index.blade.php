@extends('layouts.app')

@section('title', __('stock::stock.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --stock-ink-900: #0f172a;
        --stock-ink-700: #334155;
        --stock-ink-500: #64748b;
        --stock-brand: #0f766e;
        --stock-brand-deep: #155e75;
        --stock-line: #d8e4ee;
    }

    .stock-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--stock-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.02em;
    }

    .stock-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(15, 118, 110, 0.08);
        color: var(--stock-brand);
        border: 1px solid rgba(15, 118, 110, 0.18);
        border-radius: 999px;
        padding: 0.3rem 0.8rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .page-title {
        font-size: clamp(1.4rem, 2.5vw, 1.85rem);
        font-weight: 800;
        color: var(--stock-ink-900);
    }

    .page-title i {
        color: var(--stock-brand);
    }

    .page-subtitle {
        color: var(--stock-ink-500);
        font-size: 0.88rem;
    }

    .btn-manage-products {
        border-radius: 12px;
        border: 1px solid #cedce9;
        background: #ffffff;
        color: var(--stock-ink-700);
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.52rem 1.1rem;
        transition: all 0.2s ease;
    }

    .btn-manage-products:hover {
        background: #f8fafc;
        color: var(--stock-ink-900);
        border-color: #94a3b8;
    }

    .shop-tabs-container {
        border-bottom: 2px solid var(--stock-line);
        padding-bottom: 0.75rem;
    }

    .shop-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.48rem 0.95rem;
        border-radius: 10px;
        border: 1px solid #d8e4ee;
        background: #ffffff;
        color: var(--stock-ink-700);
        font-weight: 600;
        font-size: 0.84rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .shop-tab-btn:hover {
        background: #f8fafc;
        color: var(--stock-brand);
        border-color: #cbd5e1;
    }

    .shop-tab-btn.active {
        background: var(--stock-brand);
        color: #ffffff;
        border-color: var(--stock-brand);
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.18);
    }

    /* KPI Summary Cards */
    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--stock-line);
        border-radius: 16px;
        padding: 1.15rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05) !important;
    }

    .kpi-card-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .kpi-info {
        display: flex;
        flex-direction: column;
    }

    .kpi-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--stock-ink-500);
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }

    .kpi-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--stock-ink-900);
        line-height: 1;
    }

    .kpi-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bg-teal-light {
        background-color: rgba(15, 118, 110, 0.08);
    }

    .text-teal {
        color: var(--stock-brand);
    }

    .bg-amber-light {
        background-color: rgba(245, 158, 11, 0.1);
    }

    .text-amber {
        color: #d97706 !important;
    }

    .bg-red-light {
        background-color: rgba(220, 38, 38, 0.1);
    }

    .text-red {
        color: #dc2626 !important;
    }

    .bg-neutral-light {
        background-color: #f1f5f9;
    }

    .text-neutral {
        color: #64748b;
    }

    /* Horizontal Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--stock-line);
        border-radius: 14px;
        padding: 0.75rem 1rem;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: var(--stock-ink-500);
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 10;
    }

    .filter-search-control {
        padding-left: 36px !important;
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        height: 38px !important;
        color: var(--stock-ink-900) !important;
    }

    .filter-search-control:focus {
        background-color: #ffffff !important;
        border-color: var(--stock-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .btn-filter-submit {
        background: var(--stock-brand);
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        border: 1px solid var(--stock-brand);
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-filter-submit:hover {
        background: var(--stock-brand-deep);
        border-color: var(--stock-brand-deep);
        color: #ffffff;
    }

    .btn-filter-clear {
        background: #ffffff;
        color: var(--stock-ink-700);
        border: 1px solid #cedce9;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-filter-clear:hover {
        background: #f8fafc;
        color: var(--stock-ink-900);
        border-color: #cbd5e1;
    }

    /* Content Card & Stock Table */
    .content-card {
        background: #ffffff;
        border: 1px solid var(--stock-line);
        border-radius: 16px;
        overflow: hidden;
    }

    .content-card-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--stock-line);
        padding: 1rem 1.25rem;
    }

    .content-card-title {
        margin: 0;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--stock-ink-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .table-results-badge {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--stock-ink-500);
        background: #ffffff;
        border: 1px solid var(--stock-line);
        border-radius: 999px;
        padding: 0.2rem 0.6rem;
    }

    .stock-table {
        width: 100%;
        border-collapse: collapse;
    }

    .stock-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--stock-line) !important;
        color: var(--stock-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 1rem;
        white-space: nowrap;
    }

    .stock-table tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.8rem 1rem;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .stock-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .custom-shop-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.2rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.08);
        color: var(--stock-brand);
        border: 1px solid rgba(15, 118, 110, 0.15);
        white-space: nowrap;
    }

    .product-name {
        color: var(--stock-ink-900);
        font-weight: 600;
    }

    .text-semibold-muted {
        color: var(--stock-ink-700);
        font-weight: 500;
    }

    .stock-total-value {
        color: var(--stock-ink-900);
        font-weight: 700;
    }

    .bg-light-soft {
        background-color: #f8fafc;
    }

    /* Attribute tags */
    .attribute-badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        max-width: 220px;
        margin: 0;
        padding: 0;
    }

    .custom-attr-badge {
        display: inline-flex;
        align-items: center;
        background-color: #f1f5f9;
        color: var(--stock-ink-700);
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 0.1rem 0.35rem;
        font-size: 0.68rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .custom-attr-badge strong {
        font-weight: 700;
        color: var(--stock-ink-900);
    }

    /* Status badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        border-width: 1px;
        border-style: solid;
        white-space: nowrap;
    }

    .status-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-pill-success {
        background: rgba(16, 185, 129, 0.08);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-pill-success .status-indicator {
        background-color: #10b981;
    }

    .status-pill-warning {
        background: rgba(245, 158, 11, 0.08);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .status-pill-warning .status-indicator {
        background-color: #f59e0b;
    }

    .status-pill-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .status-pill-danger .status-indicator {
        background-color: #ef4444;
        animation: statusPulse 2s infinite ease-in-out;
    }

    @keyframes statusPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
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
        color: var(--stock-ink-500);
        margin-bottom: 0;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="stock-shell">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
        <div>
            <span class="stock-kicker mb-2"><i class="bi bi-boxes"></i> {{ __('stock::stock.title') }}</span>
            <h1 class="page-title display-font mb-1"><i class="bi bi-boxes"></i> {{ __('stock::stock.title') }}</h1>
            <p class="page-subtitle mb-0">{{ __('stock::stock.subtitle') }}</p>
        </div>
        <a href="{{ route('product.index') }}" class="btn btn-manage-products shadow-sm">
            <i class="bi bi-box-seam"></i> {{ __('stock::stock.manage_products') }}
        </a>
    </div>

    {{-- Shop Tabs Selector --}}
    <div class="shop-tabs-container mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('stock.index', array_filter(['search' => $searchTerm])) }}"
               class="shop-tab-btn {{ is_null($selectedShopId) ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                <span>{{ __('stock::stock.all_shops') }}</span>
            </a>
            @foreach($shops as $shop)
                <a href="{{ route('stock.index', array_filter(['shop_id' => $shop->id, 'search' => $searchTerm])) }}"
                   class="shop-tab-btn {{ (int)$selectedShopId === (int)$shop->id ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>{{ $shop->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- KPI Cards Metrics Computation --}}
    @php
        $activeShops = is_null($selectedShopId) ? $shops : $shops->where('id', $selectedShopId);
        $kpiTotalProducts = 0;
        $kpiTotalUnits = 0;
        $kpiStockValue = 0;
        $kpiLowStock = 0;
        $kpiOutOfStock = 0;

        foreach ($activeShops as $shop) {
            $kpiTotalProducts += $shop->products->count();
            $kpiTotalUnits += $shop->products->sum('stock_quantity');
            $kpiStockValue += $shop->products->sum(function ($product) {
                return $product->stock_quantity * $product->purchase_price;
            });
            $kpiLowStock += $shop->products->filter(function ($product) {
                return $product->stock_quantity > 0 && $product->stock_quantity <= (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5);
            })->count();
            $kpiOutOfStock += $shop->products->filter(function ($product) {
                return $product->stock_quantity <= 0;
            })->count();
        }
    @endphp

    {{-- KPI Summary Cards Grid --}}
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Products -->
        <div class="col-6 col-md">
            <div class="kpi-card shadow-sm h-100">
                <div class="kpi-card-inner">
                    <div class="kpi-info">
                        <span class="kpi-label">{{ __('stock::stock.total_products') }}</span>
                        <strong class="kpi-value display-font">{{ $kpiTotalProducts }}</strong>
                    </div>
                    <div class="kpi-icon-wrapper bg-teal-light">
                        <i class="bi bi-grid text-teal"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Units -->
        <div class="col-6 col-md">
            <div class="kpi-card shadow-sm h-100">
                <div class="kpi-card-inner">
                    <div class="kpi-info">
                        <span class="kpi-label">{{ __('stock::stock.total_units') }}</span>
                        <strong class="kpi-value display-font">{{ number_format($kpiTotalUnits) }}</strong>
                    </div>
                    <div class="kpi-icon-wrapper bg-teal-light">
                        <i class="bi bi-boxes text-teal"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Stock Value -->
        <div class="col-6 col-md">
            <div class="kpi-card shadow-sm h-100">
                <div class="kpi-card-inner">
                    <div class="kpi-info">
                        <span class="kpi-label">{{ __('stock::stock.stock_value') }}</span>
                        <strong class="kpi-value display-font">{{ number_format($kpiStockValue, 2) }}</strong>
                    </div>
                    <div class="kpi-icon-wrapper bg-teal-light">
                        <i class="bi bi-currency-dollar text-teal"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Low Stock -->
        <div class="col-6 col-md">
            <div class="kpi-card shadow-sm h-100">
                <div class="kpi-card-inner">
                    <div class="kpi-info">
                        <span class="kpi-label">{{ __('stock::stock.low_stock') }}</span>
                        <strong class="kpi-value display-font {{ $kpiLowStock > 0 ? 'text-amber' : '' }}">{{ $kpiLowStock }}</strong>
                    </div>
                    <div class="kpi-icon-wrapper {{ $kpiLowStock > 0 ? 'bg-amber-light' : 'bg-neutral-light' }}">
                        <i class="bi bi-exclamation-triangle {{ $kpiLowStock > 0 ? 'text-amber' : 'text-neutral' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Out of Stock -->
        <div class="col-12 col-md">
            <div class="kpi-card shadow-sm h-100">
                <div class="kpi-card-inner">
                    <div class="kpi-info">
                        <span class="kpi-label">{{ __('stock::stock.out_of_stock') }}</span>
                        <strong class="kpi-value display-font {{ $kpiOutOfStock > 0 ? 'text-red' : '' }}">{{ $kpiOutOfStock }}</strong>
                    </div>
                    <div class="kpi-icon-wrapper {{ $kpiOutOfStock > 0 ? 'bg-red-light' : 'bg-neutral-light' }}">
                        <i class="bi bi-x-circle {{ $kpiOutOfStock > 0 ? 'text-red' : 'text-neutral' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single-row Filter Toolbar --}}
    <div class="filter-toolbar mb-4 shadow-sm">
        <form action="{{ route('stock.index') }}" method="GET" class="m-0 w-100">
            @if($selectedShopId)
                <input type="hidden" name="shop_id" value="{{ $selectedShopId }}">
            @endif
            <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control filter-search-control"
                            value="{{ $searchTerm ?? '' }}"
                            placeholder="{{ __('stock::stock.search_placeholder') }}">
                    </div>
                </div>
                <div class="flex-shrink-0 d-flex gap-2">
                    <button type="submit" class="btn btn-filter-submit">
                        <i class="bi bi-funnel"></i> <span class="d-none d-sm-inline">{{ __('stock::stock.apply_filter') }}</span>
                    </button>
                    @if($searchTerm)
                        <a href="{{ route('stock.index', array_filter(['shop_id' => $selectedShopId])) }}" class="btn btn-filter-clear">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Stock Table Card --}}
    <div class="content-card shadow-sm mb-4">
        <div class="content-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="content-card-title">
                <i class="bi bi-table text-teal"></i>
                {{ __('stock::stock.stock_table_title') }}
            </h5>
        </div>

        <div class="table-responsive p-3">
            <table class="table align-middle stock-table mb-0 w-100" id="stockDataTable">
                <thead>
                    <tr>
                        <th>{{ __('stock::stock.shop') }}</th>
                        <th>{{ __('stock::stock.product') }}</th>
                        <th>{{ __('stock::stock.created_by') }}</th>
                        <th>{{ __('stock::stock.category') }}</th>
                        <th>{{ __('stock::stock.brand') }}</th>
                        <th>{{ __('stock::stock.custom_attributes') }}</th>
                        <th class="text-end">{{ __('stock::stock.purchase_price') }}</th>
                        <th class="text-end">{{ __('stock::stock.sale_price') }}</th>
                        <th class="text-center">{{ __('stock::stock.current_stock') }}</th>
                        <th class="text-end">{{ __('stock::stock.stock_value') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.jQuery || !$('#stockDataTable').length) {
            return;
        }

        var table = $('#stockDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("stock.index") }}',
                data: function (d) {
                    d.shop_id = '{{ $selectedShopId }}';
                    d.search = $('#search').val();
                }
            },
            columns: [
                { data: 'shop_badge', name: 'shop.name', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'creator_name', name: 'creator.name', orderable: false, searchable: false },
                { data: 'category', name: 'category' },
                { data: 'brand', name: 'brand' },
                { data: 'custom_attributes', name: 'custom_attributes', orderable: false, searchable: false },
                { data: 'purchase_price', name: 'purchase_price', className: 'text-end' },
                { data: 'sale_price', name: 'sale_price', className: 'text-end' },
                { data: 'stock_quantity', name: 'stock_quantity', className: 'text-center', orderable: true, searchable: false },
                { data: 'stock_value', name: 'stock_value', className: 'text-end', orderable: false, searchable: false }
            ],
            pageLength: 20,
            order: [[1, 'asc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: 'Search stocks...',
            },
            dom: 'rtip',
        });

        // Dynamic reload on filter change to avoid page refresh
        $('.filter-toolbar form').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endpush
