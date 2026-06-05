<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing general configurations to system group
        DB::table('settings')->whereIn('key', [
            'app_timezone',
            'currency',
            'currency_symbol',
            'default_language'
        ])->update(['group' => 'system']);

        // Update app_name and business information to dashboard group
        DB::table('settings')->whereIn('key', [
            'app_name',
            'business_name',
            'business_email',
            'business_phone',
            'business_address'
        ])->update(['group' => 'dashboard']);

        // Insert new branding settings if not exists
        $newSettings = [
            ['key' => 'dashboard_logo', 'value' => '', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'dashboard_favicon', 'value' => '', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'dashboard_theme_color', 'value' => '#0f766e', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_tagline', 'value' => 'Track your business easily', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_text', 'value' => '© Byabsha Track. All rights reserved.', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com', 'type' => 'text', 'group' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            
            // Landing settings placeholders
            ['key' => 'landing_title', 'value' => 'Byabsha Track', 'type' => 'text', 'group' => 'landing', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'landing_hero_title', 'value' => 'Ultimate Solution to Track Your Business Assets & Sales', 'type' => 'text', 'group' => 'landing', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'landing_hero_subtitle', 'value' => 'Control your inventory, sales, warranties, and exchanges all from a single dashboard.', 'type' => 'text', 'group' => 'landing', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($newSettings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert group updates
        DB::table('settings')->whereIn('key', [
            'app_timezone',
            'currency',
            'currency_symbol',
            'default_language'
        ])->update(['group' => 'general']);

        DB::table('settings')->whereIn('key', [
            'app_name',
            'business_name',
            'business_email',
            'business_phone',
            'business_address'
        ])->update(['group' => 'business']);

        // Delete branding settings
        DB::table('settings')->whereIn('key', [
            'dashboard_logo',
            'dashboard_favicon',
            'dashboard_theme_color',
            'business_tagline',
            'footer_text',
            'social_facebook',
            'social_twitter',
            'social_instagram',
            'social_linkedin',
            'landing_title',
            'landing_hero_title',
            'landing_hero_subtitle'
        ])->delete();
    }
};
