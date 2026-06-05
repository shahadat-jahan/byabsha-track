<?php $__env->startSection('title', __('brand::brand.show_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h1 class="page-title"><?php echo e(__('brand::brand.show_title')); ?></h1>
    <a href="<?php echo e(route('brand.index')); ?>" class="btn btn-secondary"><?php echo e(__('brand::brand.back')); ?></a>
</div>

<div class="content-card">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <th style="width: 220px;"><?php echo e(__('brand::brand.name')); ?></th>
                <td><?php echo e($brand->name); ?></td>
            </tr>
            <tr>
                <th><?php echo e(__('brand::brand.product_count')); ?></th>
                <td><?php echo e($brand->products_count); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex gap-2 mt-3">
        <a href="<?php echo e(route('brand.edit', $brand->id)); ?>" class="btn btn-warning"><?php echo e(__('app.edit')); ?></a>
        <a href="<?php echo e(route('brand.index')); ?>" class="btn btn-secondary"><?php echo e(__('brand::brand.back')); ?></a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Brand\resources/views/show.blade.php ENDPATH**/ ?>