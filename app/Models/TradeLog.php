<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeLog extends Model
{
    //
    protected $fillable = [
        'ticket', 'symbol', 'type', 'lots', 'sl', 'tp',
        'open_price', 'close_price', 'profit'
    ];
}
