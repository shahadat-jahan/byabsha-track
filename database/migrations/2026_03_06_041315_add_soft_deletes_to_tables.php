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
        // Add soft deletes to shops
        Schema::table('shops', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to products
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to sales
        Schema::table('sales', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to restocks
        Schema::table('restocks', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to shop_capitals
        Schema::table('shop_capitals', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('restocks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('shop_capitals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
