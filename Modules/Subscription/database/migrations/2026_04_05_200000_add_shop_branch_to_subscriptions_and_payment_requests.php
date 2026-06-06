<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('user_id')->constrained('shops')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('shop_id')->constrained('branches')->nullOnDelete();
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('user_id')->constrained('shops')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('shop_id')->constrained('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['shop_id', 'branch_id']);
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['shop_id', 'branch_id']);
        });
    }
};
