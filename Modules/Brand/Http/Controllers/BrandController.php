<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Brand\Models\Brand;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if ($user && !$user->isOwner() && !$user->isSuperAdmin()) {
                    abort(403, 'Unauthorized action. Only shop owners and admins can manage brands.');
                }
                return $next($request);
            }),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $brands = Brand::forUser($user)
                ->with(['owner'])
                ->withCount('products');

            return DataTables::eloquent($brands)
                ->addColumn('owner_info', function (Brand $brand) {
                    $name = e($brand->owner?->name ?? 'System');
                    $email = e($brand->owner?->email ?? 'N/A');
                    return '<strong>' . $name . '</strong><br><small class="text-muted">' . $email . '</small>';
                })
                ->addColumn('products_badge', function (Brand $brand) {
                    return '<span class="product-count-pill">' . $brand->products_count . '</span>';
                })
                ->addColumn('actions', function (Brand $brand) {
                    $viewUrl = route('brand.show', $brand->id);
                    $editUrl = route('brand.edit', $brand->id);
                    $deleteUrl = route('brand.destroy', $brand->id);

                    return '<div class="d-flex gap-1 justify-content-end">'
                        . '<a href="' . $viewUrl . '" class="btn btn-sm btn-outline-primary" title="View">'
                            . '<i class="bi bi-eye"></i>'
                        . '</a>'
                        . '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning" title="Edit">'
                            . '<i class="bi bi-pencil"></i>'
                        . '</a>'
                        . '<form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'' . __('brand::brand.confirm_delete') . '\')">'
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

        $totalBrandsCount = $user->isSuperAdmin() ? Brand::count() : 0;

        return view('brand::index', compact('totalBrandsCount'));
    }

    public function create()
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'brands')) {
            return redirect()->route('brand.index')->with('error', 'Your plan limit for brands has been reached. Please upgrade to add more brands.');
        }

        return view('brand::create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $validated['user_id'] = $user->id;

        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'brands')) {
            return redirect()->route('brand.index')->with('error', 'Your plan limit for brands has been reached. Please upgrade to add more brands.');
        }

        Brand::create($validated);

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.created'));
    }

    public function show($id)
    {
        $brand = Brand::forUser(auth()->user())->withCount('products')->findOrFail($id);

        return view('brand::show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::forUser(auth()->user())->findOrFail($id);

        return view('brand::edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $brand = Brand::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brand->id)->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $brand->update($validated);

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.updated'));
    }

    public function destroy($id)
    {
        $brand = Brand::forUser(auth()->user())->findOrFail($id);

        if ($brand->products()->exists()) {
            return redirect()->route('brand.index')
                ->withErrors(['error' => __('brand::brand.cannot_delete_in_use')]);
        }

        $brand->delete();

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.deleted'));
    }
}
