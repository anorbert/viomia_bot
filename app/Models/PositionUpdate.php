<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionUpdate extends Model
{
    protected $fillable = [
        'account_id',
        'ticket',
        'entry_price',
        'current_price',
        'unrealized_pl',
        'unrealized_pl_percent',
        'lot_size',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
