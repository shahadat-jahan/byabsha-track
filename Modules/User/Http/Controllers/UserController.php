<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private function moduleAccessOptions(): array
    {
        return [
            'dashboard' => __('app.dashboard'),
            'shop' => __('app.shops'),
            'branch' => __('app.branches'),
            'brand' => __('app.brands'),
            'category' => __('app.categories'),
            'product' => __('app.products'),
            'stock' => __('app.stocks'),
            'sale' => __('app.sales'),
            'capital' => __('app.capitals'),
            'restock' => __('app.restocks'),
            'damage' => __('app.damages'),
            'report' => __('app.reports'),
            'subscription' => __('app.subscription'),
        ];
    }

    private function normalizeModuleAccess(array $validated): array
    {
        $allowedKeys = User::availableModuleAccessKeys();

        if (($validated['role'] ?? null) === 'superadmin') {
            $validated['module_access'] = null;

            return $validated;
        }

        $selected = $validated['module_access'] ?? null;

        if (!is_array($selected) || count($selected) === 0) {
            $selected = $allowedKeys;
        }

        $validated['module_access'] = array_values(array_intersect($allowedKeys, $selected));

        return $validated;
    }

    public function profile()
    {
        $user = User::findOrFail(Auth::id());

        return view('user::profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|string|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['new_password'])) {
            if (!Hash::check((string) $validated['current_password'], (string) $user->password)) {
                return back()
                    ->withErrors(['current_password' => __('user.current_password_invalid')])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', __('user.profile_updated'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Get users AJAX data for Yajra DataTable
     */
    public function usersTable(Request $request)
    {
        $query = User::withTrashed()->with(['assignedShop', 'assignedBranch']);

        $customSearch = $request->input('custom_search');

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'deactive') {
                $query->onlyTrashed();
            } elseif ($status === 'pending') {
                $query->whereNull('deleted_at')
                    ->where('role', 'manager')
                    ->where(function ($q) {
                        $q->whereNull('is_approved')
                          ->orWhere('is_approved', false);
                    });
            } elseif ($status === 'active') {
                $query->whereNull('deleted_at')
                    ->where(function ($q) {
                        $q->where('role', '!=', 'manager')
                          ->orWhere('is_approved', true);
                    });
            }
        }

        return DataTables::eloquent($query)
            ->filter(function ($q) use ($request, $customSearch) {
                $datatableSearch = $request->input('search.value');
                $searchTerm = trim($datatableSearch ?: $customSearch ?: '');
                if ($searchTerm !== '') {
                    $q->where(function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%")
                             ->orWhere('email', 'like', "%{$searchTerm}%");
                    });
                }
            })
            ->editColumn('name', function ($user) {
                $youHtml = '';
                if ($user->id === auth()->id()) {
                    $youHtml = ' <span class="you-chip ms-1">' . __('user.you') . '</span>';
                }
                return '<strong class="user-name">' . e($user->name) . '</strong>' . $youHtml;
            })
            ->editColumn('email', function ($user) {
                return e($user->email);
            })
            ->editColumn('role', function ($user) {
                $class = match($user->role) {
                    'superadmin' => 'badge-superadmin',
                    'manager' => 'badge-manager',
                    default => 'badge-owner',
                };
                return '<span class="role-badge ' . $class . '">' . __('user.role_' . $user->role) . '</span>';
            })
            ->addColumn('shop_branch', function ($user) {
                if ($user->role === 'manager') {
                    $shopName = $user->assignedShop?->name;
                    $branchName = $user->assignedBranch?->name;
                    if ($shopName) {
                        return e($shopName) . ($branchName ? ' (' . e($branchName) . ')' : '');
                    }
                }
                return '-';
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? $user->created_at->format('M d, Y') : '-';
            })
            ->addColumn('status', function ($user) {
                if ($user->trashed()) {
                    return '<span class="status-badge status-deactive">' . __('user.deactive') . '</span>';
                } elseif ($user->isPendingApproval()) {
                    return '<span class="status-badge status-pending"><i class="bi bi-clock-history me-1"></i>' . __('user.pending_approval') . '</span>';
                } else {
                    return '<span class="status-badge status-active">' . __('user.active') . '</span>';
                }
            })
            ->addColumn('actions', function ($user) {
                $showUrl = route('user.show', $user->id);
                $editUrl = route('user.edit', $user->id);
                $activateUrl = route('user.activate', $user->id);
                $deactivateUrl = route('user.deactivate', $user->id);
                $approveUrl = route('user.approve.form', $user->id);
                
                $csrfToken = csrf_token();
                $confirmDeactivate = __('user.confirm_deactivate');
                $confirmActivate = __('user.confirm_activate');
                $deactivateLabel = __('user.deactivate');
                $activateLabel = __('user.activate');
                $editLabel = __('app.edit');
                $approveLabel = __('user.approve_title');

                // View Details
                $html = '<a href="' . $showUrl . '" class="btn btn-sm btn-row-action btn-row-view" title="' . __('app.view') . '"><i class="bi bi-eye"></i></a> ';

                // Dropdown trigger
                $html .= '<div class="d-inline-block dropdown">';
                $html .= '<button class="btn btn-sm btn-row-action dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: #d8e4ee; background: #f8fafc;"><i class="bi bi-three-dots-vertical"></i></button>';
                $html .= '<ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:12px; border: 1px solid #e2edf6; font-size: 0.85rem;">';

                if ($user->trashed()) {
                    $html .= '<li>';
                    $html .= '<form action="' . $activateUrl . '" method="POST" onsubmit="return confirm(\'' . e($confirmActivate) . '\')">';
                    $html .= '<input type="hidden" name="_token" value="' . $csrfToken . '">';
                    $html .= '<button type="submit" class="dropdown-item text-success"><i class="bi bi-arrow-counterclockwise me-2"></i>' . $activateLabel . '</button>';
                    $html .= '</form>';
                    $html .= '</li>';
                } else {
                    if ($user->isPendingApproval()) {
                        $html .= '<li><a class="dropdown-item text-warning" href="' . $approveUrl . '"><i class="bi bi-person-check me-2"></i>' . $approveLabel . '</a></li>';
                    } else {
                        $html .= '<li><a class="dropdown-item text-primary" href="' . $editUrl . '"><i class="bi bi-pencil me-2"></i>' . $editLabel . '</a></li>';
                    }

                    if ($user->id !== auth()->id()) {
                        $html .= '<li><hr class="dropdown-divider" style="border-color: #e4edf6;"></li>';
                        $html .= '<li>';
                        $html .= '<form action="' . $deactivateUrl . '" method="POST" onsubmit="return confirm(\'' . e($confirmDeactivate) . '\')">';
                        $html .= '<input type="hidden" name="_token" value="' . $csrfToken . '">';
                        $html .= '<button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>' . $deactivateLabel . '</button>';
                        $html .= '</form>';
                        $html .= '</li>';
                    }
                }

                $html .= '</ul>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['name', 'role', 'status', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $moduleAccessOptions = $this->moduleAccessOptions();
        $shops = Shop::orderBy('name')->get(['id', 'name']);

        return view('user::create', compact('moduleAccessOptions', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedModuleKeys = User::availableModuleAccessKeys();
        $role = $request->input('role');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['superadmin', 'owner', 'manager'])],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ];

        if ($role === 'manager') {
            $rules['shop_id'] = 'nullable|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        $validated = $this->normalizeModuleAccess($validated);
        $validated['password'] = Hash::make($validated['password']);

        if ($role === 'manager') {
            $hasAssignment = !empty($validated['shop_id']) && !empty($validated['branch_id']);
            $validated['is_approved'] = $hasAssignment ? true : false;
            $validated['shop_id'] = $validated['shop_id'] ?? null;
            $validated['branch_id'] = $validated['branch_id'] ?? null;
        } else {
            $validated['is_approved'] = null;
            unset($validated['shop_id'], $validated['branch_id']);
        }

        User::create($validated);

        return redirect()->route('user.index')
            ->with('success', __('user.created'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['assignedShop', 'assignedBranch'])->findOrFail($id);
        return view('user::show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $moduleAccessOptions = $this->moduleAccessOptions();
        $shops = Shop::orderBy('name')->get(['id', 'name']);
        $branches = Branch::with('shop:id,name')->orderBy('name')->get(['id', 'name', 'shop_id']);

        return view('user::edit', compact('user', 'moduleAccessOptions', 'shops', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $allowedModuleKeys = User::availableModuleAccessKeys();
        $role = $request->input('role', $user->role);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['superadmin', 'owner', 'manager'])],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ];

        if ($role === 'manager') {
            $rules['shop_id'] = 'nullable|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated = $this->normalizeModuleAccess($validated);

        if ($role === 'manager') {
            $validated['shop_id'] = $validated['shop_id'] ?? null;
            $validated['branch_id'] = $validated['branch_id'] ?? null;
            if (!empty($validated['shop_id']) && !empty($validated['branch_id']) && !$user->is_approved) {
                $validated['is_approved'] = true;
            }
        } else {
            $validated['is_approved'] = null;
            $validated['shop_id'] = null;
            $validated['branch_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('user.index')
            ->with('success', __('user.updated'));
    }

    /**
     * Show the approval form for a pending manager.
     */
    public function approveForm($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isManager(), 404);

        $shops = Shop::orderBy('name')->get(['id', 'name']);
        $branches = Branch::with('shop:id,name')->orderBy('name')->get(['id', 'name', 'shop_id']);
        $moduleAccessOptions = $this->moduleAccessOptions();

        return view('user::approve', compact('user', 'shops', 'branches', 'moduleAccessOptions'));
    }

    /**
     * Process manager approval: assign shop, branch (optional), permissions.
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isManager(), 404);

        $allowedModuleKeys = User::availableModuleAccessKeys();

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'branch_id' => [
                'nullable',
                'exists:branches,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        $branch = Branch::find($value);
                        if ($branch && (int) $branch->shop_id !== (int) $request->input('shop_id')) {
                            $fail(__('user.branch_shop_mismatch'));
                        }
                    }
                },
            ],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ]);

        $selected = $validated['module_access'] ?? [];
        if (count($selected) === 0) {
            $selected = $allowedModuleKeys;
        }

        $user->update([
            'shop_id' => $validated['shop_id'],
            'branch_id' => $validated['branch_id'],
            'module_access' => array_values(array_intersect($allowedModuleKeys, $selected)),
            'is_approved' => true,
        ]);

        return redirect()->route('user.show', $user->id)
            ->with('success', __('user.manager_approved'));
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => __('user.cannot_deactivate_self')]);
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', __('user.deactivated'));
    }

    /**
     * Restore a soft-deleted user.
     */
    public function activate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('user.index')
            ->with('success', __('user.activated'));
    }

    /**
     * Backward compatibility for previous route/action naming.
     */
    public function destroy($id)
    {
        return $this->deactivate($id);
    }

    /**
     * Backward compatibility for previous route/action naming.
     */
    public function restore($id)
    {
        return $this->activate($id);
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => __('user.cannot_delete_self')]);
        }

        $user->forceDelete();

        return redirect()->route('user.index')
            ->with('success', __('user.permanently_deleted'));
    }
}
