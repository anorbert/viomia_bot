<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'account_id',
        'error_type',
        'error_message',
        'price_at_error',
        'balance',
        'equity',
        'error_at',
    ];

    protected $casts = [
        'error_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
