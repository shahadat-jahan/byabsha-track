<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleWarranty;
use Modules\Sale\Services\WarrantyExchangeService;
use Modules\Shop\Models\Shop;
use Yajra\DataTables\Facades\DataTables;

class WarrantyController extends Controller
{
    public function __construct(protected WarrantyExchangeService $service) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $filters = $request->only(['shop_id', 'status']);

        return view('sale::warranties.index', compact('shops', 'filters'));
    }

    public function warrantiesTable(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        $allowedShopIds = $user->accessibleShopIds();

        $shopId = $request->input('shop_id');
        $status = $request->input('status');

        if ($shopId && ! in_array((int) $shopId, $allowedShopIds, true)) {
            abort(403, 'You do not have access to this shop.');
        }

        $query = SaleWarranty::query()
            ->join('shops', 'shops.id', '=', 'sale_warranties.shop_id')
            ->join('sales', 'sales.id', '=', 'sale_warranties.sale_id')
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->leftJoin('users', 'users.id', '=', 'sale_warranties.created_by')
            ->whereIn('sale_warranties.shop_id', $allowedShopIds)
            ->select([
                'sale_warranties.id',
                'sale_warranties.warranty_code',
                'sale_warranties.shop_id',
                'sale_warranties.sale_id',
                'sale_warranties.coverage_quantity',
                'sale_warranties.start_date',
                'sale_warranties.end_date',
                'sale_warranties.status',
                'sale_warranties.terms',
                'sale_warranties.created_by',
                'shops.name as shop_name',
                'products.name as product_name',
                'sales.customer_name as customer_name',
                'users.name as creator_name',
            ]);

        if ($shopId) {
            $query->where('sale_warranties.shop_id', (int) $shopId);
        }

        if ($status) {
            if ($status === 'expired') {
                $query->where('sale_warranties.status', 'active')
                    ->whereDate('sale_warranties.end_date', '<', now()->toDateString());
            } else {
                $query->where('sale_warranties.status', $status);
                if ($status === 'active') {
                    $query->whereDate('sale_warranties.end_date', '>=', now()->toDateString());
                }
            }
        }

        return DataTables::eloquent($query)
            ->filter(function ($q) {
                $search = request('search')['value'] ?? null;
                if ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('sale_warranties.warranty_code', 'like', '%'.$search.'%')
                            ->orWhere('products.name', 'like', '%'.$search.'%')
                            ->orWhere('sales.customer_name', 'like', '%'.$search.'%')
                            ->orWhere('shops.name', 'like', '%'.$search.'%');
                    });
                }
            }, false)
            ->addColumn('warranty_code_label', function (SaleWarranty $warranty) {
                return '<strong class="text-teal" style="font-size: 0.88rem;"><i class="bi bi-shield-check"></i> '.e($warranty->warranty_code).'</strong>';
            })
            ->addColumn('shop_name_label', function (SaleWarranty $warranty) {
                return '<span class="badge bg-light text-dark border"><i class="bi bi-shop"></i> '.e($warranty->shop_name ?? '-').'</span>';
            })
            ->addColumn('warranty_period_label', function (SaleWarranty $warranty) {
                return '<span class="text-semibold-muted" style="font-size: 0.82rem;"><i class="bi bi-calendar-event"></i> '.e($warranty->start_date->format('d M Y')).' - '.e($warranty->end_date->format('d M Y')).'</span>';
            })
            ->addColumn('status_label', function (SaleWarranty $warranty) {
                $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
                $pillClass = $effectiveStatus === 'active' ? 'success' : ($effectiveStatus === 'claimed' ? 'info' : ($effectiveStatus === 'expired' ? 'warning' : 'danger'));

                return '<span class="status-pill status-pill-'.$pillClass.'"><span class="status-indicator"></span>'.e(__('sale.status_'.$effectiveStatus)).'</span>';
            })
            ->addColumn('actions', function (SaleWarranty $warranty) {
                $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
                if ($effectiveStatus === 'active') {
                    $claimUrl = route('sale.warranties.claim', $warranty->id);
                    $voidUrl = route('sale.warranties.void', $warranty->id);
                    $csrf = csrf_field();

                    return '
                        <div class="d-flex gap-1 justify-content-center">
                            <form method="POST" action="'.$claimUrl.'" class="d-inline" onsubmit="return confirm(\'Confirm warranty claim?\')">
                                '.$csrf.'
                                <button type="submit" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle"></i> '.e(__('sale.warranty_mark_claimed')).'
                                </button>
                            </form>
                            <form method="POST" action="'.$voidUrl.'" class="d-inline" onsubmit="return confirm(\'Void this warranty?\')">
                                '.$csrf.'
                                <button type="submit" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-x-circle"></i> '.e(__('sale.warranty_void')).'
                                </button>
                            </form>
                        </div>
                    ';
                }

                return '<span class="text-muted">-</span>';
            })
            ->rawColumns(['warranty_code_label', 'shop_name_label', 'warranty_period_label', 'status_label', 'actions'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        $sales = Sale::query()
            ->with(['product' => fn ($q) => $q->withTrashed(), 'shop' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $user->accessibleShopIds())
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('sale::warranties.create', compact('shops', 'sales'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'sale_id' => 'required|exists:sales,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms' => 'nullable|string|max:2000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['created_by'] = (int) $user->id;
        $this->service->createWarranty($validated);

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_created'));
    }

    public function claim(Request $request, int $id)
    {
        $request->validate([
            'claim_note' => 'nullable|string|max:2000',
        ]);

        $warranty = SaleWarranty::findOrFail($id);
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        abort_unless($user->ownsShop((int) $warranty->shop_id), 403, 'You do not have access to this shop.');

        $this->service->markWarrantyClaimed($id, $request->input('claim_note'));

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_claimed'));
    }

    public function void(Request $request, int $id)
    {
        $request->validate([
            'claim_note' => 'nullable|string|max:2000',
        ]);

        $warranty = SaleWarranty::findOrFail($id);
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var User $user */
        abort_unless($user->ownsShop((int) $warranty->shop_id), 403, 'You do not have access to this shop.');

        $this->service->voidWarranty($id, $request->input('claim_note'));

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_voided'));
    }
}
