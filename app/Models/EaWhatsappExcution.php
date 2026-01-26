<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EaWhatsappExcution extends Model
{
    //
    protected $fillable = [
        'whatsapp_signal_id',
        'account_id',
        'status',
        'executed_at',
    ];

    protected $casts = [
    'take_profit' => 'array',
    'received_at' => 'datetime',
    ];

    public function signal()
    {
        return $this->belongsTo(WhatsappSignal::class, 'whatsapp_signal_id');
    }

    
}
