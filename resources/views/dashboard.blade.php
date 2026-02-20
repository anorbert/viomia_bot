{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-0" style="padding-top:0;">

  {{-- ================= HEADER ================= --}}
  <div class="row mb-3">
    <div class="col-md-8 col-12">
      <h3 class="mb-0">Trading Bot Control Center</h3>
      <small class="text-muted">Live system, signals, execution & performance overview</small>
    </div>

    <div class="col-md-4 col-12 text-md-right mt-2 mt-md-0">
      <span class="badge badge-success px-3 py-2">
        <i class="fa fa-circle"></i> LIVE
      </span>
      <span class="badge badge-secondary px-3 py-2 ml-1">
        Alerts: <span id="alertsCount">{{ $alertsCount ?? 0 }}</span>
      </span>
    </div>
  </div>

  {{-- ================= PRIMARY KPIs ================= --}}
  <div class="row tile_count">
    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Today's Profit (USD)</span>
        @switch($todaysProfit)
          @case($todaysProfit > 0)
          <?php $colorClass = 'green'; ?>
          @case($todaysProfit < 0)
            <?php $colorClass = 'red'; ?>
          @default
            <?php $colorClass = ''; ?>
        @endswitch
      <div class="count {{ $colorClass }}" id="todaysProfit">{{ number_format($todaysProfit, 2) }}</div>
      <span class="count_bottom">Net P/L</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Trades Today</span>
      <div class="count" id="tradesToday">{{ $todaysTrades }}</div>
      <span class="count_bottom">Total trades</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Win Rate</span>
      <div class="count" id="winRate">{{ $winRate }}%</div>
      <span class="count_bottom">Wins / trades</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Active Bots</span>
      <div class="count">{{ $activeBots }}</div>
      <span class="count_bottom {{ $serverHealth === 'OK' ? 'text-success' : 'text-danger' }}">
        Server: <span id="serverHealth">{{ $serverHealth }}</span>
      </span>
    </div>

  </div>

  {{-- ================= ADVANCED KPIs ================= --}}
  <div class="row tile_count mt-2">

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Profit Factor</span>
      <div class="count" id="profitFactor">{{ $profitFactor ?? '—' }}</div>
      <span class="count_bottom">GrossProfit / GrossLoss</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Expectancy / Trade</span>
        @switch($expectancy)
            @case($expectancy > 0)
            <?php $exccolor = 'green'; ?>
            @case($expectancy < 0)
              <?php $exccolor = 'red'; ?>
            @default
              <?php $exccolor = ''; ?>
        @endswitch
      <div class="count {{ $exccolor }}" id="expectancy">{{ number_format($expectancy, 2) }}</div>
      <span class="count_bottom">Avg edge per trade</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Avg Win</span>
      <div class="count green" id="avgWin">{{ number_format($avgWin, 2) }}</div>
      <span class="count_bottom">USD</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Avg Loss</span>
      <div class="count red" id="avgLoss">{{ number_format($avgLoss, 2) }}</div>
      <span class="count_bottom">USD</span>
    </div>

  </div>

  {{-- ================= SECONDARY KPIs ================= --}}
  <div class="row mt-3">

    <div class="col-md-2 col-6">
      <div class="x_panel text-center">
        <h5>Clients</h5>
        <h3>{{ $totalClients }}</h3>
      </div>
    </div>

    <div class="col-md-2 col-6">
      <div class="x_panel text-center">
        <h5>New (7d)</h5>
        <h3 class="green">+{{ $newClients }}</h3>
      </div>
    </div>

    <div class="col-md-2 col-6">
      <div class="x_panel text-center">
        <h5>Accounts</h5>
        <h3>{{ $connectedAccounts }}</h3>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="x_panel text-center">
        <h5>Avg Trade Duration</h5>
        <h3>{{ round($avgTradeDuration, 1) }} min</h3>
      </div>
    </div>

    <div class="col-md-3 col-12">
      <div class="x_panel text-center">
        <h5>Best vs Worst (7d)</h5>
        <div class="d-flex justify-content-center">
          <div class="mr-4">
            <small class="text-muted">Best</small>
            <div class="green" style="font-size:18px;font-weight:600">{{ number_format($bestDayPnL, 2) }}</div>
          </div>
          <div>
            <small class="text-muted">Worst</small>
            <div class="red" style="font-size:18px;font-weight:600">{{ number_format($worstDayPnL, 2) }}</div>
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
          <h2>Live Trading Snapshot</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <div class="row text-center">
            <div class="col-md-3 col-6">
              <h6>Open Positions</h6>
              <h3 id="openPositions">{{ $live['openPositions'] ?? 0 }}</h3>
            </div>

            <div class="col-md-3 col-6">
              <h6>Floating P/L</h6>
              <h3 id="floatingPnL" class="{{ ($live['floatingPnL'] ?? 0) >= 0 ? 'green' : 'red' }}">
                {{ number_format($live['floatingPnL'] ?? 0, 2) }}
              </h3>
            </div>

            <div class="col-md-3 col-6">
              <h6>Signal Queue</h6>
              <h3 id="signalQueue">{{ $live['signalQueue'] ?? 0 }}</h3>
            </div>

            <div class="col-md-3 col-6">
              <h6>Exec Success (1h)</h6>
              <h3 id="execSuccessRate">{{ $live['execSuccessRate'] ?? '—' }}</h3>
            </div>
          </div>

          <hr/>

          <div class="row">
            <div class="col-md-6">
              <h5 class="mb-2">Last Signal</h5>
              <div class="alert alert-info mb-0">
                <strong id="lastSignalText">{{ $live['lastSignalText'] ?? '—' }}</strong><br>
                <small class="text-muted">
                  Received: <span id="lastSignalAge">{{ $live['lastSignalAge'] ?? '—' }}</span>
                </small>
              </div>
            </div>

            <div class="col-md-6 mt-3 mt-md-0">
              <h5 class="mb-2">Last Execution</h5>
              <div class="alert alert-warning mb-0">
                <strong id="lastExecText">{{ $live['lastExecText'] ?? '—' }}</strong><br>
                <small class="text-muted">
                  Status: <span id="lastExecStatus">{{ $live['lastExecStatus'] ?? '—' }}</span>
                </small>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="x_panel">
        <div class="x_title">
          <h2>Exposure by Symbol</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content" style="max-height:270px; overflow:auto;">
          <table class="table table-striped table-sm mb-0">
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
                  <td>{{ $row['symbol'] }}</td>
                  <td class="text-right">{{ number_format($row['lots'], 2) }}</td>
                  <td class="text-right {{ $row['pnl'] >= 0 ? 'green' : 'red' }}">
                    {{ number_format($row['pnl'], 2) }}
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted">No exposure</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

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
          <h2>Errors (Today)</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <canvas id="errorChart" height="110"></canvas>
        </div>
      </div>
    </div>

  </div>

  {{-- ================= FEEDS ================= --}}
  <div class="row mt-3">

    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>Recent Errors</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content" style="max-height:260px; overflow:auto;">
          <ul class="list-group" id="recentErrors">
            <li class="list-group-item text-muted">Loading...</li>
          </ul>
        </div>
      </div>
    </div>
    {{-- ================= Calendar Journal ================= --}}
    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>Daily Summary</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <div class="cal-header">
            <button id="prevMonth" class="cal-nav-btn">‹</button>
            <div id="monthYear" class="cal-title">January 2026</div>
            <button id="nextMonth" class="cal-nav-btn">›</button>

            <button id="todayBtn" class="cal-today-btn">
              <i class="fa fa-calendar"></i> Today
            </button>
          </div>

          <div class="cal-weekdays">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
          </div>

          <div id="calendarGrid" class="cal-grid">
            {{-- JS will render tiles here --}}
          </div>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

@section('scripts')
  <style>
    /* Calendar look */
    .cal-header{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:14px;
    }
    .cal-nav-btn{
      width:36px;
      height:36px;
      border:1px solid #e3e7ee;
      background:#fff;
      border-radius:8px;
      cursor:pointer;
      font-size:18px;
      font-weight:700;
      color:#333;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .cal-title{
      flex:1;
      text-align:center;
      font-weight:700;
      font-size:14px;
      color:#222;
      letter-spacing:.2px;
    }
    .cal-today-btn{
      border:1px solid #e3e7ee;
      background:#fff;
      border-radius:8px;
      padding:8px 12px;
      font-size:12px;
      font-weight:600;
      color:#444;
      cursor:pointer;
      display:flex;
      align-items:center;
      gap:6px;
    }

    .cal-weekdays{
      display:grid;
      grid-template-columns:repeat(7, 1fr);
      gap:10px;
      margin-bottom:10px;
      padding:0 2px;
    }
    .cal-weekdays div{
      text-align:center;
      font-size:12px;
      font-weight:700;
      color:#8b95a5;
    }

    .cal-grid{
      display:grid;
      grid-template-columns:repeat(7, 1fr);
      gap:10px;
    }

    .cal-day{
      position:relative;
      height:74px;
      border:1px solid #e3e7ee;
      border-radius:10px;
      background:#fff;
      padding:10px;
      overflow:hidden;
      cursor:pointer;
      transition:transform .06s ease, box-shadow .06s ease;
    }
    .cal-day:hover{
      transform:translateY(-1px);
      box-shadow:0 6px 14px rgba(0,0,0,.06);
    }

    .cal-day.disabled{
      background:#fafbfd;
      color:#c2c8d3;
    }
    .cal-day.disabled .cal-date{
      color:#c2c8d3;
    }

    .cal-date{
      position:absolute;
      top:10px;
      left:10px;
      font-size:12px;
      font-weight:700;
      color:#a8b0bd;
    }

    .cal-metrics{
      position:absolute;
      right:10px;
      bottom:10px;
      text-align:right;
      line-height:1.1;
    }
    .cal-trades{
      font-size:12px;
      font-weight:700;
      color:#2a2f3a;
    }
    .cal-pnl{
      font-size:12px;
      font-weight:800;
    }

    /* Profit/Loss backgrounds like screenshot */
    .cal-profit{
      background:#d9f6ec; /* soft green */
      border-color:#56d6a6;
    }
    .cal-profit .cal-pnl{ color:#0ea56b; }

    .cal-loss{
      background:#ffd9dd; /* soft red */
      border-color:#ff6b78;
    }
    .cal-loss .cal-pnl{ color:#d7263d; }

    /* Today outline */
    .cal-today{
      box-shadow:0 0 0 2px rgba(23,162,184,.15);
      border-color:#8ad4e2;
    }
  </style>

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

/* ================= ERROR CHART ================= */
let errorChart = new Chart(document.getElementById('errorChart'), {
  type: 'bar',
  data: { labels: [], datasets: [{ data: [], borderWidth: 1 }] },
  options: { plugins: { legend: { display: false } } }
});

// Initialize calendar on load
initializeCalendarData();

function renderErrors(list){
  const el = document.getElementById('recentErrors');
  if(!el) return;

  if(!list || list.length === 0){
    el.innerHTML = `<li class="list-group-item text-muted">No errors today</li>`;
    return;
  }

  el.innerHTML = list.map(e => `
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <div>
        <strong>${e.type}</strong>
        <div class="text-muted" style="font-size:12px">${e.msg}</div>
      </div>
      <span class="badge badge-light">${e.at}</span>
    </li>
  `).join('');
}

function renderErrorChart(breakdown){
  const labels = (breakdown || []).map(x => x.type);
  const data   = (breakdown || []).map(x => x.total);

  errorChart.data.labels = labels;
  errorChart.data.datasets[0].data = data;
  errorChart.update();
}

/* ================= REAL-TIME UPDATES ================= */
async function refreshMetrics() {
  try {
    const res = await fetch("{{ route('admin.dashboard.metrics') }}", {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) return;

    const d = await res.json();

    document.getElementById('todaysProfit').innerText = Number(d.profit).toFixed(2) + ' USD';
    document.getElementById('tradesToday').innerText  = d.trades;
    document.getElementById('winRate').innerText      = (d.winRate ?? 0) + '%';

    document.getElementById('avgWin').innerText        = Number(d.avgWin ?? 0).toFixed(2);
    document.getElementById('avgLoss').innerText       = Number(d.avgLoss ?? 0).toFixed(2);
    document.getElementById('profitFactor').innerText  = (d.profitFactor === null ? '—' : d.profitFactor);
    document.getElementById('expectancy').innerText    = Number(d.expectancy ?? 0).toFixed(2);

    // live block
    if (d.live) {
      document.getElementById('openPositions').innerText = d.live.openPositions ?? 0;

      const fp = Number(d.live.floatingPnL ?? 0);
      const fpEl = document.getElementById('floatingPnL');
      fpEl.innerText = fp.toFixed(2) + ' USD';
      fpEl.classList.remove('green','red');
      fpEl.classList.add(fp >= 0 ? 'green' : 'red');

      document.getElementById('signalQueue').innerText = d.live.signalQueue ?? 0;
      document.getElementById('execSuccessRate').innerText = d.live.execSuccessRate ?? '—';

      document.getElementById('lastSignalText').innerText = d.live.lastSignalText ?? '—';
      document.getElementById('lastSignalAge').innerText  = d.live.lastSignalAge ?? '—';

      document.getElementById('lastExecText').innerText   = d.live.lastExecText ?? '—';
      document.getElementById('lastExecStatus').innerText = d.live.lastExecStatus ?? '—';

      // exposure table
      const tbody = document.getElementById('exposureTable');
      if (tbody) {
        const rows = (d.live.exposure || []).map(r => `
          <tr>
            <td>${r.symbol}</td>
            <td class="text-right">${Number(r.lots).toFixed(2)}</td>
            <td class="text-right ${Number(r.pnl) >= 0 ? 'green' : 'red'}">${Number(r.pnl).toFixed(2)}</td>
          </tr>
        `).join('');

        tbody.innerHTML = rows || `<tr><td colspan="3" class="text-center text-muted">No exposure</td></tr>`;
      }

      renderErrors(d.live.recentErrors);
      renderErrorChart(d.live.errorBreakdown);
    }

    // Update calendar with full journal data (90 days)
    if (d.journalFullData && typeof d.journalFullData === 'object') {
      allJournalData = d.journalFullData;
      renderCalendar();
    }

  } catch (e) {
    console.error('Dashboard update error:', e);
  }
}

refreshMetrics();
setInterval(refreshMetrics, 5000);
</script>

<script>
  // Expect data like:
  // window.calendarDailyData = {
  //   "2026-01-19": { trades: 1, pnl: 3.20 },
  //   "2026-01-20": { trades: 2, pnl: -172.40 },
  // };
  window.calendarDailyData = window.calendarDailyData || {};

  let currentDate = new Date(); // calendar view

  function pad(n){ return String(n).padStart(2,'0'); }
  function ymd(d){
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  }
  function monthLabel(d){
    return d.toLocaleString('en-US', { month:'long', year:'numeric' });
  }

  function renderCalendar(){
    const grid = document.getElementById('calendarGrid');
    const title = document.getElementById('monthYear');
    if(!grid || !title) return;

    title.innerText = monthLabel(currentDate);

    const viewYear  = currentDate.getFullYear();
    const viewMonth = currentDate.getMonth();

    // first day of month
    const first = new Date(viewYear, viewMonth, 1);
    const startDayIndex = first.getDay(); // 0 Sun .. 6 Sat

    const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

    // show 6 weeks = 42 tiles (matches screenshot spacing)
    const totalTiles = 42;

    // build date range start: from previous month padding
    const start = new Date(viewYear, viewMonth, 1 - startDayIndex);

    const today = new Date();
    const todayKey = ymd(today);

    let html = '';

    for(let i=0; i<totalTiles; i++){
      const d = new Date(start);
      d.setDate(start.getDate() + i);

      const key = ymd(d);
      const inMonth = (d.getMonth() === viewMonth);
      const daily = window.calendarDailyData[key];

      const isToday = (key === todayKey);

      let cls = 'cal-day';
      if(!inMonth) cls += ' disabled';
      if(isToday) cls += ' cal-today';

      // Profit/loss coloring only if we have pnl AND day is in current month
      if(inMonth && daily && typeof daily.pnl === 'number'){
        if(daily.pnl > 0) cls += ' cal-profit';
        else if(daily.pnl < 0) cls += ' cal-loss';
      }

      const dayNum = d.getDate();

      // Metrics area (like screenshot: trades count and pnl)
      let metrics = '';
      if(inMonth && daily){
        const trades = daily.trades ?? 0;
        const pnl = Number(daily.pnl ?? 0);

        // keep the same "tiny" look: show trades then $pnl
        metrics = `
          <div class="cal-metrics">
            <div class="cal-trades">${trades} ⇆</div>
            <div class="cal-pnl">$${Math.abs(pnl).toFixed(2)}</div>
          </div>
        `;

        // show minus sign using red background already; keep pnl number positive like screenshot
        // if you prefer sign, replace above with: ${pnl.toFixed(2)}
      }

      html += `
        <div class="${cls}" data-date="${key}">
          <div class="cal-date">${dayNum}</div>
          ${metrics}
        </div>
      `;
    }

    grid.innerHTML = html;

    // Optional click handler (open modal / drill-down)
    grid.querySelectorAll('.cal-day').forEach(el => {
      el.addEventListener('click', () => {
        const date = el.getAttribute('data-date');
        // Hook: open daily trades modal
        // console.log("clicked date", date, window.calendarDailyData[date]);
      });
    });
  }

  // Nav
  document.getElementById('prevMonth')?.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
  });
  document.getElementById('nextMonth')?.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
  });
  document.getElementById('todayBtn')?.addEventListener('click', () => {
    currentDate = new Date();
    renderCalendar();
  });

  // Initial
  renderCalendar();
</script>

@endsection
