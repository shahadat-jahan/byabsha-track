<?php

use App\Models\User;
use Modules\Settings\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('superadmin can view dashboard settings page', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    $response = $this->actingAs($superadmin)->get(route('settings.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Dashboard Settings');
    $response->assertSee('dashboard_theme_color');
});

test('superadmin can view landing settings page', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    $response = $this->actingAs($superadmin)->get(route('settings.landing'));

    $response->assertStatus(200);
    $response->assertSee('Landing Page Settings');
});

test('superadmin can update branding settings and upload logo', function () {
    Storage::fake('public');

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    // Ensure setting records exist
    Setting::set('app_name', 'Byabsha Track', 'text', 'dashboard');
    Setting::set('dashboard_logo', '', 'text', 'dashboard');

    $logo = UploadedFile::fake()->image('custom_logo.png');

    $response = $this->actingAs($superadmin)->put(route('settings.update', ['group' => 'dashboard']), [
        'settings' => [
            'app_name' => 'My Brand App',
        ],
        'settings_files' => [
            'dashboard_logo' => $logo,
        ]
    ]);

    $response->assertRedirect(route('settings.dashboard'));
    
    // Assert settings database values updated
    $this->assertEquals('My Brand App', Setting::get('app_name'));
    
    $logoPath = Setting::get('dashboard_logo');
    $this->assertNotEmpty($logoPath);
    
    // The logo should be stored in public disk
    $relativePath = str_replace('/storage/', '', $logoPath);
    Storage::disk('public')->assertExists($relativePath);
});

test('old settings routes redirect to dashboard settings', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    $response = $this->actingAs($superadmin)->get('/settings/general');
    $response->assertRedirect(route('settings.dashboard'));

    $response = $this->actingAs($superadmin)->get('/settings/business');
    $response->assertRedirect(route('settings.dashboard'));
});

test('landing page loads with dynamic configurations', function () {
    Setting::set('app_name', 'Dynamic Custom App Title', 'text', 'dashboard');
    Setting::set('dashboard_theme_color', '#8b5cf6', 'text', 'dashboard');
    Setting::set('dashboard_logo', '/storage/branding/fake_logo.png', 'text', 'dashboard');

    $response = $this->get(route('landing.index'));

    $response->assertStatus(200);
    $response->assertSee('Dynamic Custom App Title');
    $response->assertSee('#8b5cf6');
    $response->assertSee('/storage/branding/fake_logo.png');
});

