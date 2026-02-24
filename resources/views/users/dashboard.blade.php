@extends('layouts.user') {{-- or layouts.admin if you share same theme --}}
@section('content')

<div class="container-fluid pt-0" style="padding-top:0;">

  {{-- HEADER --}}
  <div class="row mb-3">
    <div class="col-md-8 col-12">
      <h3 class="mb-0">My Trading Dashboard</h3>
      <small class="text-muted">Accounts, signals execution, positions & profit overview</small>
    </div>

    <div class="col-md-4 col-12 text-md-right mt-2 mt-md-0">
      <span class="badge badge-success px-3 py-2">
        <i class="fa fa-circle"></i> LIVE
      </span>
    </div>
  </div>

  {{-- PRIMARY KPIs --}}
  <div class="row tile_count">

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Today's Profit</span>
      <div class="count green" id="todaysProfit">{{ number_format($todaysProfit, 2) }} USD</div>
      <span class="count_bottom">My P/L today</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Trades Today</span>
      <div class="count" id="tradesToday">{{ $todaysTrades }}</div>
      <span class="count_bottom">My trades</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Win Rate</span>
      <div class="count" id="winRate">{{ $winRate }}%</div>
      <span class="count_bottom">Wins / trades</span>
    </div>

    <div class="col-md-3 tile_stats_count">
      <span class="count_top">Execution Success (1h)</span>
      <div class="count" id="execSuccessRate">{{ $live['execSuccessRate'] ?? '—' }}</div>
      <span class="count_bottom">My accounts</span>
    </div>

  </div>

  {{-- SECONDARY KPIs --}}
  <div class="row mt-3">

    <div class="col-md-3 col-6">
      <div class="x_panel text-center">
        <h5>Connected Accounts</h5>
        <h3>{{ $live['connectedAccounts'] ?? 0 }}</h3>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="x_panel text-center">
        <h5>Running Bots</h5>
        <h3>{{ $live['runningBots'] ?? 0 }}</h3>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="x_panel text-center">
        <h5>Signal Queue</h5>
        <h3 id="signalQueue">{{ $live['signalQueue'] ?? 0 }}</h3>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="x_panel text-center">
        <h5>Open Positions</h5>
        <h3 id="openPositions">{{ $live['openPositions'] ?? 0 }}</h3>
        <small class="text-muted">Floating:
          <strong id="floatingPnL" class="{{ ($live['floatingPnL'] ?? 0) >= 0 ? 'green' : 'red' }}">
            {{ number_format($live['floatingPnL'] ?? 0, 2) }} USD
          </strong>
        </small>
      </div>
    </div>

  </div>

  {{-- BOT HEALTH & METRICS SECTION --}}
  <div class="row mt-4">
    <div class="col-md-12">
      <h4 class="mb-3 font-weight-bold">
        <i class="fa fa-robot mr-2 text-info"></i> Bot Health & Performance
      </h4>
    </div>
  </div>

  <div class="row">
    {{-- Bot Health Overview --}}
    <div class="col-md-6">
      <div class="x_panel shadow-sm">
        <div class="x_title" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px 8px 0 0; margin: -15px -15px 0 -15px; padding: 15px;">
          <h2 style="color: white; margin: 0;">
            <i class="fa fa-heartbeat mr-2"></i> Active Bots Status
          </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @php
            $bots = \App\Models\EaBot::where('status', 'active')->get();
            // Get latest status change per account
            $botStatuses = \App\Models\EaStatusChange::whereIn('account_id', auth()->user()->accounts->pluck('id'))
              ->orderBy('changed_at', 'desc')
              ->get()
              ->groupBy('account_id')
              ->map(fn($group) => $group->first());
          @endphp
          <div style="max-height: 350px; overflow-y: auto;">
            @forelse($botStatuses as $bs)
              @php
                $account = \App\Models\Account::find($bs->account_id);
                $isHealthy = !in_array($bs->status, ['error', 'stopped']);
                $healthColor = $isHealthy ? '#28a745' : '#dc3545';
              @endphp
              <div style="padding: 12px; border-bottom: 1px solid #f1f1f1; display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <h6 class="mb-1" style="font-weight: 600;">
                    Account #{{ $account->login ?? 'Unknown' }}
                  </h6>
                  <small class="text-muted">
                    <i class="fa fa-clock-o mr-1"></i> {{ $bs->changed_at ? $bs->changed_at->diffForHumans() : 'Never' }}
                  </small>
                </div>
                <div style="text-align: right;">
                  <div style="margin-bottom: 6px;">
                    <span class="badge badge-{{ $isHealthy ? 'success' : 'danger' }}" style="font-size: 11px; padding: 5px 10px;">
                      <i class="fa {{ $isHealthy ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                      {{ strtoupper($bs->status) }}
                    </span>
                  </div>
                  <small class="text-muted d-block">Loss Streak: <strong>{{ $bs->consecutive_losses ?? 0 }}</strong></small>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted">
                <i class="fa fa-inbox" style="font-size: 32px; opacity: 0.3;"></i>
                <p class="mt-2">No active bots</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- Account Risk Metrics --}}
    <div class="col-md-6">
      <div class="x_panel shadow-sm">
        <div class="x_title" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 8px 8px 0 0; margin: -15px -15px 0 -15px; padding: 15px;">
          <h2 style="color: white; margin: 0;">
            <i class="fa fa-warning mr-2"></i> Risk Indicators
          </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @php
            $allAccounts = auth()->user()->accounts;
            $maxDrawdown = 0;
            $totalEquity = 0;
            $totalBalance = 0;
            $highRiskAccounts = 0;

            foreach($allAccounts as $acc) {
              $snapshot = $acc->snapshots;
              if($snapshot) {
                $totalEquity += $snapshot->equity;
                $totalBalance += $snapshot->balance;
                if($snapshot->drawdown && $snapshot->drawdown > $maxDrawdown) {
                  $maxDrawdown = $snapshot->drawdown;
                }
                // Account with margin > 80% is high risk
                if($snapshot->margin > 0 && ($snapshot->margin / $snapshot->equity) * 100 > 80) {
                  $highRiskAccounts++;
                }
              }
            }

            $portfolioMarginUsage = $totalEquity > 0 ? (($totalBalance - $totalEquity) / $totalEquity) * 100 : 0;
          @endphp
          <div style="padding: 12px 0;">
            <div style="margin-bottom: 20px;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Portfolio Margin Usage</span>
                <strong style="color: {{ $portfolioMarginUsage > 80 ? '#dc3545' : ($portfolioMarginUsage > 50 ? '#ffc107' : '#28a745') }};">
                  {{ number_format($portfolioMarginUsage, 1) }}%
                </strong>
              </div>
              <div class="progress" style="height: 8px; border-radius: 4px;">
                <div class="progress-bar" style="width: {{ $portfolioMarginUsage }}%; background-color: {{ $portfolioMarginUsage > 80 ? '#dc3545' : ($portfolioMarginUsage > 50 ? '#ffc107' : '#28a745') }}; border-radius: 4px;"></div>
              </div>
            </div>

            <div style="margin-bottom: 20px;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Max Drawdown</span>
                <strong style="color: {{ $maxDrawdown > 20 ? '#dc3545' : '#28a745' }};">{{ number_format($maxDrawdown, 2) }}%</strong>
              </div>
            </div>

            <div style="margin-bottom: 15px;">
              <div class="d-flex justify-content-between">
                <span class="text-muted small">High Risk Accounts</span>
                <strong style="color: {{ $highRiskAccounts > 0 ? '#dc3545' : '#28a745' }};">
                  <span class="badge badge-{{ $highRiskAccounts > 0 ? 'danger' : 'success' }}">{{ $highRiskAccounts }}</span>
                </strong>
              </div>
              <small class="text-muted d-block mt-1">Accounts with >80% margin usage</small>
            </div>

            <div class="alert alert-warning py-2 px-3 small mb-0" style="border-radius: 6px; background: #fffdf0; border: 1px solid #ffc107;">
              <i class="fa fa-info-circle mr-1"></i>
              Keep margin usage below 60% for safer trading
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ACCOUNT EQUITY SUMMARY --}}
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="x_panel shadow-sm">
        <div class="x_title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0; margin: -15px -15px 0 -15px; padding: 15px;">
          <h2 style="color: white; margin: 0;">
            <i class="fa fa-list mr-2"></i> Account Summary
          </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-sm mb-0">
              <thead style="position: sticky; top: 0; background: #f8f9fa;">
                <tr>
                  <th>Account</th>
                  <th class="text-right">Balance</th>
                  <th class="text-right">Equity</th>
                  <th class="text-right">Margin %</th>
                  <th class="text-right">Today P/L</th>
                  <th class="text-center">Bot Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($allAccounts as $acc)
                  @php
                    $snapshot = $acc->snapshots;
                    $dailySummary = \App\Models\DailySummary::where('account_id', $acc->id)
                      ->whereDate('summary_date', today())
                      ->first();
                    $lastStatus = \App\Models\EaStatusChange::where('account_id', $acc->id)
                      ->orderBy('changed_at', 'desc')
                      ->first();
                  @endphp
                  <tr>
                    <td>
                      <strong>#{{ $acc->login }}</strong><br>
                      <small class="text-muted">{{ $acc->platform }} - {{ strtoupper($acc->account_type ?? 'unknown') }}</small>
                    </td>
                    <td class="text-right">
                      <strong>${{ $snapshot ? number_format($snapshot->balance, 2) : '0.00' }}</strong>
                    </td>
                    <td class="text-right">
                      <strong>${{ $snapshot ? number_format($snapshot->equity, 2) : '0.00' }}</strong>
                    </td>
                    <td class="text-right">
                      @php
                        $margin_pct = 0;
                        if($snapshot && $snapshot->equity > 0) {
                          $margin_pct = ($snapshot->margin / $snapshot->equity) * 100;
                        }
                      @endphp
                      <span style="color: {{ $margin_pct > 80 ? '#dc3545' : ($margin_pct > 50 ? '#ffc107' : '#28a745') }}; font-weight: 600;">
                        {{ number_format($margin_pct, 1) }}%
                      </span>
                    </td>
                    <td class="text-right">
                      <strong style="color: {{ ($dailySummary && $dailySummary->daily_pl >= 0) ? '#28a745' : '#dc3545' }};">
                        {{ $dailySummary ? (($dailySummary->daily_pl >= 0 ? '+' : '') . number_format($dailySummary->daily_pl, 2)) : '+0.00' }}
                      </strong>
                    </td>
                    <td class="text-center">
                      @if($lastStatus)
                        <span class="badge badge-{{ in_array($lastStatus->status, ['running', 'active']) ? 'success' : (in_array($lastStatus->status, ['error', 'stopped']) ? 'danger' : 'warning') }}" style="font-size: 11px;">
                          {{ strtoupper(substr($lastStatus->status, 0, 4)) }}
                        </span>
                      @else
                        <span class="badge badge-secondary" style="font-size: 11px;">N/A</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      <i class="fa fa-inbox" style="font-size: 32px; opacity: 0.3;"></i>
                      <p class="mt-2">No accounts connected</p>
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

  
  <div class="row mt-3">
    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>Last Signal for My Accounts</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="alert alert-info mb-0">
            <strong id="lastSignalText">{{ $live['lastSignalText'] ?? '—' }}</strong><br>
            <small class="text-muted">Received: <span id="lastSignalAge">{{ $live['lastSignalAge'] ?? '—' }}</span></small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>Quick Actions</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <a href="{{ route('user.accounts.index') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-link"></i> Manage Accounts
          </a>
          <a href="{{ route('user.signals.index') }}" class="btn btn-info btn-sm">
            <i class="fa fa-bolt"></i> View Signals
          </a>
          <a href="{{ route('user.executions.index') }}" class="btn btn-warning btn-sm">
            <i class="fa fa-check"></i> Execution Logs
          </a>
          <a href="{{ route('user.trades.history') }}" class="btn btn-default btn-sm">
            <i class="fa fa-history"></i> Trade History
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- EXPOSURE --}}
  <div class="row mt-3">
    <div class="col-md-5">
      <div class="x_panel">
        <div class="x_title">
          <h2>Exposure by Symbol</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" style="max-height:280px; overflow:auto;">
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
                <tr><td colspan="3" class="text-center text-muted">No open exposure</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- RECENT SIGNAL EXECUTIONS --}}
    <div class="col-md-7">
      <div class="x_panel">
        <div class="x_title">
          <h2>Recent Signal Executions</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" style="max-height:280px; overflow:auto;">
          <table class="table table-striped table-sm mb-0">
            <thead>
              <tr>
                <th>Time</th>
                <th>Account</th>
                <th>Signal</th>
                <th>Status</th>
                <th>Ticket</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentSignals as $row)
                <tr>
                  <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                  <td>#{{ optional($row->account)->account_id ?? $row->account_id }}</td>
                  <td>
                    @if($row->signal)
                      {{ $row->signal->symbol }} {{ $row->signal->type }} @ {{ $row->signal->entry }}
                    @else
                      Signal #{{ $row->signal_id }}
                    @endif
                  </td>
                  <td>
                    <span class="label label-{{ in_array($row->status,['executed']) ? 'success' : (in_array($row->status,['failed']) ? 'danger' : 'warning') }}">
                      {{ strtoupper($row->status) }}
                    </span>
                  </td>
                  <td>{{ $row->ticket ?? '—' }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-muted">No executions yet</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  {{-- RECENT TRADES --}}
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Recent Trades</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" style="max-height:300px; overflow:auto;">
          <table class="table table-striped table-sm mb-0">
            <thead>
              <tr>
                <th>Time</th>
                <th>Account</th>
                <th>Symbol</th>
                <th>Type</th>
                <th class="text-right">Profit</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentTrades as $t)
                <tr>
                  <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                  <td>{{ $t->account_id }}</td>
                  <td>{{ $t->symbol }}</td>
                  <td>{{ strtoupper($t->type ?? $t->side ?? '-') }}</td>
                  <td class="text-right {{ ($t->profit ?? 0) >= 0 ? 'green' : 'red' }}">
                    {{ number_format($t->profit ?? 0, 2) }}
                  </td>
                  <td>{{ $t->status }}</td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted">No trades yet</td></tr>
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
<script>
async function refreshUserMetrics(){
  try{
    const res = await fetch("{{ route('user.dashboard.metrics') }}", { headers: { 'X-Requested-With':'XMLHttpRequest' }});
    if(!res.ok) return;
    const d = await res.json();

    document.getElementById('todaysProfit').innerText = Number(d.profit ?? 0).toFixed(2) + ' USD';
    document.getElementById('tradesToday').innerText  = d.trades ?? 0;
    document.getElementById('winRate').innerText      = (d.winRate ?? 0) + '%';

    if(d.live){
      document.getElementById('signalQueue').innerText = d.live.signalQueue ?? 0;
      document.getElementById('openPositions').innerText = d.live.openPositions ?? 0;
      document.getElementById('execSuccessRate').innerText = d.live.execSuccessRate ?? '—';

      const fp = Number(d.live.floatingPnL ?? 0);
      const fpEl = document.getElementById('floatingPnL');
      fpEl.innerText = fp.toFixed(2) + ' USD';
      fpEl.classList.remove('green','red');
      fpEl.classList.add(fp >= 0 ? 'green' : 'red');

      // exposure table
      const tbody = document.getElementById('exposureTable');
      if(tbody){
        const rows = (d.live.exposure || []).map(r => `
          <tr>
            <td>${r.symbol}</td>
            <td class="text-right">${Number(r.lots).toFixed(2)}</td>
            <td class="text-right ${Number(r.pnl) >= 0 ? 'green' : 'red'}">${Number(r.pnl).toFixed(2)}</td>
          </tr>
        `).join('');
        tbody.innerHTML = rows || `<tr><td colspan="3" class="text-center text-muted">No open exposure</td></tr>`;
      }
    }
  }catch(e){}
}
refreshUserMetrics();
setInterval(refreshUserMetrics, 5000);
</script>
@endsection
