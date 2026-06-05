@extends('layouts.app')
@section('title', 'Subscription Required')

@push('styles')
    @vite(['resources/css/app.css'])
@endpush

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-16 antialiased font-sans text-slate-800 text-center">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 md:p-12 shadow-xl shadow-slate-100 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500"></div>
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl"></div>
        
        <div class="w-20 h-20 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-amber-100 shadow-inner">
            <i class="bi bi-shield-lock-fill text-amber-500 text-3xl"></i>
        </div>

        <h1 class="text-3xl font-black tracking-tight text-slate-900 mb-3">Subscription Upgrade Required</h1>
        
        @php
            $shopId = app(\App\Services\ShopContext::class)->getActiveShopId();
            $shop = $shopId ? \Modules\Shop\Models\Shop::find($shopId) : null;
        @endphp

        <p class="text-slate-500 text-sm leading-relaxed max-w-md mx-auto mb-8">
            The module you are trying to access is not included in the subscription plan for 
            <strong>{{ $shop ? $shop->name : 'this shop' }}</strong>.
        </p>

        @if(auth()->user()->isOwner())
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8 text-left">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">How to unlock:</h3>
                <ul class="space-y-2 text-xs text-slate-500">
                    <li class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-teal-600"></i>
                        <span>Go to the <strong>Subscription Plans</strong> page.</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-teal-600"></i>
                        <span>Select a plan that contains this module.</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-teal-600"></i>
                        <span>Submit payment details via bKash to activate instantly.</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <a href="{{ route('subscription.plans') }}" 
                   class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-sm shadow-md transition-all duration-150 hover:-translate-y-0.5 active:translate-y-0">
                    <i class="bi bi-stars"></i> Upgrade Subscription
                </a>
                <a href="javascript:history.back()" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-sm transition-all duration-150">
                    Go Back
                </a>
            </div>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8 text-left flex items-start gap-3">
                <i class="bi bi-info-circle-fill text-teal-600 text-lg mt-0.5"></i>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Access Restrained</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        As a manager, your features are bound to the shop owner's subscription. Please contact your 
                        <strong>Shop Owner</strong> to upgrade the plan for this shop.
                    </p>
                </div>
            </div>

            <a href="javascript:history.back()" 
               class="inline-flex justify-center items-center px-6 py-3 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-sm transition-all duration-150">
                Go Back
            </a>
        @endif
    </div>
</div>
@endsection
