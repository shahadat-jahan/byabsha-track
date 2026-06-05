<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly, lifetime
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('max_shops')->nullable();
            $table->unsignedSmallInteger('max_branches')->nullable();
            $table->unsignedSmallInteger('max_brands')->nullable();
            $table->unsignedSmallInteger('max_categories')->nullable();
            $table->unsignedInteger('max_sales')->nullable();

            $table->boolean('has_capital')->default(false);
            $table->boolean('has_restock')->default(false);
            $table->boolean('has_reports')->default(false);
            $table->boolean('has_priority_support')->default(false);

            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
