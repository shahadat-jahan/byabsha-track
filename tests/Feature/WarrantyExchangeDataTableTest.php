<?php

use App\Models\User;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductBatch;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleWarranty;
use Modules\Sale\Models\SaleExchange;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mock(\App\Services\PlanService::class, function ($mock) {
        $mock->shouldReceive('isFeatureEnabled')->andReturn(true);
        $mock->shouldReceive('canCreate')->andReturn(true);
    });
});

test('admin can view warranties table with yajra datatable structure', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Warranty Test Shop',
        'user_id' => $owner->id
    ]);

    $product = Product::create([
        'shop_id' => $shop->id,
        'name' => 'Warranty Test Product',
        'purchase_price' => 150,
        'sale_price' => 200,
        'stock_quantity' => 50,
        'created_by' => $owner->id,
    ]);

    $batch = ProductBatch::create([
        'product_id' => $product->id,
        'shop_id' => $shop->id,
        'batch_code' => 'B-001',
        'purchase_price' => 150,
        'initial_quantity' => 50,
        'remaining_quantity' => 50,
        'batch_date' => now()->toDateString(),
    ]);

    $sale = Sale::create([
        'shop_id' => $shop->id,
        'product_id' => $product->id,
        'product_batch_id' => $batch->id,
        'quantity' => 1,
        'purchase_price' => 150,
        'sale_price' => 200,
        'total_amount' => 200,
        'profit' => 50,
        'sale_date' => now()->toDateString(),
        'customer_name' => 'John Warranty',
    ]);

    $warranty = SaleWarranty::create([
        'sale_id' => $sale->id,
        'shop_id' => $shop->id,
        'warranty_code' => 'WAR-TEST-001',
        'source_type' => 'manual',
        'coverage_quantity' => 1,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addYear()->toDateString(),
        'status' => 'active',
        'created_by' => $owner->id,
    ]);

    $response = $this->actingAs($owner)
        ->get(route('sale.warranties.table', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
    $response->assertSee('WAR-TEST-001');
    $response->assertSee('Warranty Test Product');
    $response->assertSee('John Warranty');
});

test('admin can view exchanges table with yajra datatable structure', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Exchange Test Shop',
        'user_id' => $owner->id
    ]);

    $product = Product::create([
        'shop_id' => $shop->id,
        'name' => 'Exchange Test Product',
        'purchase_price' => 150,
        'sale_price' => 200,
        'stock_quantity' => 50,
        'created_by' => $owner->id,
    ]);

    $batch = ProductBatch::create([
        'product_id' => $product->id,
        'shop_id' => $shop->id,
        'batch_code' => 'B-001',
        'purchase_price' => 150,
        'initial_quantity' => 50,
        'remaining_quantity' => 50,
        'batch_date' => now()->toDateString(),
    ]);

    $sale = Sale::create([
        'shop_id' => $shop->id,
        'product_id' => $product->id,
        'product_batch_id' => $batch->id,
        'quantity' => 1,
        'purchase_price' => 150,
        'sale_price' => 200,
        'total_amount' => 200,
        'profit' => 50,
        'sale_date' => now()->toDateString(),
        'customer_name' => 'John Exchange',
    ]);

    $exchange = SaleExchange::create([
        'shop_id' => $shop->id,
        'sale_id' => $sale->id,
        'original_batch_id' => $batch->id,
        'quantity' => 1,
        'exchange_date' => now()->toDateString(),
        'exchange_type' => 'return_only',
        'reason' => 'defective',
        'cost_difference' => -150.00,
        'status' => 'completed',
        'created_by' => $owner->id,
    ]);

    $response = $this->actingAs($owner)
        ->get(route('sale.exchanges.table', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'draw',
        'recordsTotal',
        'recordsFiltered',
        'data',
    ]);
    $response->assertSee('Exchange Test Product');
    $response->assertSee('return_only');
});
