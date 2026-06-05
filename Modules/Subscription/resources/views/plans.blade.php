@extends('layouts.app')
@section('title', __( 'subscription::subscription.plans' ))

@push('styles')
    @vite(['resources/css/app.css'])
@endpush
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;\"><i class="bi bi-star-fill me-2" style="color: #f59e0b;\"></i>{{ __( 'subscription::subscription.plans' ) }}</h2>
      <p style="color: #64748b; font-size: 0.95rem;\">{{ __('Choose the perfect plan for your business') }}</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
      <a href="{{ route('subscription.my') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
        <i class="bi bi-receipt me-1"></i>{{ __('subscription::subscription.my_subscription') }}
      </a>
    </div>
  </div>


  @if($pendingRequest)
    <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; border-radius: 14px; display: flex; gap: 1rem; align-items: flex-start;">
      <i class="bi bi-hourglass-split flex-shrink-0" style="font-size: 1.25rem; margin-top: 0.1rem;\"></i>
      <div>
        <strong style="display: block; margin-bottom: 0.2rem;\">{{ __('subscription::subscription.payment_under_review') }}</strong>
        <small>{{ __('subscription::subscription.payment_pending_approval', ['plan' => $pendingRequest->plan->name, 'amount' => number_format($pendingRequest->amount)]) }}</small>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @php
    $freePlan = $plans->firstWhere('slug', 'free');
    $otherPlans = $plans->reject(fn($p) => $p->slug === 'free')->values();
    $allPlans = $freePlan ? collect([$freePlan])->merge($otherPlans) : $otherPlans;
    $allModules = collect(\App\Models\User::availableModuleAccessKeys())
      ->reject(fn($m) => in_array($m, ['dashboard', 'shop', 'subscription'], true))
      ->values();
  @endphp

  <div id="cards-view" class="row g-4 mb-4">
    @forelse($allPlans as $plan)
      @php
        $isCurrent = $currentPlan && $currentPlan->id === $plan->id;
        $badgeLabel = $plan->badgeLabel();
        $buttonText = $plan->buttonText();
      @endphp
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden {{ $isCurrent ? 'border border-2 border-primary' : '' }}" style="background: #fff; border: 1px solid #d8e4ee !important;">
          <div class="card-body d-flex flex-column p-4">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
              <h5 class="fw-bold mb-0 text-slate-900" style="font-family: 'Space Grotesk', sans-serif;">{{ $plan->name }}</h5>
              <div class="d-flex flex-column align-items-end gap-1">
                @if($plan->slug === 'free')
                  <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5">Default</span>
                @elseif($badgeLabel)
                  <span class="badge bg-primary rounded-pill px-2.5">{{ $badgeLabel }}</span>
                @endif
                <span class="badge {{ $plan->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-2">{{ $plan->status === 'active' ? 'Active' : 'Inactive' }}</span>
              </div>
            </div>
            
            <div class="mb-3">
              @if($plan->isFree())
                <span class="fs-3 fw-bold text-success" style="font-family: 'Space Grotesk', sans-serif;">FREE</span>
              @else
                <span class="fs-3 fw-bold text-slate-900" style="font-family: 'Space Grotesk', sans-serif;">{{ currency_symbol() }}{{ number_format($plan->price) }}</span>
                <span class="text-muted small">/ {{ $plan->billing_cycle }}</span>
              @endif
            </div>

            <div class="text-muted small mb-4" style="line-height: 1.5; min-height: 48px;">{{ $plan->description }}</div>

            <h6 class="text-uppercase fw-bold text-muted small tracking-wider mb-2" style="font-size: 0.7rem;">Modules / Features</h6>
            <ul class="list-unstyled small mb-4 space-y-1">
              @foreach($allModules as $moduleKey)
                @php $enabled = $plan->hasModule($moduleKey); @endphp
                <li class="mb-1 {{ $enabled ? '' : 'text-muted' }}">
                  <i class="bi {{ $enabled ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }} me-2"></i>
                  {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $moduleKey)) }}
                </li>
              @endforeach
            </ul>

            <div class="mt-auto pt-3 border-top" style="border-color: #f1f5f9;">
              @if($isCurrent)
                <button class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold" disabled><i class="bi bi-check2-circle me-1"></i>{{ __('subscription::subscription.current_plan_btn') }}</button>
              @elseif($pendingRequest && $pendingRequest->subscription_plan_id === $plan->id)
                <button class="btn btn-warning w-100 rounded-pill py-2.5 fw-bold" disabled><i class="bi bi-hourglass me-1"></i>{{ __('subscription::subscription.pending_review') }}</button>
              @elseif($plan->isFree())
                <button class="btn btn-outline-secondary w-100 rounded-pill py-2.5 fw-bold" disabled>{{ __('subscription::subscription.free_forever') }}</button>
              @else
                @if(auth()->user()->isManager())
                  <button class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold" disabled>{{ __('Upgrade required') }}</button>
                @else
                  <button class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" onclick="openPayModal({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }})" style="background-color: #0f766e; border-color: #0f766e;">
                    <i class="bi text-white bi-wallet2 me-1"></i>{{ $buttonText }}
                  </button>
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-light border text-center mb-0">
          No additional plans are available yet. Admin-created plans will appear here automatically.
        </div>
      </div>
    @endforelse




