<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('subscription_plans', 'duration_days')) {
                $table->unsignedInteger('duration_days')->default(30)->after('price');
            }
            if (! Schema::hasColumn('subscription_plans', 'status')) {
                $table->string('status')->default('active')->after('duration_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('subscription_plans', 'duration_days')) {
                $table->dropColumn('duration_days');
            }
        });
    }
};
