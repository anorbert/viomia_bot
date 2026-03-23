<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaDecision extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'decision',
        'confidence',
        'score',
        'reasons',
        'entry',
        'stop_loss',
        'take_profit',
        'rr_ratio',
        'rsi',
        'atr',
        'trend',
        'session',
        'dxy_trend',
        'risk_off',
        'web_intel',
        'web_sentiment',
        'web_score_adj',
        'account_id',
        'decided_at',
    ];

    protected $casts = [
        'web_intel' => 'json',
        'decided_at' => 'datetime',
        'entry' => 'decimal:5',
        'stop_loss' => 'decimal:5',
        'take_profit' => 'decimal:5',
        'confidence' => 'decimal:4',
        'rr_ratio' => 'decimal:2',
        'rsi' => 'decimal:4',
        'atr' => 'decimal:5',
    ];
}
