<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotSetting extends Model
{
    //
    protected $table = 'bot_settings';

    protected $fillable = [
        'bot_enabled',
        'signal_check_interval',
        'max_spread_points',
        'risk_per_trade',
        'max_trades_per_day',
        'use_news_filter',
        'block_before_news_minutes',
        'block_after_news_minutes',
        'filter_currencies',
        'debug_mode',
    ];

    protected $casts = [
        'bot_enabled'      => 'boolean',
        'use_news_filter'  => 'boolean',
        'debug_mode'       => 'boolean',
        'risk_per_trade'   => 'decimal:2',
    ];
    

}
