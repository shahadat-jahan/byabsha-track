<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_warranties', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_warranties', 'source_type')) {
                $table->enum('source_type', ['manual', 'auto_free_service'])
                    ->default('manual')
                    ->after('warranty_code');
            }

            if (!Schema::hasColumn('sale_warranties', 'duration_snapshot_value')) {
                $table->unsignedInteger('duration_snapshot_value')->nullable()->after('source_type');
            }

            if (!Schema::hasColumn('sale_warranties', 'duration_snapshot_unit')) {
                $table->enum('duration_snapshot_unit', ['day', 'month', 'year'])->nullable()->after('duration_snapshot_value');
            }

            if (!Schema::hasColumn('sale_warranties', 'coverage_quantity')) {
                $table->unsignedInteger('coverage_quantity')->default(1)->after('duration_snapshot_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_warranties', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('sale_warranties', 'coverage_quantity')) {
                $columns[] = 'coverage_quantity';
            }

            if (Schema::hasColumn('sale_warranties', 'duration_snapshot_unit')) {
                $columns[] = 'duration_snapshot_unit';
            }

            if (Schema::hasColumn('sale_warranties', 'duration_snapshot_value')) {
                $columns[] = 'duration_snapshot_value';
            }

            if (Schema::hasColumn('sale_warranties', 'source_type')) {
                $columns[] = 'source_type';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
