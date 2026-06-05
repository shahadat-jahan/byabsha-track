<?php

use App\Models\User;
use Modules\Shop\Models\Shop;
use Modules\Branch\Models\Branch;
use Modules\Product\Models\ProductDynamicField;
use Modules\Category\Models\Category;
use Modules\Brand\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mock(\App\Services\PlanService::class, function ($mock) {
        $mock->shouldReceive('isFeatureEnabled')->andReturn(true);
        $mock->shouldReceive('canCreate')->andReturn(true);
    });
});

test('cannot create branch with duplicate location name even if it is soft deleted', function () {
    $user = User::factory()->create(['role' => 'superadmin']);
    $shop = Shop::create([
        'name' => 'Test Shop',
        'user_id' => $user->id
    ]);

    // Create a branch
    $branch = Branch::create([
        'shop_id' => $shop->id,
        'name' => 'Arpa Nihan',
        'location' => 'Arpa Nihan',
        'is_active' => true,
    ]);

    // Soft delete it
    $branch->delete();

    // Try to create another branch with the same location via HTTP POST request
    $response = $this->actingAs($user)
        ->from(route('branch.create', ['shop_id' => $shop->id]))
        ->post(route('branch.store'), [
            'shop_id' => $shop->id,
            'location' => 'Arpa Nihan',
            'is_active' => 1,
        ]);

    // It should redirect back with validation errors, not throw a 500 database error
    $response->assertStatus(302);
    $response->assertInvalid(['location']);
});

test('cannot create product dynamic field with duplicate key even if it is soft deleted', function () {
    $user = User::factory()->create(['role' => 'superadmin']);
    
    $category = Category::create([
        'name' => 'Electronics',
        'user_id' => $user->id
    ]);

    // Create a dynamic field
    $field = ProductDynamicField::create([
        'category_id' => $category->id,
        'label' => 'RAM',
        'field_key' => 'ram',
        'input_type' => 'text',
        'is_active' => true,
    ]);

    // Soft delete it
    $field->delete();

    // Try to create another field with the same label/key
    $response = $this->actingAs($user)
        ->from(route('product.dynamic-fields.create'))
        ->post(route('product.dynamic-fields.store'), [
            'category_id' => $category->id,
            'label' => 'RAM',
            'input_type' => 'text',
            'is_active' => 1,
        ]);

    // Since the key is generated dynamically in store method,
    // generateFieldKey should auto-append a suffix (e.g. ram_2) because the key 'ram' is already taken
    // Let's verify that the new field is successfully created but with key 'ram_2'
    $response->assertRedirect(route('product.dynamic-fields.index'));
    
    $newField = ProductDynamicField::where('category_id', $category->id)
        ->where('label', 'RAM')
        ->whereNull('deleted_at')
        ->first();
        
    expect($newField)->not->toBeNull();
    expect($newField->field_key)->toBe('ram_2');
});

test('superadmin can see owner in shop list, edit form, and update shop owner', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Super Admin User']);
    $owner1 = User::factory()->create(['role' => 'owner', 'name' => 'First Owner']);
    $owner2 = User::factory()->create(['role' => 'owner', 'name' => 'Second Owner']);

    $shop = Shop::create([
        'name' => 'Pest Shop',
        'user_id' => $owner1->id,
    ]);

    // 1. Assert owner name is visible in shop index
    $response = $this->actingAs($superadmin)->get(route('shop.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertStatus(200);
    $response->assertSee('First Owner');

    // 2. Assert owner dropdown exists in shop edit
    $response = $this->actingAs($superadmin)->get(route('shop.edit', $shop->id));
    $response->assertStatus(200);
    $response->assertSee('First Owner');
    $response->assertSee('Second Owner');

    // 3. Update shop owner
    $response = $this->actingAs($superadmin)->put(route('shop.update', $shop->id), [
        'name' => 'Pest Shop Updated',
        'user_id' => $owner2->id,
    ]);

    $response->assertRedirect(route('shop.index'));
    
    // Assert owner was updated in DB
    $shop->refresh();
    expect($shop->user_id)->toBe($owner2->id);
    expect($shop->name)->toBe('Pest Shop Updated');
});

test('only owners and superadmins can access brand CRUD and superadmin can monitor creators and counts', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);
    
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);
    $manager = User::factory()->create(['role' => 'manager', 'name' => 'Store Manager User']);

    // Remove debugging dd
    // 1. Assert manager is forbidden (403) from listing brands
    $response = $this->actingAs($manager)->get(route('brand.index'));
    $response->assertStatus(403);

    // 2. Assert owner can access brand list successfully
    $response = $this->actingAs($owner)->get(route('brand.index'));
    $response->assertStatus(200);

    // 3. Create a brand as owner
    $brand = Brand::create([
        'name' => 'Owner Brand',
        'user_id' => $owner->id
    ]);

    // 4. Assert superadmin (Admin) can view the brand and monitor creators and counts
    $response = $this->actingAs($superadmin)->get(route('brand.index'));
    $response->assertStatus(200);
    $response->assertSee('Total Brands Created:');

    $ajaxResponse = $this->actingAs($superadmin)->get(route('brand.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $ajaxResponse->assertStatus(200);
    $ajaxResponse->assertSee('Shop Owner User');
});

