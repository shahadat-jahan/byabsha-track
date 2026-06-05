

<?php $__env->startSection('title', __('sale.product_sales_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1"><?php echo e(__('sale.product_sales_title')); ?></h1>
        <p class="text-muted mb-0"><?php echo e(__('sale.product_sales_subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('sale.index', ['shop_id' => $product->shop_id])); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> <?php echo e(__('sale.back_to_list')); ?>

    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.product')); ?></div>
                <div class="fw-semibold"><?php echo e($product->name); ?></div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.shop')); ?></div>
                <div class="fw-semibold"><?php echo e($product->shop?->name ?? '-'); ?></div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.category')); ?></div>
                <div class="fw-semibold"><?php echo e($product->category ?? '-'); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.quantity')); ?></div>
                <div class="h5 mb-0"><?php echo e((int) ($totals->total_quantity ?? 0)); ?></div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.total_amount')); ?></div>
                <div class="h5 mb-0"><?php echo e(number_format((float) ($totals->total_amount ?? 0), 2)); ?></div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small"><?php echo e(__('sale.profit')); ?></div>
                <div class="h5 mb-0 <?php echo e((float) ($totals->total_profit ?? 0) < 0 ? 'text-danger' : 'text-success'); ?>">
                    <?php echo e(number_format((float) ($totals->total_profit ?? 0), 2)); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th><?php echo e(__('sale.col_date')); ?></th>
                        <th><?php echo e(__('sale.table_batch')); ?></th>
                        <th><?php echo e(__('sale.col_quantity')); ?></th>
                        <th><?php echo e(__('sale.col_sale_price')); ?></th>
                        <th><?php echo e(__('sale.table_buying_price')); ?></th>
                        <th><?php echo e(__('sale.quick_sale_discount')); ?></th>
                        <th><?php echo e(__('sale.col_total')); ?></th>
                        <th><?php echo e(__('sale.col_profit')); ?></th>
                        <th><?php echo e(__('sale.col_customer_name')); ?></th>
                        <th><?php echo e(__('sale.col_customer_phone')); ?></th>
                        <th><?php echo e(__('sale.col_customer_address')); ?></th>
                        <th><?php echo e(__('sale.free_service_start')); ?></th>
                        <th><?php echo e(__('sale.free_service_expiry')); ?></th>
                        <th><?php echo e(__('sale.free_service_status')); ?></th>
                        <th><?php echo e(__('sale.col_actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $serviceRecord = $sale->warranties->first();
                            $serviceStatus = $serviceRecord
                                ? (($serviceRecord->status === 'active' && $serviceRecord->end_date->isPast()) ? 'expired' : $serviceRecord->status)
                                : null;
                        ?>
                        <tr>
                            <td><?php echo e(optional($sale->sale_date)->format('d M Y') ?? '-'); ?></td>
                            <td><?php echo e($sale->productBatch?->batch_code ?? '-'); ?></td>
                            <td><?php echo e((int) $sale->quantity); ?></td>
                            <td><?php echo e(number_format((float) $sale->sale_price, 2)); ?></td>
                            <td><?php echo e(number_format((float) ($sale->purchase_price_per_unit ?? $sale->productBatch?->purchase_price ?? 0), 2)); ?></td>
                            <td><?php echo e(number_format((float) ($sale->discount ?? 0), 2)); ?></td>
                            <td><?php echo e(number_format((float) $sale->total_amount, 2)); ?></td>
                            <td class="<?php echo e((float) $sale->profit < 0 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format((float) $sale->profit, 2)); ?>

                            </td>
                            <td><?php echo e($sale->customer_name ?: '-'); ?></td>
                            <td><?php echo e($sale->customer_phone ?: '-'); ?></td>
                            <td><?php echo e($sale->customer_address ?: '-'); ?></td>
                            <td><?php echo e($serviceRecord?->start_date?->format('d M Y') ?? '-'); ?></td>
                            <td><?php echo e($serviceRecord?->end_date?->format('d M Y') ?? '-'); ?></td>
                            <td>
                                <?php if($serviceStatus): ?>
                                    <span class="badge bg-<?php echo e($serviceStatus === 'active' ? 'success' : ($serviceStatus === 'claimed' ? 'info' : ($serviceStatus === 'expired' ? 'warning text-dark' : 'secondary'))); ?>">
                                        <?php echo e(__('sale.status_' . $serviceStatus)); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-info js-sale-detail-btn"
                                    title="<?php echo e(__('sale.show_title')); ?>"
                                    data-sale-id="<?php echo e($sale->id); ?>"
                                    data-sale-date="<?php echo e(optional($sale->sale_date)->format('d M Y') ?? '-'); ?>"
                                    data-batch-code="<?php echo e($sale->productBatch?->batch_code ?? '-'); ?>"
                                    data-quantity="<?php echo e((int) $sale->quantity); ?>"
                                    data-sale-price="<?php echo e(number_format((float) $sale->sale_price, 2, '.', '')); ?>"
                                    data-purchase-price="<?php echo e(number_format((float) ($sale->purchase_price_per_unit ?? 0), 2, '.', '')); ?>"
                                    data-discount="<?php echo e(number_format((float) ($sale->discount ?? 0), 2, '.', '')); ?>"
                                    data-total-amount="<?php echo e(number_format((float) $sale->total_amount, 2, '.', '')); ?>"
                                    data-profit="<?php echo e(number_format((float) $sale->profit, 2, '.', '')); ?>"
                                    data-customer-name="<?php echo e($sale->customer_name ?: '-'); ?>"
                                    data-customer-phone="<?php echo e($sale->customer_phone ?: '-'); ?>"
                                    data-customer-address="<?php echo e($sale->customer_address ?: '-'); ?>"
                                    data-service-start="<?php echo e($serviceRecord?->start_date?->format('d M Y') ?? '-'); ?>"
                                    data-service-expiry="<?php echo e($serviceRecord?->end_date?->format('d M Y') ?? '-'); ?>"
                                    data-service-status="<?php echo e($serviceStatus ? __('sale.status_' . $serviceStatus) : '-'); ?>"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="15" class="text-center py-4 text-muted"><?php echo e(__('sale.no_product_sales')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if($sales->hasPages()): ?>
    <div class="mt-3">
        <?php echo e($sales->links()); ?>

    </div>
<?php endif; ?>

<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('sale.show_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.sale_date')); ?></div>
                        <div class="fw-semibold" id="modalSaleDate">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.quantity_sold')); ?></div>
                        <div class="fw-semibold" id="modalQuantity">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.table_batch')); ?></div>
                        <div class="fw-semibold" id="modalBatchCode">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.sale_price_unit')); ?></div>
                        <div class="fw-semibold" id="modalSalePrice">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.table_buying_price')); ?></div>
                        <div class="fw-semibold" id="modalPurchasePrice">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.quick_sale_discount')); ?></div>
                        <div class="fw-semibold" id="modalDiscount">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.total_amount')); ?></div>
                        <div class="fw-semibold" id="modalTotalAmount">-</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small"><?php echo e(__('sale.profit')); ?></div>
                        <div class="fw-semibold" id="modalProfit">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.customer_name')); ?></div>
                        <div class="fw-semibold" id="modalCustomerName">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.customer_phone')); ?></div>
                        <div class="fw-semibold" id="modalCustomerPhone">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.customer_address')); ?></div>
                        <div class="fw-semibold" id="modalCustomerAddress">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.free_service_start')); ?></div>
                        <div class="fw-semibold" id="modalServiceStart">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.free_service_expiry')); ?></div>
                        <div class="fw-semibold" id="modalServiceExpiry">-</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small"><?php echo e(__('sale.free_service_status')); ?></div>
                        <div class="fw-semibold" id="modalServiceStatus">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('saleDetailsModal');
        const detailsModal = new bootstrap.Modal(modalElement);

        const formatNumber = (value) => {
            const num = Number(value);
            if (!Number.isFinite(num)) {
                return '0.00';
            }
            return num.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        };

        document.addEventListener('click', function (event) {
            const button = event.target.closest('.js-sale-detail-btn');
            if (!button) {
                return;
            }

            document.getElementById('modalSaleDate').textContent = button.dataset.saleDate || '-';
            document.getElementById('modalQuantity').textContent = button.dataset.quantity || '-';
            document.getElementById('modalBatchCode').textContent = button.dataset.batchCode || '-';
            document.getElementById('modalSalePrice').textContent = formatNumber(button.dataset.salePrice);
            document.getElementById('modalPurchasePrice').textContent = formatNumber(button.dataset.purchasePrice);
            document.getElementById('modalDiscount').textContent = formatNumber(button.dataset.discount);
            document.getElementById('modalTotalAmount').textContent = formatNumber(button.dataset.totalAmount);

            const profitValue = Number(button.dataset.profit || 0);
            const modalProfit = document.getElementById('modalProfit');
            modalProfit.textContent = formatNumber(profitValue);
            modalProfit.classList.remove('text-success', 'text-danger');
            if (profitValue < 0) {
                modalProfit.classList.add('text-danger');
            } else {
                modalProfit.classList.add('text-success');
            }

            document.getElementById('modalCustomerName').textContent = button.dataset.customerName || '-';
            document.getElementById('modalCustomerPhone').textContent = button.dataset.customerPhone || '-';
            document.getElementById('modalCustomerAddress').textContent = button.dataset.customerAddress || '-';
            document.getElementById('modalServiceStart').textContent = button.dataset.serviceStart || '-';
            document.getElementById('modalServiceExpiry').textContent = button.dataset.serviceExpiry || '-';
            document.getElementById('modalServiceStatus').textContent = button.dataset.serviceStatus || '-';

            detailsModal.show();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Sale\resources/views/product-sales.blade.php ENDPATH**/ ?>