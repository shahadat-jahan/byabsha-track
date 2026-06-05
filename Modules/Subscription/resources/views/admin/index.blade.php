@extends('layouts.app')

@section('title', 'Subscription Payment Requests')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;">
                <i class="bi bi-wallet2 me-2" style="color: #0f766e;"></i>Payment Requests
            </h2>
            <p style="color: #64748b; font-size: 0.95rem;">Review and verify manual bKash payments submitted by shop owners</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-grid-3x3-gap me-1"></i>Subscription Plans
            </a>
            <a href="{{ route('admin.subscriptions.active') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
                <i class="bi bi-check2-circle me-1"></i>Active Subscriptions
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm p-3 h-100" style="background: linear-gradient(135deg, #fff 0%, #fffbfa 100%); border-left: 4px solid #f59e0b !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.72rem;">Pending Review</div>
                        <div class="fs-2 fw-black text-warning tracking-tight">{{ $counts['pending'] }}</div>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm p-3 h-100" style="background: linear-gradient(135deg, #fff 0%, #f7fff9 100%); border-left: 4px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.72rem;">Approved payments</div>
                        <div class="fs-2 fw-black text-success tracking-tight">{{ $counts['approved'] }}</div>
                    </div>
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-check2-all fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm p-3 h-100" style="background: linear-gradient(135deg, #fff 0%, #fff5f5 100%); border-left: 4px solid #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.72rem;">Rejected requests</div>
                        <div class="fs-2 fw-black text-danger tracking-tight">{{ $counts['rejected'] }}</div>
                    </div>
                    <div class="bg-danger-subtle text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="card border-0 shadow-sm mb-3 p-3">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold text-slate-500">Search User</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-slate-400"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Search by name or email..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold text-slate-500">Shop</label>
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
                <label class="form-label small mb-1 fw-semibold text-slate-500">Branch</label>
                <select name="branch_id" id="filter-branch-id" class="form-select form-select-sm">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-funnel-fill"></i> Filter</button>
                <a href="{{ route('admin.subscriptions.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <ul class="nav nav-tabs mb-3 border-bottom border-slate-200">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'pending' ? 'active fw-bold' : 'text-slate-500' }}" href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}">
                Pending
                @if($counts['pending'] > 0)
                    <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'approved' ? 'active fw-bold' : 'text-slate-500' }}" href="{{ route('admin.subscriptions.index', ['status' => 'approved']) }}">
                Approved
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'rejected' ? 'active fw-bold' : 'text-slate-500' }}" href="{{ route('admin.subscriptions.index', ['status' => 'rejected']) }}">
                Rejected
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-slate-500" href="{{ route('admin.subscriptions.active') }}">
                <i class="bi bi-check2-circle me-1"></i>Active Subscriptions
            </a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($requests->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    No subscription payment requests found for the selected status.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="padding: 1rem 1.25rem;">ID</th>
                                <th>Shop Owner</th>
                                <th>Subscription Plan</th>
                                <th>Transaction ID</th>
                                <th>Mobile Number</th>
                                <th>Amount</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th class="text-end" style="padding: 1rem 1.25rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td class="text-muted small" style="padding: 1rem 1.25rem;">#{{ $req->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-slate-900">{{ $req->user->name }}</div>
                                        <div class="text-muted small">{{ $req->user->email }}</div>
                                        @if($req->shop)
                                            <div class="text-slate-500 mt-1 small" style="font-size: 0.78rem;">
                                                <i class="bi bi-shop me-1"></i>{{ $req->shop->name }}
                                                @if($req->branch)
                                                    <span class="text-slate-300 mx-1">|</span>
                                                    <i class="bi bi-diagram-3 me-1"></i>{{ $req->branch->name }}
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-slate-800">{{ $req->plan->name }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mt-1 small" style="font-size: 0.7rem;">
                                            {{ $req->duration_months }} {{ $req->duration_months > 1 ? 'Months' : 'Month' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-slate-700 bg-slate-100 px-2 py-1 rounded small" style="font-size: 0.82rem;">{{ $req->transaction_id }}</span>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-slate-700" style="font-size: 0.85rem;">{{ $req->sender_bkash_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-emerald-700" style="font-size: 0.95rem;">{{ currency_symbol() }}{{ number_format($req->amount) }}</div>
                                    </td>
                                    <td class="small text-muted">
                                        <div>{{ $req->created_at->format('d M Y') }}</div>
                                        <div style="font-size: 0.76rem;">{{ $req->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @if($req->status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1">Pending</span>
                                        @elseif($req->status === 'approved')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-2.5 py-1">Approved</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-2.5 py-1" title="Reason: {{ $req->admin_note }}">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="padding: 1rem 1.25rem;">
                                        <a href="{{ route('admin.subscriptions.show', $req) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
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
