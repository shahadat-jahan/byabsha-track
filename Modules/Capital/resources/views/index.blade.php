@extends('layouts.app')

@section('title', __('capital.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --capital-ink-900: #0f172a;
        --capital-ink-700: #334155;
        --capital-ink-500: #64748b;
        --capital-brand: #0f766e;
        --capital-brand-deep: #155e75;
        --capital-line: #d8e4ee;
    }

    .capital-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--capital-ink-900);
    }

    .capital-shell::before {
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

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.6rem;
        gap: 0.9rem;
    }

    .capital-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--capital-brand);
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
        font-weight: 700;
        color: var(--capital-ink-900);
        margin: 0;
    }

    .page-subtitle {
        color: var(--capital-ink-700);
        font-size: 0.98rem;
        margin-top: 0.5rem;
    }

    .capital-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid var(--capital-line);
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .capital-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
    }

    .capital-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e4edf6;
    }

    .shop-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--capital-ink-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .shop-name i {
        font-size: 1.1rem;
        color: var(--capital-brand);
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 118, 110, 0.12);
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .capital-amount {
        font-size: clamp(1.45rem, 2.8vw, 1.95rem);
        font-weight: 700;
        background: linear-gradient(140deg, var(--capital-brand), var(--capital-brand-deep));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .capital-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .capital-info {
        flex-grow: 1;
    }

    .capital-label {
        color: var(--capital-ink-500);
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.5rem;
    }

    .last-updated {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .btn-update {
        background: linear-gradient(140deg, var(--capital-brand), var(--capital-brand-deep));
        color: white;
        border: none;
        padding: 0.64rem 1.1rem;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 700;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.24);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.3);
        color: white;
    }

    .btn-update-all {
        background: linear-gradient(140deg, var(--capital-brand), var(--capital-brand-deep));
        color: white;
        border: none;
        padding: 0.66rem 1.24rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.86rem;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
    }

    .btn-update-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
        color: white;
    }

    .formula-banner {
        background: rgba(255, 255, 255, 0.78);
        border: 1px solid #d9e5f1;
        border-radius: 14px;
        color: var(--capital-ink-500);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        border: 1px solid var(--capital-line);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .alert {
        border-radius: 12px;
        border: 1px solid #dfe8f2;
    }

    .capital-shop-badge {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .btn-breakdown {
        border-radius: 999px;
        border: 1px solid #c7d8e6;
        color: #3f556c;
        background: rgba(255, 255, 255, 0.84);
        font-size: 0.78rem;
        font-weight: 700;
    }

    .btn-breakdown:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .breakdown-table {
        border-color: #e3ebf4;
    }

    .breakdown-table thead th {
        background: #f7fbff;
        color: #4b637b;
        font-size: 0.73rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .breakdown-table tfoot td {
        background: #f9fbff;
    }

    @media (max-width: 767.98px) {
        .page-header {
            align-items: stretch;
        }

        .btn-update-all {
            width: 100%;
            justify-content: center;
        }

        .capital-header,
        .capital-body {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.85rem;
        }
    }

    /* Search Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--capital-line);
        border-radius: 14px;
        padding: 0.75rem 1rem;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: var(--capital-ink-500);
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 10;
    }

    .filter-search-control {
        padding-left: 36px !important;
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        height: 38px !important;
        color: var(--capital-ink-900) !important;
    }

    .filter-search-control:focus {
        background-color: #ffffff !important;
        border-color: var(--capital-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="capital-shell">
<div class="page-header">
    <div>
        <span class="capital-kicker"><i class="bi bi-bank"></i>{{ __('capital.title') }}</span>
        <h1 class="page-title display-font">{{ __('capital.title') }}</h1>
        <p class="page-subtitle">{{ __('capital.subtitle') }}</p>
    </div>
    <form action="{{ route('capital.update-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn-update-all">
            <i class="bi bi-arrow-clockwise"></i>
            {{ __('capital.update_all') }}
        </button>
    </form>
</div>



{{-- Formula Explanation Banner --}}
<div class="alert mb-4 p-3 formula-banner" style="font-size:0.85rem;">
    <div class="fw-semibold mb-1"><i class="bi bi-calculator me-1 text-primary"></i>{{ __('capital.how_calculated') }}</div>
    <div class="text-muted">
        {{ __('capital.formula_desc') }}
    </div>
    <div class="text-muted mt-1">
        {{ __('capital.click_breakdown') }}
    </div>
</div>

{{-- Search Toolbar --}}
<div class="filter-toolbar mb-4 shadow-sm">
    <div class="d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <div class="search-input-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    id="search"
                    class="form-control filter-search-control"
                    placeholder="{{ __('capital.search_placeholder') }}">
            </div>
        </div>
    </div>
</div>

<div id="search-empty-state" class="empty-state mb-4" style="display: none;">
    <i class="bi bi-search"></i>
    <h5>{{ __('capital.no_search_results') }}</h5>
    <p>{{ __('capital.no_search_results_sub') }}</p>
</div>

@forelse($capitals as $capital)
    @if($capital->shop)
        @php $batches = $capital->shop->batches; @endphp
        <div class="capital-card">
            <div class="capital-header">
                <h3 class="shop-name">
                    <i class="bi bi-shop-window"></i>
                    {{ $capital->shop->name }}
                </h3>
                <span class="capital-shop-badge">{{ $batches->count() }} batch(es)</span>
            </div>

            <div class="capital-body">
                <div class="capital-info">
                    <div class="capital-label">{{ __('capital.current_capital') }}</div>
                    <div class="capital-amount">{{ number_format($capital->total_capital, 2) }}</div>
                    <div class="d-flex align-items-center gap-2 mt-1" style="font-size:0.82rem; color:#94a3b8;">
                        <span><i class="bi bi-clock"></i> {{ __('capital.last_updated') }}: {{ $capital->updated_at->diffForHumans() }}</span>
                        <span class="text-muted">&nbsp;|&nbsp; Formula: &Sigma;(Stock &times; Purchase Price)</span>
                    </div>
                </div>
                <form action="{{ route('capital.update-shop', $capital->shop_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-update">
                        <i class="bi bi-arrow-clockwise"></i>
                        {{ __('capital.update_capital') }}
                    </button>
                </form>
            </div>

            {{-- Product-level Breakdown --}}
            <div class="mt-3">
                <button class="btn btn-sm btn-breakdown"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#breakdown-{{ $capital->id }}"
                        aria-expanded="false">
                    <i class="bi bi-table me-1"></i>{{ __('capital.show_breakdown') }}
                </button>
                <div class="collapse mt-2" id="breakdown-{{ $capital->id }}">
                    @if($batches->count() > 0)
                        <div class="table-responsive" style="font-size:0.85rem;">
                            <table class="table table-sm table-bordered mb-1 breakdown-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('capital.col_product') }}</th>
                                        <th class="text-center">{{ __('capital.col_stock') }}</th>
                                        <th class="text-end">{{ __('capital.col_purchase_price') }}</th>
                                        <th class="text-center text-muted">{{ __('capital.col_formula') }}</th>
                                        <th class="text-end">{{ __('capital.col_stock_value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batches as $batch)
                                        @php
                                            $product = $batch->product;
                                            $stockValue = $batch->remaining_quantity * $batch->purchase_price;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="product-item-name">{{ $product ? $product->name : 'N/A' }}</span>
                                                <span class="text-muted" style="font-size:0.75rem;">({{ $batch->batch_code }})</span>
                                            </td>
                                            <td class="text-center">{{ $batch->remaining_quantity }}</td>
                                            <td class="text-end">{{ number_format($batch->purchase_price, 2) }}</td>
                                            <td class="text-center text-muted" style="font-size:0.78rem;">
                                                {{ $batch->remaining_quantity }} &times; {{ number_format($batch->purchase_price, 2) }}
                                            </td>
                                            <td class="text-end fw-semibold">{{ number_format($stockValue, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="4" class="text-end">{{ __('capital.total_capital_row') }} &nbsp;<span class="text-muted fw-normal" style="font-size:0.78rem;">{{ __('capital.sum_note') }}</span></td>
                                        <td class="text-end">{{ number_format($capital->total_capital, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small">{{ __('capital.no_products') }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
@empty
    <div class="empty-state">
        <i class="bi bi-piggy-bank"></i>
        <h5>{{ __('capital.no_capitals') }}</h5>
        <p>{{ __('capital.no_capitals_sub') }}</p>
    </div>
@endforelse
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.capital-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const shopName = card.querySelector('.shop-name').textContent.toLowerCase();
                
                let productMatch = false;
                const productNames = card.querySelectorAll('.product-item-name');
                productNames.forEach(span => {
                    if (span.textContent.toLowerCase().includes(query)) {
                        productMatch = true;
                    }
                });

                if (shopName.includes(query) || productMatch) {
                    card.style.setProperty('display', '', 'important');
                    visibleCount++;
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });

            const emptyState = document.getElementById('search-empty-state');
            if (visibleCount === 0 && cards.length > 0) {
                emptyState.style.setProperty('display', 'block', 'important');
            } else {
                emptyState.style.setProperty('display', 'none', 'important');
            }
        });
    });
</script>
@endpush
