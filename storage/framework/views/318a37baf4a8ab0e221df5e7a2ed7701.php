<?php $__env->startSection('title', __('product.dynamic_create_title')); ?>

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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="product-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="product-kicker"><i class="bi bi-plus-circle"></i><?php echo e(__('product.dynamic_create_title')); ?></span>
        <h1 class="page-title display-font"><?php echo e(__('product.dynamic_create_title')); ?></h1>
        <p class="page-subtitle"><?php echo e(__('product.dynamic_create_subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('product.dynamic-fields.index')); ?>" class="btn-soft-secondary">
        <i class="bi bi-arrow-left"></i> <?php echo e(__('product.back_to_list')); ?>

    </a>
</div>

<div class="content-card p-4">
        <?php echo $__env->make('product::dynamic-fields.form', [
            'action' => route('product.dynamic-fields.store'),
            'method' => 'POST',
            'field' => null,
            'showAdvancedFields' => false,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Product\resources/views/dynamic-fields/create.blade.php ENDPATH**/ ?>