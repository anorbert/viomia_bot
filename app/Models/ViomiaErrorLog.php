<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaErrorLog extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'error_message',
        'stack_trace',
        'occurred_at',
    ];
}
