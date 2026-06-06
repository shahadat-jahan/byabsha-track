<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionPlan;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->route($homeRouteName);
        }

        return redirect()->route('landing.index', ['auth' => 'login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Block pending managers from logging in
            if ($user instanceof User && $user->isPendingApproval()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => __('auth.manager_pending_login'),
                ])->withInput($request->only('email', '_form'));
            }

            $request->session()->regenerate();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->intended(route($homeRouteName));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('email', '_form'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->route($homeRouteName);
        }

        return redirect()->route('landing.index', ['auth' => 'register']);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', Rule::in(['owner', 'manager'])],
        ]);

        $role = $validated['role'] ?? 'owner';
        $isManager = $role === 'manager';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            // Managers start as pending (false); owners/superadmins don't use this column (null)
            'is_approved' => $isManager ? false : null,
        ]);

        // Provision a 1-month free trial subscription for new owners
        if (! $isManager) {
            $freePlan = SubscriptionPlan::where('slug', 'free')
                ->where('is_trial', true)
                ->first();

            if ($freePlan) {
                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $freePlan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($freePlan->trial_days ?? 30),
                ]);
            }
        }

        if ($isManager) {
            // Manager must wait for admin approval — do not log them in
            return redirect()->route('landing.index', ['auth' => 'login'])
                ->with('manager_pending', __('auth.register_success_manager'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->homeRouteName())
            ->with('success', __('auth.register_success'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing.index');
    }
}
