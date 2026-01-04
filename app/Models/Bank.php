<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    protected $fillable = [
        'name',
        'branch',
        'account_number',
        'account_name',
        'iban',
        'swift_code',
        'currency',
        'active',
    ];
}
