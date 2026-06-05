<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_id')->constrained('damages')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('purchase_price_per_unit', 12, 2);
            $table->decimal('total_loss', 14, 2);
            $table->string('reason', 50)->default('damaged');
            $table->text('reason_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['damage_id', 'product_id']);
            $table->index('reason');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_items');
    }
};
