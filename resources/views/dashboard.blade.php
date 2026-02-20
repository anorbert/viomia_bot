{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<style>
    /* Styling refinements for a compact, professional look */
    .tile_count .tile_stats_count { padding-bottom: 10px; border-bottom: 1px solid #eee; margin-bottom: 10px; }
    .tile_count .tile_stats_count .count { font-size: 24px; font-weight: 700; }
    .tile_count .tile_stats_count span { font-size: 11px; }
    .x_panel { border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: none; }
    .x_title h2 { font-weight: 700; font-size: 15px; }
    .kpi-small-card { background: #fff; padding: 12px; border-radius: 6px; border: 1px solid #e9ecef; transition: all 0.2s; }
    .kpi-small-card:hover { border-color: #26B99A; }
    .list-group-item { padding: 8px 12px; font-size: 13px; }
</style>

<div class="container-fluid pt-2">

  {{-- ================= HEADER ================= --}}
  <div class="row mb-3 align-items-center">
    <div class="col-md-8 col-12">
      <h3 class="mb-0 font-weight-bold" style="color: #2A3F54;">Trading Bot Control Center</h3>
      <small class="text-muted"><i class="fa fa-circle text-success mr-1"></i> Live system, signals, execution & performance overview</small>
    </div>

    <div class="col-md-4 col-12 text-md-right mt-2 mt-md-0">
      <span class="badge badge-success px-3 py-2 shadow-sm">
        <i class="fa fa-bolt mr-1"></i> LIVE
      </span>
      <span class="badge badge-secondary px-3 py-2 ml-1 shadow-sm">
        <i class="fa fa-bell mr-1"></i> Alerts: <span id="alertsCount">{{ $alertsCount ?? 0 }}</span>
      </span>
    </div>
  </div>

  {{-- ================= PRIMARY KPIs ================= --}}
  <div class="row tile_count bg-white rounded shadow-sm py-2 mb-3 mx-0">
    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-money mr-1"></i> Today's Profit (USD)</span>
        @switch($todaysProfit)
          @case($todaysProfit > 0) <?php $colorClass = 'green'; ?> @break
          @case($todaysProfit < 0) <?php $colorClass = 'red'; ?> @break
          @default <?php $colorClass = ''; ?>
        @endswitch
      <div class="count {{ $colorClass }}" id="todaysProfit">{{ number_format($todaysProfit, 2) }}</div>
      <span class="count_bottom font-weight-bold">NET P/L</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-exchange mr-1"></i> Trades Today</span>
      <div class="count" id="tradesToday">{{ $todaysTrades }}</div>
      <span class="count_bottom">TOTAL TRADES</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-line-chart mr-1"></i> Win Rate</span>
      <div class="count green" id="winRate">{{ $winRate }}%</div>
      <span class="count_bottom">WINS / TRADES</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-cubes mr-1"></i> Active Bots</span>
      <div class="count text-primary">{{ $activeBots }}</div>
      <span class="count_bottom {{ $serverHealth === 'OK' ? 'text-success' : 'text-danger' }} font-weight-bold">
        SERVER: <span id="serverHealth">{{ $serverHealth }}</span>
      </span>
    </div>
  </div>

  {{-- ================= ADVANCED KPIs ================= --}}
  <div class="row tile_count bg-white rounded shadow-sm py-2 mb-3 mx-0">
    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-calculator mr-1"></i> Profit Factor</span>
      <div class="count" id="profitFactor">{{ $profitFactor ?? '—' }}</div>
      <span class="count_bottom">GROSS P / L</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-bullseye mr-1"></i> Expectancy / Trade</span>
        @switch($expectancy)
            @case($expectancy > 0) <?php $exccolor = 'green'; ?> @break
            @case($expectancy < 0) <?php $exccolor = 'red'; ?> @break
            @default <?php $exccolor = ''; ?>
        @endswitch
      <div class="count {{ $exccolor }}" id="expectancy">{{ number_format($expectancy, 2) }}</div>
      <span class="count_bottom">AVG EDGE</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count border-right">
      <span class="count_top"><i class="fa fa-level-up mr-1"></i> Avg Win</span>
      <div class="count green" id="avgWin">{{ number_format($avgWin, 2) }}</div>
      <span class="count_bottom text-muted">USD</span>
    </div>

    <div class="col-md-3 col-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-level-down mr-1"></i> Avg Loss</span>
      <div class="count red" id="avgLoss">{{ number_format($avgLoss, 2) }}</div>
      <span class="count_bottom text-muted">USD</span>
    </div>
  </div>

  {{-- ================= SECONDARY KPIs ================= --}}
  <div class="row mt-3 mb-4">
    <div class="col-md-2 col-6">
      <div class="kpi-small-card text-center shadow-sm">
        <h6 class="text-muted mb-1">Clients</h6>
        <h4 class="mb-0 font-weight-bold">{{ $totalClients }}</h4>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="kpi-small-card text-center shadow-sm">
        <h6 class="text-muted mb-1">New (7d)</h6>
        <h4 class="mb-0 green font-weight-bold">+{{ $newClients }}</h4>
      </div>
    </div>
    <div class="col-md-2 col-6">
      <div class="kpi-small-card text-center shadow-sm">
        <h6 class="text-muted mb-1">Accounts</h6>
        <h4 class="mb-0 font-weight-bold">{{ $connectedAccounts }}</h4>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="kpi-small-card text-center shadow-sm">
        <h6 class="text-muted mb-1">Avg Duration</h6>
        <h4 class="mb-0 font-weight-bold">{{ round($avgTradeDuration, 1) }} min</h4>
      </div>
    </div>
    <div class="col-md-3 col-12">
      <div class="kpi-small-card shadow-sm">
        <h6 class="text-muted mb-2 text-center">Best vs Worst (7d)</h6>
        <div class="d-flex justify-content-around">
          <div class="text-center">
            <div class="green font-weight-bold">{{ number_format($bestDayPnL, 2) }}</div>
            <small class="text-muted" style="font-size:9px">BEST</small>
          </div>
          <div class="text-center">
            <div class="red font-weight-bold">{{ number_format($worstDayPnL, 2) }}</div>
            <small class="text-muted" style="font-size:9px">WORST</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ================= LIVE OPS ================= --}}
  <div class="row mt-3">
    <div class="col-md-8">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-rss mr-2"></i>Live Trading Snapshot</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content pt-2">
          <div class="row text-center mb-3">
            <div class="col-md-3 col-6">
              <span class="text-muted small">Open Positions</span>
              <h3 class="mt-1 font-weight-bold" id="openPositions">{{ $live['openPositions'] ?? 0 }}</h3>
            </div>
            <div class="col-md-3 col-6">
              <span class="text-muted small">Floating P/L</span>
              <h3 id="floatingPnL" class="mt-1 font-weight-bold {{ ($live['floatingPnL'] ?? 0) >= 0 ? 'green' : 'red' }}">
                {{ number_format($live['floatingPnL'] ?? 0, 2) }}
              </h3>
            </div>
            <div class="col-md-3 col-6">
              <span class="text-muted small">Signal Queue</span>
              <h3 id="signalQueue" class="mt-1 font-weight-bold text-info">{{ $live['signalQueue'] ?? 0 }}</h3>
            </div>
            <div class="col-md-3 col-6">
              <span class="text-muted small">Exec Success</span>
              <h3 id="execSuccessRate" class="mt-1 font-weight-bold">{{ $live['execSuccessRate'] ?? '—' }}</h3>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-6">
              <div class="alert alert-light border mb-0 py-2">
                <small class="font-weight-bold text-uppercase text-info">Last Signal</small>
                <div class="mt-1"><strong id="lastSignalText">{{ $live['lastSignalText'] ?? '—' }}</strong></div>
                <small class="text-muted"><i class="fa fa-clock-o mr-1"></i><span id="lastSignalAge">{{ $live['lastSignalAge'] ?? '—' }}</span></small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="alert alert-light border mb-0 py-2">
                <small class="font-weight-bold text-uppercase text-warning">Last Execution</small>
                <div class="mt-1"><strong id="lastExecText">{{ $live['lastExecText'] ?? '—' }}</strong></div>
                <small class="text-muted">Status: <span id="lastExecStatus" class="badge badge-warning" style="font-size:10px">{{ $live['lastExecStatus'] ?? '—' }}</span></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-pie-chart mr-2"></i>Exposure by Symbol</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content pt-0" style="max-height:240px; overflow-y:auto;">
          <table class="table table-hover table-sm mb-0">
            <thead class="bg-light">
              <tr>
                <th class="border-0">Symbol</th>
                <th class="border-0 text-right">Lots</th>
                <th class="border-0 text-right">P/L</th>
              </tr>
            </thead>
            <tbody id="exposureTable">
              @forelse(($live['exposure'] ?? []) as $row)
                <tr>
                  <td class="font-weight-bold">{{ $row['symbol'] }}</td>
                  <td class="text-right">{{ number_format($row['lots'], 2) }}</td>
                  <td class="text-right font-weight-bold {{ $row['pnl'] >= 0 ? 'green' : 'red' }}">
                    {{ number_format($row['pnl'], 2) }}
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted py-3">No active exposure</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- ================= CHARTS ================= --}}
  <div class="row mt-3">
    <div class="col-md-8">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-area-chart mr-2"></i>Profit Curve (12 Months)</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <canvas id="profitChart" height="100"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-exclamation-triangle mr-2"></i>Errors (Today)</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <canvas id="errorChart" height="110"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- ================= FEEDS ================= --}}
  <div class="row mt-3 mb-5">
    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-list mr-2"></i>Recent Errors</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content px-0" style="max-height:260px; overflow-y:auto;">
          <ul class="list-group list-group-flush" id="recentErrors">
            <li class="list-group-item text-muted text-center">Loading error feed...</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2><i class="fa fa-bar-chart mr-2"></i>Trades by Symbol (30 Days)</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <canvas id="symbolChart" height="110"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* Keeping all your chart logic and real-time refresh exactly as provided */
