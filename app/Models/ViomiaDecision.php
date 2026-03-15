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
        'web_intel',
        'web_sentiment',
        'web_score_adj',
        'account_id',
        'decided_at',
    ];

    protected $casts = [
        'web_intel' => 'json',
        'decided_at' => 'datetime',
    ];
}
