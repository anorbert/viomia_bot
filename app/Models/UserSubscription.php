<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Added
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use SoftDeletes; // Added for trash management

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'ends_at',
        'auto_renew',
        'reference',
        'amount'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'auto_renew'=> 'boolean',
        'amount'    => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Get the plan associated with this subscription
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Relationship: Get the user who owns this subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Check if the subscription is currently valid
     * Usage: if($subscription->is_valid)
     */
    public function getIsValidAttribute(): bool
    {
        return $this->status === 'active' && 
               $this->ends_at->isFuture();
    }

    /**
     * Helper: Get days remaining until expiry
     */
    public function getDaysLeftAttribute(): int
    {
        if ($this->ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->ends_at);
    }
}