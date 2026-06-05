

<?php $__env->startSection('title', __('user.edit_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --user-edit-ink-900: #0f172a;
        --user-edit-ink-700: #334155;
        --user-edit-ink-500: #64748b;
        --user-edit-brand: #0f766e;
        --user-edit-brand-deep: #155e75;
        --user-edit-line: #d8e4ee;
    }

    .user-edit-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--user-edit-ink-900);
    }

    .user-edit-shell::before {
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

    .form-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--user-edit-brand);
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
        color: var(--user-edit-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--user-edit-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--user-edit-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        padding: 1.5rem;
    }

    .alert-danger {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 14px;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .form-control,
    .form-select {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--user-edit-ink-900);
        font-size: 0.94rem;
        padding: 0.62rem 0.78rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .helper-text {
        font-size: 0.82rem;
        color: var(--user-edit-ink-500);
    }

    .btn-cancel {
        border-radius: 999px;
        padding: 0.62rem 1.15rem;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .btn-submit {
        background: linear-gradient(140deg, var(--user-edit-brand), var(--user-edit-brand-deep));
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
    }

    .btn-submit:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .module-access-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 0.55rem;
    }

    .module-access-item {
        border: 1px solid #d6e2ee;
        border-radius: 11px;
        background: #fbfdff;
        padding: 0.52rem 0.7rem;
        font-size: 0.86rem;
        color: #334155;
    }

    @media (max-width: 768px) {
        .content-card {
            padding: 1.05rem;
            border-radius: 16px;
        }

        .page-title {
            font-size: 1.55rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="user-edit-shell">
<div class="mb-4">
    <span class="form-kicker"><i class="bi bi-pencil-square"></i><?php echo e(__('user.edit_title')); ?></span>
    <h1 class="page-title display-font"><?php echo e(__('user.edit_title')); ?></h1>
    <p class="page-subtitle"><?php echo e(__('user.edit_subtitle')); ?></p>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><?php echo e(__('app.validation_error')); ?></strong>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="content-card">
    <form action="<?php echo e(route('user.update', $user->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-3">
            <label for="name" class="form-label"><?php echo e(__('user.name')); ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><?php echo e(__('user.email')); ?> <span class="text-danger">*</span></label>
            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label"><?php echo e(__('user.role')); ?> <span class="text-danger">*</span></label>
            <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required onchange="toggleEditManagerFields()">
                <option value=""><?php echo e(__('user.select_role')); ?></option>
                <option value="owner" <?php echo e(old('role', $user->role) === 'owner' ? 'selected' : ''); ?>><?php echo e(__('user.role_owner')); ?></option>
                <option value="manager" <?php echo e(old('role', $user->role) === 'manager' ? 'selected' : ''); ?>><?php echo e(__('user.role_manager')); ?></option>
                <option value="superadmin" <?php echo e(old('role', $user->role) === 'superadmin' ? 'selected' : ''); ?>><?php echo e(__('user.role_superadmin')); ?></option>
            </select>
            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <small class="helper-text"><?php echo e(__('user.role_description')); ?></small>
        </div>

        
        <?php $showManagerFields = in_array(old('role', $user->role), ['manager']); ?>
        <div id="editManagerFields" style="display:<?php echo e($showManagerFields ? 'block' : 'none'); ?>;">
            <div class="mb-3">
                <label for="shop_id" class="form-label"><?php echo e(__('user.assign_shop')); ?></label>
                <select class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_id" name="shop_id" onchange="filterBranchesEdit()">
                    <option value=""><?php echo e(__('user.select_shop')); ?></option>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id', $user->shop_id) == $shop->id ? 'selected' : ''); ?>>
                            <?php echo e($shop->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="branch_id" class="form-label"><?php echo e(__('user.assign_branch')); ?></label>
                <select class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="branch_id" name="branch_id">
                    <option value=""><?php echo e(__('user.select_branch')); ?></option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>"
                                data-shop-id="<?php echo e($branch->shop_id); ?>"
                                <?php echo e(old('branch_id', $user->branch_id) == $branch->id ? 'selected' : ''); ?>>
                            <?php echo e($branch->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="helper-text"><?php echo e(__('user.branch_shop_filter_hint')); ?></small>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label"><?php echo e(__('user.password')); ?></label>
            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="password" name="password">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <small class="helper-text"><?php echo e(__('user.password_hint_edit')); ?></small>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label"><?php echo e(__('user.password_confirmation')); ?></label>
            <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="password_confirmation" name="password_confirmation">
            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <?php
            $selectedModules = old('module_access', $user->getModuleAccessList());
        ?>
        <div class="mb-3">
            <label class="form-label"><?php echo e(__('user.module_access_title')); ?></label>
            <div class="module-access-grid">
                <?php $__currentLoopData = $moduleAccessOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="module-access-item">
                        <input class="form-check-input me-2" type="checkbox" name="module_access[]" value="<?php echo e($key); ?>"
                               <?php echo e(in_array($key, $selectedModules, true) ? 'checked' : ''); ?>>
                        <?php echo e($label); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="helper-text"><?php echo e(__('user.module_access_help')); ?></small>
            <?php $__errorArgs = ['module_access'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['module_access.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?php echo e(route('user.index')); ?>" class="btn btn-secondary btn-cancel"><?php echo e(__('app.cancel')); ?></a>
            <button type="submit" class="btn btn-submit"><i class="bi bi-check-circle"></i><?php echo e(__('app.update')); ?></button>
        </div>
    </form>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleEditManagerFields() {
        var role = document.getElementById('role').value;
        document.getElementById('editManagerFields').style.display = role === 'manager' ? 'block' : 'none';
        if (role === 'manager') {
            filterBranchesEdit();
        }
    }

    function filterBranchesEdit() {
        var shopId = document.getElementById('shop_id').value;
        var branchSelect = document.getElementById('branch_id');
        var options = branchSelect.querySelectorAll('option[data-shop-id]');

        options.forEach(function (opt) {
            if (!shopId || opt.dataset.shopId === shopId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
                if (opt.selected) opt.selected = false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        filterBranchesEdit();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/User\resources/views/edit.blade.php ENDPATH**/ ?>