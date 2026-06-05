
<?php $__env->startSection('title', 'Create Subscription Plan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        <a href="<?php echo e(route('admin.subscriptions.plans.index')); ?>" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
            <i class="bi bi-arrow-left me-1"></i>Back to Plans
        </a>
        <div>
            <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;">
                Create Subscription Plan
            </h2>
            <p style="color: #64748b; font-size: 0.95rem;">Define a new subscription tier with features, limits, and pricing details</p>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: #fff5f5; border: 1px solid #fecaca; color: #991b1b; border-radius: 14px;">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following validation errors:
            </div>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.subscriptions.plans.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Basic Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Business Pro, Starter Pack" value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text text-muted small mt-1.5">The public display name for the plan.</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-tag text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Pricing & Duration</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Price (<?php echo e(currency_symbol()); ?>) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><?php echo e(currency_symbol()); ?></span>
                                    <input type="number" name="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="0.00" step="0.01" value="<?php echo e(old('price', 0)); ?>" required>
                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-text text-muted small mt-1.5">Set to 0 for a free subscription tier.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" class="form-control rounded-3 <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. 30, 365" value="<?php echo e(old('duration_days', 30)); ?>" min="1" required>
                                <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text text-muted small mt-1.5">How long the subscription stays active before expiring.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-ui-checks text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Assign Modules / Features</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Select which application modules are unlocked for owners on this plan.</p>
                        
                        <?php
                            $modules = \App\Models\User::availableModuleAccessKeys();
                        ?>

                        <div class="row g-3">
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 border d-flex gap-3 align-items-start" style="background: #fbfbfc; border-color: #e2e8f0 !important;">
                                        <input class="form-check-input mt-1" type="checkbox" name="features[<?php echo e($module); ?>]" id="feat-<?php echo e($module); ?>" value="1" <?php echo e(is_array(old('features')) && isset(old('features')[$module]) ? 'checked' : ''); ?>>
                                        <div>
                                            <label class="form-check-label fw-bold text-dark text-capitalize mb-0" for="feat-<?php echo e($module); ?>">
                                                <?php echo e(str_replace('_', ' ', $module)); ?>

                                            </label>
                                            <span class="d-block text-muted uppercase font-monospace" style="font-size: 0.65rem; font-weight: 600;"><?php echo e($module); ?> module</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-left-text text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Description</h6>
                    </div>
                    <div class="card-body p-4">
                        <textarea name="description" rows="3" class="form-control rounded-3 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Describe this plan... (benefits, target businesses)"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text text-muted small mt-1.5">Displayed to owners when choosing subscriptions.</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-code-slash text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Metadata (Optional)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div>
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Metadata (JSON Format)</label>
                            <textarea name="meta" rows="3" class="form-control rounded-3 font-monospace" placeholder='{"badge_label": "Popular", "button_text": "Choose Basic"}'><?php echo e(old('meta')); ?></textarea>
                            <?php $__errorArgs = ['meta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text text-muted small mt-1.5">Custom configurations for display (e.g. ribbons, badges).</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-toggle-on text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Status & Visibility</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Plan Status</label>
                            <select name="status" class="form-select rounded-3">
                                <option value="active" <?php echo e(old('status', 'active') === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e(old('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                            <div class="form-text text-muted small mt-1.5">Inactive plans cannot be purchased by owners.</div>
                        </div>

                        <hr style="border-color: #e2e8f0; margin: 1.5rem 0;">

                        <div class="form-check d-flex gap-2">
                            <input class="form-check-input mt-1" type="checkbox" name="show_in_owner_panel" id="show_in_owner_panel" value="1" <?php echo e(old('show_in_owner_panel', '1') == '1' ? 'checked' : ''); ?>>
                            <div>
                                <label class="form-check-label fw-bold text-dark" for="show_in_owner_panel">
                                    Show in Owner Panel
                                </label>
                                <span class="d-block text-muted small mt-1 leading-relaxed">If unchecked, this plan will be hidden from the owner's billing center page.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4" style="border: 1px solid #d8e4ee !important;">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold mb-2" style="background-color: #0f766e; border-color: #0f766e;">
                        <i class="bi bi-check-lg me-1"></i>Create Plan
                    </button>
                    <a href="<?php echo e(route('admin.subscriptions.plans.index')); ?>" class="btn btn-outline-secondary w-100 rounded-pill py-2.5 fw-bold">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Subscription\resources/views/admin/plans/create.blade.php ENDPATH**/ ?>