@extends('layouts.app')

@section('title', __('shop.show_title'))

@push('styles')
<style>
    .btn-shop-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-shop-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 28px rgba(15, 118, 110, 0.28);
    }

    .shop-products-card .content-card-header {
        padding: 1rem 1.2rem;
    }

    .shop-products-card .content-card-title {
        font-size: 0.92rem;
    }

    .shop-products-card .table-responsive {
        padding: 0 1.2rem 1.2rem;
    }

    .shop-products-table thead th {
        white-space: nowrap;
    }

    .shop-products-table tbody td {
        vertical-align: middle;
    }

    .shop-products-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: #475569;
    }

    .shop-products-empty i {
        display: block;
        margin-bottom: 0.55rem;
        font-size: 2rem;
        color: #94a3b8;
    }

    .shop-products-empty strong {
        display: block;
        font-size: 0.96rem;
        margin-bottom: 0.2rem;
    }

    .shop-branch-item {
        border: 1px solid #e7edf4;
        border-radius: 14px;
        padding: 0.85rem 0.95rem;
        background: #fbfdff;
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ $shop->name }}</h1>
        <p class="page-subtitle">{{ __('shop.show_subtitle') }}</p>
    </div>
    <div>
        <a href="{{ route('shop.edit', $shop->id) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> {{ __('app.edit') }}
        </a>
        <a href="{{ route('branch.index', ['shop_id' => $shop->id]) }}" class="btn btn-primary me-2">
            <i class="bi bi-diagram-3"></i> {{ __('shop.manage_branches') }}
        </a>
        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Shop Info Card -->
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-info-circle"></i>
                    {{ __('shop.shop_information') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.shop_name') }}</label>
                    <p class="mb-0 fw-semibold">{{ $shop->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.location') }}</label>
                    <p class="mb-0">{{ $shop->location ?: '-' }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.address') }}</label>
                    <p class="mb-0">{{ $shop->address ?: '-' }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.created_date') }}</label>
                    <p class="mb-0">{{ $shop->created_at->format('F d, Y') }}</p>
                </div>
                <div class="mb-0">
                    <label class="text-muted small">{{ __('shop.last_updated') }}</label>
                    <p class="mb-0">{{ $shop->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-bar-chart"></i>
                    {{ __('shop.statistics') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #2563eb;">{{ $shop->products_count }}</h3>
                            <small class="text-muted">{{ __('shop.col_products') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #10b981;">{{ $shop->sales_count }}</h3>
                            <small class="text-muted">{{ __('shop.col_sales') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #0f766e;">{{ $shop->branches_count }}</h3>
                            <small class="text-muted">{{ __('shop.branches_count') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card mt-4">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="content-card-title">
                    <i class="bi bi-diagram-3"></i>
                    {{ __('shop.branches') }}
                </h5>
                <a href="{{ route('branch.create', ['shop_id' => $shop->id]) }}" class="btn btn-sm btn-shop-theme">
                    <i class="bi bi-plus-circle"></i> {{ __('shop.add_branch') }}
                </a>
            </div>
            <div class="p-4">
                @forelse($shop->branches as $branch)
                    <div class="shop-branch-item mb-2 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <div class="fw-semibold">{{ $branch->name }}</div>
                            <div class="small text-muted">{{ $branch->location ?: '-' }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $branch->is_active ? __('shop.active') : __('shop.inactive') }}
                            </span>
                            <a href="{{ route('branch.show', $branch->id) }}" class="btn btn-sm btn-outline-info">{{ __('app.view') }}</a>
                        </div>
                    </div>
                @empty
                    <div class="shop-products-empty">
                        <i class="bi bi-diagram-3"></i>
                        <strong>{{ __('shop.no_branches') }}</strong>
                        <p class="mb-0 small text-muted">{{ __('shop.no_branches_sub') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="col-md-8">
        <div class="content-card shop-products-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('shop.recent_products') }}
                </h5>
                <a href="{{ route('product.create') }}?shop_id={{ $shop->id }}" class="btn btn-sm btn-shop-theme">
                    <i class="bi bi-plus-circle"></i> {{ __('shop.add_product') }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom shop-products-table">
                    <thead>
                        <tr>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('product.category') }}</th>
                            <th>{{ __('product.brand') }}</th>
                            <th>{{ __('product.purchase_price') }}</th>
                            <th>{{ __('product.sale_price') }}</th>
                            <th>{{ __('product.stock') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shop->products as $product)
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ currency_symbol() }}{{ number_format($product->purchase_price, 2) }}</td>
                            <td>{{ currency_symbol() }}{{ number_format($product->sale_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity < 10 ? 'bg-danger' : 'bg-info' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="shop-products-empty">
                                    <i class="bi bi-box"></i>
                                    <strong>{{ __('shop.no_products') }}</strong>
                                    <p class="mb-0 small text-muted">{{ __('shop.add_product') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
