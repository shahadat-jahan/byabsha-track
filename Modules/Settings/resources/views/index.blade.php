@extends('layouts.app')

@section('title', $activeGroup === 'system' ? __('settings.system_settings') : ($activeGroup === 'landing' ? __('settings.landing_settings') : __('settings.dashboard_settings')))

@push('styles')
<style>
    .settings-shell {
        position: relative;
        color: var(--ink-900);
    }

    .settings-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: color-mix(in srgb, var(--brand) 12%, transparent);
        color: var(--brand);
        border: 1px solid color-mix(in srgb, var(--brand) 22%, transparent);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--brand) 13%, transparent);
    }

    .settings-title {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }

    .settings-subtitle {
        color: #334155;
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .settings-card {
        background: #ffffff;
        border: 1px solid #d8e4ee;
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .settings-tab-content {
        padding: 1.75rem;
    }

    .settings-section-title {
        margin-bottom: 0.5rem;
        font-size: 1.14rem;
        font-weight: 800;
        color: #1f3348;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .settings-section-meta {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 1.75rem;
    }

    .settings-field {
        padding: 1rem;
        border: 1px solid #e4edf6;
        border-radius: 14px;
        background: #fcfeff;
        margin-bottom: 1rem;
    }

    .settings-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .settings-input,
    .settings-select,
    .settings-textarea {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: #0f172a;
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .settings-input:focus,
    .settings-select:focus,
    .settings-textarea:focus {
        border-color: color-mix(in srgb, var(--brand) 70%, #ffffff);
        box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--brand) 14%, transparent);
        background: #ffffff;
    }

    .settings-help {
        display: block;
        color: #64748b;
        margin-top: 0.45rem;
        font-size: 0.8rem;
    }

    .settings-footer {
        padding: 1rem 1.75rem 1.5rem;
        border-top: 1px solid #e1eaf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.7rem;
    }

    .btn-clear-cache {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
        transition: all 0.2s;
    }

    .btn-clear-cache:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .btn-save-settings {
        background: linear-gradient(140deg, var(--brand), var(--brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.62rem 1.5rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 8px 20px color-mix(in srgb, var(--brand) 28%, transparent);
        transition: all 0.2s;
    }

    .btn-save-settings:hover {
        color: #fff;
        opacity: 0.92;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .settings-tab-content {
            padding: 1rem;
        }

        .settings-footer {
            flex-direction: column;
            align-items: stretch;
            padding: 0.9rem 1rem 1rem;
        }

        .btn-clear-cache,
        .btn-save-settings {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-shell">
<div class="mb-4">
    @php
        if ($activeGroup === 'system') {
            $sectionLabel = __('settings.system_settings');
            $sectionIcon = 'bi-cpu';
            $displayGroups = ['system'];
        } elseif ($activeGroup === 'landing') {
            $sectionLabel = __('settings.landing_settings');
            $sectionIcon = 'bi-browser-safari';
            $displayGroups = ['landing'];
        } else {
            $sectionLabel = __('settings.dashboard_settings');
            $sectionIcon = 'bi-sliders';
            $displayGroups = ['dashboard'];
        }
    @endphp

    <span class="settings-kicker"><i class="bi {{ $sectionIcon }}"></i> {{ $sectionLabel }}</span>
    <h1 class="settings-title">{{ $sectionLabel }}</h1>
    <p class="settings-subtitle">{{ __('settings.subtitle') }}</p>
</div>

<div class="settings-card">
    <form action="{{ route('settings.update', ['group' => $activeGroup]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="settings-tab-content">
            @if($activeGroup === 'landing')
                <div class="alert alert-info border-0 rounded-4 p-3 mb-4 d-flex gap-3 align-items-center" style="background: color-mix(in srgb, var(--brand) 8%, transparent); color: var(--brand-deep);">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <div>
                        <strong class="d-block mb-1">Landing Page Customization Settings</strong>
                        <span class="fs-7 opacity-90">Landing Page Settings allow you to customize the public homepage of your application. You will be able to customize more sections soon.</span>
                    </div>
                </div>
            @endif

            @php $hasAnySetting = false; @endphp
            @foreach($displayGroups as $group)
                @php $groupSettings = $settings->get($group, collect()); @endphp
                @if($groupSettings->count() > 0)
                    @php $hasAnySetting = true; @endphp

                    @foreach($groupSettings as $setting)
                        <div class="settings-field">
                            <label for="{{ $setting->key }}" class="form-label settings-label d-flex justify-content-between align-items-center">
                                <span>{{ __('settings.' . $setting->key) }}</span>
                                <span class="badge text-uppercase opacity-75 font-monospace" style="font-size: 0.62rem; background: #e2e8f0; color: #475569;">{{ $setting->key }}</span>
                            </label>

                            @if($setting->type === 'boolean')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>{{ __('app.no') }}</option>
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>{{ __('app.yes') }}</option>
                                </select>
                            @elseif($setting->key === 'default_language')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="bn" {{ $setting->value === 'bn' ? 'selected' : '' }}>বাংলা</option>
                                </select>
                            @elseif($setting->key === 'app_timezone')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" {{ $setting->value === $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                            @elseif($setting->key === 'currency')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>USD (United States Dollar)</option>
                                    <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="GBP" {{ $setting->value === 'GBP' ? 'selected' : '' }}>GBP (British Pound)</option>
                                    <option value="BDT" {{ $setting->value === 'BDT' ? 'selected' : '' }}>BDT (Bangladeshi Taka)</option>
                                    <option value="INR" {{ $setting->value === 'INR' ? 'selected' : '' }}>INR (Indian Rupee)</option>
                                </select>
                            @elseif($setting->key === 'business_address' || $setting->key === 'company_address')
                                <textarea class="form-control settings-textarea"
                                          id="{{ $setting->key }}"
                                          name="settings[{{ $setting->key }}]"
                                          rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @elseif($setting->key === 'dashboard_logo' || $setting->key === 'dashboard_favicon')
                                <div class="branding-upload-wrap">
                                    @if($setting->value)
                                        <div class="mb-3 d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                            <img src="{{ asset($setting->value) }}" alt="Preview" style="max-height: {{ $setting->key === 'dashboard_logo' ? '50px' : '28px' }}; max-width: 140px; object-fit: contain;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remove_files[]" value="{{ $setting->key }}" id="remove_{{ $setting->key }}">
                                                <label class="form-check-label text-danger fw-bold fs-7" for="remove_{{ $setting->key }}">
                                                    <i class="bi bi-trash"></i> {{ __('app.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file"
                                           class="form-control settings-input"
                                           id="{{ $setting->key }}"
                                           name="settings_files[{{ $setting->key }}]"
                                           accept="image/*">
                                </div>
                            @elseif($setting->key === 'dashboard_theme_color')
                                <div class="d-flex align-items-center gap-3">
                                    <input type="color"
                                           class="form-control form-control-color settings-color-input"
                                           id="{{ $setting->key }}"
                                           name="settings[{{ $setting->key }}]"
                                           value="{{ old('settings.' . $setting->key, $setting->value ?: '#0f766e') }}"
                                           style="width: 60px; height: 42px; border-radius: 8px; border: 1px solid #d6e2ee; padding: 4px;">
                                    <span class="text-muted font-monospace fs-7">{{ $setting->value ?: '#0f766e' }}</span>
                                </div>
                            @else
                                <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                       class="form-control settings-input"
                                       id="{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.' . $setting->key, $setting->value) }}">
                            @endif

                            @if(__('settings.' . $setting->key . '_help') !== 'settings.' . $setting->key . '_help')
                                <small class="settings-help">{{ __('settings.' . $setting->key . '_help') }}</small>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endforeach

            @if(!$hasAnySetting)
                <div class="settings-field mb-0 text-center py-4">
                    <span class="settings-help mb-0">{{ __('settings.subtitle') }}</span>
                </div>
            @endif
        </div>

        <div class="settings-footer">
            <a href="{{ route('settings.clear-cache', ['group' => $activeGroup]) }}" class="btn btn-clear-cache shadow-sm">
                <i class="bi bi-arrow-clockwise"></i> {{ __('settings.clear_cache') }}
            </a>
            <button type="submit" class="btn btn-save-settings shadow">
                <i class="bi bi-check-circle"></i> {{ __('app.save') }}
            </button>
        </div>
    </form>
</div>
</div>
@endsection
