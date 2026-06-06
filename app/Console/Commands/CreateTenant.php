<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Shop\Models\Shop;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionPlan;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create
                            {name : Business / organization name}
                            {--email= : Owner email address}
                            {--password= : Owner password (auto-generated if omitted)}
                            {--shop= : Shop name (defaults to the tenant name)}
                            {--plan= : Subscription plan slug (default: trial)}
                            {--days= : Trial duration in days (default: 30)}';

    protected $description = 'Create a new tenant with an owner account, shop, and trial subscription';

    public function handle(): int
    {
        $tenantName = trim($this->argument('name'));

        // ── Resolve & validate email ──────────────────────────────────────────
        $email = $this->option('email')
            ?? $this->ask('Owner email address');

        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required|email|unique:users,email']
        );

        if ($validator->fails()) {
            $this->error('Invalid or already-used email: '.$email);

            return self::FAILURE;
        }

        //  Password
        $password = $this->option('password') ?? Str::random(16);
        $autoPassword = ! $this->option('password');

        //  Shop name
        $shopName = $this->option('shop') ?? $tenantName;

        //  Subscription plan
        $planSlug = $this->option('plan') ?? 'trial';
        $trialDays = (int) ($this->option('days') ?? 30);

        $plan = SubscriptionPlan::where('slug', $planSlug)->first();

        if (! $plan) {
            // Fall back to any trial plan, then to free plan
            $plan = SubscriptionPlan::where('is_trial', true)->where('is_active', true)->first()
                ?? SubscriptionPlan::freePlan();

            if ($planSlug !== 'trial' && $planSlug !== 'free') {
                $this->warn("Plan \"{$planSlug}\" not found. Using \"{$plan->slug}\" instead.");
            }
        }

        // Summary before creation
        $this->newLine();
        $this->line('  <fg=white;options=bold>Tenant details</>');
        $this->line("  Oner Name: {$tenantName}");
        $this->line("  Email    : {$email}");
        $this->line("  Shop     : {$shopName}");
        $this->line("  Plan     : {$plan->name} ({$plan->slug})");
        if ($plan->is_trial || $plan->isFree()) {
            $this->line("  Trial    : {$trialDays} days");
        }
        $this->newLine();

        if (! $this->confirm('Proceed with creation?', true)) {
            $this->info('Aborted.');

            return self::SUCCESS;
        }

        // Create everything in a transaction
        try {
            $result = DB::transaction(function () use (
                $tenantName, $email, $password, $shopName, $plan, $trialDays
            ) {
                // 1. Owner user
                $owner = User::create([
                    'name' => $tenantName,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'owner',
                ]);

                // 2. Shop
                $shop = Shop::create([
                    'name' => $shopName,
                    'user_id' => $owner->id,
                ]);

                // 3. Subscription
                $endsAt = ($plan->is_trial || $plan->isFree())
                    ? now()->addDays($trialDays)
                    : null;

                $subscription = null;
                if ($plan->exists) {
                    $subscription = Subscription::create([
                        'user_id' => $owner->id,
                        'shop_id' => $shop->id,
                        'subscription_plan_id' => $plan->id,
                        'status' => 'active',
                        'starts_at' => now(),
                        'ends_at' => $endsAt,
                    ]);
                }

                return compact('owner', 'shop', 'subscription');
            });
        } catch (\Throwable $e) {
            $this->error('Creation failed: '.$e->getMessage());

            return self::FAILURE;
        }

        // Success output
        $owner = $result['owner'];
        $shop = $result['shop'];
        $sub = $result['subscription'];

        $this->newLine();
        $this->line('  <fg=green;options=bold>✓ Tenant created successfully</>');
        $this->newLine();

        $this->table(
            ['Field', 'Value'],
            [
                ['Owner ID',   $owner->id],
                ['Name',       $owner->name],
                ['Email',      $owner->email],
                ['Role',       $owner->role],
                ['Shop ID',    $shop->id],
                ['Shop Name',  $shop->name],
                ['Plan',       $plan->name.' ('.$plan->slug.')'],
                ['Sub Status', $sub?->status ?? 'none'],
                ['Expires',    $sub?->ends_at?->toDateString() ?? 'never'],
            ]
        );

        if ($autoPassword) {
            $this->newLine();
            $this->warn('  Auto-generated password (save this now — it will not be shown again):');
            $this->line("  <fg=yellow;options=bold>  {$password}  </>");
            $this->newLine();
        }

        return self::SUCCESS;
    }
}
