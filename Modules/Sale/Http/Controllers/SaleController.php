<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleBatchItem;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Restock\Models\Restock;
use Modules\Product\Models\ProductBatch;
use Modules\Capital\Services\CapitalService;
use Modules\Product\Services\ProductBatchService;
use Modules\Sale\Services\WarrantyExchangeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    protected $capitalService;
    protected ProductBatchService $productBatchService;
    protected WarrantyExchangeService $warrantyExchangeService;

    public function __construct(
        CapitalService $capitalService,
        ProductBatchService $productBatchService,
        WarrantyExchangeService $warrantyExchangeService
    )
    {
        $this->capitalService = $capitalService;
        $this->productBatchService = $productBatchService;
        $this->warrantyExchangeService = $warrantyExchangeService;
    }

    /**
     * Deduct $quantity units from restock batches using FIFO order.
     *
     * Must be called inside a DB::transaction with row-level locking.
     *
     * Returns an array with:
     *   - 'items'                     => array of [restock_id, quantity, purchase_price_per_unit]
     *   - 'weighted_avg_cost'         => total weighted-average cost per unit
     */
    private function deductFIFO(int $productId, int $shopId, int $quantity): array
    {
        $batches = Restock::where('product_id', $productId)
            ->where('shop_id', $shopId)
            ->where('remaining_quantity', '>', 0)
            ->whereNull('deleted_at')
            ->orderBy('restock_date', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        $remaining = $quantity;
        $items = [];
        $totalCost = 0.0;

        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }

            $take = min($remaining, $batch->remaining_quantity);
            $batch->decrement('remaining_quantity', $take);

            $items[] = [
                'restock_id'              => $batch->id,
                'quantity'                => $take,
                'purchase_price_per_unit' => (float) $batch->purchase_price_per_unit,
            ];

            $totalCost += $take * (float) $batch->purchase_price_per_unit;
            $remaining -= $take;
        }

        // If there are not enough batch units (e.g. data pre-dates batch tracking),
        // fall back to the product's reference purchase_price for the unmatched qty.
        if ($remaining > 0) {
            $product = Product::withTrashed()->find($productId);
            $fallbackPrice = $product ? (float) $product->purchase_price : 0.0;
            $totalCost += $remaining * $fallbackPrice;
            // No batch item is created for the fallback portion.
        }

        $weightedAvgCost = $quantity > 0 ? round($totalCost / $quantity, 2) : 0.0;

        return [
            'items'            => $items,
            'weighted_avg_cost' => $weightedAvgCost,
        ];
    }

    /**
     * Restore batch quantities consumed by a sale (reverse FIFO deduction).
     *
     * Must be called inside a DB::transaction.
     */
    private function restoreBatchItems(Sale $sale): void
    {
        foreach ($sale->batchItems as $item) {
            Restock::withTrashed()
                ->where('id', $item->restock_id)
                ->increment('remaining_quantity', $item->quantity);
        }

        $sale->batchItems()->delete();
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id', (int) optional($shops->first())->id);

        // Ensure the selected shop belongs to the user
        if ($selectedShopId && !$user->ownsShop($selectedShopId)) {
            $selectedShopId = (int) optional($shops->first())->id;
        }

        return view('sale::index', compact('shops', 'selectedShopId'));
    }

    public function productsTable(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);

        $shopId = (int) $validated['shop_id'];
        abort_unless(auth()->user()->ownsShop($shopId), 403, 'You do not have access to this shop.');

        $batches = ProductBatch::query()
            ->join('products', 'products.id', '=', 'product_batches.product_id')
            ->with(['product.dynamicValues.dynamicField'])
            ->where('product_batches.shop_id', $shopId)
            ->where('remaining_quantity', '>', 0)
            ->select([
                'product_batches.id',
                'product_batches.product_id',
                'product_batches.shop_id',
                'product_batches.batch_code',
                'product_batches.attribute_values',
                'product_batches.purchase_price',
                'product_batches.remaining_quantity',
                'product_batches.batch_date',
                'products.name as product_name',
                'products.category as product_category',
                'products.has_free_service',
                'products.free_service_duration_value',
                'products.free_service_duration_unit',
                'products.free_service_terms',
            ])
            ->addSelect([
                'latest_profit' => Sale::query()
                    ->selectRaw('COALESCE(SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END), 0)')
                    ->whereColumn('sales.product_batch_id', 'product_batches.id')
                    ->where('sales.shop_id', $shopId)
                    ->limit(1),
                'latest_loss' => Sale::query()
                    ->selectRaw('COALESCE(SUM(CASE WHEN profit < 0 THEN ABS(profit) ELSE 0 END), 0)')
                    ->whereColumn('sales.product_batch_id', 'product_batches.id')
                    ->where('sales.shop_id', $shopId)
                    ->limit(1),
            ]);

        return DataTables::eloquent($batches)
            ->filter(function ($query) {
                $search = request('search')['value'] ?? null;
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('products.name', 'like', '%' . $search . '%')
                            ->orWhere('product_batches.batch_code', 'like', '%' . $search . '%')
                            ->orWhereRaw("JSON_SEARCH(product_batches.attribute_values, 'one', CONCAT('%', ?, '%')) IS NOT NULL", [$search]);
                    });
                }
            }, false)
            ->addColumn('name', function (ProductBatch $batch) {
                return $batch->product_name ?? '-';
            })
            ->addColumn('category_name', function (ProductBatch $batch) {
                return $batch->product_category ?? '-';
            })
            ->addColumn('batch_label', function (ProductBatch $batch) {
                $code = '<span class="batch-code-pill"><i class="bi bi-qr-code"></i> ' . e($batch->batch_code) . '</span>';
                $date = $batch->batch_date ? '<div class="batch-date-sub text-muted small mt-1"><i class="bi bi-calendar3"></i> ' . e($batch->batch_date->format('d M Y')) . '</div>' : '';
                return '<div class="batch-info-wrapper">' . $code . $date . '</div>';
            })
            ->addColumn('attribute_summary', function (ProductBatch $batch) {
                return $this->buildSaleAttributeSummary($batch);
            })
            ->addColumn('attribute_values', function (ProductBatch $batch) {
                return $this->buildSaleAttributeValues($batch);
            })
            ->editColumn('purchase_price', function (ProductBatch $batch) {
                return number_format((float) $batch->purchase_price, 2);
            })
            ->addColumn('stock_quantity', function (ProductBatch $batch) {
                return (int) $batch->remaining_quantity;
            })
            ->editColumn('latest_profit', function (ProductBatch $batch) {
                return number_format((float) ($batch->latest_profit ?? 0), 2);
            })
            ->editColumn('latest_loss', function (ProductBatch $batch) {
                return number_format((float) ($batch->latest_loss ?? 0), 2);
            })
            ->addColumn('actions', function (ProductBatch $batch) {
                $createUrl = route('product.create', ['shop_id' => $batch->shop_id]);
                $editUrl = route('product.edit', $batch->product_id);
                $deleteUrl = route('product.destroy', $batch->product_id);
                $viewSalesUrl = route('sale.product-sales', [
                    'product' => (int) $batch->product_id,
                    'shop_id' => (int) $batch->shop_id,
                ]);
                $canSell = $batch->remaining_quantity > 0;

                $saleButton = '<button type="button" class="btn btn-sm btn-sale-primary js-sale-btn" '
                    . 'data-product-id="' . e((string) $batch->product_id) . '" '
                    . 'data-batch-id="' . e((string) $batch->id) . '" '
                    . 'data-batch-code="' . e((string) $batch->batch_code) . '" '
                    . 'data-attribute-summary="' . e($this->buildSaleAttributeSummary($batch)) . '" '
                    . 'data-attribute-values="' . e(json_encode($this->buildSaleAttributeValues($batch), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '" '
                    . 'data-shop-id="' . e((string) $batch->shop_id) . '" '
                    . 'data-product-name="' . e((string) $batch->product_name) . '" '
                    . 'data-stock="' . e((string) $batch->remaining_quantity) . '" '
                    . 'data-purchase-price="' . e((string) $batch->purchase_price) . '" '
                    . 'data-has-free-service="' . e((string) ((int) ($batch->has_free_service ?? 0))) . '" '
                    . 'data-free-service-duration-value="' . e((string) ($batch->free_service_duration_value ?? '')) . '" '
                    . 'data-free-service-duration-unit="' . e((string) ($batch->free_service_duration_unit ?? '')) . '" '
                    . 'data-free-service-terms="' . e((string) ($batch->free_service_terms ?? '')) . '" '
                    . ($canSell ? '' : 'disabled ')
                    . 'title="Sale">'
                    . '<i class="bi bi-cart-plus-fill"></i> ' . e(__('sale.sale_button'))
                    . '</button>';

                $deleteForm = '<form action="' . e($deleteUrl) . '" method="POST" class="d-inline" '
                    . 'onsubmit="return confirm(\'' . e(__('product.confirm_delete')) . '\')">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="dropdown-item text-danger">'
                    . '<i class="bi bi-trash me-2"></i>' . e(__('app.delete'))
                    . '</button>'
                    . '</form>';

                $dropdown = '<div class="dropdown d-inline-block">'
                    . '<button class="btn btn-sm btn-outline-secondary dropdown-toggle btn-actions-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">'
                    . '<i class="bi bi-three-dots-vertical"></i>'
                    . '</button>'
                    . '<ul class="dropdown-menu dropdown-menu-end shadow-sm border-light">'
                    . '<li><a class="dropdown-item" href="' . e($viewSalesUrl) . '"><i class="bi bi-eye me-2"></i>' . e(__('sale.view_all_sales')) . '</a></li>'
                    . '<li><a class="dropdown-item" href="' . e($createUrl) . '"><i class="bi bi-plus-circle me-2"></i>' . e(__('app.create')) . '</a></li>'
                    . '<li><a class="dropdown-item" href="' . e($editUrl) . '"><i class="bi bi-pencil me-2"></i>' . e(__('app.edit')) . '</a></li>'
                    . '<li><hr class="dropdown-divider"></li>'
                    . '<li>' . $deleteForm . '</li>'
                    . '</ul>'
                    . '</div>';

                return '<div class="d-flex gap-2 justify-content-center align-items-center">'
                    . $saleButton
                    . $dropdown
                    . '</div>';
            })
            ->rawColumns(['actions', 'batch_label'])
            ->toJson();
    }

    public function quickSale(Request $request)
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'sales')) {
            return response()->json(['message' => 'Your plan limit for sales has been reached. Please upgrade.'], 403);
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'sale_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'nullable|date',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'customer_address' => 'nullable|string|max:500',
        ]);

        abort_unless(auth()->user()->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $product = Product::query()
            ->where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        $batch = ProductBatch::query()
            ->where('id', $validated['product_batch_id'])
            ->where('product_id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product || !$batch) {
            return response()->json([
                'message' => 'Selected product batch does not belong to the selected shop.',
            ], 422);
        }

        if ($batch->remaining_quantity < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient batch stock. Available: ' . $batch->remaining_quantity,
            ], 422);
        }

        try {
            $creatorId = (int) Auth::id();

            DB::transaction(function () use ($validated, $batch, $creatorId): void {
                $quantity = (int) $validated['quantity'];
                $salePrice = (float) $validated['sale_price'];
                $discount = (float) ($validated['discount'] ?? 0);
                $discountedSalePrice = max($salePrice - $discount, 0);
                $totalAmount = $quantity * $discountedSalePrice;
                $purchasePrice = (float) $batch->purchase_price;
                $profit = ($discountedSalePrice - $purchasePrice) * $quantity;

                $sale = Sale::create([
                    'shop_id' => $validated['shop_id'],
                    'product_id' => $validated['product_id'],
                    'product_batch_id' => $validated['product_batch_id'],
                    'quantity' => $quantity,
                    'sale_price' => $salePrice,
                    'purchase_price_per_unit' => $purchasePrice,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'profit' => $profit,
                    'sale_date' => $validated['sale_date'] ?? now()->toDateString(),
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'customer_address' => $validated['customer_address'] ?? null,
                ]);

                $this->productBatchService->consumeBatch($batch, $quantity);
                $this->warrantyExchangeService->syncAutoWarrantyForSale($sale, $creatorId);
                $this->capitalService->updateShopCapital((int) $validated['shop_id']);
            });
        } catch (\RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        $product->refresh();
        $batch->refresh();

        return response()->json([
            'message' => 'Sale recorded successfully.',
            'stock_quantity' => $product->stock_quantity,
            'batch_stock_quantity' => $batch->remaining_quantity,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'sales')) {
            return redirect()->route('sale.index')->with('error', 'Your plan limit for sales has been reached. Please upgrade to add more sales.');
        }
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();
        return view('sale::create', compact('shops', 'products'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'sales')) {
            return back()->withInput()->withErrors(['error' => 'Your plan limit for sales has been reached. Please upgrade.']);
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        abort_unless(auth()->user()->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $batch = ProductBatch::with('product')->findOrFail($validated['product_batch_id']);
        $product = $batch->product;

        if (!$product || (int) $batch->shop_id !== (int) $validated['shop_id']) {
            return back()
                ->withInput()
                ->withErrors(['product_batch_id' => 'Selected product batch does not belong to the selected shop.']);
        }

        if ((int) $batch->remaining_quantity < (int) $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Insufficient batch stock. Available: ' . $batch->remaining_quantity]);
        }

        try {
            $creatorId = (int) Auth::id();

            DB::transaction(function () use ($validated, $product, $batch, $creatorId): void {
                $salePrice = (float) $product->sale_price;
                $purchasePrice = (float) $batch->purchase_price;
                $quantity = (int) $validated['quantity'];
                $totalAmount = $quantity * $salePrice;
                $profit = ($salePrice - $purchasePrice) * $quantity;

                $sale = Sale::create([
                    'shop_id' => $validated['shop_id'],
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'sale_price' => $salePrice,
                    'purchase_price_per_unit' => $purchasePrice,
                    'total_amount' => $totalAmount,
                    'profit' => $profit,
                    'sale_date' => $validated['sale_date'],
                ]);

                $this->productBatchService->consumeBatch($batch, $quantity);
                $this->warrantyExchangeService->syncAutoWarrantyForSale($sale, $creatorId);
                $this->capitalService->updateShopCapital((int) $validated['shop_id']);
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => $exception->getMessage()]);
        }

        return redirect()->route('sale.index')
            ->with('success', 'Sale created successfully!');
    }

    public function show($id)
    {
        $sale = Sale::with([
            'shop',
            'product',
            'productBatch',
            'warranties' => fn ($query) => $query->latest('id'),
        ])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $sale->shop_id), 403, 'You do not have access to this shop.');
        return view('sale::show', compact('sale'));
    }

    public function productSales(Request $request, $productId)
    {
        $user = auth()->user();
        $product = Product::with('shop')->findOrFail($productId);
        abort_unless($user->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');

        $shopId = $request->integer('shop_id');

        $salesQuery = Sale::query()
            ->with([
                'shop',
                'product',
                'productBatch',
                'warranties' => fn ($query) => $query->latest('id'),
            ])
            ->where('product_id', $product->id)
            ->when($shopId, function ($query) use ($shopId) {
                $query->where('shop_id', $shopId);
            });

        $sales = (clone $salesQuery)
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $totals = (clone $salesQuery)
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as total_amount')
            ->selectRaw('COALESCE(SUM(profit), 0) as total_profit')
            ->first();

        return view('sale::product-sales', compact('product', 'sales', 'totals'));
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $sale->shop_id), 403, 'You do not have access to this shop.');
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();
        return view('sale::edit', compact('sale', 'shops', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        $user = auth()->user();
        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $sale = Sale::findOrFail($id);
        abort_unless($user->ownsShop((int) $sale->shop_id), 403, 'You do not have access to this shop.');
        $oldShopId = (int) $sale->shop_id;

        $batch = ProductBatch::with('product')->findOrFail($validated['product_batch_id']);
        $product = $batch->product;

        if (!$product || (int) $batch->shop_id !== (int) $validated['shop_id']) {
            return back()
                ->withInput()
                ->withErrors(['product_batch_id' => 'Selected product batch does not belong to the selected shop.']);
        }

        try {
            $creatorId = (int) Auth::id();

            DB::transaction(function () use ($validated, $sale, $product, $batch, $creatorId, $oldShopId): void {
                $oldBatch = ProductBatch::withTrashed()->find($sale->product_batch_id);
                if ($oldBatch) {
                    $this->productBatchService->restoreBatch($oldBatch, (int) $sale->quantity);
                }

                $requestedQty = (int) $validated['quantity'];
                $batch->refresh();
                if ((int) $batch->remaining_quantity < $requestedQty) {
                    throw new \RuntimeException('Insufficient batch stock.');
                }

                $salePrice = (float) $product->sale_price;
                $purchasePrice = (float) $batch->purchase_price;
                $totalAmount = $requestedQty * $salePrice;
                $profit = ($salePrice - $purchasePrice) * $requestedQty;

                $sale->update([
                    'shop_id' => $validated['shop_id'],
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'quantity' => $requestedQty,
                    'sale_price' => $salePrice,
                    'purchase_price_per_unit' => $purchasePrice,
                    'total_amount' => $totalAmount,
                    'profit' => $profit,
                    'sale_date' => $validated['sale_date'],
                ]);

                $this->productBatchService->consumeBatch($batch, $requestedQty);
                $this->warrantyExchangeService->syncAutoWarrantyForSale($sale->fresh('product'), $creatorId);
                $this->capitalService->updateShopCapital((int) $validated['shop_id']);

                if ($oldShopId !== (int) $validated['shop_id']) {
                    $this->capitalService->updateShopCapital($oldShopId);
                }
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => $exception->getMessage()]);
        }


        return redirect()->route('sale.index')
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $sale->shop_id), 403, 'You do not have access to this shop.');

        $shopId = $sale->shop_id;

        DB::transaction(function () use ($sale, $shopId) {
            $batch = ProductBatch::withTrashed()->find($sale->product_batch_id);
            if ($batch) {
                $this->productBatchService->restoreBatch($batch, (int) $sale->quantity);
            }

            $sale->delete();

            // Recalculate shop capital after stock restoration
            $this->capitalService->updateShopCapital($shopId);
        });

        return redirect()->route('sale.index')
            ->with('success', 'Sale deleted successfully!');
    }

    /**
     * API endpoint: return products for a given shop (JSON).
     */
    public function productsByShop(Request $request)
    {
        $shopId = (int) $request->shop_id;
        abort_unless(auth()->user()->ownsShop($shopId), 403, 'You do not have access to this shop.');

        $products = ProductBatch::query()
            ->with(['product.dynamicValues.dynamicField'])
            ->where('shop_id', $shopId)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('batch_date')
            ->orderBy('id')
            ->get();

        return response()->json(
            $products->map(function (ProductBatch $batch) {
                return [
                    'id' => (int) $batch->id,
                    'product_id' => (int) $batch->product_id,
                    'product_name' => $batch->product?->name ?? '-',
                    'batch_code' => (string) $batch->batch_code,
                    'batch_date' => optional($batch->batch_date)->toDateString(),
                    'attribute_summary' => $this->buildSaleAttributeSummary($batch),
                    'attribute_values' => $this->buildSaleAttributeValues($batch),
                    'purchase_price' => (float) $batch->purchase_price,
                    'sale_price' => (float) ($batch->product?->sale_price ?? 0),
                    'stock_quantity' => (int) $batch->remaining_quantity,
                ];
            })->values()
        );
    }

    private function buildSaleAttributeValues(ProductBatch $batch): array
    {
        $productValues = collect($batch->product?->dynamicValues ?? [])
            ->filter(fn ($item) => $item->dynamicField && filled($item->value))
            ->map(function ($item) {
                return [
                    'field_id' => (int) $item->product_dynamic_field_id,
                    'field_key' => (string) ($item->dynamicField->field_key ?? ''),
                    'label' => (string) ($item->dynamicField->label ?? 'Attribute'),
                    'value' => (string) $item->value,
                ];
            })
            ->values()
            ->all();

        if (!empty($productValues)) {
            return $productValues;
        }

        return collect($batch->attribute_values ?? [])
            ->filter(fn ($item) => is_array($item) && filled($item['value'] ?? null))
            ->map(function (array $item) {
                return [
                    'field_id' => isset($item['field_id']) ? (int) $item['field_id'] : null,
                    'field_key' => (string) ($item['field_key'] ?? ''),
                    'label' => (string) ($item['label'] ?? 'Attribute'),
                    'value' => (string) ($item['value'] ?? ''),
                ];
            })
            ->values()
            ->all();
    }

    private function buildSaleAttributeSummary(ProductBatch $batch): string
    {
        $attributes = collect($this->buildSaleAttributeValues($batch))
            ->filter(fn ($item) => is_array($item) && filled($item['value'] ?? null))
            ->map(function (array $item) {
                $label = (string) ($item['label'] ?? $item['field_key'] ?? 'Attribute');
                $value = (string) ($item['value'] ?? '');

                return trim($label) . ': ' . trim($value);
            })
            ->values();

        return $attributes->isNotEmpty() ? $attributes->implode(' | ') : '-';
    }
}
