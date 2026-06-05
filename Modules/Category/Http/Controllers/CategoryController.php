<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Category\Models\Category;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if ($user && !$user->isOwner() && !$user->isSuperAdmin() && !$user->isManager()) {
                    abort(403, 'Unauthorized action. Only shop owners, managers, and admins can manage categories.');
                }
                return $next($request);
            }),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $categories = Category::forUser($user)
                ->with(['owner'])
                ->withCount('products');

            return DataTables::eloquent($categories)
                ->addColumn('owner_info', function (Category $category) {
                    $name = e($category->owner?->name ?? 'System');
                    $email = e($category->owner?->email ?? 'N/A');
                    return '<strong>' . $name . '</strong><br><small class="text-muted">' . $email . '</small>';
                })
                ->addColumn('products_badge', function (Category $category) {
                    return '<span class="product-count-pill">' . $category->products_count . '</span>';
                })
                ->addColumn('actions', function (Category $category) {
                    $viewUrl = route('category.show', $category->id);
                    $editUrl = route('category.edit', $category->id);
                    $deleteUrl = route('category.destroy', $category->id);

                    return '<div class="d-flex gap-1 justify-content-end">'
                        . '<a href="' . $viewUrl . '" class="btn btn-sm btn-outline-primary" title="View">'
                            . '<i class="bi bi-eye"></i>'
                        . '</a>'
                        . '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning" title="Edit">'
                            . '<i class="bi bi-pencil"></i>'
                        . '</a>'
                        . '<form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'' . __('category::category.confirm_delete') . '\')">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">'
                                . '<i class="bi bi-trash"></i>'
                            . '</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['owner_info', 'products_badge', 'actions'])
                ->toJson();
        }

        $totalCategoriesCount = $user->isSuperAdmin() ? Category::count() : 0;

        return view('category::index', compact('totalCategoriesCount'));
    }

    public function create()
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'categories')) {
            return redirect()->route('category.index')->with('error', 'Your plan limit for categories has been reached. Please upgrade to add more categories.');
        }

        return view('category::create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $validated['user_id'] = $user->id;

        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'categories')) {
            return redirect()->route('category.index')->with('error', 'Your plan limit for categories has been reached. Please upgrade to add more categories.');
        }

        Category::create($validated);

        return redirect()->route('category.index')
            ->with('success', __('category::category.created'));
    }

    public function show($id)
    {
        $category = Category::forUser(auth()->user())->withCount('products')->findOrFail($id);

        return view('category::show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::forUser(auth()->user())->findOrFail($id);

        return view('category::edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $category = Category::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id)->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $category->update($validated);

        return redirect()->route('category.index')
            ->with('success', __('category::category.updated'));
    }

    public function destroy($id)
    {
        $category = Category::forUser(auth()->user())->findOrFail($id);

        if ($category->products()->exists()) {
            return redirect()->route('category.index')
                ->withErrors(['error' => __('category::category.cannot_delete_in_use')]);
        }

        $category->delete();

        return redirect()->route('category.index')
            ->with('success', __('category::category.deleted'));
    }
}
