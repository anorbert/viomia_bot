<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Added
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use SoftDeletes; // Maintain consistency with User and Account

    protected $fillable = [
        'name',
        'slug',
        'currency',
        'price',
        'billing_interval',
        'duration_days',
        'description',
        'features',
        'is_active',
        'sort_order',
        'profit_share'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
        'profit_share' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Get all user subscriptions for this plan
     */
    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'subscription_plan_id');
    }

    /**
     * Senior Dev Helper: Formatted price string
     * Usage: $plan->formatted_price
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }
}