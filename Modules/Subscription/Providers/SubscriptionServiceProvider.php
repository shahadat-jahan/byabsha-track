<?php

namespace Modules\Subscription\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

class SubscriptionServiceProvider extends ServiceProvider {
    use PathNamespace;

    protected string $name = 'Subscription';
    protected string $nameLower = 'subscription';

    public function boot(): void { $this->registerTranslations(); $this->registerConfig(); $this->registerViews(); $this->loadMigrationsFrom(module_path($this->name, 'database/migrations')); }

    public function register(): void { $this->app->register(RouteServiceProvider::class); }

    protected function registerConfig(): void { $this->mergeConfigFrom(module_path($this->name, 'config/config.php'), $this->nameLower); }

    public function registerViews(): void { $vp = resource_path('views/modules/' . $this->nameLower); $sp2 = module_path($this->name, 'resources/views'); $this->publishes([$sp2 => $vp], ['views', $this->nameLower . '-module-views']); $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sp2]), $this->nameLower); }

    public function registerTranslations(): void { $lp = resource_path('lang/modules/' . $this->nameLower); if (is_dir($lp)) { $this->loadTranslationsFrom($lp, $this->nameLower); } else { $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower); } }

    private function getPublishableViewPaths(): array { $paths = []; foreach (config('view.paths') as $p) { if (is_dir($p . '/modules/' . $this->nameLower)) { $paths[] = $p . '/modules/' . $this->nameLower; } } return $paths; }
}
