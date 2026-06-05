<?php $__env->startSection('title', __('branch::branch.create_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
        --branch-ink-900: #0f172a;
        --branch-ink-700: #334155;
        --branch-ink-500: #64748b;
    }

    .branch-form-shell {
        position: relative;
    }

    .branch-form-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.16), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.12), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .branch-form-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--branch-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .branch-form-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .branch-form-subtitle {
        color: var(--branch-ink-500);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--branch-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 0.95rem 1.2rem;
        border-bottom: 1px solid #dce8f3;
        background: #f7fbff;
    }

    .content-card-title {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 700;
        color: #36506b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .content-card-title i {
        color: var(--branch-brand);
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
        color: #0f172a;
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

    .btn-back {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .btn-submit {
        background: linear-gradient(140deg, #16a34a, #15803d);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.6rem 1.15rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-submit:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(21, 128, 61, 0.34);
    }

    .tip-item {
        display: flex;
        gap: 0.65rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #edf3f8;
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.5;
    }

    .tip-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .tip-item i {
        color: var(--branch-brand);
        font-size: 1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    @media (max-width: 991.98px) {
        .tips-col {
            margin-top: 0;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="branch-form-shell">
    <div class="mb-4">
        <span class="branch-form-kicker"><i class="bi bi-plus-circle"></i><?php echo e(__('branch::branch.create_title')); ?></span>
        <h1 class="branch-form-title fw-bold"><?php echo e(__('branch::branch.create_title')); ?></h1>
        <p class="branch-form-subtitle"><?php echo e(__('branch::branch.subtitle')); ?></p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-diagram-3"></i><?php echo e(__('branch::branch.create_title')); ?>

                    </h5>
                </div>
                <div class="p-4">
                    <form action="<?php echo e(route('branch.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label for="shop_id" class="form-label">
                                <?php echo e(__('branch::branch.shop')); ?> <span class="text-danger">*</span>
                            </label>
                            <select name="shop_id" id="shop_id" class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('branch::branch.shop_placeholder')); ?></option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id', $selectedShopId) == $shop->id ? 'selected' : ''); ?>>
                                        <?php echo e($shop->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">
                                <?php echo e(__('branch::branch.location')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="location" name="location" value="<?php echo e(old('location')); ?>" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('branch::branch.location_placeholder')); ?>" required>
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label"><?php echo e(__('branch::branch.phone')); ?></label>
                            <input type="text" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" placeholder="<?php echo e(__('branch::branch.phone_placeholder')); ?>">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label"><?php echo e(__('branch::branch.email')); ?></label>
                            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('branch::branch.email_placeholder')); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>



                        <div class="d-flex justify-content-between flex-wrap gap-2 pt-3 border-top">
                            <a href="<?php echo e(route('branch.index')); ?>" class="btn-back">
                                <i class="bi bi-arrow-left"></i> <?php echo e(__('branch::branch.back')); ?>

                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle"></i> <?php echo e(__('branch::branch.save_btn')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 tips-col">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title">
                        <i class="bi bi-lightbulb"></i>Tips
                    </h5>
                </div>
                <div class="p-4">
                    <div class="tip-item">
                        <i class="bi bi-diagram-3"></i>
                        <span>Each branch belongs to one shop and can have its own location, phone, and email.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Branch names must be unique within each shop.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-telephone"></i>
                        <span>Phone and email help customers reach a specific branch directly.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-toggle-on"></i>
                        <span>Inactive branches are hidden from sales while preserving historical data.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Branch\resources/views/create.blade.php ENDPATH**/ ?>