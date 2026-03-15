@extends('layouts.admin')

@section('title', 'Trading Signals — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; transition: all 0.2s; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 600 !important; font-size: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-buy { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-sell { background-color: rgba(239,68,68,0.13) !important; color: #ef4444 !important; }
.vi-badge-active { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-inactive { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.13) !important; color: #ef4444 !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.2) !important; }
.vi-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; }
.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #4b5563; opacity: 0.5; display: block; margin-bottom: 16px; }
.empty-state p { color: #94a3b8; font-size: 14px; margin-bottom: 20px; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 6px; }
.status-dot.active { background: #22C55E; box-shadow: 0 0 8px rgba(34,197,94,0.5); }
.status-dot.inactive { background: #9CA3AF; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📡 Trading Signals</div>
        <div class="vi-header-title">Active Signals</div>
        <div class="vi-header-sub">Monitor and manage all trading signals</div>
    </div>
    <a href="{{ route('admin.signals.create') }}" class="vi-btn vi-btn-primary" style="margin-left:auto;">
        <i class="fa fa-plus-circle"></i> Create Signal
    </a>
</div>

<!-- Statistics Cards -->
<div class="vi-stats-grid">
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-signal" style="color:#1ABB9C;"></i> Total Signals</div>
        <div class="vi-stat-value" style="color: #1ABB9C;">{{ $totalSignals ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-calendar" style="color:#FB923C;"></i> Generated Today</div>
        <div class="vi-stat-value" style="color: #FB923C;">{{ $todaySignals ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-arrow-up" style="color:#22C55E;"></i> Buy Signals</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $buySignals ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-arrow-down" style="color:#ef4444;"></i> Sell Signals</div>
        <div class="vi-stat-value" style="color: #ef4444;">{{ $sellSignals ?? 0 }}</div>
    </div>
</div>

<!-- Signals Table -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Signal List</div>
    </div>

    <div style="overflow-x:auto;">
        <table class="vi-table">
            <thead>
                <tr>
                    <th style="width:12%;">Symbol</th>
                    <th style="width:10%;">Type</th>
                    <th style="width:12%;">Strength</th>
                    <th style="width:15%;">Price</th>
                    <th style="width:15%;">Timestamp</th>
                    <th style="width:12%;">Status</th>
                    <th style="text-align:right; width:14%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($signals as $signal)
                <tr>
                    <td class="td-sym">{{ strtoupper($signal->symbol ?? 'N/A') }}</td>
                    <td>
                        @if($signal->direction === 'buy')
                            <span class="vi-badge vi-badge-buy">
                                <i class="fa fa-arrow-up"></i> BUY
                            </span>
                        @elseif($signal->direction === 'sell')
                            <span class="vi-badge vi-badge-sell">
                                <i class="fa fa-arrow-down"></i> SELL
                            </span>
                        @else
                            <span class="vi-badge vi-badge-buy">{{ ucfirst($signal->direction ?? 'N/A') }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $strength = $signal->strength ?? ($signal->confidence ?? 'N/A');
                            if (is_numeric($strength)) {
                                $percentage = (float)$strength;
                                $barColor = $percentage >= 75 ? '#22C55E' : ($percentage >= 50 ? '#FB923C' : '#ef4444');
                                echo '<div style="display:flex; align-items:center; gap:6px;">';
                                echo '<div style="width:40px; height:6px; background:#1a2235; border-radius:3px; overflow:hidden;">';
                                echo '<div style="height:100%; width:' . min($percentage, 100) . '%; background:' . $barColor . ';"></div>';
                                echo '</div>';
                                echo '<span style="color:' . $barColor . '; font-weight:600; font-size:11px;">' . round($percentage) . '%</span>';
                                echo '</div>';
                            }
                        @endphp
                    </td>
                    <td style="color: #f1f5f9; font-weight: 600; font-family: 'Courier New', monospace;">
                        {{ $signal->entry ?? $signal->price ? '$' . number_format($signal->entry ?? $signal->price, 4) : 'N/A' }}
                    </td>
                    <td style="color: #94a3b8; font-size: 11px;">
                        {{ $signal->created_at ? $signal->created_at->format('M d, H:i') : 'N/A' }}
                    </td>
                    <td>
                        @if($signal->active ?? true)
                            <span class="vi-badge vi-badge-active">
                                <span class="status-dot active"></span> ACTIVE
                            </span>
                        @else
                            <span class="vi-badge vi-badge-inactive">
                                <span class="status-dot inactive"></span> INACTIVE
                            </span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            @if(Route::has('admin.signals.edit'))
                                <a href="{{ route('admin.signals.edit', $signal) }}" class="vi-btn vi-btn-secondary" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            @endif
                            @if(Route::has('admin.signals.destroy'))
                                <form action="{{ route('admin.signals.destroy', $signal) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="vi-btn vi-btn-danger" title="Delete" onclick="return confirm('Delete this signal?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <i class="fa fa-inbox"></i>
                            <p>No signals found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($signals->hasPages())
<div style="display: flex; justify-content: center; margin-top: 20px;">
    {{ $signals->links() }}
</div>
@endif

@endsection