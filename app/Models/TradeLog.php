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
}
