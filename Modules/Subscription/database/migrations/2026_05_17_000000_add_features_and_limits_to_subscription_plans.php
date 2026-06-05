<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'features')) {
                $table->json('features')->nullable()->after('description');
            }
            if (!Schema::hasColumn('subscription_plans', 'limits')) {
                $table->json('limits')->nullable()->after('features');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'limits')) {
                $table->dropColumn('limits');
            }
            if (Schema::hasColumn('subscription_plans', 'features')) {
                $table->dropColumn('features');
            }
        });
    }
};
