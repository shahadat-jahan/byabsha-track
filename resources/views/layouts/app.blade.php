<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track')) - Business Tracking System</title>
    <link rel="icon" href="{{ \Modules\Settings\Models\Setting::get('dashboard_favicon') ?: asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 68px;
            --bg: #f4f8fb;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --brand: {{ \Modules\Settings\Models\Setting::get('dashboard_theme_color', '#0f766e') }};
            --brand-deep: color-mix(in srgb, var(--brand) 80%, #000000);
            --line: #d8e4ee;
            --primary-color: var(--brand);
            --primary-dark: var(--brand-deep);
            --sidebar-bg: linear-gradient(180deg, var(--brand) 0%, color-mix(in srgb, var(--brand) 80%, #000000) 52%, color-mix(in srgb, var(--brand) 60%, #000000) 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.14);
            --text-muted: var(--ink-500);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background:
                radial-gradient(900px 500px at 85% -5%, color-mix(in srgb, var(--brand) 15%, transparent), transparent 60%),
                radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
                linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
            color: var(--ink-900);
            overflow-x: hidden;
        }

        .display-font {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            letter-spacing: -0.03em;
        }

        /* Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: rgba(250, 252, 254, 0.9);
            border-bottom: 1px solid rgba(100, 116, 139, 0.17);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.14rem;
            font-weight: 800;
            color: var(--ink-900);
            text-decoration: none;
            margin-right: 2rem;
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

        .header-brand:hover {
            color: var(--ink-900);
        }

        .active-shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            background-color: #f0fdfa;
            border: 1px solid #ccfbf1;
            color: #0f766e;
            font-size: 0.8rem;
            font-weight: 700;
            margin-left: 0.5rem;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.05);
        }
        @media (max-width: 576px) {
            .active-shop-badge {
                display: none !important;
            }
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-time {
            color: var(--ink-700);
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.42rem 0.8rem;
            border-radius: 999px;
            background: color-mix(in srgb, var(--brand) 9%, transparent);
            border: 1px solid color-mix(in srgb, var(--brand) 18%, transparent);
        }

        .header-user {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--ink-700);
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0.35rem 0.4rem 0.35rem 0.7rem;
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
            transition: background .2s, color .2s, box-shadow .2s;
            white-space: nowrap;
            letter-spacing: .01em;
        }
        .lang-btn:hover:not(.active) {
            background: #d8e5f1;
            color: #0f172a;
        }
        .lang-btn.active {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 1px 4px color-mix(in srgb, var(--brand) 30%, transparent);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 10px;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height) - 10px);
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
            border: 1px solid rgba(8, 82, 76, 0.75);
            border-radius: 18px;
            box-shadow: 0 20px 35px rgba(6, 54, 50, 0.28);
            backdrop-filter: blur(8px);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.34);
            border-radius: 3px;
        }

        .sidebar-nav {
            padding: 1.2rem 0;
        }

        .nav-section-title {
            color: rgba(214, 245, 237, 0.82);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.66rem 1rem;
            margin: 0.2rem 0.75rem;
            border-radius: 12px;
            color: rgba(240, 255, 251, 0.9);
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .nav-link-custom i {
            font-size: 1.125rem;
            width: 20px;
        }

        .nav-link-custom:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link-custom.active {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow: inset 3px 0 0 #ffffff;
        }

        /* Submenu Styles */
        .nav-item-submenu {
            position: relative;
        }

        .nav-link-parent {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.66rem 1rem;
            margin: 0.2rem 0.75rem;
            border-radius: 12px;
            color: rgba(240, 255, 251, 0.9);
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
            justify-content: space-between;
        }

        .nav-link-parent:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link-parent.active,
        .nav-link-parent[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow: inset 3px 0 0 #ffffff;
        }

        .nav-link-parent .left-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link-parent i.bi-chevron-down {
            font-size: 0.875rem;
            transition: transform 0.2s;
        }

        .nav-link-parent[aria-expanded="true"] i.bi-chevron-down {
            transform: rotate(180deg);
        }

        .submenu {
            background: rgba(7, 67, 62, 0.38);
            overflow: hidden;
            border-radius: 12px;
            margin: 0.1rem 0.75rem;
        }

        .submenu .nav-link-custom {
            margin: 0.16rem 0.4rem;
            padding-left: 2.4rem;
            font-size: 0.9rem;
        }

        .submenu .nav-link-custom i {
            font-size: 1rem;
        }

        .submenu-group-title {
            font-size: 0.75rem;
            color: rgba(240,255,251,0.6);
            padding: 0.5rem 1.1rem;
            margin-top: 0.6rem;
            margin-bottom: 0.1rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: calc(var(--sidebar-width) + 10px);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #41556a;
            cursor: pointer;
            margin-right: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                left: 0;
                height: calc(100vh - var(--header-height));
                border-radius: 0 18px 18px 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .header-time {
                display: none;
            }
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            margin-right: 1rem;
        }

        .notification-bell .btn {
            position: relative;
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid var(--line);
            font-size: 1.25rem;
            color: #49627a;
            padding: 0.42rem 0.58rem;
            cursor: pointer;
            border-radius: 999px;
        }

        .notification-bell .btn:hover {
            color: var(--primary-color);
            background: #ffffff;
            border-color: #bfd3e4;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 380px;
            max-height: 500px;
            background: #fdfefe;
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.14);
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-dropdown-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #dde7f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: color-mix(in srgb, var(--brand) 6%, transparent);
        }

        .notification-dropdown-header h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #102438;
        }

        .notification-dropdown-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #edf3f8;
            display: flex;
            gap: 0.75rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: #f1f7fb;
        }

        .notification-item.unread {
            background: #e8f4fb;
        }

        .notification-item.unread:hover {
            background: #dcedf8;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #102438;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #70879d;
        }

        .notification-dropdown-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #dde7f0;
            text-align: center;
        }

        .notification-dropdown-footer a {
            font-size: 0.875rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .notification-dropdown-footer a:hover {
            text-decoration: underline;
        }

        .notification-empty {
            padding: 3rem 1.25rem;
            text-align: center;
            color: #70879d;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Page Title */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #102438;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .btn-outline-primary {
            --bs-btn-color: var(--brand);
            --bs-btn-border-color: var(--brand);
            --bs-btn-hover-bg: var(--brand);
            --bs-btn-hover-border-color: var(--brand);
            --bs-btn-active-bg: var(--brand-deep);
            --bs-btn-active-border-color: var(--brand-deep);
            --bs-btn-disabled-color: var(--brand);
            --bs-btn-disabled-border-color: var(--brand);
        }

        .btn-outline-danger {
            --bs-btn-color: #b91c1c;
            --bs-btn-border-color: #f0b3b3;
            --bs-btn-hover-bg: #b91c1c;
            --bs-btn-hover-border-color: #b91c1c;
        }

        /* Fix style collision between Bootstrap 5 collapse/collapsing and Tailwind CSS v4 collapse utility */
        .collapse:not(.show) {
            display: none !important;
        }
        .collapse.show {
            visibility: visible !important;
            display: block !important;
        }
        .collapsing {
            visibility: visible !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ url('/') }}" class="header-brand">
            @if(\Modules\Settings\Models\Setting::get('dashboard_logo'))
                <img src="{{ asset(\Modules\Settings\Models\Setting::get('dashboard_logo')) }}" alt="Logo" style="height: 38px; max-width: 120px; object-fit: contain;" class="me-1">
            @else
                <span class="brand-chip">
                    <i class="bi bi-graph-up-arrow"></i>
                </span>
            @endif
            <span class="display-font">{{ \Modules\Settings\Models\Setting::get('app_name', 'Byabsha Track') }}</span>
        </a>
        @php
            $activeShopIdForHeader = app(\App\Services\ShopContext::class)->getActiveShopId();
            $activeShopForHeader = $activeShopIdForHeader ? \Modules\Shop\Models\Shop::find($activeShopIdForHeader) : null;
        @endphp
        @if($activeShopForHeader)
            <div class="active-shop-badge">
                <i class="bi bi-shop text-teal-600"></i>
                <span>{{ $activeShopForHeader->name }}</span>
            </div>
        @endif
        <div class="header-right">
            <span class="header-time">
                <i class="bi bi-calendar3"></i>
                <span id="currentDate"></span>
            </span>

            <!-- Notification Bell -->
            <div class="notification-bell">
                <button class="btn" id="notificationBell" type="button">
                    <i class="bi bi-bell-fill"></i>
                    @if(auth()->user()->unreadNotificationsCount() > 0)
                        <span class="notification-badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
                    @endif
                </button>

                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-header">
                        <h6>{{ __('notifications.title') }}</h6>
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-primary p-0" style="font-size: 0.8rem;">
                                {{ __('notifications.mark_all_read') }}
                            </button>
                        </form>
                    </div>
                    <div class="notification-dropdown-body" id="notificationList">
                        @php
                            $recentNotifications = auth()->user()->notifications()->latest()->limit(5)->get();
                        @endphp

                        @forelse($recentNotifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}"
                               class="notification-item {{ $notification->isUnread() ? 'unread' : '' }}">
                                <i class="{{ $notification->icon }} notification-icon"></i>
                                <div class="notification-content">
                                    <div class="notification-title">{{ $notification->title }}</div>
                                    <div class="notification-message">{{ $notification->message }}</div>
                                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p class="mb-0">{{ __('notifications.no_notifications') }}</p>
                            </div>
                        @endforelse
                    </div>
                    @if($recentNotifications->count() > 0)
                        <div class="notification-dropdown-footer">
                            <a href="{{ route('notifications.index') }}">{{ __('notifications.view_all') }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Language Switcher -->
            <div class="lang-switcher me-2">
                <a href="{{ route('language.switch', 'en') }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'bn') }}"
                   class="lang-btn {{ app()->getLocale() === 'bn' ? 'active' : '' }}">বাংলা</a>
            </div>
            <div class="header-user ms-1">
                <i class="bi bi-person-circle me-1"></i>
                <span class="me-2">{{ auth()->user()->name ?? 'User' }}</span>
                <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-person-gear"></i> {{ __('user.profile_title') }}
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> {{ __('app.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            @php $sidebarUser = auth()->user(); @endphp
            <div class="nav-section-title">{{ __('app.main_menu') }}</div>
            @if($sidebarUser->hasModuleAccess('dashboard'))
                <a href="{{ route('dashboard.index') }}" class="nav-link-custom {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>{{ __('app.dashboard') }}</span>
                </a>
            @endif
            <a href="{{ route('user.profile.edit') }}" class="nav-link-custom {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>{{ __('user.profile_title') }}</span>
            </a>
            @if(!$sidebarUser->isSuperAdmin())
            <a href="{{ route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('subscription.*') ? 'active' : '' }}">
                <i class="bi bi-stars"></i>
                <span>{{ __('app.subscription') }}</span>
                @php
                    $activeSub = $sidebarUser->activeSubscription();
                    $subPlan = $activeSub?->plan;
                @endphp
                @if($subPlan && !$subPlan->isFree())
                    <span class="ms-auto badge" style="background: rgba(255,255,255,0.22); font-size: 0.65rem; border-radius: 999px; letter-spacing: 0.03em;">{{ $subPlan->name }}</span>
                @endif
            </a>
            @endif

            {{-- Setup submenu --}}
            @php
                $isSetupOpen = request()->routeIs('shop.*') || request()->routeIs('branch.*') || request()->routeIs('brand.*') || request()->routeIs('category.*');
            @endphp
            <div class="nav-item-submenu">
                <a class="nav-link-parent {{ $isSetupOpen ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#setupSubmenu"
                   role="button"
                   aria-expanded="{{ $isSetupOpen ? 'true' : 'false' }}"
                   aria-controls="setupSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear-wide-connected"></i>
                        <span>{{ __('app.setup') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu {{ $isSetupOpen ? 'show' : '' }}" id="setupSubmenu">
                    <a href="{{ $sidebarUser->hasModuleAccess('shop') ? route('shop.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('shop.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('shop') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('shop') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-shop"></i>
                        <span>{{ __('app.shops') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('shop'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    <a href="{{ $sidebarUser->hasModuleAccess('branch') ? route('branch.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('branch.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('branch') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('branch') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-diagram-3"></i>
                        <span>{{ __('app.branches') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('branch'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    <a href="{{ $sidebarUser->hasModuleAccess('brand') ? route('brand.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('brand.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('brand') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('brand') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-bookmark-star"></i>
                        <span>{{ __('app.brands') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('brand'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    <a href="{{ $sidebarUser->hasModuleAccess('category') ? route('category.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('category.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('category') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('category') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-tags"></i>
                        <span>{{ __('app.categories') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('category'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Inventory submenu --}}
            @php
                $isInventoryOpen = request()->routeIs('product.*') || request()->routeIs('product.dynamic-fields.*') || request()->routeIs('stock.*');
            @endphp
            <div class="nav-item-submenu">
                <a class="nav-link-parent {{ $isInventoryOpen ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#inventorySubmenu"
                   role="button"
                   aria-expanded="{{ $isInventoryOpen ? 'true' : 'false' }}"
                   aria-controls="inventorySubmenu">
                    <div class="left-content">
                        <i class="bi bi-boxes"></i>
                        <span>{{ __('app.inventory') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu {{ $isInventoryOpen ? 'show' : '' }}" id="inventorySubmenu">
                    <a href="{{ $sidebarUser->hasModuleAccess('product') ? route('product.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('product.*') && !request()->routeIs('product.dynamic-fields.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('product') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('product') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-box-seam"></i>
                        <span>{{ __('app.products') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('product'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    @if($sidebarUser->hasModuleAccess('product') && $sidebarUser->canUseProductAttributes())
                        <a href="{{ route('product.dynamic-fields.index') }}" class="nav-link-custom {{ request()->routeIs('product.dynamic-fields.*') ? 'active' : '' }}">
                            <i class="bi bi-sliders"></i>
                            <span>{{ __('app.product_attributes') }}</span>
                        </a>
                    @else
                        <a href="{{ route('subscription.plans') }}" class="nav-link-custom opacity-75" title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip">
                            <i class="bi bi-sliders"></i>
                            <span>{{ __('app.product_attributes') }}</span>
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        </a>
                    @endif
                    <a href="{{ $sidebarUser->hasModuleAccess('stock') ? route('stock.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('stock.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('stock') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('stock') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-boxes"></i>
                        <span>{{ __('app.stocks') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('stock'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Operations submenu --}}
            @php
                $isOperationsOpen = request()->routeIs('sale.*') || request()->routeIs('capital.*') || request()->routeIs('restock.*') || request()->routeIs('damage.*');
            @endphp
            <div class="nav-item-submenu">
                <a class="nav-link-parent {{ $isOperationsOpen ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#operationsSubmenu"
                   role="button"
                   aria-expanded="{{ $isOperationsOpen ? 'true' : 'false' }}"
                   aria-controls="operationsSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear-fill"></i>
                        <span>{{ __('app.operations') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu {{ $isOperationsOpen ? 'show' : '' }}" id="operationsSubmenu">
                    <a href="{{ $sidebarUser->hasModuleAccess('sale') ? route('sale.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('sale.*') && !request()->routeIs('sale.warranties.*') && !request()->routeIs('sale.exchanges.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('sale') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('sale') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-cart-check"></i>
                        <span>{{ __('app.sales') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('sale'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    @if($sidebarUser->hasModuleAccess('sale'))
                        <a href="{{ route('sale.warranties.index') }}" class="nav-link-custom {{ request()->routeIs('sale.warranties.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i>
                            <span>{{ __('sale.warranty_title') }}</span>
                        </a>
                        <a href="{{ route('sale.exchanges.index') }}" class="nav-link-custom {{ request()->routeIs('sale.exchanges.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>{{ __('sale.exchange_title') }}</span>
                        </a>
                    @endif
                    <a href="{{ $sidebarUser->hasModuleAccess('capital') ? route('capital.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('capital.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('capital') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('capital') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-cash-coin"></i>
                        <span>{{ __('app.capitals') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('capital'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    <a href="{{ $sidebarUser->hasModuleAccess('restock') ? route('restock.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('restock.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('restock') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('restock') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-arrow-repeat"></i>
                        <span>{{ __('app.restocks') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('restock'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                    <a href="{{ $sidebarUser->hasModuleAccess('damage') ? route('damage.index') : route('subscription.plans') }}" class="nav-link-custom {{ request()->routeIs('damage.*') ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('damage') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('damage') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>{{ __('app.damages') }}</span>
                        @if(!$sidebarUser->hasModuleAccess('damage'))
                            <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                        @endif
                    </a>
                </div>
            </div>

            @if($sidebarUser->isSuperAdmin())
            <div class="nav-section-title">{{ __('app.system') }}</div>
            @php
                $isUserManagementRoute = request()->routeIs('user.index')
                    || request()->routeIs('user.create')
                    || request()->routeIs('user.store')
                    || request()->routeIs('user.show')
                    || request()->routeIs('user.edit')
                    || request()->routeIs('user.update')
                    || request()->routeIs('user.destroy')
                    || request()->routeIs('user.restore')
                    || request()->routeIs('user.force-delete');
                $isSettingsMenuOpen = request()->routeIs('settings.*') || $isUserManagementRoute || request()->routeIs('admin.subscriptions.*');
            @endphp

            <!-- Settings Submenu -->
            <div class="nav-item-submenu">
                <a class="nav-link-parent {{ $isSettingsMenuOpen ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#settingsSubmenu"
                   role="button"
                   aria-expanded="{{ $isSettingsMenuOpen ? 'true' : 'false' }}"
                   aria-controls="settingsSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear"></i>
                        <span>{{ __('app.settings') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu {{ $isSettingsMenuOpen ? 'show' : '' }}" id="settingsSubmenu">
                    <a href="{{ route('settings.dashboard') }}" class="nav-link-custom {{ request()->routeIs('settings.dashboard') || request()->routeIs('settings.index') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>{{ __('settings.dashboard_settings') }}</span>
                    </a>
                    <a href="{{ route('settings.system') }}" class="nav-link-custom {{ request()->routeIs('settings.system') ? 'active' : '' }}">
                        <i class="bi bi-cpu"></i>
                        <span>{{ __('settings.system_settings') }}</span>
                    </a>
                    <a href="{{ route('user.index') }}" class="nav-link-custom {{ $isUserManagementRoute ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>{{ __('app.users') }}</span>
                    </a>
                    <a href="{{ route('admin.subscriptions.index') }}" class="nav-link-custom {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>{{ __('app.subscriptions') }}</span>
                        @php $pendingSubCount = \Modules\Subscription\Models\PaymentRequest::where('status','pending')->count(); @endphp
                        @if($pendingSubCount > 0)
                            <span class="ms-auto badge bg-warning text-dark" style="font-size: 0.65rem; border-radius: 999px;">{{ $pendingSubCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
            @endif

            <div class="nav-section-title">{{ __('app.analytics') }}</div>
            <a href="{{ $sidebarUser->hasModuleAccess('report') ? route('report.index') : route('subscription.plans') }}" class="nav-link-custom {{ (request()->routeIs('report.index') || request()->routeIs('report.sales') || request()->routeIs('report.products') || request()->routeIs('report.shops')) ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('report') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('report') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                <i class="bi bi-bar-chart-line"></i>
                <span>{{ __('app.reports') }}</span>
                @if(!$sidebarUser->hasModuleAccess('report'))
                    <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                @endif
            </a>
            <a href="{{ $sidebarUser->hasModuleAccess('report') ? route('report.daily') : route('subscription.plans') }}" class="nav-link-custom {{ (request()->routeIs('report.daily') || request()->routeIs('report.export.daily-pdf')) ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('report') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('report') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                <i class="bi bi-calendar-check"></i>
                <span>{{ __('app.daily_pnl') }}</span>
                @if(!$sidebarUser->hasModuleAccess('report'))
                    <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                @endif
            </a>
            <a href="{{ $sidebarUser->hasModuleAccess('report') ? route('report.monthly') : route('subscription.plans') }}" class="nav-link-custom {{ (request()->routeIs('report.monthly') || request()->routeIs('report.export.monthly-pdf')) ? 'active' : '' }} {{ !$sidebarUser->hasModuleAccess('report') ? 'opacity-75' : '' }}" {!! !$sidebarUser->hasModuleAccess('report') ? 'title="Available on paid plans. Click to upgrade." data-bs-toggle="tooltip"' : '' !!}>
                <i class="bi bi-calendar-range"></i>
                <span>{{ __('app.monthly_pnl') }}</span>
                @if(!$sidebarUser->hasModuleAccess('report'))
                    <i class="bi bi-lock-fill ms-auto text-muted" style="font-size: 0.85rem;"></i>
                @endif
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $activeShopId = app(\App\Services\ShopContext::class)->getActiveShopId();
            $shopForBanner = $activeShopId ? \Modules\Shop\Models\Shop::find($activeShopId) : null;
            $subForBanner = $shopForBanner?->activeSubscription;
        @endphp

        @if($subForBanner)
            @if($subForBanner->inGracePeriod())
                <div class="alert alert-warning alert-dismissible fade show rounded-3 d-flex align-items-center justify-content-between p-3 mb-4" role="alert" style="border-left: 4px solid #d97706; background-color: #fffbeb; color: #92400e;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                        <span>Your subscription for <strong>{{ $shopForBanner->name }}</strong> expired on {{ $subForBanner->ends_at->format('d M Y') }}. You are currently in a grace period. Please renew to avoid service lockout.</span>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-warning btn-sm fw-bold rounded-pill px-3 py-1.5 ms-3 flex-shrink-0" style="background-color: #d97706; border-color: #d97706; color: #fff;">Renew Now</a>
                </div>
            @elseif($subForBanner->isExpiringSoon())
                <div class="alert alert-info alert-dismissible fade show rounded-3 d-flex align-items-center justify-content-between p-3 mb-4" role="alert" style="border-left: 4px solid #0284c7; background-color: #f0f9ff; color: #0369a1;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-info fs-5"></i>
                        <span>Your subscription for <strong>{{ $shopForBanner->name }}</strong> is expiring on {{ $subForBanner->ends_at->format('d M Y') }} ({{ $subForBanner->ends_at->diffForHumans() }}). Please renew to avoid interruption.</span>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-info btn-sm text-white fw-bold rounded-pill px-3 py-1.5 ms-3 flex-shrink-0" style="background-color: #0284c7; border-color: #0284c7;">Renew Now</a>
                </div>
            @endif
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Update current date
        function updateDate() {
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                dateElement.textContent = new Date().toLocaleDateString('en-US', options);
            }
        }
        updateDate();

        // Notification bell dropdown
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBell && notificationDropdown) {
            notificationBell.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });
        }

        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
    @stack('scripts')
</body>
</html>