test('owners, managers, and superadmins can access category CRUD and superadmin can monitor creators and counts', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);
    
    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);
    $manager = User::factory()->create(['role' => 'manager', 'name' => 'Store Manager User']);

    // 1. Assert manager can access category list successfully
    $response = $this->actingAs($manager)->get(route('category.index'));
    $response->assertStatus(200);

    // 2. Assert owner can access category list successfully
    $response = $this->actingAs($owner)->get(route('category.index'));
    $response->assertStatus(200);

    // 3. Create a category as owner
    $category = Category::create([
        'name' => 'Owner Category',
        'user_id' => $owner->id
    ]);

    // 4. Assert superadmin (Admin) can view the category and monitor creators and counts
    $response = $this->actingAs($superadmin)->get(route('category.index'));
    $response->assertStatus(200);
    $response->assertSee('Total Categories Created:');

    $ajaxResponse = $this->actingAs($superadmin)->get(route('category.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $ajaxResponse->assertStatus(200);
    $ajaxResponse->assertSee('Shop Owner User');
});

test('admin can monitor who created branch and created_by is assigned on store', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Test Shop',
        'user_id' => $owner->id
    ]);

    // Create branch via HTTP POST request
    $response = $this->actingAs($owner)
        ->post(route('branch.store'), [
            'shop_id' => $shop->id,
            'location' => 'Dhaka Branch',
            'is_active' => 1,
        ]);

    // Assert it redirects
    $response->assertRedirect();
    
    // Assert created_by was correctly stored
    $branch = Branch::where('shop_id', $shop->id)->where('name', 'Dhaka Branch')->first();
    expect($branch)->not->toBeNull();
    expect($branch->created_by)->toBe($owner->id);

    // Assert superadmin can view it in AJAX DataTable list and see the creator name
    $ajaxResponse = $this->actingAs($superadmin)
        ->get(route('branch.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $ajaxResponse->assertStatus(200);
    $ajaxResponse->assertSee('Shop Owner User');
    
    // Assert show page displays creator's name
    $showResponse = $this->actingAs($superadmin)->get(route('branch.show', $branch->id));
    $showResponse->assertStatus(200);
    $showResponse->assertSee('Shop Owner User');
});

test('admin can monitor who created product and created_by is assigned on store', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Test Shop',
        'user_id' => $owner->id
    ]);

    // Create product via HTTP POST request
    $response = $this->actingAs($owner)
        ->post(route('product.store'), [
            'shop_id' => $shop->id,
            'name' => 'Test Product',
            'purchase_price' => 100,
            'stock_quantity' => 10,
        ]);

    // Assert it redirects
    $response->assertRedirect();
    
    // Assert created_by was correctly stored
    $product = Modules\Product\Models\Product::where('shop_id', $shop->id)->where('name', 'Test Product')->first();
    expect($product)->not->toBeNull();
    expect($product->created_by)->toBe($owner->id);

    // Assert superadmin can view it in the index list and see the creator name
    $indexResponse = $this->actingAs($superadmin)
        ->get(route('product.index', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $indexResponse->assertStatus(200);
    $indexResponse->assertSee('Shop Owner User');
    
    // Assert show page displays creator's name
    $showResponse = $this->actingAs($superadmin)->get(route('product.show', $product->id));
    $showResponse->assertStatus(200);
    $showResponse->assertSee('Shop Owner User');
});

test('admin can monitor who created product dynamic field and created_by is assigned on store', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    // Create dynamic field via HTTP POST request
    $response = $this->actingAs($owner)
        ->post(route('product.dynamic-fields.store'), [
            'label' => 'Warranty Duration',
            'input_type' => 'text',
        ]);

    // Assert it redirects
    $response->assertRedirect();

    // Assert created_by was correctly stored
    $field = ProductDynamicField::where('label', 'Warranty Duration')->first();
    expect($field)->not->toBeNull();
    expect($field->created_by)->toBe($owner->id);

    // Assert superadmin can view it in the index list and see the creator name
    $indexResponse = $this->actingAs($superadmin)
        ->get(route('product.dynamic-fields.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $indexResponse->assertStatus(200);
    $indexResponse->assertSee('Shop Owner User');
});

test('admin can monitor who created product in stock overview list', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Test Shop',
        'user_id' => $owner->id
    ]);

    // Create product
    $product = Modules\Product\Models\Product::create([
        'shop_id' => $shop->id,
        'name' => 'Test Stock Product',
        'purchase_price' => 120,
        'sale_price' => 150,
        'stock_quantity' => 15,
        'created_by' => $owner->id,
    ]);

    // Assert superadmin can view stock index and see the creator name
    $response = $this->actingAs($superadmin)
        ->get(route('stock.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertSee('Shop Owner User');
});

test('admin can view products table for sales with yajra datatable structure and primary sale button', function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);

    $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Admin User']);
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Shop Owner User']);

    $shop = Shop::create([
        'name' => 'Sales Test Shop',
        'user_id' => $owner->id
    ]);

    $product = Modules\Product\Models\Product::create([
        'shop_id' => $shop->id,
        'name' => 'Redesigned Sale Product',
        'purchase_price' => 200,
        'sale_price' => 250,
        'stock_quantity' => 10,
        'created_by' => $owner->id,
    ]);

    // Create a product batch for this product
    $batch = Modules\Product\Models\ProductBatch::create([
        'product_id' => $product->id,
        'shop_id' => $shop->id,
        'batch_code' => 'BATCH-SALES-XYZ',
        'purchase_price' => 200,
        'initial_quantity' => 10,
        'remaining_quantity' => 10,
        'batch_date' => now(),
    ]);

    $response = $this->actingAs($superadmin)
        ->get(route('sale.products-table', ['shop_id' => $shop->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertSee('BATCH-SALES-XYZ');
    $response->assertSee('batch-code-pill');
    $response->assertSee('btn-sale-primary js-sale-btn');
    $response->assertSee('btn-actions-dropdown');
});



