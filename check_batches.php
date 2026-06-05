<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::table('restocks')
    ->where('product_id', 1)
    ->whereNull('deleted_at')
    ->orderBy('id')
    ->get(['id','quantity','remaining_quantity','purchase_price_per_unit','note','restock_date']);

foreach ($rows as $r) {
    echo "id={$r->id} qty={$r->quantity} remaining={$r->remaining_quantity} price={$r->purchase_price_per_unit} | {$r->note}\n";
}
echo "\nTotal batches for product 'testertwo': " . count($rows) . "\n";
