<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

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
            <label for="label" class="form-label">{{ __('product.dynamic_label') }} <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control @error('label') is-invalid @enderror"
                id="label"
                name="label"
                value="{{ old('label', $field->label ?? '') }}"
                required>
            @error('label')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="category_id" class="form-label">{{ __('product.category') }}</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                <option value="">{{ __('product.dynamic_all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) old('category_id', $field->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if(($showAdvancedFields ?? true))
        <div class="col-md-6">
            <label for="field_key" class="form-label">{{ __('product.dynamic_key') }} <span class="text-danger">*</span></label>
            <input
                type="text"
                class="form-control @error('field_key') is-invalid @enderror"
                id="field_key"
                name="field_key"
                value="{{ old('field_key', $field->field_key ?? '') }}"
                placeholder="screen_size"
                required>
            @error('field_key')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">{{ __('product.dynamic_key_help') }}</small>
        </div>
        @endif

        <div class="col-md-6">
            <label for="input_type" class="form-label">{{ __('product.dynamic_input_type') }} <span class="text-danger">*</span></label>
            <select class="form-select @error('input_type') is-invalid @enderror" id="input_type" name="input_type" required>
                @foreach($inputTypes as $type)
                    <option value="{{ $type }}" {{ old('input_type', $field->input_type ?? 'text') === $type ? 'selected' : '' }}>
                        {{ strtoupper($type) }}
                    </option>
                @endforeach
            </select>
            @error('input_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if(($showAdvancedFields ?? true))
        <div class="col-md-6">
            <label for="placeholder" class="form-label">{{ __('product.dynamic_placeholder') }}</label>
            <input
                type="text"
                class="form-control @error('placeholder') is-invalid @enderror"
                id="placeholder"
                name="placeholder"
                value="{{ old('placeholder', $field->placeholder ?? '') }}">
            @error('placeholder')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="help_text" class="form-label">{{ __('product.dynamic_help_text') }}</label>
            <input
                type="text"
                class="form-control @error('help_text') is-invalid @enderror"
                id="help_text"
                name="help_text"
                value="{{ old('help_text', $field->help_text ?? '') }}">
            @error('help_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @else
        <div class="col-md-6">
            <label for="placeholder" class="form-label">{{ __('product.dynamic_placeholder') }}</label>
            <input
                type="text"
                class="form-control @error('placeholder') is-invalid @enderror"
                id="placeholder"
                name="placeholder"
                value="{{ old('placeholder', $field->placeholder ?? '') }}">
            @error('placeholder')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @endif

        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    id="is_required"
                    name="is_required"
                    {{ old('is_required', $field->is_required ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_required">
                    {{ __('product.dynamic_required') }}
                </label>
            </div>
        </div>

        @if(($showAdvancedFields ?? true))
        <div class="col-md-4">
            <label for="sort_order" class="form-label">{{ __('product.dynamic_sort_order') }}</label>
            <input
                type="number"
                min="0"
                class="form-control @error('sort_order') is-invalid @enderror"
                id="sort_order"
                name="sort_order"
                value="{{ old('sort_order', $field->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    id="is_active"
                    name="is_active"
                    {{ old('is_active', $field->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    {{ __('app.active') }}
                </label>
            </div>
        </div>
        <div class="col-12" id="optionsBlock" style="display:none;">
            <label for="options_text" class="form-label">{{ __('product.dynamic_options') }}</label>
            <textarea
                class="form-control @error('options_text') is-invalid @enderror"
                id="options_text"
                name="options_text"
                rows="5"
                placeholder="LED&#10;QLED&#10;OLED">{{ old('options_text', $optionsText ?? '') }}</textarea>
            @error('options_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">{{ __('product.dynamic_options_help') }}</small>
        </div>
        @endif
    </div>

    <div class="mt-4 d-flex gap-2 flex-wrap">
        <button type="submit" class="btn-primary-pill">
            <i class="bi bi-check-circle"></i>
            {{ $method === 'POST' ? __('product.add_dynamic_field') : __('app.update') }}
        </button>
        <a href="{{ route('product.dynamic-fields.index') }}" class="btn-soft-secondary">
            <i class="bi bi-x-circle"></i> {{ __('app.cancel') }}
        </a>
    </div>
</form>

@push('scripts')
<script>
    (function () {
        const advancedFieldsEnabled = @json(($showAdvancedFields ?? true));
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
@endpush
