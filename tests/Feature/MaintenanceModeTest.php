<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Settings\Models\Setting;

uses(RefreshDatabase::class);

test('guest user can access the landing page when maintenance mode is disabled', function () {
    Setting::set('maintenance_mode', '0', 'boolean', 'system');

    $response = $this->get('/');

    $response->assertStatus(200);
});

test('guest user is blocked when accessing the landing page if maintenance mode is enabled', function () {
    Setting::set('maintenance_mode', '1', 'boolean', 'system');

    $response = $this->get('/');

    $response->assertStatus(503);
});

test('guest user can still access the login page when maintenance mode is enabled', function () {
    Setting::set('maintenance_mode', '1', 'boolean', 'system');

    $response = $this->get('/login');
    $response->assertRedirect(route('landing.index', ['auth' => 'login']));

    $response = $this->followRedirects($response);
    $response->assertStatus(200);
});

test('standard user (owner) is blocked from accessing the dashboard when maintenance mode is enabled', function () {
    Setting::set('maintenance_mode', '1', 'boolean', 'system');

    $owner = User::factory()->create(['role' => 'owner']);

    $response = $this->actingAs($owner)->get('/dashboard');

    $response->assertStatus(503);
});

test('superadmin user bypasses maintenance mode and can access landing and dashboard', function () {
    Setting::set('maintenance_mode', '1', 'boolean', 'system');

    $superadmin = User::factory()->create(['role' => 'superadmin']);

    $response = $this->actingAs($superadmin)->get('/');
    $response->assertStatus(200);

    $response = $this->actingAs($superadmin)->get('/dashboard');
    $this->assertNotEquals(503, $response->getStatusCode());
});
