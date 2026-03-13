<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'week_start',
        'week_end',
        'weekly_profit',
        'percentage',
        'amount',
        'status',
        'payment_method',
        'reference',
        'paid_at',
        'notes',
        'momo_phone',
        'account_name',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'weekly_profit' => 'decimal:2',
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns this payment record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription this payment is for
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Mark as paid
     */
    public function markAsPaid($paymentMethod, $reference = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'reference' => $reference,
            'paid_at' => now(),
        ]);
    }
}
