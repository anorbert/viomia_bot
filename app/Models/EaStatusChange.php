<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EaStatusChange extends Model
{
    protected $fillable = [
        'account_id',
        'status',
        'reason',
        'consecutive_losses',
        'balance',
        'equity',
        'positions_open',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
