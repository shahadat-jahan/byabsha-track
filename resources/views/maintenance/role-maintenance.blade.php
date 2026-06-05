<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __("app.maintenance_title") }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse at top right, #1e3a8a 0%, #0f172a 45%, #020617 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            padding: 1.5rem;
        }
        .card-wrap {
            width: 100%;
            max-width: 520px;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
            text-align: center;
            backdrop-filter: blur(16px);
        }
        .icon-wrap {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(245,158,11,0.05));
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2.5s infinite;
        }
        .icon-wrap i { font-size: 2rem; color: #fbbf24; }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0.25); }
            50%       { box-shadow: 0 0 0 16px rgba(245,158,11,0); }
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 1rem;
            border-radius: 999px;
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fcd34d;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }
        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #fbbf24;
            animation: blink 1.4s infinite;
            flex-shrink: 0;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }
        p {
            color: #94a3b8;
            font-size: 0.95rem;
            line-height: 1.65;
            margin-bottom: 0;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(148,163,184,0.12);
            margin: 2rem 0;
        }
        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.75rem;
            border-radius: 50px;
            background: transparent;
            border: 1px solid rgba(148,163,184,0.3);
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s;
        }
        .btn-logout:hover {
            background: rgba(148,163,184,0.08);
            border-color: rgba(148,163,184,0.5);
            color: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="card-wrap">
        <div class="icon-wrap">
            <i class="bi bi-tools"></i>
        </div>
        <div class="badge-pill">
            <span class="dot"></span>
            {{ __("app.maintenance_badge") }}
        </div>
        <h1>{{ __("app.maintenance_heading") }}</h1>
        <p>{{ __("app.maintenance_message") }}</p>
        <hr class="divider">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                {{ __("app.logout") }}
            </button>
        </form>
    </div>
</body>
</html>
