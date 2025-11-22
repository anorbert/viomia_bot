<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotStatus extends Model
{
    //
    protected $fillable = [
        'balance', 'equity', 'daily_pl', 'open_positions', 'max_dd'
    ];
}
