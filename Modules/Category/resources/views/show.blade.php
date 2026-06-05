@extends('layouts.app')

@section('title', __('category::category.show_title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    .category-show-shell {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .show-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
    }

    .page-title {
        font-size: clamp(1.45rem, 3vw, 2.05rem);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid #d8e4ee;
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        padding: 1.2rem;
    }

    .info-grid {
        display: grid;
        gap: 0.9rem;
    }

    .info-item {
        background: #f8fbff;
        border: 1px solid #dce8f3;
        border-radius: 12px;
        padding: 0.85rem 0.95rem;
    }

    .info-label {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .info-value {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
    }

    .btn-edit {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-edit:hover {
        color: #fff;
    }

    .btn-back {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }
</style>
@endpush

@section('content')
<div class="category-show-shell">
<div class="mb-4">
    <span class="show-kicker"><i class="bi bi-tag"></i>{{ __('category::category.show_title') }}</span>
    <h1 class="page-title display-font">{{ __('category::category.show_title') }}</h1>
</div>

<div class="content-card">
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">{{ __('category::category.name') }}</div>
            <div class="info-value">{{ $category->name }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">{{ __('category::category.product_count') }}</div>
            <div class="info-value">{{ $category->products_count }}</div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('category.edit', $category->id) }}" class="btn-edit">{{ __('app.edit') }}</a>
        <a href="{{ route('category.index') }}" class="btn-back">{{ __('category::category.back') }}</a>
    </div>
</div>
</div>
@endsection
