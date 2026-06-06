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
        DB::table('settings')->whereIn('key', [
            'business_tagline',
            'footer_text',
            'support_email',
            'support_phone',
            'company_address',
            'social_facebook',
            'social_twitter',
            'social_instagram',
            'social_linkedin',
        ])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-insert is not strictly necessary for simple rollback of cleanup
    }
};
