<?php $__env->startSection('title', __('shop.edit_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .btn-shop-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-shop-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="page-title"><?php echo e(__('shop.edit_title')); ?></h1>
    <p class="page-subtitle"><?php echo e(__('shop.edit_subtitle')); ?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-shop"></i>
                    <?php echo e(__('shop.shop_info')); ?>

                </h5>
            </div>
            <div class="p-4">
                <form action="<?php echo e(route('shop.update', $shop->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <?php if(auth()->user()->isSuperAdmin() && $shopOwners): ?>
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">
                                <?php echo e(__('app.shop_owner')); ?> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="user_id"
                                    name="user_id"
                                    required>
                                <option value=""><?php echo e(__('app.select_owner')); ?></option>
                                <?php $__currentLoopData = $shopOwners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($owner->id); ?>" <?php echo e(old('user_id', $shop->user_id) == $owner->id ? 'selected' : ''); ?>>
                                        <?php echo e($owner->name); ?> (<?php echo e($owner->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['user_id'];
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
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <?php echo e(__('shop.name')); ?> <span class="text-danger">*</span>
                        </label>
                        <input type="text"
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
                               value="<?php echo e(old('name', $shop->name)); ?>"
                               placeholder="<?php echo e(__('shop.name_placeholder')); ?>"
                               required>
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

                    <div class="mb-4">
                        <label for="location" class="form-label fw-semibold">
                            <?php echo e(__('shop.location')); ?>

                        </label>
                        <input type="text"
                               class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="location"
                               name="location"
                               value="<?php echo e(old('location', $shop->location)); ?>"
                               placeholder="<?php echo e(__('shop.location_placeholder')); ?>">
                        <?php $__errorArgs = ['location'];
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

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">
                            <?php echo e(__('shop.address')); ?>

                        </label>
                        <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="address"
                                  name="address"
                                  rows="3"
                                  placeholder="<?php echo e(__('shop.address_placeholder')); ?>"><?php echo e(old('address', $shop->address)); ?></textarea>
                        <?php $__errorArgs = ['address'];
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

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="<?php echo e(route('shop.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> <?php echo e(__('shop.back_to_list')); ?>

                        </a>
                        <button type="submit" class="btn btn-shop-theme">
                            <i class="bi bi-check-circle"></i> <?php echo e(__('shop.update_btn')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Shop\resources/views/edit.blade.php ENDPATH**/ ?>