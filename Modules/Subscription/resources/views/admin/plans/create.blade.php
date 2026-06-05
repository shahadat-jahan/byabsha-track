@extends('layouts.app')
@section('title', 'Create Subscription Plan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn border rounded-pill" style="border-color: rgba(15, 118, 110, 0.35); color: #0f766e; font-size: 0.86rem; font-weight: 700; padding: 0.66rem 1.22rem;">
            <i class="bi bi-arrow-left me-1"></i>Back to Plans
        </a>
        <div>
            <h2 class="fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; letter-spacing: -0.03em; color: #0f172a;">
                Create Subscription Plan
            </h2>
            <p style="color: #64748b; font-size: 0.95rem;">Define a new subscription tier with features, limits, and pricing details</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: #fff5f5; border: 1px solid #fecaca; color: #991b1b; border-radius: 14px;">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following validation errors:
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.subscriptions.plans.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Basic Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="e.g. Business Pro, Starter Pack" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-muted small mt-1.5">The public display name for the plan.</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-tag text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Pricing & Duration</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Price ({{ currency_symbol() }}) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted">{{ currency_symbol() }}</span>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00" step="0.01" value="{{ old('price', 0) }}" required>
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text text-muted small mt-1.5">Set to 0 for a free subscription tier.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" class="form-control rounded-3 @error('duration_days') is-invalid @enderror" placeholder="e.g. 30, 365" value="{{ old('duration_days', 30) }}" min="1" required>
                                @error('duration_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text text-muted small mt-1.5">How long the subscription stays active before expiring.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-ui-checks text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Assign Modules / Features</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Select which application modules are unlocked for owners on this plan.</p>
                        
                        @php
                            $modules = \App\Models\User::availableModuleAccessKeys();
                        @endphp

                        <div class="row g-3">
                            @foreach($modules as $module)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 border d-flex gap-3 align-items-start" style="background: #fbfbfc; border-color: #e2e8f0 !important;">
                                        <input class="form-check-input mt-1" type="checkbox" name="features[{{ $module }}]" id="feat-{{ $module }}" value="1" {{ is_array(old('features')) && isset(old('features')[$module]) ? 'checked' : '' }}>
                                        <div>
                                            <label class="form-check-label fw-bold text-dark text-capitalize mb-0" for="feat-{{ $module }}">
                                                {{ str_replace('_', ' ', $module) }}
                                            </label>
                                            <span class="d-block text-muted uppercase font-monospace" style="font-size: 0.65rem; font-weight: 600;">{{ $module }} module</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-left-text text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Description</h6>
                    </div>
                    <div class="card-body p-4">
                        <textarea name="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror" placeholder="Describe this plan... (benefits, target businesses)">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text text-muted small mt-1.5">Displayed to owners when choosing subscriptions.</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-code-slash text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Metadata (Optional)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div>
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Metadata (JSON Format)</label>
                            <textarea name="meta" rows="3" class="form-control rounded-3 font-monospace" placeholder='{"badge_label": "Popular", "button_text": "Choose Basic"}'>{{ old('meta') }}</textarea>
                            @error('meta')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="form-text text-muted small mt-1.5">Custom configurations for display (e.g. ribbons, badges).</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #d8e4ee !important;">
                    <div class="p-3 bg-light border-bottom rounded-top-4 d-flex align-items-center gap-2">
                        <i class="bi bi-toggle-on text-teal-600 fs-5"></i>
                        <h6 class="fw-bold mb-0 text-slate-900">Status & Visibility</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-muted mb-2" style="font-size: 0.72rem; tracking-wider;">Plan Status</label>
                            <select name="status" class="form-select rounded-3">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text text-muted small mt-1.5">Inactive plans cannot be purchased by owners.</div>
                        </div>

                        <hr style="border-color: #e2e8f0; margin: 1.5rem 0;">

                        <div class="form-check d-flex gap-2">
                            <input class="form-check-input mt-1" type="checkbox" name="show_in_owner_panel" id="show_in_owner_panel" value="1" {{ old('show_in_owner_panel', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <label class="form-check-label fw-bold text-dark" for="show_in_owner_panel">
                                    Show in Owner Panel
                                </label>
                                <span class="d-block text-muted small mt-1 leading-relaxed">If unchecked, this plan will be hidden from the owner's billing center page.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4" style="border: 1px solid #d8e4ee !important;">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold mb-2" style="background-color: #0f766e; border-color: #0f766e;">
                        <i class="bi bi-check-lg me-1"></i>Create Plan
                    </button>
                    <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2.5 fw-bold">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
