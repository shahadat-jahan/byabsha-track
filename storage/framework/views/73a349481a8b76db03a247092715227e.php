<form method="POST" action="<?php echo e($action); ?>">
    <?php echo csrf_field(); ?>
    <?php if($method !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <style>
        .dynamic-form .form-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b637b;
            margin-bottom: 0.3rem;
        }

        .dynamic-form .form-control,
        .dynamic-form .form-select {
            min-height: 38px;
            border-radius: 10px;
            border: 1px solid #d6e2ee;
            background: #fbfdff;
            font-size: 0.87rem;
        }

        .dynamic-form .form-control:focus,
        .dynamic-form .form-select:focus {
            border-color: #53a89f;
            box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
            background: #ffffff;
        }

        .dynamic-form .form-check-label {
            font-size: 0.84rem;
        }

        .btn-primary-pill {
            background: linear-gradient(140deg, #0f766e, #155e75);
            color: #fff;
            border: 0;
            border-radius: 999px;
            padding: 0.56rem 1rem;
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.26);
        }

        .btn-primary-pill:hover {
            color: #fff;
            filter: brightness(1.03);
        }

        .btn-soft-secondary {
            border-radius: 999px;
            padding: 0.56rem 1rem;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid #9eb8cb;
            color: #1f3f58;
            background: #f7fbff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
        }

        .btn-soft-secondary:hover {
            color: #0f172a;
            border-color: #6f93b0;
            background: #ffffff;
        }
    </style>

    <div class="row g-3 dynamic-form">
        <div class="col-md-6">
            <label for="label" class="form-label"><?php echo e(__('product.dynamic_label')); ?> <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="label"
                name="label"
                value="<?php echo e(old('label', $field->label ?? '')); ?>"
                required>
            <?php $__errorArgs = ['label'];
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

        <div class="col-md-6">
            <label for="category_id" class="form-label"><?php echo e(__('product.category')); ?></label>
            <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category_id" name="category_id">
                <option value=""><?php echo e(__('product.dynamic_all_categories')); ?></option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e((string) old('category_id', $field->category_id ?? '') === (string) $category->id ? 'selected' : ''); ?>>
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

        <?php if(($showAdvancedFields ?? true)): ?>
        <div class="col-md-6">
            <label for="field_key" class="form-label"><?php echo e(__('product.dynamic_key')); ?> <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['field_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="field_key"
                name="field_key"
                value="<?php echo e(old('field_key', $field->field_key ?? '')); ?>"
                placeholder="screen_size"
                required>
            <?php $__errorArgs = ['field_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <small class="text-muted"><?php echo e(__('product.dynamic_key_help')); ?></small>
        </div>
        <?php endif; ?>

        <div class="col-md-6">
            <label for="input_type" class="form-label"><?php echo e(__('product.dynamic_input_type')); ?> <span class="text-danger">*</span></label>
            <select class="form-select <?php $__errorArgs = ['input_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="input_type" name="input_type" required>
                <?php $__currentLoopData = $inputTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type); ?>" <?php echo e(old('input_type', $field->input_type ?? 'text') === $type ? 'selected' : ''); ?>>
                        <?php echo e(strtoupper($type)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['input_type'];
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

        <?php if(($showAdvancedFields ?? true)): ?>
        <div class="col-md-6">
            <label for="placeholder" class="form-label"><?php echo e(__('product.dynamic_placeholder')); ?></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['placeholder'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="placeholder"
                name="placeholder"
                value="<?php echo e(old('placeholder', $field->placeholder ?? '')); ?>">
            <?php $__errorArgs = ['placeholder'];
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

        <div class="col-md-6">
            <label for="help_text" class="form-label"><?php echo e(__('product.dynamic_help_text')); ?></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['help_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="help_text"
                name="help_text"
                value="<?php echo e(old('help_text', $field->help_text ?? '')); ?>">
            <?php $__errorArgs = ['help_text'];
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
        <?php else: ?>
        <div class="col-md-6">
            <label for="placeholder" class="form-label"><?php echo e(__('product.dynamic_placeholder')); ?></label>
            <input
                type="text"
                class="form-control <?php $__errorArgs = ['placeholder'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="placeholder"
                name="placeholder"
                value="<?php echo e(old('placeholder', $field->placeholder ?? '')); ?>">
            <?php $__errorArgs = ['placeholder'];
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

        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    id="is_required"
                    name="is_required"
                    <?php echo e(old('is_required', $field->is_required ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="is_required">
                    <?php echo e(__('product.dynamic_required')); ?>

                </label>
            </div>
        </div>

        <?php if(($showAdvancedFields ?? true)): ?>
        <div class="col-md-4">
            <label for="sort_order" class="form-label"><?php echo e(__('product.dynamic_sort_order')); ?></label>
            <input
                type="number"
                min="0"
                class="form-control <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="sort_order"
                name="sort_order"
                value="<?php echo e(old('sort_order', $field->sort_order ?? 0)); ?>">
            <?php $__errorArgs = ['sort_order'];
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

        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    id="is_active"
                    name="is_active"
                    <?php echo e(old('is_active', $field->is_active ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="is_active">
                    <?php echo e(__('app.active')); ?>

                </label>
            </div>
        </div>
        <div class="col-12" id="optionsBlock" style="display:none;">
            <label for="options_text" class="form-label"><?php echo e(__('product.dynamic_options')); ?></label>
            <textarea
                class="form-control <?php $__errorArgs = ['options_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="options_text"
                name="options_text"
                rows="5"
                placeholder="LED&#10;QLED&#10;OLED"><?php echo e(old('options_text', $optionsText ?? '')); ?></textarea>
            <?php $__errorArgs = ['options_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <small class="text-muted"><?php echo e(__('product.dynamic_options_help')); ?></small>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 d-flex gap-2 flex-wrap">
        <button type="submit" class="btn-primary-pill">
            <i class="bi bi-check-circle"></i>
            <?php echo e($method === 'POST' ? __('product.add_dynamic_field') : __('app.update')); ?>

        </button>
        <a href="<?php echo e(route('product.dynamic-fields.index')); ?>" class="btn-soft-secondary">
            <i class="bi bi-x-circle"></i> <?php echo e(__('app.cancel')); ?>

        </a>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        const advancedFieldsEnabled = <?php echo json_encode(($showAdvancedFields ?? true), 15, 512) ?>;
        if (!advancedFieldsEnabled) {
            return;
        }

        const inputTypeEl = document.getElementById('input_type');
        const optionsBlockEl = document.getElementById('optionsBlock');

        function toggleOptions() {
            if (!inputTypeEl || !optionsBlockEl) {
                return;
            }

            optionsBlockEl.style.display = inputTypeEl.value === 'select' ? '' : 'none';
        }

        if (inputTypeEl) {
            inputTypeEl.addEventListener('change', toggleOptions);
            toggleOptions();
        }
    })();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\byabsha-track\Modules/Product\resources/views/dynamic-fields/form.blade.php ENDPATH**/ ?>