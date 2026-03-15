<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaTradeExecution extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id',
        'ticket',
        'symbol',
        'decision',
        'ml_confidence',
        'signal_combo',
        'regime_type',
        'entry_price',
        'profit_loss',
        'result',
        'session_name',
    ];
}
