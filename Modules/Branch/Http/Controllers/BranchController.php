<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id');

        if ($selectedShopId && ! $user->ownsShop($selectedShopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        if ($request->ajax()) {
            $branches = Branch::forUser($user)
                ->with(['shop:id,name', 'creator:id,name'])
                ->when($selectedShopId, function ($query) use ($selectedShopId) {
                    $query->where('shop_id', $selectedShopId);
                });

            return DataTables::eloquent($branches)
                ->addColumn('shop_name', function (Branch $branch) {
                    return $branch->shop?->name ?? '-';
                })
                ->addColumn('creator_name', function (Branch $branch) {
                    return $branch->creator?->name ?? '-';
                })
                ->addColumn('status', function (Branch $branch) {
                    if ($branch->is_active) {
                        return '<span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i>'.__('branch::branch.active').'</span>';
                    }

                    return '<span class="status-badge status-inactive"><i class="bi bi-dash-circle"></i>'.__('branch::branch.inactive').'</span>';
                })
                ->addColumn('created_at_formatted', function (Branch $branch) {
                    return $branch->created_at?->format('M d, Y') ?? '-';
                })
                ->addColumn('actions', function (Branch $branch) {
                    $viewUrl = route('branch.show', $branch->id);
                    $editUrl = route('branch.edit', $branch->id);
                    $deleteUrl = route('branch.destroy', $branch->id);

                    return '<div class="d-flex gap-1 justify-content-center">'
                        .'<a href="'.$viewUrl.'" class="btn btn-outline-info action-btn" title="'.__('app.view').'">'
                            .'<i class="bi bi-eye"></i>'
                        .'</a>'
                        .'<a href="'.$editUrl.'" class="btn btn-outline-warning action-btn" title="'.__('app.edit').'">'
                            .'<i class="bi bi-pencil"></i>'
                        .'</a>'
                        .'<form action="'.$deleteUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\''.__('branch::branch.confirm_delete').'\')">'
                            .csrf_field()
                            .method_field('DELETE')
                            .'<button type="submit" class="btn btn-outline-danger action-btn" title="'.__('app.delete').'">'
                                .'<i class="bi bi-trash"></i>'
                            .'</button>'
                        .'</form>'
                        .'</div>';
                })
                ->rawColumns(['status', 'actions'])
                ->toJson();
        }

        return view('branch::index', compact('shops', 'selectedShopId'));
    }

    public function create(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $planService = app(PlanService::class);
        if (! $planService->isFeatureEnabled($user, 'branches')) {
            return redirect()->route('branch.index')->with('error', 'Branches are not available on your current plan. Please upgrade to access this feature.');
        }
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id');

        if ($selectedShopId && ! $user->ownsShop($selectedShopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        return view('branch::create', compact('shops', 'selectedShopId'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $planService = app(PlanService::class);
        if (! $planService->isFeatureEnabled($user, 'branches')) {
            return redirect()->route('branch.index')->with('error', 'Branches are not available on your current plan. Please upgrade to access this feature.');
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'location' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches', 'name')->where(function ($query) use ($request) {
                    return $query->where('shop_id', $request->input('shop_id'));
                }),
            ],
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        abort_unless($user->isSuperAdmin() || $user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['name'] = $validated['location'];
        $validated['created_by'] = $user->id;

        $branch = Branch::create($validated);

        return redirect()->route('branch.show', $branch->id)
            ->with('success', __('branch::branch.created'));
    }

    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->with(['shop:id,name', 'creator:id,name'])->findOrFail($id);

        return view('branch::show', compact('branch'));
    }

    public function edit($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->with('shop:id,name')->findOrFail($id);
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        return view('branch::edit', compact('branch', 'shops'));
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'location' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches', 'name')
                    ->ignore($branch->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('shop_id', $request->input('shop_id'));
                    }),
            ],
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        abort_unless($user->isSuperAdmin() || $user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['name'] = $validated['location'];

        $branch->update($validated);

        return redirect()->route('branch.show', $branch->id)
            ->with('success', __('branch::branch.updated'));
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->findOrFail($id);
        $branch->delete();

        return redirect()->route('branch.index')
            ->with('success', __('branch::branch.deleted'));
    }
}
