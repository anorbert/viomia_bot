@extends('layouts.admin')

@section('title', 'Trade #' . $trade->id . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; margin-bottom: 16px; }
.vi-panel-head { padding: 16px 20px; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; padding: 16px 20px; }
.vi-info-item { }
.vi-info-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 6px; }
.vi-info-value { font-size: 13px; font-weight: 600; color: #f1f5f9; }
.vi-section { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-open { background-color: rgba(59,158,255,0.13) !important; color: #3B9EFF !important; }
.vi-badge-closed { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-partial { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-buy { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-sell { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-btn { padding: 8px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
.stat-box { background-color: #1a2235; padding: 12px; border-radius: 6px; border-left: 3px solid #1ABB9C; }
.stat-label { font-size: 9px; color: #4b5563; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
.stat-value { font-size: 14px; font-weight: 800; color: #f1f5f9; }
.stat-value.profit { color: #22C55E; }
.stat-value.loss { color: #EF4444; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📊 Trade Details</div>
        <div class="vi-header-title">Trade #{{ $trade->id }}</div>
    </div>
    <div style="margin-left: auto;">
        <a href="{{ route('admin.trades.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-chevron-left"></i> Back to Trades
        </a>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 14px; font-weight: 800; color: #f1f5f9; margin-bottom: 4px;">{{ $trade->symbol ?? 'N/A' }}</div>
                <div style="font-size: 11px; color: #4b5563;">Opened {{ $trade->created_at->diffForHumans() }} • {{ $trade->created_at->format('M d, Y H:i A') }}</div>
            </div>
            <div style="display: flex; gap: 8px;">
                @if(strtoupper($trade->direction ?? '') === 'BUY')
                    <span class="vi-badge vi-badge-buy">🟢 BUY</span>
                @else
                    <span class="vi-badge vi-badge-sell">🔴 SELL</span>
                @endif
                @if($trade->status === 'open' || $trade->status === 'Open')
                    <span class="vi-badge vi-badge-open">OPEN</span>
                @elseif($trade->status === 'closed' || $trade->status === 'Closed')
                    <span class="vi-badge vi-badge-closed">CLOSED ✓</span>
                @else
                    <span class="vi-badge vi-badge-partial">PARTIAL</span>
                @endif
            </div>
        </div>
    </div>

    <div class="vi-section">
        <div class="vi-section-title">💰 Entry Information</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Entry Price</div>
                <div class="stat-value">${{ number_format($trade->entry_price ?? 0, 5) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Stop Loss</div>
                <div class="stat-value">${{ number_format($trade->stop_loss ?? 0, 5) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Take Profit</div>
                <div class="stat-value">${{ number_format($trade->take_profit ?? 0, 5) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Lot Size</div>
                <div class="stat-value">{{ number_format($trade->lot_size ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="vi-section">
        <div class="vi-section-title">📈 Current Status</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Current Price</div>
                <div class="stat-value">${{ number_format($trade->current_price ?? $trade->entry_price, 5) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Profit/Loss</div>
                @php
                    $pnl = ($trade->pnl ?? 0);
                    $pnlClass = $pnl >= 0 ? 'profit' : 'loss';
                @endphp
                <div class="stat-value {{ $pnlClass }}">{{ $pnl >= 0 ? '+' : '' }}${{ number_format($pnl, 2) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">P&L %</div>
                @php $pnlPercent = $trade->entry_price ? (($pnl / ($trade->entry_price * $trade->lot_size)) * 100) : 0; @endphp
                <div class="stat-value {{ $pnlPercent >= 0 ? 'profit' : 'loss' }}">{{ $pnlPercent >= 0 ? '+' : '' }}{{ number_format($pnlPercent, 2) }}%</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Pip Movement</div>
                @php $pips = (($trade->current_price ?? $trade->entry_price) - $trade->entry_price) * 10000; @endphp
                <div class="stat-value {{ $pips >= 0 ? 'profit' : 'loss' }}">{{ $pips >= 0 ? '+' : '' }}{{ number_format($pips, 1) }}</div>
            </div>
        </div>
    </div>

    @if($trade->status === 'closed' || $trade->status === 'Closed')
        <div class="vi-section">
            <div class="vi-section-title">✅ Closing Information</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Exit Price</div>
                    <div class="stat-value">${{ number_format($trade->exit_price ?? $trade->current_price, 5) }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Closed At</div>
                    <div class="stat-value">{{ $trade->closed_at ? \Carbon\Carbon::parse($trade->closed_at)->format('M d, H:i') : 'N/A' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Duration</div>
                    <div class="stat-value">
                        @php
                            $duration = $trade->closed_at 
                                ? \Carbon\Carbon::parse($trade->closed_at)->diff(\Carbon\Carbon::parse($trade->created_at))->format('%d d %h h')
                                : 'N/A';
                        @endphp
                        {{ $duration }}
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Final P&L</div>
                    <div class="stat-value {{ ($trade->pnl ?? 0) >= 0 ? 'profit' : 'loss' }}">{{ ($trade->pnl ?? 0) >= 0 ? '+' : '' }}${{ number_format($trade->pnl ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="vi-section">
        <div class="vi-section-title">ℹ️ Trade Information</div>
        <div class="vi-info-grid">
            <div class="vi-info-item">
                <div class="vi-info-label">Account</div>
                <div class="vi-info-value">{{ $trade->account->name ?? 'N/A' }}<br><span style="font-size:10px; color:#4b5563;">{{ $trade->account->account_number ?? 'N/A' }}</span></div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Platform</div>
                <div class="vi-info-value">{{ $trade->account->platform ?? 'N/A' }}</div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Broker</div>
                <div class="vi-info-value">{{ $trade->account->broker_server ?? 'N/A' }}</div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Trade Type</div>
                <div class="vi-info-value">{{ $trade->trade_type ?? 'Manual' }}</div>
            </div>
        </div>
    </div>

    @if($trade->comment || $trade->note)
        <div class="vi-section">
            <div class="vi-section-title">💬 Notes</div>
            <div style="background-color: #1a2235; padding: 12px; border-radius: 6px; color: #94a3b8; font-size: 12px; border-left: 3px solid #1ABB9C;">
                {{ $trade->comment ?? $trade->note ?? 'No notes' }}
            </div>
        </div>
    @endif
</div>

<div style="display: flex; gap: 10px; justify-content: flex-end;">
    <a href="{{ route('admin.trades.index') }}" class="vi-btn vi-btn-secondary">
        <i class="fa fa-list"></i> All Trades
    </a>
</div>

@endsection
