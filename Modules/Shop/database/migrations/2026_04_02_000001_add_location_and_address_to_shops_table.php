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
        Schema::table('shops', function (Blueprint $table) {
            if (! Schema::hasColumn('shops', 'location')) {
                $table->string('location')->nullable()->after('name');
            }

            if (! Schema::hasColumn('shops', 'address')) {
                $table->text('address')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'location')) {
                $table->dropColumn('location');
            }

            if (Schema::hasColumn('shops', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
