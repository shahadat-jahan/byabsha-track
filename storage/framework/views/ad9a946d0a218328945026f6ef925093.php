<?php $__env->startSection('title', __('brand::brand.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --brand-ink-900: #0f172a;
        --brand-ink-700: #334155;
        --brand-ink-500: #64748b;
        --brand-accent: #0f766e;
        --brand-accent-deep: #155e75;
        --brand-line: #d8e4ee;
    }

    .brand-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--brand-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .brand-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--brand-accent);
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
        color: var(--brand-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--brand-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-brand {
        background: linear-gradient(140deg, var(--brand-accent), var(--brand-accent-deep));
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
        text-decoration: none;
    }

    .btn-add-brand:hover {
        color: #fff;
        filter: brightness(1.03);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--brand-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table.table-brand {
        margin-bottom: 0;
    }

    .table.table-brand thead th {
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

    .table.table-brand tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .brand-name {
        color: #1e293b;
        font-weight: 700;
    }

    .product-count-pill {
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

    .empty-state {
        padding: 2.8rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.3rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .btn-empty-brand {
        background: linear-gradient(140deg, var(--brand-accent), var(--brand-accent-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-empty-brand:hover {
        color: #fff;
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--brand-line);
        border-radius: 10px;
        padding: 0.35rem 1.8rem 0.35rem 0.75rem;
        font-size: 0.88rem;
        color: var(--brand-ink-700);
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--brand-line);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.88rem;
        color: var(--brand-ink-900);
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        outline: none;
        border-color: var(--brand-accent);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }
    .dataTables_info {
        font-size: 0.85rem;
        color: var(--brand-ink-500);
        padding-top: 1rem !important;
    }
    .dataTables_paginate {
        padding-top: 1rem !important;
    }
    .paginate_button.page-item.active .page-link {
        background-color: var(--brand-accent) !important;
        border-color: var(--brand-accent) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="brand-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="brand-kicker"><i class="bi bi-bookmark-star"></i><?php echo e(__('brand::brand.title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('brand::brand.title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('brand::brand.subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('brand.create')); ?>" class="btn-add-brand">
        <i class="bi bi-plus-circle"></i> <?php echo e(__('brand::brand.add_new')); ?>

    </a>
</div>

<?php if(auth()->user()->isSuperAdmin()): ?>
    <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4 d-inline-flex align-items-center gap-2">
        <i class="bi bi-info-circle-fill text-info fs-5"></i>
        <div>
            Total Brands Created: <strong class="text-dark"><?php echo e($totalBrandsCount); ?></strong> across all users.
        </div>
    </div>
<?php endif; ?>

<div class="content-card">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="brandsTable" class="table table-hover align-middle mb-0 table-brand w-100">
                <thead class="table-light">
                    <tr>
                        <th><?php echo e(__('brand::brand.name')); ?></th>
                        <?php if(auth()->user()->isSuperAdmin()): ?>
                            <th>Created By</th>
                        <?php endif; ?>
                        <th><?php echo e(__('brand::brand.product_count')); ?></th>
                        <th class="text-end"><?php echo e(__('app.actions')); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
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
        $('#brandsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('brand.index')); ?>",
            columns: [
                { data: 'name', name: 'name', render: function(data, type, row) {
                    return '<span class="brand-name">' + escapeHtml(data) + '</span>';
                }},
                <?php if(auth()->user()->isSuperAdmin()): ?>
                    { data: 'owner_info', name: 'owner.name' },
                <?php endif; ?>
                { data: 'products_badge', name: 'products_count', className: 'text-center', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[0, 'asc']],
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                searchPlaceholder: "Search brands...",
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Brand\resources/views/index.blade.php ENDPATH**/ ?>