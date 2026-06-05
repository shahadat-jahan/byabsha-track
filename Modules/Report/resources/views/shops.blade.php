@extends('layouts.app')

@section('title', __('report.shops_report'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-shop-window"></i> {{ __('report.shops_report') }}</h1>
        <p class="page-subtitle">{{ __('report.shops_report_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('report.export.shops-pdf', request()->query()) }}" class="btn btn-danger">
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
        <form action="{{ route('report.shops') }}" method="GET">
            <div class="row">
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

<!-- Shops Comparison Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-bar-chart"></i>
            {{ __('report.shop_comparison') }}
        </h5>
    </div>
    <div class="table-responsive">
    @if($shopData->count() > 0)
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.shop_name') }}</th>
                    <th class="text-center">{{ __('report.total_products') }}</th>
                    <th class="text-end">{{ __('report.stock_value_capital') }}</th>
                    <th class="text-center">{{ __('report.total_sales_count') }}</th>
                    <th class="text-end">{{ __('report.total_revenue') }}</th>
                    <th class="text-end">{{ __('report.total_profit') }}</th>
                    <th class="text-end">{{ __('report.profit_margin') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shopData as $shop)
                <tr>
                    <td><span class="badge bg-primary">{{ $shop->name }}</span></td>
                    <td class="text-center">{{ $shop->total_products }}</td>
                    <td class="text-end">{{ number_format($shop->stock_value, 2) }}</td>
                    <td class="text-center">{{ $shop->total_sales }}</td>
                    <td class="text-end">{{ number_format($shop->total_revenue, 2) }}</td>
                    <td class="text-end text-success">{{ number_format($shop->total_profit, 2) }}</td>
                    <td class="text-end">
                        <span class="badge {{ $shop->profit_margin >= 20 ? 'bg-success' : ($shop->profit_margin >= 10 ? 'bg-warning' : 'bg-danger') }}">
                            {{ number_format($shop->profit_margin, 1) }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td>{{ __('report.grand_total') }}</td>
                    <td class="text-center">{{ $shopData->sum('total_products') }}</td>
                    <td class="text-end">{{ number_format($shopData->sum('stock_value'), 2) }}</td>
                    <td class="text-center">{{ $shopData->sum('total_sales') }}</td>
                    <td class="text-end">{{ number_format($shopData->sum('total_revenue'), 2) }}</td>
                    <td class="text-end text-success">{{ number_format($shopData->sum('total_profit'), 2) }}</td>
                    <td class="text-end">
                        @php
                            $totalRev = $shopData->sum('total_revenue');
                            $totalProf = $shopData->sum('total_profit');
                            $overallMargin = $totalRev > 0 ? ($totalProf / $totalRev) * 100 : 0;
                        @endphp
                        <span class="badge {{ $overallMargin >= 20 ? 'bg-success' : ($overallMargin >= 10 ? 'bg-warning' : 'bg-danger') }}">
                            {{ number_format($overallMargin, 1) }}%
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
    <div class="p-5 text-center text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        <p class="mb-0">{{ __('report.no_shops_found') }}</p>
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