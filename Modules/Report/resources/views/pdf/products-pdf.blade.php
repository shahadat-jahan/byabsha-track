<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.products_report') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #2563eb; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        .summary { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e2e8f0; }
        .summary-item { display: table-cell; width: 16.66%; text-align: center; padding: 10px 4px; border-right: 1px solid #e2e8f0; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; }
        .summary-value { font-size: 14px; font-weight: 700; margin-top: 2px; }
        .profit-positive { color: #16a34a; }
        .text-warning { color: #d97706; }
        .text-danger { color: #dc2626; }
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 6px 6px; text-align: left; font-size: 9px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 5px 6px; font-size: 10px; }
        table.report-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 8px; font-size: 8px; font-weight: 600; }
        .badge-danger { background-color: #fee2e2; color: #dc2626; }
        .badge-warning { background-color: #fef9c3; color: #a16207; }
        .badge-success { background-color: #dcfce7; color: #16a34a; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name">{{ $shopName }}</div>
        <div class="date-range">{{ __('report.products_report') }}</div>
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_products') }}</div>
            <div class="summary-value">{{ $stockSummary['total_products'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.stock_value') }}</div>
            <div class="summary-value">{{ number_format($stockSummary['total_stock_value'], 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.potential_revenue') }}</div>
            <div class="summary-value">{{ number_format($stockSummary['total_potential_revenue'], 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.potential_profit') }}</div>
            <div class="summary-value profit-positive">{{ number_format($stockSummary['total_potential_profit'], 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.low_stock_label') }}</div>
            <div class="summary-value text-warning">{{ $stockSummary['low_stock_count'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.out_of_stock') }}</div>
            <div class="summary-value text-danger">{{ $stockSummary['out_of_stock_count'] }}</div>
        </div>
    </div>

    @if($products->count() > 0)
    <table class="report-table">
        <thead>
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
                <td style="font-weight:600;">{{ $product->name }}</td>
                <td>{{ $product->category ?? '-' }}</td>
                <td>{{ $product->brand ?? '-' }}</td>
                <td>{{ $product->shop->name }}</td>
                <td class="text-end">{{ number_format($product->purchase_price, 2) }}</td>
                <td class="text-end">{{ number_format($product->sale_price, 2) }}</td>
                <td class="text-center">
                    @if($product->stock_quantity == 0)
                        <span class="badge badge-danger">{{ __('report.out_of_stock_badge') }}</span>
                    @elseif($product->stock_quantity <= (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5))
                        <span class="badge badge-warning">{{ $product->stock_quantity }}</span>
                    @else
                        <span class="badge badge-success">{{ $product->stock_quantity }}</span>
                    @endif
                </td>
                <td class="text-end">{{ number_format($product->stock_quantity * $product->purchase_price, 2) }}</td>
                <td class="text-center">{{ $product->total_units_sold }}</td>
                <td class="text-end profit-positive">{{ number_format($product->total_revenue, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_products_found') }}</p>
    @endif

    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>