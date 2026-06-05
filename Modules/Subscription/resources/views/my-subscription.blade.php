@extends('layouts.app')
@section('title', __( 'subscription::subscription.my_subscription' ))
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;\"><i class="bi bi-receipt me-2" style="color: #0f766e;\"></i>{{ __( 'subscription::subscription.my_subscription' ) }}</h2>
      <p style="color: #64748b; font-size: 0.95rem;\">{{ __('Manage your current plan and view payment history') }}</p>
    </div>
    <a href="{{ route('subscription.plans') }}" class="btn text-white rounded-pill" style="background: linear-gradient(140deg, #0f766e, #155e75); box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28); font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
      <i class="bi bi-grid-3x3-gap me-1"></i>{{ __('subscription::subscription.view_plans') }}
    </a>
  </div>



  <div class="row mb-4">
    <div class="col-12">
      <div class="rounded-3 overflow-hidden" style="background: #fff; border: 1px solid #d8e4ee; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
        <div style="padding: 1.2rem 1.5rem; border-bottom: 1px solid #d8e4ee; background: #fafbfc;">
          <h6 class="fw-semibold mb-0" style="color: #0f172a; font-size: 0.94rem;">
            <i class="bi bi-star-fill me-2" style="color: #f59e0b;"></i>{{ __('subscription::subscription.current_plan') }}
          </h6>
        </div>
        <div style="padding: 1.5rem;">
          <div class="row align-items-center g-3">
            <div class="col-md-4 d-flex align-items-center gap-3">
              <div style="background: rgba(15, 118, 110, 0.12); border-radius: 50%; padding: 1rem; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-shield-check" style="color: #0f766e; font-size: 1.5rem;"></i>
              </div>
              <div>
                <div style="color: #64748b; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('subscription::subscription.subscribed_to') }}</div>
                <div style="color: #0f172a; font-weight: 700; font-size: 1.2rem; line-height: 1.2; margin: 0.1rem 0;">{{ $currentPlan->name }}</div>
                @if($currentPlan->isFree())
                  <span class="badge" style="background: rgba(22, 163, 74, 0.14); color: #166534; border: 1px solid rgba(22, 163, 74, 0.24); padding: 0.36rem 0.76rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700;">{{ __('subscription::subscription.free') }}</span>
                @else
                  <small style="color: #64748b; font-weight: 600;">{{ currency_symbol() }}{{ number_format($currentPlan->price) }} / {{ $currentPlan->billing_cycle }}</small>
                @endif
              </div>
            </div>

            <div class="col-md-5">
              <div style="padding: 1rem; background: #f7fbff; border-left: 3px solid #0f766e; border-radius: 11px;">
                @if($subscription && $subscription->ends_at)
                  <div class="row g-3">
                    <div class="col-6">
                      <small style="color: #64748b; font-size: 0.75rem; display: block; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.15rem;">{{ __('subscription::subscription.active_date') }}</small>
                      <span style="color: #0f172a; font-weight: 700; font-size: 1.05rem;">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="col-6">
                      <small style="color: #64748b; font-size: 0.75rem; display: block; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.15rem;">{{ __('subscription::subscription.expiry_date') }}</small>
                      <div class="d-flex align-items-baseline gap-1 flex-wrap">
                        <span style="color: #047857; font-weight: 700; font-size: 1.05rem;">{{ $subscription->ends_at->format('d M Y') }}</span>
                        <small style="color: #64748b; font-size: 0.72rem;">({{ $subscription->ends_at->diffForHumans() }})</small>
                      </div>
                    </div>
                  </div>
                @else
                  <div class="d-flex align-items-center gap-2 py-1">
                    <i class="bi bi-infinity" style="color: #0f766e; font-size: 1.25rem;"></i>
                    <span style="color: #64748b; font-size: 0.88rem; font-weight: 600;">{{ __('subscription::subscription.free_plan_no_expiry') }}</span>
                  </div>
                @endif
              </div>
            </div>

            <div class="col-md-3 text-md-end">
              <a href="{{ route('subscription.plans') }}" class="btn border rounded-pill px-4 py-2.5 w-100 w-md-auto" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s;">
                <i class="bi bi-arrow-repeat"></i>
                <span>{{ __('subscription::subscription.change_plan') }}</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
      <h6 class="fw-semibold mb-0">
        <i class="bi bi-clock-history me-2"></i>{{ __('subscription::subscription.payment_history') }}
      </h6>
    </div>
    <div class="card-body p-0">
      @if($history->isEmpty())
        <div class="text-center py-6">
          <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50 text-muted"></i>
          <p class="text-muted mb-0">{{ __('subscription::subscription.no_payment_history') }}</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>{{ __('subscription::subscription.plan') }}</th>
                <th>{{ __('app.shop') }}</th>
                <th>{{ __('subscription::subscription.amount') }}</th>
                <th>{{ __('subscription::subscription.duration') }}</th>
                <th>{{ __('subscription::subscription.txn_id') }}</th>
                <th>{{ __('subscription::subscription.date') }}</th>
                <th class="text-center">{{ __('subscription::subscription.status') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($history as $req)
              <tr>
                <td class="fw-semibold">{{ $req->plan->name }}</td>
                <td>
                  <span class="badge bg-secondary text-dark bg-opacity-10 border border-secondary border-opacity-20 px-2.5 py-1.5 rounded-pill" style="font-weight: 700; font-size: 0.78rem;">
                    <i class="bi bi-shop me-1 text-secondary"></i>{{ $req->shop->name ?? 'N/A' }}
                  </span>
                </td>
                <td>{{ currency_symbol() }}{{ number_format($req->amount) }}</td>
                <td>
                  <span class="badge bg-info-subtle text-info">
                    {{ $req->duration_months }} {{ $req->duration_months > 1 ? __('subscription::subscription.months') : __('subscription::subscription.month') }}
                  </span>
                </td>
                <td>
                  <code class="bg-light p-1 rounded" title="{{ $req->transaction_id }}">
                    {{ substr($req->transaction_id, 0, 8) }}{{ strlen($req->transaction_id) > 8 ? '...' : '' }}
                  </code>
                </td>
                <td class="text-muted small">
                  {{ $req->created_at->format('d M Y') }}<br/>
                  <small>{{ $req->created_at->diffForHumans() }}</small>
                </td>
                <td class="text-center">
                  @if($req->status==='approved')
                    <span class="badge bg-success">
                      <i class="bi bi-check-circle me-1"></i>{{ __('subscription::subscription.approved') }}
                    </span>
                  @elseif($req->status==='rejected')
                    <span class="badge bg-danger" title="{{ $req->admin_note }}">
                      <i class="bi bi-x-circle me-1"></i>{{ __('subscription::subscription.rejected') }}
                    </span>
                  @else
                    <span class="badge bg-warning text-dark">
                      <i class="bi bi-hourglass-split me-1"></i>{{ __('subscription::subscription.pending') }}
                    </span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="border-top p-3 bg-light">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Showing {{ $history->firstItem() ?? 0 }} to {{ $history->lastItem() ?? 0 }} of {{ $history->total() }} payments</small>
            {{ $history->links() }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
