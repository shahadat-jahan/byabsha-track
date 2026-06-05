<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;
use Modules\Shop\Models\Shop;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'stocks')) {
            return redirect()->route('dashboard.index')->with('error', 'Stocks are not available on your current plan. Please upgrade to access this feature.');
        }
        $allowedShopIds = $user->accessibleShopIds();

        $selectedShopId = $request->filled('shop_id') ? (int) $request->input('shop_id') : null;
        $searchTerm = trim((string) $request->input('search', ''));

        // Prevent accessing a shop the user doesn't own
        if ($selectedShopId && !in_array((int) $selectedShopId, $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }

        if ($request->ajax()) {
            $supportsModelName = Schema::hasColumn('products', 'model_name');
            $lowStockAlert = (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5);

            $query = Product::with(['shop', 'creator:id,name', 'dynamicValues.dynamicField'])
                ->whereIn('shop_id', $allowedShopIds);

            return datatables()->of($query)
                ->filter(function ($query) use ($request, $supportsModelName) {
                    if ($request->filled('shop_id')) {
                        $query->where('shop_id', $request->integer('shop_id'));
                    }

                    $datatableSearch = $request->input('search.value');
                    $customSearch = $request->input('search');
                    $searchTerm = trim($datatableSearch ?: $customSearch ?: '');

                    if ($searchTerm !== '') {
                        $this->applySearchFilter($query, $searchTerm, $supportsModelName);
                    }
                })
                ->addColumn('shop_badge', function ($product) {
                    $shopName = $product->shop?->name ?? __('stock::stock.deleted_shop');
                    return '<span class="custom-shop-badge"><i class="bi bi-shop"></i> ' . e($shopName) . '</span>';
                })
                ->addColumn('creator_name', function ($product) {
                    return $product->creator?->name ?? '-';
                })
                ->addColumn('custom_attributes', function ($product) {
                    $attributes = $this->buildAttributeSummaryItems($product);
                    if (empty($attributes)) {
                        return '<span class="text-muted">-</span>';
                    }

                    $html = '<div class="attribute-badge-list">';
                    foreach ($attributes as $attr) {
                        $html .= '<span class="custom-attr-badge" title="' . e($attr['label']) . ': ' . e($attr['value']) . '">';
                        $html .= '<strong>' . e($attr['label']) . ':</strong> ' . e($attr['value']);
                        $html .= '</span>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('purchase_price', function ($product) {
                    return number_format($product->purchase_price, 2);
                })
                ->editColumn('sale_price', function ($product) {
                    return number_format($product->sale_price, 2);
                })
                ->editColumn('stock_quantity', function ($product) use ($lowStockAlert) {
                    if ($product->stock_quantity <= 0) {
                        return '<span class="status-pill status-pill-danger"><span class="status-indicator"></span>' . __('stock::stock.out') . '</span>';
                    } elseif ($product->stock_quantity <= $lowStockAlert) {
                        return '<span class="status-pill status-pill-warning"><span class="status-indicator"></span>' . $product->stock_quantity . '</span>';
                    } else {
                        return '<span class="status-pill status-pill-success"><span class="status-indicator"></span>' . $product->stock_quantity . '</span>';
                    }
                })
                ->addColumn('stock_value', function ($product) {
                    return '<strong>' . number_format($product->stock_quantity * $product->purchase_price, 2) . '</strong>';
                })
                ->rawColumns(['shop_badge', 'custom_attributes', 'stock_quantity', 'stock_value'])
                ->make(true);
        }

        $shops = Shop::forUser($user)->with(['products' => function ($query) {
            $query->select('id', 'shop_id', 'stock_quantity', 'purchase_price', 'sale_price');
        }])->get();

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        $attributesByProductId = [];

        return view('stock::index', [
            'shops' => $shops,
            'products' => $products,
            'attributesByProductId' => $attributesByProductId,
            'selectedShopId' => $selectedShopId,
            'searchTerm' => $searchTerm,
        ]);
    }

    private function applySearchFilter($query, string $searchTerm, bool $supportsModelName): void
    {
        $like = '%' . $searchTerm . '%';

        $query->where(function ($searchQuery) use ($like, $supportsModelName) {
            $searchQuery->where('name', 'like', $like)
                ->orWhere('brand', 'like', $like)
                ->orWhere('category', 'like', $like);

            if ($supportsModelName) {
                $searchQuery->orWhere('model_name', 'like', $like);
            }

            $searchQuery->orWhereHas('dynamicValues', function ($dynamicValueQuery) use ($like) {
                $dynamicValueQuery
                    ->where('value', 'like', $like)
                    ->orWhereHas('dynamicField', function ($fieldQuery) use ($like) {
                        $fieldQuery
                            ->where('label', 'like', $like)
                            ->orWhere('field_key', 'like', $like);
                    });
            });
        });
    }

    private function buildAttributeSummaryItems(Product $product): array
    {
        return $product->dynamicValues
            ->filter(fn ($value) => $value->dynamicField && filled($value->value))
            ->map(function ($value) {
                return [
                    'label' => (string) ($value->dynamicField->label ?? 'Attribute'),
                    'field_key' => (string) ($value->dynamicField->field_key ?? ''),
                    'value' => (string) $value->value,
                ];
            })
            ->sortBy('label')
            ->values()
            ->all();
    }
}
