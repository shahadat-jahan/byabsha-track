<?php $__env->startSection('title', __('shop.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --shop-ink-900: #0f172a;
        --shop-ink-700: #334155;
        --shop-ink-500: #64748b;
        --shop-brand: #0f766e;
        --shop-brand-deep: #155e75;
        --shop-line: #d8e4ee;
    }

    .shop-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--shop-ink-900);
    }

    .shop-shell::before {
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

    .shop-header {
        gap: 0.9rem;
    }

    .shop-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--shop-brand);
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
        color: var(--shop-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--shop-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-shop {
        background: linear-gradient(140deg, var(--shop-brand), var(--shop-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-add-shop:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--shop-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table.table-custom {
        margin-bottom: 0;
    }

    .table.table-custom thead th {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .table.table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table.table-custom tbody tr:hover {
        background: #fbfdff;
    }

    .shop-name {
        color: #1e293b;
        font-weight: 700;
    }

    .shop-date {
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .shop-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.38rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .shop-badge-products {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .shop-badge-sales {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .actions-cell {
        white-space: nowrap;
    }

    .btn-group .btn {
        border-radius: 10px !important;
        margin-right: 0.25rem;
        padding: 0.34rem 0.52rem;
        border-width: 1px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-outline-info {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }

    .btn-outline-info:hover {
        background: #0f766e;
        border-color: #0f766e;
        color: #fff;
    }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }

    .btn-outline-warning:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }

    .empty-state {
        padding: 2.5rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.2rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .btn-create-first {
        background: linear-gradient(140deg, var(--shop-brand), var(--shop-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 0.88rem;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
        text-decoration: none;
        line-height: 1;
    }

    .btn-create-first:hover {
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .shop-header {
            align-items: stretch !important;
        }

        .btn-add-shop {
            width: 100%;
            justify-content: center;
        }
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--shop-line);
        border-radius: 10px;
        padding: 0.35rem 1.8rem 0.35rem 0.75rem;
        font-size: 0.88rem;
        color: var(--shop-ink-700);
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--shop-line);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.88rem;
        color: var(--shop-ink-900);
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        outline: none;
        border-color: var(--shop-brand);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }
    .dataTables_info {
        font-size: 0.85rem;
        color: var(--shop-ink-500);
        padding-top: 1rem !important;
    }
    .dataTables_paginate {
        padding-top: 1rem !important;
    }
    .paginate_button.page-item.active .page-link {
        background-color: var(--shop-brand) !important;
        border-color: var(--shop-brand) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="shop-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap shop-header">
    <div>
        <span class="shop-kicker"><i class="bi bi-shop-window"></i><?php echo e(__('shop.title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('shop.title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('shop.subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('shop.create')); ?>" class="btn-add-shop">
        <i class="bi bi-plus-circle"></i> <?php echo e(__('shop.add_new')); ?>

    </a>
</div>

<div class="content-card">
    <div class="table-responsive p-3">
        <table id="shopsTable" class="table table-custom w-100">
            <thead>
                <tr>
                    <th><?php echo e(__('shop.col_name')); ?></th>
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        <th><?php echo e(__('app.shop_owner')); ?></th>
                    <?php endif; ?>
                    <th><?php echo e(__('shop.col_location')); ?></th>
                    <th><?php echo e(__('shop.col_address')); ?></th>
                    <th><?php echo e(__('shop.col_products')); ?></th>
                    <th><?php echo e(__('shop.col_sales')); ?></th>
                    <th><?php echo e(__('shop.col_branches')); ?></th>
                    <th><?php echo e(__('shop.col_created')); ?></th>
                    <th><?php echo e(__('shop.col_actions')); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
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
    $(document).ready(function() {
        $('#shopsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('shop.index')); ?>",
            columns: [
                { data: 'name', name: 'name', render: function(data, type, row) {
                    return '<strong class="shop-name">' + escapeHtml(data) + '</strong>';
                }},
                <?php if(auth()->user()->isSuperAdmin()): ?>
                    { data: 'owner_name', name: 'owner.name' },
                <?php endif; ?>
                { data: 'location', name: 'location', defaultContent: '-', render: function(data) {
                    return data ? escapeHtml(data) : '-';
                }},
                { data: 'address', name: 'address', defaultContent: '-', render: function(data) {
                    return data ? escapeHtml(data) : '-';
                }},
                { data: 'products_badge', name: 'products_count', orderable: false, searchable: false },
                { data: 'sales_badge', name: 'sales_count', orderable: false, searchable: false },
                { data: 'branches_badge', name: 'branches_count', orderable: false, searchable: false },
                { data: 'created_at_formatted', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'actions-cell text-center' }
            ],
            order: [[0, 'asc']],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                searchPlaceholder: "Search shops...",
                search: ""
            }
        });
        
        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Shop\resources/views/index.blade.php ENDPATH**/ ?>