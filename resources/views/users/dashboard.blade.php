@extends('layouts.user')

@section('content')
<style>
    /* UI Fundamentals */
    .right_col { 
        background: #f3f2ef !important; 
        padding: 20px 25px !important; /* Increased horizontal breathing room */
    }
    
    /* LinkedIn-Style Card Architecture */
    .ln-card { 
        background: #fff; 
        border-radius: 8px; 
        border: 1px solid #e0e0e0; 
        margin-bottom: 20px; /* More space between vertical cards */
        overflow: hidden; 
        box-shadow: 0 0.1rem 0.3rem rgba(0,0,0,0.05);
    }
    .ln-card-header { 
        padding: 15px 20px; /* More internal header space */
        border-bottom: 1px solid #f3f2ef; 
        font-weight: 600; 
        font-size: 14px; 
        color: #2A3F54; 
    }
    .ln-card-body {
        padding: 15px 20px;
    }
    
    /* Profile Summary Spacing */
    .profile-bg { background: linear-gradient(to right, #2A3F54, #1ABB9C); height: 80px; }
    .profile-avatar { 
        width: 85px; height: 85px; border: 4px solid #fff; 
        border-radius: 50%; margin: -45px auto 15px; 
        display: block; background: #fff; 
    }
    
    /* Quick Actions Grid Spacing */
    .quick-link-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 15px; /* More space between action buttons */
    }
    .ql-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 10px;
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        background: #ffffff;
        transition: all 0.2s ease-in-out;
    }
    .ql-item:hover {
        background: #f8fbfa;
        border-color: #1ABB9C;
        transform: translateY(-3px);
    }
    .ql-item i { font-size: 22px; margin-bottom: 10px; color: #73879C; }
    .ql-item span { font-size: 11px; font-weight: 700; color: #2A3F54; letter-spacing: 0.3px; }

    /* KPI Display Spacing */
    .kpi-box { padding: 20px; }
    .kpi-label { font-size: 11px; color: #73879C; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 5px; }
    .kpi-value { font-size: 24px; font-weight: 700; color: #2A3F54; }
    .green { color: #1ABB9C !important; }
    .red { color: #E74C3C !important; }

    /* Table Spacing Refinement */
    .table-clean thead th { background: #f9fafb; padding: 12px 15px !important; border-bottom: 1px solid #eee; font-size: 11px; }
    .table-clean td { vertical-align: middle; padding: 15px 15px !important; border-bottom: 1px solid #f3f2ef; }

    /* Status Pulse */
    .pulse-dot { 
        height: 10px; width: 10px; background-color: #1ABB9C; 
        border-radius: 50%; display: inline-block; margin-right: 8px; 
        animation: pulse 2s infinite; 
    }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(26, 187, 156, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(26, 187, 156, 0); } 100% { box-shadow: 0 0 0 0 rgba(26, 187, 156, 0); } }
</style>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-3 col-md-4 px-3">
            <div class="ln-card text-center pb-4">
                <div class="profile-bg"></div>
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="profile-avatar" alt="Logo">
                <h5 class="mb-1"><strong>Trader Dashboard</strong></h5>
                <p class="text-muted small"><span class="pulse-dot"></span> System Live</p>
            </div>

            <div class="ln-card">
                <div class="ln-card-header">Current Exposure</div>
                <div class="p-0"> <table class="table table-sm mb-0 table-clean">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th class="text-right">Lots</th>
                                <th class="text-right">P/L</th>
                            </tr>
                        </thead>
                        <tbody id="exposureTable">
                            @forelse(($live['exposure'] ?? []) as $row)
                            <tr>
                                <td><strong>{{ $row['symbol'] }}</strong></td>
                                <td class="text-right">{{ number_format($row['lots'], 2) }}</td>
                                <td class="text-right font-weight-bold {{ $row['pnl'] >= 0 ? 'green' : 'red' }}">
                                    {{ number_format($row['pnl'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-5 small">No open positions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-8 px-3">
            
            <div class="ln-card">
                <div class="ln-card-header">Quick Actions</div>
                <div class="ln-card-body">
                    <div class="quick-link-grid">
                        <a href="{{ route('user.accounts.index') }}" class="ql-item">
                            <i class="fa fa-university"></i>
                            <span>Accounts</span>
                        </a>
                        <a href="{{ route('user.signals.index') }}" class="ql-item">
                            <i class="fa fa-bolt"></i>
                            <span>Signals</span>
                        </a>
                        <a href="{{ route('user.executions.index') }}" class="ql-item">
                            <i class="fa fa-list-alt"></i>
                            <span>Logs</span>
                        </a>
                        <a href="{{ route('user.trades.history') }}" class="ql-item">
                            <i class="fa fa-history"></i>
                            <span>History</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="ln-card kpi-box">
                        <span class="kpi-label">Today's Profit</span>
                        <span class="kpi-value green" id="todaysProfit">{{ number_format($todaysProfit ?? 0, 2) }} USD</span>
                        <div class="mt-2 small text-muted">Win Rate: <span id="winRate" class="font-weight-bold text-dark">{{ $winRate ?? 0 }}%</span></div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="ln-card kpi-box">
                        <span class="kpi-label">Floating P/L</span>
                        <span class="kpi-value {{ ($live['floatingPnL'] ?? 0) >= 0 ? 'green' : 'red' }}" id="floatingPnL">
                            {{ number_format($live['floatingPnL'] ?? 0, 2) }} USD
                        </span>
                        <div class="mt-2 small text-muted">Active Trades: <span id="openPositions" class="font-weight-bold text-dark">{{ $live['openPositions'] ?? 0 }}</span></div>
                    </div>
                </div>
            </div>

            <div class="ln-card">
                <div class="ln-card-header d-flex justify-content-between align-items-center">
                    <span>Recent Trading Activity</span>
                    <i class="fa fa-circle-o-notch fa-spin text-muted small" style="display:none;" id="loader"></i>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-clean">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Asset Details</th>
                                    <th class="text-right">Profit/Loss</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTrades ?? [] as $t)
                                <tr>
                                    <td class="text-muted small">{{ $t->created_at->format('H:i:s') }}</td>
                                    <td><strong>{{ $t->symbol }}</strong> <small class="ml-2 px-2 py-1 bg-light rounded text-uppercase">{{ $t->type }}</small></td>
                                    <td class="text-right font-weight-bold {{ $t->profit >= 0 ? 'green' : 'red' }}">
                                        {{ number_format($t->profit, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-5">Waiting for market activity...</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 d-none d-lg-block px-3">
            <div class="ln-card ln-card-body">
                <div class="mb-4 border-bottom pb-3">
                    <span class="kpi-label">Signal Queue</span>
                    <h3 class="mb-0 font-weight-bold" id="signalQueue">{{ $live['signalQueue'] ?? 0 }}</h3>
                    <small class="text-muted">Current depth</small>
                </div>

                <div class="p-3 bg-light rounded border mb-2">
                    <small class="kpi-label text-success">Last Execution</small>
                    <strong style="font-size: 12px; display:block; margin: 4px 0;" id="lastSignalText">{{ $live['lastSignalText'] ?? 'Searching...' }}</strong>
                    <small class="text-muted"><i class="fa fa-clock-o mr-1"></i> <span id="lastSignalAge">{{ $live['lastSignalAge'] ?? 'Calculating' }}</span></small>
                </div>
            </div>

            <div class="ln-card">
                <div class="ln-card-header">Bot Notifications</div>
                <div class="ln-card-body small text-muted line-height-lg">
                    <p class="mb-2"><strong>Tip:</strong> Ensure your API keys are valid to prevent execution delays.</p>
                    <a href="#" class="font-weight-bold text-primary">Learn more about risk management</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function refreshUserMetrics(){
    const loader = document.getElementById('loader');
    if(loader) loader.style.display = 'inline-block';
    
    try {
        const res = await fetch("{{ route('user.dashboard.metrics') }}", { 
            headers: { 'X-Requested-With':'XMLHttpRequest' }
        });
        if(!res.ok) throw new Error('Refresh failed');
        const d = await res.json();

        document.getElementById('todaysProfit').innerText = Number(d.profit ?? 0).toFixed(2) + ' USD';
        document.getElementById('winRate').innerText = (d.winRate ?? 0) + '%';

        if(d.live){
            document.getElementById('signalQueue').innerText = d.live.signalQueue ?? 0;
            document.getElementById('openPositions').innerText = d.live.openPositions ?? 0;

            const fp = Number(d.live.floatingPnL ?? 0);
            const fpEl = document.getElementById('floatingPnL');
            if(fpEl) {
                fpEl.innerText = fp.toFixed(2) + ' USD';
                fpEl.className = 'kpi-value ' + (fp >= 0 ? 'green' : 'red');
            }

            const tbody = document.getElementById('exposureTable');
            if(tbody && d.live.exposure){
                tbody.innerHTML = d.live.exposure.map(r => `
                    <tr>
                        <td><strong>${r.symbol}</strong></td>
                        <td class="text-right">${Number(r.lots).toFixed(2)}</td>
                        <td class="text-right font-weight-bold ${Number(r.pnl) >= 0 ? 'green' : 'red'}">${Number(r.pnl).toFixed(2)}</td>
                    </tr>
                `).join('') || `<tr><td colspan="3" class="text-center text-muted py-5 small">No open positions</td></tr>`;
            }
        }
    } catch(e) { console.error("Metrics Update error", e); }
    finally { if(loader) loader.style.display = 'none'; }
}

setInterval(refreshUserMetrics, 5000);
</script>
@endsection