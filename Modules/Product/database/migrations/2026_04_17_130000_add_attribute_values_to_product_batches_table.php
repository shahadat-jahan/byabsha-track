<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('product_batches', 'attribute_values')) {
                $table->json('attribute_values')->nullable()->after('source_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            if (Schema::hasColumn('product_batches', 'attribute_values')) {
                $table->dropColumn('attribute_values');
            }
        });
    }
};
