<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LossLimitAlert extends Model
{
    protected $fillable = [
        'account_id',
        'daily_loss',
        'daily_loss_limit',
        'limit_type',
        'balance',
        'equity',
        'alert_at',
    ];

    protected $casts = [
        'alert_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
