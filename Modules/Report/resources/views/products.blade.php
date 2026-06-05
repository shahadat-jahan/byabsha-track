@extends('layouts.app')

@section('title', __('report.products_report'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-box-seam"></i> {{ __('report.products_report') }}</h1>
        <p class="page-subtitle">{{ __('report.products_report_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('report.export.products-pdf', request()->query()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> {{ __('report.download_pdf') }}
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer"></i> {{ __('report.print') }}
        </button>
        <a href="{{ route('report.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('report.back_to_reports') }}
        </a>
    </div>
</div>

<!-- Filter -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-funnel"></i>
            {{ __('report.filters') }}
        </h5>
    </div>
    <div class="p-4">
        <form action="{{ route('report.products') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('report.shop') }}</label>
                    <select class="form-select" id="shop_id" name="shop_id">
                        <option value="">{{ __('report.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ $filters['shop_id'] == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('report.apply_filters') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.total_products') }}</p>
                <h4 class="mb-0">{{ $stockSummary['total_products'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.stock_value') }}</p>
                <h4 class="mb-0">{{ number_format($stockSummary['total_stock_value'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.potential_revenue') }}</p>
                <h4 class="mb-0">{{ number_format($stockSummary['total_potential_revenue'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.potential_profit') }}</p>
                <h4 class="mb-0 text-success">{{ number_format($stockSummary['total_potential_profit'], 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.low_stock_label') }}</p>
                <h4 class="mb-0 {{ $stockSummary['low_stock_count'] > 0 ? 'text-warning' : 'text-muted' }}">
                    {{ $stockSummary['low_stock_count'] }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="content-card">
            <div class="p-3 text-center">
                <p class="text-muted small mb-1">{{ __('report.out_of_stock') }}</p>
                <h4 class="mb-0 {{ $stockSummary['out_of_stock_count'] > 0 ? 'text-danger' : 'text-muted' }}">
                    {{ $stockSummary['out_of_stock_count'] }}
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-table"></i>
            {{ __('report.product_details') }}
        </h5>
    </div>
    <div class="table-responsive">
    @if($products->count() > 0)
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.product_name') }}</th>
                    <th>{{ __('report.category') }}</th>
                    <th>{{ __('report.brand') }}</th>
                    <th>{{ __('report.shop') }}</th>
                    <th class="text-end">{{ __('report.purchase_price') }}</th>
                    <th class="text-end">{{ __('report.sale_price') }}</th>
                    <th class="text-center">{{ __('report.stock_qty') }}</th>
                    <th class="text-end">{{ __('report.inventory_value') }}</th>
                    <th class="text-center">{{ __('report.units_sold') }}</th>
                    <th class="text-end">{{ __('report.total_revenue_col') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td class="text-muted">{{ $product->category ?? '-' }}</td>
                    <td class="text-muted">{{ $product->brand ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $product->shop->name }}</span></td>
                    <td class="text-end">{{ number_format($product->purchase_price, 2) }}</td>
                    <td class="text-end">{{ number_format($product->sale_price, 2) }}</td>
                    <td class="text-center">
                        @if($product->stock_quantity == 0)
                            <span class="badge bg-danger">{{ __('report.out_of_stock_badge') }}</span>
                        @elseif($product->stock_quantity <= (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5))
                            <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                        @endif
                    </td>
                    <td class="text-end"><strong>{{ number_format($product->stock_quantity * $product->purchase_price, 2) }}</strong></td>
                    <td class="text-center">{{ $product->total_units_sold }}</td>
                    <td class="text-end text-success">{{ number_format($product->total_revenue, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
    <div class="p-5 text-center text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        <p class="mb-0">{{ __('report.no_products_found') }}</p>
    </div>
    @endif
    </div>
</div>

@push('styles')
<style>
    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .content-card.mb-4:has(.bi-funnel),
        .alert,
        .btn,
        a.btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        body {
            background: white !important;
            font-size: 11px;
        }
        .content-card {
            border: none !important;
            box-shadow: none !important;
        }
        .table { font-size: 10px; }
    }
</style>
@endpush
@endsection