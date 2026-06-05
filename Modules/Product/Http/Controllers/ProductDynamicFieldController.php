<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Product\Models\ProductDynamicField;

class ProductDynamicFieldController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProductDynamicField::query()
                ->with(['category:id,name', 'creator:id,name']);

            return datatables()->of($query)
                ->addColumn('category_name', function ($field) {
                    return $field->category?->name ?? __('product.dynamic_all_categories');
                })
                ->addColumn('creator_name', function ($field) {
                    return $field->creator?->name ?? '-';
                })
                ->editColumn('field_key', function ($field) {
                    return '<span class="key-chip">' . e($field->field_key) . '</span>';
                })
                ->editColumn('input_type', function ($field) {
                    return strtoupper(e($field->input_type));
                })
                ->editColumn('is_active', function ($field) {
                    $class = $field->is_active ? 'text-bg-success' : 'text-bg-secondary';
                    $text = $field->is_active ? __('app.active') : __('app.inactive');
                    return '<span class="badge ' . $class . '">' . e($text) . '</span>';
                })
                ->editColumn('is_required', function ($field) {
                    $class = $field->is_required ? 'text-bg-danger' : 'text-bg-light border';
                    $text = $field->is_required ? __('app.yes') : __('app.no');
                    return '<span class="badge ' . $class . '">' . e($text) . '</span>';
                })
                ->addColumn('actions', function ($field) {
                    $editRoute = route('product.dynamic-fields.edit', $field->id);
                    $destroyRoute = route('product.dynamic-fields.destroy', $field->id);
                    $confirmMessage = __('product.confirm_delete_dynamic_field');
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return <<<HTML
                        <div class="btn-group btn-group-sm">
                            <a href="{$editRoute}" class="btn btn-action-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{$destroyRoute}" onsubmit="return confirm('{$confirmMessage}')" class="d-inline">
                                {$csrf}
                                {$method}
                                <button type="submit" class="btn btn-action-delete" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    HTML;
                })
                ->rawColumns(['field_key', 'is_active', 'is_required', 'actions'])
                ->make(true);
        }

        $fields = ProductDynamicField::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('product::dynamic-fields.index', compact('fields'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $inputTypes = array_values(array_filter(
            ProductDynamicField::INPUT_TYPES,
            static fn (string $type) => $type !== 'select'
        ));

        return view('product::dynamic-fields.create', compact('categories', 'inputTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'field_key' => $this->generateFieldKey(
                (string) $request->input('label', ''),
                $request->input('category_id')
            ),
        ]);

        $validated = $this->validateRequest($request);
        $validated['created_by'] = auth()->id();

        ProductDynamicField::create($validated);

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_created'));
    }

    public function edit(int $id)
    {
        $field = ProductDynamicField::findOrFail($id);
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $inputTypes = ProductDynamicField::INPUT_TYPES;
        $optionsText = implode("\n", $field->options ?? []);

        return view('product::dynamic-fields.edit', compact('field', 'categories', 'inputTypes', 'optionsText'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $field = ProductDynamicField::findOrFail($id);
        $validated = $this->validateRequest($request, $field);

        $field->update($validated);

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $field = ProductDynamicField::findOrFail($id);
        $field->delete();

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_deleted'));
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(Request $request, ?ProductDynamicField $field = null): array
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'label' => 'required|string|max:120',
            'field_key' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('product_dynamic_fields', 'field_key')
                    ->where(fn ($query) => $query->where('category_id', $request->input('category_id')))
                    ->ignore($field?->id),
            ],
            'input_type' => ['required', Rule::in(ProductDynamicField::INPUT_TYPES)],
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'options_text' => 'nullable|string',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['options'] = null;

        if ($validated['input_type'] === 'select') {
            $validated['options'] = $this->parseOptions($validated['options_text'] ?? '');

            if (empty($validated['options'])) {
                throw ValidationException::withMessages([
                    'options_text' => __('product.dynamic_options_required'),
                ]);
            }
        }

        unset($validated['options_text']);

        return $validated;
    }

    private function parseOptions(string $optionsText): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $optionsText) ?: [])
            ->map(static fn ($item) => trim($item))
            ->filter(static fn ($item) => $item !== '')
            ->values()
            ->all();
    }

    private function generateFieldKey(string $label, mixed $categoryId = null): string
    {
        $baseKey = Str::slug($label, '_');
        $baseKey = $baseKey !== '' ? $baseKey : 'field';

        $fieldKey = $baseKey;
        $suffix = 2;

        while (ProductDynamicField::withTrashed()
            ->where('category_id', $categoryId)
            ->where('field_key', $fieldKey)
            ->exists()) {
            $fieldKey = $baseKey . '_' . $suffix;
            $suffix++;
        }

        return $fieldKey;
    }
}
