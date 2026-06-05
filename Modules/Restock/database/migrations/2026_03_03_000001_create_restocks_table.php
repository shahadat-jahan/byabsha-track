<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('purchase_price_per_unit', 12, 2);
            $table->decimal('total_cost', 14, 2);
            $table->date('restock_date');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['shop_id', 'restock_date']);
            $table->index('restock_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restocks');
    }
};