@extends('layouts.app')

@section('title', __('sale.show_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --sale-show-ink-900: #0f172a;
        --sale-show-ink-700: #334155;
        --sale-show-ink-500: #64748b;
        --sale-show-brand: #0f766e;
        --sale-show-brand-deep: #155e75;
        --sale-show-line: #d8e4ee;
    }

    .sale-show-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--sale-show-ink-900);
    }

    .sale-show-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.19), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .show-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--sale-show-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--sale-show-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--sale-show-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--sale-show-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        padding: 0.9rem 1.2rem;
    }

    .content-card-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: #36506b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .sale-info-table td {
        border-color: #e7edf4;
        padding: 0.8rem 0.5rem;
    }

    .sale-info-table td.fw-semibold {
        color: #475569;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .shop-pill,
    .qty-pill,
    .profit-pill {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .shop-pill {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .qty-pill {
        background: rgba(14, 165, 233, 0.14);
        color: #0369a1;
        border: 1px solid rgba(14, 165, 233, 0.24);
    }

    .profit-positive {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .profit-negative {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .profit-neutral {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #dbe6f1;
    }

    .btn-back {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
    }

    .btn-back:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .btn-delete {
        background: rgba(220, 38, 38, 0.12);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.28);
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="sale-show-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="show-kicker"><i class="bi bi-receipt"></i>{{ __('sale.show_title') }}</span>
        <h1 class="page-title display-font">{{ __('sale.show_title') }}</h1>
        <p class="page-subtitle">{{ __('sale.show_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.index') }}" class="btn btn-back">
        <i class="bi bi-arrow-left"></i> {{ __('sale.back_to_list') }}
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-receipt"></i>
                    {{ __('sale.sale_information') }}
                </h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless sale-info-table">
                    <tbody>
                        @php
                            $serviceRecord = $sale->warranties->first();
                            $serviceStatus = $serviceRecord
                                ? (($serviceRecord->status === 'active' && $serviceRecord->end_date->isPast()) ? 'expired' : $serviceRecord->status)
                                : null;
                        @endphp
                        <tr>
                            <td class="fw-semibold" style="width: 200px;">{{ __('sale.sale_date') }}:</td>
                            <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.shop') }}:</td>
                            <td><span class="shop-pill">{{ $sale->shop->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.product') }}:</td>
                            <td>{{ $sale->product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.category') }}:</td>
                            <td>{{ $sale->product->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.brand') }}:</td>
                            <td>{{ $sale->product->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.customer_name') }}:</td>
                            <td>{{ $sale->customer_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.customer_phone') }}:</td>
                            <td>{{ $sale->customer_phone ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.customer_address') }}:</td>
                            <td>{{ $sale->customer_address ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.free_service_start') }}:</td>
                            <td>{{ $serviceRecord?->start_date?->format('d M Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.free_service_expiry') }}:</td>
                            <td>{{ $serviceRecord?->end_date?->format('d M Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.free_service_status') }}:</td>
                            <td>
                                @if($serviceStatus)
                                    <span class="shop-pill">{{ __('sale.status_' . $serviceStatus) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.quantity_sold') }}:</td>
                            <td><span class="qty-pill">{{ $sale->quantity }} units</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.sale_price_unit') }}:</td>
                            <td>{{ number_format($sale->sale_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.total_amount') }}:</td>
                            <td><h4 class="mb-0">{{ number_format($sale->total_amount, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.profit') }}:</td>
                            <td>
                                <span class="profit-pill {{ $sale->profit > 0 ? 'profit-positive' : ($sale->profit < 0 ? 'profit-negative' : 'profit-neutral') }}">
                                    {{ number_format($sale->profit, 2) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.created_at') }}:</td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.updated_at') }}:</td>
                            <td>{{ $sale->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    {{ __('sale.breakdown') }}
                </h5>
            </div>
            <div class="p-4">
                @php
                    $purchaseTotal = $sale->product->purchase_price * $sale->quantity;
                    $profitMargin  = $sale->total_amount > 0 ? (($sale->profit / $sale->total_amount) * 100) : 0;
                    $perUnitProfit = $sale->sale_price - $sale->product->purchase_price;
                @endphp

                {{-- Total Amount --}}
                <div class="mb-3 p-3 rounded" style="background:#f0f9ff; border-left:3px solid #0ea5e9;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.total_amount') }}</div>
                    <code class="d-block small text-muted">= Quantity &times; Sale Price</code>
                    <code class="d-block small text-muted">= {{ $sale->quantity }} &times; {{ number_format($sale->sale_price, 2) }}</code>
                    <h4 class="mb-0 text-primary mt-1">{{ number_format($sale->total_amount, 2) }}</h4>
                </div>

                {{-- Purchase Cost --}}
                <div class="mb-3 p-3 rounded" style="background:#fff7ed; border-left:3px solid #f97316;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.purchase_cost') }} <span class="text-muted fw-normal">({{ __('sale.your_expense') }})</span></div>
                    <code class="d-block small text-muted">= Quantity &times; Purchase Price</code>
                    <code class="d-block small text-muted">= {{ $sale->quantity }} &times; {{ number_format($sale->product->purchase_price, 2) }}</code>
                    <h4 class="mb-0 text-warning mt-1">{{ number_format($purchaseTotal, 2) }}</h4>
                </div>

                {{-- Profit --}}
                <div class="mb-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #22c55e;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.profit') }}</div>
                    <code class="d-block small text-muted">= (Sale Price &minus; Purchase Price) &times; Quantity</code>
                    <code class="d-block small text-muted">= ({{ number_format($sale->sale_price, 2) }} &minus; {{ number_format($sale->product->purchase_price, 2) }}) &times; {{ $sale->quantity }}</code>
                    <code class="d-block small text-muted">= {{ number_format($perUnitProfit, 2) }} &times; {{ $sale->quantity }}</code>
                    <h4 class="mb-0 mt-1 {{ $sale->profit > 0 ? 'text-success' : ($sale->profit < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ number_format($sale->profit, 2) }}
                    </h4>
                </div>

                {{-- Profit Margin --}}
                <div class="p-3 rounded" style="background:#faf5ff; border-left:3px solid #a855f7;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.profit_margin') }}</div>
                    <code class="d-block small text-muted">= (Profit &divide; Total Amount) &times; 100</code>
                    <code class="d-block small text-muted">= ({{ number_format($sale->profit, 2) }} &divide; {{ number_format($sale->total_amount, 2) }}) &times; 100</code>
                    <h4 class="mb-0 mt-1 {{ $profitMargin > 0 ? 'text-success' : ($profitMargin < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ number_format($profitMargin, 2) }}%
                    </h4>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-gear"></i>
                    {{ __('sale.actions') }}
                </h5>
            </div>
            <div class="p-3">
                <form action="{{ route('sale.destroy', $sale->id) }}"
                      method="POST"
                      onsubmit="return confirm('{{ __("sale.confirm_delete") }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete w-100">
                        <i class="bi bi-trash"></i> {{ __('sale.delete_sale') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
