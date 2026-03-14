<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaSignalPattern extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'symbol',
        'pattern_type',
        'confidence',
        'detected_at',
    ];
}
