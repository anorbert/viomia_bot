@extends('layouts.admin')

@section('title', 'Trade Statistics — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,187,156,0.15) !important; }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; }
.vi-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; transition: all 0.2s; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 600 !important; font-size: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-success { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-warning { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-error { background-color: rgba(239,68,68,0.13) !important; color: #ef4444 !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
.metric-item { background-color: #1a2235; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 14px; }
.metric-label { font-size: 10px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.metric-value { font-size: 20px; font-weight: 900; }
.chart-placeholder { background-color: #1a2235; border: 1px dashed rgba(26,187,156,0.3); border-radius: 8px; padding: 40px 20px; text-align: center; color: #4b5563; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📈 Analytics</div>
        <div class="vi-header-title">Trade Statistics</div>
        <div class="vi-header-sub">Comprehensive trading performance analysis</div>
    </div>
    <a href="{{ route('admin.trades.index') }}" class="vi-btn vi-btn-primary" style="margin-left:auto;">
        <i class="fa fa-arrow-left"></i> Back to Trades
    </a>
</div>

<!-- KPI Statistics Cards -->
<div class="vi-stats-grid">
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-exchange" style="color:#1ABB9C;"></i> Total Trades</div>
        <div class="vi-stat-value" style="color: #1ABB9C;">{{ $totalTrades ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-check" style="color:#22C55E;"></i> Winning Trades</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $winningTrades ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-times" style="color:#ef4444;"></i> Losing Trades</div>
        <div class="vi-stat-value" style="color: #ef4444;">{{ $losingTrades ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-percent" style="color:#FB923C;"></i> Win Rate</div>
        <div class="vi-stat-value" style="color: #FB923C;">{{ number_format(($winRate ?? 0), 1) }}%</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-dollar" style="color:#1ABB9C;"></i> Total Profit</div>
        <div class="vi-stat-value" style="color: {{ ($totalProfit ?? 0) >= 0 ? '#22C55E' : '#ef4444' }};">{{ number_format($totalProfit ?? 0, 2) }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-cubes" style="color:#A78BFA;"></i> Total Lots</div>
        <div class="vi-stat-value" style="color: #A78BFA;">{{ number_format($totalLots ?? 0, 2) }}</div>
    </div>
</div>

<!-- Average Metrics -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-bar-chart" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Average Metrics</div>
    </div>
    <div style="padding: 18px;">
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="metric-label"><i class="fa fa-calculator"></i> Avg Profit/Trade</div>
                <div class="metric-value" style="color: {{ ($avgProfit ?? 0) >= 0 ? '#22C55E' : '#ef4444' }};">{{ number_format($avgProfit ?? 0, 2) }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label"><i class="fa fa-arrow-up"></i> Avg Win</div>
                <div class="metric-value" style="color: #22C55E;">{{ number_format($avgWin ?? 0, 2) }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label"><i class="fa fa-arrow-down"></i> Avg Loss</div>
                <div class="metric-value" style="color: #ef4444;">{{ number_format($avgLoss ?? 0, 2) }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label"><i class="fa fa-superscript"></i> Profit Factor</div>
                <div class="metric-value" style="color: #FB923C;">{{ number_format($profitFactor ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Profit by Type Section -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-pie-chart" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Profit by Trade Type</div>
    </div>
    <div style="padding: 24px;">
        <div class="chart-placeholder">
            <i class="fa fa-line-chart" style="font-size: 32px; margin-bottom: 12px;"></i>
            <p>Pie Chart - Buy vs Sell Profit Comparison</p>
            <div style="margin-top: 10px; font-size: 11px; color: #4b5563;">Chart rendering area</div>
        </div>
    </div>
</div>

<!-- Win/Loss Distribution -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-bar-chart" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Win/Loss Distribution</div>
    </div>
    <div style="padding: 24px;">
        <div class="chart-placeholder">
            <i class="fa fa-bar-chart" style="font-size: 32px; margin-bottom: 12px;"></i>
            <p>Bar Chart - Win vs Loss Analysis</p>
            <div style="margin-top: 10px; font-size: 11px; color: #4b5563;">Chart rendering area</div>
        </div>
    </div>
</div>

<!-- Daily Profit Chart -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-line-chart" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Daily Profit (Last 30 Days)</div>
    </div>
    <div style="padding: 24px;">
        <div class="chart-placeholder">
            <i class="fa fa-area-chart" style="font-size: 32px; margin-bottom: 12px;"></i>
            <p>Line Chart - Daily Profit Trend</p>
            <div style="margin-top: 10px; font-size: 11px; color: #4b5563;">Chart rendering area</div>
        </div>
    </div>
</div>

<!-- Profit by Symbol Table -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-table" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Profit by Symbol</div>
    </div>

    <div style="overflow-x:auto;">
        <table class="vi-table">
            <thead>
                <tr>
                    <th style="width:18%;">Symbol</th>
                    <th style="width:15%;">Trade Count</th>
                    <th style="width:15%;">Winning</th>
                    <th style="width:15%;">Profit</th>
                    <th style="width:15%;">Win Rate</th>
                    <th style="text-align:right; width:22%;">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($symbolStats as $stat)
                <tr>
                    <td class="td-sym">{{ $stat->symbol ?? 'N/A' }}</td>
                    <td>
                        <span class="vi-badge vi-badge-success">{{ $stat->trade_count ?? 0 }}</span>
                    </td>
                    <td style="color: #22C55E; font-weight: 600;">{{ $stat->winning ?? 0 }}</td>
                    <td style="color: {{ ($stat->total_profit ?? 0) >= 0 ? '#22C55E' : '#ef4444' }}; font-weight: 600;">
                        {{ number_format($stat->total_profit ?? 0, 2) }}
                    </td>
                    <td>
                        <span class="vi-badge vi-badge-warning">
                            {{ number_format(($stat->win_rate ?? 0), 1) }}%
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <span style="color: #94a3b8; font-size: 11px;">
                            <i class="fa fa-info-circle"></i> View
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center;">
                        <div style="color: #4b5563;">
                            <i class="fa fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            <p>No symbol statistics available</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
