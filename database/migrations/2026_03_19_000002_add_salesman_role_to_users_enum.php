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

        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','manager','owner','salesman') NOT NULL DEFAULT 'owner'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("UPDATE users SET role = 'manager' WHERE role = 'salesman'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','manager','owner') NOT NULL DEFAULT 'owner'");
    }
};
