<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalSignal extends Model
{
    protected $fillable = [
        'account_id',
        'trend_score',
        'choch_signal',
        'rsi_value',
        'atr_value',
        'ema_20',
        'ema_50',
        'signal_description',
        'captured_at',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
