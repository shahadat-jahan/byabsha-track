@extends('layouts.app')

@section('title', __('report.sales_report'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-graph-up-arrow"></i> {{ __('report.sales_report') }}</h1>
        <p class="page-subtitle">{{ __('report.sales_report_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('report.export.sales-pdf', request()->query()) }}" class="btn btn-danger">
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

<!-- Filters -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-funnel"></i>
            {{ __('report.filters') }}
        </h5>
    </div>
    <div class="p-4">
        <form action="{{ route('report.sales') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold">{{ __('report.start_date') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $filters['start_date'] }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-semibold">{{ __('report.end_date') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $filters['end_date'] }}">
                </div>
                <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_sales') }}</p>
                        <h4 class="mb-0">{{ $salesSummary->total_transactions ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_revenue') }}</p>
                        <h4 class="mb-0">{{ currency_symbol() }}{{ number_format($salesSummary->total_revenue ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_profit') }}</p>
                        <h4 class="mb-0 text-success">{{ currency_symbol() }}{{ number_format($salesSummary->total_profit ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.items_sold') }}</p>
                        <h4 class="mb-0">{{ $salesSummary->total_quantity_sold ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-receipt"></i>
            {{ __('report.sales_transactions') }}
        </h5>
    </div>
    @if($sales->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.date') }}</th>
                    <th>{{ __('report.shop') }}</th>
                    <th>{{ __('report.product_name') }}</th>
                    <th class="text-center">{{ __('report.quantity') }}</th>
                    <th class="text-end">{{ __('report.sale_price') }}</th>
                    <th class="text-end">{{ __('report.total_amount') }}</th>
                    <th class="text-end">{{ __('report.profit') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                    <td><span class="badge bg-primary">{{ $sale->shop->name }}</span></td>
                    <td><strong>{{ $sale->product->name }}</strong></td>
                    <td class="text-center">{{ $sale->quantity }}</td>
                    <td class="text-end">{{ currency_symbol() }}{{ number_format($sale->sale_price, 2) }}</td>
                    <td class="text-end"><strong>{{ currency_symbol() }}{{ number_format($sale->total_amount, 2) }}</strong></td>
                    <td class="text-end text-success">{{ currency_symbol() }}{{ number_format($sale->profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td colspan="3">{{ __('report.page_total') }}</td>
                    <td class="text-center">{{ $sales->sum('quantity') }}</td>
                    <td class="text-end"></td>
                    <td class="text-end">{{ currency_symbol() }}{{ number_format($sales->sum('total_amount'), 2) }}</td>
                    <td class="text-end text-success">{{ currency_symbol() }}{{ number_format($sales->sum('profit'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-3">
        {{ $sales->links() }}
    </div>
    @else
    <div class="p-5 text-center text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        <p class="mb-0">{{ __('report.no_sales_found') }}</p>
    </div>
    @endif
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
