@extends('layouts.app')

@section('title', __('restock.create_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --restock-form-ink-900: #0f172a;
        --restock-form-ink-700: #334155;
        --restock-form-ink-500: #64748b;
        --restock-form-brand: #0f766e;
        --restock-form-brand-deep: #155e75;
        --restock-form-line: #d8e4ee;
    }

    .restock-create-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--restock-form-ink-900);
    }

    .restock-create-shell::before {
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

    .form-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--restock-form-brand);
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
        color: var(--restock-form-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--restock-form-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--restock-form-line);
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

    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .form-control,
    .form-select,
    .input-group-text {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--restock-form-ink-900);
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .input-group-text {
        color: #5f7287;
        font-weight: 700;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
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

    .btn-submit {
        background: linear-gradient(140deg, var(--restock-form-brand), var(--restock-form-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.6rem 1.15rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
    }

    .btn-submit:hover {
        color: #fff;
    }

    .preview-hint {
        background: linear-gradient(140deg, rgba(14, 165, 233, 0.14), rgba(14, 165, 233, 0.06));
        border: 1px solid rgba(14, 165, 233, 0.2);
        color: #0f3c52;
        border-radius: 10px;
        font-size: 0.84rem;
    }

    .preview-item {
        border-radius: 12px;
        padding: 0.85rem;
        border-left: 3px solid transparent;
    }

    .preview-item-label {
        color: #5d6b7b;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.28rem;
    }

    .preview-item-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--restock-form-ink-900);
    }

    .preview-item.price {
        background: #f0f9ff;
        border-left-color: #0ea5e9;
    }

    .preview-item.total {
        background: #fff7ed;
        border-left-color: #f97316;
    }

    .preview-item.current {
        background: #f0fdf4;
        border-left-color: #22c55e;
    }

    .preview-item.after {
        background: #faf5ff;
        border-left-color: #a855f7;
    }

    .preview-formula {
        color: #c2410c;
        font-size: 0.82rem;
        margin-top: 0.18rem;
    }

    @media (max-width: 991.98px) {
        .restock-create-shell .col-md-8,
        .restock-create-shell .col-md-4 {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="restock-create-shell">
    <div class="mb-4">
        <span class="form-kicker"><i class="bi bi-box-arrow-in-down"></i>{{ __('restock.create_title') }}</span>
        <h1 class="page-title display-font">{{ __('restock.create_title') }}</h1>
        <p class="page-subtitle">{{ __('restock.create_subtitle') }}</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-box-seam"></i>
                        {{ __('restock.restock_info') }}
                    </h5>
                </div>
                <div class="p-4">
                    <form action="{{ route('restock.store') }}" method="POST" id="restockForm">
                        @csrf

                        <div class="mb-3">
                            <label for="shop_id" class="form-label fw-semibold">
                                {{ __('restock.shop') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('shop_id') is-invalid @enderror"
                                    id="shop_id"
                                    name="shop_id"
                                    required>
                                <option value="">{{ __('restock.select_shop') }}</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ (old('shop_id') ?: $prefilledShopId) == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label fw-semibold">
                                {{ __('restock.product') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('product_id') is-invalid @enderror"
                                    id="product_id"
                                    name="product_id"
                                    required>
                                <option value="">{{ __('restock.select_shop_first') }}</option>
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="product-info"></div>
                        </div>

                        <div class="mb-3" id="attributeFieldsWrapper" style="display:none;">
                            <label class="form-label fw-semibold">
                                {{ __('restock.attribute_combination') }} <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2" id="attributeFieldsContainer"></div>
                            <div class="form-text">{{ __('restock.attribute_help') }}</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-semibold">
                                    {{ __('restock.quantity') }} <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       value="{{ old('quantity', 1) }}"
                                       min="1"
                                       placeholder="{{ __('restock.enter_quantity') }}"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="purchase_price_per_unit" class="form-label fw-semibold">
                                    {{ __('restock.purchase_price') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ currency_symbol() }}</span>
                                    <input type="number"
                                           class="form-control @error('purchase_price_per_unit') is-invalid @enderror"
                                           id="purchase_price_per_unit"
                                           name="purchase_price_per_unit"
                                           value="{{ old('purchase_price_per_unit') }}"
                                           step="0.01"
                                           min="0.01"
                                           placeholder="{{ __('restock.enter_price') }}"
                                           required>
                                    @error('purchase_price_per_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="restock_date" class="form-label fw-semibold">
                                {{ __('restock.restock_date') }} <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('restock_date') is-invalid @enderror"
                                   id="restock_date"
                                   name="restock_date"
                                   value="{{ old('restock_date', date('Y-m-d')) }}"
                                   required>
                            @error('restock_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-semibold">{{ __('restock.note') }}</label>
                            <textarea class="form-control @error('note') is-invalid @enderror"
                                      id="note"
                                      name="note"
                                      rows="2"
                                      placeholder="{{ __('restock.note_placeholder') }}">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('restock.index') }}" class="btn btn-back">
                                <i class="bi bi-arrow-left"></i> {{ __('restock.back_to_list') }}
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <i class="bi bi-check-circle"></i> {{ __('restock.record_restock') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-3 mt-md-0">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-calculator"></i>
                        {{ __('restock.cost_preview') }}
                    </h5>
                </div>
                <div class="p-4">
                    <div class="alert preview-hint py-2 px-3 mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('restock.cost_hint') }}
                    </div>

                    <div id="preview-details" style="display:none;">
                        <div class="mb-3 preview-item price">
                            <div class="preview-item-label">{{ __('restock.product_purchase_price') }}</div>
                            <div class="preview-item-value" id="preview-product-price">-</div>
                        </div>

                        <div class="mb-3 preview-item total">
                            <div class="preview-item-label">{{ __('restock.total_cost_label') }}</div>
                            <div class="preview-formula" id="preview-formula"></div>
                            <div class="preview-item-value mt-1" id="preview-total-cost">-</div>
                        </div>

                        <div class="mb-3 preview-item current">
                            <div class="preview-item-label">{{ __('restock.current_stock_label') }}</div>
                            <div class="preview-item-value" id="preview-current-stock">-</div>
                        </div>

                        <div class="preview-item after">
                            <div class="preview-item-label">{{ __('restock.stock_after_label') }}</div>
                            <div class="preview-item-value text-success" id="preview-stock-after">-</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const shopSelect = document.getElementById('shop_id');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('purchase_price_per_unit');
    const productInfo = document.getElementById('product-info');
    const attributeFieldsWrapper = document.getElementById('attributeFieldsWrapper');
    const attributeFieldsContainer = document.getElementById('attributeFieldsContainer');
    const productsUrl = "{{ route('restock.products-by-shop') }}";
    const oldAttributeValues = @json(old('attribute_values', []));

    let productsData = [];

    shopSelect.addEventListener('change', function() {
        const shopId = this.value;
        productSelect.innerHTML = '';

        if (!shopId) {
            productSelect.innerHTML = '<option value="">{{ __("restock.select_shop_first") }}</option>';
            productsData = [];
            productInfo.innerHTML = '';
            updatePreview();
            return;
        }

        productSelect.innerHTML = '<option value="">{{ __("app.loading") }}</option>';

        fetch(productsUrl + '?shop_id=' + shopId)
            .then(response => response.json())
            .then(products => {
                productsData = products;
                productSelect.innerHTML = '<option value="">{{ __("restock.select_product") }}</option>';
                products.forEach(function(product) {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name + ' (Stock: ' + product.stock_quantity + ')';
                    option.dataset.stock = product.stock_quantity;
                    option.dataset.purchasePrice = product.purchase_price;
                    option.dataset.attributes = JSON.stringify(product.attributes || []);
                    productSelect.appendChild(option);
                });

                const oldProductId = "{{ old('product_id', $prefilledProductId ?? '') }}";
                if (oldProductId) {
                    productSelect.value = oldProductId;
                    productSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(() => {
                productSelect.innerHTML = '<option value="">{{ __("restock.select_product") }}</option>';
            });
    });

    productSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected && selected.value) {
            const stock = selected.dataset.stock;
            const purchasePrice = parseFloat(selected.dataset.purchasePrice) || 0;
            productInfo.innerHTML = '{{ __("restock.current_stock_label") }}: <strong>' + stock + '</strong> | {{ __("restock.product_purchase_price") }}: <strong>{{ currency_symbol() }}' + purchasePrice.toFixed(2) + '</strong>';

            const attributes = JSON.parse(selected.dataset.attributes || '[]');
            renderAttributeFields(attributes);

            if (!priceInput.value) {
                priceInput.value = purchasePrice.toFixed(2);
            }
        } else {
            productInfo.innerHTML = '';
            renderAttributeFields([]);
        }
        updatePreview();
    });

    quantityInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);

    function renderAttributeFields(attributes) {
        attributeFieldsContainer.innerHTML = '';

        if (!Array.isArray(attributes) || attributes.length === 0) {
            attributeFieldsWrapper.style.display = 'none';
            return;
        }

        attributes.forEach(function(attribute) {
            const col = document.createElement('div');
            col.className = 'col-md-6';

            const label = document.createElement('label');
            label.className = 'form-label fw-semibold';
            label.setAttribute('for', `attribute_${attribute.field_id}`);
            label.textContent = attribute.label + (attribute.is_required ? ' *' : '');

            let input;
            const fieldName = `attribute_values[${attribute.field_id}]`;
            const oldValue = oldAttributeValues[String(attribute.field_id)] ?? attribute.value ?? '';

            if (attribute.input_type === 'select' && Array.isArray(attribute.options) && attribute.options.length > 0) {
                input = document.createElement('select');
                input.className = 'form-select';
                input.name = fieldName;
                input.id = `attribute_${attribute.field_id}`;
                if (attribute.is_required) {
                    input.required = true;
                }

                attribute.options.forEach(function(optionValue) {
                    const option = document.createElement('option');
                    option.value = optionValue;
                    option.textContent = optionValue;
                    if (String(optionValue) === String(oldValue)) {
                        option.selected = true;
                    }
                    input.appendChild(option);
                });
            } else {
                input = document.createElement('input');
                input.type = attribute.input_type === 'number' ? 'number' : (attribute.input_type === 'date' ? 'date' : 'text');
                input.className = 'form-control';
                input.name = fieldName;
                input.id = `attribute_${attribute.field_id}`;
                input.value = oldValue;
                if (attribute.is_required) {
                    input.required = true;
                }
            }

            col.appendChild(label);
            col.appendChild(input);
            attributeFieldsContainer.appendChild(col);
        });

        attributeFieldsWrapper.style.display = 'block';
    }

    function updatePreview() {
        const previewDetails = document.getElementById('preview-details');
        const selected = productSelect.options[productSelect.selectedIndex];
        const qty = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        if (!selected || !selected.value || qty <= 0) {
            previewDetails.style.display = 'none';
            return;
        }

        previewDetails.style.display = 'block';

        const currentStock = parseInt(selected.dataset.stock) || 0;
        const productPrice = parseFloat(selected.dataset.purchasePrice) || 0;
        const totalCost = qty * price;
        const stockAfter = currentStock + qty;

        document.getElementById('preview-product-price').textContent = '{{ currency_symbol() }}' + productPrice.toFixed(2);
        document.getElementById('preview-formula').textContent = qty + ' × {{ currency_symbol() }}' + price.toFixed(2);
        document.getElementById('preview-total-cost').textContent = '{{ currency_symbol() }}' + totalCost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-current-stock').textContent = currentStock + ' {{ __("app.units") }}';
        document.getElementById('preview-stock-after').textContent = stockAfter + ' {{ __("app.units") }}';
    }

    if (shopSelect.value) {
        shopSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
