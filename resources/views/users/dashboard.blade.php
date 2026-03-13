@extends('layouts.user')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Mono:wght@700&display=swap');

  .db * { box-sizing: border-box; }
  .db { font-family: 'Inter', sans-serif; font-size: 12px; color: #1e293b; padding: 20px; background: #f0f4f8; min-height: 100vh; }

  /* ── HEADER ── */
  .db-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
  .db-hdr h1 { font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 3px; }
  .db-hdr p  { font-size: 11px; color: #64748b; margin: 0; }
  .live-badge { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; border-radius: 20px; padding: 5px 14px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
  .live-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; animation: db-pulse 1.5s infinite; }
  @keyframes db-pulse { 0%,100%{opacity:1;} 50%{opacity:0.35;} }

  /* ── GRID HELPERS ── */
  .db-grid4  { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 12px; }
  .db-row2   { display: grid; grid-template-columns: 7fr 5fr; gap: 12px; margin-bottom: 14px; }
  .db-row25  { display: grid; grid-template-columns: 5fr 7fr; gap: 12px; margin-bottom: 14px; }
  .db-row27  { display: grid; grid-template-columns: 7fr 5fr; gap: 12px; }

  /* ── CARDS ── */
  .db-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; }
  .db-card-hdr { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
  .db-card-title { font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }

  /* ── BORDER ACCENTS ── */
  .bt-blue   { border-top: 3px solid #3b82f6 !important; }
  .bt-amber  { border-top: 3px solid #f59e0b !important; }
  .bt-violet { border-top: 3px solid #8b5cf6 !important; }
  .bt-cyan   { border-top: 3px solid #06b6d4 !important; }
  .bt-green  { border-top: 3px solid #10b981 !important; }
  .bt-pink   { border-top: 3px solid #ec4899 !important; }

  /* ── KPI TILES ── */
  .kpi-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 12px 14px; display: flex; align-items: center; gap: 10px; }
  .kpi-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
  .kpi-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 700; display: block; margin-bottom: 2px; }
  .kpi-val   { font-size: 17px; font-weight: 700; color: #0f172a; line-height: 1.2; }

  .sub-card  { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 14px; text-align: center; }
  .sub-icon  { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin: 0 auto 8px; }
  .sub-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 700; display: block; margin-bottom: 4px; }
  .sub-val   { font-size: 20px; font-weight: 700; }

  /* ── PROFIT/LOSS COLOURS ── */
  .col-green { color: #10b981 !important; font-weight: 700; }
  .col-red   { color: #ef4444 !important; font-weight: 700; }

  /* ── BADGE ── */
  .db-badge { font-size: 9px; padding: 3px 9px; border-radius: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; }

  /* ── MICRO LABEL ── */
  .micro { font-size: 9px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 700; }

  /* ── CHART ── */
  .chart-wrap { padding: 14px; height: 210px; }

  /* ── PROGRESS BAR ── */
  .prog-wrap { padding: 12px 14px 0; }
  .prog-lbl  { display: flex; justify-content: space-between; margin-bottom: 5px; }
  .prog-track { height: 6px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
  .prog-fill  { height: 100%; border-radius: 4px; transition: width 0.5s; }
  .risk-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin: 10px 14px; }
  .risk-box   { border-radius: 8px; padding: 10px 12px; }
  .info-tip   { margin: 0 14px 14px; background: #eff6ff; border-radius: 6px; padding: 8px 10px; font-size: 10.5px; color: #3b82f6; }

  /* ── TABLES ── */
  .db-tbl { width: 100%; border-collapse: collapse; font-size: 11.5px; }
  .db-tbl thead th { background: #f8fafc; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; padding: 9px 12px; font-weight: 700; border-top: none; }
  .db-tbl tbody td { padding: 9px 12px; border-bottom: 1px solid #f8fafc; }
  .db-tbl tbody tr:last-child td { border-bottom: none; }
  .db-tbl .t-right { text-align: right; }
  .db-tbl .t-center { text-align: center; }

  /* ── BOT ROWS ── */
  .bot-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; border-bottom: 1px solid #f8fafc; }
  .bot-row:last-child { border-bottom: none; }

  /* ── ROW TINTS ── */
  .row-gain { background: linear-gradient(90deg, #f0fdf4, #fff); }
  .row-loss { background: linear-gradient(90deg, #fef2f2, #fff); }
</style>

<div class="db">

  {{-- ── HEADER ── --}}
  <div class="db-hdr">
    <div>
      <h1>My Trading Dashboard</h1>
      <p>Accounts, signals execution, positions &amp; profit overview</p>
    </div>
    <div class="live-badge">
      <div class="live-dot"></div> LIVE SYSTEM
    </div>
  </div>

  {{-- ── PRIMARY KPIs ── --}}
  <div class="db-grid4">
    @php
      $kpis = [
        ['label'=>"Today's Profit", 'val'=>number_format($todaysProfit,2).' USD', 'id'=>'todaysProfit',    'color'=>'#10b981','bg'=>'#ecfdf5','icon'=>'fa-wallet'],
        ['label'=>'Trades Today',   'val'=>$todaysTrades,                          'id'=>'tradesToday',     'color'=>'#f59e0b','bg'=>'#fffbeb','icon'=>'fa-rotate'],
        ['label'=>'Win Rate',       'val'=>$winRate.'%',                           'id'=>'winRate',         'color'=>'#3b82f6','bg'=>'#eff6ff','icon'=>'fa-chart-line'],
        ['label'=>'Exec. Success',  'val'=>$live['execSuccessRate'] ?? '—',        'id'=>'execSuccessRate', 'color'=>'#ef4444','bg'=>'#fef2f2','icon'=>'fa-shield-check'],
      ];
    @endphp
    @foreach($kpis as $k)
      <div class="kpi-card">
        <div class="kpi-icon" style="background:{{ $k['bg'] }};color:{{ $k['color'] }};">
          <i class="fa-solid {{ $k['icon'] }}"></i>
        </div>
        <div>
          <span class="kpi-label">{{ $k['label'] }}</span>
          <span class="kpi-val" id="{{ $k['id'] }}" style="color:{{ $k['color'] }};">{{ $k['val'] }}</span>
        </div>
      </div>
    @endforeach
  </div>

  {{-- ── SECONDARY KPIs ── --}}
  <div class="db-grid4" style="margin-bottom:16px;">
    @php
      $subKpis = [
        ['label'=>'Connected Accounts','val'=>$live['connectedAccounts']??0,'id'=>'connectedAccounts','icon'=>'fa-link',           'color'=>'#06b6d4','bg'=>'#ecf9ff'],
        ['label'=>'Running Bots',       'val'=>$live['runningBots']??0,      'id'=>'runningBots',      'icon'=>'fa-robot',          'color'=>'#8b5cf6','bg'=>'#faf5ff'],
        ['label'=>'Signal Queue',       'val'=>$live['signalQueue']??0,      'id'=>'signalQueue',      'icon'=>'fa-bell',           'color'=>'#ec4899','bg'=>'#fdf2f8'],
        ['label'=>'Open Positions',     'val'=>$live['openPositions']??0,    'id'=>'openPositions',    'icon'=>'fa-arrow-trend-up', 'color'=>'#06a84d','bg'=>'#f0fdf4'],
      ];
    @endphp
    @foreach($subKpis as $s)
      <div class="sub-card" style="border-left:3px solid {{ $s['color'] }};">
        <div class="sub-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};">
          <i class="fa-solid {{ $s['icon'] }}"></i>
        </div>
        <span class="sub-label">{{ $s['label'] }}</span>
        <span class="sub-val" id="{{ $s['id'] }}" style="color:{{ $s['color'] }};">{{ $s['val'] }}</span>
        @if($s['label'] === 'Open Positions')
          <div class="micro mt-2">Floating P/L</div>
          <div id="floatingPnL"
               class="{{ ($live['floatingPnL']??0) >= 0 ? 'col-green' : 'col-red' }}"
               style="font-size:12px;margin-top:3px;">
            {{ number_format($live['floatingPnL']??0,2) }} USD
          </div>
        @endif
      </div>
    @endforeach
  </div>

  {{-- ── CHART + RISK ── --}}
  <div class="db-row2">

    {{-- P/L Chart --}}
    <div class="db-card bt-blue">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#3b82f6;">
          <i class="fa fa-chart-area me-1"></i> Profit / Loss Performance
        </span>
        <span class="db-badge" style="background:#eff6ff;color:#3b82f6;">LIVE</span>
      </div>
      <div class="chart-wrap">
        <canvas id="pnlChart"></canvas>
      </div>
    </div>

    {{-- Risk --}}
    <div class="db-card bt-amber">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#f59e0b;">
          <i class="fa fa-shield-exclamation me-1"></i> Portfolio Risk Indicators
        </span>
      </div>
      @php
        $allAccounts = auth()->user()->accounts;
        $maxDrawdown = 0; $totalEquity = 0; $totalBalance = 0; $highRiskAccounts = 0;
        foreach($allAccounts as $acc) {
          $snapshot = $acc->snapshots;
          if($snapshot) {
            $totalEquity  += $snapshot->equity;
            $totalBalance += $snapshot->balance;
            if($snapshot->drawdown > $maxDrawdown) $maxDrawdown = $snapshot->drawdown;
            if($snapshot->margin > 0 && ($snapshot->margin / $snapshot->equity) * 100 > 80) $highRiskAccounts++;
          }
        }
        $portfolioMarginUsage = $totalEquity > 0 ? (($totalBalance - $totalEquity) / $totalEquity) * 100 : 0;
      @endphp
      <div class="prog-wrap">
        <div class="prog-lbl micro">
          <span>Margin Usage</span>
          <span style="color:#3b82f6;font-weight:700;">{{ number_format($portfolioMarginUsage,1) }}%</span>
        </div>
        <div class="prog-track">
          <div class="prog-fill" style="width:{{ min($portfolioMarginUsage,100) }}%;background:#3b82f6;"></div>
        </div>
      </div>
      <div class="risk-grid">
        <div class="risk-box" style="background:#fee2e2;border-left:3px solid #ef4444;">
          <div class="micro mb-1"><i class="fa fa-arrow-down me-1" style="color:#ef4444;"></i>Max Drawdown</div>
          <span class="col-red" style="font-size:16px;">{{ number_format($maxDrawdown,2) }}%</span>
        </div>
        <div class="risk-box" style="background:{{ $highRiskAccounts>0?'#fee2e2':'#f0fdf4' }};border-left:3px solid {{ $highRiskAccounts>0?'#ef4444':'#10b981' }};">
          <div class="micro mb-1">
            <i class="fa {{ $highRiskAccounts>0?'fa-triangle-exclamation':'fa-circle-check' }} me-1"
               style="color:{{ $highRiskAccounts>0?'#ef4444':'#10b981' }};"></i>Risk Status
          </div>
          <span class="db-badge" style="background:{{ $highRiskAccounts>0?'#fef2f2':'#dcfce7' }};color:{{ $highRiskAccounts>0?'#ef4444':'#15803d' }};">
            {{ $highRiskAccounts }} High Risk
          </span>
        </div>
      </div>
      <div class="info-tip">
        <i class="fa fa-info-circle me-1"></i> Keep margin usage below 60% for safety.
      </div>
    </div>

  </div>

  {{-- ── EXPOSURE + BOT STATUS ── --}}
  <div class="db-row25">

    {{-- Exposure --}}
    <div class="db-card bt-violet">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#8b5cf6;">
          <i class="fa fa-chart-pie me-1"></i> Exposure by Symbol
        </span>
      </div>
      <div style="max-height:250px;overflow-y:auto;">
        <table class="db-tbl">
          <thead>
            <tr>
              <th style="color:#8b5cf6;">Symbol</th>
              <th class="t-right" style="color:#8b5cf6;">Lots</th>
              <th class="t-right" style="color:#8b5cf6;">P/L</th>
            </tr>
          </thead>
          <tbody id="exposureTable">
            @forelse(($live['exposure']??[]) as $row)
              <tr>
                <td style="font-weight:700;">{{ $row['symbol'] }}</td>
                <td class="t-right">{{ number_format($row['lots'],2) }}</td>
                <td class="t-right {{ $row['pnl']>=0?'col-green':'col-red' }}">{{ number_format($row['pnl'],2) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="t-center" style="padding:24px;color:#94a3b8;">No open exposure</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Active Bots --}}
    <div class="db-card bt-cyan">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#06b6d4;">
          <i class="fa fa-microchip me-1"></i> Active Bots Status
        </span>
      </div>
      <div style="max-height:250px;overflow-y:auto;">
        @php
          $botStatuses = \App\Models\EaStatusChange::whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->orderBy('changed_at','desc')->get()
            ->groupBy('account_id')->map(fn($g)=>$g->first());
        @endphp
        @forelse($botStatuses as $bs)
          @php
            $account   = \App\Models\Account::find($bs->account_id);
            $isHealthy = !in_array($bs->status,['error','stopped']);
            $stColor   = match($bs->status??'off') {
              'running' => '#10b981','error' => '#ef4444','stopped' => '#f59e0b', default => '#64748b'
            };
          @endphp
          <div class="bot-row {{ $isHealthy?'row-gain':'row-loss' }}">
            <div>
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:2px;">
                <i class="fa fa-server" style="color:{{ $stColor }};font-size:14px;"></i>
                <span style="font-weight:700;">Account #{{ $account->login??'Unknown' }}</span>
              </div>
              <span class="micro">{{ $bs->changed_at?$bs->changed_at->diffForHumans():'Never' }}</span>
            </div>
            <div style="text-align:right;">
              <span class="db-badge" style="background:{{ $isHealthy?'#ecfdf5':'#fef2f2' }};color:{{ $isHealthy?'#10b981':'#ef4444' }};">
                <i class="fa {{ $isHealthy?'fa-check-circle':'fa-exclamation-circle' }}" style="font-size:8px;"></i>
                {{ strtoupper($bs->status) }}
              </span>
              <div class="micro mt-2">
                Losses: <strong style="color:{{ $bs->consecutive_losses>0?'#ef4444':'#10b981' }};">{{ $bs->consecutive_losses??0 }}</strong>
              </div>
            </div>
          </div>
        @empty
          <div style="text-align:center;padding:24px;color:#94a3b8;">No active bots found</div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- ── ACCOUNT SUMMARY TABLE ── --}}
  <div class="db-card bt-green" style="margin-bottom:14px;">
    <div class="db-card-hdr">
      <span class="db-card-title" style="color:#10b981;">
        <i class="fa fa-wallet me-1"></i> Account Summary
      </span>
      <i class="fa fa-list" style="color:#94a3b8;"></i>
    </div>
    <div style="overflow-x:auto;">
      <table class="db-tbl">
        <thead>
          <tr style="color:#10b981;">
            <th>Account</th>
            <th class="t-right">Balance</th>
            <th class="t-right">Equity</th>
            <th class="t-right">Margin %</th>
            <th class="t-right">Today P/L</th>
            <th class="t-center">Bot Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($allAccounts as $acc)
            @php
              $snap        = $acc->snapshots;
              $dailySummary= \App\Models\DailySummary::where('account_id',$acc->id)->whereDate('summary_date',today())->first();
              $lastStatus  = \App\Models\EaStatusChange::where('account_id',$acc->id)->orderBy('changed_at','desc')->first();
              $margin_pct  = ($snap && $snap->equity>0) ? ($snap->margin/$snap->equity)*100 : 0;
              $dailyPl     = $dailySummary ? $dailySummary->daily_pl : 0;
              $statusVal   = $lastStatus ? strtolower($lastStatus->status) : 'off';
            @endphp
            <tr class="{{ $dailyPl>=0?'row-gain':'row-loss' }}">
              <td style="padding:10px 12px;">
                <div style="display:flex;align-items:center;gap:8px;">
                  <i class="fa fa-building" style="color:{{ $dailyPl>=0?'#10b981':'#ef4444' }};"></i>
                  <div>
                    <span style="font-weight:700;">#{{ $acc->login }}</span><br>
                    <span class="micro">{{ $acc->platform }}</span>
                  </div>
                </div>
              </td>
              <td class="t-right" style="font-weight:700;">${{ $snap?number_format($snap->balance,2):'0.00' }}</td>
              <td class="t-right" style="font-weight:700;color:#3b82f6;">${{ $snap?number_format($snap->equity,2):'0.00' }}</td>
              <td class="t-right {{ $margin_pct>80?'col-red':'col-green' }}">{{ number_format($margin_pct,1) }}%</td>
              <td class="t-right {{ $dailyPl>=0?'col-green':'col-red' }}">
                {{ $dailyPl>=0?'+':'' }}{{ number_format($dailyPl,2) }}
              </td>
              <td class="t-center">
                <span class="db-badge" style="background:{{ $statusVal=='running'?'#ecfdf5':'#fef2f2' }};color:{{ $statusVal=='running'?'#10b981':'#ef4444' }};">
                  <i class="fa {{ $statusVal=='running'?'fa-play':'fa-stop' }}" style="font-size:7px;"></i>
                  {{ strtoupper(substr($statusVal,0,4)) }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- ── SIGNAL EXECUTIONS + RECENT TRADES ── --}}
  <div class="db-row27">

    {{-- Executions --}}
    <div class="db-card bt-pink">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#ec4899;">
          <i class="fa fa-bolt me-1"></i> Recent Signal Executions
        </span>
      </div>
      <div style="max-height:250px;overflow-y:auto;">
        <table class="db-tbl">
          <thead>
            <tr style="color:#ec4899;">
              <th>Time</th><th>Account</th><th>Signal</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentSignals as $row)
              <tr class="{{ $row->status=='executed'?'row-gain':'row-loss' }}">
                <td style="color:#64748b;">
                  <i class="fa fa-clock me-1" style="color:#f59e0b;"></i>
                  {{ $row->created_at->format('H:i') }}
                </td>
                <td style="color:#3b82f6;font-weight:600;">
                  #{{ optional($row->account)->account_id ?? $row->account_id }}
                </td>
                <td style="font-weight:700;">
                  @if($row->signal)
                    <i class="fa fa-arrow-right me-1" style="color:{{ $row->signal->type=='BUY'?'#10b981':'#ef4444' }};"></i>
                    {{ $row->signal->symbol }} {{ $row->signal->type }}
                  @else
                    Signal #{{ $row->signal_id }}
                  @endif
                </td>
                <td>
                  <span class="db-badge" style="background:{{ $row->status=='executed'?'#ecfdf5':'#fef2f2' }};color:{{ $row->status=='executed'?'#10b981':'#ef4444' }};">
                    <i class="fa {{ $row->status=='executed'?'fa-check':'fa-times' }}" style="font-size:8px;"></i>
                    {{ strtoupper($row->status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" style="text-align:center;padding:20px;color:#94a3b8;"><i class="fa fa-inbox me-1"></i>No executions</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Recent Trades --}}
    <div class="db-card bt-cyan">
      <div class="db-card-hdr">
        <span class="db-card-title" style="color:#06b6d4;">
          <i class="fa fa-handshake me-1"></i> Recent Completed Trades
        </span>
      </div>
      <div style="max-height:250px;overflow-y:auto;">
        <table class="db-tbl">
          <thead>
            <tr style="color:#06b6d4;">
              <th>Symbol</th><th class="t-right">Profit</th><th class="t-center">Side</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentTrades as $t)
              @php $pl = $t->profit ?? 0; $side = $t->type ?? $t->side ?? '-'; @endphp
              <tr class="{{ $pl>=0?'row-gain':'row-loss' }}">
                <td style="font-weight:700;">
                  <i class="fa fa-chart-line me-1" style="color:#8b5cf6;"></i>
                  {{ $t->symbol }}
                </td>
                <td class="t-right {{ $pl>=0?'col-green':'col-red' }}">
                  {{ $pl>=0?'+':'' }}${{ number_format($pl,2) }}
                </td>
                <td class="t-center">
                  <i class="fa {{ strtoupper($side)==='BUY'?'fa-arrow-up':'fa-arrow-down' }}"
                     style="color:{{ strtoupper($side)==='BUY'?'#10b981':'#ef4444' }};font-weight:700;"></i>
                  <span style="font-size:9px;font-weight:700;color:{{ strtoupper($side)==='BUY'?'#10b981':'#ef4444' }};">
                    {{ strtoupper($side) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8;"><i class="fa fa-inbox me-1"></i>No trades</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pnlChart').getContext('2d');
const pnlChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['00:00','04:00','08:00','12:00','16:00','20:00','Live'],
    datasets: [{
      data: [0, 10, -5, 20, 15, 40, {{ $todaysProfit }}],
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.05)',
      borderWidth: 2.5,
      tension: 0.4,
      fill: true,
      pointRadius: 0,
      pointHoverRadius: 4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 9 } } },
      y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 9 } } }
    }
  }
});

async function refreshUserMetrics() {
  try {
    const res = await fetch("{{ route('user.dashboard.metrics') }}", {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) return;
    const d = await res.json();

    document.getElementById('todaysProfit').innerText   = Number(d.profit??0).toFixed(2) + ' USD';
    document.getElementById('tradesToday').innerText    = d.trades ?? 0;
    document.getElementById('winRate').innerText        = (d.winRate??0) + '%';

    if (d.live) {
      document.getElementById('signalQueue').innerText     = d.live.signalQueue ?? 0;
      document.getElementById('openPositions').innerText   = d.live.openPositions ?? 0;
      document.getElementById('execSuccessRate').innerText = d.live.execSuccessRate ?? '—';
      document.getElementById('connectedAccounts').innerText = d.live.connectedAccounts ?? 0;
      document.getElementById('runningBots').innerText     = d.live.runningBots ?? 0;

      const fp   = Number(d.live.floatingPnL ?? 0);
      const fpEl = document.getElementById('floatingPnL');
      fpEl.innerText  = fp.toFixed(2) + ' USD';
      fpEl.className  = fp >= 0 ? 'col-green' : 'col-red';

      const tbody = document.getElementById('exposureTable');
      if (tbody && d.live.exposure) {
        tbody.innerHTML = d.live.exposure.length
          ? d.live.exposure.map(r => `
              <tr>
                <td style="font-weight:700;">${r.symbol}</td>
                <td class="t-right">${Number(r.lots).toFixed(2)}</td>
                <td class="t-right ${Number(r.pnl)>=0?'col-green':'col-red'}">${Number(r.pnl).toFixed(2)}</td>
              </tr>`).join('')
          : '<tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8;">No open exposure</td></tr>';
      }
    }

    pnlChart.data.datasets[0].data[6] = d.profit;
    pnlChart.update('none');
  } catch(e) {}
}

setInterval(refreshUserMetrics, 5000);
</script>
@endsection