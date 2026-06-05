<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track') }} - {{ __('landing.smart_business') }}</title>
    <link rel="icon" href="{{ \Modules\Settings\Models\Setting::get('dashboard_favicon') ?: asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bg: #f4f8fb;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --brand: {{ \Modules\Settings\Models\Setting::get('dashboard_theme_color', '#0f766e') }};
            --brand-deep: color-mix(in srgb, var(--brand) 80%, #000000);
            --card: #ffffff;
            --line: #d8e4ee;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink-900);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            scroll-behavior: smooth;
        }

        .display-font {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            letter-spacing: -0.03em;
        }

        .site-wrap {
            min-height: 100vh;
            background:
                radial-gradient(900px 500px at 85% -5%, color-mix(in srgb, var(--brand) 23%, transparent), transparent 60%),
                radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.2), transparent 55%),
                linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
        }

        .site-navbar {
            background: rgba(250, 252, 254, 0.9);
            border-bottom: 1px solid rgba(100, 116, 139, 0.17);
            backdrop-filter: blur(12px);
        }

        .site-navbar .navbar-brand {
            color: var(--ink-900);
            text-decoration: none;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .brand-chip {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--brand), var(--brand-deep));
            color: #fff;
            box-shadow: 0 10px 26px rgba(15, 118, 110, 0.34);
        }

        .site-navbar .nav-link {
            color: var(--ink-700) !important;
            border-radius: 999px;
            font-size: 0.88rem;
            padding: 0.45rem 0.92rem !important;
            transition: all 0.2s ease;
        }

        .site-navbar .nav-link:hover {
            background: #e7f0f7;
            color: var(--ink-900) !important;
        }

        .nav-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(15, 118, 110, 0.12);
            border: 1px solid rgba(15, 118, 110, 0.22);
            color: #0f766e;
            padding: 0.28rem 0.75rem;
            border-radius: 99px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            background: #e6eef5;
            border: 1px solid #d2deea;
            border-radius: 20px;
            padding: 3px;
            gap: 2px;
        }

        .lang-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            color: #55657a;
            transition: background 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .lang-btn:hover:not(.lang-active) {
            background: #d8e5f1;
            color: #0f172a;
        }

        .lang-btn.lang-active {
            background: #0f766e;
            color: #fff;
        }

        .btn-nav-cta,
        .btn-hero-primary {
            background: linear-gradient(140deg, var(--brand), var(--brand-deep));
            color: #fff !important;
            border: 0;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }

        .btn-nav-cta {
            padding: 0.55rem 1.25rem;
            font-size: 0.86rem;
        }

        .btn-hero-primary {
            padding: 0.85rem 1.7rem;
            font-size: 0.93rem;
            box-shadow: 0 14px 28px rgba(15, 118, 110, 0.32);
        }

        .btn-nav-cta:hover,
        .btn-hero-primary:hover {
            color: #fff !important;
            transform: translateY(-2px);
            box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
        }

        .btn-hero-outline {
            background: rgba(255, 255, 255, 0.6);
            color: var(--ink-700);
            border: 1px solid #cedce9;
            border-radius: 999px;
            padding: 0.85rem 1.6rem;
            font-size: 0.93rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-hero-outline:hover {
            color: var(--ink-900);
            border-color: #97b0c8;
            background: #fff;
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 4.9rem 0 3rem;
            isolation: isolate;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: auto auto -140px -130px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--brand) 24%, transparent) 0%, transparent 72%);
            z-index: -1;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: -120px -140px auto auto;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, rgba(245, 158, 11, 0) 72%);
            z-index: -1;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.48rem;
            background: color-mix(in srgb, var(--brand) 12%, transparent);
            color: var(--brand);
            border: 1px solid color-mix(in srgb, var(--brand) 22%, transparent);
            border-radius: 999px;
            padding: 0.44rem 0.92rem;
            font-size: 0.78rem;
            font-weight: 700;
            margin-bottom: 1rem;
            box-shadow: 0 8px 18px color-mix(in srgb, var(--brand) 13%, transparent);
        }

        .hero-title {
            font-size: clamp(2rem, 4.6vw, 4rem);
            line-height: 1.02;
            margin-bottom: 1.1rem;
            color: var(--ink-900);
            max-width: 700px;
            text-wrap: balance;
        }

        .hero-title .hl {
            color: var(--brand);
        }

        .hero-sub {
            color: var(--ink-700);
            line-height: 1.8;
            font-size: 1.05rem;
            max-width: 640px;
            margin-bottom: 1.8rem;
        }

        .hero-trust-row {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .hero-trust-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.66);
            border: 1px solid #d2deea;
            color: #42546a;
            border-radius: 999px;
            padding: 0.35rem 0.72rem;
            font-size: 0.77rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
        }

        .hero-trust-pill i {
            color: #0f766e;
        }

        .hero-surface {
            background: linear-gradient(160deg, #092132, #123b59);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 26px;
            padding: 1.35rem;
            box-shadow: 0 28px 64px rgba(15, 23, 42, 0.28);
            position: relative;
            overflow: hidden;
        }

        .hero-surface::before {
            content: '';
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.18) 0%, rgba(34, 211, 238, 0) 70%);
            top: -80px;
            right: -100px;
            pointer-events: none;
        }

        .hero-surface-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #dce7f3;
            font-size: 0.86rem;
        }

        .dot-set {
            display: inline-flex;
            gap: 0.36rem;
        }

        .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.32);
        }

        .hero-stack {
            display: grid;
            gap: 0.75rem;
        }

        .hero-feature {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            color: #ebf5ff;
            padding: 0.92rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.74rem;
            opacity: 0;
            transform: translateY(10px);
            animation: riseUp 0.65s ease forwards;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .hero-feature:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.32);
            background: rgba(255, 255, 255, 0.13);
        }

        .hero-feature:nth-child(1) {
            animation-delay: 0.12s;
        }

        .hero-feature:nth-child(2) {
            animation-delay: 0.22s;
        }

        .hero-feature:nth-child(3) {
            animation-delay: 0.32s;
        }

        .hero-feature i {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .metrics-band {
            padding: 1.1rem;
            margin-top: 1.9rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 18px;
            border: 1px solid #d9e5f0;
            display: grid;
            gap: 0.8rem;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        }

        .hero-insight {
            margin-top: 0.9rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 0.9rem;
        }

        .hero-insight-title {
            color: #c8def3;
            font-size: 0.73rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.7rem;
            font-weight: 700;
        }

        .hero-insight-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.55rem;
        }

        .hero-insight-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            text-align: center;
            padding: 0.58rem 0.45rem;
        }

        .hero-insight-item strong {
            display: block;
            color: #ffffff;
            font-size: 0.95rem;
            line-height: 1.1;
            margin-bottom: 0.22rem;
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .hero-insight-item span {
            color: #9ec0de;
            font-size: 0.64rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
        }

        .metric-item {
            text-align: center;
            padding: 0.5rem;
        }

        .metric-value {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            font-size: 1.85rem;
            line-height: 1;
            color: var(--ink-900);
            margin-bottom: 0.18rem;
            font-weight: 700;
        }

        .metric-label {
            font-size: 0.79rem;
            color: var(--ink-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .section-block {
            padding: 5rem 0;
        }

        .section-heading {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 2rem;
        }

        .section-badge {
            display: inline-block;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--brand);
            margin-bottom: 0.65rem;
        }

        .section-title {
            font-size: clamp(1.6rem, 3.2vw, 2.55rem);
            color: var(--ink-900);
            margin-bottom: 0.8rem;
            line-height: 1.15;
        }

        .section-sub {
            color: var(--ink-700);
            font-size: 1rem;
            line-height: 1.7;
        }

        .feature-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 1.5rem;
            height: 100%;
            box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.95rem;
            font-size: 1.25rem;
        }

        .workflow {
            position: relative;
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .workflow::before {
            content: '';
            position: absolute;
            top: 28px;
            left: 8%;
            right: 8%;
            height: 2px;
            background: linear-gradient(90deg, color-mix(in srgb, var(--brand) 20%, transparent), color-mix(in srgb, var(--brand) 65%, transparent), color-mix(in srgb, var(--brand) 20%, transparent));
            z-index: 0;
        }

        .workflow-step {
            position: relative;
            z-index: 1;
            background: #fff;
            border: 1px solid #d7e2ec;
            border-radius: 16px;
            padding: 1.15rem 1rem;
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
        }

        .workflow-number {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            margin: 0 auto 0.85rem;
            background: linear-gradient(150deg, var(--brand), color-mix(in srgb, var(--brand) 85%, #000000));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .cta-shell {
            background: linear-gradient(135deg, color-mix(in srgb, var(--brand) 80%, #000000) 0%, var(--brand) 60%, color-mix(in srgb, var(--brand) 80%, #000000) 100%);
            border-radius: 24px;
            padding: clamp(2rem, 4vw, 3.3rem);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.13);
        }

        .cta-shell::after {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            right: -120px;
            top: -130px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.34) 0%, rgba(245, 158, 11, 0) 70%);
        }

        .footer {
            padding: 2rem 0 2.4rem;
            color: var(--ink-500);
            text-align: center;
        }

        .footer-meta-row {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            margin: 0.35rem auto 0;
            gap: 50rem;
        }

        .footer-meta-col {
            white-space: nowrap;
            padding: 0 0.4rem;
        }

        .modal-login .modal-content {
            background: rgba(7, 23, 33, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.11);
            border-radius: 20px;
            backdrop-filter: blur(12px);
        }

        .form-ctrl-dark {
            background: rgba(15, 23, 42, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 10px;
            color: #dbe6f2;
            padding: 0.7rem 1rem 0.7rem 2.6rem;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.2s;
        }

        .form-ctrl-dark::placeholder {
            color: #546173;
        }

        .form-ctrl-dark:focus {
            outline: none;
            border-color: #1d9f8f;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.18);
        }

        .form-ctrl-dark.is-invalid {
            border-color: #ef4444;
        }

        .btn-login-modal {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(140deg, var(--brand), var(--brand-deep));
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.25s;
            box-shadow: 0 9px 18px rgba(15, 118, 110, 0.35);
        }

        .btn-login-modal:hover {
            transform: translateY(-1px);
            box-shadow: 0 13px 20px rgba(15, 118, 110, 0.38);
        }

        .input-icon-modal {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #4f5f73;
            font-size: 0.95rem;
            z-index: 5;
        }

        .toggle-btn-modal {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #4f5f73;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-size: 0.95rem;
            z-index: 5;
        }

        .reveal {
            opacity: 0;
            transform: translateY(15px);
            animation: riseUp 0.65s ease forwards;
        }

        .reveal:nth-child(2) {
            animation-delay: 0.08s;
        }

        .reveal:nth-child(3) {
            animation-delay: 0.16s;
        }

        @keyframes riseUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991.98px) {
            .hero {
                padding-top: 3.6rem;
            }

            .hero-title {
                max-width: 100%;
            }

            .metrics-band {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .workflow {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .workflow::before {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-trust-row {
                gap: 0.45rem;
            }

            .hero-trust-pill {
                font-size: 0.72rem;
            }

            .hero-insight-grid {
                grid-template-columns: 1fr;
            }

            .metrics-band {
                grid-template-columns: 1fr;
            }

            .workflow {
                grid-template-columns: 1fr;
            }

            .btn-hero-primary,
            .btn-hero-outline {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="site-wrap">
    <nav class="navbar navbar-expand-lg sticky-top site-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing.index') }}">
                @if(\Modules\Settings\Models\Setting::get('dashboard_logo'))
                    <img src="{{ asset(\Modules\Settings\Models\Setting::get('dashboard_logo')) }}" alt="Logo" style="height: 38px; max-width: 120px; object-fit: contain;" class="me-1">
                @else
                    <span class="brand-chip">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                @endif
                <span class="display-font">{{ \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track') }}</span>
            </a>

            <button class="navbar-toggler border-0 ms-auto me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="bi bi-grid-3x3-gap me-1"></i>{{ __('landing.nav_features') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cta">
                            <i class="bi bi-rocket me-1"></i>{{ __('landing.nav_get_started') }}
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-lg-2 mt-2 mt-lg-0">
                        <span class="nav-badge">
                            <i class="bi bi-lightning-fill"></i>{{ __('landing.v1_live') }}
                        </span>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="lang-switcher">
                        <a href="{{ route('language.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}">EN</a>
                        <a href="{{ route('language.switch', 'bn') }}" class="lang-btn {{ app()->getLocale() === 'bn' ? 'lang-active' : '' }}">বাংলা</a>
                    </div>

                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn-nav-cta btn">
                            <i class="bi bi-speedometer2"></i>{{ __('landing.go_to_dashboard') }}
                        </a>
                    @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="nav-link" style="white-space: nowrap;">{{ __('landing.sign_in') }}</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-nav-cta btn">
                            <i class="bi bi-box-arrow-in-right"></i>{{ __('landing.get_started') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="hero-kicker reveal">
                        <i class="bi bi-stars"></i>{{ __('landing.smart_business') }}
                    </div>

                    <h1 class="hero-title display-font reveal">
                        {{ __('landing.hero_title_1') }}
                        <span class="hl">{{ __('landing.hero_title_hl') }}</span>
                        {{ __('landing.hero_title_2') }}
                    </h1>

                    <p class="hero-sub reveal">{{ __('landing.hero_desc') }}</p>

                    <div class="d-flex flex-wrap gap-3 reveal">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i>{{ __('landing.start_tracking') }}
                        </a>
                        <a href="#features" class="btn-hero-outline">
                            <i class="bi bi-play-circle"></i>{{ __('landing.see_features') }}
                        </a>
                    </div>

                    <div class="hero-trust-row reveal">
                        <span class="hero-trust-pill"><i class="bi bi-shield-check"></i>{{ __('landing.feat_secure') }}</span>
                        <span class="hero-trust-pill"><i class="bi bi-graph-up-arrow"></i>{{ __('landing.feat_analytics') }}</span>
                        <span class="hero-trust-pill"><i class="bi bi-currency-dollar"></i>{{ __('landing.feat_profit') }}</span>
                    </div>

                    <div class="metrics-band reveal">
                        <div class="metric-item">
                            <div class="metric-value">{{ __('landing.modules_count') }}</div>
                            <div class="metric-label">{{ __('landing.modules') }}</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-value">{{ __('landing.real_time') }}</div>
                            <div class="metric-label">{{ __('landing.real_time_label') }}</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-value">{{ __('landing.auto') }}</div>
                            <div class="metric-label">{{ __('landing.capital_sync') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-surface">
                        <div class="hero-surface-header">
                            <span class="display-font">Business Ops Board</span>
                            <span class="dot-set">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </span>
                        </div>
                        <div class="hero-stack">
                            <div class="hero-feature">
                                <i class="bi bi-activity"></i>
                                <span>{{ __('landing.feat_analytics') }}</span>
                            </div>
                            <div class="hero-feature">
                                <i class="bi bi-cash-stack"></i>
                                <span>{{ __('landing.feat_profit') }}</span>
                            </div>
                            <div class="hero-feature">
                                <i class="bi bi-shield-lock"></i>
                                <span>{{ __('landing.feat_secure') }}</span>
                            </div>
                        </div>

                        <div class="hero-insight">
                            <div class="hero-insight-title">Today Snapshot</div>
                            <div class="hero-insight-grid">
                                <div class="hero-insight-item">
                                    <strong>{{ __('landing.modules_count') }}</strong>
                                    <span>{{ __('landing.modules') }}</span>
                                </div>
                                <div class="hero-insight-item">
                                    <strong>{{ __('landing.real_time') }}</strong>
                                    <span>{{ __('landing.real_time_label') }}</span>
                                </div>
                                <div class="hero-insight-item">
                                    <strong>{{ __('landing.auto') }}</strong>
                                    <span>{{ __('landing.capital_sync') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="section-block">
        <div class="container">
            <div class="section-heading">
                <span class="section-badge">{{ __('landing.features_badge') }}</span>
                <h2 class="section-title display-font">{{ __('landing.features_title') }}</h2>
                <p class="section-sub">{{ __('landing.features_sub') }}</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#ebf8ff;color:#0ea5e9;"><i class="bi bi-speedometer2"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat1_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat1_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#ecfdf5;color:#16a34a;"><i class="bi bi-cart-check-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat2_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat2_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#fefce8;color:#ca8a04;"><i class="bi bi-box-seam-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat3_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat3_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#f0fdfa;color:#0d9488;"><i class="bi bi-cash-coin"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat4_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat4_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-bar-chart-line-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat5_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat5_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-shop-window"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat6_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat6_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#fdf2f8;color:#db2777;"><i class="bi bi-exclamation-octagon-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat7_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat7_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-diagram-3-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat8_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat8_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <span class="feature-icon" style="background:#ecfeff;color:#0891b2;"><i class="bi bi-shield-lock-fill"></i></span>
                        <h3 class="h5 mb-2">{{ __('landing.feat9_title') }}</h3>
                        <p class="mb-0 text-secondary small">{{ __('landing.feat9_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="container">
            <div class="section-heading mb-4">
                <span class="section-badge">{{ __('landing.usage_badge') }}</span>
                <h2 class="section-title display-font">{{ __('landing.usage_title') }}</h2>
                <p class="section-sub mb-0">{{ __('landing.usage_sub') }}</p>
            </div>

            <div class="workflow">
                <div class="workflow-step text-center">
                    <div class="workflow-number">01</div>
                    <h3 class="h6 mb-2">{{ __('landing.flow1_title') }}</h3>
                    <p class="mb-0 small text-secondary">{{ __('landing.flow1_desc') }}</p>
                </div>
                <div class="workflow-step text-center">
                    <div class="workflow-number">02</div>
                    <h3 class="h6 mb-2">{{ __('landing.flow2_title') }}</h3>
                    <p class="mb-0 small text-secondary">{{ __('landing.flow2_desc') }}</p>
                </div>
                <div class="workflow-step text-center">
                    <div class="workflow-number">03</div>
                    <h3 class="h6 mb-2">{{ __('landing.flow3_title') }}</h3>
                    <p class="mb-0 small text-secondary">{{ __('landing.flow3_desc') }}</p>
                </div>
                <div class="workflow-step text-center">
                    <div class="workflow-number">04</div>
                    <h3 class="h6 mb-2">{{ __('landing.flow4_title') }}</h3>
                    <p class="mb-0 small text-secondary">{{ __('landing.flow4_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-block pt-0" id="cta">
        <div class="container">
            <div class="cta-shell text-center text-md-start">
                <div class="row g-4 align-items-center position-relative" style="z-index:1;">
                    <div class="col-md-8">
                        <h2 class="display-font text-white" style="font-size:clamp(1.6rem,3.8vw,2.6rem);margin-bottom:0.8rem;">{{ __('landing.cta_title') }}</h2>
                        <p class="mb-0" style="color:#cae7eb;line-height:1.8;">{{ __('landing.cta_sub') }}</p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end justify-content-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-hero-primary" style="background:linear-gradient(140deg,#f59e0b,#d97706);box-shadow:0 15px 24px rgba(245,158,11,.3);">
                            <i class="bi bi-box-arrow-in-right"></i>{{ __('landing.cta_btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
            <div class="footer-meta-row small">
                <div class="footer-meta-col">&copy; {{ date('Y') }} {{ \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track') }}. {{ app()->getLocale() === 'bn' ? 'সর্বস্বত্ব সংরক্ষিত।' : 'All rights reserved.' }}</div>
                <div class="footer-meta-col">{{ __('landing.footer_developer') }}</div>

            </div>
        </div>
    </footer>
</div>

@guest
@php
    $requestedAuthModal = request()->query('auth');
    $formSource = old('_form');
    $sessionAuthModal = session('auth_modal');

    $initialAuthPanel = 'login';
    if ($requestedAuthModal === 'register' || $formSource === 'register') {
        $initialAuthPanel = 'register';
    } elseif ($requestedAuthModal === 'forgot' || $formSource === 'forgot' || $sessionAuthModal === 'forgot') {
        $initialAuthPanel = 'forgot';
    }

    $shouldOpenAuthModal = in_array($requestedAuthModal, ['login', 'register', 'forgot'], true)
        || $errors->any()
        || session()->has('status')
        || $sessionAuthModal === 'forgot';

    $hasLoginOldInput = $errors->any()
        && old('_form') !== 'register'
        && old('_form') !== 'forgot';
@endphp
<div class="modal fade modal-login" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('landing.index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    @if(\Modules\Settings\Models\Setting::get('dashboard_logo'))
                        <img src="{{ asset(\Modules\Settings\Models\Setting::get('dashboard_logo')) }}" alt="Logo" style="height: 34px; max-width: 120px; object-fit: contain;" class="me-1">
                    @else
                        <span class="brand-chip" style="width:38px;height:38px;">
                            <i class="bi bi-graph-up-arrow" style="color:#fff;font-size:1rem;"></i>
                        </span>
                    @endif
                    <span style="font-size:1.2rem;font-weight:700;color:#fff;">{{ \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track') }}</span>
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div id="mLoginPanel" style="display:{{ $initialAuthPanel === 'login' ? 'block' : 'none' }};">
                <h2 style="font-size:1.35rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.3rem;">{{ __('auth.welcome_back') }}</h2>
                <p style="font-size:.85rem;color:#73879b;text-align:center;margin-bottom:1rem;">{{ __('auth.sign_in_sub') }}</p>

                @if($errors->any() && old('_form') !== 'register' && old('_form') !== 'forgot')
                    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:.75rem 1rem;color:#fca5a5;font-size:.875rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_form" value="login">
                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.email_address') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-envelope input-icon-modal"></i>
                            <input type="email" name="email" class="form-ctrl-dark {{ $errors->has('email') && old('_form') !== 'register' && old('_form') !== 'forgot' ? 'is-invalid' : '' }}" placeholder="{{ __('auth.enter_email') }}" value="{{ old('_form') !== 'register' && old('_form') !== 'forgot' ? old('email') : '' }}" required autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute('readonly');" autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.password') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-lock input-icon-modal"></i>
                            <input type="password" id="mPwd" name="password" class="form-ctrl-dark" placeholder="{{ __('auth.enter_password') }}" required autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');">
                            <button type="button" class="toggle-btn-modal" onclick="toggleMPwd()">
                                <i class="bi bi-eye" id="mEye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <input type="checkbox" id="mRemember" name="remember" style="width:15px;height:15px;accent-color:#0f766e;cursor:pointer;">
                        <label for="mRemember" style="font-size:.82rem;color:#73879b;cursor:pointer;margin-left:.4rem;">{{ __('auth.remember_me') }}</label>
                        <button type="button" onclick="switchToForgot()" style="margin-left:auto;font-size:.82rem;color:#91dce0;text-decoration:none;background:none;border:none;padding:0;">{{ __('auth.forgot_password_link') }}</button>
                    </div>

                    <button type="submit" class="btn-login-modal">
                        <i class="bi bi-box-arrow-in-right"></i>{{ __('auth.sign_in') }}
                    </button>
                </form>

                <div class="text-center mt-3">
                    <button type="button" onclick="switchToRegister()" style="background:none;border:none;padding:0;font-size:.84rem;color:#91dce0;cursor:pointer;">
                        <i class="bi bi-person-plus"></i> {{ __('auth.create_owner_account') }}
                    </button>
                </div>
            </div>

            <div id="mRegisterPanel" style="display:{{ $initialAuthPanel === 'register' ? 'block' : 'none' }};">
                <h2 style="font-size:1.35rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.3rem;">{{ __('auth.register_heading') }}</h2>
                <p style="font-size:.85rem;color:#73879b;text-align:center;margin-bottom:1rem;">{{ __('auth.register_subtitle') }}</p>

                @if(session('manager_pending'))
                    <div style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);border-radius:10px;padding:.75rem 1rem;color:#fcd34d;font-size:.875rem;display:flex;align-items:flex-start;gap:.5rem;margin-bottom:1rem;">
                        <i class="bi bi-clock-history" style="margin-top:.1rem;flex-shrink:0;"></i>
                        <span>{{ session('manager_pending') }}</span>
                    </div>
                @endif

                @if($errors->any() && old('_form') === 'register')
                    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:.75rem 1rem;color:#fca5a5;font-size:.875rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_form" value="register">

                    {{-- Role selector --}}
                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.55rem;">{{ __('auth.register_as') }}</label>
                        <div style="display:flex;gap:.55rem;">
                            <label style="flex:1;cursor:pointer;">
                                <input type="radio" name="role" value="owner" id="regRoleOwner"
                                    {{ (old('_form') !== 'register' || old('role', 'owner') === 'owner') ? 'checked' : '' }}
                                    style="display:none;" onchange="updateRegisterRole()">
                                <div id="regRoleOwnerBox" style="border:2px solid rgba(15,118,110,.6);border-radius:10px;padding:.65rem .55rem;text-align:center;transition:all .2s ease;background:rgba(15,118,110,.15);">
                                    <i class="bi bi-shop" style="font-size:1.3rem;color:#34d399;display:block;margin-bottom:.25rem;"></i>
                                    <div style="font-size:.8rem;font-weight:700;color:#e2e8f0;">{{ __('auth.role_owner') }}</div>
                                    <div style="font-size:.7rem;color:#73879b;margin-top:.15rem;">{{ __('auth.role_owner_hint') }}</div>
                                </div>
                            </label>
                            <label style="flex:1;cursor:pointer;">
                                <input type="radio" name="role" value="manager" id="regRoleManager"
                                    {{ (old('_form') === 'register' && old('role') === 'manager') ? 'checked' : '' }}
                                    style="display:none;" onchange="updateRegisterRole()">
                                <div id="regRoleManagerBox" style="border:2px solid rgba(100,116,139,.35);border-radius:10px;padding:.65rem .55rem;text-align:center;transition:all .2s ease;background:rgba(100,116,139,.08);">
                                    <i class="bi bi-person-badge" style="font-size:1.3rem;color:#94a3b8;display:block;margin-bottom:.25rem;"></i>
                                    <div style="font-size:.8rem;font-weight:700;color:#e2e8f0;">{{ __('auth.role_manager') }}</div>
                                    <div style="font-size:.7rem;color:#73879b;margin-top:.15rem;">{{ __('auth.role_manager_hint') }}</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.full_name') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-person input-icon-modal"></i>
                            <input type="text" name="name" class="form-ctrl-dark {{ $errors->has('name') && old('_form') === 'register' ? 'is-invalid' : '' }}" placeholder="{{ __('auth.enter_name') }}" value="{{ old('_form') === 'register' ? old('name') : '' }}" required autocomplete="name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.email_address') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-envelope input-icon-modal"></i>
                            <input type="email" name="email" class="form-ctrl-dark {{ $errors->has('email') && old('_form') === 'register' ? 'is-invalid' : '' }}" placeholder="{{ __('auth.enter_email') }}" value="{{ old('_form') === 'register' ? old('email') : '' }}" required autocomplete="email">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.password') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-lock input-icon-modal"></i>
                            <input type="password" id="rPwd" name="password" class="form-ctrl-dark" placeholder="{{ __('auth.enter_password') }}" required autocomplete="new-password">
                            <button type="button" class="toggle-btn-modal" onclick="toggleRPwd()">
                                <i class="bi bi-eye" id="rEye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.confirm_new_password') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-shield-lock input-icon-modal"></i>
                            <input type="password" id="rCPwd" name="password_confirmation" class="form-ctrl-dark" placeholder="{{ __('auth.confirm_new_password') }}" required autocomplete="new-password">
                            <button type="button" class="toggle-btn-modal" onclick="toggleRCPwd()">
                                <i class="bi bi-eye" id="rCEye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Manager note --}}
                    <div id="managerNote" style="display:{{ (old('_form') === 'register' && old('role') === 'manager') ? 'flex' : 'none' }};align-items:flex-start;gap:.5rem;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.28);border-radius:10px;padding:.65rem .85rem;margin-bottom:.85rem;font-size:.82rem;color:#fcd34d;">
                        <i class="bi bi-info-circle-fill" style="margin-top:.05rem;flex-shrink:0;"></i>
                        <span>{{ __('auth.manager_register_note') }}</span>
                    </div>

                    <button type="submit" class="btn-login-modal" id="registerSubmitBtn">
                        <i class="bi bi-person-plus" id="registerBtnIcon"></i>
                        <span id="registerBtnText">{{ __('auth.register_as_owner') }}</span>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <button type="button" onclick="switchToLogin()" style="background:none;border:none;padding:0;font-size:.84rem;color:#91dce0;cursor:pointer;">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.already_have_account') }}
                    </button>
                </div>
            </div>

            <div id="mForgotPanel" style="display:{{ $initialAuthPanel === 'forgot' ? 'block' : 'none' }};">
                <h2 style="font-size:1.35rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.3rem;">{{ __('auth.forgot_password_heading') }}</h2>
                <p style="font-size:.85rem;color:#73879b;text-align:center;margin-bottom:1rem;">{{ __('auth.forgot_password_subtitle') }}</p>

                @if(session('status'))
                    <div style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.28);border-radius:10px;padding:.75rem 1rem;color:#86efac;font-size:.875rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
                        <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                    </div>
                @endif

                @if($errors->any() && old('_form') === 'forgot')
                    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:.75rem 1rem;color:#fca5a5;font-size:.875rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_form" value="forgot">

                    <div class="mb-3">
                        <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem;">{{ __('auth.email_address') }}</label>
                        <div style="position:relative;">
                            <i class="bi bi-envelope input-icon-modal"></i>
                            <input type="email" name="email" class="form-ctrl-dark {{ $errors->has('email') && old('_form') === 'forgot' ? 'is-invalid' : '' }}" placeholder="{{ __('auth.enter_email') }}" value="{{ old('_form') === 'forgot' ? old('email') : '' }}" required autocomplete="email">
                        </div>
                    </div>

                    <button type="submit" class="btn-login-modal">
                        <i class="bi bi-envelope"></i>{{ __('auth.send_reset_link') }}
                    </button>
                </form>

                <div class="text-center mt-3">
                    <button type="button" onclick="switchToLogin()" style="background:none;border:none;padding:0;font-size:.84rem;color:#91dce0;cursor:pointer;">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.back_to_login') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleMPwd() {
        var i = document.getElementById('mPwd');
        var ic = document.getElementById('mEye');
        i.type = i.type === 'password' ? 'text' : 'password';
        ic.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    function toggleRPwd() {
        var i = document.getElementById('rPwd');
        var ic = document.getElementById('rEye');
        i.type = i.type === 'password' ? 'text' : 'password';
        ic.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    function updateRegisterRole() {
        var isManager = document.getElementById('regRoleManager').checked;

        // Highlight active role card
        var ownerBox = document.getElementById('regRoleOwnerBox');
        var managerBox = document.getElementById('regRoleManagerBox');
        if (isManager) {
            ownerBox.style.border = '2px solid rgba(100,116,139,.35)';
            ownerBox.style.background = 'rgba(100,116,139,.08)';
            ownerBox.querySelector('i').style.color = '#94a3b8';
            managerBox.style.border = '2px solid rgba(245,158,11,.6)';
            managerBox.style.background = 'rgba(245,158,11,.12)';
            managerBox.querySelector('i').style.color = '#fcd34d';
            document.getElementById('registerBtnText').textContent = '{{ __('auth.register_as_manager') }}';
            document.getElementById('managerNote').style.display = 'flex';
        } else {
            ownerBox.style.border = '2px solid rgba(15,118,110,.6)';
            ownerBox.style.background = 'rgba(15,118,110,.15)';
            ownerBox.querySelector('i').style.color = '#34d399';
            managerBox.style.border = '2px solid rgba(100,116,139,.35)';
            managerBox.style.background = 'rgba(100,116,139,.08)';
            managerBox.querySelector('i').style.color = '#94a3b8';
            document.getElementById('registerBtnText').textContent = '{{ __('auth.register_as_owner') }}';
            document.getElementById('managerNote').style.display = 'none';
        }
    }

    // Run on page load to set correct initial state
    document.addEventListener('DOMContentLoaded', function () {
        updateRegisterRole();
    });

    function toggleRCPwd() {
        var i = document.getElementById('rCPwd');
        var ic = document.getElementById('rCEye');
        i.type = i.type === 'password' ? 'text' : 'password';
        ic.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    function showAuthPanel(panel) {
        document.getElementById('mLoginPanel').style.display = panel === 'login' ? 'block' : 'none';
        document.getElementById('mRegisterPanel').style.display = panel === 'register' ? 'block' : 'none';
        document.getElementById('mForgotPanel').style.display = panel === 'forgot' ? 'block' : 'none';
    }

    function switchToRegister() {
        showAuthPanel('register');
    }

    function switchToForgot() {
        showAuthPanel('forgot');
    }

    function switchToLogin() {
        showAuthPanel('login');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var shouldOpenAuthModal = @json($shouldOpenAuthModal ?? false);
        var initialAuthPanel = @json($initialAuthPanel ?? 'login');
        var hasLoginOldInput = @json($hasLoginOldInput ?? false);

        showAuthPanel(initialAuthPanel);

        var loginModalEl = document.getElementById('loginModal');
        if (loginModalEl) {
            loginModalEl.addEventListener('shown.bs.modal', function () {
                if (!hasLoginOldInput) {
                    var loginEmail = loginModalEl.querySelector('input[name="email"]');
                    var loginPassword = document.getElementById('mPwd');

                    if (loginEmail) {
                        loginEmail.value = '';
                    }

                    if (loginPassword) {
                        loginPassword.value = '';
                    }
                }
            });
        }

        if (shouldOpenAuthModal) {
            new bootstrap.Modal(loginModalEl).show();
        }
    });
</script>
</body>
</html>
