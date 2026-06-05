<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('report.title') }} - {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1e293b; line-height: 1.5; background: #fff; }
        .screen-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .screen-toolbar span { font-size: 0.85rem; color: #64748b; }
        .screen-toolbar .toolbar-actions { display: flex; gap: 0.5rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.9rem; font-size: 0.82rem; font-weight: 600; border-radius: 8px; border: 1px solid transparent; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #2563eb; color: #fff; border-color: #2563eb; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-ghost { background: transparent; color: #475569; border-color: #d1d5db; }
        .btn-ghost:hover { background: #f1f5f9; }
        .report-wrap { max-width: 900px; margin: 0 auto; padding: 1.5rem 2rem; }
        .report-header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .report-header h1 { font-size: 22px; font-weight: 800; color: #2563eb; margin-bottom: 0.25rem; }
        .report-header .shop-name { font-size: 14px; font-weight: 600; color: #334155; }
        .report-header .date-range { font-size: 12px; color: #64748b; margin-top: 0.2rem; }
        .report-header .generated { font-size: 10px; color: #94a3b8; margin-top: 0.4rem; }
        .kpi-row { display: table; width: 100%; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 1.5rem; overflow: hidden; }
        .kpi-cell { display: table-cell; width: 25%; text-align: center; padding: 0.75rem 0.5rem; border-right: 1px solid #e2e8f0; vertical-align: middle; }
        .kpi-cell:last-child { border-right: none; }
        .kpi-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; }
        .kpi-value { font-size: 18px; font-weight: 800; color: #1e293b; margin-top: 0.15rem; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.4rem; margin-bottom: 0.75rem; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        .report-table thead th { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 6px 8px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #334155; }
        .report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 11px; }
        .report-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .report-table tfoot td { border: 1px solid #cbd5e1; padding: 6px 8px; font-weight: 700; background: #f1f5f9; font-size: 11px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .detail-section { margin-bottom: 1.5rem; }
        .detail-date-header { font-size: 11px; font-weight: 700; color: #2563eb; padding: 0.35rem 0.5rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; margin-bottom: 0.3rem; }
        .detail-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 0.5rem; }
        .detail-table thead th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 6px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em; }
        .detail-table tbody td { border: 1px solid #e2e8f0; padding: 4px 6px; }
        .report-footer { border-top: 1px solid #e2e8f0; padding-top: 0.75rem; margin-top: 2rem; font-size: 10px; color: #94a3b8; display: flex; justify-content: space-between; }
        @media print {
            .screen-toolbar { display: none !important; }
            body { font-size: 11px; }
            .report-wrap { padding: 0; max-width: 100%; }
            .kpi-value { font-size: 15px; }
            .report-table thead th, .report-table tbody td, .report-table tfoot td { font-size: 9.5px; padding: 4px 6px; }
            .detail-table thead th, .detail-table tbody td { font-size: 8.5px; padding: 3px 5px; }
        }
    </style>
</head>
<body>
<div class="screen-toolbar">
    <span>{{ __('report.title') }} &mdash; {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</span>
    <div class="toolbar-actions">
        <button class="btn btn-primary" onclick="window.print()">&#128438; {{ __('report.print') }}</button>
        <a href="{{ route('report.index', request()->query()) }}" class="btn btn-ghost">&#8592; {{ __('report.back_to_reports') }}</a>
    </div>
</div>
<div class="report-wrap">
    <div class="report-header">
        <h1>{{ __('report.title') }}</h1>
        <div class="shop-name">{{ $shopName }}</div>
        <div class="date-range">{{ $filters['start_date'] }} &mdash; {{ $filters['end_date'] }}</div>
        <div class="generated">{{ __('report.generated') }}: {{ now()->format('d M Y, H:i') }}</div>
    </div>
    <div class="kpi-row">
        <div class="kpi-cell">
            <div class="kpi-label">{{ __('report.total_sales') }}</div>
            <div class="kpi-value">{{ $salesSummary->total_transactions ?? 0 }}</div>
        </div>
        <div class="kpi-cell">
            <div class="kpi-label">{{ __('report.total_revenue') }}</div>
            <div class="kpi-value">{{ number_format($salesSummary->total_revenue ?? 0, 2) }}</div>
        </div>
        <div class="kpi-cell">
            @php $monthProfit = $monthlyOverview['totals']->total_profit ?? 0; @endphp
            <div class="kpi-label">{{ __('report.this_month_profit') }}</div>
            <div class="kpi-value {{ $monthProfit >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($monthProfit, 2) }}</div>
        </div>
        <div class="kpi-cell">
            @php $yearProfit = $yearlyOverview['totals']->total_profit ?? 0; @endphp
            <div class="kpi-label">{{ __('report.this_year_profit') }}</div>
            <div class="kpi-value {{ $yearProfit >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($yearProfit, 2) }}</div>
        </div>
    </div>
    <div class="section-title">{{ __('report.recent_daily_performance') }}</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>{{ __('report.date') }}</th>
                <th class="text-center">{{ __('report.total_transactions') }}</th>
                <th class="text-end">{{ __('report.revenue') }}</th>
                <th class="text-end">{{ __('report.profit') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailySales as $day)
            <tr>
                <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                <td class="text-center">{{ $day->transactions }}</td>
                <td class="text-end">{{ number_format($day->revenue, 2) }}</td>
                <td class="text-end {{ $day->profit >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($day->profit, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding:1rem;color:#94a3b8;">{{ __('report.no_data_short') }}</td>
            </tr>
            @endforelse
        </tbody>
        @if($dailySales->count())
        <tfoot>
            <tr>
                <td><strong>{{ __('report.total') }}</strong></td>
                <td class="text-center">{{ $dailySales->sum('transactions') }}</td>
                <td class="text-end">{{ number_format($dailySales->sum('revenue'), 2) }}</td>
                <td class="text-end {{ $dailySales->sum('profit') >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($dailySales->sum('profit'), 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
    @if($dailySalesDetails->isNotEmpty())
    <div class="section-title">{{ __('report.sale_details') }}</div>
    @foreach($dailySalesDetails as $dateKey => $sales)
    <div class="detail-section">
        <div class="detail-date-header">{{ \Carbon\Carbon::parse($dateKey)->format('l, M d, Y') }} &mdash; {{ $sales->count() }} {{ __('report.records') }}</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>{{ __('report.shop') }}</th>
                    <th>{{ __('report.product_name') }}</th>
                    <th>{{ __('report.customer_name') }}</th>
                    <th>{{ __('report.customer_phone') }}</th>
                    <th class="text-center">{{ __('report.quantity') }}</th>
                    <th class="text-end">{{ __('report.sale_price') }}</th>
                    <th class="text-end">{{ __('report.discount') }}</th>
                    <th class="text-end">{{ __('report.total_amount') }}</th>
                    <th class="text-end">{{ __('report.total_profit') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->shop->name ?? '-' }}</td>
                    <td>{{ $sale->product->name ?? '-' }}</td>
                    <td>{{ $sale->customer_name ?: '-' }}</td>
                    <td>{{ $sale->customer_phone ?: '-' }}</td>
                    <td class="text-center">{{ $sale->quantity }}</td>
                    <td class="text-end">{{ number_format($sale->sale_price, 2) }}</td>
                    <td class="text-end">{{ number_format($sale->discount ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-end {{ $sale->profit >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($sale->profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
    @endif
    <div class="report-footer">
        <span>{{ config('app.name') }}</span>
        <span>{{ __('report.generated') }}: {{ now()->format('d M Y, H:i') }}</span>
    </div>
</div>
<script>window.addEventListener('load', function () { window.print(); });</script>
</body>
</html>