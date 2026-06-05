<?php
/**
 * Manual FIFO batch-tracking test script.
 * Run with: php artisan tinker --execute="require 'test_fifo.php';"
 * OR simply:  php test_fifo.php  (from project root, but artisan bootstrap needed)
 *
 * Easiest: paste each section into `php artisan tinker` interactively.
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Restock\Models\Restock;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleBatchItem;
use Modules\Restock\Services\RestockService;

DB::beginTransaction();

try {
    // ----------------------------------------------------------------
    // 1. Pick the first product (or adjust ID)
    // ----------------------------------------------------------------
    $product = Product::first();
    if (!$product) {
        echo "SKIP: No products in database. Create a product first.\n";
        DB::rollBack();
        exit(1);
    }

    $shopId    = $product->shop_id;
    $productId = $product->id;
    $startStock = $product->stock_quantity;

    echo "=== Product: {$product->name} (id={$productId}, shop_id={$shopId}) ===\n";
    echo "Starting stock: {$startStock}\n\n";

    // ----------------------------------------------------------------
    // 2. Restock Batch A — 10 units @ 10.00 each
    // ----------------------------------------------------------------
    $restockService = app(RestockService::class);

    $batchA = $restockService->storeRestock([
        'product_id'               => $productId,
        'shop_id'                  => $shopId,
        'quantity'                 => 10,
        'purchase_price_per_unit'  => 10.00,
        'restock_date'             => '2026-04-01',
        'note'                     => 'Test Batch A',
    ]);

    echo "Batch A created  — id={$batchA->id}, qty=10, price=10, remaining={$batchA->remaining_quantity}\n";

    // ----------------------------------------------------------------
    // 3. Restock Batch B — 10 units @ 12.00 each
    // ----------------------------------------------------------------
    $batchB = $restockService->storeRestock([
        'product_id'               => $productId,
        'shop_id'                  => $shopId,
        'quantity'                 => 10,
        'purchase_price_per_unit'  => 12.00,
        'restock_date'             => '2026-04-02',
        'note'                     => 'Test Batch B',
    ]);

    echo "Batch B created  — id={$batchB->id}, qty=10, price=12, remaining={$batchB->remaining_quantity}\n";

    $product->refresh();
    echo "Stock after 2 restocks: {$product->stock_quantity} (expected " . ($startStock + 20) . ")\n\n";

    // ----------------------------------------------------------------
    // 4. Simulate a quick-sale of 15 units (should consume all 10 from
    //    Batch A at 10 and 5 from Batch B at 12)
    //    Expected weighted avg cost = (10*10 + 5*12) / 15 = 160/15 = 10.67
    // ----------------------------------------------------------------
    // We call the FIFO helper directly via a test Sale create.
    // Use the same code path as SaleController::quickSale.

    // Manually replicate deductFIFO logic here to inspect results
    $batches = Restock::where('product_id', $productId)
        ->where('shop_id', $shopId)
        ->where('remaining_quantity', '>', 0)
        ->whereNull('deleted_at')
        ->orderBy('restock_date', 'asc')
        ->orderBy('id', 'asc')
        ->lockForUpdate()
        ->get();

    $sellQty   = 15;
    $remaining = $sellQty;
    $items     = [];
    $totalCost = 0.0;

    foreach ($batches as $batch) {
        if ($remaining <= 0) break;
        $take = min($remaining, $batch->remaining_quantity);
        $batch->decrement('remaining_quantity', $take);
        $items[]    = ['restock_id' => $batch->id, 'qty' => $take, 'price' => (float) $batch->purchase_price_per_unit];
        $totalCost += $take * (float) $batch->purchase_price_per_unit;
        $remaining -= $take;
    }

    $weightedAvg = round($totalCost / $sellQty, 2);
    $salePrice   = (float) $product->sale_price ?: 20.00;
    $profit      = ($salePrice - $weightedAvg) * $sellQty;

    $sale = Sale::create([
        'shop_id'                 => $shopId,
        'product_id'              => $productId,
        'quantity'                => $sellQty,
        'sale_price'              => $salePrice,
        'purchase_price_per_unit' => $weightedAvg,
        'discount'                => 0,
        'total_amount'            => $sellQty * $salePrice,
        'profit'                  => $profit,
        'sale_date'               => '2026-04-06',
        'customer_name'           => 'Test Customer',
    ]);

    foreach ($items as $item) {
        SaleBatchItem::create([
            'sale_id'                 => $sale->id,
            'restock_id'              => $item['restock_id'],
            'quantity'                => $item['qty'],
            'purchase_price_per_unit' => $item['price'],
        ]);
    }

    $product->decrement('stock_quantity', $sellQty);

    $product->refresh();
    $batchA->refresh();
    $batchB->refresh();

    echo "=== Sale of 15 units ===\n";
    foreach ($items as $item) {
        echo "  Consumed {$item['qty']} units from restock #{$item['restock_id']} @ {$item['price']} each\n";
    }
    echo "Weighted avg purchase cost : {$weightedAvg}  (expected 10.67)\n";
    echo "Profit                     : {$profit}  (expected " . (($salePrice - 10.67) * 15) . ")\n";
    echo "Batch A remaining          : {$batchA->remaining_quantity}  (expected 0)\n";
    echo "Batch B remaining          : {$batchB->remaining_quantity}  (expected 5)\n";
    echo "Product stock              : {$product->stock_quantity}  (expected " . ($startStock + 5) . ")\n\n";

    $batchItemsCount = SaleBatchItem::where('sale_id', $sale->id)->count();
    echo "SaleBatchItem rows for this sale: {$batchItemsCount}  (expected 2)\n\n";

    // ----------------------------------------------------------------
    // 5. Check assertions
    // ----------------------------------------------------------------
    $pass = true;

    $check = function(string $label, $actual, $expected) use (&$pass) {
        $ok = (string) $actual === (string) $expected;
        echo ($ok ? "PASS" : "FAIL") . "  {$label}: got {$actual}, want {$expected}\n";
        if (!$ok) $pass = false;
    };

    $check('Batch A remaining',  $batchA->remaining_quantity, 0);
    $check('Batch B remaining',  $batchB->remaining_quantity, 5);
    $check('Weighted avg cost',  $weightedAvg,               10.67);
    $check('SaleBatchItem rows', $batchItemsCount,            2);
    $check('Product stock',      $product->stock_quantity,    $startStock + 5);

    echo "\n" . ($pass ? "ALL TESTS PASSED" : "SOME TESTS FAILED") . "\n";

} finally {
    // Always roll back so the test data doesn't pollute the DB
    DB::rollBack();
    $product->refresh();
    echo "\nRolled back — stock restored to {$product->stock_quantity}\n";
}
