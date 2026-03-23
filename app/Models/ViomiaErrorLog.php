<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaErrorLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'error_type',
        'account_id',
        'error_message',
        'context',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];
}
