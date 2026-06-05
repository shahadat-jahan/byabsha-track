

<?php $__env->startSection('title', __('sale.create_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --sale-form-ink-900: #0f172a;
        --sale-form-ink-700: #334155;
        --sale-form-ink-500: #64748b;
        --sale-form-brand: #0f766e;
        --sale-form-brand-deep: #155e75;
        --sale-form-line: #d8e4ee;
    }

    .sale-form-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--sale-form-ink-900);
    }

    .sale-form-shell::before {
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
        color: var(--sale-form-brand);
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
        color: var(--sale-form-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--sale-form-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--sale-form-line);
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
    .form-select {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--sale-form-ink-900);
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
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
        background: linear-gradient(140deg, var(--sale-form-brand), var(--sale-form-brand-deep));
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

    @media (max-width: 991.98px) {
        .sale-form-shell .col-md-8,
        .sale-form-shell .col-md-4 {
            width: 100%;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sale-form-shell">
<div class="mb-4">
    <span class="form-kicker"><i class="bi bi-plus-circle"></i><?php echo e(__('sale.create_title')); ?></span>
    <h1 class="page-title display-font"><?php echo e(__('sale.create_title')); ?></h1>
    <p class="page-subtitle"><?php echo e(__('sale.create_subtitle')); ?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-receipt"></i>
                    <?php echo e(__('sale.sale_information')); ?>

                </h5>
            </div>
            <div class="p-4">
                <form action="<?php echo e(route('sale.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="shop_id" class="form-label fw-semibold">
                            <?php echo e(__('sale.shop')); ?> <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="shop_id"
                                name="shop_id"
                                required>
                            <option value=""><?php echo e(__('sale.select_shop')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id') == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="product_batch_id" class="form-label fw-semibold">
                            <?php echo e(__('sale.batch')); ?> <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?php $__errorArgs = ['product_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="product_batch_id"
                                name="product_batch_id"
                                data-selected-product-id="<?php echo e(old('product_batch_id', '')); ?>"
                                required>
                            <option value=""><?php echo e(__('sale.select_batch')); ?></option>
                        </select>
                        <?php $__errorArgs = ['product_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text" id="stock-info"></div>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">
                            <?php echo e(__('sale.quantity')); ?> <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="quantity"
                               name="quantity"
                               value="<?php echo e(old('quantity', 1)); ?>"
                               min="1"
                               placeholder="<?php echo e(__('sale.enter_quantity')); ?>"
                               required>
                        <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="sale_date" class="form-label fw-semibold">
                            <?php echo e(__('sale.sale_date_label')); ?> <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control <?php $__errorArgs = ['sale_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="sale_date"
                               name="sale_date"
                               value="<?php echo e(old('sale_date', date('Y-m-d'))); ?>"
                               required>
                        <?php $__errorArgs = ['sale_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="<?php echo e(route('sale.index')); ?>" class="btn btn-back">
                            <i class="bi bi-arrow-left"></i> <?php echo e(__('sale.back_to_list')); ?>

                        </a>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle"></i> <?php echo e(__('sale.create_btn')); ?>

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
                    <?php echo e(__('sale.calc_preview')); ?>

                </h5>
            </div>
            <div class="p-4">
                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php echo e(__('sale.calc_hint')); ?>

                </div>

                <div id="calc-details" style="display:none;">
                    <div class="mb-3 p-3 rounded" style="background:#f0f9ff; border-left:3px solid #0ea5e9;">
                        <div class="text-muted small fw-semibold mb-1"><?php echo e(__('sale.total_amount_label')); ?></div>
                        <div class="text-primary small" id="formula-total"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-total">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#fff7ed; border-left:3px solid #f97316;">
                        <div class="text-muted small fw-semibold mb-1"><?php echo e(__('sale.purchase_cost_label')); ?></div>
                        <div class="text-warning small" id="formula-cost"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-cost">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #22c55e;">
                        <div class="text-muted small fw-semibold mb-1"><?php echo e(__('sale.profit_label')); ?></div>
                        <div class="text-success small" id="formula-profit"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-profit">—</div>
                    </div>
                    <div class="p-3 rounded" style="background:#faf5ff; border-left:3px solid #a855f7;">
                        <div class="text-muted small fw-semibold mb-1"><?php echo e(__('sale.margin_label')); ?></div>
                        <div class="small" id="formula-margin" style="color:#7c3aed;"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-margin">—</div>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded" style="background:#f8fafc; border:1px dashed #cbd5e1; font-size:0.8rem;">
                    <div class="fw-semibold text-muted mb-2"><i class="bi bi-lightbulb me-1"></i><?php echo e(__('sale.formulas_used')); ?></div>
                    <div class="text-muted"><?php echo e(__('sale.formula_total')); ?></div>
                    <div class="text-muted"><?php echo e(__('sale.formula_cost')); ?></div>
                    <div class="text-muted"><?php echo e(__('sale.formula_profit')); ?></div>
                    <div class="text-muted"><?php echo e(__('sale.formula_margin')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const productsByShopEndpoint = "<?php echo e(route('sale.products-by-shop')); ?>";

async function loadProductsByShop(selectedShopId) {
    const productSelect = document.getElementById('product_batch_id');
    const selectedProductId = productSelect.dataset.selectedProductId || '';

    productSelect.innerHTML = `<option value=""><?php echo e(__('sale.select_batch')); ?></option>`;

    if (!selectedShopId) {
        updateCalcPreview();
        return;
    }

    try {
        const response = await fetch(`${productsByShopEndpoint}?shop_id=${selectedShopId}`);
        const products = await response.json();

        products.forEach((product) => {
            const option = document.createElement('option');
            option.value = product.id;
            option.setAttribute('data-product-id', product.product_id);
            option.setAttribute('data-product-name', product.product_name);
            option.setAttribute('data-batch-code', product.batch_code);
            option.setAttribute('data-stock', product.stock_quantity);
            option.setAttribute('data-price', product.sale_price);
            option.setAttribute('data-purchase-price', product.purchase_price);
            option.setAttribute('data-attribute-summary', product.attribute_summary || '-');
            option.textContent = `${product.product_name} | Batch: ${product.batch_code} | Attr: ${product.attribute_summary || '-'} | Stock: ${product.stock_quantity} | Price: ${Number(product.sale_price).toFixed(2)}`;

            if (String(selectedProductId) === String(product.id)) {
                option.selected = true;
            }

            productSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Failed to load products by shop:', error);
    }

    updateCalcPreview();
}

function filterProductsByShop() {
    const shopSelect = document.getElementById('shop_id');
    const selectedShopId = shopSelect.value;

    loadProductsByShop(selectedShopId);
}

function updateCalcPreview() {
    const productSelect = document.getElementById('product_batch_id');
    const quantityInput = document.getElementById('quantity');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const stockInfo = document.getElementById('stock-info');

    const salePrice     = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const purchasePrice = parseFloat(selectedOption.getAttribute('data-purchase-price')) || 0;
    const stock         = selectedOption.getAttribute('data-stock');
    const qty           = parseInt(quantityInput.value) || 0;
    const batchCode     = selectedOption.getAttribute('data-batch-code') || '-';
    const attributeSummary = selectedOption.getAttribute('data-attribute-summary') || '-';

    if (stock) {
        stockInfo.innerHTML = `Batch <strong>${batchCode}</strong> | Attr: <strong>${attributeSummary}</strong> | Available Stock: <strong>${stock}</strong> units | Sale Price: <strong>${salePrice.toFixed(2)}</strong>`;
        quantityInput.max = stock;
    } else {
        stockInfo.innerHTML = '';
    }

    const calcDetails = document.getElementById('calc-details');
    if (salePrice <= 0 || qty <= 0) { calcDetails.style.display = 'none'; return; }
    calcDetails.style.display = 'block';

    const totalAmount = qty * salePrice;
    const totalCost   = qty * purchasePrice;
    const profit      = (salePrice - purchasePrice) * qty;
    const margin      = totalAmount > 0 ? (profit / totalAmount) * 100 : 0;

    document.getElementById('formula-total').textContent  = `${qty} × ${salePrice.toFixed(2)}`;
    document.getElementById('value-total').textContent    = totalAmount.toFixed(2);

    document.getElementById('formula-cost').textContent   = `${qty} × ${purchasePrice.toFixed(2)}`;
    document.getElementById('value-cost').textContent     = totalCost.toFixed(2);

    document.getElementById('formula-profit').textContent = `(${salePrice.toFixed(2)} − ${purchasePrice.toFixed(2)}) × ${qty}`;
    const profitEl = document.getElementById('value-profit');
    profitEl.textContent = profit.toFixed(2);
    profitEl.className   = 'fs-5 fw-bold mt-1 ' + (profit > 0 ? 'text-success' : (profit < 0 ? 'text-danger' : 'text-secondary'));

    document.getElementById('formula-margin').textContent = `(${profit.toFixed(2)} ÷ ${totalAmount.toFixed(2)}) × 100`;
    document.getElementById('value-margin').textContent   = margin.toFixed(2) + '%';
}

document.getElementById('shop_id').addEventListener('change', filterProductsByShop);
document.getElementById('product_batch_id').addEventListener('change', updateCalcPreview);
document.getElementById('quantity').addEventListener('input', updateCalcPreview);
document.addEventListener('DOMContentLoaded', filterProductsByShop);
</script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Sale\resources/views/create.blade.php ENDPATH**/ ?>