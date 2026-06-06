<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (! Schema::hasColumn('sales', 'product_batch_id')) {
                $table->foreignId('product_batch_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('product_batches')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('sales', 'purchase_price_per_unit')) {
                $table->decimal('purchase_price_per_unit', 12, 2)
                    ->nullable()
                    ->after('sale_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'product_batch_id')) {
                $table->dropForeign(['product_batch_id']);
                $table->dropColumn('product_batch_id');
            }

            if (Schema::hasColumn('sales', 'purchase_price_per_unit')) {
                $table->dropColumn('purchase_price_per_unit');
            }
        });
    }
};
