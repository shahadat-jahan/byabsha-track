<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.reset_password_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(30, 41, 59, .9);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 16px;
            padding: 2rem;
            color: #e2e8f0;
        }
        .form-control {
            background: rgba(15, 23, 42, .6);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #e2e8f0;
        }
        .form-control:focus {
            background: rgba(15, 23, 42, .8);
            color: #e2e8f0;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .2);
        }
        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }
        .text-muted-custom {
            color: #94a3b8;

        }











































</html></body>    </div>        </div>            <a href="{{ route('login') }}" class="text-decoration-none">{{ __('auth.back_to_login') }}</a>        <div class="text-center mt-3">        </form>            </button>                <i class="bi bi-shield-lock"></i> {{ __('auth.reset_password_btn') }}            <button type="submit" class="btn btn-primary w-100">            </div>                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>                <label for="password_confirmation" class="form-label">{{ __('auth.confirm_new_password') }}</label>            <div class="mb-3">            </div>                @enderror                    <div class="invalid-feedback">{{ $message }}</div>                @error('password')                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>                <label for="password" class="form-label">{{ __('auth.new_password') }}</label>            <div class="mb-3">            </div>                @enderror                    <div class="invalid-feedback">{{ $message }}</div>                @error('email')                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required>                <label for="email" class="form-label">{{ __('auth.email_address') }}</label>            <div class="mb-3">            <input type="hidden" name="token" value="{{ $token }}">            @csrf        <form method="POST" action="{{ route('password.update') }}">        <p class="text-muted-custom mb-4">{{ __('auth.reset_password_subtitle') }}</p>        <h1 class="h4 mb-2">{{ __('auth.reset_password_heading') }}</h1>    <div class="auth-card"><body></head>    </style>
