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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique('sales_invoice_number_unique');

            // Drop old columns
            $table->dropColumn([
                'invoice_number',
                'discount',
                'tax',
                'grand_total',
                'customer_name',
                'customer_phone',
                'payment_status',
                'notes',
            ]);
        });

        Schema::table('sales', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('product_id')->after('shop_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->after('product_id');
            $table->decimal('sale_price', 10, 2)->after('quantity');
            $table->decimal('profit', 10, 2)->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Remove new columns
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity', 'sale_price', 'profit']);
        });

        Schema::table('sales', function (Blueprint $table) {
            // Restore old columns
            $table->string('invoice_number')->unique();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('pending');
            $table->text('notes')->nullable();
        });
    }
};
