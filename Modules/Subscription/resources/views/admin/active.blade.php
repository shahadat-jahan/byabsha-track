@extends('layouts.app')

@section('title', 'Active Subscriptions')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-0"><i class="bi bi-check2-circle me-2"></i>Active Subscriptions</h4>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-grid-3x3-gap me-1"></i>Subscription Plans
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-wallet2 me-1"></i>Payment Requests
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-warning">{{ $counts['pending'] }}</div><div class="small text-muted">Pending Payments</div></div></div>
        <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-success">{{ $counts['approved'] }}</div><div class="small text-muted">Approved</div></div></div>
        <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-danger">{{ $counts['rejected'] }}</div><div class="small text-muted">Rejected</div></div></div>
    </div>

    <form method="GET" action="{{ route('admin.subscriptions.active') }}" class="card border-0 shadow-sm mb-3 p-3">
        <div class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label small mb-1">Search User</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ $search }}">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1">Shop</label>
                <select name="shop_id" id="filter-shop-id" class="form-select form-select-sm">
                    <option value="">All Shops</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}"
                            data-branches="{{ $shop->branches->map(fn($b) => ['id' => $b->id, 'name' => $b->name])->toJson() }}"
                            {{ $shopId == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1">Branch</label>
                <select name="branch_id" id="filter-branch-id" class="form-select form-select-sm">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-funnel-fill"></i> Filter</button>
                <a href="{{ route('admin.subscriptions.active') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}">Pending @if($counts['pending'] > 0)<span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>@endif</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.subscriptions.index', ['status' => 'approved']) }}">Approved</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.subscriptions.index', ['status' => 'rejected']) }}">Rejected</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('admin.subscriptions.active') }}"><i class="bi bi-check2-circle me-1"></i>Active Subscriptions</a></li>
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($subscriptions->isEmpty())
                <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No active subscriptions found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Shop</th>
                                <th>Branch</th>
                                <th>Plan</th>
                                <th>Started</th>
                                <th>Expires</th>
                                <th>Days Left</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $sub)
                                @php
                                    $daysLeft = $sub->ends_at ? now()->diffInDays($sub->ends_at, false) : null;
                                    $badgeClass = $daysLeft === null ? 'secondary' : ($daysLeft <= 7 ? 'danger' : ($daysLeft <= 30 ? 'warning' : 'success'));
                                @endphp
                                <tr>
                                    <td class="text-muted small">{{ $sub->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $sub->user->name }}</div>
                                        <div class="text-muted small">{{ $sub->user->email }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary text-capitalize">{{ $sub->user->role }}</span></td>
                                    <td>{{ $sub->shop?->name ?? '—' }}</td>
                                    <td>{{ $sub->branch?->name ?? '—' }}</td>
                                    <td>{{ $sub->plan->name }}</td>
                                    <td class="small text-muted">{{ $sub->starts_at?->format('d M Y') ?? '—' }}</td>
                                    <td class="small">{{ $sub->ends_at?->format('d M Y') ?? 'Lifetime' }}</td>
                                    <td>
                                        @if($daysLeft !== null)
                                            <span class="badge bg-{{ $badgeClass }}">{{ (int) $daysLeft }}d</span>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $subscriptions->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var shopSel = document.getElementById('filter-shop-id');
    var branchSel = document.getElementById('filter-branch-id');
    if (!shopSel || !branchSel) return;

    shopSel.addEventListener('change', function () {
        var opt = shopSel.options[shopSel.selectedIndex];
        var branches = opt.dataset.branches ? JSON.parse(opt.dataset.branches) : [];
        branchSel.innerHTML = '<option value="">All Branches</option>';

        branches.forEach(function (branch) {
            var option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.name;
            branchSel.appendChild(option);
        });
    });
})();
</script>
@endpush
@endsection
