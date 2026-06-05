@extends('layouts.app')

@section('title', __('user.profile_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --profile-ink-900: #0f172a;
        --profile-ink-700: #334155;
        --profile-ink-500: #64748b;
        --profile-brand: #0f766e;
        --profile-brand-deep: #155e75;
        --profile-line: #d8e4ee;
    }

    .profile-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--profile-ink-900);
    }

    .profile-shell::before {
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

    .profile-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--profile-brand);
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
        color: var(--profile-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--profile-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
        max-width: 760px;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--profile-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        padding: 1.5rem;
    }

    .content-card hr {
        border-color: #dde7f1;
        opacity: 1;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
        color: var(--profile-ink-900);
    }

    .section-sub {
        font-size: 0.9rem;
        color: var(--profile-ink-500);
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .form-control {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--profile-ink-900);
        font-size: 0.94rem;
        padding: 0.62rem 0.78rem;
    }

    .form-control:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .btn-save-profile {
        background: linear-gradient(140deg, var(--profile-brand), var(--profile-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-save-profile:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .alert-danger {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 14px;
    }

    @media (max-width: 768px) {
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
<div class="profile-shell">
    <div class="mb-4">
        <span class="profile-kicker"><i class="bi bi-person-badge"></i>{{ __('user.profile_title') }}</span>
        <h1 class="page-title display-font">{{ __('user.profile_title') }}</h1>
        <p class="page-subtitle">{{ __('user.profile_subtitle') }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ __('app.validation_error') }}</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card">
        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">{{ __('user.name') }} <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">{{ __('user.email') }} <span class="text-danger">*</span></label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">
            <h5 class="section-title">{{ __('user.change_password_title') }}</h5>
            <p class="section-sub mb-3">{{ __('user.change_password_subtitle') }}</p>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="current_password" class="form-label">{{ __('user.current_password') }}</label>
                    <input
                        type="password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        id="current_password"
                        name="current_password"
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="new_password" class="form-label">{{ __('user.new_password') }}</label>
                    <input
                        type="password"
                        class="form-control @error('new_password') is-invalid @enderror"
                        id="new_password"
                        name="new_password"
                    >
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="new_password_confirmation" class="form-label">{{ __('user.new_password_confirmation') }}</label>
                    <input
                        type="password"
                        class="form-control"
                        id="new_password_confirmation"
                        name="new_password_confirmation"
                    >
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-save-profile">
                    <i class="bi bi-save me-1"></i>{{ __('user.save_profile') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
