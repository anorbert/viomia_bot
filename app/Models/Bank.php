<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    protected $fillable = [
        'payment_owner', 'appId', 'secret', 'charges',
        'phone_number', 'logo', 'balance', 'status', 'deactivated_at'
    ];
}
