@extends('layouts.app')

@section('title', __('shop.create_title'))

@push('styles')
<style>
    .btn-shop-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-shop-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('shop.create_title') }}</h1>
    <p class="page-subtitle">{{ __('shop.create_subtitle') }}</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-shop"></i>
                    {{ __('shop.shop_info') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('shop.store') }}" method="POST">
                    @csrf

                    {{-- Shop Owner Selection (for Superadmin only) --}}
                    @if(auth()->user()->isSuperAdmin() && $shopOwners)
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">
                                {{ __('app.shop_owner') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id"
                                    required>
                                <option value="">{{ __('app.select_owner') }}</option>
                                @foreach($shopOwners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('user_id') == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            {{ __('shop.name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="{{ __('shop.name_placeholder') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label fw-semibold">
                            {{ __('shop.location') }}
                        </label>
                        <input type="text"
                               class="form-control @error('location') is-invalid @enderror"
                               id="location"
                               name="location"
                               value="{{ old('location') }}"
                               placeholder="{{ __('shop.location_placeholder') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">
                            {{ __('shop.address') }}
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address"
                                  name="address"
                                  rows="3"
                                  placeholder="{{ __('shop.address_placeholder') }}">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('shop.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-shop-theme">
                            <i class="bi bi-check-circle"></i> {{ __('shop.create_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
