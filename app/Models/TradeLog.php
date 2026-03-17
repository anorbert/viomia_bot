<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeLog extends Model
{
    protected $fillable = [
        'account_id', 'ticket', 'symbol', 'type', 'lots', 'sl', 'tp',
        'open_price', 'close_price', 'profit','closed_lots', 'status', 'close_reason'
    ];

    /**
     * Relationship: TradeLog belongs to Account
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Prevent creating TradeLog records without required fields
        static::creating(function ($model) {
            if (empty($model->symbol)) {
                throw new \InvalidArgumentException('TradeLog symbol is required and cannot be empty');
            }
            if (empty($model->ticket)) {
                throw new \InvalidArgumentException('TradeLog ticket is required and cannot be empty');
            }
        });

        // Prevent updating to remove symbol
        static::updating(function ($model) {
            if (empty($model->symbol)) {
                throw new \InvalidArgumentException('TradeLog symbol cannot be removed or set to empty');
            }
        });
    }
}
