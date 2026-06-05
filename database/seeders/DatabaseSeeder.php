<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Shop\Database\Seeders\ShopSeeder;
// use Modules\Category\Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdminEmail = env('SUPERADMIN_EMAIL', 'superadmin@byabsha.local');
        $superAdminPassword = env('SUPERADMIN_PASSWORD');

        if (empty($superAdminPassword)) {
            $superAdminPassword = Str::random(20);

            if ($this->command) {
                $this->command->warn('SUPERADMIN_PASSWORD was not set. A random password was generated for the seeded superadmin.');
                $this->command->line('Superadmin Email: ' . $superAdminEmail);
                $this->command->line('Superadmin Password: ' . $superAdminPassword);
            }
        }

        User::updateOrCreate(
            ['email' => $superAdminEmail],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($superAdminPassword),
                'role' => 'superadmin',
            ]
        );

        $this->call([
            ShopSeeder::class,
        ]);
    }
}
