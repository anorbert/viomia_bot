<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappSignal extends Model
{
    //
    protected $fillable = [
        'source',
        'group_id',
        'sender',
        'symbol',
        'type',
        'entry',
        'stop_loss',
        'take_profit',
        'status',
        'raw_text',
        'received_at'
    ];

    protected $casts = [
        'take_profit' => 'array',
        'received_at' => 'datetime'
    ];

    public function executions()
    {
        return $this->hasMany(EaWhatsappExcution::class, 'whatsapp_signal_id');
    }

}
