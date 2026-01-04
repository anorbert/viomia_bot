<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeEvent extends Model
{
    protected $fillable = [
        'account_id',
        'ticket',
        'direction',
        'entry_price',
        'sl_price',
        'tp_price',
        'lot_size',
        'signal_source',
        'opened_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
