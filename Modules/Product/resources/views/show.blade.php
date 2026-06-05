@extends('layouts.app')

@section('title', __('product.show_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --product-show-ink-900: #0f172a;
        --product-show-ink-700: #334155;
        --product-show-ink-500: #64748b;
        --product-show-brand: #0f766e;
        --product-show-brand-deep: #155e75;
        --product-show-line: #d8e4ee;
    }

    .product-show-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--product-show-ink-900);
    }

    .product-show-shell::before {
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
        color: var(--product-show-brand);
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
        color: var(--product-show-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--product-show-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--product-show-line);
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

    .show-main-table td {
        border-color: #e7edf4;
        padding: 0.8rem 0.5rem;
    }

    .show-main-table td.fw-semibold {
        color: #475569;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .shop-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .profit-pill,
    .stock-pill {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .profit-positive,
    .stock-high {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .profit-negative,
    .stock-low {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .profit-neutral,
    .stock-mid {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .quick-stat-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .quick-stat-value {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: 1.25rem;
        line-height: 1.15;
        margin-bottom: 0;
        color: #0f172a;
    }

    .btn-action-back {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
    }

    .btn-action-back:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .btn-action-edit {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.32);
        border-radius: 999px;
        padding: 0.58rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .btn-action-edit:hover {
        background: #f59e0b;
        color: #fff;
        border-color: #f59e0b;
    }

    .btn-action-delete {
        background: rgba(220, 38, 38, 0.12);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.28);
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="product-show-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="show-kicker"><i class="bi bi-box-seam"></i>{{ __('product.show_title') }}</span>
        <h1 class="page-title display-font">{{ __('product.show_title') }}</h1>
        <p class="page-subtitle">{{ __('product.show_subtitle') }}</p>
    </div>
    <div>
        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-action-edit">
            <i class="bi bi-pencil"></i> {{ __('app.edit') }}
        </a>
        <a href="{{ route('product.index') }}" class="btn btn-action-back">
            <i class="bi bi-arrow-left"></i> {{ __('product.back_to_list') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('product.product_information') }}
                </h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless show-main-table">
                    <tbody>
                        <tr>
                            <td class="fw-semibold" style="width: 200px;">{{ __('product.product_name') }}:</td>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.shop') }}:</td>
                            <td><span class="shop-pill">{{ $product->shop->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.category') }}:</td>
                            <td>{{ $product->productCategory?->name ?? $product->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.brand') }}:</td>
                            <td>{{ $product->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.free_service') }}:</td>
                            <td>
                                @if($product->has_free_service)
                                    <span class="stock-pill stock-high">{{ __('product.free_service_enabled') }}</span>
                                    <small class="d-block text-muted mt-1">
                                        {{ (int) $product->free_service_duration_value }} {{ __('product.duration_' . ($product->free_service_duration_unit ?? 'month')) }}
                                    </small>
                                @else
                                    <span class="stock-pill stock-mid">{{ __('product.free_service_disabled') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.free_service_terms') }}:</td>
                            <td>{{ $product->free_service_terms ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.purchase_price') }}:</td>
                            <td>{{ number_format($product->purchase_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.current_stock') }}:</td>
                            <td>
                                @php
                                    $lowStockAlert = (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5);
                                @endphp
                                @if($product->stock_quantity <= $lowStockAlert)
                                    <span class="stock-pill stock-low">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                    <small class="text-danger d-block mt-1"> {{ __('product.low_stock_alert') }}</small>
                                @elseif($product->stock_quantity <= ($lowStockAlert * 4))
                                    <span class="stock-pill stock-mid">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                @else
                                    <span class="stock-pill stock-high">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.col_created_by') }}:</td>
                            <td>{{ $product->creator?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.created_at') }}:</td>
                            <td>{{ $product->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.updated_at') }}:</td>
                            <td>{{ $product->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>

                @php
                    $visibleDynamicValues = $product->dynamicValues
                        ->filter(fn ($value) => $value->dynamicField && $value->value !== null && $value->value !== '');
                @endphp

                @if($visibleDynamicValues->isNotEmpty())
                    <hr>
                    <h6 class="content-card-title mb-3">
                        <i class="bi bi-sliders"></i>
                        {{ __('product.custom_attributes') }}
                    </h6>
                    <table class="table table-borderless show-main-table mb-0">
                        <tbody>
                        @foreach($visibleDynamicValues as $dynamicValue)
                            <tr>
                                <td class="fw-semibold" style="width: 200px;">{{ $dynamicValue->dynamicField->label }}:</td>
                                <td>{{ $dynamicValue->value }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if($product->batches->isNotEmpty())
                    <hr>
                    <h6 class="content-card-title mb-3">
                        <i class="bi bi-layers"></i>
                        Batch Price History
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Date</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Initial Qty</th>
                                <th class="text-end">Remaining Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->batches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_code }}</td>
                                    <td>{{ optional($batch->batch_date)->format('d M Y') ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float) $batch->purchase_price, 2) }}</td>
                                    <td class="text-end">{{ number_format((int) $batch->initial_quantity) }}</td>
                                    <td class="text-end">{{ number_format((int) $batch->remaining_quantity) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    {{ __('product.quick_stats') }}
                </h5>
            </div>
            <div class="p-4">
                @php
                    $inventoryValue = $product->batches->sum(function ($batch) {
                        return (int) $batch->remaining_quantity * (float) $batch->purchase_price;
                    });
                @endphp

                <div class="mb-3">
                    <label class="quick-stat-label">{{ __('product.inventory_value') }}</label>
                    <h4 class="quick-stat-value">{{ number_format($inventoryValue, 2) }}</h4>
                    <small class="text-muted">Based on remaining quantities across all active batches</small>
                </div>

                <div>
                    <label class="quick-stat-label">{{ __('product.current_stock') }}</label>
                    <h4 class="quick-stat-value">{{ number_format($product->stock_quantity) }} {{ __('app.units') }}</h4>
                    <small class="text-muted">{{ __('product.stock_update_hint') }}</small>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-gear"></i>
                    {{ __('product.actions') }}
                </h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-action-edit">
                        <i class="bi bi-pencil"></i> {{ __('product.edit_product') }}
                    </a>
                    <form action="{{ route('product.destroy', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('{{ __("product.confirm_delete") }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-action-delete w-100">
                            <i class="bi bi-trash"></i> {{ __('product.delete_product') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
