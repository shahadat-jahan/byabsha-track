@extends('layouts.app')
@section('title', 'Review Payment Request')
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
      <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
    <div>
      <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;">
        <i class="bi bi-receipt me-2" style="color: #0f766e;"></i>{{ __('Review Payment Request') }} #{{ $paymentRequest->id }}
      </h2>
      <small style="color: #64748b;">{{ $paymentRequest->created_at->format('M d, Y H:i A') }}</small>
    </div>
  </div>



  <div class="row g-4">
    <div class="col-lg-8">
      <div class="rounded-3 overflow-hidden mb-4" style="background: #fff; border: 1px solid #d8e4ee; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
        <div style="padding: 1.2rem 1.5rem; border-bottom: 1px solid #d8e4ee; background: #fafbfc;">
          <h6 class="fw-semibold mb-0" style="color: #0f172a; font-size: 0.94rem;">
            <i class="bi bi-info-circle me-2" style="color: #0f766e;"></i>{{ __('Payment Details') }}
          </h6>
        </div>
        <div style="padding: 1.5rem;">
          <div class="row mb-4">
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('app.user') }}</label>
                <div style="color: #0f172a; font-weight: 700;">{{ $paymentRequest->user->name }}</div>
                <small style="color: #64748b;">{{ $paymentRequest->user->email }}</small>
              </div>
              @if($paymentRequest->shop)
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('app.shop') }}</label>
                <div style="color: #0f172a;">{{ $paymentRequest->shop->name }}</div>
              </div>
              @endif
              @if($paymentRequest->branch)
              <div>
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('app.branch') }}</label>
                <div style="color: #0f172a;">{{ $paymentRequest->branch->name }}</div>
              </div>
              @endif
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Plan') }}</label>
                <div style="color: #0f172a; font-weight: 700;">{{ $paymentRequest->plan->name }}</div>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Duration') }}</label>
                <div style="color: #0f172a;">{{ $paymentRequest->duration_months }} {{ $paymentRequest->duration_months > 1 ? __('months') : __('month') }}</div>
              </div>
              <div>
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Amount') }}</label>
                <div style="font-size: 1.5rem; font-weight: 700; color: #047857;">{{ currency_symbol() }}{{ number_format($paymentRequest->amount) }}</div>
              </div>
            </div>
          </div>

          <div style="border-top: 1px solid #d8e4ee; margin: 1.2rem 0; padding-top: 1.2rem;"></div>

          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Sender bKash Number') }}</label>
                <div style="font-family: 'Monaco', 'Courier New', monospace; background: #fbfdff; border: 1px solid #d6e2ee; padding: 0.6rem 0.8rem; border-radius: 11px; color: #0f172a;">{{ $paymentRequest->sender_bkash_number }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Transaction ID') }}</label>
                <div style="font-family: 'Monaco', 'Courier New', monospace; background: #fbfdff; border: 1px solid #d6e2ee; padding: 0.6rem 0.8rem; border-radius: 11px; color: #0f172a;">{{ $paymentRequest->transaction_id }}</div>
              </div>
            </div>
          </div>

          @if($paymentRequest->admin_note)
          <div style="border-top: 1px solid #d8e4ee; margin-top: 1.2rem; padding-top: 1.2rem;">
            <label class="form-label small fw-semibold mb-1" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Admin Note') }}</label>
            <p style="background: #f7fbff; border-left: 3px solid #0f766e; padding: 0.8rem; color: #0f172a; margin-bottom: 0;">{{ $paymentRequest->admin_note }}</p>
          </div>
          @endif

          <div style="border-top: 1px solid #d8e4ee; margin-top: 1.2rem; padding-top: 1.2rem;">
            <label class="form-label small fw-semibold mb-2" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Current Status') }}</label>
            <div>
              @if($paymentRequest->status === 'approved')
                <span class="badge" style="background: rgba(22, 163, 74, 0.14); color: #166534; border: 1px solid rgba(22, 163, 74, 0.24); padding: 0.42rem 0.92rem; border-radius: 999px; font-size: 0.76rem; font-weight: 700;">
                  <i class="bi bi-check-circle me-1"></i>{{ __('Approved') }}
                </span>
                @if($paymentRequest->reviewer)
                  <small style="color: #64748b; font-size: 0.82rem;" class="d-block mt-2">{{ __('by') }} {{ $paymentRequest->reviewer->name }} {{ __('on') }} {{ $paymentRequest->updated_at->format('M d, Y H:i A') }}</small>
                @endif
              @elseif($paymentRequest->status === 'rejected')
                <span class="badge" style="background: rgba(220, 38, 38, 0.14); color: #991b1b; border: 1px solid rgba(220, 38, 38, 0.24); padding: 0.42rem 0.92rem; border-radius: 999px; font-size: 0.76rem; font-weight: 700;">
                  <i class="bi bi-x-circle me-1"></i>{{ __('Rejected') }}
                </span>
                @if($paymentRequest->reviewer)
                  <small style="color: #64748b; font-size: 0.82rem;" class="d-block mt-2">{{ __('by') }} {{ $paymentRequest->reviewer->name }} {{ __('on') }} {{ $paymentRequest->updated_at->format('M d, Y H:i A') }}</small>
                @endif
              @else
                <span class="badge" style="background: rgba(245, 158, 11, 0.14); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.24); padding: 0.42rem 0.92rem; border-radius: 999px; font-size: 0.76rem; font-weight: 700;">
                  <i class="bi bi-hourglass-split me-1"></i>{{ __('Pending Review') }}
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>

      @if($paymentRequest->receipt_image)
      <div class="rounded-3 overflow-hidden" style="background: #fff; border: 1px solid #d8e4ee; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
        <div style="padding: 1.2rem 1.5rem; border-bottom: 1px solid #d8e4ee; background: #fafbfc;">
          <h6 class="fw-semibold mb-0" style="color: #0f172a; font-size: 0.94rem;">
            <i class="bi bi-image me-2" style="color: #0f766e;"></i>{{ __('Receipt Screenshot') }}
          </h6>
        </div>
        <div style="padding: 1.5rem; text-align: center;">
          <img src="{{ Storage::url($paymentRequest->receipt_image) }}"
               class="img-fluid rounded"
               style="max-height: 500px; border-radius: 16px;"
               alt="Payment Receipt">
          <div style="margin-top: 1rem;">
            <a href="{{ Storage::url($paymentRequest->receipt_image) }}" target="_blank" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
              <i class="bi bi-download me-1"></i>{{ __('Download') }}
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>

    <div class="col-lg-4">
      @if($paymentRequest->status === 'pending')
        <div class="rounded-3 overflow-hidden mb-3" style="background: #fff; border: 2px solid #16a34a; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
          <div style="padding: 1.2rem 1.5rem; background: rgba(22, 163, 74, 0.08); border-bottom: 1px solid #d8e4ee;">
            <h6 class="fw-semibold mb-0" style="color: #166534; font-size: 0.94rem;">
              <i class="bi bi-check-circle me-2"></i>{{ __('Approve & Activate') }}
            </h6>
          </div>
          <div style="padding: 1.5rem;">
            <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 1rem;">
              {{ __('Activates') }} <strong>{{ $paymentRequest->plan->name }}</strong> {{ __('for') }}
              <strong>{{ $paymentRequest->duration_months }} {{ $paymentRequest->duration_months > 1 ? __('months') : __('month') }}</strong>
              {{ __('for') }} {{ $paymentRequest->user->name }}.
            </p>
            <form action="{{ route('admin.subscriptions.approve', $paymentRequest) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-2" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Note') }} <span style="color: #64748b;">({{ __('optional') }})</span></label>
                <textarea name="admin_note" class="form-control" rows="3" style="border-radius: 11px; border: 1px solid #d6e2ee; background: #fbfdff; color: #0f172a; font-size: 0.94rem; padding: 0.62rem 0.78rem;"
                          placeholder="{{ __('Add a note for the record...') }}"></textarea>
              </div>
              <button type="submit" class="btn text-white w-100 rounded-pill" style="background: linear-gradient(140deg, #047857, #065f46); box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28); font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;" onclick="return confirm('{{ __('Approve this payment request?') }}');">
                <i class="bi bi-check-lg me-1"></i>{{ __('Approve & Activate') }}
              </button>
            </form>
          </div>
        </div>

        <div class="rounded-3 overflow-hidden" style="background: #fff; border: 2px solid #dc2626; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
          <div style="padding: 1.2rem 1.5rem; background: rgba(220, 38, 38, 0.08); border-bottom: 1px solid #d8e4ee;">
            <h6 class="fw-semibold mb-0" style="color: #991b1b; font-size: 0.94rem;">
              <i class="bi bi-x-circle me-2"></i>{{ __('Reject Request') }}
            </h6>
          </div>
          <div style="padding: 1.5rem;">
            <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 1rem;">
              {{ __('Rejects this payment. The user will be notified and can submit another request.') }}
            </p>
            <form action="{{ route('admin.subscriptions.reject', $paymentRequest) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label small fw-semibold mb-2" style="color: #4b637b; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">{{ __('Reason for Rejection') }} <span style="color: #dc2626;">*</span></label>
                <textarea name="admin_note" class="form-control" rows="3" style="border-radius: 11px; border: 1px solid #d6e2ee; background: #fbfdff; color: #0f172a; font-size: 0.94rem; padding: 0.62rem 0.78rem;"
                          placeholder="{{ __('Explain why this payment is being rejected...') }}" required></textarea>
              </div>
              <button type="submit" class="btn text-white w-100 rounded-pill" style="background: linear-gradient(140deg, #dc2626, #b91c1c); box-shadow: 0 14px 28px rgba(220, 38, 38, 0.28); font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;" onclick="return confirm('{{ __('Reject this payment request?') }}');">
                <i class="bi bi-x-lg me-1"></i>{{ __('Reject') }}
              </button>
            </form>
          </div>
        </div>
      @else
        <div class="rounded-3 overflow-hidden" style="background: #fff; border: 2px solid {{ $paymentRequest->status === 'approved' ? '#16a34a' : '#dc2626' }}; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);">
          <div style="padding: 1.2rem 1.5rem; background: {{ $paymentRequest->status === 'approved' ? 'rgba(22, 163, 74, 0.08)' : 'rgba(220, 38, 38, 0.08)' }}; border-bottom: 1px solid #d8e4ee;">
            <h6 class="fw-semibold mb-0" style="color: {{ $paymentRequest->status === 'approved' ? '#166534' : '#991b1b' }}; font-size: 0.94rem;">
              @if($paymentRequest->status === 'approved')
                <i class="bi bi-check-circle me-2"></i>{{ __('Approved') }}
              @else
                <i class="bi bi-x-circle me-2"></i>{{ __('Rejected') }}
              @endif
            </h6>
          </div>
          <div style="padding: 1.5rem; text-align: center;">
            <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 1rem;">{{ __('This request has already been') }} {{ $paymentRequest->status }}.</p>
            @if($paymentRequest->reviewer)
              <small style="color: #64748b; font-size: 0.82rem;" class="d-block mb-2">
                <i class="bi bi-person-circle me-1"></i>{{ __('by') }} {{ $paymentRequest->reviewer->name }}
              </small>
              <small style="color: #64748b; font-size: 0.82rem;" class="d-block">
                {{ $paymentRequest->updated_at->format('M d, Y H:i A') }}
              </small>
            @endif
          </div>
        </div>

        <a href="{{ route('admin.subscriptions.index') }}" class="btn border rounded-pill w-100 mt-3" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
          <i class="bi bi-arrow-left me-1"></i>{{ __('Back to Requests') }}
        </a>
      @endif
    </div>
  </div>
</div>
@endsection
