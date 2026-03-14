<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViomiaModelVersion extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'version',
        'description',
        'deployed_at',
    ];
}
