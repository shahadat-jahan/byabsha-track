<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    private function authorizedFilters(Request $request, array $extra = []): array
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $shopIds = $user->accessibleShopIds();

        $shopId = $request->input('shop_id');
        if ($shopId && !$user->ownsShop((int) $shopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        return array_merge([
            'shop_id'  => $shopId,
            'shop_ids' => $shopIds,
        ], $extra);
    }

    public function index(Request $request)
    {
        // Ensure user is authorized for requested shop and populate shop_ids
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->subDays(6)->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $salesSummary = $this->reportService->getSalesSummary($filters);
        $warrantyExchangeSummary = $this->reportService->getWarrantyExchangeSummary($filters);
        $dailySales = $this->reportService->getDailySales($filters);
        $dailyDates = collect($dailySales)
            ->pluck('date')
            ->map(fn ($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
            ->all();
        $dailySalesDetails = $this->reportService->getSalesDetailsForDates($filters, $dailyDates);
        $monthlyOverview = $this->reportService->getDailyProfitLoss([
            'shop_id' => $filters['shop_id'],
            'month' => now()->format('Y-m'),
        ]);
        $yearlyOverview = $this->reportService->getMonthlyProfitLoss([
            'shop_id' => $filters['shop_id'],
            'year' => now()->format('Y'),
        ]);
        $shops = $this->reportService->getShops();

        return view('report::index', compact(
            'salesSummary',
            'warrantyExchangeSummary',
            'dailySales',
            'dailySalesDetails',
            'monthlyOverview',
            'yearlyOverview',
            'shops',
            'filters'
        ));
    }

    public function printIndex(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->subDays(6)->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $salesSummary = $this->reportService->getSalesSummary($filters);
        $warrantyExchangeSummary = $this->reportService->getWarrantyExchangeSummary($filters);
        $dailySales = $this->reportService->getDailySales($filters);
        $dailyDates = collect($dailySales)
            ->pluck('date')
            ->map(fn ($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
            ->all();
        $dailySalesDetails = $this->reportService->getSalesDetailsForDates($filters, $dailyDates);
        $monthlyOverview = $this->reportService->getDailyProfitLoss([
            'shop_id'  => $filters['shop_id'],
            'shop_ids' => $filters['shop_ids'],
            'month'    => now()->format('Y-m'),
        ]);
        $yearlyOverview = $this->reportService->getMonthlyProfitLoss([
            'shop_id'  => $filters['shop_id'],
            'shop_ids' => $filters['shop_ids'],
            'year'     => now()->format('Y'),
        ]);
        $shops = $this->reportService->getShops($filters['shop_ids']);
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        return view('report::print-index', compact(
            'salesSummary',
            'warrantyExchangeSummary',
            'dailySales',
            'dailySalesDetails',
            'monthlyOverview',
            'yearlyOverview',
            'shops',
            'filters',
            'shopName'
        ));
    }

    public function sales(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $sales = $this->reportService->getPaginatedSales($filters);
        $salesSummary = $this->reportService->getSalesSummary($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);

        return view('report::sales', compact('sales', 'salesSummary', 'shops', 'filters'));
    }

    public function products(Request $request)
    {
        $filters = $this->authorizedFilters($request);

        $products = $this->reportService->getProductReport($filters);
        $stockSummary = $this->reportService->getStockSummary($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);

        return view('report::products', compact('products', 'stockSummary', 'shops', 'filters'));
    }

    public function shops(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $shopData = $this->reportService->getShopComparison($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);

        return view('report::shops', compact('shopData', 'shops', 'filters'));
    }

    public function daily(Request $request)
    {
        $user = Auth::user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'daily_pl')) {
            return redirect()->route('report.index')->with('error', 'Daily P&L is not available on your current plan. Please upgrade to access this feature.');
        }
        $filters = $this->authorizedFilters($request, [
            'month' => $request->input('month', now()->format('Y-m')),
        ]);

        $dailyData = $this->reportService->getDailyProfitLoss($filters);
        $startDate = $filters['month'] . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $dailyDetailsByDate = $this->reportService
            ->getSalesDetailsByDateRange($filters, $startDate, $endDate)
            ->groupBy(fn ($sale) => $sale->sale_date->format('Y-m-d'));
        $shops = $this->reportService->getShops($filters['shop_ids']);

        return view('report::daily', compact('dailyData', 'dailyDetailsByDate', 'shops', 'filters'));
    }

    public function monthly(Request $request)
    {
        $user = Auth::user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'monthly_pl')) {
            return redirect()->route('report.index')->with('error', 'Monthly P&L is not available on your current plan. Please upgrade to access this feature.');
        }
        $filters = $this->authorizedFilters($request, [
            'year' => $request->input('year', now()->format('Y')),
        ]);

        $monthlyData = $this->reportService->getMonthlyProfitLoss($filters);
        $startDate = $filters['year'] . '-01-01';
        $endDate = $filters['year'] . '-12-31';
        $monthlyDetailsByMonth = $this->reportService
            ->getSalesDetailsByDateRange($filters, $startDate, $endDate)
            ->groupBy(fn ($sale) => $sale->sale_date->format('n'));
        $shops = $this->reportService->getShops($filters['shop_ids']);

        return view('report::monthly', compact('monthlyData', 'monthlyDetailsByMonth', 'shops', 'filters'));
    }

    public function exportDailyPdf(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'month' => $request->input('month', now()->format('Y-m')),
        ]);

        $dailyData = $this->reportService->getDailyProfitLoss($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.daily-pdf', compact('dailyData', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'daily-report-' . $filters['month'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportMonthlyPdf(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'year' => $request->input('year', now()->format('Y')),
        ]);

        $monthlyData = $this->reportService->getMonthlyProfitLoss($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.monthly-pdf', compact('monthlyData', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'monthly-report-' . $filters['year'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportSalesPdf(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $sales = $this->reportService->getPaginatedSales($filters, 1000);
        $salesSummary = $this->reportService->getSalesSummary($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.sales-pdf', compact('sales', 'salesSummary', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'sales-report-' . $filters['start_date'] . '-to-' . $filters['end_date'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportProductsPdf(Request $request)
    {
        $filters = $this->authorizedFilters($request);

        $products = $this->reportService->getProductReport($filters);
        $stockSummary = $this->reportService->getStockSummary($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.products-pdf', compact('products', 'stockSummary', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'products-report-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportShopsPdf(Request $request)
    {
        $filters = $this->authorizedFilters($request, [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date'   => $request->input('end_date', now()->format('Y-m-d')),
        ]);

        $shopData = $this->reportService->getShopComparison($filters);
        $shops = $this->reportService->getShops($filters['shop_ids']);

        $pdf = Pdf::loadView('report::pdf.shops-pdf', compact('shopData', 'shops', 'filters'))
            ->setPaper('a4', 'landscape');

        $filename = 'shops-report-' . $filters['start_date'] . '-to-' . $filters['end_date'] . '.pdf';

        return $pdf->download($filename);
    }
}