<div class="modal fade" id="pay-modal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content rounded-4 border-0 shadow-lg" style="overflow: hidden;">
      <div class="w-100" style="height: 6px; background-color: #e2136e !important;"></div>

      <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 position-relative">
        <h5 class="modal-title fw-bold text-slate-900 d-flex align-items-center gap-2" id="payModalLabel" style="font-family: 'Space Grotesk', sans-serif;">
          <i class="bi bi-wallet2" style="color: #e2136e; font-size: 1.4rem;"></i>
          <span>bKash Manual Payment</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="payment-request-form" action="{{ route('subscription.payment.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="plan_id" id="modal-plan-id">

        <div class="modal-body px-4 py-3">
          <div id="ajax-success-alert" class="alert alert-success d-none rounded-3 text-center mb-3" role="alert" style="background-color: #f0fdf4; border-color: #bbf7d0; color: #166534;">
            <i class="bi bi-check-circle-fill fs-4 d-block mb-2"></i>
            <strong class="d-block mb-1">Payment Submitted Successfully!</strong>
            <span class="small">Your transaction details have been sent for manual admin review. Access will be unlocked upon approval.</span>
          </div>

          <div id="form-fields-container">
            <div class="d-flex gap-3 mb-4">
              <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark text-white rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem;">1</div>
              <div class="flex-grow-1">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Duration & Payable</h6>
                <div class="mb-2">
                  <span class="text-muted small">Selected Plan:</span>
                  <span id="modal-plan-name" class="fw-bold text-dark small"></span>
                </div>
                <select name="duration_months" id="duration_months" class="form-select form-select-sm rounded-3">
                  @for($i=1;$i<=12;$i++)
                    <option value="{{ $i }}">{{ $i }} {{ $i>1 ? 'Months' : 'Month' }}</option>
                  @endfor
                </select>
                <div class="mt-2 text-muted small">
                  Total Payable: <span id="modal-total" class="text-success fw-bold fs-6"></span>
                </div>
              </div>
            </div>

            @if($shops->isNotEmpty())
              <hr class="my-3 opacity-10">
              <div class="d-flex gap-3 mb-4">
                <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark text-white rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem;">
                  <i class="bi bi-shop" style="font-size: 0.85rem;"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Select Shop & Branch</h6>
                  <div class="mb-3">
                    <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">Shop <span class="text-danger">*</span></label>
                    <select name="shop_id" id="modal-shop-id" required class="form-select form-select-sm rounded-3">
                      <option value="">-- Select Shop --</option>
                      @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" data-branches="{{ $shop->branches->map(fn($b) => ['id'=>$b->id,'name'=>$b->name])->toJson() }}">
                          {{ $shop->name }}
                        </option>
                      @endforeach
                    </select>
                    <span class="error-msg text-danger small mt-1 d-none" id="err-shop_id"></span>
                  </div>
                  <div>
                    <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">Branch <span class="text-secondary">(Optional)</span></label>
                    <select name="branch_id" id="modal-branch-id" class="form-select form-select-sm rounded-3">
                      <option value="">-- All Branches --</option>
                    </select>
                    <span class="error-msg text-danger small mt-1 d-none" id="err-branch_id"></span>
                  </div>
                </div>
              </div>
            @endif

            <hr class="my-3 opacity-10">

            <div class="d-flex gap-3 mb-4">
              <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark text-white rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem;">2</div>
              <div class="flex-grow-1">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Send payment to bKash</h6>
                <div class="rounded-3 p-3 text-center mb-3" style="background-color: #fff0f6; border: 1px solid #ffd6e7;">
                  <span class="d-block text-muted fw-bold text-uppercase mb-1" style="font-size: 0.7rem; color: #e2136e !important;">bKash Send Money Number</span>
                  <span class="d-block fw-black tracking-wider text-danger fs-3" style="color: #e2136e !important; font-weight: 800;">{{ config('subscription.bkash_number', '01700000000') }}</span>
                  <span class="d-block text-muted mt-1" style="font-size: 0.7rem;">Please use "Send Money" option from your bKash app</span>
                </div>
                <ol class="small text-muted ps-3 mb-0" style="line-height: 1.6;">
                  <li>Dial <strong>*247#</strong> or open bKash app</li>
                  <li>Select <strong>Send Money</strong> option</li>
                  <li>Enter the payment number shown above</li>
                  <li>Enter the Total Payable amount</li>
                  <li>Confirm transaction to receive <strong>Transaction ID</strong></li>
                </ol>
              </div>
            </div>

            <hr class="my-3 opacity-10">

            <div class="d-flex gap-3 mb-1">
              <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark text-white rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem;">3</div>
              <div class="flex-grow-1">
                <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Submit payment details</h6>
                
                <div class="mb-3">
                  <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">Your bKash Number <span class="text-danger">*</span></label>
                  <input type="text" name="sender_bkash_number" placeholder="e.g. 01823456789" maxlength="11" required 
                         class="form-control form-control-sm font-monospace rounded-3">
                  <span class="error-msg text-danger small mt-1 d-none" id="err-sender_bkash_number"></span>
                  <span class="text-muted d-block mt-1" style="font-size: 0.7rem;">11-digit mobile number used to send the payment.</span>
                </div>

                <div class="mb-3">
                  <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">Transaction ID (TxnID) <span class="text-danger">*</span></label>
                  <input type="text" name="transaction_id" placeholder="e.g. AE83K2PM01" maxlength="100" required 
                         class="form-control form-control-sm font-monospace rounded-3 text-uppercase">
                  <span class="error-msg text-danger small mt-1 d-none" id="err-transaction_id"></span>
                  <span class="text-muted d-block mt-1" style="font-size: 0.7rem;">Unique transaction code from the bKash confirmation message.</span>
                </div>

                <div class="mb-2">
                  <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">Receipt Screenshot <span class="text-secondary">(Optional)</span></label>
                  <input type="file" name="receipt_image" accept="image/jpeg,image/png,image/webp" 
                         class="form-control form-control-sm rounded-3">
                  <span class="error-msg text-danger small mt-1 d-none" id="err-receipt_image"></span>
                  <span class="text-muted d-block mt-1" style="font-size: 0.7rem;">Supported: JPG, PNG, WEBP (Max 3MB). Recommended.</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light border-top-0 px-4 py-3">
          <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal" id="btn-cancel">Cancel</button>
          <button type="submit" id="btn-submit" class="btn btn-sm text-white rounded-pill px-4" style="background-color: #e2136e; border-color: #e2136e;">
            <span id="submit-text">Submit Payment</span>
            <span id="submit-spinner" class="d-none"><i class="bi bi-arrow-repeat spin"></i></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>


