<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->string('reference_no')->nullable()->unique();
            $table->date('damage_date');
            $table->unsignedInteger('total_quantity')->default(0);
            $table->decimal('total_loss', 14, 2)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['shop_id', 'damage_date']);
            $table->index('damage_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damages');
    }
};
