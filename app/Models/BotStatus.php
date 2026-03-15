<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotStatus extends Model
{
    //
    protected $fillable = [
        'balance', 'equity', 'daily_pl', 'open_positions', 'max_dd'
    ];

    /**
     * Get the account associated with this bot status.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