const profitChart = new Chart(document.getElementById('profitChart'), {
  type: 'line',
  data: {
    labels: @json($months),
    datasets: [{
      label: 'Net Profit (USD)',
      data: @json($monthlyProfit),
      borderColor: '#26B99A',
      backgroundColor: 'rgba(38, 185, 154, 0.1)',
      borderWidth: 2,
      tension: 0.35,
      fill: true
    }]
  },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: false } } }
});

const symbolChart = new Chart(document.getElementById('symbolChart'), {
  type: 'bar',
  data: {
    labels: @json($symbolData->pluck('symbol')),
    datasets: [{
      data: @json($symbolData->pluck('total')),
      backgroundColor: '#34495E',
      borderWidth: 0
    }]
  },
  options: { plugins: { legend: { display: false } } }
});

let errorChart = new Chart(document.getElementById('errorChart'), {
  type: 'bar',
  data: { labels: [], datasets: [{ data: [], backgroundColor: '#E74C3C', borderWidth: 0 }] },
  options: { plugins: { legend: { display: false } } }
});

function renderErrors(list){
  const el = document.getElementById('recentErrors');
  if(!el) return;
  if(!list || list.length === 0){
    el.innerHTML = `<li class="list-group-item text-muted text-center">No errors today</li>`;
    return;
  }
  el.innerHTML = list.map(e => `
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <div>
        <strong class="text-danger">${e.type}</strong>
        <div class="text-muted" style="font-size:12px">${e.msg}</div>
      </div>
      <span class="badge badge-light" style="font-weight:400">${e.at}</span>
    </li>
  `).join('');
}

