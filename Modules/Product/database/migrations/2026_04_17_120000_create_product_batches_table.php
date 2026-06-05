<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->string('batch_code')->nullable()->unique();
            $table->string('source_type', 40)->default('manual');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->decimal('purchase_price', 12, 2);
            $table->unsignedInteger('initial_quantity');
            $table->unsignedInteger('remaining_quantity');
            $table->date('batch_date');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'batch_date']);
            $table->index(['shop_id', 'batch_date']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
