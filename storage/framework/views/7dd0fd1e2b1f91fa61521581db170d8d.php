<?php $__env->startSection('title', __('product.show_title')); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="product-show-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="show-kicker"><i class="bi bi-box-seam"></i><?php echo e(__('product.show_title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('product.show_title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('product.show_subtitle')); ?></p>
    </div>
    <div>
        <a href="<?php echo e(route('product.edit', $product->id)); ?>" class="btn btn-action-edit">
            <i class="bi bi-pencil"></i> <?php echo e(__('app.edit')); ?>

        </a>
        <a href="<?php echo e(route('product.index')); ?>" class="btn btn-action-back">
            <i class="bi bi-arrow-left"></i> <?php echo e(__('product.back_to_list')); ?>

        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    <?php echo e(__('product.product_information')); ?>

                </h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless show-main-table">
                    <tbody>
                        <tr>
                            <td class="fw-semibold" style="width: 200px;"><?php echo e(__('product.product_name')); ?>:</td>
                            <td><?php echo e($product->name); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.shop')); ?>:</td>
                            <td><span class="shop-pill"><?php echo e($product->shop->name); ?></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.category')); ?>:</td>
                            <td><?php echo e($product->productCategory?->name ?? $product->category ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.brand')); ?>:</td>
                            <td><?php echo e($product->brand ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.free_service')); ?>:</td>
                            <td>
                                <?php if($product->has_free_service): ?>
                                    <span class="stock-pill stock-high"><?php echo e(__('product.free_service_enabled')); ?></span>
                                    <small class="d-block text-muted mt-1">
                                        <?php echo e((int) $product->free_service_duration_value); ?> <?php echo e(__('product.duration_' . ($product->free_service_duration_unit ?? 'month'))); ?>

                                    </small>
                                <?php else: ?>
                                    <span class="stock-pill stock-mid"><?php echo e(__('product.free_service_disabled')); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.free_service_terms')); ?>:</td>
                            <td><?php echo e($product->free_service_terms ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.purchase_price')); ?>:</td>
                            <td><?php echo e(number_format($product->purchase_price, 2)); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.current_stock')); ?>:</td>
                            <td>
                                <?php
                                    $lowStockAlert = (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5);
                                ?>
                                <?php if($product->stock_quantity <= $lowStockAlert): ?>
                                    <span class="stock-pill stock-low"><?php echo e($product->stock_quantity); ?> <?php echo e(__('app.units')); ?></span>
                                    <small class="text-danger d-block mt-1"> <?php echo e(__('product.low_stock_alert')); ?></small>
                                <?php elseif($product->stock_quantity <= ($lowStockAlert * 4)): ?>
                                    <span class="stock-pill stock-mid"><?php echo e($product->stock_quantity); ?> <?php echo e(__('app.units')); ?></span>
                                <?php else: ?>
                                    <span class="stock-pill stock-high"><?php echo e($product->stock_quantity); ?> <?php echo e(__('app.units')); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('product.col_created_by')); ?>:</td>
                            <td><?php echo e($product->creator?->name ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('app.created_at')); ?>:</td>
                            <td><?php echo e($product->created_at->format('d M Y, h:i A')); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold"><?php echo e(__('app.updated_at')); ?>:</td>
                            <td><?php echo e($product->updated_at->format('d M Y, h:i A')); ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php
                    $visibleDynamicValues = $product->dynamicValues
                        ->filter(fn ($value) => $value->dynamicField && $value->value !== null && $value->value !== '');
                ?>

                <?php if($visibleDynamicValues->isNotEmpty()): ?>
                    <hr>
                    <h6 class="content-card-title mb-3">
                        <i class="bi bi-sliders"></i>
                        <?php echo e(__('product.custom_attributes')); ?>

                    </h6>
                    <table class="table table-borderless show-main-table mb-0">
                        <tbody>
                        <?php $__currentLoopData = $visibleDynamicValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dynamicValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold" style="width: 200px;"><?php echo e($dynamicValue->dynamicField->label); ?>:</td>
                                <td><?php echo e($dynamicValue->value); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if($product->batches->isNotEmpty()): ?>
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
                            <?php $__currentLoopData = $product->batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($batch->batch_code); ?></td>
                                    <td><?php echo e(optional($batch->batch_date)->format('d M Y') ?? '-'); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float) $batch->purchase_price, 2)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((int) $batch->initial_quantity)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((int) $batch->remaining_quantity)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    <?php echo e(__('product.quick_stats')); ?>

                </h5>
            </div>
            <div class="p-4">
                <?php
                    $inventoryValue = $product->batches->sum(function ($batch) {
                        return (int) $batch->remaining_quantity * (float) $batch->purchase_price;
                    });
                ?>

                <div class="mb-3">
                    <label class="quick-stat-label"><?php echo e(__('product.inventory_value')); ?></label>
                    <h4 class="quick-stat-value"><?php echo e(number_format($inventoryValue, 2)); ?></h4>
                    <small class="text-muted">Based on remaining quantities across all active batches</small>
                </div>

                <div>
                    <label class="quick-stat-label"><?php echo e(__('product.current_stock')); ?></label>
                    <h4 class="quick-stat-value"><?php echo e(number_format($product->stock_quantity)); ?> <?php echo e(__('app.units')); ?></h4>
                    <small class="text-muted"><?php echo e(__('product.stock_update_hint')); ?></small>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-gear"></i>
                    <?php echo e(__('product.actions')); ?>

                </h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('product.edit', $product->id)); ?>" class="btn btn-action-edit">
                        <i class="bi bi-pencil"></i> <?php echo e(__('product.edit_product')); ?>

                    </a>
                    <form action="<?php echo e(route('product.destroy', $product->id)); ?>"
                          method="POST"
                          onsubmit="return confirm('<?php echo e(__("product.confirm_delete")); ?>')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-action-delete w-100">
                            <i class="bi bi-trash"></i> <?php echo e(__('product.delete_product')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Product\resources/views/show.blade.php ENDPATH**/ ?>