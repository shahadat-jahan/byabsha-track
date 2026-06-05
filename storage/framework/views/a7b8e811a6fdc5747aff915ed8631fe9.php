<?php $__env->startSection('title', __('sale.warranty_title')); ?>

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

    /* Single-row Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--sale-line);
        border-radius: 14px;
        padding: 0.75rem 1rem;
    }

    .filter-select-control {
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        height: 38px !important;
        color: var(--sale-ink-900) !important;
    }

    .filter-select-control:focus {
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

    .table thead th {
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

    .table tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.5rem 1rem !important; /* Reduced row height! */
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status indicator badges */
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

    .status-pill-info {
        background: rgba(14, 165, 233, 0.08);
        color: #0369a1;
        border-color: rgba(14, 165, 233, 0.2);
    }

    .status-pill-info .status-indicator {
        background-color: #0ea5e9;
    }

    .status-pill-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .status-pill-danger .status-indicator {
        background-color: #ef4444;
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
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sale-shell">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 sale-header">
        <div>
            <span class="sale-kicker"><i class="bi bi-shield-check"></i> <?php echo e(__('sale.title')); ?></span>
            <h1 class="page-title display-font mb-1"><i class="bi bi-shield-check"></i> <?php echo e(__('sale.warranty_title')); ?></h1>
            <p class="page-subtitle mb-0"><?php echo e(__('sale.warranty_subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('sale.warranties.create')); ?>" class="btn-new-sale shadow-sm">
            <i class="bi bi-plus-circle"></i> <?php echo e(__('sale.warranty_create_btn')); ?>

        </a>
    </div>

    
    <div class="alert border-0 rounded-3 mb-4 p-3 d-flex align-items-start gap-3" style="background: rgba(15, 118, 110, 0.05); border: 1px solid rgba(15, 118, 110, 0.15) !important;">
        <i class="bi bi-info-circle-fill text-teal" style="font-size: 1.2rem; color: var(--sale-brand);"></i>
        <div>
            <strong style="color: var(--sale-ink-900); font-size: 0.88rem;"><?php echo e(__('sale.why_warranty_title')); ?></strong>
            <p class="mb-0 text-muted mt-1" style="font-size: 0.82rem; line-height: 1.45;">
                <?php echo e(__('sale.why_warranty_desc')); ?>

            </p>
        </div>
    </div>

    
    <div class="filter-toolbar mb-4 shadow-sm">
        <form method="GET" action="<?php echo e(route('sale.warranties.index')); ?>" class="m-0 w-100" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            type="text"
                            id="search"
                            class="form-control filter-search-control"
                            placeholder="Search warranties...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="shop_id" id="shop_id" class="form-select filter-select-control">
                        <option value=""><?php echo e(__('sale.all_shops')); ?></option>
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop->id); ?>" <?php echo e((string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" id="status" class="form-select filter-select-control">
                        <option value=""><?php echo e(__('sale.all_statuses')); ?></option>
                        <?php $__currentLoopData = ['active', 'claimed', 'expired', 'void']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($statusOption); ?>" <?php echo e(($filters['status'] ?? '') === $statusOption ? 'selected' : ''); ?>><?php echo e(__('sale.status_' . $statusOption)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-filter-submit w-100" type="submit">
                        <i class="bi bi-funnel"></i> <?php echo e(__('sale.apply_filters')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <div class="product-table-wrap p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 w-100" id="warrantiesDataTable">
                <thead>
                    <tr>
                        <th><?php echo e(__('sale.warranty_code')); ?></th>
                        <th><?php echo e(__('sale.shop')); ?></th>
                        <th><?php echo e(__('sale.product')); ?></th>
                        <th><?php echo e(__('sale.customer_name')); ?></th>
                        <th><?php echo e(__('sale.warranty_period')); ?></th>
                        <th><?php echo e(__('sale.warranty_status')); ?></th>
                        <th class="text-center"><?php echo e(__('sale.col_actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.jQuery || !$('#warrantiesDataTable').length) {
            return;
        }

        var table = $('#warrantiesDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo e(route("sale.warranties.table")); ?>',
                data: function (d) {
                    d.shop_id = $('#shop_id').val();
                    d.status = $('#status').val();
                    d.search.value = $('#search').val();
                }
            },
            columns: [
                { data: 'warranty_code_label', name: 'sale_warranties.warranty_code' },
                { data: 'shop_name_label', name: 'shops.name' },
                { data: 'product_name', name: 'products.name' },
                { data: 'customer_name', name: 'sales.customer_name' },
                { data: 'warranty_period_label', name: 'sale_warranties.end_date' },
                { data: 'status_label', name: 'sale_warranties.status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            pageLength: 20,
            order: [[0, 'desc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: 'Search warranties...',
            },
            dom: 'rtip',
        });

        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
        
        $('#shop_id, #status').on('change', function () {
            table.ajax.reload();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Sale\resources/views/warranties/index.blade.php ENDPATH**/ ?>