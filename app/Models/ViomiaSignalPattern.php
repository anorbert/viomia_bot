<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaSignalPattern extends Model
{
    use HasFactory;
    protected $fillable = [
        'pattern_name',
        'with_bos',
        'with_equal_levels',
        'web_sentiment',
        'market_regime',
        'decision',
        'result',
        'profit',
    ];

    protected $casts = [
        'with_bos' => 'boolean',
        'with_equal_levels' => 'boolean',
        'profit' => 'decimal:2',
    ];
}
