<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalAccount extends Model
{
    //
    protected $fillable = [
        'signal_id',
        'account_id',
        'status',
        'ticket',
    ];
}
