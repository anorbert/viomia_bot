<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Free Demo Plan
        SubscriptionPlan::create([
            'name' => 'Free Demo',
            'slug' => 'free-demo',
            'currency' => 'USD',
            'price' => 0,
            'billing_interval' => 'yearly',
            'duration_days' => 30,
            'description' => 'Demo trading account. Users can trade up to 30% profit. After reaching the limit they are asked to upgrade.',
            'features' => json_encode([
                'Demo trading',
                'Max 30% profit target',
                'Practice environment'
            ]),
            'profit_share' => 0,
            'max_accounts' => 1,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Entry Plan
        SubscriptionPlan::create([
            'name' => 'Entry Plan',
            'slug' => 'entry-plan',
            'currency' => 'USD',
            'price' => 200,
            'billing_interval' => 'yearly',
            'duration_days' => 365,
            'description' => 'Entry trading plan. User pays $200 entry fee and shares 30% of cumulative profit weekly.',
            'features' => json_encode([
                'Real trading account',
                'Weekly payout',
                '30% profit share'
            ]),
            'profit_share' => 30,
            'max_accounts' => 1,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Pro Plan
        SubscriptionPlan::create([
            'name' => 'Pro Plan',
            'slug' => 'pro-plan',
            'currency' => 'USD',
            'price' => 2000,
            'billing_interval' => 'yearly',
            'duration_days' => 365,
            'description' => 'Professional plan for experienced traders. Allows up to 2 trading accounts with no profit sharing.',
            'features' => json_encode([
                '2 trading accounts',
                'No profit share',
                'Priority support',
                'Advanced trading tools'
            ]),
            'profit_share' => 0,
            'max_accounts' => 2,
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}
