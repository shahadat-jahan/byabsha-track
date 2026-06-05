@extends('layouts.app')
@section('title', __('subscription::subscription.expired_title'))
@section('content')
<div class="container-fluid py-5 d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 68px);">
  <div class="text-center" style="max-width: 580px;">

    <div class="mb-4">
      <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:88px;height:88px;">
        <i class="bi bi-lock-fill text-danger" style="font-size:2.6rem;"></i>
      </div>
    </div>

    <h2 class="fw-bold mb-2">{{ __('subscription::subscription.access_locked') }}</h2>

    @if($trialEnded)
      <p class="text-muted mb-1">{{ __('subscription::subscription.trial_ended_on', ['date' => $trialEnded->format('d M Y')]) }}</p>
    @else
      <p class="text-muted mb-1">{{ __('subscription::subscription.subscription_expired_msg') }}</p>
    @endif

    <p class="text-muted mb-4">{{ __('subscription::subscription.data_safe') }}</p>

    @if($user->isOwner())
      <div class="d-flex gap-3 justify-content-center flex-wrap mb-5">
        <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-lg px-5">
          <i class="bi bi-star me-2"></i>{{ __('subscription::subscription.view_plans_subscribe') }}
        </a>
        <a href="{{ route('subscription.my') }}" class="btn btn-outline-secondary btn-lg px-4">
          <i class="bi bi-receipt me-1"></i>{{ __('subscription::subscription.my_subscription') }}
        </a>
      </div>
    @else
      <div class="alert alert-warning mb-5 text-start">
        {{ __('subscription::subscription.subscription_expired_msg') }}
        <div class="mt-2">Please contact your shop owner or admin to restore access.</div>
      </div>
    @endif

    <div class="card border-0 bg-light rounded-3 p-4 text-start">
      <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>{{ __('subscription::subscription.what_happens_title') }}</h6>
      <ul class="list-unstyled mb-0 small text-muted">
        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>{{ __('subscription::subscription.step_bkash_payment') }}</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>{{ __('subscription::subscription.step_admin_verify') }}</li>
        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>{{ __('subscription::subscription.step_instant_unlock') }}</li>
        <li><i class="bi bi-check2-circle text-success me-2"></i>{{ __('subscription::subscription.step_data_intact') }}</li>
      </ul>
    </div>

  </div>
</div>
@endsection
@endsection
