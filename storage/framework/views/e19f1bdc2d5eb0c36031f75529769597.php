
<?php $__env->startSection('title', 'Manage Subscription Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;">
                <i class="bi bi-grid-3x3-gap me-2" style="color: #0f766e;"></i>Subscription Plans
            </h2>
            <p style="color: #64748b; font-size: 0.95rem;">Configure pricing tiers, duration, and active modules for shop owners</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-wallet2 me-1"></i>Payment Requests
            </a>
            <a href="<?php echo e(route('admin.subscriptions.plans.create')); ?>" class="btn btn-primary rounded-pill px-3" style="background-color: #0f766e; border-color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-plus-lg me-1"></i>Create New Plan
            </a>
        </div>
    </div>



    <?php if($plans->isEmpty()): ?>
        <div class="card border-0 shadow-sm text-center p-5 rounded-4" style="border: 1px solid #d8e4ee !important;">
            <div class="my-4">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="fw-bold text-slate-900 mb-1">No subscription plans found</h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">Start by creating your first subscription plan to offer to shop owners.</p>
            <a href="<?php echo e(route('admin.subscriptions.plans.create')); ?>" class="btn btn-primary rounded-pill px-4" style="background-color: #0f766e; border-color: #0f766e;">
                <i class="bi bi-plus-lg me-1"></i>Create Plan
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #fff; border: 1px solid #d8e4ee !important;">
                        <div class="p-4 text-white" style="background: linear-gradient(135deg, #0f766e 0%, #155e75 100%);">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <h4 class="fw-bold mb-0" style="font-family: 'Space Grotesk', sans-serif;"><?php echo e($plan->name); ?></h4>
                                    <small class="text-white-50 font-monospace mt-1 d-block" style="font-size: 0.72rem;"><?php echo e($plan->slug); ?></small>
                                </div>
                                <span class="badge rounded-pill text-uppercase px-2.5 py-1" style="font-size: 0.65rem; font-weight: 700; background: <?php echo e($plan->status === 'active' ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.1)'); ?>; border: 1px solid rgba(255,255,255,0.25);">
                                    <?php echo e($plan->status ?? 'active'); ?>

                                </span>
                            </div>
                            <div class="mt-4">
                                <span class="fs-2 fw-black" style="font-family: 'Space Grotesk', sans-serif;">
                                    <?php echo e($plan->price > 0 ? currency_symbol() . number_format($plan->price, 0) : 'Free'); ?>

                                </span>
                                <?php if($plan->price > 0): ?>
                                    <span class="text-white-50 small">/ <?php echo e($plan->duration_days); ?> Days</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <?php if($plan->description): ?>
                                <p class="text-muted small mb-4" style="line-height: 1.6;"><?php echo e($plan->description); ?></p>
                            <?php else: ?>
                                <p class="text-muted small italic mb-4">No description provided for this plan.</p>
                            <?php endif; ?>

                            <hr style="border-color: #e2e8f0; margin: 1rem 0;">

                            <div class="space-y-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center small mb-2">
                                    <span class="text-muted"><i class="bi bi-clock me-1.5"></i>Duration</span>
                                    <span class="fw-bold text-dark"><?php echo e($plan->duration_days); ?> Days</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small mb-2">
                                    <span class="text-muted"><i class="bi <?php echo e($plan->getMeta('show_in_owner_panel') !== false ? 'bi-eye' : 'bi-eye-slash'); ?> me-1.5"></i>Visibility</span>
                                    <span class="fw-bold <?php echo e($plan->getMeta('show_in_owner_panel') !== false ? 'text-teal-600' : 'text-secondary'); ?>">
                                        <?php echo e($plan->getMeta('show_in_owner_panel') !== false ? 'Visible to owners' : 'Hidden from owners'); ?>

                                    </span>
                                </div>
                            </div>

                            <hr style="border-color: #e2e8f0; margin: 1rem 0;">

                            <div class="mb-4 flex-grow-1">
                                <h6 class="text-uppercase fw-bold text-muted small tracking-wider mb-3" style="font-size: 0.72rem;">Subscribed Modules</h6>
                                <?php
                                    $featuresArray = (array) ($plan->features ?? []);
                                    $activeFeatures = array_keys(array_filter($featuresArray));
                                ?>
                                <?php if(count($activeFeatures) > 0): ?>
                                    <div class="d-flex flex-wrap gap-1.5" style="gap: 5px;">
                                        <?php $__currentLoopData = $activeFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge text-uppercase" style="background: rgba(15, 118, 110, 0.08); color: #0f766e; border: 1px solid rgba(15, 118, 110, 0.15); font-size: 0.68rem; font-weight: 700; padding: 0.35em 0.65em;">
                                                <?php echo e($feat); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small italic">No modules enabled.</span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2 pt-3 border-top mt-auto" style="border-color: #f1f5f9;">
                                <a href="<?php echo e(route('admin.subscriptions.plans.edit', $plan->id)); ?>" 
                                   class="btn btn-outline-teal flex-grow-1 fw-bold" style="font-size: 0.8rem;">
                                    <i class="bi bi-pencil me-1"></i>Edit Plan
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.subscriptions.plans.destroy', $plan->id)); ?>" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this plan? This action is permanent.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger" title="Delete Plan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<style>
.btn-outline-teal {
    border-color: #0f766e;
    color: #0f766e;
    border-radius: 999px;
}
.btn-outline-teal:hover {
    background-color: #0f766e;
    color: #fff;
}
.btn-outline-danger {
    border-radius: 999px;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Subscription\resources/views/admin/plans/index.blade.php ENDPATH**/ ?>