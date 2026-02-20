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

    public function signal()
    {
        return $this->belongsTo(Signal::class, 'signal_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