let bsPayModal = null;

function openPayModal(planId, planName, planPrice) {
    if (!bsPayModal) {
        bsPayModal = new bootstrap.Modal(document.getElementById('pay-modal'));
    }
    
    document.getElementById('modal-plan-id').value = planId;
    document.getElementById('modal-plan-name').textContent = planName;
    window._planPrice = parseInt(planPrice, 10);
    updateTotal();
    
    bsPayModal.show();
}

function closePayModal() {
    if (bsPayModal) {
        bsPayModal.hide();
    }
}

document.getElementById('pay-modal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('payment-request-form').reset();
    document.getElementById('form-fields-container').classList.remove('d-none');
    document.getElementById('ajax-success-alert').classList.add('d-none');
    
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    btnSubmit.classList.remove('d-none');
    btnSubmit.disabled = false;
    btnCancel.disabled = false;
    btnCancel.textContent = 'Cancel';
    submitSpinner.classList.add('d-none');
    submitText.textContent = 'Submit Payment';

    document.querySelectorAll('.error-msg').forEach(el => {
        el.textContent = '';
        el.classList.add('d-none');
    });
});

document.getElementById('duration_months').addEventListener('change', updateTotal);

function updateTotal() {
  var months = parseInt(document.getElementById('duration_months').value, 10);
  var total = (window._planPrice || 0) * months;
  document.getElementById('modal-total').textContent = '{{ currency_symbol() }}' + total.toLocaleString();
}

