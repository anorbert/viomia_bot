here we are
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">
                <i class="fa fa-chart-line text-primary"></i> Trading Statistics
            </h2>
            <p class="text-muted">Comprehensive trading performance analysis and metrics</p>
        </div>
    </div>

    {{-- Top KPI Cards --}}
    <div class="row mb-4">
        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fa fa-exchange"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ $totalTrades }}</h4>
                    <p class="stat-label">Total Trades</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fa fa-arrow-up"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ $winningTrades }}</h4>
                    <p class="stat-label">Winning Trades</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fa fa-arrow-down"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ $losingTrades }}</h4>
                    <p class="stat-label">Losing Trades</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fa fa-percent"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ $winRate }}%</h4>
                    <p class="stat-label">Win Rate</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <i class="fa fa-dollar"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ number_format($totalProfit, 2) }}</h4>
                    <p class="stat-label">Total Profit</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="stat-content">
                    <h4 class="stat-value">{{ number_format($totalLots, 2) }}</h4>
                    <p class="stat-label">Total Lots</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Metrics --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="margin-bottom: 0;">
                        <i class="fa fa-info-circle text-info"></i> Average Metrics
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="metric-row">
                        <span class="metric-label">Avg Profit/Trade</span>
                        <span class="metric-value" style="color: {{ $avgProfit >= 0 ? '#10b981' : '#ef4444' }};">
                            {{ number_format($avgProfit, 2) }}
                        </span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">Avg Win</span>
                        <span class="metric-value" style="color: #10b981;">
                            {{ number_format($avgWin, 2) }}
                        </span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">Avg Loss</span>
                        <span class="metric-value" style="color: #ef4444;">
                            {{ number_format($avgLoss, 2) }}
                        </span>
                    </div>
                    <div class="metric-row" style="border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px;">
                        <span class="metric-label">Profit Factor</span>
                        <span class="metric-value">
                            {{ $avgLoss != 0 ? number_format(abs($avgWin / $avgLoss), 2) : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profit by Type Chart --}}
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="margin-bottom: 0;">
                        <i class="fa fa-pie-chart text-success"></i> Profit by Type
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="profitByTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Trade Distribution --}}
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="margin-bottom: 0;">
                        <i class="fa fa-bar-chart text-warning"></i> Win/Loss Distribution
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="wlDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Profit Chart --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="margin-bottom: 0;">
                        <i class="fa fa-line-chart text-primary"></i> Daily Profit (Last 30 Days)
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="dailyProfitChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Profit by Symbol --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="margin-bottom: 0;">
                        <i class="fa fa-list text-info"></i> Profit by Symbol
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background: #f9fafb;">
                                <tr>
                                    <th style="color: #374151; font-weight: 600;">Symbol</th>
                                    <th style="color: #374151; font-weight: 600; text-align: right;">Trades</th>
                                    <th style="color: #374151; font-weight: 600; text-align: right;">Winning</th>
                                    <th style="color: #374151; font-weight: 600; text-align: right;">Profit</th>
                                    <th style="color: #374151; font-weight: 600; text-align: right;">Win Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($profitBySymbol as $symbol)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="color: #111827; font-weight: 600;">
                                            {{ $symbol['symbol'] }}
                                        </td>
                                        <td style="text-align: right; color: #555;">
                                            <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                                {{ $symbol['count'] }}
                                            </span>
                                        </td>
                                        <td style="text-align: right; color: #10b981; font-weight: 600;">
                                            {{ $symbol['wins'] }}
                                        </td>
                                        <td style="text-align: right; font-weight: 600; color: {{ $symbol['profit'] >= 0 ? '#10b981' : '#ef4444' }};">
                                            {{ number_format($symbol['profit'], 2) }}
                                        </td>
                                        <td style="text-align: right;">
                                            <span class="badge" style="background: {{ ($symbol['wins'] / $symbol['count'] * 100) >= 50 ? '#d1fae5' : '#fee2e2' }}; color: {{ ($symbol['wins'] / $symbol['count'] * 100) >= 50 ? '#065f46' : '#7f1d1d' }};">
                                                {{ round(($symbol['wins'] / $symbol['count'] * 100), 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #9ca3af; padding: 30px;">
                                            <i class="fa fa-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                            No trading data available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #e5e7eb;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-left-color: #3b82f6;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .stat-label {
        font-size: 12px;
        color: #9ca3af;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .metric-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .metric-row:last-child {
        border-bottom: none;
    }

    .metric-label {
        color: #6b7280;
        font-weight: 500;
        font-size: 13px;
    }

    .metric-value {
        font-weight: 700;
        font-size: 16px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .x_panel {
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .x_title {
        padding: 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .x_title h2 {
        margin: 0;
        color: #111827;
        font-size: 16px;
        font-weight: 600;
    }

    .x_content {
        padding: 20px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        padding: 12px 16px;
        background: #f9fafb;
        border: none;
    }

    .table tbody td {
        padding: 14px 16px;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Profit by Type Chart
    const profitByTypeData = {!! json_encode($profitByType) !!};
    
    if (profitByTypeData.length > 0) {
        const typeCtx = document.getElementById('profitByTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: profitByTypeData.map(d => d.type),
                datasets: [{
                    data: profitByTypeData.map(d => Math.abs(d.profit)),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                    ],
                    borderColor: ['#3b82f6', '#10b981'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 13, weight: '600' },
                            usePointStyle: true,
                        }
                    }
                }
            }
        });
    }

    // Win/Loss Distribution
    const wlData = {
        wins: {{ $winningTrades }},
        losses: {{ $losingTrades }}
    };

    const wlCtx = document.getElementById('wlDistributionChart').getContext('2d');
    const wlChart = new Chart(wlCtx, {
        type: 'bar',
        data: {
            labels: ['Winning', 'Losing'],
            datasets: [{
                label: 'Trades',
                data: [wlData.wins, wlData.losses],
                backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                borderColor: ['#10b981', '#ef4444'],
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });

    // Daily Profit Chart
    const dailyData = {!! json_encode($dailyProfit) !!};

    if (dailyData.length > 0) {
        const dailyCtx = document.getElementById('dailyProfitChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [{
                    label: 'Daily Profit',
                    data: dailyData.map(d => d.profit),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 13, weight: '600' },
                            padding: 15,
                        }
                    }
                },
                scales: {
                    y: {
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>

@endsection