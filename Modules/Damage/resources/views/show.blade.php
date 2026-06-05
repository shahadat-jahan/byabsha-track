@extends('layouts.app')

@section('title', __('damage.show_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --damage-ink-900: #0f172a;
        --damage-ink-700: #334155;
        --damage-ink-500: #64748b;
        --damage-brand: #0f766e;
        --damage-brand-deep: #155e75;
        --damage-line: #d8e4ee;
    }

    .damage-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--damage-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.02em;
    }

    .damage-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(15, 118, 110, 0.08);
        color: var(--damage-brand);
        border: 1px solid rgba(15, 118, 110, 0.18);
        border-radius: 999px;
        padding: 0.3rem 0.8rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .page-title {
        font-size: clamp(1.4rem, 2.5vw, 1.85rem);
        font-weight: 800;
        color: var(--damage-ink-900);
    }

    .page-subtitle {
        color: var(--damage-ink-500);
        font-size: 0.88rem;
    }

    .btn-back-custom {
        border-radius: 12px;
        border: 1px solid #cedce9;
        background: #ffffff;
        color: var(--damage-ink-700);
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.52rem 1.1rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-back-custom:hover {
        background: #f8fafc;
        color: var(--damage-ink-900);
        border-color: #94a3b8;
    }

    /* KPI Cards */
    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--damage-line);
        border-radius: 16px;
        padding: 1.15rem;
        height: 100%;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
    }

    .kpi-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--damage-ink-500);
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }

    .kpi-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--damage-ink-900);
    }

    /* Product Table Wrap & Row Height */
    .product-table-wrap {
        background: #fff;
        border: 1px solid var(--damage-line);
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--damage-line) !important;
        color: var(--damage-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.62rem 1rem !important;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="damage-shell">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 damage-header">
        <div>
            <span class="damage-kicker"><i class="bi bi-exclamation-triangle"></i> {{ __('damage.title') }}</span>
            <h1 class="page-title display-font mb-1">{{ __('damage.show_title') }}</h1>
            <p class="page-subtitle mb-0">{{ __('damage.show_subtitle') }}</p>
        </div>
        <a href="{{ route('damage.index') }}" class="btn-back-custom shadow-sm">
            <i class="bi bi-arrow-left"></i> {{ __('damage.back_to_list') }}
        </a>
    </div>

    {{-- Details Summary Grid --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.reference_no') }}</div>
                <strong class="kpi-value display-font text-teal text-gradient"><i class="bi bi-hash"></i>{{ $damage->reference_no }}</strong>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.damage_date') }}</div>
                <strong class="kpi-value display-font"><i class="bi bi-calendar3"></i> {{ optional($damage->damage_date)->format('d M Y') }}</strong>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.shop') }}</div>
                <strong class="kpi-value display-font text-truncate d-block" title="{{ $damage->shop?->name ?? '-' }}"><i class="bi bi-shop"></i> {{ $damage->shop?->name ?? '-' }}</strong>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.items_count') }}</div>
                <strong class="kpi-value display-font"><i class="bi bi-list-ol"></i> {{ $damage->items->count() }} line(s)</strong>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.total_quantity') }}</div>
                <strong class="kpi-value display-font text-teal"><i class="bi bi-boxes"></i> {{ number_format((int)$damage->total_quantity) }} pcs</strong>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.total_loss') }}</div>
                <strong class="kpi-value display-font text-danger"><i class="bi bi-currency-dollar"></i> {{ number_format((float)$damage->total_loss, 2) }}</strong>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">{{ __('damage.note') }}</div>
                <strong class="kpi-value fs-6 text-muted d-block text-truncate" title="{{ $damage->note ?? '-' }}"><i class="bi bi-chat-text"></i> {{ $damage->note ?: '-' }}</strong>
            </div>
        </div>
    </div>

    {{-- Line Items Table Card --}}
    <div class="product-table-wrap p-3 mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 w-100">
                <thead>
                    <tr>
                        <th>{{ __('damage.product') }}</th>
                        <th>{{ __('damage.batch') }}</th>
                        <th>{{ __('damage.batch_attributes') }}</th>
                        <th class="text-center">{{ __('damage.quantity') }}</th>
                        <th class="text-end">{{ __('damage.purchase_price') }}</th>
                        <th class="text-end">{{ __('damage.line_loss') }}</th>
                        <th>{{ __('damage.reason') }}</th>
                        <th>{{ __('damage.reason_note') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($damage->items as $item)
                    <tr>
                        <td><strong>{{ $item->product?->name ?? '-' }}</strong></td>
                        <td><span class="badge bg-light text-dark border">{{ $item->productBatch?->batch_code ?? '-' }}</span></td>
                        <td><span class="text-muted" style="font-size: 0.8rem;">{{ $item->productBatch?->attribute_summary ?? '-' }}</span></td>
                        <td class="text-center fw-bold">{{ number_format((int)$item->quantity) }}</td>
                        <td class="text-end">{{ number_format((float)$item->purchase_price_per_unit, 2) }}</td>
                        <td class="text-end text-danger fw-semibold">{{ number_format((float)$item->total_loss, 2) }}</td>
                        <td>
                            @php
                                $reasonStr = (string)$item->reason;
                                $badgeClass = $reasonStr === 'expired' ? 'bg-warning text-dark' : ($reasonStr === 'missing' ? 'bg-secondary' : 'bg-danger');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($reasonStr) }}</span>
                        </td>
                        <td><span class="text-muted" style="font-size: 0.82rem;">{{ $item->reason_note ?: '-' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">{{ __('damage.no_records') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
