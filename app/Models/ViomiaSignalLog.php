<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaSignalLog extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'symbol',
        'decision',
        'entry',
        'push_status',
        'laravel_resp',
        'pushed_at',
    ];
}
