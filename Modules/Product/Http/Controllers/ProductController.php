<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Modules\Brand\Models\Brand;
use Modules\Capital\Services\CapitalService;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductDynamicField;
use Modules\Product\Models\ProductDynamicValue;
use Modules\Product\Services\ProductBatchService;
use Modules\Restock\Models\Restock;
use Modules\Sale\Models\Sale;
use Modules\Settings\Models\Setting;
use Modules\Shop\Models\Shop;

class ProductController extends Controller
{
    protected $capitalService;

    protected ProductBatchService $productBatchService;

    public function __construct(CapitalService $capitalService, ProductBatchService $productBatchService)
    {
        $this->capitalService = $capitalService;
        $this->productBatchService = $productBatchService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedShopIds = $user->accessibleShopIds();

        $shops = Shop::forUser($user)
            ->withCount('products')
            ->orderBy('name')
            ->get(['id', 'name']);
        $selectedShopId = $request->filled('shop_id') ? $request->integer('shop_id') : null;

        // Prevent accessing a shop the user doesn't own
        if ($selectedShopId && ! in_array($selectedShopId, $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }

        if ($request->ajax()) {
            $modelNumberFieldIds = ProductDynamicField::query()
                ->where(function ($query) {
                    $query->whereRaw('LOWER(field_key) = ?', ['model_number'])
                        ->orWhereRaw('LOWER(label) = ?', ['model number'])
                        ->orWhereRaw('LOWER(label) like ?', ['%model number%']);
                })
                ->pluck('id');

            $query = Product::query()
                ->with([
                    'shop:id,name',
                    'productCategory:id,name',
                    'creator:id,name',
                    'dynamicValues' => function ($q) use ($modelNumberFieldIds) {
                        $q->whereIn('product_dynamic_field_id', $modelNumberFieldIds);
                    },
                ])
                ->where('shop_id', $selectedShopId);

            return datatables()->of($query)
                ->filter(function ($query) use ($request, $modelNumberFieldIds) {
                    if ($request->filled('category_id')) {
                        $query->where('category_id', $request->integer('category_id'));
                    }

                    $datatableSearch = $request->input('search.value');
                    $customSearch = $request->input('search');
                    $searchTerm = trim($datatableSearch ?: $customSearch ?: '');

                    if ($searchTerm !== '') {
                        $supportsModelName = Schema::hasColumn('products', 'model_name');

                        $query->where(function ($q) use ($searchTerm, $supportsModelName, $modelNumberFieldIds) {
                            $q->where('products.name', 'like', "%{$searchTerm}%")
                                ->orWhere('products.brand', 'like', "%{$searchTerm}%")
                                ->orWhere('products.category', 'like', "%{$searchTerm}%");

                            if ($supportsModelName) {
                                $q->orWhere('products.model_name', 'like', "%{$searchTerm}%");
                            }

                            if ($modelNumberFieldIds->isNotEmpty()) {
                                $q->orWhereHas('dynamicValues', function ($dvQ) use ($searchTerm, $modelNumberFieldIds) {
                                    $dvQ->whereIn('product_dynamic_field_id', $modelNumberFieldIds)
                                        ->where('value', 'like', "%{$searchTerm}%");
                                });
                            }
                        });
                    }
                })
                ->addColumn('model_name', function ($product) {
                    return $product->dynamicValues->first()?->value ?? '-';
                })
                ->addColumn('shop_name', function ($product) {
                    return $product->shop?->name ?? '-';
                })
                ->addColumn('creator_name', function ($product) {
                    return $product->creator?->name ?? '-';
                })
                ->editColumn('purchase_price', function ($product) {
                    return currency_symbol().number_format($product->purchase_price, 2);
                })
                ->editColumn('stock_quantity', function ($product) {
                    $lowStockAlert = (int) Setting::get('low_stock_alert', 5);
                    if ($product->stock_quantity <= $lowStockAlert) {
                        return '<span class="stock-badge stock-low">'.$product->stock_quantity.'</span>';
                    } elseif ($product->stock_quantity <= ($lowStockAlert * 4)) {
                        return '<span class="stock-badge stock-mid">'.$product->stock_quantity.'</span>';
                    } else {
                        return '<span class="stock-badge stock-high">'.$product->stock_quantity.'</span>';
                    }
                })
                ->addColumn('actions', function ($product) {
                    $batchesRoute = route('product.batches', $product->id);
                    $showRoute = route('product.show', $product->id);
                    $editRoute = route('product.edit', $product->id);
                    $destroyRoute = route('product.destroy', $product->id);
                    $confirmMessage = __('product.confirm_delete');
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return <<<HTML
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{$batchesRoute}" class="btn btn-outline-success" title="Batch Tracker">
                                <i class="bi bi-layers"></i>
                            </a>
                            <a href="{$showRoute}" class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{$editRoute}" class="btn btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{$destroyRoute}" method="POST" class="d-inline" onsubmit="return confirm('{$confirmMessage}')">
                                {$csrf}
                                {$method}
                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    HTML;
                })
                ->rawColumns(['stock_quantity', 'actions'])
                ->make(true);
        }

        $selectedCategoryId = $request->filled('category_id') ? $request->integer('category_id') : null;
        $searchTerm = trim((string) $request->input('search', ''));
        $selectedShop = $selectedShopId ? $shops->firstWhere('id', $selectedShopId) : null;

        if ($selectedShopId && ! $selectedShop) {
            $selectedShopId = null;
        }

        $filters = [
            'search' => $searchTerm,
        ];

        $modelNumberFieldIds = ProductDynamicField::query()
            ->where(function ($query) {
                $query->whereRaw('LOWER(field_key) = ?', ['model_number'])
                    ->orWhereRaw('LOWER(label) = ?', ['model number'])
                    ->orWhereRaw('LOWER(label) like ?', ['%model number%']);
            })
            ->pluck('id');

        $categoryOptions = collect();

        if ($selectedShopId) {
            $categoryOptions = Category::query()
                ->whereHas('products', function ($query) use ($selectedShopId) {
                    $query->where('shop_id', $selectedShopId);
                })
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $searchSuggestions = collect();
        $products = collect(); // Pass empty collection for standard view compatibility

        if ($selectedShopId) {
            $supportsModelName = Schema::hasColumn('products', 'model_name');
            $searchSuggestions = Product::query()
                ->where('shop_id', $selectedShopId)
                ->select(['name', 'brand', 'category'])
                ->latest()
                ->limit(250)
                ->get()
                ->flatMap(function (Product $product) {
                    return [
                        $product->name,
                        $product->brand,
                        $product->category,
                    ];
                })
                ->filter(static fn ($value) => is_string($value) && trim($value) !== '')
                ->map(static fn ($value) => trim((string) $value))
                ->merge(
                    ProductDynamicValue::query()
                        ->whereHas('product', function ($query) use ($selectedShopId) {
                            $query->where('shop_id', $selectedShopId);
                        })
                        ->whereIn('product_dynamic_field_id', $modelNumberFieldIds)
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->orderBy('value')
                        ->limit(100)
                        ->pluck('value')
                )
                ->when($supportsModelName, function ($collection) use ($selectedShopId) {
                    return $collection->merge(
                        Product::query()
                            ->where('shop_id', $selectedShopId)
                            ->whereNotNull('model_name')
                            ->where('model_name', '!=', '')
                            ->orderBy('model_name')
                            ->limit(100)
                            ->pluck('model_name')
                    );
                })
                ->unique()
                ->sort()
                ->values()
                ->take(80);
        }

        return view('product::index', compact(
            'products',
            'shops',
            'selectedShopId',
            'selectedShop',
            'selectedCategoryId',
            'filters',
            'categoryOptions',
            'searchSuggestions'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $planService = app(PlanService::class);
        if (! $planService->canCreate($user, 'products')) {
            return redirect()->route('product.index')->with('error', 'Your plan limit for products has been reached. Please upgrade to add more products.');
        }
        $shops = Shop::forUser($user)->get();
        $categories = Category::forUser($user)->orderBy('name')->get();
        $brands = Brand::forUser($user)->orderBy('name')->get();
        $selectedShopId = $request->integer('shop_id');
        $dynamicFieldsByCategory = $this->getDynamicFieldsByCategory();
        $dynamicFieldValues = old('custom_fields', []);

        return view('product::create', compact(
            'shops',
            'categories',
            'brands',
            'selectedShopId',
            'dynamicFieldsByCategory',
            'dynamicFieldValues'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $request->input('shop_id')), 403, 'You do not have access to this shop.');

        $planService = app(PlanService::class);
        if (! $planService->canCreate($user, 'products')) {
            return back()->withInput()->withErrors(['error' => 'Your plan limit for products has been reached. Please upgrade to add more products.']);
        }

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'has_free_service' => 'nullable|boolean',
            'free_service_duration_value' => 'nullable|integer|min:1|required_if:has_free_service,1',
            'free_service_duration_unit' => 'nullable|in:day,month,year|required_if:has_free_service,1',
            'free_service_terms' => 'nullable|string|max:2000',
        ]);

        $validated['has_free_service'] = $request->boolean('has_free_service');
        if (! $validated['has_free_service']) {
            $validated['free_service_duration_value'] = null;
            $validated['free_service_duration_unit'] = null;
            $validated['free_service_terms'] = null;
        }

        if ($supportsModelName) {
            $validated['model_name'] = trim((string) $request->input('model_name', '')) ?: null;
        }

        // Creation form no longer asks for sale price; keep DB insert valid.
        $validated['sale_price'] = $validated['sale_price'] ?? $validated['purchase_price'];

        $validated['category'] = Category::query()
            ->whereKey($validated['category_id'] ?? null)
            ->value('name');

        $validated['created_by'] = $user->id;

        $validatedDynamicValues = $this->validateDynamicFieldValues($request, $validated['category_id'] ?? null);

        DB::transaction(function () use ($validated, $validatedDynamicValues): void {
            $product = Product::create($validated);
            $this->syncDynamicFieldValues($product, $validatedDynamicValues);

            if ((int) $validated['stock_quantity'] > 0) {
                $this->productBatchService->createBatch($product, [
                    'source_type' => 'initial',
                    'purchase_price' => (float) $validated['purchase_price'],
                    'initial_quantity' => (int) $validated['stock_quantity'],
                    'batch_date' => now()->toDateString(),
                    'note' => 'Initial stock batch created during product creation.',
                ]);
            }

            $this->capitalService->updateShopCapital($product->shop_id);
        });

        return redirect()->route('product.index')
            ->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with([
            'shop',
            'productCategory',
            'creator:id,name',
            'dynamicValues.dynamicField',
            'batches' => function ($query) {
                $query->orderByDesc('batch_date')->orderByDesc('id');
            },
        ])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');

        return view('product::show', compact('product'));
    }

    public function batches($id)
    {
        $product = Product::with(['shop', 'productCategory'])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');

        $batches = Restock::where('product_id', $product->id)
            ->where('shop_id', $product->shop_id)
            ->withTrashed()
            ->orderBy('restock_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $sales = Sale::with(['batchItems.restock'])
            ->where('product_id', $product->id)
            ->where('shop_id', $product->shop_id)
            ->withTrashed()
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->get();

        $totalSold = $sales->whereNull('deleted_at')->sum('quantity');
        $totalRevenue = $sales->whereNull('deleted_at')->sum('total_amount');
        $totalProfit = $sales->whereNull('deleted_at')->sum('profit');

        return view('product::batches', compact(
            'product',
            'batches',
            'sales',
            'totalSold',
            'totalRevenue',
            'totalProfit'
        ));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $product = Product::with('productCategory')->findOrFail($id);
        abort_unless($user->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        $shops = Shop::forUser($user)->get();
        $categories = Category::forUser($user)->orderBy('name')->get();
        $brands = Brand::forUser($user)->orderBy('name')->get();
        $dynamicFieldsByCategory = $this->getDynamicFieldsByCategory();
        $dynamicFieldValues = old(
            'custom_fields',
            $product->dynamicValues()->pluck('value', 'product_dynamic_field_id')->toArray()
        );

        return view('product::edit', compact(
            'product',
            'shops',
            'categories',
            'brands',
            'dynamicFieldsByCategory',
            'dynamicFieldValues'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);
        abort_unless($user->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        abort_unless($user->ownsShop((int) $request->input('shop_id')), 403, 'You do not have access to this shop.');

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'has_free_service' => 'nullable|boolean',
            'free_service_duration_value' => 'nullable|integer|min:1|required_if:has_free_service,1',
            'free_service_duration_unit' => 'nullable|in:day,month,year|required_if:has_free_service,1',
            'free_service_terms' => 'nullable|string|max:2000',
        ]);

        $validated['has_free_service'] = $request->boolean('has_free_service');
        if (! $validated['has_free_service']) {
            $validated['free_service_duration_value'] = null;
            $validated['free_service_duration_unit'] = null;
            $validated['free_service_terms'] = null;
        }

        if ($supportsModelName) {
            $validated['model_name'] = trim((string) $request->input('model_name', '')) ?: null;
        }

        $validated['category'] = Category::query()
            ->whereKey($validated['category_id'] ?? null)
            ->value('name');

        $validatedDynamicValues = $this->validateDynamicFieldValues($request, $validated['category_id'] ?? null);

        // Ensure stock cannot go below zero
        if ($validated['stock_quantity'] < 0) {
            return back()->withErrors(['stock_quantity' => 'Stock quantity cannot be negative.'])->withInput();
        }

        $oldShopId = $product->shop_id;

        DB::transaction(function () use ($product, $validated, $validatedDynamicValues, $oldShopId): void {
            $product->update($validated);
            $this->syncDynamicFieldValues($product, $validatedDynamicValues);

            if ((int) $oldShopId !== (int) $product->shop_id) {
                $product->batches()->update(['shop_id' => $product->shop_id]);
            }

            $this->productBatchService->syncManualStockQuantity(
                $product,
                (int) $validated['stock_quantity'],
                (float) $validated['purchase_price']
            );

            $this->capitalService->updateShopCapital($product->shop_id);
            if ($oldShopId != $product->shop_id) {
                $this->capitalService->updateShopCapital($oldShopId);
            }
        });

        return redirect()->route('product.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        $shopId = $product->shop_id;

        DB::transaction(function () use ($product, $shopId): void {
            $product->batches()->delete();
            $product->delete();
            $this->capitalService->updateShopCapital($shopId);
        });

        return redirect()->route('product.index')
            ->with('success', 'Product deleted successfully!');
    }

    private function getDynamicFieldsByCategory(): array
    {
        return ProductDynamicField::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy(static fn (ProductDynamicField $field) => $field->category_id ? (string) $field->category_id : 'global')
            ->map(static fn ($fields) => $fields->map(static function (ProductDynamicField $field) {
                return [
                    'id' => $field->id,
                    'label' => $field->label,
                    'field_key' => $field->field_key,
                    'input_type' => $field->input_type,
                    'placeholder' => $field->placeholder,
                    'help_text' => $field->help_text,
                    'is_required' => $field->is_required,
                    'options' => $field->options ?? [],
                ];
            })->values()->all())
            ->toArray();
    }

    /**
     * @throws ValidationException
     */
    private function validateDynamicFieldValues(Request $request, ?int $categoryId): array
    {
        $fields = ProductDynamicField::query()
            ->where('is_active', true)
            ->where(function ($query) use ($categoryId) {
                $query->whereNull('category_id');

                if ($categoryId) {
                    $query->orWhere('category_id', $categoryId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $submittedValues = $request->input('custom_fields', []);
        $validated = [];
        $errors = [];

        foreach ($fields as $field) {
            $rawValue = $submittedValues[$field->id] ?? null;
            $value = is_string($rawValue) ? trim($rawValue) : $rawValue;
            $isEmpty = $value === null || $value === '';

            if ($field->is_required && $isEmpty) {
                $errors["custom_fields.{$field->id}"] = __('validation.required', ['attribute' => $field->label]);

                continue;
            }

            if ($isEmpty) {
                continue;
            }

            switch ($field->input_type) {
                case 'number':
                    if (! is_numeric($value)) {
                        $errors["custom_fields.{$field->id}"] = __('validation.numeric', ['attribute' => $field->label]);

                        continue 2;
                    }
                    break;

                case 'date':
                    if (strtotime((string) $value) === false) {
                        $errors["custom_fields.{$field->id}"] = __('validation.date', ['attribute' => $field->label]);

                        continue 2;
                    }
                    break;

                case 'select':
                    $options = $field->options ?? [];
                    if (! in_array((string) $value, $options, true)) {
                        $errors["custom_fields.{$field->id}"] = __('validation.in', ['attribute' => $field->label]);

                        continue 2;
                    }
                    break;

                default:
                    break;
            }

            $validated[$field->id] = (string) $value;
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $validated;
    }

    private function syncDynamicFieldValues(Product $product, array $validatedDynamicValues): void
    {
        $product->dynamicValues()->delete();

        if (empty($validatedDynamicValues)) {
            return;
        }

        $rows = collect($validatedDynamicValues)
            ->map(static function ($value, $fieldId) use ($product) {
                return [
                    'product_id' => $product->id,
                    'product_dynamic_field_id' => (int) $fieldId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->all();

        if (! empty($rows)) {
            $product->dynamicValues()->insert($rows);
        }
    }
}
