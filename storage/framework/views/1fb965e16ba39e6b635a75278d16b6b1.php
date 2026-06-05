<?php $__env->startSection('title', __('product.dynamic_fields_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .product-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: #0f172a;
    }

    .product-shell::before {
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

    .product-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .page-title {
        font-size: clamp(1.45rem, 2.8vw, 2rem);
        line-height: 1.1;
        margin-bottom: 0.35rem;
    }

    .page-subtitle {
        color: #334155;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid #d8e4ee;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f7fbff !important;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.64rem 0.72rem;
        white-space: nowrap;
    }

    .table tbody td {
        border-color: #e7edf4;
        padding: 0.58rem 0.72rem;
        vertical-align: middle;
        font-size: 0.87rem;
    }

    .table tbody tr:hover {
        background: #fbfdff;
    }

    .key-chip {
        display: inline-block;
        border: 1px solid #d6e2ee;
        background: #f8fbff;
        color: #35516b;
        border-radius: 999px;
        padding: 0.15rem 0.5rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .btn-group .btn {
        border-radius: 8px !important;
        margin-right: 0.2rem;
        padding: 0.26rem 0.42rem;
        border-width: 1px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-primary-pill {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.58rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.26);
        text-decoration: none;
    }

    .btn-primary-pill:hover {
        color: #fff;
        filter: brightness(1.03);
    }

    .btn-soft-secondary {
        border-radius: 999px;
        padding: 0.58rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid #9eb8cb;
        color: #1f3f58;
        background: #f7fbff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
    }

    .btn-soft-secondary:hover {
        color: #0f172a;
        border-color: #6f93b0;
        background: #ffffff;
    }

    .btn-action-edit {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }

    .btn-action-edit:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    .dataTables_filter {
        margin-bottom: 1.15rem;
        text-align: right;
    }
    .dataTables_filter label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #334155;
        font-weight: 600;
    }
    .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #cedce9;
        padding: 0.38rem 0.85rem;
        outline: none;
        font-size: 0.86rem;
        color: #0f172a;
        transition: border-color 0.2s;
        background-color: #f8fafc;
        width: 240px;
    }
    .dataTables_filter input:focus {
        border-color: #0f766e;
        background-color: #ffffff;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15);
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="product-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="product-kicker"><i class="bi bi-sliders"></i><?php echo e(__('product.dynamic_fields_title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('product.dynamic_fields_title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('product.dynamic_fields_subtitle')); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('product.index')); ?>" class="btn-soft-secondary">
            <i class="bi bi-arrow-left"></i> <?php echo e(__('product.back_to_list')); ?>

        </a>
        <a href="<?php echo e(route('product.dynamic-fields.create')); ?>" class="btn-primary-pill">
            <i class="bi bi-plus-circle"></i> <?php echo e(__('product.add_dynamic_field')); ?>

        </a>
    </div>
</div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle mb-0 w-100" id="dynamicFieldsTable">
            <thead class="table-light">
                <tr>
                    <th><?php echo e(__('product.dynamic_label')); ?></th>
                    <th><?php echo e(__('product.dynamic_key')); ?></th>
                    <th><?php echo e(__('product.dynamic_input_type')); ?></th>
                    <th><?php echo e(__('product.category')); ?></th>
                    <th><?php echo e(__('product.col_created_by')); ?></th>
                    <th class="text-center"><?php echo e(__('app.status')); ?></th>
                    <th class="text-center"><?php echo e(__('product.dynamic_required')); ?></th>
                    <th class="text-end"><?php echo e(__('app.actions')); ?></th>
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
        if (!window.jQuery || !$('#dynamicFieldsTable').length) {
            return;
        }

        $('#dynamicFieldsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo e(route("product.dynamic-fields.index")); ?>'
            },
            columns: [
                { data: 'label', name: 'label' },
                { data: 'field_key', name: 'field_key' },
                { data: 'input_type', name: 'input_type' },
                { data: 'category_name', name: 'category.name', orderable: false, searchable: false },
                { data: 'creator_name', name: 'creator.name', orderable: false, searchable: false },
                { data: 'is_active', name: 'is_active', className: 'text-center', searchable: false },
                { data: 'is_required', name: 'is_required', className: 'text-center', searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            pageLength: 20,
            order: [[0, 'asc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: 'Search attributes...',
            },
            dom: 'ftip',
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Product\resources/views/dynamic-fields/index.blade.php ENDPATH**/ ?>