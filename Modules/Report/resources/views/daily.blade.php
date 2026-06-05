@extends('layouts.app')

@section('title', __('report.daily_pnl'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@600;700&display=swap');

    .daily-shell {
        font-family: 'Manrope', 'Segoe UI', sans-serif;
        color: #0f172a;
    }

    .daily-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(700px 420px at 90% 5%, rgba(14, 116, 144, 0.14), transparent 60%),
            radial-gradient(580px 360px at 5% 8%, rgba(251, 146, 60, 0.14), transparent 60%),
            linear-gradient(180deg, #f7fafc 0%, #eff4f8 100%);
    }

    .page-title {
        font-family: 'Outfit', 'Segoe UI', sans-serif;
        font-size: clamp(1.35rem, 2.4vw, 1.9rem);
        margin: 0;
    }

    .panel {
        background: #fff;
        border: 1px solid #dce6ef;
        border-radius: 14px;
    }

    .panel-head {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e7eef5;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
    }

    .panel-body {
        padding: 1rem;
    }

    .kpi {
        border: 1px solid #dde7f0;
        border-radius: 12px;
        padding: 0.8rem;
        background: #fff;
        height: 100%;
    }

    .kpi-label {
        margin: 0;
        font-size: 0.78rem;
        color: #64748b;
    }

    .kpi-value {
        margin: 0.2rem 0 0;
        font-weight: 800;
        font-size: 1.1rem;
    }

    .compact-table th {
        background: #f7fbff;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
    }

    .compact-table td,
    .compact-table th {
        padding: 0.65rem 0.75rem;
        border-color: #e6eef5;
        white-space: nowrap;
    }

    .report-modal {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .report-modal .modal-header {
        background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
        border-bottom: 1px solid #dbe7f3;
    }

    .modal-kicker {
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin: 0 0 0.2rem;
        font-weight: 700;
    }

    .modal-toolbar {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }

    .chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .stat-chip {
        border: 1px solid #d9e7f3;
        background: #f8fbff;
        border-radius: 999px;
        padding: 0.32rem 0.62rem;
        font-size: 0.78rem;
        color: #1e293b;
        font-weight: 600;
    }

    .stat-chip strong {
        font-weight: 800;
    }

    .modal-search {
        min-width: 220px;
        max-width: 300px;
    }

    .modal-search .form-control {
        border-radius: 10px;
        border-color: #d6e3ef;
    }

    .modal-table-wrap {
        border: 1px solid #e3edf7;
        border-radius: 12px;
        overflow: hidden;
    }

    @media (min-width: 1200px) {
        #dailyRowDetailsModal .modal-dialog {
            max-width: 92% !important;
        }
    }

    .modal-sales-table th {
        background: #f3f8fe;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        white-space: nowrap;
    }

    .modal-sales-table td {
        white-space: nowrap;
        font-size: 0.85rem;
    }

    .btn-report-brand {
        background: linear-gradient(135deg, #0f766e 0%, #0d5969 100%);
        border-color: #0f766e;
        color: #fff;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.18);
    }

    .btn-report-brand:hover,
    .btn-report-brand:focus {
        background: linear-gradient(135deg, #0d5969 0%, #0f766e 100%);
        border-color: #0d5969;
        color: #fff;
    }

    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .btn,
        a.btn,
        .panel:first-of-type {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }

        body {
            background: #fff !important;
            font-size: 11px;
        }
    }
</style>
@endpush

@section('content')
<div class="daily-shell">
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-title"><i class="bi bi-calendar-day me-1"></i>{{ __('report.daily_pnl') }}</h1>
            @php
                $currentShop = $shops->firstWhere('id', $filters['shop_id']);
                $shopDisplay = $currentShop ? $currentShop->name : __('report.all_shops');
            @endphp
            <p class="mb-0 text-muted">{{ __('report.daily_pnl_subtitle') }} • <strong>{{ __('report.shop') }}:</strong> {{ $shopDisplay }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('report.export.daily-pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> {{ __('report.download_pdf') }}
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer"></i> {{ __('report.print') }}
            </button>
            <a href="{{ route('report.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ __('report.back_to_reports') }}
            </a>
        </div>
    </div>

    <div class="panel mb-3">
        <div class="panel-head"><i class="bi bi-funnel me-1"></i>{{ __('report.filters') }}</div>
        <div class="panel-body">
            <form action="{{ route('report.daily') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="shop_id" class="form-label small fw-semibold">{{ __('report.shop') }}</label>
                        <select class="form-select" id="shop_id" name="shop_id">
                            <option value="">{{ __('report.all_shops') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ ($filters['shop_id'] ?? '') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="month" class="form-label small fw-semibold">{{ __('report.month') }}</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ $filters['month'] }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-report-brand w-100">
                            <i class="bi bi-search"></i> {{ __('report.apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $totalSales = $dailyData['totals']->total_sales_count ?? 0;
        $totalRevenue = $dailyData['totals']->total_revenue ?? 0;
        $totalCost = $dailyData['totals']->total_cost ?? 0;
        $totalProfit = $dailyData['totals']->total_profit ?? 0;
    @endphp

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="kpi"><p class="kpi-label">{{ __('report.total_sales_count') }}</p><p class="kpi-value">{{ $totalSales }}</p></div></div>
        <div class="col-md-3"><div class="kpi"><p class="kpi-label">{{ __('report.total_revenue') }}</p><p class="kpi-value">{{ number_format($totalRevenue, 2) }}</p></div></div>
        <div class="col-md-3"><div class="kpi"><p class="kpi-label">{{ __('report.total_cost') }}</p><p class="kpi-value">{{ number_format($totalCost, 2) }}</p></div></div>
        <div class="col-md-3"><div class="kpi"><p class="kpi-label">{{ __('report.total_profit') }}</p><p class="kpi-value {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($totalProfit, 2) }}</p></div></div>
    </div>

    <div class="panel">
        <div class="panel-head"><i class="bi bi-table me-1"></i>{{ __('report.daily_pnl_table') }}</div>
        <div class="table-responsive">
            <table class="table compact-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('report.date') }}</th>
                        <th class="text-center">{{ __('report.total_sales_count') }}</th>
                        <th class="text-end">{{ __('report.total_revenue') }}</th>
                        <th class="text-end">{{ __('report.total_cost') }}</th>
                        <th class="text-end">{{ __('report.total_profit') }}</th>
                        <th class="text-center">{{ __('report.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyData['rows'] as $row)
                    @php
                        $dayKey = \Carbon\Carbon::parse($row->date)->format('Y-m-d');
                        $dayLabel = \Carbon\Carbon::parse($row->date)->format('M d, Y');
                        $daySales = ($dailyDetailsByDate[$dayKey] ?? collect())
                            ->map(function ($sale) {
                                return [
                                    'shop' => $sale->shop->name ?? '-',
                                    'product' => $sale->product->name ?? '-',
                                    'customer_name' => $sale->customer_name ?: '-',
                                    'customer_phone' => $sale->customer_phone ?: '-',
                                    'customer_address' => $sale->customer_address ?: '-',
                                    'quantity' => $sale->quantity,
                                    'sale_price' => (float) $sale->sale_price,
                                    'discount' => (float) ($sale->discount ?? 0),
                                    'total_amount' => (float) $sale->total_amount,
                                    'profit' => (float) $sale->profit,
                                ];
                            })
                            ->values();
                    @endphp
                    <tr>
                        <td>{{ $dayLabel }}</td>
                        <td class="text-center">{{ $row->total_sales_count }}</td>
                        <td class="text-end">{{ number_format($row->total_revenue, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_cost, 2) }}</td>
                        <td class="text-end {{ $row->total_profit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($row->total_profit, 2) }}</td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#dailyRowDetailsModal"
                                data-period-label="{{ $dayLabel }}"
                                data-sales='@json($daySales, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                            >
                                <i class="bi bi-eye me-1"></i>{{ __('report.view') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">{{ __('report.no_daily_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td>{{ __('report.monthly_total') }}</td>
                        <td class="text-center">{{ $totalSales }}</td>
                        <td class="text-end">{{ number_format($totalRevenue, 2) }}</td>
                        <td class="text-end">{{ number_format($totalCost, 2) }}</td>
                        <td class="text-end {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($totalProfit, 2) }}</td>
                        <td class="text-center">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="modal fade" id="dailyRowDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content report-modal">
                <div class="modal-header">
                    <div>
                        <p class="modal-kicker">{{ __('report.sale_details') }}</p>
                        <h5 class="modal-title mb-0" id="dailyRowDetailsModalTitle">{{ __('report.sale_details') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-toolbar">
                        <div class="chip-list">
                            <span class="stat-chip">{{ __('report.records') }}: <strong id="dailyRowSalesCount">0</strong></span>
                            <span class="stat-chip">{{ __('report.total_revenue') }}: <strong id="dailyRowSalesRevenue">0.00</strong></span>
                            <span class="stat-chip">{{ __('report.total_profit') }}: <strong id="dailyRowSalesProfit">0.00</strong></span>
                        </div>
                        <div class="modal-search">
                            <input id="dailyRowSalesSearch" type="text" class="form-control form-control-sm" placeholder="{{ __('report.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="modal-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 modal-sales-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('report.shop') }}</th>
                                        <th>{{ __('report.product_name') }}</th>
                                        <th>{{ __('report.customer_name') }}</th>
                                        <th>{{ __('report.customer_phone') }}</th>
                                        <th>{{ __('report.customer_address') }}</th>
                                        <th class="text-center">{{ __('report.quantity') }}</th>
                                        <th class="text-end">{{ __('report.sale_price') }}</th>
                                        <th class="text-end">{{ __('report.discount') }}</th>
                                        <th class="text-end">{{ __('report.total_amount') }}</th>
                                        <th class="text-end">{{ __('report.total_profit') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="dailyRowDetailsModalBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('dailyRowDetailsModal');
        if (!modalEl) {
            return;
        }

        const modalTitle = document.getElementById('dailyRowDetailsModalTitle');
        const modalBody = document.getElementById('dailyRowDetailsModalBody');
        const salesCount = document.getElementById('dailyRowSalesCount');
        const salesRevenue = document.getElementById('dailyRowSalesRevenue');
        const salesProfit = document.getElementById('dailyRowSalesProfit');
        const salesSearch = document.getElementById('dailyRowSalesSearch');
        const emptyMessage = @json(__('report.no_sales_found'));
        const detailsPrefix = @json(__('report.sale_details_for'));
        let allSales = [];

        const escapeHtml = (value) => {
            return String(value ?? '-')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        const renderRows = (sales) => {
            if (!Array.isArray(sales) || sales.length === 0) {
                modalBody.innerHTML = `<tr><td colspan="10" class="text-center text-muted py-3">${escapeHtml(emptyMessage)}</td></tr>`;
                return;
            }

            modalBody.innerHTML = sales.map((sale) => `
                <tr>
                    <td>${escapeHtml(sale.shop)}</td>
                    <td>${escapeHtml(sale.product)}</td>
                    <td>${escapeHtml(sale.customer_name)}</td>
                    <td>${escapeHtml(sale.customer_phone)}</td>
                    <td>${escapeHtml(sale.customer_address)}</td>
                    <td class="text-center">${escapeHtml(sale.quantity)}</td>
                    <td class="text-end">${Number(sale.sale_price || 0).toFixed(2)}</td>
                    <td class="text-end">${Number(sale.discount || 0).toFixed(2)}</td>
                    <td class="text-end">${Number(sale.total_amount || 0).toFixed(2)}</td>
                    <td class="text-end ${Number(sale.profit || 0) >= 0 ? 'text-success' : 'text-danger'}">${Number(sale.profit || 0).toFixed(2)}</td>
                </tr>
            `).join('');
        };

        const applySearch = () => {
            const keyword = (salesSearch?.value || '').trim().toLowerCase();
            if (!keyword) {
                renderRows(allSales);
                return;
            }

            const filtered = allSales.filter((sale) => {
                return [sale.shop, sale.product, sale.customer_name, sale.customer_phone, sale.customer_address]
                    .join(' ')
                    .toLowerCase()
                    .includes(keyword);
            });

            renderRows(filtered);
        };

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) {
                return;
            }

            const periodLabel = button.getAttribute('data-period-label') || '';
            allSales = JSON.parse(button.getAttribute('data-sales') || '[]');
            modalTitle.textContent = `${detailsPrefix}: ${periodLabel}`;

            const totalRevenue = allSales.reduce((sum, row) => sum + Number(row.total_amount || 0), 0);
            const totalProfit = allSales.reduce((sum, row) => sum + Number(row.profit || 0), 0);
            salesCount.textContent = allSales.length;
            salesRevenue.textContent = totalRevenue.toFixed(2);
            salesProfit.textContent = totalProfit.toFixed(2);
            if (salesSearch) {
                salesSearch.value = '';
            }
            renderRows(allSales);
        });

        if (salesSearch) {
            salesSearch.addEventListener('input', applySearch);
        }
    });
</script>
@endpush
