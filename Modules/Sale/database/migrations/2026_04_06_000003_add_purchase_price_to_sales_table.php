<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Weighted-average purchase cost per unit for the goods sold in this sale.
            // Populated by the FIFO deduction logic; NULL for sales recorded before this
            // feature was introduced.
            if (! Schema::hasColumn('sales', 'purchase_price_per_unit')) {
                $table->decimal('purchase_price_per_unit', 12, 2)->nullable()->after('sale_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'purchase_price_per_unit')) {
                $table->dropColumn('purchase_price_per_unit');
            }
        });
    }
};
