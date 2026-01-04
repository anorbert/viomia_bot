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
}
