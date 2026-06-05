<?php $__env->startSection('title', __('damage.title')); ?>

<?php $__env->startPush('styles'); ?>
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

    .btn-new-damage {
        background: linear-gradient(140deg, var(--damage-brand), var(--damage-brand-deep));
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

    .btn-new-damage:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
    }

    /* Single-row Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--damage-line);
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
        color: var(--damage-ink-500);
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
        color: var(--damage-ink-900) !important;
    }

    .filter-search-control:focus {
        background-color: #ffffff !important;
        border-color: var(--damage-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .filter-select-control {
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        height: 38px !important;
        color: var(--damage-ink-900) !important;
    }

    .filter-select-control:focus {
        background-color: #ffffff !important;
        border-color: var(--damage-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .btn-filter-submit {
        background: var(--damage-brand);
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        border: 1px solid var(--damage-brand);
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-filter-submit:hover {
        background: var(--damage-brand-deep);
        border-color: var(--damage-brand-deep);
        color: #ffffff;
    }

    /* Product Table Wrap & Row Height */
    .product-table-wrap {
        background: #fff;
        border: 1px solid var(--damage-line);
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--damage-line) !important;
        color: var(--damage-ink-700);
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
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="damage-shell">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 damage-header">
        <div>
            <span class="damage-kicker"><i class="bi bi-exclamation-triangle"></i> <?php echo e(__('damage.title')); ?></span>
            <h1 class="page-title display-font mb-1"><i class="bi bi-exclamation-triangle"></i> <?php echo e(__('damage.title')); ?></h1>
            <p class="page-subtitle mb-0"><?php echo e(__('damage.subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('damage.create')); ?>" class="btn-new-damage shadow-sm">
            <i class="bi bi-plus-circle"></i> <?php echo e(__('damage.record_damage')); ?>

        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="filter-toolbar mb-4 shadow-sm">
        <form method="GET" action="<?php echo e(route('damage.index')); ?>" class="m-0 w-100" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            type="text"
                            id="search"
                            class="form-control filter-search-control"
                            placeholder="Search damages by reference or shop...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="shop_id" id="shop_id" class="form-select filter-select-control">
                        <option value=""><?php echo e(__('damage.all_shops')); ?></option>
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop->id); ?>" <?php echo e((string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <input type="date" id="date_from" class="form-control filter-select-control" placeholder="From Date">
                    <input type="date" id="date_to" class="form-control filter-select-control" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-filter-submit w-100" type="submit">
                        <i class="bi bi-funnel"></i> <?php echo e(__('damage.apply_filters')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <div class="product-table-wrap p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 w-100" id="damagesDataTable">
                <thead>
                    <tr>
                        <th><?php echo e(__('damage.reference_no')); ?></th>
                        <th><?php echo e(__('damage.damage_date')); ?></th>
                        <th><?php echo e(__('damage.shop')); ?></th>
                        <th class="text-end"><?php echo e(__('damage.total_quantity')); ?></th>
                        <th class="text-end"><?php echo e(__('damage.total_loss')); ?></th>
                        <th><?php echo e(__('app.actions')); ?></th>
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
        if (!window.jQuery || !$('#damagesDataTable').length) {
            return;
        }

        var table = $('#damagesDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo e(route("damage.table")); ?>',
                data: function (d) {
                    d.shop_id = $('#shop_id').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                    d.search.value = $('#search').val();
                }
            },
            columns: [
                { data: 'reference_no_label', name: 'damages.reference_no' },
                { data: 'damage_date', name: 'damages.damage_date' },
                { data: 'shop_name_label', name: 'shops.name' },
                { data: 'total_quantity', name: 'damages.total_quantity', className: 'text-end fw-bold' },
                { data: 'total_loss', name: 'damages.total_loss', className: 'text-end text-danger fw-semibold' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            pageLength: 20,
            order: [[1, 'desc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: 'Search damages...',
            },
            dom: 'rtip',
        });

        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
        
        $('#shop_id, #date_from, #date_to').on('change', function () {
            table.ajax.reload();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Damage\resources/views/index.blade.php ENDPATH**/ ?>