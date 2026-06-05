<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_dynamic_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_dynamic_field_id')->constrained('product_dynamic_fields')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'product_dynamic_field_id'], 'pdv_product_field_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_dynamic_values');
    }
};
