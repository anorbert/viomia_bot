<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaCandleLog extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'symbol',
        'price',
        'rsi',
        'atr',
        'trend',
        'resistance',
        'support',
        'session',
        'account_id',
        'candles_json',
        'received_at',
    ];
}
