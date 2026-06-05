<?php

namespace Modules\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Shop\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            ['name' => 'Malaysia Electronics'],
            ['name' => 'Japan Electronics'],
            ['name' => 'Mousumi Electronics'],
        ];

        $owner = \App\Models\User::where('role', 'owner')->first();
        $userId = $owner?->id;

        foreach ($shops as $shop) {
            if ($userId) {
                $shop['user_id'] = $userId;
            }
            Shop::create($shop);
        }
    }
}
