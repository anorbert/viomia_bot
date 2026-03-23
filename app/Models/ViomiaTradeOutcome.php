<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaTradeOutcome extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket',
        'account_id',
        'symbol',
        'decision',
        'entry',
        'sl',
        'tp',
        'close_price',
        'profit',
        'close_reason',
        'duration_mins',
        'result',
        'rsi',
        'atr',
        'trend',
        'session',
        'bos',
        'liquidity_sweep',
        'equal_highs',
        'equal_lows',
        'volume_spike',
        'dxy_trend',
        'risk_off',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'bos' => 'boolean',
        'liquidity_sweep' => 'boolean',
        'equal_highs' => 'boolean',
        'equal_lows' => 'boolean',
        'volume_spike' => 'boolean',
        'entry' => 'decimal:5',
        'sl' => 'decimal:5',
        'tp' => 'decimal:5',
        'close_price' => 'decimal:5',
        'profit' => 'decimal:4',
        'rsi' => 'decimal:4',
        'atr' => 'decimal:5',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
