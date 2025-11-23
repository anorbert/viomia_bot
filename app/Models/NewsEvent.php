<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsEvent extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'currency', 'event_name', 'event_time', 'impact',
        'raw', 'previous', 'forecast', 'actual',
        'notified', 'status'
    ];

}
