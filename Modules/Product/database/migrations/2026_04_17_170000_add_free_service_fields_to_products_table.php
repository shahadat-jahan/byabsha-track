<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'has_free_service')) {
                $table->boolean('has_free_service')->default(false)->after('stock_quantity');
            }

            if (! Schema::hasColumn('products', 'free_service_duration_value')) {
                $table->unsignedInteger('free_service_duration_value')->nullable()->after('has_free_service');
            }

            if (! Schema::hasColumn('products', 'free_service_duration_unit')) {
                $table->enum('free_service_duration_unit', ['day', 'month', 'year'])->nullable()->after('free_service_duration_value');
            }

            if (! Schema::hasColumn('products', 'free_service_terms')) {
                $table->text('free_service_terms')->nullable()->after('free_service_duration_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('products', 'free_service_terms')) {
                $columns[] = 'free_service_terms';
            }

            if (Schema::hasColumn('products', 'free_service_duration_unit')) {
                $columns[] = 'free_service_duration_unit';
            }

            if (Schema::hasColumn('products', 'free_service_duration_value')) {
                $columns[] = 'free_service_duration_value';
            }

            if (Schema::hasColumn('products', 'has_free_service')) {
                $columns[] = 'has_free_service';
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
