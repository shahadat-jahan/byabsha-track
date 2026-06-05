<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.shops_report') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #2563eb; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 8px 8px; text-align: left; font-size: 10px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 11px; }
        table.report-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        table.report-table tfoot td { border: 1px solid #cbd5e1; padding: 8px 8px; font-size: 11px; font-weight: 700; background-color: #f1f5f9; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .profit-positive { color: #16a34a; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 8px; font-size: 9px; font-weight: 600; }
        .badge-primary { background-color: #dbeafe; color: #2563eb; }
        .badge-success { background-color: #dcfce7; color: #16a34a; }
        .badge-warning { background-color: #fef9c3; color: #a16207; }
        .badge-danger { background-color: #fee2e2; color: #dc2626; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name">{{ __('report.shops_report') }}</div>
        @if($filters['start_date'] || $filters['end_date'])
        <div class="date-range">
            {{ $filters['start_date'] ?? '...' }} &mdash; {{ $filters['end_date'] ?? '...' }}
        </div>
        @endif
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    @if($shopData->count() > 0)
    <table class="report-table">
        <thead>
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
                <td><span class="badge badge-primary">{{ $shop->name }}</span></td>
                <td class="text-center">{{ $shop->total_products }}</td>
                <td class="text-end">{{ number_format($shop->stock_value, 2) }}</td>
                <td class="text-center">{{ $shop->total_sales }}</td>
                <td class="text-end">{{ number_format($shop->total_revenue, 2) }}</td>
                <td class="text-end profit-positive">{{ number_format($shop->total_profit, 2) }}</td>
                <td class="text-end">
                    @php $margin = $shop->profit_margin; @endphp
                    <span class="badge {{ $margin >= 20 ? 'badge-success' : ($margin >= 10 ? 'badge-warning' : 'badge-danger') }}">
                        {{ number_format($margin, 1) }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>{{ __('report.grand_total') }}</td>
                <td class="text-center">{{ $shopData->sum('total_products') }}</td>
                <td class="text-end">{{ number_format($shopData->sum('stock_value'), 2) }}</td>
                <td class="text-center">{{ $shopData->sum('total_sales') }}</td>
                <td class="text-end">{{ number_format($shopData->sum('total_revenue'), 2) }}</td>
                <td class="text-end profit-positive">{{ number_format($shopData->sum('total_profit'), 2) }}</td>
                <td class="text-end">
                    @php
                        $totalRev = $shopData->sum('total_revenue');
                        $totalProf = $shopData->sum('total_profit');
                        $overallMargin = $totalRev > 0 ? ($totalProf / $totalRev) * 100 : 0;
                    @endphp
                    <span class="badge {{ $overallMargin >= 20 ? 'badge-success' : ($overallMargin >= 10 ? 'badge-warning' : 'badge-danger') }}">
                        {{ number_format($overallMargin, 1) }}%
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_shops_found') }}</p>
    @endif

    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>