<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaTradeExecution extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'ticket',
        'account_id',
        'symbol',
        'profit',
        'result',
        'recorded_at',
    ];
}
