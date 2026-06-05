<?php $__env->startSection('title', __('restock.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --restock-ink-900: #0f172a;
        --restock-ink-700: #334155;
        --restock-ink-500: #64748b;
        --restock-brand: #0f766e;
        --restock-brand-deep: #155e75;
        --restock-line: #d8e4ee;
    }

    .restock-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--restock-ink-900);
    }

    .restock-shell::before {
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

    .restock-header {
        gap: 0.9rem;
    }

    .restock-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--restock-brand);
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
        color: var(--restock-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--restock-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-new-restock {
        background: linear-gradient(140deg, var(--restock-brand), var(--restock-brand-deep));
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
        white-space: nowrap;
    }

    .btn-new-restock:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--restock-line);
        border-radius: 16px;
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
        color: var(--restock-ink-900);
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

    /* Single-row Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--restock-line);
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
        color: var(--restock-ink-500);
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
        color: var(--restock-ink-900) !important;
    }

    .filter-search-control:focus {
        background-color: #ffffff !important;
        border-color: var(--restock-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .filter-select-control {
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        height: 38px !important;
        color: var(--restock-ink-900) !important;
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
    }

    .filter-select-control:focus {
        background-color: #ffffff !important;
        border-color: var(--restock-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .btn-filter-submit {
        background: var(--restock-brand);
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.5rem 1.25rem;
        border: 1px solid var(--restock-brand);
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-filter-submit:hover {
        background: var(--restock-brand-deep);
        border-color: var(--restock-brand-deep);
        color: #ffffff;
    }

    .restock-table {
        margin-bottom: 0;
    }

    .restock-table thead th {
        background: #f8fafc !important;
        border-bottom: 1px solid var(--restock-line) !important;
        color: var(--restock-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 0.95rem;
        white-space: nowrap;
    }

    .restock-table tbody td,
    .restock-table tfoot td {
        border-color: #e7edf4;
        padding: 0.5rem 0.95rem !important;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .restock-table tbody tr:hover {
        background: #fbfdff;
    }

    .shop-pill,
    .qty-pill,
    .stock-pill {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .shop-pill {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .qty-pill {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .stock-pill-ok {
        background: rgba(14, 165, 233, 0.14);
        color: #0369a1;
        border: 1px solid rgba(14, 165, 233, 0.24);
    }

    .stock-pill-out {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .btn-row-action {
        border-radius: 10px;
        padding: 0.34rem 0.52rem;
    }

    .btn-row-edit {
        color: #475569;
        border-color: #cdd9e6;
        background: #fff;
    }

    .btn-row-edit:hover {
        background: #64748b;
        border-color: #64748b;
        color: #fff;
    }

    .btn-row-delete {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
        background: #fff;
    }

    .btn-row-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    .restock-table tfoot {
        background: #f9fbff;
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

    .empty-state h3 {
        font-size: 1.15rem;
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        color: var(--restock-ink-500);
        margin-bottom: 0.95rem;
    }

    .btn-empty-restock {
        background: linear-gradient(140deg, var(--restock-brand), var(--restock-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .btn-empty-restock:hover {
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .restock-header {
            align-items: stretch !important;
        }

        .btn-new-restock,
        .btn-apply-filter {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="restock-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap restock-header">
    <div>
        <span class="restock-kicker"><i class="bi bi-arrow-repeat"></i><?php echo e(__('restock.title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('restock.title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('restock.subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('restock.create')); ?>" class="btn-new-restock">
        <i class="bi bi-plus-circle"></i> <?php echo e(__('restock.create_title')); ?>

    </a>
</div>

    
    <div class="filter-toolbar mb-4 shadow-sm">
        <form id="restocksFilterForm" class="m-0 w-100">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            type="text"
                            id="search"
                            class="form-control filter-search-control"
                            placeholder="Search product, batch, note...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-select-control" id="filter_shop_id" name="shop_id">
                        <option value=""><?php echo e(__('restock.all_shops')); ?></option>
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop->id); ?>" <?php echo e(($filters['shop_id'] ?? '') == $shop->id ? 'selected' : ''); ?>>
                                <?php echo e($shop->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control filter-select-control" id="date_from" name="date_from"
                           value="<?php echo e($filters['date_from'] ?? ''); ?>" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control filter-select-control" id="date_to" name="date_to"
                           value="<?php echo e($filters['date_to'] ?? ''); ?>" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter-submit w-100">
                        <i class="bi bi-funnel"></i> <?php echo e(__('restock.apply_filters')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Restocks Table -->
    <div class="content-card p-3">
    <div class="table-responsive">
        <table id="restocksTable" class="table table-hover align-middle mb-0 restock-table w-100">
            <thead>
                <tr>
                    <th><?php echo e(__('restock.col_date')); ?></th>
                    <th><?php echo e(__('restock.col_shop')); ?></th>
                    <th><?php echo e(__('restock.col_product')); ?></th>
                    <th><?php echo e(__('restock.batch_code')); ?></th>
                    <th><?php echo e(__('restock.table_attributes')); ?></th>
                    <th class="text-center"><?php echo e(__('restock.col_quantity')); ?></th>
                    <th class="text-end"><?php echo e(__('restock.col_price_per_unit')); ?></th>
                    <th class="text-end"><?php echo e(__('restock.col_total_cost')); ?></th>
                    <th class="text-center"><?php echo e(__('restock.col_current_stock')); ?></th>
                    <th><?php echo e(__('restock.col_note')); ?></th>
                    <th class="text-end"><?php echo e(__('app.actions')); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="table-light fw-semibold">
                <tr>
                    <td colspan="5" class="text-muted small">
                        <?php echo e(__('restock.page_totals')); ?> (0 <?php echo e(__('restock.records')); ?>)
                    </td>
                    <td class="text-center">
                        <span class="qty-pill">+0</span>
                    </td>
                    <td></td>
                    <td class="text-end">0.00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
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
$(document).ready(function () {
    const tableUrl = <?php echo json_encode(route('restock.table'), 15, 512) ?>;
    
    const table = $('#restocksTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        dom: 'rtip',
        order: [[0, 'desc']],
        ajax: {
            url: tableUrl,
            data: function (d) {
                d.shop_id = $('#filter_shop_id').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
                d.search = { value: $('#search').val() };
            }
        },
        columns: [
            { data: 'restock_date', name: 'restocks.restock_date' },
            { data: 'shop_name_label', name: 'shops.name' },
            { data: 'product_name', name: 'products.name' },
            { data: 'batch_label', name: 'product_batches.batch_code' },
            { data: 'attribute_summary', name: 'attribute_summary', orderable: false, searchable: false },
            { data: 'quantity', name: 'restocks.quantity', className: 'text-center' },
            { data: 'purchase_price_per_unit', name: 'restocks.purchase_price_per_unit', className: 'text-end' },
            { data: 'total_cost', name: 'restocks.total_cost', className: 'text-end' },
            { data: 'current_stock_label', name: 'products.stock_quantity', className: 'text-center' },
            { data: 'note', name: 'restocks.note' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ],
        footerCallback: function (row, data, start, end, display) {
            const api = this.api();
            
            const intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[^\d\.-]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            
            const pageTotalQty = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    const valA = typeof a === 'string' ? a.replace(/<[^>]*>/g, '') : a;
                    const valB = typeof b === 'string' ? b.replace(/<[^>]*>/g, '') : b;
                    return intVal(valA) + intVal(valB);
                }, 0);
            
            const pageTotalCost = api
                .column(7, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    const valA = typeof a === 'string' ? a.replace(/<[^>]*>/g, '') : a;
                    const valB = typeof b === 'string' ? b.replace(/<[^>]*>/g, '') : b;
                    return intVal(valA) + intVal(valB);
                }, 0);
            
            $(api.column(5).footer()).html('<span class="qty-pill">+' + pageTotalQty.toLocaleString() + '</span>');
            $(api.column(7).footer()).html('<strong>' + pageTotalCost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>');
            
            const recordsCount = api.rows({ page: 'current' }).count();
            $(api.column(0).footer()).html("<?php echo e(__('restock.page_totals')); ?> (" + recordsCount + " <?php echo e(__('restock.records')); ?>)");
        }
    });

    $('#restocksFilterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Restock\resources/views/index.blade.php ENDPATH**/ ?>