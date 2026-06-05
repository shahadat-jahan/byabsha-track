<?php $__env->startSection('title', __('product.edit_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --product-form-ink-900: #0f172a;
        --product-form-ink-700: #334155;
        --product-form-ink-500: #64748b;
        --product-form-brand: #0f766e;
        --product-form-brand-deep: #155e75;
        --product-form-line: #d8e4ee;
    }

    .product-form-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--product-form-ink-900);
    }

    .product-form-shell::before {
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
        color: var(--product-form-brand);
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
        color: var(--product-form-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--product-form-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--product-form-line);
        border-radius: 20px;
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
        font-size: 0.96rem;
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
    .form-select,
    .input-group-text {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--product-form-ink-900);
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .input-group-text {
        color: #5f7287;
        font-weight: 700;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .form-text {
        color: var(--product-form-ink-500);
        font-size: 0.78rem;
    }

    .btn-back {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
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

    .dynamic-fields-wrap {
        border: 1px dashed #bfd6e8;
        border-radius: 14px;
        padding: 1rem;
        background: #f9fcff;
    }

    .dynamic-fields-empty {
        margin: 0;
        color: #64748b;
        font-size: 0.86rem;
    }

    @media (max-width: 991.98px) {
        .product-form-shell .col-md-8 {
            width: 100%;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="product-form-shell">
<div class="mb-4">
    <span class="form-kicker"><i class="bi bi-pencil-square"></i><?php echo e(__('product.edit_title')); ?></span>
    <h1 class="page-title display-font"><?php echo e(__('product.edit_title')); ?></h1>
    <p class="page-subtitle"><?php echo e(__('product.edit_subtitle')); ?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    <?php echo e(__('product.product_info_card')); ?>

                </h5>
            </div>
            <div class="p-4">
                <form action="<?php echo e(route('product.update', $product->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="shop_id" class="form-label fw-semibold">
                            <?php echo e(__('product.shop')); ?> <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="shop_id"
                                name="shop_id"
                                required>
                            <option value=""><?php echo e(__('product.select_shop')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id', $product->shop_id) == $shop->id ? 'selected' : ''); ?>>
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
                        <label for="name" class="form-label fw-semibold">
                            <?php echo e(__('product.name')); ?> <span class="text-danger">*</span>
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
                               value="<?php echo e(old('name', $product->name)); ?>"
                               placeholder="<?php echo e(__('product.enter_product_name')); ?>"
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

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category_id" class="form-label fw-semibold"><?php echo e(__('product.category')); ?></label>
                            <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="category_id"
                                    name="category_id">
                                <option value=""><?php echo e(__('product.select_category')); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
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

                        <div class="col-md-4 mb-3">
                            <label for="model_name" class="form-label fw-semibold"><?php echo e(__('product.model_name')); ?></label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['model_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="model_name"
                                   name="model_name"
                                   value="<?php echo e(old('model_name', $product->model_name)); ?>"
                                   placeholder="<?php echo e(__('product.enter_model_name')); ?>">
                            <?php $__errorArgs = ['model_name'];
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

                        <div class="col-md-4 mb-3">
                            <label for="brand" class="form-label fw-semibold"><?php echo e(__('product.brand')); ?></label>
                            <select class="form-select <?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="brand"
                                    name="brand">
                                <option value=""><?php echo e(__('product.select_brand') ?? 'Select a brand'); ?></option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->name); ?>" <?php echo e(old('brand', $product->brand) == $brand->name ? 'selected' : ''); ?>>
                                        <?php echo e($brand->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['brand'];
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
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label fw-semibold">
                                <?php echo e(__('product.purchase_price')); ?> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><?php echo e(currency_symbol()); ?></span>
                                <input type="number"
                                       class="form-control <?php $__errorArgs = ['purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="purchase_price"
                                       name="purchase_price"
                                       value="<?php echo e(old('purchase_price', $product->purchase_price)); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                                <?php $__errorArgs = ['purchase_price'];
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
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label fw-semibold">
                                <?php echo e(__('product.sale_price')); ?> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><?php echo e(currency_symbol()); ?></span>
                                <input type="number"
                                       class="form-control <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="sale_price"
                                       name="sale_price"
                                       value="<?php echo e(old('sale_price', $product->sale_price)); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                                <?php $__errorArgs = ['sale_price'];
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label fw-semibold">
                                <?php echo e(__('product.stock')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="stock_quantity"
                                   name="stock_quantity"
                                   value="<?php echo e(old('stock_quantity', $product->stock_quantity)); ?>"
                                   min="0"
                                   placeholder="0"
                                   required>
                            <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text"><?php echo e(__('product.stock_update_hint')); ?></div>
                        </div>
                    </div>

                    <div class="content-card border rounded-3 p-3 mb-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="has_free_service" name="has_free_service" value="1" <?php echo e(old('has_free_service', $product->has_free_service) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-semibold" for="has_free_service"><?php echo e(__('product.free_service_available')); ?></label>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="free_service_duration_value" class="form-label fw-semibold"><?php echo e(__('product.free_service_duration_value')); ?></label>
                                <input type="number"
                                       class="form-control <?php $__errorArgs = ['free_service_duration_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="free_service_duration_value"
                                       name="free_service_duration_value"
                                       min="1"
                                       value="<?php echo e(old('free_service_duration_value', $product->free_service_duration_value)); ?>"
                                       placeholder="6">
                                <?php $__errorArgs = ['free_service_duration_value'];
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

                            <div class="col-md-4 mb-3">
                                <label for="free_service_duration_unit" class="form-label fw-semibold"><?php echo e(__('product.free_service_duration_unit')); ?></label>
                                <select class="form-select <?php $__errorArgs = ['free_service_duration_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="free_service_duration_unit"
                                        name="free_service_duration_unit">
                                    <option value=""><?php echo e(__('product.select_duration_unit')); ?></option>
                                    <option value="day" <?php echo e(old('free_service_duration_unit', $product->free_service_duration_unit) === 'day' ? 'selected' : ''); ?>><?php echo e(__('product.duration_day')); ?></option>
                                    <option value="month" <?php echo e(old('free_service_duration_unit', $product->free_service_duration_unit) === 'month' ? 'selected' : ''); ?>><?php echo e(__('product.duration_month')); ?></option>
                                    <option value="year" <?php echo e(old('free_service_duration_unit', $product->free_service_duration_unit) === 'year' ? 'selected' : ''); ?>><?php echo e(__('product.duration_year')); ?></option>
                                </select>
                                <?php $__errorArgs = ['free_service_duration_unit'];
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

                            <div class="col-md-12 mb-0">
                                <label for="free_service_terms" class="form-label fw-semibold"><?php echo e(__('product.free_service_terms')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['free_service_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="free_service_terms"
                                          name="free_service_terms"
                                          rows="2"
                                          placeholder="<?php echo e(__('product.free_service_terms_placeholder')); ?>"><?php echo e(old('free_service_terms', $product->free_service_terms)); ?></textarea>
                                <?php $__errorArgs = ['free_service_terms'];
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
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo e(__('product.custom_attributes')); ?></label>
                        <div id="dynamicFieldsContainer" class="dynamic-fields-wrap">
                            <p class="dynamic-fields-empty"><?php echo e(__('product.dynamic_no_fields_for_category')); ?></p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="<?php echo e(route('product.index')); ?>" class="btn btn-back">
                            <i class="bi bi-arrow-left"></i> <?php echo e(__('product.back_to_list')); ?>

                        </a>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle"></i> <?php echo e(__('product.update_btn')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        const categorySelect = document.getElementById('category_id');
        const container = document.getElementById('dynamicFieldsContainer');
        const dynamicFields = <?php echo json_encode($dynamicFieldsByCategory ?? [], 15, 512) ?>;
        const dynamicValues = <?php echo json_encode($dynamicFieldValues ?? [], 15, 512) ?>;
        const validationErrors = <?php echo json_encode($errors->getMessages(), 15, 512) ?>;
        const labels = {
            noFields: <?php echo json_encode(__('product.dynamic_no_fields_for_category'), 15, 512) ?>,
        };

        function mergeFields(categoryId) {
            const globalFields = dynamicFields.global || [];
            const categoryFields = categoryId && dynamicFields[categoryId] ? dynamicFields[categoryId] : [];
            return [...globalFields, ...categoryFields];
        }

        function buildFieldInput(field) {
            const col = document.createElement('div');
            col.className = 'mb-3';

            const label = document.createElement('label');
            label.className = 'form-label fw-semibold';
            label.setAttribute('for', `custom_field_${field.id}`);
            label.textContent = field.label;

            if (field.is_required) {
                const requiredMark = document.createElement('span');
                requiredMark.className = 'text-danger ms-1';
                requiredMark.textContent = '*';
                label.appendChild(requiredMark);
            }

            col.appendChild(label);

            const fieldName = `custom_fields[${field.id}]`;
            const errorKey = `custom_fields.${field.id}`;
            const errorMessage = validationErrors[errorKey] ? validationErrors[errorKey][0] : null;
            const fieldValue = dynamicValues[field.id] ?? dynamicValues[String(field.id)] ?? '';

            let input;
            if (field.input_type === 'textarea') {
                input = document.createElement('textarea');
                input.rows = 3;
                input.textContent = fieldValue;
            } else if (field.input_type === 'select') {
                input = document.createElement('select');
                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = 'Select';
                input.appendChild(placeholderOption);

                (field.options || []).forEach((optionValue) => {
                    const option = document.createElement('option');
                    option.value = optionValue;
                    option.textContent = optionValue;
                    option.selected = String(fieldValue) === String(optionValue);
                    input.appendChild(option);
                });
            } else {
                input = document.createElement('input');
                input.type = field.input_type === 'number' ? 'number' : (field.input_type === 'date' ? 'date' : 'text');
                input.value = fieldValue;
                if (field.input_type === 'number') {
                    input.step = 'any';
                }
            }

            input.id = `custom_field_${field.id}`;
            input.name = fieldName;
            input.className = `form-control${errorMessage ? ' is-invalid' : ''}`;

            if (field.placeholder && field.input_type !== 'select') {
                input.placeholder = field.placeholder;
            }

            if (field.is_required) {
                input.required = true;
            }

            col.appendChild(input);

            if (field.help_text) {
                const help = document.createElement('small');
                help.className = 'form-text';
                help.textContent = field.help_text;
                col.appendChild(help);
            }

            if (errorMessage) {
                const invalidFeedback = document.createElement('div');
                invalidFeedback.className = 'invalid-feedback d-block';
                invalidFeedback.textContent = errorMessage;
                col.appendChild(invalidFeedback);
            }

            return col;
        }

        function renderDynamicFields() {
            if (!container || !categorySelect) {
                return;
            }

            const selectedCategoryId = categorySelect.value;
            const fields = mergeFields(selectedCategoryId);

            container.innerHTML = '';
            if (!fields.length) {
                const empty = document.createElement('p');
                empty.className = 'dynamic-fields-empty';
                empty.textContent = labels.noFields;
                container.appendChild(empty);
                return;
            }

            fields.forEach((field) => {
                container.appendChild(buildFieldInput(field));
            });
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', renderDynamicFields);
        }

        renderDynamicFields();
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Product\resources/views/edit.blade.php ENDPATH**/ ?>