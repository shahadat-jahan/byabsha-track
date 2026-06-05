<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restocks', function (Blueprint $table) {
            // remaining_quantity tracks how many units from this batch have not yet been sold
            $table->unsignedInteger('remaining_quantity')->default(0)->after('quantity');
        });

        // Initialise remaining_quantity = quantity for all existing restocks,
        // then apply FIFO consumption based on existing sales so the numbers are consistent.
        $this->backfillRemainingQuantities();
    }

    private function backfillRemainingQuantities(): void
    {
        // Set every batch's remaining_quantity to its full quantity to start with
        DB::table('restocks')->update(['remaining_quantity' => DB::raw('quantity')]);

        // For each (product_id, shop_id) pair, consume existing sales in FIFO order
        $soldGroups = DB::table('sales')
            ->select('product_id', 'shop_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereNull('deleted_at')
            ->groupBy('product_id', 'shop_id')
            ->get();

        foreach ($soldGroups as $group) {
            $remaining = (int) $group->total_sold;

            $batches = DB::table('restocks')
                ->where('product_id', $group->product_id)
                ->where('shop_id', $group->shop_id)
                ->whereNull('deleted_at')
                ->orderBy('restock_date', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'quantity']);

            foreach ($batches as $batch) {
                if ($remaining <= 0) {
                    break;
                }

                $consume = min($remaining, (int) $batch->quantity);
                DB::table('restocks')
                    ->where('id', $batch->id)
                    ->decrement('remaining_quantity', $consume);

                $remaining -= $consume;
            }
        }
    }

    public function down(): void
    {
        Schema::table('restocks', function (Blueprint $table) {
            $table->dropColumn('remaining_quantity');
        });
    }
};
