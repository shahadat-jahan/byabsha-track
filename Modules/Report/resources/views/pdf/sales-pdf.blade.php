<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.sales_report') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #2563eb; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        .summary { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e2e8f0; }
        .summary-item { display: table-cell; width: 25%; text-align: center; padding: 10px 6px; border-right: 1px solid #e2e8f0; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
        .profit-positive { color: #16a34a; }
        .profit-negative { color: #dc2626; }
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10.5px; }
        table.report-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        table.report-table tfoot td { border: 1px solid #cbd5e1; padding: 7px 8px; font-weight: 700; background-color: #f1f5f9; font-size: 11px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name">{{ $shopName }}</div>
        <div class="date-range">{{ __('report.sales_report') }} &mdash; {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</div>
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_sales') }}</div>
            <div class="summary-value">{{ $salesSummary->total_transactions ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_revenue') }}</div>
            <div class="summary-value">{{ number_format($salesSummary->total_revenue ?? 0, 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_profit') }}</div>
            <div class="summary-value profit-positive">{{ number_format($salesSummary->total_profit ?? 0, 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.items_sold') }}</div>
            <div class="summary-value">{{ $salesSummary->total_quantity_sold ?? 0 }}</div>
        </div>
    </div>

    @if($sales->count() > 0)
    <table class="report-table">
        <thead>
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
                <td>{{ $sale->shop->name }}</td>
                <td>{{ $sale->product->name }}</td>
                <td class="text-center">{{ $sale->quantity }}</td>
                <td class="text-end">{{ number_format($sale->sale_price, 2) }}</td>
                <td class="text-end">{{ number_format($sale->total_amount, 2) }}</td>
                <td class="text-end profit-positive">{{ number_format($sale->profit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">{{ __('report.grand_total') }}</td>
                <td class="text-center">{{ $sales->sum('quantity') }}</td>
                <td class="text-end"></td>
                <td class="text-end">{{ number_format($sales->sum('total_amount'), 2) }}</td>
                <td class="text-end profit-positive">{{ number_format($sales->sum('profit'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_sales_found') }}</p>
    @endif

    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>