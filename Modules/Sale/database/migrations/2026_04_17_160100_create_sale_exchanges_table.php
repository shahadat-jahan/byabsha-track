<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('original_batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->foreignId('replacement_batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->date('exchange_date');
            $table->enum('exchange_type', ['replacement', 'return_only'])->default('replacement');
            $table->string('reason', 50)->default('defective');
            $table->text('note')->nullable();
            $table->decimal('cost_difference', 14, 2)->default(0);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['shop_id', 'exchange_date']);
            $table->index(['sale_id', 'status']);
            $table->index('reason');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_exchanges');
    }
};
