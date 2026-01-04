<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSnapshot extends Model
{
    //
    protected $fillable = [
        'account_id',
        'balance',
        'equity',
        'margin',
        'free_margin',
        'drawdown',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
