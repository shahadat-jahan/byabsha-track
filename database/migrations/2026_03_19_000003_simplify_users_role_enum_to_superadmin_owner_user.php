<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Ensure all legacy and target values are allowed during transition.
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','owner','user','manager','salesman','employee') NOT NULL DEFAULT 'owner'");

        // Normalize legacy roles before shrinking enum.
        DB::statement("UPDATE users SET role = 'user' WHERE role IN ('manager', 'salesman', 'employee')");
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','owner','user') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Rollback keeps simplified users as manager to fit old enum.
        DB::statement("UPDATE users SET role = 'manager' WHERE role = 'user'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','manager','owner','salesman') NOT NULL DEFAULT 'owner'");
    }
};
