<?php $__env->startSection('title', __('sale.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --sale-ink-900: #0f172a;
        --sale-ink-700: #334155;
        --sale-ink-500: #64748b;
        --sale-brand: #0f766e;
        --sale-brand-deep: #155e75;
        --sale-line: #d8e4ee;
    }

    .sale-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--sale-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.02em;
    }

    .sale-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(15, 118, 110, 0.08);
        color: var(--sale-brand);
        border: 1px solid rgba(15, 118, 110, 0.18);
        border-radius: 999px;
        padding: 0.3rem 0.8rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .page-title {
        font-size: clamp(1.4rem, 2.5vw, 1.85rem);
        font-weight: 800;
        color: var(--sale-ink-900);
    }

    .page-subtitle {
        color: var(--sale-ink-500);
        font-size: 0.88rem;
    }

    /* Actions Header */
    .btn-new-sale {
        background: linear-gradient(140deg, var(--sale-brand), var(--sale-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 0.52rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 8px 20px rgba(15, 118, 110, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
    }

    .btn-new-sale:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
    }

    /* Shop switcher segmented pill buttons */
    .shop-switcher-card {
        background: #ffffff;
        border: 1px solid var(--sale-line);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }

    .shop-switcher {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .shop-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.48rem 0.95rem;
        border-radius: 10px;
        border: 1px solid #d8e4ee;
        background: #ffffff;
        color: var(--sale-ink-700);
        font-weight: 600;
        font-size: 0.84rem;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .shop-tab-btn:hover {
        background: #f8fafc;
        color: var(--sale-brand);
        border-color: #cbd5e1;
    }

    .shop-tab-btn.active {
        background: var(--sale-brand);
        color: #ffffff;
        border-color: var(--sale-brand);
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.18);
    }

    /* Single-row Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--sale-line);
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
        color: var(--sale-ink-500);
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
        color: var(--sale-ink-900) !important;
    }

    .filter-search-control:focus {
        background-color: #ffffff !important;
        border-color: var(--sale-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .btn-filter-submit {
        background: var(--sale-brand);
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        border: 1px solid var(--sale-brand);
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-filter-submit:hover {
        background: var(--sale-brand-deep);
        border-color: var(--sale-brand-deep);
        color: #ffffff;
    }

    /* Product Table Wrap & Row Height */
    .product-table-wrap {
        background: #fff;
        border: 1px solid var(--sale-line);
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    #shopProductsTable thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--sale-line) !important;
        color: var(--sale-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 1rem;
        white-space: nowrap;
    }

    #shopProductsTable tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.5rem 1rem !important; /* Reduced row height! */
        font-size: 0.85rem;
        vertical-align: middle;
    }

    #shopProductsTable tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Attributes Chips list */
    .attribute-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        max-width: 220px;
        margin: 0;
        padding: 0;
    }

    .custom-attr-badge {
        display: inline-flex;
        align-items: center;
        background-color: #f1f5f9;
        color: var(--sale-ink-700);
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 0.1rem 0.35rem;
        font-size: 0.68rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .custom-attr-badge strong {
        font-weight: 700;
        color: var(--sale-ink-900);
    }

    /* Batch presentation wrapper */
    .batch-info-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .batch-code-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 6px;
        padding: 0.15rem 0.45rem;
        font-size: 0.76rem;
        font-weight: 700;
        background: #f1f5f9;
        color: var(--sale-ink-900);
        border: 1px solid #e2e8f0;
        align-self: start;
        white-space: nowrap;
    }

    .batch-date-sub {
        font-size: 0.68rem;
        color: var(--sale-ink-500);
    }

    /* Status badges for Stock */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        border-width: 1px;
        border-style: solid;
        white-space: nowrap;
    }

    .status-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-pill-success {
        background: rgba(16, 185, 129, 0.08);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-pill-success .status-indicator {
        background-color: #10b981;
    }

    .status-pill-warning {
        background: rgba(245, 158, 11, 0.08);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .status-pill-warning .status-indicator {
        background-color: #f59e0b;
    }

    .status-pill-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .status-pill-danger .status-indicator {
        background-color: #ef4444;
        animation: statusPulse 2s infinite ease-in-out;
    }

    @keyframes statusPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Profit and Loss Badges */
    .profit-badge {
        display: inline-flex;
        align-items: center;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.22rem 0.55rem;
        border-radius: 6px;
        border-width: 1px;
        border-style: solid;
        white-space: nowrap;
    }

    .profit-badge-positive {
        background: rgba(16, 185, 129, 0.08);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .profit-badge-negative {
        background: rgba(220, 38, 38, 0.08);
        color: #b91c1c;
        border-color: rgba(220, 38, 38, 0.2);
    }

    .profit-badge-neutral {
        background: #f1f5f9;
        color: #475569;
        border-color: #cbd5e1;
    }

    /* Actions styling */
    .btn-sale-primary {
        background-color: var(--sale-brand);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.8rem;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid var(--sale-brand);
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.15);
        transition: all 0.2s;
    }

    .btn-sale-primary:hover:not(:disabled) {
        background-color: var(--sale-brand-deep);
        border-color: var(--sale-brand-deep);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(15, 118, 110, 0.22);
    }

    .btn-sale-primary:disabled {
        background-color: #cbd5e1;
        border-color: #cbd5e1;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    .btn-actions-dropdown {
        border-radius: 8px !important;
        padding: 0.35rem 0.5rem !important;
        border: 1px solid #cedce9 !important;
        background-color: #ffffff;
    }

    .btn-actions-dropdown:hover {
        background-color: #f8fafc;
        border-color: #94a3b8 !important;
    }

    .stock-hint {
        font-size: 0.86rem;
        color: #64748b;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sale-shell">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 sale-header">
        <div>
            <span class="sale-kicker"><i class="bi bi-receipt-cutoff"></i> <?php echo e(__('sale.title')); ?></span>
            <h1 class="page-title display-font mb-1"><i class="bi bi-cart-check"></i> <?php echo e(__('sale.shop_products_title')); ?></h1>
            <p class="page-subtitle mb-0"><?php echo e(__('sale.shop_products_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('sale.warranties.index')); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-shield-check"></i> <?php echo e(__('sale.warranty_title')); ?>

            </a>
            <a href="<?php echo e(route('sale.exchanges.index')); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left-right"></i> <?php echo e(__('sale.exchange_title')); ?>

            </a>
            <a href="<?php echo e(route('sale.create')); ?>" class="btn-new-sale shadow-sm">
                <i class="bi bi-plus-circle"></i> <?php echo e(__('sale.new_sale')); ?>

            </a>
        </div>
    </div>

    
    <div class="shop-switcher-card mb-4 p-3">
        <div class="selected-shop-title mb-2 text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;"><?php echo e(__('sale.col_shop')); ?></div>
        <div class="d-flex flex-wrap gap-2 shop-switcher" id="shopSwitcher">
            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    type="button"
                    class="shop-tab-btn <?php echo e((int) $selectedShopId === (int) $shop->id ? 'active' : ''); ?>"
                    data-shop-id="<?php echo e($shop->id); ?>"
                    data-shop-name="<?php echo e($shop->name); ?>"
                >
                    <i class="bi bi-shop"></i>
                    <span><?php echo e($shop->name); ?></span>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($shops->isEmpty()): ?>
            <div class="text-muted small"><?php echo e(__('sale.no_shops_found')); ?></div>
        <?php endif; ?>
    </div>

    
    <div class="filter-toolbar mb-4 shadow-sm">
        <form class="m-0 w-100" id="salesFilterForm">
            <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            type="text"
                            id="search"
                            class="form-control filter-search-control"
                            placeholder="Search by product name, batch code, category, or attributes...">
                    </div>
                </div>
                <div class="flex-shrink-0 d-flex gap-2">
                    <button type="submit" class="btn btn-filter-submit">
                        <i class="bi bi-funnel"></i> <span class="d-none d-sm-inline"><?php echo e(__('app.apply_filters')); ?></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <div class="product-table-wrap p-3">
        <div class="table-responsive">
            <table id="shopProductsTable" class="table align-middle mb-0 w-100">
                <thead>
                    <tr>
                        <th><?php echo e(__('sale.table_product_name')); ?></th>
                        <th><?php echo e(__('sale.table_batch')); ?></th>
                        <th><?php echo e(__('sale.table_attributes')); ?></th>
                        <th class="text-end"><?php echo e(__('sale.table_buying_price')); ?></th>
                        <th><?php echo e(__('sale.table_category')); ?></th>
                        <th class="text-center"><?php echo e(__('sale.table_stock')); ?></th>
                        <th class="text-end"><?php echo e(__('sale.table_profit')); ?></th>
                        <th class="text-end"><?php echo e(__('sale.table_loss')); ?></th>
                        <th class="text-center"><?php echo e(__('sale.col_actions')); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="quickSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('sale.quick_sale_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickSaleForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" id="qsShopId" name="shop_id">
                    <input type="hidden" id="qsProductId" name="product_id">
                    <input type="hidden" id="qsProductBatchId" name="product_batch_id">

                    <div class="mb-2">
                        <strong id="qsProductName"></strong>
                    </div>
                    <div class="mb-2 stock-hint" id="qsBatchHint"></div>
                    <div class="mb-2 stock-hint" id="qsAttributeHint"></div>
                    <div class="mb-3 stock-hint" id="qsStockHint"></div>

                    <div class="mb-3 border rounded p-3 bg-white">
                        <div class="small text-uppercase fw-bold text-muted mb-2"><?php echo e(__('sale.table_attributes')); ?></div>
                        <div id="qsAttributeDetails" class="small"></div>
                    </div>

                    <div class="mb-3">
                        <label for="qsSalePrice" class="form-label"><?php echo e(__('sale.quick_sale_price')); ?></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="qsSalePrice" name="sale_price" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsDiscount" class="form-label"><?php echo e(__('sale.quick_sale_discount')); ?></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="qsDiscount" name="discount" value="0">
                        <div class="form-text"><?php echo e(__('sale.quick_sale_discount_hint')); ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="qsQuantity" class="form-label"><?php echo e(__('sale.quick_sale_quantity')); ?></label>
                        <input type="number" min="1" class="form-control" id="qsQuantity" name="quantity" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerName" class="form-label"><?php echo e(__('sale.quick_sale_customer_name')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerName" name="customer_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerPhone" class="form-label"><?php echo e(__('sale.quick_sale_customer_phone')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerPhone" name="customer_phone">
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerAddress" class="form-label"><?php echo e(__('sale.quick_sale_customer_address')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerAddress" name="customer_address">
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('sale.quick_sale_effective_unit_price')); ?></span>
                            <strong id="qsEffectiveUnitPrice">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('sale.quick_sale_total_amount')); ?></span>
                            <strong id="qsTotalAmount">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span><?php echo e(__('sale.quick_sale_total_cost')); ?></span>
                            <strong id="qsTotalCost">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span><?php echo e(__('sale.quick_sale_profit_loss')); ?></span>
                            <strong id="qsProfitLoss" class="profit-neutral">0.00</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="qsSaleDate" class="form-label"><?php echo e(__('sale.sale_date_label')); ?></label>
                        <input type="date" class="form-control" id="qsSaleDate" name="sale_date" value="<?php echo e(now()->toDateString()); ?>">
                    </div>

                    <div class="alert alert-info d-none" id="qsServicePreview">
                        <div class="small text-uppercase fw-bold mb-1"><?php echo e(__('sale.quick_sale_free_service_preview')); ?></div>
                        <div><strong><?php echo e(__('sale.free_service_start')); ?>:</strong> <span id="qsServiceStart">-</span></div>
                        <div><strong><?php echo e(__('sale.free_service_expiry')); ?>:</strong> <span id="qsServiceExpiry">-</span></div>
                        <div><strong><?php echo e(__('sale.warranty_period')); ?>:</strong> <span id="qsServiceDuration">-</span></div>
                        <div class="mt-1" id="qsServiceTermsWrap">
                            <strong><?php echo e(__('sale.warranty_terms')); ?>:</strong> <span id="qsServiceTerms">-</span>
                        </div>
                    </div>

                    <div class="alert alert-danger d-none" id="quickSaleError"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-success" id="quickSaleSubmitBtn">
                        <i class="bi bi-check-circle"></i> <?php echo e(__('sale.quick_sale_submit')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    let selectedShopId = <?php echo json_encode($selectedShopId, 15, 512) ?>;
    const productsTableUrl = <?php echo json_encode(route('sale.products-table'), 15, 512) ?>;
    const quickSaleUrl = <?php echo json_encode(route('sale.quick-sale'), 15, 512) ?>;
    const quickSaleModalEl = document.getElementById('quickSaleModal');
    const quickSaleModal = new bootstrap.Modal(quickSaleModalEl);
    const qsSalePriceEl = document.getElementById('qsSalePrice');
    const qsDiscountEl = document.getElementById('qsDiscount');
    const qsQuantityEl = document.getElementById('qsQuantity');
    const qsEffectiveUnitPriceEl = document.getElementById('qsEffectiveUnitPrice');
    const qsTotalAmountEl = document.getElementById('qsTotalAmount');
    const qsTotalCostEl = document.getElementById('qsTotalCost');
    const qsProfitLossEl = document.getElementById('qsProfitLoss');
    const qsBatchHintEl = document.getElementById('qsBatchHint');
    const qsAttributeHintEl = document.getElementById('qsAttributeHint');
    const qsAttributeDetailsEl = document.getElementById('qsAttributeDetails');
    const qsSaleDateEl = document.getElementById('qsSaleDate');
    const qsServicePreviewEl = document.getElementById('qsServicePreview');
    const qsServiceStartEl = document.getElementById('qsServiceStart');
    const qsServiceExpiryEl = document.getElementById('qsServiceExpiry');
    const qsServiceDurationEl = document.getElementById('qsServiceDuration');
    const qsServiceTermsWrapEl = document.getElementById('qsServiceTermsWrap');
    const qsServiceTermsEl = document.getElementById('qsServiceTerms');
    let currentPurchasePrice = 0;
    let currentFreeService = {
        enabled: false,
        durationValue: null,
        durationUnit: null,
        terms: '',
    };

    const durationUnitLabels = {
        day: <?php echo json_encode(__('sale.duration_unit_day'), 15, 512) ?>,
        month: <?php echo json_encode(__('sale.duration_unit_month'), 15, 512) ?>,
        year: <?php echo json_encode(__('sale.duration_unit_year'), 15, 512) ?>,
    };

    const numberFormatter = new Intl.NumberFormat(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderAttributeList(attributes) {
        const items = Array.isArray(attributes) ? attributes : [];
        const visibleItems = items.filter((item) => item && typeof item === 'object' && String(item.value ?? '').trim() !== '');

        if (!visibleItems.length) {
            return '<span class="text-muted">-</span>';
        }

        return '<div class="attribute-list">' + visibleItems.map((item) => {
            const label = escapeHtml(item.label || item.field_key || 'Attribute');
            const value = escapeHtml(item.value || '');
            return '<span class="custom-attr-badge" title="' + label + ': ' + value + '"><strong>' + label + ':</strong> ' + value + '</span>';
        }).join('') + '</div>';
    }

    const table = $('#shopProductsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        dom: 'rtip',
        ajax: {
            url: productsTableUrl,
            data: function (d) {
                d.shop_id = selectedShopId;
                d.search = { value: $('#search').val() };
            }
        },
        columns: [
            { data: 'name', name: 'products.name' },
            { data: 'batch_label', name: 'product_batches.batch_code' },
            {
                data: 'attribute_values',
                name: 'attribute_summary',
                orderable: false,
                render: function (data, type, row) {
                    if (type !== 'display') {
                        return row.attribute_summary || '-';
                    }

                    return renderAttributeList(data);
                }
            },
            { data: 'purchase_price', name: 'product_batches.purchase_price', className: 'text-end' },
            { data: 'category_name', name: 'products.category' },
            {
                data: 'stock_quantity',
                name: 'product_batches.remaining_quantity',
                className: 'text-center',
                render: function (data, type) {
                    const stock = parseInt(data || 0);
                    if (type !== 'display') {
                        return stock;
                    }
                    if (stock <= 0) {
                        return '<span class="status-pill status-pill-danger"><span class="status-indicator"></span>' + stock + ' (Out)</span>';
                    } else if (stock <= 5) {
                        return '<span class="status-pill status-pill-warning"><span class="status-indicator"></span>' + stock + ' (Low)</span>';
                    } else {
                        return '<span class="status-pill status-pill-success"><span class="status-indicator"></span>' + stock + '</span>';
                    }
                }
            },
            {
                data: 'latest_profit',
                name: 'latest_profit',
                className: 'text-end',
                render: function (data, type) {
                    const value = parseFloat(data || 0);
                    if (type !== 'display') {
                        return value;
                    }
                    const badgeClass = value > 0 ? 'profit-badge-positive' : (value < 0 ? 'profit-badge-negative' : 'profit-badge-neutral');
                    return '<span class="profit-badge ' + badgeClass + '">' + numberFormatter.format(value) + '</span>';
                }
            },
            {
                data: 'latest_loss',
                name: 'latest_loss',
                className: 'text-end',
                orderable: false,
                searchable: false,
                render: function (data, type) {
                    const value = parseFloat(data || 0);
                    if (type !== 'display') {
                        return value;
                    }
                    const badgeClass = value > 0 ? 'profit-badge-negative' : 'profit-badge-neutral';
                    return '<span class="profit-badge ' + badgeClass + '">' + numberFormatter.format(value) + '</span>';
                }
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[0, 'asc']],
    });

    // Intercept custom search form
    $('#salesFilterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

    function recalculateQuickSalePreview() {
        const salePrice = parseFloat(qsSalePriceEl.value || '0');
        const discount = parseFloat(qsDiscountEl.value || '0');
        const quantity = parseInt(qsQuantityEl.value || '0', 10);
        const effectiveUnitPrice = Math.max(salePrice - (isNaN(discount) ? 0 : discount), 0);
        const totalAmount = effectiveUnitPrice * quantity;
        const totalCost = currentPurchasePrice * quantity;
        const profitLoss = totalAmount - totalCost;

        qsEffectiveUnitPriceEl.textContent = numberFormatter.format(isNaN(effectiveUnitPrice) ? 0 : effectiveUnitPrice);
        qsTotalAmountEl.textContent = numberFormatter.format(isNaN(totalAmount) ? 0 : totalAmount);
        qsTotalCostEl.textContent = numberFormatter.format(isNaN(totalCost) ? 0 : totalCost);
        qsProfitLossEl.textContent = numberFormatter.format(isNaN(profitLoss) ? 0 : profitLoss);

        qsProfitLossEl.classList.remove('profit-positive', 'profit-negative', 'profit-neutral');
        if (profitLoss > 0) {
            qsProfitLossEl.classList.add('profit-positive');
        } else if (profitLoss < 0) {
            qsProfitLossEl.classList.add('profit-negative');
        } else {
            qsProfitLossEl.classList.add('profit-neutral');
        }

        recalculateServicePreview();
    }

    function formatDateISO(dateObj) {
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function recalculateServicePreview() {
        if (!currentFreeService.enabled || !currentFreeService.durationValue || !currentFreeService.durationUnit) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        const rawSaleDate = qsSaleDateEl.value;
        const baseDate = rawSaleDate ? new Date(rawSaleDate + 'T00:00:00') : new Date();
        if (Number.isNaN(baseDate.getTime())) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        const expiryDate = new Date(baseDate.getTime());
        const durationValue = parseInt(currentFreeService.durationValue, 10);
        if (!Number.isFinite(durationValue) || durationValue <= 0) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        if (currentFreeService.durationUnit === 'day') {
            expiryDate.setDate(expiryDate.getDate() + durationValue);
        } else if (currentFreeService.durationUnit === 'month') {
            expiryDate.setMonth(expiryDate.getMonth() + durationValue);
        } else if (currentFreeService.durationUnit === 'year') {
            expiryDate.setFullYear(expiryDate.getFullYear() + durationValue);
        }

        const unitLabel = durationUnitLabels[currentFreeService.durationUnit] || currentFreeService.durationUnit;
        qsServiceStartEl.textContent = formatDateISO(baseDate);
        qsServiceExpiryEl.textContent = formatDateISO(expiryDate);
        qsServiceDurationEl.textContent = String(durationValue) + ' ' + unitLabel;

        if (currentFreeService.terms) {
            qsServiceTermsEl.textContent = currentFreeService.terms;
            qsServiceTermsWrapEl.classList.remove('d-none');
        } else {
            qsServiceTermsEl.textContent = '-';
            qsServiceTermsWrapEl.classList.add('d-none');
        }

        qsServicePreviewEl.classList.remove('d-none');
    }

    document.getElementById('shopSwitcher').addEventListener('click', function (event) {
        const button = event.target.closest('button[data-shop-id]');
        if (!button) {
            return;
        }

        selectedShopId = parseInt(button.dataset.shopId, 10);

        document.querySelectorAll('#shopSwitcher button[data-shop-id]').forEach((item) => {
            item.classList.remove('active');
        });
        button.classList.add('active');

        table.ajax.reload();
    });

    document.addEventListener('click', function (event) {
        const saleBtn = event.target.closest('.js-sale-btn');
        if (!saleBtn) {
            return;
        }

        const stock = parseInt(saleBtn.dataset.stock || '0', 10);
        currentPurchasePrice = parseFloat(saleBtn.dataset.purchasePrice || '0');
        const defaultSalePrice = currentPurchasePrice;
        let attributeValues = [];

        try {
            attributeValues = saleBtn.dataset.attributeValues ? JSON.parse(saleBtn.dataset.attributeValues) : [];
        } catch (error) {
            attributeValues = [];
        }

        document.getElementById('qsShopId').value = saleBtn.dataset.shopId;
        document.getElementById('qsProductId').value = saleBtn.dataset.productId;
        document.getElementById('qsProductBatchId').value = saleBtn.dataset.batchId;
        document.getElementById('qsProductName').textContent = saleBtn.dataset.productName;
        qsBatchHintEl.textContent = 'Batch: ' + (saleBtn.dataset.batchCode || '-');
        qsAttributeHintEl.textContent = '<?php echo e(__('sale.table_attributes')); ?>: ' + (saleBtn.dataset.attributeSummary || '-');
        document.getElementById('qsStockHint').textContent = '<?php echo e(__('sale.available_stock')); ?>: ' + stock;
        document.getElementById('qsQuantity').max = stock;
        document.getElementById('qsQuantity').value = 1;
        document.getElementById('qsSalePrice').value = Number.isFinite(defaultSalePrice) ? defaultSalePrice : 0;
        document.getElementById('qsDiscount').value = 0;
        document.getElementById('qsSaleDate').value = '<?php echo e(now()->toDateString()); ?>';
        document.getElementById('qsCustomerName').value = '';
        document.getElementById('qsCustomerPhone').value = '';
        document.getElementById('qsCustomerAddress').value = '';
        document.getElementById('quickSaleError').classList.add('d-none');
        document.getElementById('quickSaleError').textContent = '';

        if (qsAttributeDetailsEl) {
            qsAttributeDetailsEl.innerHTML = renderAttributeList(attributeValues);
        }

        currentFreeService = {
            enabled: parseInt(saleBtn.dataset.hasFreeService || '0', 10) === 1,
            durationValue: saleBtn.dataset.freeServiceDurationValue || null,
            durationUnit: saleBtn.dataset.freeServiceDurationUnit || null,
            terms: saleBtn.dataset.freeServiceTerms || '',
        };

        recalculateQuickSalePreview();

        quickSaleModal.show();
    });

    qsSalePriceEl.addEventListener('input', recalculateQuickSalePreview);
    qsDiscountEl.addEventListener('input', recalculateQuickSalePreview);
    qsQuantityEl.addEventListener('input', recalculateQuickSalePreview);
    qsSaleDateEl.addEventListener('input', recalculateServicePreview);

    document.getElementById('quickSaleForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const submitBtn = document.getElementById('quickSaleSubmitBtn');
        const errorEl = document.getElementById('quickSaleError');
        const formData = new FormData(event.target);

        submitBtn.disabled = true;
        errorEl.classList.add('d-none');
        errorEl.textContent = '';

        try {
            const response = await fetch(quickSaleUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const payload = await response.json();

            if (!response.ok) {
                const message = payload.message || '<?php echo e(__('app.validation_error')); ?>';
                throw new Error(message);
            }

            quickSaleModal.hide();
            table.ajax.reload(null, false);
        } catch (error) {
            errorEl.textContent = error.message;
            errorEl.classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Sale\resources/views/index.blade.php ENDPATH**/ ?>