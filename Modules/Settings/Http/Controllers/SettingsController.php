<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private const GROUPS = ['dashboard', 'landing', 'system'];

    /**
     * Display the settings form
     */
    public function index()
    {
        return redirect()->route('settings.dashboard');
    }

    public function dashboard()
    {
        return $this->showByGroup('dashboard');
    }

    public function landing()
    {
        return $this->showByGroup('landing');
    }

    public function system()
    {
        return $this->showByGroup('system');
    }

    private function showByGroup(string $group)
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        $activeGroup = $this->normalizeGroup($group);

        return view('settings::index', compact('settings', 'activeGroup'));
    }

    /**
     * Update settings
     */
    public function update(Request $request, ?string $group = null)
    {
        $activeGroup = $this->normalizeGroup($group);

        $validated = $request->validate([
            'settings' => 'nullable|array',
            'settings.*' => 'nullable|string|max:1000',
            'remove_files' => 'nullable|array',
            'remove_files.*' => 'string',
            'settings_files' => 'nullable|array',
            'settings_files.*' => 'nullable|file|image|max:2048',
        ]);

        try {
            // Handle file deletions
            if ($request->has('remove_files')) {
                foreach ($request->input('remove_files') as $key) {
                    $setting = Setting::where('key', $key)->first();
                    if ($setting && $setting->value) {
                        $relativePath = str_replace('/storage/', '', $setting->value);
                        if (Storage::disk('public')->exists($relativePath)) {
                            Storage::disk('public')->delete($relativePath);
                        }
                        $setting->update(['value' => '']);
                    }
                }
            }

            // Handle file uploads (logo / favicon)
            if ($request->hasFile('settings_files')) {
                foreach ($request->file('settings_files') as $key => $file) {
                    $path = $file->store('branding', 'public');
                    $setting = Setting::where('key', $key)->first();
                    if ($setting) {
                        // Delete old file if exists
                        if ($setting->value) {
                            $oldRelativePath = str_replace('/storage/', '', $setting->value);
                            if (Storage::disk('public')->exists($oldRelativePath)) {
                                Storage::disk('public')->delete($oldRelativePath);
                            }
                        }
                        $setting->update(['value' => '/storage/' . $path]);
                    }
                }
            }

            // Handle text/other settings
            if (isset($validated['settings'])) {
                foreach ($validated['settings'] as $key => $value) {
                    $setting = Setting::where('key', $key)->first();
                    if ($setting) {
                        $setting->update(['value' => $value ?? '']);
                    }
                }
            }

            // Clear cache after updating
            Setting::clearCache();

            return redirect()->route('settings.' . $activeGroup)
                ->with('success', __('settings.updated'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('settings.update_failed') . ' ' . $e->getMessage()]);
        }
    }

    /**
     * Clear settings cache
     */
    public function clearCache(?string $group = null)
    {
        $activeGroup = $this->normalizeGroup($group);

        try {
            Setting::clearCache();

            return redirect()->route('settings.' . $activeGroup)
                ->with('success', __('settings.cache_cleared'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('settings.cache_clear_failed')]);
        }
    }

    private function normalizeGroup(?string $group): string
    {
        return in_array($group, self::GROUPS, true) ? $group : 'dashboard';
    }
}
