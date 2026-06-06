<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new columns only if they don't exist
            if (! Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('name');
            }
            if (! Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('category');
            }

            // Drop columns only if they exist (to handle SQLite constraints)
            $columnsToCheck = ['sku', 'description', 'unit', 'is_active'];
            $existingColumns = [];

            foreach ($columnsToCheck as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $existingColumns[] = $col;
                }
            }

            if (in_array('sku', $existingColumns, true)) {
                $table->dropUnique('products_sku_unique');
            }

            if (! empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });

        // Rename columns separately to avoid issues
        if (Schema::hasColumn('products', 'cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('cost', 'purchase_price');
            });
        }

        if (Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('price', 'sale_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the rename
            $table->renameColumn('purchase_price', 'cost');
            $table->renameColumn('sale_price', 'price');

            // Remove new columns
            $table->dropColumn(['category', 'brand']);

            // Add back old columns
            $table->string('sku')->unique()->after('name');
            $table->text('description')->nullable()->after('sku');
            $table->string('unit')->default('pcs')->after('stock_quantity');
            $table->boolean('is_active')->default(true)->after('shop_id');
        });
    }
};
