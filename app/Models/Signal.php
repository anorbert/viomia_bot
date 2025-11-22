<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    //
    protected $fillable = [
        'symbol', 'direction', 'entry', 'sl', 'tp', 'timeframe', 'active'
    ];
}
