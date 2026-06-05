<?php

namespace App\Providers {

    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            // Dynamically override config values based on settings database
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    // Timezone injection
                    $timezone = \Modules\Settings\Models\Setting::get('app_timezone');
                    if ($timezone && in_array($timezone, timezone_identifiers_list(), true)) {
                        config(['app.timezone' => $timezone]);
                        date_default_timezone_set($timezone);
                    }

                    // Default Language injection
                    $defaultLang = \Modules\Settings\Models\Setting::get('default_language');
                    if ($defaultLang) {
                        config(['app.locale' => $defaultLang]);
                        config(['app.fallback_locale' => $defaultLang]);
                        \Illuminate\Support\Facades\App::setLocale($defaultLang);
                    }

                    // Currency injection
                    $currencySymbol = \Modules\Settings\Models\Setting::get('currency_symbol', '$');
                    config(['app.currency_symbol' => $currencySymbol]);

                    $currency = \Modules\Settings\Models\Setting::get('currency', 'USD');
                    config(['app.currency' => $currency]);
                }
            } catch (\Throwable $e) {
                // Silence any errors during migration or bootstrap when DB is unavailable
            }

            // Register simple plan helpers for convenience in controllers and views
            if (! function_exists('App\Providers\planFeature')) {
                function planFeature(string $feature, $user = null): bool
                {
                    $user = $user ?: auth()->user();
                    if (! $user) {
                        return false;
                    }
                    return app(\App\Services\PlanService::class)->isFeatureEnabled($user, $feature);
                }
            }

            if (! function_exists('App\Providers\planLimit')) {
                function planLimit(string $limitKey, $user = null)
                {
                    $user = $user ?: auth()->user();
                    if (! $user) {
                        return null;
                    }
                    $plan = $user->currentPlan();
                    if ($plan instanceof \Modules\Subscription\Models\SubscriptionPlan) {
                        return $plan->getLimit($limitKey);
                    }
                    return null;
                }
            }

            if (! function_exists('App\Providers\has_module_access')) {
                function has_module_access(string $moduleKey, $user = null): bool
                {
                    $user = $user ?: auth()->user();
                    if (! $user) {
                        return false;
                    }
                    return $user->hasModuleAccess($moduleKey);
                }
            }
        }
    }
}

namespace {
    if (! function_exists('currency_symbol')) {
        function currency_symbol(): string
        {
            return config('app.currency_symbol', '$');
        }
    }

    if (! function_exists('format_price')) {
        function format_price($amount): string
        {
            return currency_symbol() . number_format((float)$amount, 2);
        }
    }
}
