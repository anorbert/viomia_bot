<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EaBot extends Model
{
    //
    protected $fillable = [
        'name',
        'version',
        'address',
        'description',
        'status',
    ];
}
