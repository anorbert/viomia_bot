@extends('layouts.admin')

@section('content')
<div class="container-fluid">

  {{-- ================= HEADER ================= --}}
  <div class="row mb-4">
    <div class="col-md-6">
      <h3 class="mb-0">Trading Bot Control Center</h3>
      <small class="text-muted">Live system & performance overview</small>
    </div>
    <div class="col-md-6 text-right">
      <span class="badge badge-success px-3 py-2">
        <i class="fa fa-circle"></i> LIVE
      </span>
    </div>
  </div>

  {{-- ================= PRIMARY KPIs ================= --}}
  <div class="row tile_count">

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Today's Profit</span>
      <div class="count green" id="todaysProfit">
        {{ number_format($todaysProfit, 2) }} USD
      </div>
      <span class="count_bottom">Net P/L</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Trades Today</span>
      <div class="count" id="tradesToday">{{ $todaysTrades }}</div>
      <span class="count_bottom">Closed positions</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Win Rate</span>
      <div class="count" id="winRate">
        {{ $todaysTrades > 0 ? round(($todaysWins / $todaysTrades) * 100, 1) : 0 }}%
      </div>
      <span class="count_bottom">Accuracy</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Active Bots</span>
      <div class="count">{{ $activeBots }}</div>
      <span class="count_bottom text-success">Running</span>
    </div>

  </div>

  {{-- ================= SECONDARY KPIs ================= --}}
  <div class="row mt-3">

    <div class="col-md-2">
      <div class="x_panel text-center">
        <h5>Clients</h5>
        <h3>{{ $totalClients }}</h3>
      </div>
    </div>

    <div class="col-md-2">
      <div class="x_panel text-center">
        <h5>New (7d)</h5>
        <h3 class="green">+{{ $newClients }}</h3>
      </div>
    </div>

    <div class="col-md-2">
      <div class="x_panel text-center">
        <h5>Accounts</h5>
        <h3>{{ $connectedAccounts }}</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="x_panel text-center">
        <h5>Avg Trade Duration</h5>
        <h3>{{ round($avgTradeDuration, 1) }} min</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="x_panel text-center">
        <h5>Server Health</h5>
        <h3 class="{{ $serverHealth === 'OK' ? 'green' : 'red' }}">
          {{ $serverHealth }}
        </h3>
      </div>
    </div>

  </div>

  {{-- ================= CHARTS ================= --}}
  <div class="row mt-4">

    <div class="col-md-8">
      <div class="x_panel">
        <div class="x_title">
          <h2>Profit Curve (12 Months)</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <canvas id="profitChart" height="110"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="x_panel">
        <div class="x_title">
          <h2>Trades by Symbol</h2>
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
/* ================= PROFIT CURVE ================= */
const profitChart = new Chart(document.getElementById('profitChart'), {
  type: 'line',
  data: {
    labels: @json($months),
    datasets: [{
      label: 'Net Profit (USD)',
      data: @json($monthlyProfit),
      borderWidth: 2,
      tension: 0.35,
      fill: true
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: false } }
  }
});

/* ================= SYMBOL DISTRIBUTION ================= */
const symbolChart = new Chart(document.getElementById('symbolChart'), {
  type: 'bar',
  data: {
    labels: @json($symbolData->pluck('symbol')),
    datasets: [{
      data: @json($symbolData->pluck('total')),
      borderWidth: 1
    }]
  },
  options: {
    plugins: { legend: { display: false } }
  }
});

/* ================= REAL-TIME UPDATES ================= */
setInterval(async () => {
  const res = await fetch("{{ route('admin.dashboard.metrics') }}");
  const d = await res.json();

  document.getElementById('todaysProfit').innerText =
    d.profit.toFixed(2) + ' USD';

  document.getElementById('tradesToday').innerText = d.trades;

  const winRate = d.trades > 0
    ? ((d.wins / d.trades) * 100).toFixed(1)
    : 0;

  document.getElementById('winRate').innerText = winRate + '%';

}, 5000);
</script>
@endsection
