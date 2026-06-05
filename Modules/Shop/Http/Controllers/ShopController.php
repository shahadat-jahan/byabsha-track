<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Models\Shop;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $shops = Shop::forUser($user)->with(['owner'])->withCount(['products', 'sales', 'branches']);

            return DataTables::eloquent($shops)
                ->addColumn('owner_name', function (Shop $shop) {
                    return $shop->owner?->name ?? 'N/A';
                })
                ->addColumn('products_badge', function (Shop $shop) {
                    return '<span class="shop-badge shop-badge-products">' . $shop->products_count . ' ' . __('shop.products_badge') . '</span>';
                })
                ->addColumn('sales_badge', function (Shop $shop) {
                    return '<span class="shop-badge shop-badge-sales">' . $shop->sales_count . ' ' . __('shop.sales_badge') . '</span>';
                })
                ->addColumn('branches_badge', function (Shop $shop) {
                    return '<span class="shop-badge shop-badge-branches">' . $shop->branches_count . ' ' . __('shop.branches_badge') . '</span>';
                })
                ->addColumn('created_at_formatted', function (Shop $shop) {
                    return $shop->created_at?->format('M d, Y') ?? '-';
                })
                ->addColumn('actions', function (Shop $shop) {
                    $viewUrl = route('shop.show', $shop->id);
                    $editUrl = route('shop.edit', $shop->id);
                    $deleteUrl = route('shop.destroy', $shop->id);

                    return '<div class="btn-group btn-group-sm">'
                        . '<a href="' . $viewUrl . '" class="btn btn-outline-info" title="View Details">'
                            . '<i class="bi bi-eye"></i>'
                        . '</a>'
                        . '<a href="' . $editUrl . '" class="btn btn-outline-warning" title="Edit">'
                            . '<i class="bi bi-pencil"></i>'
                        . '</a>'
                        . '<form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'' . __('shop.confirm_delete') . '\')">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button type="submit" class="btn btn-outline-danger" title="Delete">'
                                . '<i class="bi bi-trash"></i>'
                            . '</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['products_badge', 'sales_badge', 'branches_badge', 'actions'])
                ->toJson();
        }

        return view('shop::index');
    }

    public function create()
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'shops')) {
            return redirect()->route('shop.index')->with('error', 'Your plan limit for shops has been reached. Please upgrade to add more shops.');
        }

        // For superadmin, load available shop owners
        $shopOwners = null;
        if ($user->isSuperAdmin()) {
            $shopOwners = \App\Models\User::where('role', 'owner')
                ->orWhere('role', 'superadmin')
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return view('shop::create', compact('shopOwners'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation rules for basic shop fields
        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ];

        // For superadmin, require and validate user_id selection
        if ($user->isSuperAdmin()) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Set user_id: from request for superadmin, from authenticated user for others
        if ($user->isSuperAdmin()) {
            $validated['user_id'] = $request->integer('user_id');
        } else {
            $validated['user_id'] = Auth::id();
        }

        $planService = app(\App\Services\PlanService::class);
        if (!$planService->canCreate($user, 'shops')) {
            return redirect()->route('shop.index')->with('error', 'Your plan limit for shops has been reached. Please upgrade to add more shops.');
        }

        Shop::create($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop created successfully!');
    }

    public function show($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->withCount(['products', 'sales', 'branches'])
            ->with(['products' => function($query) {
                $query->latest()->take(10);
            }, 'branches' => function ($query) {
                $query->latest()->take(5);
            }])
            ->findOrFail($id);

        return view('shop::show', compact('shop'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);

        $shopOwners = null;
        if ($user->isSuperAdmin()) {
            $shopOwners = \App\Models\User::where('role', 'owner')
                ->orWhere('role', 'superadmin')
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return view('shop::edit', compact('shop', 'shopOwners'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ];

        if ($user->isSuperAdmin()) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        if ($user->isSuperAdmin()) {
            $validated['user_id'] = $request->integer('user_id');
        }

        $shop->update($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);
        $shop->delete();

        return redirect()->route('shop.index')
            ->with('success', 'Shop deleted successfully!');
    }
}
