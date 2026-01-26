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

  {{-- LIVE INFO --}}
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
