@extends('layouts.user')

@section('content')
<style>
    .dashboard-wrapper { font-size: 12px; color: #334155; }
    .text-micro { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; }
    .phx-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; position: relative; }
    .icon-box { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px; }
    .table thead th { background: #f8fafc; text-transform: uppercase; font-size: 9px; color: #64748b; border-top: none; padding: 10px 8px; }
    .green { color: #10b981 !important; }
    .red { color: #ef4444 !important; }
    .badge-phx { font-size: 9px; padding: 3px 10px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
    .chart-h { height: 200px; width: 100%; }
</style>

<div class="container-fluid p-0 dashboard-wrapper">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bolder mb-0" style="font-size: 18px;">My Trading Dashboard</h4>
            <span class="text-muted" style="font-size: 11px;">Accounts, signals execution, positions & profit overview</span>
        </div>
        <div class="badge-phx" style="background: #e2f6f1; color: #25b89a; border: 1px solid #c3eee4;">
            <i class="fa fa-circle small"></i> LIVE SYSTEM
        </div>
    </div>

    {{-- PRIMARY KPIs (The 4 Main Tiles) --}}
    <div class="row g-2 mb-3">
        @php
            $kpis = [
                ['label' => "Today's Profit", 'val' => number_format($todaysProfit, 2).' USD', 'id' => 'todaysProfit', 'color' => '#10b981', 'bg' => '#ecfdf5', 'icon' => 'fa-wallet'],
                ['label' => 'Trades Today', 'val' => $todaysTrades, 'id' => 'tradesToday', 'color' => '#f59e0b', 'bg' => '#fffbeb', 'icon' => 'fa-rotate'],
                ['label' => 'Win Rate', 'val' => $winRate.'%', 'id' => 'winRate', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'icon' => 'fa-chart-line'],
                ['label' => 'Exec. Success', 'val' => $live['execSuccessRate'] ?? '—', 'id' => 'execSuccessRate', 'color' => '#ef4444', 'bg' => '#fef2f2', 'icon' => 'fa-shield-check']
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="col-md-3">
            <div class="phx-card p-2 d-flex align-items-center shadow-sm border-0">
                <div class="icon-box" style="background: {{ $kpi['bg'] }}; color: {{ $kpi['color'] }};">
                    <i class="fa-solid {{ $kpi['icon'] }} small"></i>
                </div>
                <div>
                    <p class="text-micro mb-0">{{ $kpi['label'] }}</p>
                    <span class="fw-bolder fs-6" id="{{ $kpi['id'] }}">{{ $kpi['val'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- SECONDARY KPIs (Signal Queue, Bots, etc.) --}}
    <div class="row g-2 mb-3">
        @php
            $subKpis = [
                ['label' => 'Connected Accounts', 'val' => $live['connectedAccounts'] ?? 0, 'id' => 'connectedAccounts', 'icon' => 'fa-link', 'color' => '#06b6d4', 'bg' => '#ecf9ff'],
                ['label' => 'Running Bots', 'val' => $live['runningBots'] ?? 0, 'id' => 'runningBots', 'icon' => 'fa-robot', 'color' => '#8b5cf6', 'bg' => '#faf5ff'],
                ['label' => 'Signal Queue', 'val' => $live['signalQueue'] ?? 0, 'id' => 'signalQueue', 'icon' => 'fa-bell', 'color' => '#ec4899', 'bg' => '#fdf2f8'],
                ['label' => 'Open Positions', 'val' => $live['openPositions'] ?? 0, 'id' => 'openPositions', 'icon' => 'fa-arrow-trend-up', 'color' => '#06a84d', 'bg' => '#f0fdf4'],
            ];
        @endphp
        @foreach($subKpis as $skpi)
        <div class="col-6 col-md-3">
            <div class="phx-card p-3 text-center shadow-sm border-0" style="border-left: 3px solid {{ $skpi['color'] }}; transition: all 0.3s ease;" 
                 onmouseover="this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)'; this.style.transform='translateY(0)';">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: {{ $skpi['bg'] }}; color: {{ $skpi['color'] }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 16px;">
                    <i class="fa-solid {{ $skpi['icon'] }}"></i>
                </div>
                <span class="text-micro d-block mb-1">{{ $skpi['label'] }}</span>
                <span class="fw-bold fs-5" id="{{ $skpi['id'] }}" style="color: {{ $skpi['color'] }};">{{ $skpi['val'] }}</span>
                @if($skpi['label'] == 'Open Positions')
                    <div class="text-micro mt-2">
                        <span style="font-size: 9px; color: #64748b;">Floating P/L</span>
                        <div style="margin-top: 4px;">
                            <span id="floatingPnL" class="{{ ($live['floatingPnL'] ?? 0) >= 0 ? 'green' : 'red' }}" style="font-weight: 700;">
                                {{ number_format($live['floatingPnL'] ?? 0, 2) }} USD
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHART AND RISK SECTION --}}
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="phx-card p-3 shadow-sm border-0 h-100" style="border-top: 3px solid #3b82f6;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-micro"><i class="fa fa-chart-area me-2" style="color: #3b82f6;"></i> Profit / Loss Performance</span>
                    <span class="badge" style="background: #eff6ff; color: #3b82f6; font-size: 9px;">LIVE</span>
                </div>
                <div class="chart-h">
                    <canvas id="pnlChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="phx-card p-3 shadow-sm border-0 h-100" style="border-top: 3px solid #f59e0b;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa fa-shield-exclamation" style="color: #f59e0b; font-size: 14px;"></i>
                    <span class="text-micro">Portfolio Risk Indicators</span>
                </div>
                @php
                    $allAccounts = auth()->user()->accounts;
                    $maxDrawdown = 0; $totalEquity = 0; $totalBalance = 0; $highRiskAccounts = 0;
                    foreach($allAccounts as $acc) {
                        $snapshot = $acc->snapshots;
                        if($snapshot) {
                            $totalEquity += $snapshot->equity; $totalBalance += $snapshot->balance;
                            if($snapshot->drawdown > $maxDrawdown) $maxDrawdown = $snapshot->drawdown;
                            if($snapshot->margin > 0 && ($snapshot->margin / $snapshot->equity) * 100 > 80) $highRiskAccounts++;
                        }
                    }
                    $portfolioMarginUsage = $totalEquity > 0 ? (($totalBalance - $totalEquity) / $totalEquity) * 100 : 0;
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between text-micro mb-1">
                        <span>Margin Usage</span>
                        <span class="fw-bold">{{ number_format($portfolioMarginUsage, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 6px; background: #f1f5f9;">
                        <div class="progress-bar rounded" style="width: {{ $portfolioMarginUsage }}%; background: #3b82f6;"></div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded border-0" style="background: linear-gradient(135deg, #fee2e2, #fef2f2); border-left: 3px solid #ef4444;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <i class="fa fa-arrow-down" style="color: #ef4444; font-size: 12px;"></i>
                                <span class="text-micro">Max Drawdown</span>
                            </div>
                            <span class="fw-bold text-danger" style="font-size: 16px;">{{ number_format($maxDrawdown, 2) }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded border-0" style="background: linear-gradient(135deg, {{ $highRiskAccounts > 0 ? '#fee2e2' : '#f0fdf4' }}, {{ $highRiskAccounts > 0 ? '#fef2f2' : '#f0fdf4' }}); border-left: 3px solid {{ $highRiskAccounts > 0 ? '#ef4444' : '#10b981' }};">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <i class="fa {{ $highRiskAccounts > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check' }}" style="color: {{ $highRiskAccounts > 0 ? '#ef4444' : '#10b981' }}; font-size: 12px;"></i>
                                <span class="text-micro">Risk Status</span>
                            </div>
                            <span class="badge bg-{{ $highRiskAccounts > 0 ? 'danger' : 'success' }}" style="font-size: 11px;">{{ $highRiskAccounts }} High Risk</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 p-2 mb-0" style="background: #eff6ff; font-size: 10.5px;">
                    <i class="fa fa-info-circle me-1"></i> Keep margin usage below 60% for safety.
                </div>
            </div>
        </div>
    </div>

    {{-- EXPOSURE AND BOT STATUS (SIDE BY SIDE) --}}
    <div class="row g-3 mt-1">
        <div class="col-md-5">
            <div class="phx-card shadow-sm border-0 h-100" style="border-top: 3px solid #8b5cf6;">
                <div class="p-2 px-3 border-bottom text-micro fw-bold" style="color: #8b5cf6;">
                    <i class="fa fa-chart-pie me-2"></i>Exposure by Symbol
                </div>
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th style="color: #8b5cf6;">Symbol</th><th class="text-end" style="color: #8b5cf6;">Lots</th><th class="text-end" style="color: #8b5cf6;">P/L</th></tr>
                        </thead>
                        <tbody id="exposureTable" style="font-size: 11.5px;">
                            @forelse(($live['exposure'] ?? []) as $row)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $row['symbol'] }}</td>
                                    <td class="text-end">{{ number_format($row['lots'], 2) }}</td>
                                    <td class="text-end fw-bold {{ $row['pnl'] >= 0 ? 'green' : 'red' }} pe-3">
                                        {{ number_format($row['pnl'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No open exposure</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="phx-card shadow-sm border-0 h-100" style="border-top: 3px solid #06b6d4;">
                <div class="p-2 px-3 border-bottom text-micro fw-bold" style="color: #06b6d4;">
                    <i class="fa fa-microchip me-2"></i>Active Bots Status
                </div>
                <div style="max-height: 250px; overflow-y: auto;">
                    @php
                        $botStatuses = \App\Models\EaStatusChange::whereIn('account_id', auth()->user()->accounts->pluck('id'))
                          ->orderBy('changed_at', 'desc')->get()->groupBy('account_id')->map(fn($group) => $group->first());
                    @endphp
                    @forelse($botStatuses as $bs)
                        @php
                            $account = \App\Models\Account::find($bs->account_id);
                            $isHealthy = !in_array($bs->status, ['error', 'stopped']);
                            $statusColor = match($bs->status ?? 'off') {
                                'running' => '#10b981',
                                'error' => '#ef4444',
                                'stopped' => '#f59e0b',
                                default => '#64748b'
                            };
                        @endphp
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="background: {{ $isHealthy ? 'linear-gradient(90deg, #f0fdf4, #ffffff)' : 'linear-gradient(90deg, #fef2f2, #ffffff)' }};">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-server" style="color: {{ $statusColor }}; font-size: 14px;"></i>
                                    <span class="fw-bold">Account #{{ $account->login ?? 'Unknown' }}</span>
                                </div>
                                <span class="text-muted" style="font-size: 10px;">{{ $bs->changed_at ? $bs->changed_at->diffForHumans() : 'Never' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge-phx" style="background: {{ $isHealthy ? '#ecfdf5' : '#fef2f2' }}; color: {{ $isHealthy ? '#10b981' : '#ef4444' }};">
                                    <i class="fa {{ $isHealthy ? 'fa-check-circle' : 'fa-exclamation-circle' }}" style="font-size: 8px;"></i>
                                    {{ strtoupper($bs->status) }}
                                </span>
                                <small class="text-muted d-block mt-2" style="font-size: 9px;">Losses: <strong style="color: {{ $bs->consecutive_losses > 0 ? '#ef4444' : '#10b981' }};">{{ $bs->consecutive_losses ?? 0 }}</strong></small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">No active bots found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ACCOUNT SUMMARY TABLE --}}
    <div class="phx-card mt-3 shadow-sm border-0" style="border-top: 3px solid #10b981;">
        <div class="p-2 px-3 border-bottom bg-white fw-bold text-micro d-flex justify-content-between" style="color: #10b981;">
            <span><i class="fa fa-wallet me-2"></i>Account Summary</span>
            <i class="fa fa-list text-muted"></i>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle" style="font-size: 11.5px;">
                <thead>
                    <tr class="text-micro" style="color: #10b981;">
                        <th class="ps-3">Account</th>
                        <th class="text-end">Balance</th>
                        <th class="text-end">Equity</th>
                        <th class="text-end">Margin %</th>
                        <th class="text-end">Today P/L</th>
                        <th class="text-center">Bot Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allAccounts as $acc)
                        @php
                            $snapshot = $acc->snapshots;
                            $dailySummary = \App\Models\DailySummary::where('account_id', $acc->id)->whereDate('summary_date', today())->first();
                            $lastStatus = \App\Models\EaStatusChange::where('account_id', $acc->id)->orderBy('changed_at', 'desc')->first();
                            $margin_pct = ($snapshot && $snapshot->equity > 0) ? ($snapshot->margin / $snapshot->equity) * 100 : 0;
                            $dailyPl = $dailySummary ? $dailySummary->daily_pl : 0;
                            $statusValue = $lastStatus ? strtolower($lastStatus->status) : 'off';
                        @endphp
                        <tr style="background: {{ $dailyPl >= 0 ? 'linear-gradient(90deg, #f0fdf4, #ffffff)' : 'linear-gradient(90deg, #fef2f2, #ffffff)' }};">
                            <td class="ps-3 py-2">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-building" style="color: {{ $dailyPl >= 0 ? '#10b981' : '#ef4444' }};"></i>
                                    <div>
                                        <span class="fw-bold">#{{ $acc->login }}</span><br>
                                        <span class="text-muted" style="font-size: 10px;">{{ $acc->platform }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end fw-bold">${{ $snapshot ? number_format($snapshot->balance, 2) : '0.00' }}</td>
                            <td class="text-end fw-bold" style="color: #3b82f6;">${{ $snapshot ? number_format($snapshot->equity, 2) : '0.00' }}</td>
                            <td class="text-end fw-bold {{ $margin_pct > 80 ? 'red' : 'green' }}">{{ number_format($margin_pct, 1) }}%</td>
                            <td class="text-end fw-bold {{ $dailyPl >= 0 ? 'green' : 'red' }}">
                                {{ $dailyPl >= 0 ? '+' : '' }}{{ number_format($dailyPl, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge-phx" style="background: {{ $statusValue == 'running' ? '#ecfdf5' : '#fef2f2' }}; color: {{ $statusValue == 'running' ? '#10b981' : '#ef4444' }};">
                                    <i class="fa {{ $statusValue == 'running' ? 'fa-play' : 'fa-stop' }}" style="font-size: 7px;"></i>
                                    {{ strtoupper(substr($statusValue, 0, 4)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- RECENT SIGNAL EXECUTIONS & TRADES --}}
    <div class="row g-3 mt-1">
        <div class="col-md-7">
            <div class="phx-card shadow-sm border-0" style="border-top: 3px solid #ec4899;">
                <div class="p-2 px-3 border-bottom text-micro fw-bold" style="color: #ec4899;">
                    <i class="fa fa-zap me-2"></i>Recent Signal Executions
                </div>
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm mb-0" style="font-size: 11px;">
                        <thead>
                            <tr class="text-micro" style="color: #ec4899;">
                                <th class="ps-3">Time</th>
                                <th>Account</th>
                                <th>Signal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSignals as $row)
                                <tr style="background: {{ $row->status == 'executed' ? 'linear-gradient(90deg, #f0fdf4, #ffffff)' : 'linear-gradient(90deg, #fef2f2, #ffffff)' }};">
                                    <td class="ps-3 text-muted">
                                        <i class="fa fa-clock" style="color: #f59e0b; margin-right: 6px;"></i>
                                        {{ $row->created_at->format('H:i') }}
                                    </td>
                                    <td>
                                        <span style="color: #3b82f6; font-weight: 600;">#{{ optional($row->account)->account_id ?? $row->account_id }}</span>
                                    </td>
                                    <td class="fw-bold">
                                        @if($row->signal)
                                            <i class="fa fa-arrow-right" style="color: {{ $row->signal->type == 'BUY' ? '#10b981' : '#ef4444' }}; margin-right: 4px;"></i>
                                            {{ $row->signal->symbol }} {{ $row->signal->type }}
                                        @else
                                            Signal #{{ $row->signal_id }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-phx" style="background: {{ $row->status == 'executed' ? '#ecfdf5' : '#fef2f2' }}; color: {{ $row->status == 'executed' ? '#10b981' : '#ef4444' }};">
                                            <i class="fa {{ $row->status == 'executed' ? 'fa-check' : 'fa-times' }}" style="font-size: 8px;"></i>
                                            {{ strtoupper($row->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3 text-muted"><i class="fa fa-inbox"></i> No executions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="phx-card shadow-sm border-0" style="border-top: 3px solid #06b6d4;">
                <div class="p-2 px-3 border-bottom text-micro fw-bold" style="color: #06b6d4;">
                    <i class="fa fa-handshake me-2"></i>Recent Completed Trades
                </div>
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm mb-0" style="font-size: 11px;">
                        <thead>
                            <tr class="text-micro" style="color: #06b6d4;">
                                <th class="ps-3">Symbol</th>
                                <th class="text-end">Profit</th>
                                <th class="text-center">Side</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTrades as $t)
                                <tr style="background: {{ ($t->profit ?? 0) >= 0 ? 'linear-gradient(90deg, #f0fdf4, #ffffff)' : 'linear-gradient(90deg, #fef2f2, #ffffff)' }};">
                                    <td class="ps-3 fw-bold">
                                        <i class="fa fa-chart-line" style="color: #8b5cf6; margin-right: 6px;"></i>
                                        {{ $t->symbol }}
                                    </td>
                                    <td class="text-end fw-bold {{ ($t->profit ?? 0) >= 0 ? 'green' : 'red' }}">
                                        {{ ($t->profit ?? 0) >= 0 ? '+' : '' }}${{ number_format($t->profit ?? 0, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa {{ ($t->type ?? $t->side) === 'BUY' ? 'fa-arrow-up' : 'fa-arrow-down' }}" 
                                           style="color: {{ ($t->type ?? $t->side) === 'BUY' ? '#10b981' : '#ef4444' }}; font-weight: bold;"></i>
                                        <span style="color: {{ ($t->type ?? $t->side) === 'BUY' ? '#10b981' : '#ef4444' }}; font-size: 9px; font-weight: 700;">
                                            {{ strtoupper($t->type ?? $t->side ?? '-') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-3 text-muted"><i class="fa fa-inbox"></i> No trades</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// CHART.JS Setup
const ctx = document.getElementById('pnlChart').getContext('2d');
const pnlChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', 'Live'],
        datasets: [{
            data: [0, 10, -5, 20, 15, 40, {{ $todaysProfit }}], // Dynamic initial value
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.05)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointRadius: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 9 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 9 } } }
        }
    }
});

async function refreshUserMetrics(){
    try{
        const res = await fetch("{{ route('user.dashboard.metrics') }}", { headers: { 'X-Requested-With':'XMLHttpRequest' }});
        if(!res.ok) return;
        const d = await res.json();

        // Update Text KPIs
        document.getElementById('todaysProfit').innerText = Number(d.profit ?? 0).toFixed(2) + ' USD';
        document.getElementById('tradesToday').innerText = d.trades ?? 0;
        document.getElementById('winRate').innerText = (d.winRate ?? 0) + '%';

        if(d.live){
            document.getElementById('signalQueue').innerText = d.live.signalQueue ?? 0;
            document.getElementById('openPositions').innerText = d.live.openPositions ?? 0;
            document.getElementById('execSuccessRate').innerText = d.live.execSuccessRate ?? '—';
            
            // Update Floating PnL
            const fp = Number(d.live.floatingPnL ?? 0);
            const fpEl = document.getElementById('floatingPnL');
            fpEl.innerText = fp.toFixed(2) + ' USD';
            fpEl.className = fp >= 0 ? 'green' : 'red';

            // Update Exposure Table
            const tbody = document.getElementById('exposureTable');
            if(tbody && d.live.exposure){
                tbody.innerHTML = d.live.exposure.map(r => `
                    <tr>
                        <td class="ps-3 fw-bold">${r.symbol}</td>
                        <td class="text-end">${Number(r.lots).toFixed(2)}</td>
                        <td class="text-end fw-bold ${Number(r.pnl) >= 0 ? 'green' : 'red'} pe-3">${Number(r.pnl).toFixed(2)}</td>
                    </tr>
                `).join('');
            }
        }
        
        // Live update chart
        pnlChart.data.datasets[0].data[6] = d.profit;
        pnlChart.update('none');

    } catch(e) {}
}
setInterval(refreshUserMetrics, 5000);
</script>
@endsection