<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json
            $table->string('group')->default('general'); // general, business, system, appearance
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            // General Settings
            ['key' => 'app_name', 'value' => 'Byabsha Track', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_timezone', 'value' => 'Asia/Dhaka', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency', 'value' => 'BDT', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],

            // Business Settings
            ['key' => 'business_name', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_email', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_phone', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_address', 'value' => '', 'type' => 'text', 'group' => 'business', 'created_at' => now(), 'updated_at' => now()],

            // System Settings
            ['key' => 'low_stock_alert', 'value' => '10', 'type' => 'number', 'group' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
