<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.monthly_pnl') }} - {{ $filters['year'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 22px;
            color: #2563eb;
            margin-bottom: 4px;
        }
        .header .shop-name {
            font-size: 14px;
            color: #334155;
            font-weight: 600;
        }
        .header .date-range {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .header .generated {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 6px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px 6px;
            border-right: 1px solid #e2e8f0;
        }
        .summary-item:last-child { border-right: none; }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: 700;
            margin-top: 2px;
        }
        .profit-positive { color: #16a34a; }
        .profit-negative { color: #dc2626; }
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.report-table thead th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.report-table tbody td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 10.5px;
        }
        table.report-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        table.report-table tfoot td {
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
            font-weight: 700;
            background-color: #f1f5f9;
            font-size: 11px;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-profit { background-color: #dcfce7; color: #16a34a; }
        .badge-loss { background-color: #fee2e2; color: #dc2626; }
        .badge-even { background-color: #f1f5f9; color: #64748b; }
        .badge-margin-good { background-color: #dcfce7; color: #16a34a; }
        .badge-margin-ok { background-color: #fef9c3; color: #a16207; }
        .badge-margin-bad { background-color: #fee2e2; color: #dc2626; }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @php
        $monthNames = [
            1 => __('report.month_jan'), 2 => __('report.month_feb'), 3 => __('report.month_mar'),
            4 => __('report.month_apr'), 5 => __('report.month_may'), 6 => __('report.month_jun'),
            7 => __('report.month_jul'), 8 => __('report.month_aug'), 9 => __('report.month_sep'),
            10 => __('report.month_oct'), 11 => __('report.month_nov'), 12 => __('report.month_dec'),
        ];
        $rowsByMonth = $monthlyData['rows']->keyBy('month_number');
    @endphp

    {{-- Header --}}
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name">{{ $shopName }}</div>
        <div class="date-range">{{ __('report.monthly_pnl') }} &mdash; {{ $monthlyData['year'] }}</div>
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_sales_count') }}</div>
            <div class="summary-value">{{ $monthlyData['totals']->total_sales_count }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_revenue') }}</div>
            <div class="summary-value">{{ number_format($monthlyData['totals']->total_revenue, 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_profit') }}</div>
            <div class="summary-value {{ $monthlyData['totals']->total_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                {{ number_format($monthlyData['totals']->total_profit, 2) }}
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.profit_margin') }}</div>
            <div class="summary-value">{{ $monthlyData['totals']->profit_margin }}%</div>
        </div>
    </div>

    {{-- Table --}}
    @if($monthlyData['rows']->count() > 0)
    <table class="report-table">
        <thead>
            <tr>
                <th>{{ __('report.month') }}</th>
                <th class="text-center">{{ __('report.total_sales_count') }}</th>
                <th class="text-end">{{ __('report.total_revenue') }}</th>
                <th class="text-end">{{ __('report.total_cost') }}</th>
                <th class="text-end">{{ __('report.total_profit') }}</th>
                <th class="text-center">{{ __('report.profit_margin') }}</th>
                <th class="text-center">{{ __('report.pnl_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @for($m = 1; $m <= 12; $m++)
                @php $row = $rowsByMonth->get($m); @endphp
                @if($row)
                <tr>
                    <td style="font-weight:600;">{{ $monthNames[$m] }}</td>
                    <td class="text-center">{{ $row->total_sales_count }}</td>
                    <td class="text-end">{{ number_format($row->total_revenue, 2) }}</td>
                    <td class="text-end">{{ number_format($row->total_cost, 2) }}</td>
                    <td class="text-end {{ $row->total_profit >= 0 ? 'profit-positive' : 'profit-negative' }}" style="font-weight:600;">
                        {{ number_format($row->total_profit, 2) }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $row->profit_margin >= 20 ? 'badge-margin-good' : ($row->profit_margin >= 0 ? 'badge-margin-ok' : 'badge-margin-bad') }}">
                            {{ $row->profit_margin }}%
                        </span>
                    </td>
                    <td class="text-center">
                        @if($row->total_profit > 0)
                            <span class="badge badge-profit">{{ __('report.profit_label') }}</span>
                        @elseif($row->total_profit < 0)
                            <span class="badge badge-loss">{{ __('report.loss_label') }}</span>
                        @else
                            <span class="badge badge-even">{{ __('report.breakeven_label') }}</span>
                        @endif
                    </td>
                </tr>
                @else
                <tr style="color:#94a3b8;">
                    <td>{{ $monthNames[$m] }}</td>
                    <td class="text-center">0</td>
                    <td class="text-end">0.00</td>
                    <td class="text-end">0.00</td>
                    <td class="text-end">0.00</td>
                    <td class="text-center"><span class="badge badge-even">0%</span></td>
                    <td class="text-center"><span class="badge badge-even">&mdash;</span></td>
                </tr>
                @endif
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td>{{ __('report.yearly_total') }}</td>
                <td class="text-center">{{ $monthlyData['totals']->total_sales_count }}</td>
                <td class="text-end">{{ number_format($monthlyData['totals']->total_revenue, 2) }}</td>
                <td class="text-end">{{ number_format($monthlyData['totals']->total_cost, 2) }}</td>
                <td class="text-end {{ $monthlyData['totals']->total_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    {{ number_format($monthlyData['totals']->total_profit, 2) }}
                </td>
                <td class="text-center">
                    <span class="badge {{ $monthlyData['totals']->profit_margin >= 20 ? 'badge-margin-good' : ($monthlyData['totals']->profit_margin >= 0 ? 'badge-margin-ok' : 'badge-margin-bad') }}">
                        {{ $monthlyData['totals']->profit_margin }}%
                    </span>
                </td>
                <td class="text-center">
                    @if($monthlyData['totals']->total_profit > 0)
                        <span class="badge badge-profit">{{ __('report.profit_label') }}</span>
                    @elseif($monthlyData['totals']->total_profit < 0)
                        <span class="badge badge-loss">{{ __('report.loss_label') }}</span>
                    @else
                        <span class="badge badge-even">{{ __('report.breakeven_label') }}</span>
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_monthly_data') }}</p>
    @endif

    {{-- Footer --}}
    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>