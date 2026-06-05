<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Models\ProductBatch;
use Modules\Sale\Models\Sale;
use Modules\Sale\Services\WarrantyExchangeService;
use Modules\Shop\Models\Shop;
use Yajra\DataTables\Facades\DataTables;

class ExchangeController extends Controller
{
    public function __construct(protected WarrantyExchangeService $service)
    {
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $filters = $request->only(['shop_id', 'type']);

        return view('sale::exchanges.index', compact('shops', 'filters'));
    }

    public function exchangesTable(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $allowedShopIds = $user->accessibleShopIds();

        $shopId = $request->input('shop_id');
        $type = $request->input('type');

        if ($shopId && !in_array((int) $shopId, $allowedShopIds, true)) {
            abort(403, 'You do not have access to this shop.');
        }

        $query = \Modules\Sale\Models\SaleExchange::query()
            ->join('shops', 'shops.id', '=', 'sale_exchanges.shop_id')
            ->join('sales', 'sales.id', '=', 'sale_exchanges.sale_id')
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->leftJoin('users', 'users.id', '=', 'sale_exchanges.created_by')
            ->whereIn('sale_exchanges.shop_id', $allowedShopIds)
            ->select([
                'sale_exchanges.id',
                'sale_exchanges.shop_id',
                'sale_exchanges.sale_id',
                'sale_exchanges.original_batch_id',
                'sale_exchanges.replacement_batch_id',
                'sale_exchanges.quantity',
                'sale_exchanges.exchange_date',
                'sale_exchanges.exchange_type',
                'sale_exchanges.reason',
                'sale_exchanges.note',
                'sale_exchanges.cost_difference',
                'sale_exchanges.status',
                'sale_exchanges.created_by',
                'shops.name as shop_name',
                'products.name as product_name',
                'users.name as creator_name',
            ]);

        if ($shopId) {
            $query->where('sale_exchanges.shop_id', (int) $shopId);
        }

        if ($type) {
            $query->where('sale_exchanges.exchange_type', $type);
        }

        return DataTables::eloquent($query)
            ->filter(function ($q) {
                $search = request('search')['value'] ?? null;
                if ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('sale_exchanges.sale_id', 'like', '%' . $search . '%')
                            ->orWhere('products.name', 'like', '%' . $search . '%')
                            ->orWhere('shops.name', 'like', '%' . $search . '%')
                            ->orWhere('sale_exchanges.reason', 'like', '%' . $search . '%');
                    });
                }
            }, false)
            ->addColumn('sale_reference_label', function (\Modules\Sale\Models\SaleExchange $exchange) {
                return '<strong class="text-teal">#' . e($exchange->sale_id) . '</strong>';
            })
            ->addColumn('shop_name_label', function (\Modules\Sale\Models\SaleExchange $exchange) {
                return '<span class="badge bg-light text-dark border"><i class="bi bi-shop"></i> ' . e($exchange->shop_name ?? '-') . '</span>';
            })
            ->addColumn('exchange_date_label', function (\Modules\Sale\Models\SaleExchange $exchange) {
                return '<span class="text-semibold-muted" style="font-size: 0.82rem;"><i class="bi bi-calendar3"></i> ' . e($exchange->exchange_date->format('d M Y')) . '</span>';
            })
            ->addColumn('exchange_type_label', function (\Modules\Sale\Models\SaleExchange $exchange) {
                $badgeClass = $exchange->exchange_type === 'replacement' ? 'replacement' : 'return';
                $iconClass = $exchange->exchange_type === 'replacement' ? 'bi-arrow-left-right' : 'bi-arrow-return-left';
                return '<span class="exchange-badge exchange-badge-' . $badgeClass . '"><i class="bi ' . $iconClass . '"></i> ' . e(__('sale.exchange_type_' . $exchange->exchange_type)) . '</span>';
            })
            ->addColumn('cost_difference_label', function (\Modules\Sale\Models\SaleExchange $exchange) {
                $diffVal = (float)$exchange->cost_difference;
                $diffClass = $diffVal > 0 ? 'positive' : ($diffVal < 0 ? 'negative' : 'neutral');
                return '<span class="diff-badge diff-badge-' . $diffClass . '">' . e(number_format($diffVal, 2)) . '</span>';
            })
            ->rawColumns(['sale_reference_label', 'shop_name_label', 'exchange_date_label', 'exchange_type_label', 'cost_difference_label'])
            ->make(true);
    }

    public function create()
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $shopIds = $user->accessibleShopIds();

        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $sales = Sale::query()
            ->with(['product' => fn ($q) => $q->withTrashed(), 'shop' => fn ($q) => $q->withTrashed(), 'productBatch' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $shopIds)
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $batches = ProductBatch::query()
            ->with(['product' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $shopIds)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('batch_date')
            ->orderBy('id')
            ->limit(500)
            ->get();

        return view('sale::exchanges.create', compact('shops', 'sales', 'batches'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'sale_id' => 'required|exists:sales,id',
            'quantity' => 'required|integer|min:1',
            'exchange_date' => 'required|date',
            'exchange_type' => 'required|in:replacement,return_only',
            'replacement_batch_id' => 'nullable|exists:product_batches,id',
            'reason' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:2000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['created_by'] = (int) $user->id;
        $validated['reason'] = $validated['reason'] ?? 'defective';

        $this->service->createExchange($validated);

        return redirect()->route('sale.exchanges.index')->with('success', __('sale.exchange_created'));
    }
}
