<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeEntryContext extends Model
{
    protected $table = 'trade_entry_context';

    protected $fillable = [
        'account_id',
        'ticket',
        'symbol',
        'direction',
        'entry_price',
        'entry_time',
        
        // Technical indicators
        'entry_rsi',
        'entry_atr',
        'entry_rsi_level',
        
        // Trend
        'entry_trend',
        'trend_strength',
        
        // Patterns
        'entry_pattern_type',
        'pattern_quality',
        
        // Context
        'entry_atr_multiplier',
        'entry_spread',
        'entry_bid',
        'entry_ask',
        
        // Macro context
        'dxy_trend',
        'dxy_level',
        'risk_off',
        
        // Account state
        'account_balance_at_entry',
        'account_equity_at_entry',
        'margin_used_percent',
        
        // Signal reference
        'signal_id',
        'signal_correlation_id',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'entry_rsi' => 'float',
        'entry_atr' => 'float',
        'trend_strength' => 'float',
        'pattern_quality' => 'float',
        'entry_atr_multiplier' => 'float',
        'entry_spread' => 'float',
        'entry_bid' => 'float',
        'entry_ask' => 'float',
        'account_balance_at_entry' => 'float',
        'account_equity_at_entry' => 'float',
        'margin_used_percent' => 'float',
        'risk_off' => 'boolean',
    ];

    /**
     * Get related trade outcome
     */
    public function outcome()
    {
        return $this->hasOne(ViomiaTradeOutcome::class, 'ticket', 'ticket');
    }

    /**
     * Get related signal
     */
    public function signal()
    {
        return $this->belongsTo(Signal::class, 'signal_id');
    }

    /**
     * Scope: Get entries for a specific symbol
     */
    public function scopeSymbol($query, $symbol)
    {
        return $query->where('symbol', $symbol);
    }

    /**
     * Scope: Get entries within date range
     */
    public function scopeDateBetween($query, $from, $to)
    {
        return $query->whereBetween('entry_time', [$from, $to]);
    }

    /**
     * Scope: Get entries by pattern type
     */
    public function scopePattern($query, $pattern)
    {
        return $query->where('entry_pattern_type', $pattern);
    }

    /**
     * Get linked outcome data for analysis
     */
    public function getWithOutcome()
    {
        return [
            'entry_context' => $this,
            'outcome' => $this->outcome()->first(),
        ];
    }

    /**
     * Calculate entry-to-close statistics
     */
    public function calculatePnLStatistics()
    {
        $outcome = $this->outcome()->first();
        
        if (!$outcome) {
            return null;
        }

        return [
            'entry_price' => $this->entry_price,
            'entry_rsi' => $this->entry_rsi,
            'entry_pattern' => $this->entry_pattern_type,
            'entry_trend' => $this->entry_trend,
            'pnl' => $outcome->profit,
            'result' => $outcome->result,
            'win' => $outcome->result === 'WIN',
        ];
    }
}
