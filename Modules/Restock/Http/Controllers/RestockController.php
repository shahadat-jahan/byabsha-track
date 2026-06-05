<?php

namespace Modules\Restock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Restock\Services\RestockService;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\Models\ProductDynamicValue;
use Modules\Restock\Models\Restock;
use Yajra\DataTables\Facades\DataTables;

class RestockController extends Controller
{
    protected RestockService $restockService;

    public function __construct(RestockService $restockService)
    {
        $this->restockService = $restockService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->get();
        $filters = $request->only(['shop_id', 'date_from', 'date_to']);

        return view('restock::index', compact('shops', 'filters'));
    }

    public function restocksTable(Request $request)
    {
        $user = auth()->user();
        $allowedShopIds = $user->accessibleShopIds();

        $query = Restock::query()
            ->join('shops', 'shops.id', '=', 'restocks.shop_id')
            ->join('products', 'products.id', '=', 'restocks.product_id')
            ->leftJoin('product_batches', 'product_batches.id', '=', 'restocks.product_batch_id')
            ->whereIn('restocks.shop_id', $allowedShopIds)
            ->select([
                'restocks.id',
                'restocks.product_id',
                'restocks.shop_id',
                'restocks.quantity',
                'restocks.purchase_price_per_unit',
                'restocks.total_cost',
                'restocks.restock_date',
                'restocks.note',
                'restocks.product_batch_id',
                'shops.name as shop_name',
                'products.name as product_name',
                'products.stock_quantity as product_stock_quantity',
                'product_batches.batch_code as batch_code',
                'product_batches.attribute_values as batch_attribute_values',
            ]);

        $shopId = $request->input('shop_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($shopId) {
            $query->where('restocks.shop_id', $shopId);
        }
        if ($dateFrom) {
            $query->whereDate('restocks.restock_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('restocks.restock_date', '<=', $dateTo);
        }

        return DataTables::eloquent($query)
            ->filter(function ($q) {
                $search = request('search')['value'] ?? null;
                if ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('products.name', 'like', '%' . $search . '%')
                           ->orWhere('product_batches.batch_code', 'like', '%' . $search . '%')
                           ->orWhere('shops.name', 'like', '%' . $search . '%')
                           ->orWhere('restocks.note', 'like', '%' . $search . '%');
                    });
                }
            }, false)
            ->editColumn('restock_date', function (Restock $restock) {
                return $restock->restock_date->format('d M Y');
            })
            ->addColumn('shop_name_label', function (Restock $restock) {
                return '<span class="shop-pill">' . e($restock->shop_name ?? 'Deleted shop') . '</span>';
            })
            ->addColumn('batch_label', function (Restock $restock) {
                return e($restock->batch_code ?? '-');
            })
            ->addColumn('attribute_summary', function (Restock $restock) {
                $attrs = $restock->batch_attribute_values;
                if (is_string($attrs)) {
                    $attrs = json_decode($attrs, true);
                }
                if (!is_array($attrs) || empty($attrs)) {
                    return '-';
                }
                return collect($attrs)
                    ->map(fn($item) => ($item['label'] ?? $item['field_key'] ?? 'Attribute') . ': ' . ($item['value'] ?? ''))
                    ->implode(' | ');
            })
            ->editColumn('quantity', function (Restock $restock) {
                return '<span class="qty-pill">+' . number_format($restock->quantity) . '</span>';
            })
            ->editColumn('purchase_price_per_unit', function (Restock $restock) {
                return number_format($restock->purchase_price_per_unit, 2);
            })
            ->editColumn('total_cost', function (Restock $restock) {
                return '<strong>' . number_format($restock->total_cost, 2) . '</strong>';
            })
            ->addColumn('current_stock_label', function (Restock $restock) {
                $stock = $restock->product_stock_quantity;
                $class = $stock > 0 ? 'stock-pill-ok' : 'stock-pill-out';
                return '<span class="stock-pill ' . $class . '">' . ($stock !== null ? number_format($stock) : 'N/A') . '</span>';
            })
            ->editColumn('note', function (Restock $restock) {
                if ($restock->note) {
                    return '<span class="text-muted small" title="' . e($restock->note) . '">' . e(\Str::limit($restock->note, 30)) . '</span>';
                }
                return '<span class="text-muted">—</span>';
            })
            ->addColumn('actions', function (Restock $restock) {
                $batchUrl = route('product.batches', $restock->product_id);
                $editUrl = route('restock.edit', $restock->id);
                $deleteUrl = route('restock.destroy', $restock->id);
                $confirmMsg = __('restock.confirm_delete');

                $deleteForm = '<form action="' . e($deleteUrl) . '" method="POST" class="d-inline" onsubmit="return confirm(\'' . e($confirmMsg) . '\')">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-row-action btn-row-delete" title="' . e(__('app.delete')) . '">'
                    . '<i class="bi bi-trash"></i>'
                    . '</button>'
                    . '</form>';

                return '<div class="d-flex gap-1 justify-content-end">'
                    . '<a href="' . e($batchUrl) . '" class="btn btn-sm btn-row-action" style="color:#0f766e;border-color:rgba(15,118,110,.35);background:#fff;" title="View Batch Tracker"><i class="bi bi-layers"></i></a>'
                    . '<a href="' . e($editUrl) . '" class="btn btn-sm btn-row-action btn-row-edit" title="' . e(__('app.edit')) . '"><i class="bi bi-pencil"></i></a>'
                    . $deleteForm
                    . '</div>';
            })
            ->rawColumns(['shop_name_label', 'quantity', 'total_cost', 'current_stock_label', 'note', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'restock')) {
            return redirect()->route('restock.index')->with('error', 'Restock is not available on your current plan. Please upgrade to access this feature.');
        }
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();

        // Pre-fill shop/product when arriving from the batch tracker page
        $prefilledShopId    = request()->integer('shop_id') ?: null;
        $prefilledProductId = request()->integer('product_id') ?: null;

        return view('restock::create', compact('shops', 'products', 'prefilledShopId', 'prefilledProductId'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'restock')) {
            return redirect()->route('restock.index')->with('error', 'Restock is not available on your current plan. Please upgrade to access this feature.');
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        // Ensure product belongs to the selected shop
        $product = Product::where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product) {
            return back()->withInput()
                ->withErrors(['product_id' => __('restock.product_shop_mismatch')]);
        }

        $validated['attribute_values'] = $this->validateAndNormalizeAttributeValues(
            $product,
            (array) $request->input('attribute_values', [])
        );

        $this->restockService->storeRestock($validated);

        return redirect()->route('restock.index')
            ->with('success', __('restock.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $restock = $this->restockService->getRestock($id);
        abort_unless($user->ownsShop((int) $restock->shop_id), 403, 'You do not have access to this shop.');
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();

        return view('restock::edit', compact('restock', 'shops', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        // Ensure product belongs to the selected shop
        $product = Product::where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product) {
            return back()->withInput()
                ->withErrors(['product_id' => __('restock.product_shop_mismatch')]);
        }

        $validated['attribute_values'] = $this->validateAndNormalizeAttributeValues(
            $product,
            (array) $request->input('attribute_values', [])
        );

        $this->restockService->updateRestock($id, $validated);

        return redirect()->route('restock.index')
            ->with('success', __('restock.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $restock = $this->restockService->getRestock($id);
        abort_unless($user->ownsShop((int) $restock->shop_id), 403, 'You do not have access to this shop.');

        Log::info('Destroy method called with ID: ' . $id);
        try {
            $this->restockService->deleteRestock($id);
            Log::info('Restock deleted successfully');
            return redirect()->route('restock.index')
                ->with('success', __('restock.deleted'));
        } catch (\Exception $e) {
            Log::error('Delete failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * API endpoint: return products for a given shop (JSON).
     */
    public function productsByShop(Request $request)
    {
        $user = auth()->user();
        $shopId = (int) $request->shop_id;
        abort_unless($user->ownsShop($shopId), 403, 'You do not have access to this shop.');

        $products = Product::query()
            ->where('shop_id', $shopId)
            ->with([
                'dynamicValues' => function ($query) {
                    $query->with('dynamicField:id,field_key,label,input_type,is_required,options');
                },
            ])
            ->select('id', 'name', 'purchase_price', 'stock_quantity')
            ->orderBy('name')
            ->get();

        return response()->json(
            $products->map(function (Product $product) {
                $attributes = $product->dynamicValues
                    ->filter(fn ($value) => $value->dynamicField && filled($value->value))
                    ->map(function (ProductDynamicValue $value) {
                        return [
                            'field_id' => (int) $value->product_dynamic_field_id,
                            'field_key' => (string) ($value->dynamicField->field_key ?? ''),
                            'label' => (string) ($value->dynamicField->label ?? 'Attribute'),
                            'input_type' => (string) ($value->dynamicField->input_type ?? 'text'),
                            'is_required' => (bool) ($value->dynamicField->is_required ?? false),
                            'options' => (array) ($value->dynamicField->options ?? []),
                            'value' => (string) ($value->value ?? ''),
                        ];
                    })
                    ->values();

                return [
                    'id' => (int) $product->id,
                    'name' => (string) $product->name,
                    'purchase_price' => (float) $product->purchase_price,
                    'stock_quantity' => (int) $product->stock_quantity,
                    'attributes' => $attributes,
                ];
            })->values()
        );
    }

    private function validateAndNormalizeAttributeValues(Product $product, array $submittedAttributes): array
    {
        $assignedAttributes = $product->dynamicValues()
            ->with('dynamicField:id,field_key,label,input_type,is_required,options')
            ->get()
            ->filter(fn ($value) => $value->dynamicField && filled($value->value));

        if ($assignedAttributes->isEmpty()) {
            return [];
        }

        $rules = [];
        foreach ($assignedAttributes as $assignedAttribute) {
            $field = $assignedAttribute->dynamicField;
            $key = 'attribute_values.' . $assignedAttribute->product_dynamic_field_id;
            $rules[$key] = ($field->is_required ? 'required' : 'nullable') . '|string|max:255';
        }

        Validator::make(['attribute_values' => $submittedAttributes], $rules)->validate();

        return $assignedAttributes
            ->map(function (ProductDynamicValue $assignedAttribute) use ($submittedAttributes) {
                $field = $assignedAttribute->dynamicField;
                $fieldId = (int) $assignedAttribute->product_dynamic_field_id;
                $submitted = trim((string) ($submittedAttributes[$fieldId] ?? ''));

                if ($submitted !== '' && $field->input_type === 'select') {
                    $options = collect($field->options ?? [])->map(fn ($item) => (string) $item);
                    if ($options->isNotEmpty() && !$options->contains($submitted)) {
                        throw ValidationException::withMessages([
                            'attribute_values.' . $fieldId => __('validation.in', ['attribute' => $field->label]),
                        ]);
                    }
                }

                return [
                    'field_id' => $fieldId,
                    'field_key' => (string) ($field->field_key ?? ''),
                    'label' => (string) ($field->label ?? 'Attribute'),
                    'value' => $submitted,
                ];
            })
            ->filter(fn (array $item) => $item['value'] !== '')
            ->values()
            ->all();
    }
}
