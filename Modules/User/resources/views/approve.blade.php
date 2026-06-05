@extends('layouts.app')

@section('title', __('user.approve_title'))

@push('styles')
<style>
    @@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --approve-ink-900: #0f172a;
        --approve-ink-700: #334155;
        --approve-ink-500: #64748b;
        --approve-amber: #d97706;
        --approve-amber-deep: #92400e;
        --approve-line: #d8e4ee;
    }

    .user-approve-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--approve-ink-900);
    }

    .user-approve-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(217, 119, 6, 0.14), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(15, 118, 110, 0.12), transparent 55%),
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
        background: rgba(217, 119, 6, 0.12);
        color: var(--approve-amber);
        border: 1px solid rgba(217, 119, 6, 0.28);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(217, 119, 6, 0.13);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--approve-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--approve-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .manager-banner {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
        border-radius: 16px;
        padding: 1.1rem 1.3rem;
    }

    .pending-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(217, 119, 6, 0.15);
        color: var(--approve-amber);
        border: 1px solid rgba(217, 119, 6, 0.3);
        border-radius: 999px;
        padding: 0.25rem 0.7rem;
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--approve-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        padding: 1.5rem;
    }

    .section-divider {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--approve-ink-500);
        border-bottom: 1px solid var(--approve-line);
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .alert-danger {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 14px;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .form-control,
    .form-select {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--approve-ink-900);
        font-size: 0.94rem;
        padding: 0.62rem 0.78rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 0.2rem rgba(217, 119, 6, 0.14);
        background: #ffffff;
    }

    .helper-text {
        font-size: 0.82rem;
        color: var(--approve-ink-500);
    }

    .btn-cancel {
        border-radius: 999px;
        padding: 0.62rem 1.15rem;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .btn-approve {
        background: linear-gradient(140deg, #d97706, #92400e);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(217, 119, 6, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-approve:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(217, 119, 6, 0.38);
    }

    .module-access-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 0.55rem;
    }

    .module-access-item {
        border: 1px solid #d6e2ee;
        border-radius: 11px;
        background: #fbfdff;
        padding: 0.52rem 0.7rem;
        font-size: 0.86rem;
        color: #334155;
        cursor: pointer;
    }

    .module-access-item:has(input:checked) {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    @@media (max-width: 768px) {
        .content-card {
            padding: 1.05rem;
            border-radius: 16px;
        }

        .page-title {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<div class="user-approve-shell">

    <div class="mb-4">
        <span class="form-kicker"><i class="bi bi-person-check"></i>{{ __('user.approve_title') }}</span>
        <h1 class="page-title display-font">{{ __('user.approve_title') }}</h1>
        <p class="page-subtitle">{{ __('user.approve_subtitle') }}</p>
    </div>

    <div class="manager-banner mb-4 d-flex align-items-center gap-3">
        <div class="flex-shrink-0">
            <div style="width:46px;height:46px;border-radius:50%;background:rgba(217,119,6,0.18);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-badge" style="font-size:1.35rem;color:#d97706;"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <strong style="color:#92400e;font-size:1rem;">{{ $user->name }}</strong>
                <span class="pending-badge"><i class="bi bi-clock"></i>{{ __('user.pending_approval') }}</span>
            </div>
            <div style="font-size:0.88rem;color:#78350f;">{{ $user->email }}</div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>{{ __('app.validation_error') }}</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card">
        <form action="{{ route('user.approve', $user->id) }}" method="POST">
            @csrf

            <p class="section-divider">{{ __('user.assign_shop') }}</p>
            <div class="mb-3">
                <label for="shop_id" class="form-label">{{ __('user.shop') }} <span class="text-danger">*</span></label>
                <select class="form-select @error('shop_id') is-invalid @enderror"
                        id="shop_id" name="shop_id" required onchange="filterBranches()">
                    <option value="">{{ __('user.select_shop') }}</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
                @error('shop_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <p class="section-divider">{{ __('user.assign_branch') }}</p>
            <div class="mb-4">
                <label for="branch_id" class="form-label">{{ __('user.branch') }}</label>
                <select class="form-select @error('branch_id') is-invalid @enderror"
                        id="branch_id" name="branch_id">
                    <option value="">{{ __('user.select_branch') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                                data-shop-id="{{ $branch->shop_id }}"
                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                            @if($branch->shop)
                                &mdash; {{ $branch->shop->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="helper-text">{{ __('user.branch_shop_filter_hint') }} {{ __('user.branch_optional_hint') }}</small>
            </div>

            <p class="section-divider">{{ __('user.manager_permissions') }}</p>
            <div class="mb-4">
                <label class="form-label">{{ __('user.module_access_title') }}</label>
                <div class="module-access-grid">
                    @foreach($moduleAccessOptions as $key => $label)
                        <label class="module-access-item">
                            <input class="form-check-input me-2" type="checkbox"
                                   name="module_access[]" value="{{ $key }}"
                                   {{ in_array($key, old('module_access', []), true) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                <small class="helper-text">{{ __('user.module_access_help') }}</small>
                @error('module_access')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('user.show', $user->id) }}" class="btn btn-secondary btn-cancel">
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" class="btn btn-approve">
                    <i class="bi bi-person-check-fill"></i>{{ __('user.approve_btn') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function filterBranches() {
        var shopId = document.getElementById('shop_id').value;
        var branchSelect = document.getElementById('branch_id');
        var options = branchSelect.querySelectorAll('option[data-shop-id]');

        options.forEach(function (opt) {
            if (!shopId || opt.dataset.shopId === shopId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
                if (opt.selected) {
                    opt.selected = false;
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        filterBranches();
    });
</script>
@endpush
