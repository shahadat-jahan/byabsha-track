<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\EnsureSubscriptionActive;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Damage\Models\Damage;
use Modules\Shop\Models\Shop;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mock(PlanService::class, function ($mock) {
        $mock->shouldReceive('isFeatureEnabled')->andReturn(true);
        $mock->shouldReceive('canCreate')->andReturn(true);
    });
});

test('admin can view damages table with yajra datatable structure', function () {
    $this->withoutMiddleware([
        EnsureSubscriptionActive::class,
        CheckModuleAccess::class,
    ]);

    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Damage Test Shop',
        'user_id' => $owner->id,
    ]);

    // Create a Damage record
    $damage = Damage::create([
        'shop_id' => $shop->id,
        'reference_no' => 'DMG-TEST-001',
        'damage_date' => now()->toDateString(),
        'total_quantity' => 10,
        'total_loss' => 500.00,
        'note' => 'Test damage entry note',
        'created_by' => $owner->id,
    ]);

    // Request the AJAX DataTable route
    $response = $this->actingAs($owner)
        ->get(route('damage.table', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
    $response->assertSee('DMG-TEST-001');
    $response->assertSee('Damage Test Shop');
});

test('admin cannot view damages table for unauthorized shop', function () {
    $this->withoutMiddleware([
        EnsureSubscriptionActive::class,
        CheckModuleAccess::class,
    ]);

    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);
    $otherOwner = User::factory()->create(['role' => 'owner', 'name' => 'Other Owner User']);

    $shop = Shop::create([
        'name' => 'Damage Test Shop',
        'user_id' => $owner->id,
    ]);

    $otherShop = Shop::create([
        'name' => 'Other Test Shop',
        'user_id' => $otherOwner->id,
    ]);

    // Create a Damage record in the other shop
    Damage::create([
        'shop_id' => $otherShop->id,
        'reference_no' => 'DMG-OTHER-001',
        'damage_date' => now()->toDateString(),
        'total_quantity' => 5,
        'total_loss' => 200.00,
        'note' => 'Other damage entry note',
        'created_by' => $otherOwner->id,
    ]);

    // Request the AJAX DataTable route with the other shop's ID (which owner doesn't own)
    $response = $this->actingAs($owner)
        ->get(route('damage.table', ['shop_id' => $otherShop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(403);
});
