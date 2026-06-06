<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('superadmin can view users table with yajra datatable structure', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    // Create some fake users
    User::factory()->create(['name' => 'John Doe Manager', 'email' => 'john@manager.local', 'role' => 'manager']);
    User::factory()->create(['name' => 'Jane Owner', 'email' => 'jane@owner.local', 'role' => 'owner']);

    $response = $this->actingAs($superadmin)
        ->get(route('user.table'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);

    $response->assertSee('John Doe Manager');
    $response->assertSee('Jane Owner');
    $response->assertSee('jane@owner.local');
});

test('non-superadmin users cannot view users table', function () {
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $response = $this->actingAs($owner)
        ->get(route('user.table'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(403);
});

test('superadmin can search and filter users table by role and status', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);

    $manager = User::factory()->create(['name' => 'Bob Manager', 'email' => 'bob@manager.local', 'role' => 'manager']);
    $owner = User::factory()->create(['name' => 'Alice Owner', 'email' => 'alice@owner.local', 'role' => 'owner']);

    // Deactivated user
    $deactivated = User::factory()->create(['name' => 'Charlie Expelled', 'email' => 'charlie@deactive.local', 'role' => 'manager']);
    $deactivated->delete(); // Soft delete

    // 1. Filter by role
    $response = $this->actingAs($superadmin)
        ->get(route('user.table', ['role' => 'owner']), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertSee('Alice Owner');
    $response->assertDontSee('Bob Manager');

    // 2. Filter by status deactive
    $response = $this->actingAs($superadmin)
        ->get(route('user.table', ['status' => 'deactive']), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertSee('Charlie Expelled');
    $response->assertDontSee('Alice Owner');
    $response->assertDontSee('Bob Manager');

    // 3. Search query
    $response = $this->actingAs($superadmin)
        ->get(route('user.table', ['custom_search' => 'Bob']), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertSee('Bob Manager');
    $response->assertDontSee('Alice Owner');
});
