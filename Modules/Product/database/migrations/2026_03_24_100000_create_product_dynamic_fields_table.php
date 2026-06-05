<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('label');
            $table->string('field_key');
            $table->string('input_type', 30)->default('text');
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['category_id', 'field_key'], 'pdf_category_field_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_dynamic_fields');
    }
};
