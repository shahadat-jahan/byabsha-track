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

        // Ensure current values are allowed, then normalize and shrink enum.
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','owner','user') NOT NULL DEFAULT 'owner'");
        DB::statement("UPDATE users SET role = 'owner' WHERE role = 'user'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','owner') NOT NULL DEFAULT 'owner'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','owner','user') NOT NULL DEFAULT 'owner'");
    }
};
