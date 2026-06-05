<?php $__env->startSection('title', __('user.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --user-ink-900: #0f172a;
        --user-ink-700: #334155;
        --user-ink-500: #64748b;
        --user-brand: #0f766e;
        --user-brand-deep: #155e75;
        --user-line: #d8e4ee;
    }

    .user-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--user-ink-900);
    }

    .user-shell::before {
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

    .user-header {
        gap: 0.9rem;
    }

    .user-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--user-brand);
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
        color: var(--user-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--user-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-user {
        background: linear-gradient(140deg, var(--user-brand), var(--user-brand-deep));
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

    .btn-add-user:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--user-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .alert-success {
        border: 1px solid #b9ebd6;
        background: #f1fff8;
        color: #065f46;
        border-radius: 14px;
    }

    .alert-danger {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 14px;
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
        padding: 0.55rem 0.95rem !important;
        vertical-align: middle;
    }

    .table.table-custom tbody tr:hover {
        background: #fbfdff;
    }

    .user-name {
        color: #1e293b;
        font-weight: 700;
    }

    .you-chip {
        background: rgba(59, 130, 246, 0.14);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #1d4ed8;
        border-radius: 999px;
        padding: 0.2rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.36rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .badge-superadmin {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.26);
    }

    .badge-user {
        background: rgba(59, 130, 246, 0.12);
        color: #1d4ed8;
        border-color: rgba(59, 130, 246, 0.28);
    }

    .badge-owner {
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.24);
    }

    .badge-manager {
        background: rgba(217, 119, 6, 0.12);
        color: #d97706;
        border-color: rgba(217, 119, 6, 0.26);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.36rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.14);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.3);
    }

    .status-deactive {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.28);
    }

    .actions-cell {
        white-space: nowrap;
    }

    .btn-row-action {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .btn-row-view {
        color: #0369a1;
        border-color: rgba(14, 116, 144, 0.28);
    }

    .btn-row-view:hover {
        color: #fff;
        background: #0284c7;
        border-color: #0284c7;
    }

    .btn-row-edit {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.42);
    }

    .btn-row-edit:hover {
        color: #fff;
        background: #f59e0b;
        border-color: #f59e0b;
    }

    .btn-row-delete {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.34);
    }

    .btn-row-delete:hover {
        color: #fff;
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-row-restore {
        color: #047857;
        border-color: rgba(16, 185, 129, 0.35);
    }

    .btn-row-restore:hover {
        color: #fff;
        background: #10b981;
        border-color: #10b981;
    }

    /* DataTable Pagination Overrides */
    .dataTables_wrapper .paginate_button.page-item.active .page-link {
        background-color: var(--user-brand) !important;
        border-color: var(--user-brand) !important;
        color: #fff !important;
    }
    .dataTables_wrapper .paginate_button.page-item .page-link {
        color: var(--user-ink-700);
        border-radius: 8px;
        margin: 0 2px;
        font-size: 0.82rem;
    }
    .dataTables_wrapper .paginate_button.page-item .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: var(--user-ink-900);
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 0.8rem;
        color: var(--user-ink-500);
        margin-top: 1rem;
        font-weight: 500;
    }

    .dropdown-toggle.no-caret::after {
        display: none !important;
    }

    @media (max-width: 767.98px) {
        .user-header {
            align-items: stretch !important;
        }

        .btn-add-user {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="user-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap user-header">
    <div>
        <span class="user-kicker"><i class="bi bi-people"></i><?php echo e(__('user.title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('user.title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('user.subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('user.create')); ?>" class="btn-add-user">
        <i class="bi bi-plus-circle"></i> <?php echo e(__('user.add_new')); ?>

    </a>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($error); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<div class="card border-0 rounded-4 shadow-sm mb-4" style="background: rgba(255,255,255,0.7); border: 1px solid var(--user-line) !important; backdrop-filter: blur(8px);">
    <div class="card-body p-3">
        <form id="filterForm" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 1rem;"></i>
                    <input type="text" id="custom_search" class="form-control form-control-sm border-0 bg-white" placeholder="Search by name or email..." style="border-radius: 12px; padding: 0.65rem 1rem 0.65rem 2.5rem; font-size: 0.88rem; border: 1px solid #e2edf6 !important; box-shadow: 0 4px 10px rgba(15,23,42,0.02);">
                </div>
            </div>
            
            <div class="col-12 col-md-3">
                <div class="position-relative">
                    <i class="bi bi-person-badge position-absolute top-50 translate-middle-y text-muted" style="left: 1rem;"></i>
                    <select id="filter_role" class="form-select form-select-sm border-0 bg-white" style="border-radius: 12px; padding: 0.65rem 1rem 0.65rem 2.5rem; font-size: 0.88rem; border: 1px solid #e2edf6 !important;">
                        <option value="">All Roles</option>
                        <option value="superadmin"><?php echo e(__('user.role_superadmin')); ?></option>
                        <option value="owner"><?php echo e(__('user.role_owner')); ?></option>
                        <option value="manager"><?php echo e(__('user.role_manager')); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="position-relative">
                    <i class="bi bi-activity position-absolute top-50 translate-middle-y text-muted" style="left: 1rem;"></i>
                    <select id="filter_status" class="form-select form-select-sm border-0 bg-white" style="border-radius: 12px; padding: 0.65rem 1rem 0.65rem 2.5rem; font-size: 0.88rem; border: 1px solid #e2edf6 !important;">
                        <option value="">All Statuses</option>
                        <option value="active"><?php echo e(__('user.active')); ?></option>
                        <option value="pending"><?php echo e(__('user.pending_approval')); ?></option>
                        <option value="deactive"><?php echo e(__('user.deactive')); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-2 text-md-end text-stretch">
                <button type="submit" class="btn btn-sm w-100" style="background: var(--user-brand); color: #fff; border-radius: 12px; padding: 0.65rem 1rem; font-size: 0.86rem; font-weight: 700; border: 0; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);">
                    <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

<div class="content-card p-3">
    <div class="table-responsive">
        <table class="table table-custom w-100" id="usersDataTable">
            <thead>
                <tr>
                    <th><?php echo e(__('user.col_name')); ?></th>
                    <th><?php echo e(__('user.col_email')); ?></th>
                    <th><?php echo e(__('user.col_role')); ?></th>
                    <th><?php echo e(__('user.assigned_shop')); ?></th>
                    <th><?php echo e(__('user.col_created')); ?></th>
                    <th><?php echo e(__('user.col_status')); ?></th>
                    <th class="text-end" style="min-width:100px;"><?php echo e(__('app.actions')); ?></th>
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
        if (!window.jQuery || !$('#usersDataTable').length) {
            return;
        }

        var table = $('#usersDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo e(route("user.table")); ?>',
                data: function (d) {
                    d.role = $('#filter_role').val();
                    d.status = $('#filter_status').val();
                    d.custom_search = $('#custom_search').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'shop_branch', name: 'shop_branch', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end actions-cell' }
            ],
            pageLength: 15,
            order: [[4, 'desc']],
            responsive: true,
            language: {
                search: '',
                searchPlaceholder: 'Search users...',
            },
            dom: 'rtip',
        });

        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
        
        $('#filter_role, #filter_status').on('change', function () {
            table.ajax.reload();
        });

        // Trigger reload when search is cleared manually or typed
        var searchTimeout = null;
        $('#custom_search').on('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                table.ajax.reload();
            }, 300);
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/User\resources/views/index.blade.php ENDPATH**/ ?>