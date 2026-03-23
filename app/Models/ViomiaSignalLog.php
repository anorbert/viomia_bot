<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaSignalLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'decision',
        'entry',
        'stop_loss',
        'take_profit',
        'confidence',
        'score',
        'account_id',
        'push_status',
        'laravel_resp',
        'pushed_at',
    ];

    protected $casts = [
        'pushed_at' => 'datetime',
        'entry' => 'decimal:5',
        'stop_loss' => 'decimal:5',
        'take_profit' => 'decimal:5',
        'confidence' => 'decimal:4',
    ];
}
