@extends('layouts.admin')

@section('title', 'AI Performance - AI Analytics')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #1ABB9C !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
.vi-header-title { font-size: 18px !important; font-weight: 800 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-stat-card { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; display: flex; align-items: center; gap: 15px; }
.vi-stat-icon { font-size: 24px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
.vi-stat-content h4 { margin: 0; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.vi-stat-content .value { margin: 6px 0 0 0; font-size: 20px; color: #f1f5f9; font-weight: 800; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 13px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-panel-body { padding: 18px !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 800 !important; font-size: 13px; }
.vi-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
.vi-trade-detail { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 8px; padding: 14px; margin-bottom: 10px; display: flex; justify-content: space-between; }
.vi-trade-detail-label { font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; }
.vi-trade-detail-value { font-size: 13px; color: #f1f5f9; font-weight: 700; }
</style>
@endpush

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📈 Performance Analytics</div>
        <div class="vi-header-title">AI Performance Analytics</div>
        <div class="vi-header-sub">Detailed performance metrics and historical analysis</div>
      </div>
    </div>
  </div>
</div>

<!-- Time Period Filter -->
<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="{{ route('admin.ai.performance') }}" class="form-inline" style="gap:10px;">
            <label style="color:#94a3b8; font-size:12px; font-weight:700;">Period:</label>
            <select name="days" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;" onchange="this.form.submit()">
                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                <option value="15" {{ $days == 15 ? 'selected' : '' }}>Last 15 Days</option>
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                <option value="60" {{ $days == 60 ? 'selected' : '' }}>Last 60 Days</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 Days</option>
            </select>
        </form>
    </div>
</div>

<!-- Key Metrics -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(59,158,255,0.13); color:#3B9EFF;">
                <i class="fa fa-exchange" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Total Trades</h4>
                <div class="value">{{ number_format($totalTrades) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(34,197,94,0.13); color:#22C55E;">
                <i class="fa fa-percent" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Win Rate</h4>
                <div class="value">{{ $winRate }}%</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(245,158,11,0.13); color:#F59E0B;">
                <i class="fa fa-dollar" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Total Profit</h4>
                <div class="value" style="color: {{ ($totalProfit ?? 0) >= 0 ? '#22C55E' : '#EF4444' }};">{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(139,92,246,0.13); color:#A78BFA;">
                <i class="fa fa-line-chart" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Avg Profit/Trade</h4>
                <div class="value">{{ number_format($avgProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Win/Loss Chart -->
<div class="row">
    <div class="col-md-6">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-pie-chart" style="color:#22C55E; font-size:14px;"></i>
                <div class="vi-panel-title">Win/Loss Distribution</div>
            </div>
            <div class="vi-panel-body" style="padding:20px;">
                <canvas id="winLossChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-line-chart" style="color:#1ABB9C; font-size:14px;"></i>
                <div class="vi-panel-title">Daily Performance</div>
            </div>
            <div class="vi-panel-body" style="padding:20px;">
                <canvas id="dailyProfitChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Symbol Performance Table -->
<div class="row">
    <div class="col-md-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-list" style="color:#3B9EFF; font-size:14px;"></i>
                <div class="vi-panel-title">Symbol Performance</div>
            </div>
            <div class="vi-panel-body">
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Trades</th>
                                <th>Wins</th>
                                <th>Win Rate</th>
                                <th>Total Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($symbolPerformance as $symbol)
                                <tr>
                                    <td class="td-sym">{{ $symbol->symbol }}</td>
                                    <td>{{ $symbol->trades }}</td>
                                    <td>{{ $symbol->wins }}</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <div style="width:50px; background-color:#222d42; border-radius:4px; height:6px;">
                                                <div style="width:{{ min($symbol->win_rate, 100) }}%; background-color:#22C55E; height:100%; border-radius:4px;"></div>
                                            </div>
                                            <span style="font-size:11px;">{{ $symbol->win_rate }}%</span>
                                        </div>
                                    </td>
                                    <td style="font-weight:700; color: {{ $symbol->pnl >= 0 ? '#22C55E' : '#EF4444' }};">
                                        {{ number_format($symbol->pnl, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:20px;">No symbol performance data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Best and Worst Trades -->
<div class="row">
    <div class="col-md-6">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-trophy" style="color:#F59E0B; font-size:14px;"></i>
                <div class="vi-panel-title">Best Trade</div>
            </div>
            <div class="vi-panel-body">
                @if($bestTrade)
                    <div class="vi-trade-detail">
                        <div>
                            <div class="vi-trade-detail-label">Ticket</div>
                            <div class="vi-trade-detail-value" style="color:#3B9EFF;">{{ $bestTrade->ticket }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Symbol</div>
                            <div class="vi-trade-detail-value">{{ $bestTrade->symbol }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Profit</div>
                            <div class="vi-trade-detail-value" style="color:#22C55E;">{{ number_format($bestTrade->profit, 2) }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Date</div>
                            <div class="vi-trade-detail-value">{{ date('M d, h:i A', strtotime($bestTrade->recorded_at)) }}</div>
                        </div>
                    </div>
                @else
                    <p style="color:#94a3b8; text-align:center; padding:20px 0;">No trade data available</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-bomb" style="color:#EF4444; font-size:14px;"></i>
                <div class="vi-panel-title">Worst Trade</div>
            </div>
            <div class="vi-panel-body">
                @if($worstTrade)
                    <div class="vi-trade-detail">
                        <div>
                            <div class="vi-trade-detail-label">Ticket</div>
                            <div class="vi-trade-detail-value" style="color:#3B9EFF;">{{ $worstTrade->ticket }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Symbol</div>
                            <div class="vi-trade-detail-value">{{ $worstTrade->symbol }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Profit</div>
                            <div class="vi-trade-detail-value" style="color:#EF4444;">{{ number_format($worstTrade->profit, 2) }}</div>
                        </div>
                        <div>
                            <div class="vi-trade-detail-label">Date</div>
                            <div class="vi-trade-detail-value">{{ date('M d, h:i A', strtotime($worstTrade->recorded_at)) }}</div>
                        </div>
                    </div>
                @else
                    <p style="color:#94a3b8; text-align:center; padding:20px 0;">No trade data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Set Chart.js default colors
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255,255,255,0.07)';

    // Win/Loss Chart
    var ctx1 = document.getElementById('winLossChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Wins', 'Losses'],
            datasets: [{
                data: [{{ $winCount }}, {{ $lossCount }}],
                backgroundColor: ['#22C55E', '#EF4444'],
                borderColor: ['#16a34a', '#dc2626'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#94a3b8',
                        font: { size: 12, weight: '700' },
                        padding: 15
                    }
                }
            }
        }
    });

    // Daily Profit Chart
    var ctx2 = document.getElementById('dailyProfitChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! $dailyData !!},
            datasets: [
                {
                    label: 'Daily Profit',
                    data: {!! $dailyProfit !!},
                    borderColor: '#1ABB9C',
                    backgroundColor: 'rgba(26, 187, 156, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#1ABB9C',
                    pointBorderColor: '#1ABB9C',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                filler: {
                    propagate: false
                },
                legend: {
                    display: true,
                    labels: {
                        color: '#94a3b8',
                        font: { size: 12, weight: '700' },
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.07)'
                    }
                },
                x: {
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.07)'
                    }
                }
            }
        }
    });
</script>
@endsection
@endsection
