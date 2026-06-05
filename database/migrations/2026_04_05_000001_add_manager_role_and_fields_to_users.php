<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Expand role ENUM to include 'manager'
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'owner', 'manager') NOT NULL DEFAULT 'owner'");
        }

        Schema::table('users', function (Blueprint $table) {
            // NULL = not a manager (n/a); false = pending approval; true = approved
            $table->boolean('is_approved')->nullable()->after('module_access');

            // Manager's assigned shop and branch (set during approval)
            $table->unsignedBigInteger('shop_id')->nullable()->after('is_approved');
            $table->unsignedBigInteger('branch_id')->nullable()->after('shop_id');

            $table->foreign('shop_id')->references('id')->on('shops')->nullOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['is_approved', 'shop_id', 'branch_id']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'owner') NOT NULL DEFAULT 'owner'");
        }
    }
};