var shopSelect   = document.getElementById('modal-shop-id');
var branchSelect = document.getElementById('modal-branch-id');
if (shopSelect) {
  shopSelect.addEventListener('change', function() {
    var selected = shopSelect.options[shopSelect.selectedIndex];
    var branches = selected.dataset.branches ? JSON.parse(selected.dataset.branches) : [];
    branchSelect.innerHTML = '<option value="">-- All Branches --</option>';
    branches.forEach(function(b) {
      var opt = document.createElement('option');
      opt.value = b.id;
      opt.textContent = b.name;
      branchSelect.appendChild(opt);
    });
  });
}

document.getElementById('payment-request-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    document.querySelectorAll('.error-msg').forEach(el => {
        el.textContent = '';
        el.classList.add('d-none');
    });
    
    btnSubmit.disabled = true;
    btnCancel.disabled = true;
    submitSpinner.classList.remove('d-none');
    submitText.textContent = 'Submitting...';
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                throw { status: response.status, errors: data.errors || { message: data.message } };
            }
            return data;
        });
    })
    .then(data => {
        document.getElementById('form-fields-container').classList.add('d-none');
        document.getElementById('ajax-success-alert').classList.remove('d-none');
        btnSubmit.classList.add('d-none');
        btnCancel.textContent = 'Close';
        btnCancel.disabled = false;
        
        setTimeout(() => {
            window.location.href = data.redirect || '{{ route("subscription.my") }}';
        }, 2500);
    })
    .catch(err => {
        btnSubmit.disabled = false;
        btnCancel.disabled = false;
        submitSpinner.classList.add('d-none');
        submitText.textContent = 'Submit Payment';
        
        if (err.status === 422) {
            const errors = err.errors;
            if (errors.message) {
                alert(errors.message);
            } else {
                for (const [field, messages] of Object.entries(errors)) {
                    const errEl = document.getElementById('err-' + field);
                    if (errEl) {
                        errEl.textContent = messages[0];
                        errEl.classList.remove('d-none');
                    }
                }
            }
        } else {
            alert('An unexpected error occurred. Please try again.');
            console.error(err);
        }
    });
});
</script>
@endpush
@endsection
