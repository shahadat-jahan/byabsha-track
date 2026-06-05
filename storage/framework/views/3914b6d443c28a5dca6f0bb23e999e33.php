<?php $__env->startSection('title', __('brand::brand.create_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="page-title"><?php echo e(__('brand::brand.create_title')); ?></h1>
</div>

<div class="content-card">
    <form action="<?php echo e(route('brand.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label for="name" class="form-label"><?php echo e(__('brand::brand.name')); ?> <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="name"
                name="name"
                value="<?php echo e(old('name')); ?>"
                placeholder="<?php echo e(__('brand::brand.name_placeholder')); ?>"
                required
            >
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

        <div class="d-flex justify-content-between">
            <a href="<?php echo e(route('brand.index')); ?>" class="btn btn-secondary"><?php echo e(__('app.cancel')); ?></a>
            <button type="submit" class="btn btn-primary"><?php echo e(__('brand::brand.save_btn')); ?></button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Brand\resources/views/create.blade.php ENDPATH**/ ?>