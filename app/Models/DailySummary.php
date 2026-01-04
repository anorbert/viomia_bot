<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    protected $fillable = [
        'account_id',
        'summary_date',
        'daily_pl',
        'trades_count',
        'winning_trades',
        'losing_trades',
        'win_rate_percent',
        'balance',
        'equity',
        'captured_at',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'captured_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
