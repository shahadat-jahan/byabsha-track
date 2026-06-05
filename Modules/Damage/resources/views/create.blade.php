@extends('layouts.app')

@section('title', __('damage.create_title'))

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

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--damage-line);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .form-card-header {
        background: #ffffff;
        border-bottom: 1px solid #edf3f8;
        padding: 1.25rem 1.5rem;
    }

    .form-card-title {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--damage-ink-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .form-control-custom,
    .form-select-custom {
        padding: 0.62rem 0.9rem !important;
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        color: var(--damage-ink-900) !important;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        background-color: #ffffff !important;
        border-color: var(--damage-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--damage-ink-700);
        margin-bottom: 0.42rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .btn-submit-premium {
        background: linear-gradient(140deg, var(--damage-brand), var(--damage-brand-deep));
        color: white;
        border: none;
        padding: 0.66rem 1.4rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.86rem;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.24);
    }

    .btn-submit-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.3);
        color: white;
    }

    .line-item-card {
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e4edf3;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    .line-item-card:hover {
        border-color: #cbdbe6;
        background: #fdfefe;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    }

    .btn-add-line {
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid #c7d8e6;
        color: #3f556c;
        background: rgba(255, 255, 255, 0.84);
        padding: 0.45rem 0.9rem;
    }

    .btn-add-line:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }
</style>
@endpush

@section('content')
<div class="damage-shell">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 damage-header">
        <div>
            <span class="damage-kicker"><i class="bi bi-exclamation-triangle"></i> {{ __('damage.title') }}</span>
            <h1 class="page-title display-font mb-1">{{ __('damage.create_title') }}</h1>
            <p class="page-subtitle mb-0">{{ __('damage.create_subtitle') }}</p>
        </div>
        <a href="{{ route('damage.index') }}" class="btn-back-custom shadow-sm">
            <i class="bi bi-arrow-left"></i> {{ __('damage.back_to_list') }}
        </a>
    </div>

    <form method="POST" action="{{ route('damage.store') }}" id="damageForm" class="m-0">
        @csrf

        {{-- Summary Information --}}
        <div class="form-card mb-4">
            <div class="form-card-header">
                <h5 class="form-card-title">
                    <i class="bi bi-card-heading text-teal"></i>
                    {{ __('damage.summary') }}
                </h5>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">{{ __('damage.shop') }} *</label>
                        <select class="form-select form-select-custom @error('shop_id') is-invalid @enderror" id="shop_id" name="shop_id" required>
                            <option value="">{{ __('damage.select_shop') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                            @endforeach
                        </select>
                        @error('shop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">{{ __('damage.damage_date') }} *</label>
                        <input type="date" class="form-control form-control-custom @error('damage_date') is-invalid @enderror" name="damage_date" value="{{ old('damage_date', now()->toDateString()) }}" required>
                        @error('damage_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">{{ __('damage.note') }}</label>
                        <input type="text" class="form-control form-control-custom @error('note') is-invalid @enderror" name="note" value="{{ old('note') }}">
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="form-card mb-4">
            <div class="form-card-header d-flex justify-content-between align-items-center">
                <h5 class="form-card-title">
                    <i class="bi bi-list-task text-teal"></i>
                    {{ __('damage.line_items') }}
                </h5>
                <button type="button" class="btn btn-add-line" id="addLineBtn">
                    <i class="bi bi-plus-circle me-1"></i>{{ __('damage.add_line') }}
                </button>
            </div>
            <div class="form-card-body">
                <div id="lineItems"></div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn-submit-premium">
                <i class="bi bi-check-circle"></i>
                {{ __('damage.record_damage') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const batchesByShopUrl = @json(route('damage.batches-by-shop'));
    const lineItemsWrap = document.getElementById('lineItems');
    const addLineBtn = document.getElementById('addLineBtn');
    const shopSelect = document.getElementById('shop_id');

    let lineIndex = 0;
    let availableBatches = [];

    function reasonOptions(selected = '') {
        const options = [
            { value: 'damaged', label: @json(__('damage.reason_damaged')) },
            { value: 'expired', label: @json(__('damage.reason_expired')) },
            { value: 'spoiled', label: @json(__('damage.reason_spoiled')) },
            { value: 'missing', label: @json(__('damage.reason_missing')) },
            { value: 'adjustment', label: @json(__('damage.reason_adjustment')) },
        ];

        return options.map((opt) => {
            const isSelected = String(opt.value) === String(selected) ? 'selected' : '';
            return `<option value="${opt.value}" ${isSelected}>${opt.label}</option>`;
        }).join('');
    }

    function batchOptions(selectedBatchId = '') {
        const first = `<option value="">${@json(__('damage.select_batch'))}</option>`;

        const rows = availableBatches.map((batch) => {
            const label = `${batch.product_name} | ${batch.batch_code} | ${batch.attribute_summary || '-'} | ${@json(__('damage.available_stock'))}: ${batch.stock_quantity}`;
            const selected = String(batch.id) === String(selectedBatchId) ? 'selected' : '';

            return `<option value="${batch.id}" data-product-id="${batch.product_id}" data-stock="${batch.stock_quantity}" data-purchase-price="${batch.purchase_price}" ${selected}>${label}</option>`;
        }).join('');

        return first + rows;
    }

    function addLine(defaults = {}) {
        const idx = lineIndex++;
        const row = document.createElement('div');
        row.className = 'line-item-card line-item';
        row.innerHTML = `
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label form-label-custom">${@json(__('damage.batch'))} *</label>
                    <select class="form-select form-select-custom batch-select" name="items[${idx}][product_batch_id]" required>
                        ${batchOptions(defaults.product_batch_id || '')}
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label form-label-custom">${@json(__('damage.quantity'))} *</label>
                    <input type="number" min="1" class="form-control form-control-custom quantity-input" name="items[${idx}][quantity]" value="${defaults.quantity || 1}" required>
                </div>
                <div class="col-lg-2">
                    <label class="form-label form-label-custom">${@json(__('damage.purchase_price'))}</label>
                    <input type="text" class="form-control form-control-custom purchase-price-input" readonly>
                </div>
                <div class="col-lg-2">
                    <label class="form-label form-label-custom">${@json(__('damage.reason'))}</label>
                    <select class="form-select form-select-custom" name="items[${idx}][reason]">
                        ${reasonOptions(defaults.reason || 'damaged')}
                    </select>
                </div>
                <div class="col-lg-2 text-end">
                    <button type="button" class="btn btn-outline-danger remove-line-btn btn-sm" style="height: 38px; border-radius: 10px; width: 100%;">
                        <i class="bi bi-trash me-1"></i>${@json(__('damage.remove_line'))}
                    </button>
                </div>
                <div class="col-12">
                    <label class="form-label form-label-custom">${@json(__('damage.reason_note'))}</label>
                    <input type="text" class="form-control form-control-custom" name="items[${idx}][reason_note]" value="${defaults.reason_note || ''}">
                </div>
                <input type="hidden" class="product-id-input" name="items[${idx}][product_id]" value="${defaults.product_id || ''}">
            </div>
        `;

        const batchSelect = row.querySelector('.batch-select');
        const qtyInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.purchase-price-input');
        const productIdInput = row.querySelector('.product-id-input');

        function syncBatchMeta() {
            const selected = batchSelect.options[batchSelect.selectedIndex];
            const productId = selected ? selected.getAttribute('data-product-id') : '';
            const stock = selected ? parseInt(selected.getAttribute('data-stock') || '0', 10) : 0;
            const purchasePrice = selected ? parseFloat(selected.getAttribute('data-purchase-price') || '0') : 0;

            productIdInput.value = productId || '';
            qtyInput.max = stock > 0 ? String(stock) : '';
            priceInput.value = purchasePrice ? Number(purchasePrice).toFixed(2) : '';
        }

        batchSelect.addEventListener('change', syncBatchMeta);
        row.querySelector('.remove-line-btn').addEventListener('click', () => row.remove());

        lineItemsWrap.appendChild(row);
        syncBatchMeta();
    }

    async function loadBatches(shopId) {
        availableBatches = [];

        if (!shopId) {
            lineItemsWrap.innerHTML = '';
            return;
        }

        const response = await fetch(`${batchesByShopUrl}?shop_id=${encodeURIComponent(shopId)}`);
        availableBatches = await response.json();

        lineItemsWrap.querySelectorAll('.line-item').forEach((line) => line.remove());
        addLine();
    }

    shopSelect.addEventListener('change', (event) => {
        loadBatches(event.target.value).catch(() => {
            availableBatches = [];
            lineItemsWrap.innerHTML = '';
        });
    });

    addLineBtn.addEventListener('click', () => addLine());

    document.addEventListener('DOMContentLoaded', () => {
        const oldItems = @json(old('items', []));
        const selectedShop = shopSelect.value;

        if (!selectedShop) {
            addLine();
            return;
        }

        loadBatches(selectedShop)
            .then(() => {
                lineItemsWrap.innerHTML = '';

                if (Array.isArray(oldItems) && oldItems.length > 0) {
                    oldItems.forEach((item) => addLine(item));
                } else {
                    addLine();
                }
            })
            .catch(() => {
                addLine();
            });
    });
</script>
@endpush
