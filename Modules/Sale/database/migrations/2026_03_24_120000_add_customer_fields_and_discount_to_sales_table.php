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
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('sale_price');
            }

            if (!Schema::hasColumn('sales', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('sale_date');
            }

            if (!Schema::hasColumn('sales', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }

            if (!Schema::hasColumn('sales', 'customer_address')) {
                $table->string('customer_address', 500)->nullable()->after('customer_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('sales', 'customer_address')) {
                $columnsToDrop[] = 'customer_address';
            }

            if (Schema::hasColumn('sales', 'customer_phone')) {
                $columnsToDrop[] = 'customer_phone';
            }

            if (Schema::hasColumn('sales', 'customer_name')) {
                $columnsToDrop[] = 'customer_name';
            }

            if (Schema::hasColumn('sales', 'discount')) {
                $columnsToDrop[] = 'discount';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
