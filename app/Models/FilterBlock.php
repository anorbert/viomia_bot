<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterBlock extends Model
{
    protected $fillable = [
        'account_id',
        'filter_type',
        'block_reason',
        'blocked_at',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
