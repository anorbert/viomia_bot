<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Signal extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'ticket','symbol', 'direction', 'entry', 'sl', 'tp', 'timeframe', 'active'
    ];

    protected static function boot()
    {
        parent::boot();

        // Prevent creating Signal records without required fields
        static::creating(function ($model) {
            if (empty($model->symbol)) {
                throw new \InvalidArgumentException('Signal symbol is required and cannot be empty');
            }
            if (empty($model->ticket)) {
                throw new \InvalidArgumentException('Signal ticket is required and cannot be empty');
            }
        });

        // Prevent updating to remove symbol
        static::updating(function ($model) {
            if (empty($model->symbol)) {
                throw new \InvalidArgumentException('Signal symbol cannot be removed or set to empty');
            }
        });
    }
}
