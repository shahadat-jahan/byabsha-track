<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Modules\Product\Models\Product;
use Modules\Restock\Services\RestockService;

$svc = app(RestockService::class);

$a = $svc->storeRestock([
    'product_id' => 1,
    'shop_id' => 4,
    'quantity' => 10,
    'purchase_price_per_unit' => 10,
    'restock_date' => '2026-04-01',
    'note' => 'Batch A cheap stock',
]);

$b = $svc->storeRestock([
    'product_id' => 1,
    'shop_id' => 4,
    'quantity' => 10,
    'purchase_price_per_unit' => 12,
    'restock_date' => '2026-04-02',
    'note' => 'Batch B expensive stock',
]);

$stock = Product::find(1)->stock_quantity;
echo 'Batch A id='.$a->id.' qty='.$a->quantity.' remaining='.$a->remaining_quantity."\n";
echo 'Batch B id='.$b->id.' qty='.$b->quantity.' remaining='.$b->remaining_quantity."\n";
echo 'Stock now: '.$stock."\n";
echo "Go to: http://localhost:8000/products/1/batches\n";
