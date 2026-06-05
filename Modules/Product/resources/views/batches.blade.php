@extends('layouts.app')

@section('title', $product->name . ' — Batch Tracker')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --bt-ink-900: #0f172a;
        --bt-ink-700: #334155;
        --bt-ink-500: #64748b;
        --bt-brand: #0f766e;
        --bt-brand-deep: #155e75;
        --bt-line: #d8e4ee;
    }

    body { font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif; }

    .bt-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15,118,110,.18), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245,158,11,.14), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .display-font { font-family: 'Space Grotesk', 'Segoe UI', sans-serif; letter-spacing: -.03em; }

    .kicker {
        display: inline-flex; align-items: center; gap: .45rem;
        background: rgba(15,118,110,.12); color: var(--bt-brand);
        border: 1px solid rgba(15,118,110,.22); border-radius: 999px;
        padding: .38rem .88rem; font-size: .73rem; font-weight: 700;
        box-shadow: 0 8px 18px rgba(15,118,110,.13);
    }

    .stat-card {
        background: #fff; border: 1px solid var(--bt-line);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
    }
    .stat-label { font-size: .72rem; font-weight: 700; color: var(--bt-ink-500); text-transform: uppercase; letter-spacing: .05em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--bt-ink-900); letter-spacing: -.02em; }

    .section-card {
        background: #fff; border: 1px solid var(--bt-line);
        border-radius: 18px; box-shadow: 0 10px 24px rgba(15,23,42,.06);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .section-header {
        background: #f7fbff; border-bottom: 1px solid #dce8f3;
        padding: .85rem 1.2rem; display: flex; align-items: center; justify-content: space-between; gap: .75rem;
    }
    .section-title {
        font-size: .88rem; font-weight: 700; color: #36506b;
        text-transform: uppercase; letter-spacing: .06em;
        display: flex; align-items: center; gap: .45rem; margin: 0;
    }

    .bt-table { margin: 0; }
    .bt-table thead th {
        background: #f7fbff !important; border-bottom: 1px solid #dce8f3;
        color: #4b637b; font-size: .72rem; font-weight: 700; letter-spacing: .06em;
        text-transform: uppercase; padding: .85rem 1rem; white-space: nowrap;
    }
    .bt-table tbody td, .bt-table tfoot td {
        border-color: #e7edf4; padding: .88rem 1rem; vertical-align: middle;
    }
    .bt-table tbody tr:hover { background: #fbfdff; }

    .batch-status {
        display: inline-flex; align-items: center; gap: .35rem;
        border-radius: 999px; padding: .3rem .65rem; font-size: .7rem; font-weight: 700;
    }
    .batch-full    { background: rgba(15,118,110,.14); color: #0f766e; border: 1px solid rgba(15,118,110,.22); }
    .batch-partial { background: rgba(245,158,11,.14);  color: #b45309; border: 1px solid rgba(245,158,11,.22); }
    .batch-done    { background: rgba(220,38,38,.14);   color: #b91c1c; border: 1px solid rgba(220,38,38,.24); }
    .batch-deleted { background: rgba(100,116,139,.12); color: #64748b; border: 1px solid rgba(100,116,139,.22); }

    .qty-bar-wrap { background: #e9f0f7; border-radius: 999px; height: 6px; min-width: 80px; overflow: hidden; }
    .qty-bar-fill { height: 6px; border-radius: 999px; transition: width .3s; }
    .fill-green  { background: #0f766e; }
    .fill-amber  { background: #f59e0b; }
    .fill-red    { background: #ef4444; }

    .btn-action {
        border-radius: 9px; padding: .3rem .52rem; font-size: .78rem; font-weight: 600; border-width: 1px;
    }

    .sale-row { cursor: pointer; }
    .batch-breakdown { background: #f9fbff; }
    .breakdown-table thead th {
        font-size: .69rem; text-transform: uppercase; letter-spacing: .05em;
        color: #64748b; background: #f1f5fb !important; padding: .55rem .9rem; font-weight: 700;
    }
    .breakdown-table td { font-size: .83rem; padding: .55rem .9rem; border-color: #e7edf4; }

    .profit-pos { color: #0f766e; font-weight: 700; }
    .profit-neg { color: #b91c1c; font-weight: 700; }
    .profit-zero { color: #64748b; }

    .btn-add-batch {
        background: linear-gradient(140deg, var(--bt-brand), var(--bt-brand-deep));
        color: #fff; border: 0; border-radius: 999px; padding: .5rem 1rem;
        font-size: .82rem; font-weight: 700; display: inline-flex; align-items: center; gap: .45rem;
        box-shadow: 0 12px 24px rgba(15,118,110,.26); text-decoration: none;
        transition: transform .2s, box-shadow .2s;
    }
    .btn-add-batch:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 20px 28px rgba(15,118,110,.32); }

    .empty-state { padding: 2.5rem 1rem; text-align: center; }
    .empty-state i { font-size: 2.2rem; color: #8aa0b6; display: block; margin-bottom: .6rem; }
    .empty-state h4 { font-size: 1rem; margin-bottom: .3rem; }
    .empty-state p { color: var(--bt-ink-500); font-size: .88rem; margin: 0; }
</style>
@endpush

@section('content')
<div class="bt-shell">

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="kicker mb-2">
                <i class="bi bi-layers"></i> Batch Tracker
            </div>
            <h1 class="display-font fw-800 mb-1" style="font-size: clamp(1.5rem,3vw,2.1rem); color: var(--bt-ink-900);">
                {{ $product->name }}
            </h1>
            <p class="mb-0" style="color: var(--bt-ink-700); font-size: .95rem;">
                {{ $product->shop?->name ?? '—' }}
                @if($product->category) · {{ $product->category }} @endif
                @if($product->brand) · {{ $product->brand }} @endif
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('restock.create', ['product_id' => $product->id, 'shop_id' => $product->shop_id]) }}"
               class="btn-add-batch">
                <i class="bi bi-plus-circle"></i> Add Restock Batch
            </a>
            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-outline-secondary btn-sm" style="border-radius:999px;padding:.5rem 1rem;font-weight:600;">
                <i class="bi bi-pencil"></i> Edit Product
            </a>
            <a href="{{ route('product.index', ['shop_id' => $product->shop_id]) }}" class="btn btn-outline-secondary btn-sm" style="border-radius:999px;padding:.5rem 1rem;font-weight:600;">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label mb-1">Total Stock</div>
                <div class="stat-value">{{ number_format($product->stock_quantity) }}</div>
                <div class="text-muted" style="font-size:.75rem;">units available</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label mb-1">Sale Price</div>
                <div class="stat-value">{{ number_format((float)$product->sale_price, 2) }}</div>
                <div class="text-muted" style="font-size:.75rem;">per unit</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label mb-1">Total Sold</div>
                <div class="stat-value">{{ number_format($totalSold) }}</div>
                <div class="text-muted" style="font-size:.75rem;">units across all sales</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label mb-1">Total Profit</div>
                <div class="stat-value {{ $totalProfit < 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format((float)$totalProfit, 2) }}
                </div>
                <div class="text-muted" style="font-size:.75rem;">revenue {{ number_format((float)$totalRevenue, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- ── RESTOCK BATCHES ── --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title"><i class="bi bi-box-seam"></i> Restock Batches</h2>
            <span class="text-muted" style="font-size:.78rem;">{{ $batches->count() }} batch(es) — FIFO order (oldest first)</span>
        </div>

        @if($batches->isEmpty())
            <div class="empty-state">
                <i class="bi bi-box"></i>
                <h4>No restock batches yet</h4>
                <p>Add the first batch using the "Add Restock Batch" button above.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table bt-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Purchase Price / Unit</th>
                            <th>Total Qty</th>
                            <th>Sold</th>
                            <th>Remaining</th>
                            <th>Stock Usage</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            @php
                                $consumed   = $batch->quantity - $batch->remaining_quantity;
                                $pct        = $batch->quantity > 0 ? round($consumed / $batch->quantity * 100) : 0;
                                $isDeleted  = !is_null($batch->deleted_at);

                                if ($isDeleted) {
                                    $statusLabel = 'Deleted';
                                    $statusClass = 'batch-deleted';
                                    $barClass    = 'fill-red';
                                } elseif ($batch->remaining_quantity <= 0) {
                                    $statusLabel = 'Exhausted';
                                    $statusClass = 'batch-done';
                                    $barClass    = 'fill-red';
                                } elseif ($consumed > 0) {
                                    $statusLabel = 'Partial';
                                    $statusClass = 'batch-partial';
                                    $barClass    = 'fill-amber';
                                } else {
                                    $statusLabel = 'Full';
                                    $statusClass = 'batch-full';
                                    $barClass    = 'fill-green';
                                }
                            @endphp
                            <tr class="{{ $isDeleted ? 'opacity-50' : '' }}">
                                <td class="text-muted" style="font-size:.78rem;">{{ $batch->id }}</td>
                                <td>{{ optional($batch->restock_date)->format('d M Y') }}</td>
                                <td>
                                    <strong>{{ number_format((float)$batch->purchase_price_per_unit, 2) }}</strong>
                                    <span class="text-muted" style="font-size:.75rem;">{{ currency_symbol() }}</span>
                                </td>
                                <td>{{ number_format($batch->quantity) }}</td>
                                <td>{{ number_format($consumed) }}</td>
                                <td>
                                    <strong>{{ number_format($batch->remaining_quantity) }}</strong>
                                </td>
                                <td style="min-width:100px;">
                                    <div class="qty-bar-wrap mb-1">
                                        <div class="qty-bar-fill {{ $barClass }}" style="width:{{ $pct }}%;"></div>
                                    </div>
                                    <span style="font-size:.7rem; color:#64748b;">{{ $pct }}% sold</span>
                                </td>
                                <td>{{ number_format((float)$batch->total_cost, 2) }}</td>
                                <td><span class="batch-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td class="text-muted" style="font-size:.82rem; max-width:140px;">{{ $batch->note ?: '—' }}</td>
                                <td class="text-center">
                                    @if(!$isDeleted)
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('restock.edit', $batch->id) }}"
                                               class="btn btn-outline-secondary btn-action" title="Edit Batch">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('restock.destroy', $batch->id) }}" method="POST"
                                                  onsubmit="return confirm('Delete this restock batch? This cannot be undone if units have been sold.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-action" title="Delete Batch">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-size:.75rem;">Deleted</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f9fbff; font-weight:700; font-size:.85rem;">
                            <td colspan="3" class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">
                                Totals (active batches)
                            </td>
                            <td>{{ number_format($batches->whereNull('deleted_at')->sum('quantity')) }}</td>
                            <td>{{ number_format($batches->whereNull('deleted_at')->sum(fn($b) => $b->quantity - $b->remaining_quantity)) }}</td>
                            <td>{{ number_format($batches->whereNull('deleted_at')->sum('remaining_quantity')) }}</td>
                            <td></td>
                            <td>{{ number_format((float)$batches->whereNull('deleted_at')->sum('total_cost'), 2) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- ── SALES HISTORY ── --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title"><i class="bi bi-receipt"></i> Sales History</h2>
            <span class="text-muted" style="font-size:.78rem;">
                Click any row to see which batch(es) were consumed
            </span>
        </div>

        @if($sales->isEmpty())
            <div class="empty-state">
                <i class="bi bi-cart-x"></i>
                <h4>No sales recorded yet</h4>
                <p>Sales will appear here once you sell units from this product.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table bt-table align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Qty Sold</th>
                            <th>Sale Price / Unit</th>
                            <th>Discount</th>
                            <th>Avg Purchase Cost / Unit</th>
                            <th>Total Revenue</th>
                            <th>Profit</th>
                            <th>Customer</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            @php
                                $isDeleted     = !is_null($sale->deleted_at);
                                $profitClass   = (float)$sale->profit > 0 ? 'profit-pos' : ((float)$sale->profit < 0 ? 'profit-neg' : 'profit-zero');
                                $hasBatches    = $sale->batchItems->isNotEmpty();
                            @endphp

                            {{-- Main sale row --}}
                            <tr class="sale-row {{ $isDeleted ? 'opacity-50' : '' }}"
                                @if($hasBatches) data-bs-toggle="collapse"
                                    data-bs-target="#breakdown-{{ $sale->id }}"
                                    aria-expanded="false" @endif
                                title="{{ $hasBatches ? 'Click to expand batch breakdown' : '' }}"
                                style="{{ $hasBatches ? 'cursor:pointer;' : '' }}">
                                <td style="width:28px;">
                                    @if($hasBatches)
                                        <i class="bi bi-chevron-right" style="font-size:.75rem; color:#64748b; transition:transform .2s;"
                                           id="chevron-{{ $sale->id }}"></i>
                                    @endif
                                </td>
                                <td>{{ optional($sale->sale_date)->format('d M Y') ?? '—' }}</td>
                                <td><strong>{{ (int)$sale->quantity }}</strong></td>
                                <td>{{ number_format((float)$sale->sale_price, 2) }}</td>
                                <td>{{ number_format((float)($sale->discount ?? 0), 2) }}</td>
                                <td>
                                    @if($sale->purchase_price_per_unit !== null)
                                        {{ number_format((float)$sale->purchase_price_per_unit, 2) }}
                                    @else
                                        <span class="text-muted" style="font-size:.75rem;">legacy</span>
                                    @endif
                                </td>
                                <td>{{ number_format((float)$sale->total_amount, 2) }}</td>
                                <td class="{{ $profitClass }}">{{ number_format((float)$sale->profit, 2) }}</td>
                                <td style="font-size:.82rem;">
                                    {{ $sale->customer_name ?: '—' }}
                                    @if($sale->customer_phone)
                                        <br><span class="text-muted">{{ $sale->customer_phone }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$isDeleted)
                                        <div class="d-flex gap-1 justify-content-center" onclick="event.stopPropagation();">
                                            <a href="{{ route('sale.edit', $sale->id) }}"
                                               class="btn btn-outline-secondary btn-action" title="Edit Sale">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('sale.destroy', $sale->id) }}" method="POST"
                                                  onsubmit="return confirm('Delete this sale? Batch quantities will be restored.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-action" title="Delete Sale">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-size:.75rem;">Deleted</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Batch breakdown accordion row --}}
                            @if($hasBatches)
                                <tr class="collapse batch-breakdown" id="breakdown-{{ $sale->id }}">
                                    <td colspan="10" class="p-0">
                                        <div class="px-4 py-3">
                                            <p class="mb-2" style="font-size:.78rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.05em;">
                                                <i class="bi bi-diagram-3 me-1"></i> Batch Breakdown — FIFO Deduction
                                            </p>
                                            <table class="table breakdown-table table-sm mb-0" style="border-radius:10px;overflow:hidden;">
                                                <thead>
                                                    <tr>
                                                        <th>Batch ID</th>
                                                        <th>Batch Date</th>
                                                        <th>Units from this Batch</th>
                                                        <th>Purchase Price / Unit</th>
                                                        <th>Batch Cost</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sale->batchItems as $item)
                                                        <tr>
                                                            <td class="text-muted">#{{ $item->restock_id }}</td>
                                                            <td>{{ optional($item->restock?->restock_date)->format('d M Y') ?? '—' }}</td>
                                                            <td><strong>{{ (int)$item->quantity }}</strong> units</td>
                                                            <td>{{ number_format((float)$item->purchase_price_per_unit, 2) }} {{ currency_symbol() }}</td>
                                                            <td>{{ number_format((float)$item->quantity * (float)$item->purchase_price_per_unit, 2) }} {{ currency_symbol() }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr style="background:#f1f5fb; font-weight:700; font-size:.8rem;">
                                                        <td colspan="2">Totals</td>
                                                        <td>{{ (int)$sale->quantity }} units</td>
                                                        <td>{{ number_format((float)$sale->purchase_price_per_unit, 2) }} {{ currency_symbol() }} avg</td>
                                                        <td>{{ number_format((float)$sale->quantity * (float)$sale->purchase_price_per_unit, 2) }} {{ currency_symbol() }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f9fbff; font-weight:700; font-size:.85rem;">
                            <td colspan="2" class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Totals (active)</td>
                            <td>{{ number_format($totalSold) }}</td>
                            <td colspan="3"></td>
                            <td>{{ number_format((float)$totalRevenue, 2) }}</td>
                            <td class="{{ $totalProfit < 0 ? 'profit-neg' : 'profit-pos' }}">{{ number_format((float)$totalProfit, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Rotate chevron icon on accordion toggle
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (row) {
        var targetId = row.getAttribute('data-bs-target');
        var saleId   = targetId.replace('#breakdown-', '');
        var chevron  = document.getElementById('chevron-' + saleId);

        if (!chevron) return;

        var collapse = document.querySelector(targetId);
        if (collapse) {
            collapse.addEventListener('show.bs.collapse', function () {
                chevron.style.transform = 'rotate(90deg)';
            });
            collapse.addEventListener('hide.bs.collapse', function () {
                chevron.style.transform = 'rotate(0deg)';
            });
        }
    });
</script>
@endpush
