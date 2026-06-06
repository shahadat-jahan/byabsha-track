<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\EnsureSubscriptionActive;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Product;
use Modules\Restock\Models\Restock;
use Modules\Shop\Models\Shop;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mock(PlanService::class, function ($mock) {
        $mock->shouldReceive('isFeatureEnabled')->andReturn(true);
        $mock->shouldReceive('canCreate')->andReturn(true);
    });
});

test('admin can view restocks table with yajra datatable structure', function () {
    $this->withoutMiddleware([
        EnsureSubscriptionActive::class,
        CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Restock Test Shop',
        'user_id' => $owner->id,
    ]);

    $product = Product::create([
        'shop_id' => $shop->id,
        'name' => 'Restock Test Product',
        'purchase_price' => 150,
        'sale_price' => 200,
        'stock_quantity' => 50,
        'created_by' => $owner->id,
    ]);

    // Create a Restock record
    $restock = Restock::create([
        'product_id' => $product->id,
        'shop_id' => $shop->id,
        'quantity' => 20,
        'remaining_quantity' => 20,
        'purchase_price_per_unit' => 150.00,
        'total_cost' => 3000.00,
        'restock_date' => now()->toDateString(),
        'note' => 'Initial Restock for Test',
    ]);

    // Request the AJAX DataTable route
    $response = $this->actingAs($owner)
        ->get(route('restock.table', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertSee('Restock Test Product');
    $response->assertSee('Initial Restock for Test');
    $response->assertSee('qty-pill');
    $response->assertSee('btn-row-delete');
});
