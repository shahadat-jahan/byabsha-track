<?php $__env->startSection('title', __('dashboard.title')); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --landing-bg: #f4f8fb;
        --landing-ink-900: #0f172a;
        --landing-ink-700: #334155;
        --landing-ink-500: #64748b;
        --landing-brand: #0f766e;
        --landing-brand-deep: #155e75;
        --landing-line: #d8e4ee;
    }

    .dashboard-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--landing-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .dashboard-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.23), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.2), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
        pointer-events: none;
        z-index: -1;
    }

    .dashboard-header {
        margin-bottom: 1.4rem;
        background: transparent;
        color: var(--landing-ink-900);
        border-radius: 0;
        padding: 0.35rem 0;
        border: 0;
        box-shadow: none;
        position: relative;
        overflow: visible;
    }

    .dashboard-header::after {
        display: none;
    }

    .dashboard-title {
        font-size: clamp(1.45rem, 3vw, 2.1rem);
        font-weight: 700;
        color: var(--landing-ink-900);
        margin-bottom: 0.35rem;
        position: relative;
        z-index: 1;
        text-wrap: balance;
    }

    .dashboard-subtitle {
        color: var(--landing-ink-700);
        font-size: 0.95rem;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
        max-width: 720px;
    }

    .overall-stats {
        margin-bottom: 2.1rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.15rem;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid var(--landing-line);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        border-color: #97b0c8;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.24rem;
        margin-bottom: 0.85rem;
    }

    .stat-icon.blue {
        background: linear-gradient(145deg, var(--landing-brand), var(--landing-brand-deep));
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(145deg, #0f766e, #0d5969);
        color: white;
    }

    .stat-icon.yellow {
        background: linear-gradient(145deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-label {
        color: var(--landing-ink-500);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.35rem;
    }

    .stat-value {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: clamp(1.2rem, 2.1vw, 1.6rem);
        font-weight: 700;
        color: var(--landing-ink-900);
        line-height: 1.2;
    }

    .stat-card .text-muted,
    .summary-stat .text-muted {
        margin-top: 0.25rem;
        color: #7f8fa3 !important;
    }

    .btn-calc-guide {
        border-color: #c7d8e6;
        color: #3f556c;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.77rem;
        padding: 0.38rem 0.82rem;
        background: rgba(255, 255, 255, 0.62);
    }

    .btn-calc-guide:hover,
    .btn-calc-guide:focus {
        border-color: #97b0c8;
        color: #1e293b;
        background: #ffffff;
    }

    .calc-guide-panel {
        background: rgba(255, 255, 255, 0.8) !important;
        border: 1px solid var(--landing-line) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    }

    .shop-section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--landing-ink-900);
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .shop-section-title i {
        font-size: 1.15rem;
        color: #0f766e;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(15, 118, 110, 0.12);
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .shop-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(265px, 1fr));
        gap: 1.1rem;
        margin-bottom: 2rem;
    }

    .shop-summary-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid var(--landing-line);
        overflow: hidden;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .shop-summary-card:hover {
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        transform: translateY(-4px);
    }

    .shop-summary-header {
        background: linear-gradient(135deg, #0c2f44 0%, #0f766e 60%, #0d5969 100%);
        color: white;
        padding: 1.05rem 1.2rem;
    }

    .shop-summary-header h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .shop-summary-body {
        padding: 1.08rem 1.15rem;
        flex-grow: 1;
    }

    .shop-summary-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
    }

    .summary-stat {
        background: #f8fbff;
        border: 1px solid #dce7f2;
        border-radius: 11px;
        padding: 0.7rem;
        text-align: center;
    }

    .summary-stat-label {
        display: block;
        font-size: 0.67rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .summary-stat-value {
        display: block;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--landing-ink-900);
    }

    .shop-summary-footer {
        padding: 0 1.15rem 1rem;
    }

    .btn-show-details {
        display: block;
        width: 100%;
        background: linear-gradient(140deg, var(--landing-brand), var(--landing-brand-deep));
        color: white;
        border: none;
        border-radius: 999px;
        padding: 0.62rem 0.95rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-align: center;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.24);
    }

    .btn-show-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.3);
    }

    #calcGuide .border {
        border: 1px solid var(--landing-line) !important;
    }

    #calcGuide code {
        background: #edf3f8;
        border-radius: 6px;
        padding: 0.3rem 0.45rem;
        font-size: 0.75rem;
    }

    .modal-content {
        border: 1px solid rgba(255, 255, 255, 0.11);
        border-radius: 20px;
        box-shadow: 0 20px 38px rgba(15, 23, 42, 0.22);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(125deg, #f8fbff 0%, #eef6fb 100%);
    }

    @media (max-width: 991px) {
        .dashboard-header {
            padding: 0.2rem 0;
        }
    }

    @media (max-width: 768px) {
        .shop-card-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-title {
            font-size: 1.38rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-shell">
<div class="dashboard-header flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-5 mb-8">
    <div>
        <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none"><?php echo e(__('dashboard.title')); ?></h1>
        <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('dashboard.subtitle')); ?></p>
    </div>
    
    
    <?php if(auth()->user()->isOwner() || auth()->user()->isSuperAdmin()): ?>
        <?php if($userShops->count() > 0): ?>
            <div class="dropdown">
                <button class="btn d-flex align-items-center gap-2 px-3 py-1.5 border rounded-pill bg-white shadow-sm dropdown-toggle" 
                        type="button" 
                        id="shopSelectorDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="border-color: rgba(15, 118, 110, 0.25) !important; color: #0f172a; font-size: 0.85rem; font-weight: 700; transition: all 0.2s;">
                    <i class="bi bi-shop" style="font-size: 1rem; color: #0f766e;"></i>
                    <span><?php echo e($userShops->firstWhere('id', $activeShopId)?->name ?? __('Select Shop Context')); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 py-1 mt-1" aria-labelledby="shopSelectorDropdown" style="min-width: 220px; border: 1px solid #d8e4ee !important;">
                    <li class="dropdown-header text-uppercase tracking-wider small fw-bold text-muted px-3 py-1.5" style="font-size: 0.65rem; border-bottom: 1px solid #f1f5f9; margin-bottom: 4px;">
                        <?php echo e(__('Select Shop')); ?>

                    </li>
                    <?php $__currentLoopData = $userShops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isActive = $activeShopId == $s->id;
                        ?>
                        <li>
                            <form action="<?php echo e(route('dashboard.select-shop')); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="shop_id" value="<?php echo e($s->id); ?>">
                                <button type="submit" 
                                        class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 rounded-2" 
                                        style="font-size: 0.85rem; font-weight: 600; text-align: left; width: calc(100% - 16px); margin: 2px 8px; border: 0; 
                                               background: <?php echo e($isActive ? '#0f766e' : 'transparent'); ?>; 
                                               color: <?php echo e($isActive ? '#ffffff' : '#334155'); ?>; 
                                               transition: all 0.15s;"
                                        onmouseover="this.style.background='<?php echo e($isActive ? '#0f766e' : '#f1f5f9'); ?>'; this.style.color='<?php echo e($isActive ? '#ffffff' : '#0f172a'); ?>'"
                                        onmouseout="this.style.background='<?php echo e($isActive ? '#0f766e' : 'transparent'); ?>'; this.style.color='<?php echo e($isActive ? '#ffffff' : '#334155'); ?>'">
                                    <i class="bi bi-shop <?php echo e($isActive ? 'text-white' : 'text-muted'); ?>"></i>
                                    <span><?php echo e($s->name); ?></span>
                                </button>
                            </form>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php elseif(auth()->user()->isManager() && auth()->user()->assignedShop): ?>
        <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-600">
            <i class="bi bi-shop text-slate-500"></i>
            <span>Active Shop: <?php echo e(auth()->user()->assignedShop->name); ?></span>
        </div>
    <?php endif; ?>
</div>

<?php if(false): ?>

<?php endif; ?>


<div class="overall-stats">
    <div class="row g-3">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.total_shops')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_shops']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.total_products')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_products']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.sales_today')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_sales_today']); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.sales_count_today')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.revenue_today')); ?></div>
                <div class="stat-value"><?php echo e(number_format($overallMetrics['total_revenue_today'], 0)); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.revenue_formula')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.profit_today')); ?></div>
                <div class="stat-value"><?php echo e(number_format($overallMetrics['total_profit_today'], 0)); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.profit_formula')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.low_stock')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['low_stock_count']); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.low_stock_desc')); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="mb-4">
    <button class="btn btn-sm btn-outline-secondary btn-calc-guide"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#calcGuide"
            aria-expanded="false">
        <i class="bi bi-lightbulb me-1"></i> <?php echo e(__('dashboard.how_calculated')); ?>

    </button>
    <div class="collapse mt-2" id="calcGuide">
        <div class="p-3 rounded border calc-guide-panel" style="font-size:0.85rem;">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-primary"><i class="bi bi-cash-stack me-1"></i><?php echo e(__('dashboard.revenue_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.revenue_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.revenue_desc')); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-success"><i class="bi bi-graph-up me-1"></i><?php echo e(__('dashboard.profit_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.profit_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.profit_desc')); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1" style="color:#7c3aed;"><i class="bi bi-bank me-1"></i><?php echo e(__('dashboard.capital_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.capital_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.capital_desc')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mb-12">
    <div class="inline-flex items-center gap-2 mb-3 rounded-full px-3 py-1 bg-teal-500/10 border border-teal-500/20 text-teal-700 text-xs font-bold uppercase tracking-wider">
        <i class="bi bi-stars"></i>
        <span>Module Subscription Matrix</span>
    </div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Subscribed Modules & Features</h2>
    <p class="text-slate-500 text-sm mb-6">Manage settings and launch active modules available under your active shop context subscription.</p>

    <?php
        $modulesConfig = [
            'shop' => [
                'name' => 'Shop Setup',
                'desc' => 'Configure and manage retail shops and settings.',
                'icon' => 'bi-shop',
                'route' => 'shop.index'
            ],
            'branch' => [
                'name' => 'Branches',
                'desc' => 'Manage multiple physical locations and branches.',
                'icon' => 'bi-diagram-3',
                'route' => 'branch.index'
            ],
            'brand' => [
                'name' => 'Brands',
                'desc' => 'Organize inventory products by manufacturer brands.',
                'icon' => 'bi-bookmark-star',
                'route' => 'brand.index'
            ],
            'category' => [
                'name' => 'Categories',
                'desc' => 'Classify products into logical custom categories.',
                'icon' => 'bi-tags',
                'route' => 'category.index'
            ],
            'product' => [
                'name' => 'Products Catalog',
                'desc' => 'Maintain item lists, prices, and dynamic specs.',
                'icon' => 'bi-box-seam',
                'route' => 'product.index'
            ],
            'stock' => [
                'name' => 'Stock Levels',
                'desc' => 'Monitor real-time inventory and warehouse levels.',
                'icon' => 'bi-boxes',
                'route' => 'stock.index'
            ],
            'sale' => [
                'name' => 'Sales & Billing',
                'desc' => 'Check out customers, print invoices, track revenue.',
                'icon' => 'bi-cart-check',
                'route' => 'sale.index'
            ],
            'capital' => [
                'name' => 'Capital Ledger',
                'desc' => 'Audit business investments, logs, and net cash flow.',
                'icon' => 'bi-cash-coin',
                'route' => 'capital.index'
            ],
            'restock' => [
                'name' => 'Restock Logs',
                'desc' => 'Place vendor restock orders and track quantities.',
                'icon' => 'bi-arrow-repeat',
                'route' => 'restock.index'
            ],
            'damage' => [
                'name' => 'Damaged Goods',
                'desc' => 'Log broken or lost stock and leakage reports.',
                'icon' => 'bi-exclamation-triangle',
                'route' => 'damage.index'
            ],
            'report' => [
                'name' => 'Analytics & PnL',
                'desc' => 'Generate daily/monthly statements and sales charts.',
                'icon' => 'bi-bar-chart-line',
                'route' => 'report.index'
            ]
        ];
    ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php $__currentLoopData = $modulesConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modKey => $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $hasAccess = auth()->user()->hasModuleAccess($modKey);
            ?>
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md transition-all duration-200 relative group <?php echo e(!$hasAccess ? 'border-dashed' : ''); ?>">
                <?php if(!$hasAccess): ?>
                    <div class="absolute inset-0 bg-slate-50/10 backdrop-blur-[0.5px] rounded-2xl pointer-events-none z-10"></div>
                <?php endif; ?>

                
                <div class="flex items-start justify-between gap-4 mb-4 z-20">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center border shadow-inner <?php echo e($hasAccess ? 'bg-teal-50 border-teal-100 text-teal-600' : 'bg-slate-100 border-slate-200 text-slate-400'); ?>">
                        <i class="bi <?php echo e($mod['icon']); ?> text-lg"></i>
                    </div>
                    <div>
                        <?php if($hasAccess): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 border border-teal-100 text-teal-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                                Active
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 border border-amber-200 text-amber-700">
                                <i class="bi bi-lock-fill"></i>
                                Locked
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="flex-grow z-20">
                    <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-teal-600 transition-colors"><?php echo e($mod['name']); ?></h3>
                    <p class="text-slate-500 text-xs leading-relaxed"><?php echo e($mod['desc']); ?></p>
                </div>

                
                <div class="mt-5 border-t border-slate-100 pt-4 z-20">
                    <?php if($hasAccess): ?>
                        <a href="<?php echo e(route($mod['route'])); ?>" 
                           class="w-full inline-flex justify-center items-center gap-1 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-colors duration-150">
                            Open Module <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php else: ?>
                        <?php if(auth()->user()->isOwner()): ?>
                            <a href="<?php echo e(route('subscription.plans')); ?>" 
                               class="w-full inline-flex justify-center items-center gap-1.5 px-4 py-2 border border-amber-200 hover:border-amber-300 hover:bg-amber-50 text-amber-800 rounded-xl text-xs font-bold transition-all duration-150">
                                <i class="bi bi-stars"></i> Buy Subscription
                            </a>
                        <?php else: ?>
                            <button disabled 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold border border-slate-200 cursor-not-allowed">
                                <i class="bi bi-lock-fill me-1"></i> Locked
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="shop-metrics-section">
    <h2 class="shop-section-title">
        <i class="bi bi-shop"></i>
        <?php echo e(__('dashboard.shop_performance')); ?>

    </h2>

    <div class="shop-card-grid">
    <?php $__empty_1 = true; $__currentLoopData = $shopMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="shop-summary-card">
            <div class="shop-summary-header">
                <h3>
                    <i class="bi bi-shop-window"></i>
                    <?php echo e($metric['shop']->name); ?>

                </h3>
            </div>
            <div class="shop-summary-body">
                <div class="shop-summary-stats">
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.todays_sales')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['today_sales'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.total_amount_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.todays_profit')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['today_profit'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.profit_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.monthly_profit')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['monthly_profit'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.monthly_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.capital')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['total_capital'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.capital_val')); ?></span>
                    </div>
                </div>
            </div>
            <div class="shop-summary-footer">
                <button class="btn-show-details" data-shop-id="<?php echo e($metric['shop']->id); ?>">
                    <i class="bi bi-info-circle me-1"></i> <?php echo e(__('dashboard.show_details')); ?>

                </button>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="shop-summary-card">
            <div class="empty-state" style="padding: 3rem 2rem;">
                <i class="bi bi-shop" style="font-size:3rem; color:#94a3b8; display:block; margin-bottom:1rem;"></i>
                <h5 style="color:#64748b;"><?php echo e(__('dashboard.no_shops')); ?></h5>
                <p style="color:#94a3b8;"><?php echo e(__('dashboard.create_first_shop')); ?></p>
            </div>
        </div>
    <?php endif; ?>
    </div>


<div class="modal fade" id="shopDetailsModal" tabindex="-1" aria-labelledby="shopDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shopDetailsModalLabel"><?php echo e(__('dashboard.shop_details')); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="shopDetailsModalBody">
        
      </div>
    </div>
  </div>
</div>

</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-show-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var shopId = this.getAttribute('data-shop-id');
            var modalBody = document.getElementById('shopDetailsModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            var modal = new bootstrap.Modal(document.getElementById('shopDetailsModal'));
            modal.show();
            fetch('/dashboard/shop-details/' + shopId)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(function() {
                    modalBody.innerHTML = '<p class="text-danger">Failed to load shop details.</p>';
                });
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\byabsha-track\Modules/Dashboard\resources/views/index.blade.php ENDPATH**/ ?>