function renderErrorChart(breakdown){
  errorChart.data.labels = (breakdown || []).map(x => x.type);
  errorChart.data.datasets[0].data = (breakdown || []).map(x => x.total);
  errorChart.update();
}

async function refreshMetrics() {
  try {
    const res = await fetch("{{ route('admin.dashboard.metrics') }}", {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) return;
    const d = await res.json();

    document.getElementById('todaysProfit').innerText = Number(d.profit).toFixed(2);
    document.getElementById('tradesToday').innerText  = d.trades;
    document.getElementById('winRate').innerText      = (d.winRate ?? 0) + '%';
    document.getElementById('avgWin').innerText        = Number(d.avgWin ?? 0).toFixed(2);
    document.getElementById('avgLoss').innerText       = Number(d.avgLoss ?? 0).toFixed(2);
    document.getElementById('profitFactor').innerText  = (d.profitFactor === null ? '—' : d.profitFactor);
    document.getElementById('expectancy').innerText    = Number(d.expectancy ?? 0).toFixed(2);

    if (d.live) {
      document.getElementById('openPositions').innerText = d.live.openPositions ?? 0;
      const fp = Number(d.live.floatingPnL ?? 0);
      const fpEl = document.getElementById('floatingPnL');
      fpEl.innerText = fp.toFixed(2);
      fpEl.classList.remove('green','red');
      fpEl.classList.add(fp >= 0 ? 'green' : 'red');

      document.getElementById('signalQueue').innerText = d.live.signalQueue ?? 0;
      document.getElementById('execSuccessRate').innerText = d.live.execSuccessRate ?? '—';
      document.getElementById('lastSignalText').innerText = d.live.lastSignalText ?? '—';
      document.getElementById('lastSignalAge').innerText  = d.live.lastSignalAge ?? '—';
      document.getElementById('lastExecText').innerText   = d.live.lastExecText ?? '—';
      document.getElementById('lastExecStatus').innerText = d.live.lastExecStatus ?? '—';

      const tbody = document.getElementById('exposureTable');
      if (tbody) {
        tbody.innerHTML = (d.live.exposure || []).map(r => `
          <tr>
            <td class="font-weight-bold">${r.symbol}</td>
            <td class="text-right">${Number(r.lots).toFixed(2)}</td>
            <td class="text-right font-weight-bold ${Number(r.pnl) >= 0 ? 'green' : 'red'}">${Number(r.pnl).toFixed(2)}</td>
          </tr>
        `).join('') || `<tr><td colspan="3" class="text-center text-muted">No exposure</td></tr>`;
      }
      renderErrors(d.live.recentErrors);
      renderErrorChart(d.live.errorBreakdown);
    }
  } catch (e) { }
}

refreshMetrics();
setInterval(refreshMetrics, 5000);
</script>
@endsection