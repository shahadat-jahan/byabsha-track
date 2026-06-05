@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-stars me-2"></i>Subscription Plans</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-primary">
                <i class="bi bi-gear-fill me-1"></i>Manage Plans
            </a>
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Plan
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Limits</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $plan->name }}</div>
                                    <div class="text-muted small">{{ $plan->description }}</div>
                                </td>
                                <td>{{ $plan->slug }}</td>
                                <td>{{ $plan->isFree() ? 'Free' : number_format($plan->price) . ' / ' . $plan->billing_cycle }}</td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    Shops: {{ $plan->getLimit('shops') ?? 'Unlimited' }}<br>
                                    Brands: {{ $plan->getLimit('brands') ?? 'Unlimited' }}<br>
                                    Categories: {{ $plan->getLimit('categories') ?? 'Unlimited' }}<br>
                                    Products: {{ $plan->getLimit('products') ?? 'Unlimited' }}<br>
                                    Sales: {{ $plan->getLimit('sales') ?? 'Unlimited' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No plans found. Create one to make it available in the shop owner panel.
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
