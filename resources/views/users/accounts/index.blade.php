@extends('layouts.user')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

  .accp * { box-sizing: border-box; }
  .accp {
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    color: #1e293b;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
  }

  /* ── PAGE HEADER ── */
  .accp-hdr { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
  .accp-hdr h1 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .accp-breadcrumb { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
  .accp-breadcrumb a { color: #6366f1; text-decoration: none; }
  .accp-breadcrumb a:hover { text-decoration: underline; }

  .btn-connect {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff; border: none; border-radius: 8px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    transition: opacity 0.2s; text-decoration: none;
  }
  .btn-connect:hover { opacity: 0.9; color: #fff; }

  /* ── ALERTS ── */
  .accp-alert-success {
    background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 8px;
    padding: 10px 14px; font-size: 12.5px; color: #15803d;
    display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
  }
  .accp-alert-danger {
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;
    padding: 10px 14px; font-size: 12.5px; color: #dc2626; margin-bottom: 14px;
  }

  /* ── MAIN CARD ── */
  .accp-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }

  /* ── FILTER BAR ── */
  .accp-filter {
    background: #fcfcfc; padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .accp-search-wrap { position: relative; flex: 1; min-width: 220px; }
  .accp-search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; }
  .accp-search-input {
    width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12.5px; font-family: 'Inter', sans-serif;
    outline: none; color: #1e293b;
  }
  .accp-search-input:focus { border-color: #6366f1; }
  .accp-stats { display: flex; gap: 20px; }
  .accp-stat { text-align: right; }
  .accp-stat-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .accp-stat-val { font-size: 16px; font-weight: 700; line-height: 1.2; margin-top: 2px; }

  /* ── TABLE ── */
  .accp-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .accp-tbl thead th {
    background: #f8fafc; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; font-weight: 700; padding: 10px 12px; border-bottom: 2px solid #f1f5f9; white-space: nowrap;
  }
  .accp-tbl tbody tr.accp-row { border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.15s; }
  .accp-tbl tbody tr.accp-row:hover { background: #f8f9ff; }
  .accp-tbl tbody tr.accp-row.accp-expanded { background: #f0f4ff; }
  .accp-tbl tbody td { padding: 10px 12px; vertical-align: middle; }

  /* ── ACCOUNT CELL ── */
  .accp-icon {
    width: 38px; height: 38px; border-radius: 9px; flex-shrink: 0;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex; align-items: center; justify-content: center; color: #fff; font-size: 15px;
  }
  .accp-login { font-weight: 700; font-size: 13px; color: #0f172a; }
  .accp-meta { font-size: 10px; color: #94a3b8; margin-top: 2px; }

  /* ── BADGES ── */
  .accp-badge {
    font-size: 9.5px; padding: 3px 9px; border-radius: 12px;
    font-weight: 700; display: inline-flex; align-items: center; gap: 3px;
  }
  .b-demo    { background: #eff6ff; color: #2563eb;  border: 1px solid #bfdbfe; }
  .b-real    { background: #fffbeb; color: #92400e;  border: 1px solid #fde68a; }
  .b-online  { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-offline { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-healthy { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-error   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-stopped { background: #fffbeb; color: #92400e;  border: 1px solid #fde68a; }
  .b-success-sm { background: #f0fdf4; color: #15803d;  border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 600; }
  .b-warn-sm    { background: #fffbeb; color: #92400e;  border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 600; }

  /* ── PROGRESS ── */
  .accp-prog-track { height: 5px; background: #f1f5f9; border-radius: 4px; overflow: hidden; margin: 3px 0; }
  .accp-prog-fill  { height: 100%; border-radius: 4px; }

  /* ── ACTION BUTTONS ── */
  .accp-actions { display: flex; gap: 6px; justify-content: flex-end; }
  .accp-btn {
    width: 30px; height: 30px; border-radius: 7px; border: 1px solid #e2e8f0;
    background: #fff; display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 12px; transition: all 0.15s; text-decoration: none; color: inherit;
  }
  .accp-btn:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.08); }
  .accp-btn.edit:hover  { border-color: #6366f1; color: #6366f1; }
  .accp-btn.del:hover   { border-color: #ef4444; color: #ef4444; }
  .accp-btn.ton:hover   { border-color: #10b981; color: #10b981; }
  .accp-btn.toff:hover  { border-color: #f59e0b; color: #f59e0b; }

  /* ── DETAIL ROW ── */
  .accp-detail-row td { padding: 0; background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
  .accp-detail-inner {
    padding: 20px; display: grid;
    grid-template-columns: repeat(3, 1fr); gap: 14px;
  }
  .accp-detail-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
  .accp-detail-hdr  { padding: 10px 14px; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
  .accp-detail-body { padding: 14px; }
  .accp-dm { margin-bottom: 12px; }
  .accp-dm:last-child { margin-bottom: 0; }
  .accp-dm-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
  .accp-dm-val { font-size: 15px; font-weight: 700; color: #0f172a; }
  .accp-detail-full { grid-column: 1 / -1; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; }
  .accp-detail-full-hdr { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
  .accp-detail-full-body { padding: 14px; display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; }
  .accp-conn-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px; }

  /* ── EMPTY STATE ── */
  .accp-empty { text-align: center; padding: 48px; color: #94a3b8; }
  .accp-empty .ico { font-size: 40px; opacity: 0.3; margin-bottom: 12px; }

  /* ── MISC ── */
  .micro { font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 700; }
  .col-green { color: #10b981 !important; }
  .col-red   { color: #ef4444 !important; }
</style>

<div class="accp">

  {{-- ── HEADER ── --}}
  <div class="accp-hdr">
    <div>
      <h1><i class="fa fa-university mr-2 text-primary"></i> My Trading Accounts</h1>
      <div class="accp-breadcrumb">
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span style="color:#1e293b;">Trading Accounts</span>
      </div>
    </div>
    {{-- @if($accounts->count() === 0 && FALSE) --}}
    <button class="btn-connect" data-bs-toggle="modal" data-bs-target="#addAccountModal">
      <i class="fa fa-plus-circle"></i> Connect Account
    </button>
    {{-- @endif --}}
  </div>

  {{-- ── ALERTS ── --}}
  @if(session('success'))
    <div class="accp-alert-success">
      <i class="fa fa-check-circle" style="font-size:15px;"></i>
      <div><strong>Success!</strong> {{ session('success') }}</div>
    </div>
  @endif

  @if($errors->any())
    <div class="accp-alert-danger">
      <div style="display:flex;align-items:flex-start;gap:10px;">
        <i class="fa fa-exclamation-circle" style="font-size:15px;margin-top:2px;"></i>
        <div>
          <strong>Validation Errors:</strong>
          <ul style="margin:6px 0 0 18px;padding:0;">
            @foreach($errors->all() as $error)
              <li style="font-size:12px;">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- ── MAIN CARD ── --}}
  <div class="accp-card">

    {{-- Filter Bar --}}
    <div class="accp-filter">
      <div class="accp-search-wrap">
        <i class="fa fa-search accp-search-icon"></i>
        <input type="text" id="accpSearch" class="accp-search-input"
               placeholder="Search by login, platform, server...">
      </div>
      <div class="accp-stats">
        <div class="accp-stat">
          <div class="accp-stat-lbl">Total Accounts</div>
          <div class="accp-stat-val" style="color:#0f172a;">{{ $accounts->count() }}</div>
        </div>
        <div class="accp-stat">
          <div class="accp-stat-lbl">Active Accounts</div>
          <div class="accp-stat-val col-green">{{ $accounts->where('active',true)->count() }}</div>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
      <table class="accp-tbl" id="accpTable">
        <thead>
          <tr>
            <th style="width:44px;">#</th>
            <th>Account &amp; Details</th>
            <th style="text-align:center;">Type</th>
            <th>Platform Info</th>
            <th style="text-align:center;">Balance / Equity</th>
            <th style="text-align:center;">Connection</th>
            <th style="text-align:center;">Health</th>
            <th style="text-align:right;padding-right:16px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($accounts as $key => $acc)
            @php
              $meta         = $acc->meta ?? [];
              $snapshot     = $acc->snapshots ?? null;
              $balance      = $snapshot ? $snapshot->balance : 0;
              $equity       = $snapshot ? $snapshot->equity  : 0;
              $margin       = $snapshot ? $snapshot->margin  : 0;
              $marginUsage  = ($equity > 0 && $margin > 0) ? min(100, ($margin / $equity) * 100) : 0;
              $marginColor  = $marginUsage > 80 ? '#ef4444' : ($marginUsage > 50 ? '#f59e0b' : '#10b981');

              $lastStatus   = \App\Models\EaStatusChange::where('account_id', $acc->id)
                                ->orderBy('changed_at','desc')->first();
              $isHealthy    = !($lastStatus && in_array($lastStatus->status, ['stopped','error','paused']));
              $healthText   = $lastStatus ? strtoupper($lastStatus->status) : 'HEALTHY';
              $healthClass  = $isHealthy ? 'b-healthy' : (($lastStatus && $lastStatus->status === 'stopped') ? 'b-stopped' : 'b-error');

              $statusColor  = $acc->active ? 'toff' : 'ton';
              $statusTitle  = $acc->active ? 'Deactivate' : 'Activate';
              
              // Check payment status
              $subscription = \App\Models\UserSubscription::where('user_id', Auth::id())->first();
              $paidSubscription = null;
              $unpaidWeekly = false;
              $actionsDisabled = false;
              
              if ($subscription && $subscription->plan) {
                $paidSubscription = \App\Models\PaymentTransaction::where('user_id', Auth::id())
                  ->where('subscription_plan_id', $subscription->plan->id)
                  ->whereIn('status', ['paid', 'success'])
                  ->first();
              }
              
              $unpaidWeekly = \App\Models\WeeklyPayment::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->where('week_end', '<', now())
                ->exists();
              
              // Disable buttons if no paid subscription OR unpaid weekly payments
              if (!$paidSubscription || $unpaidWeekly) {
                $actionsDisabled = true;
              }
            @endphp

            {{-- Main Row --}}
            <tr class="accp-row" data-id="{{ $acc->id }}">
              <td>
                <div style="width:30px;height:30px;border-radius:50%;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#64748b;">
                  {{ $key + 1 }}
                </div>
              </td>

              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <div class="accp-icon"><i class="fa fa-briefcase"></i></div>
                  <div>
                    <div class="accp-login">{{ $acc->login }}</div>
                    <div class="accp-meta">
                      @if(!empty($meta['currency']))
                        <i class="fa fa-dollar mr-1"></i>{{ $meta['currency'] }}
                      @endif
                      @if(!empty($meta['currency']) && !empty($meta['leverage']))
                        &nbsp;•&nbsp;
                      @endif
                      @if(!empty($meta['leverage']))
                        <i class="fa fa-bolt mr-1"></i>{{ $meta['leverage'] }} Leverage
                      @endif
                    </div>
                  </div>
                </div>
              </td>

              <td style="text-align:center;">
                @if(strtoupper($acc->account_type ?? '') === 'DEMO')
                  <span class="accp-badge b-demo"><i class="fa fa-flask"></i> DEMO</span>
                @else
                  <span class="accp-badge b-real"><i class="fa fa-shield"></i> REAL</span>
                @endif
              </td>

              <td>
                <div style="display:flex;align-items:center;gap:8px;">
                  <i class="fa fa-windows" style="font-size:14px;color:{{ strtoupper($acc->platform)==='MT5'?'#06b6d4':'#64748b' }};"></i>
                  <div>
                    <div style="font-weight:700;font-size:12px;">{{ strtoupper($acc->platform) }}</div>
                    <div style="font-size:10px;color:#94a3b8;"><i class="fa fa-server mr-1"></i>{{ $acc->server }}</div>
                  </div>
                </div>
              </td>

              <td style="text-align:center;">
                <div style="font-weight:700;font-size:13px;color:#0f172a;">${{ number_format($balance,2) }}</div>
                <div style="font-size:10px;color:#64748b;margin:2px 0;">Equity: {{ number_format($equity,2) }}</div>
                <div class="accp-prog-track">
                  <div class="accp-prog-fill" style="width:{{ $marginUsage }}%;background:{{ $marginColor }};"></div>
                </div>
                <div style="font-size:10px;font-weight:600;color:{{ $marginColor }};">{{ number_format($marginUsage,1) }}%</div>
              </td>

              <td style="text-align:center;">
                @if($acc->active)
                  <span class="accp-badge b-online"><i class="fa fa-circle" style="font-size:7px;"></i> ONLINE</span>
                @else
                  <span class="accp-badge b-offline"><i class="fa fa-circle" style="font-size:7px;"></i> OFFLINE</span>
                @endif
              </td>

              <td style="text-align:center;">
                <span class="accp-badge {{ $healthClass }}" style="cursor: pointer;" 
                      data-bs-toggle="modal" data-bs-target="#healthModal{{ $acc->id }}"
                      title="Click to view health details">
                  <i class="fa {{ $isHealthy?'fa-heartbeat':'fa-exclamation-triangle' }}" style="font-size:8px;"></i>
                  {{ $healthText }}
                </span>
              </td>

              <td style="text-align:right;padding-right:16px;">
                <div class="accp-actions" onclick="event.stopPropagation()" @if($actionsDisabled) title="Complete payment to manage accounts" @endif>
                  {{-- Edit --}}
                  <button class="accp-btn edit accp-edit-btn" title="Edit Account"
                          data-id="{{ $acc->id }}"
                          data-login="{{ $acc->login }}"
                          data-platform="{{ $acc->platform }}"
                          data-server="{{ $acc->server }}"
                          data-type="{{ $acc->account_type }}"
                          data-currency="{{ $meta['currency'] ?? '' }}"
                          data-leverage="{{ $meta['leverage'] ?? '' }}"
                          data-bs-toggle="modal" data-bs-target="#editAccountModal"
                          @if($actionsDisabled) disabled style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" @endif>
                    <i class="fa fa-edit"></i>
                  </button>

                  {{-- Toggle --}}
                  <a class="accp-btn {{ $statusColor }} accp-toggle-btn"
                     href="{{ route('user.accounts.activate', $acc->login) }}"
                     title="{{ $statusTitle }} Account"
                     data-active="{{ $acc->active ? '1' : '0' }}"
                     @if($actionsDisabled) style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;" @endif>
                    <i class="fa fa-power-off"></i>
                  </a>

                  {{-- Delete --}}
                  <button type="button" class="accp-btn del accp-delete-btn" title="Delete Account"
                          data-form-action="{{ route('user.accounts.destroy', $acc->id) }}"
                          data-csrf="{{ csrf_token() }}"
                          @if($actionsDisabled) disabled style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" @endif>
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>

            {{-- Expandable Detail Row --}}
            <tr class="accp-detail-row" id="accp-detail-{{ $acc->id }}" style="display:none;">
              <td colspan="8">
                <div class="accp-detail-inner">

                  {{-- Account Metrics --}}
                  <div class="accp-detail-card">
                    <div class="accp-detail-hdr" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                      <i class="fa fa-chart-bar mr-2"></i> Account Metrics
                    </div>
                    <div class="accp-detail-body">
                      <div class="accp-dm"><div class="accp-dm-lbl">Balance</div><div class="accp-dm-val">{{ $snapshot ? '$'.number_format($snapshot->balance,2) : 'N/A' }}</div></div>
                      <div class="accp-dm"><div class="accp-dm-lbl">Equity</div><div class="accp-dm-val">{{ $snapshot ? '$'.number_format($snapshot->equity,2) : 'N/A' }}</div></div>
                      <div class="accp-dm"><div class="accp-dm-lbl">Free Margin</div><div class="accp-dm-val">{{ $snapshot ? '$'.number_format($snapshot->free_margin,2) : 'N/A' }}</div></div>
                      <div class="accp-dm">
                        <div class="accp-dm-lbl">Max Drawdown</div>
                        <div class="accp-dm-val" style="color:{{ ($snapshot && $snapshot->drawdown > 20)?'#ef4444':'#10b981' }};">
                          {{ ($snapshot && $snapshot->drawdown) ? number_format($snapshot->drawdown,2).'%' : 'N/A' }}
                        </div>
                      </div>
                    </div>
                  </div>

                  {{-- Trading Activity --}}
                  <div class="accp-detail-card">
                    <div class="accp-detail-hdr" style="background:linear-gradient(135deg,#f472b6,#ec4899);">
                      <i class="fa fa-tachometer mr-2"></i> Trading Activity
                    </div>
                    <div class="accp-detail-body">
                      @php
                        $openPos     = \App\Models\PositionUpdate::where('account_id',$acc->id)->count();
                        $dailyTrades = \App\Models\TradeLog::where('account_id',$acc->id)->whereDate('created_at',today())->count();
                        $dailySummary= \App\Models\DailySummary::where('account_id',$acc->id)->whereDate('summary_date',today())->first();
                      @endphp
                      <div class="accp-dm"><div class="accp-dm-lbl">Open Positions</div><div class="accp-dm-val">{{ $openPos }}</div></div>
                      <div class="accp-dm"><div class="accp-dm-lbl">Today's Trades</div><div class="accp-dm-val">{{ $dailyTrades }}</div></div>
                      <div class="accp-dm">
                        <div class="accp-dm-lbl">Today's P/L</div>
                        <div class="accp-dm-val" style="color:{{ ($dailySummary && $dailySummary->daily_pl>=0)?'#10b981':'#ef4444' }};">
                          {{ $dailySummary ? '$'.number_format($dailySummary->daily_pl,2) : 'N/A' }}
                        </div>
                      </div>
                      <div class="accp-dm"><div class="accp-dm-lbl">Win Rate (Today)</div><div class="accp-dm-val">{{ $dailySummary ? number_format($dailySummary->win_rate_percent,1) : 'N/A' }}%</div></div>
                    </div>
                  </div>

                  {{-- Bot Status --}}
                  <div class="accp-detail-card">
                    <div class="accp-detail-hdr" style="background:linear-gradient(135deg,#38bdf8,#0284c7);">
                      <i class="fa fa-robot mr-2"></i> Bot Status
                    </div>
                    <div class="accp-detail-body">
                      <div class="accp-dm">
                        <div class="accp-dm-lbl">Bot State</div>
                        @if($lastStatus)
                          @php
                            $bClass = in_array($lastStatus->status,['running','active']) ? 'b-online' : (in_array($lastStatus->status,['stopped','error']) ? 'b-error' : 'b-stopped');
                          @endphp
                          <span class="accp-badge {{ $bClass }}">{{ strtoupper($lastStatus->status) }}</span>
                        @else
                          <span style="color:#94a3b8;font-size:12px;">Unknown</span>
                        @endif
                      </div>
                      @if($lastStatus)
                        <div class="accp-dm">
                          <div class="accp-dm-lbl">Consecutive Losses</div>
                          <div class="accp-dm-val" style="color:{{ $lastStatus->consecutive_losses>3?'#ef4444':'#10b981' }};">{{ $lastStatus->consecutive_losses }}</div>
                        </div>
                        <div class="accp-dm">
                          <div class="accp-dm-lbl">Open Positions (Bot)</div>
                          <div class="accp-dm-val">{{ $lastStatus->positions_open }}</div>
                        </div>
                        <div class="accp-dm">
                          <div class="accp-dm-lbl">Last Changed</div>
                          <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $lastStatus->changed_at?$lastStatus->changed_at->diffForHumans():'N/A' }}</div>
                        </div>
                      @else
                        <p style="color:#94a3b8;font-size:12px;margin:0;">No status data available yet</p>
                      @endif
                    </div>
                  </div>

                  {{-- Connection Details --}}
                  <div class="accp-detail-full">
                    <div class="accp-detail-full-hdr"><i class="fa fa-history mr-2"></i>Connection Details &amp; Status History</div>
                    <div class="accp-detail-full-body">
                      <div>
                        <div class="accp-conn-lbl">Connection Status</div>
                        <span class="accp-badge {{ $acc->active?'b-online':'b-offline' }}">
                          {{ $acc->active?'🟢 Active':'🔴 Inactive' }}
                        </span>
                      </div>
                      <div>
                        <div class="accp-conn-lbl">Active Status</div>
                        <span class="accp-badge {{ $acc->active?'b-healthy':'b-stopped' }}">
                          {{ $acc->active?'✓ Active':'✗ Inactive' }}
                        </span>
                      </div>
                      <div>
                        <div class="accp-conn-lbl">Verification</div>
                        <span class="accp-badge {{ $acc->is_verified?'b-healthy':'b-stopped' }}">
                          {{ $acc->is_verified?'✓ Verified':'⏳ Pending' }}
                        </span>
                      </div>
                      <div>
                        <div class="accp-conn-lbl">Last Updated</div>
                        <span style="font-size:12px;font-weight:500;color:#0f172a;">{{ $acc->updated_at->diffForHumans() }}</span>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>

            <!-- Health Details Modal -->
            <div class="modal fade" id="healthModal{{ $acc->id }}" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
                  <div class="modal-header" style="background:linear-gradient(135deg,#38bdf8,#0284c7);color:#fff;border:none;border-radius:14px 14px 0 0;">
                    <h5 class="modal-title" style="font-weight:700;"><i class="fa fa-heartbeat mr-2"></i>Bot Health & Validation Report - {{ $acc->login }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body p-4">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                      <!-- Bot Running State -->
                      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 8px;">
                          <i class="fa fa-play-circle mr-1"></i> Bot Running State
                        </div>
                        @if($lastStatus)
                          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                            @php
                              $isRunning = in_array($lastStatus->status, ['running', 'active']);
                              $stateColor = $isRunning ? '#10b981' : '#ef4444';
                              $stateIcon = $isRunning ? 'fa-check-circle' : 'fa-times-circle';
                            @endphp
                            <i class="fa {{ $stateIcon }}" style="color: {{ $stateColor }}; font-size: 18px;"></i>
                            <span style="font-size: 16px; font-weight: 700; color: {{ $stateColor }};">
                              {{ strtoupper($lastStatus->status) }}
                            </span>
                          </div>
                          <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                            <strong>Status Message:</strong> Bot is currently <strong>{{ $isRunning ? 'ACTIVE & RUNNING' : strtoupper($lastStatus->status) }}</strong>
                          </div>
                          <div style="font-size: 11px; color: #94a3b8;">
                            Last changed: <strong>{{ $lastStatus->changed_at->diffForHumans() }}</strong>
                          </div>
                        @else
                          <div style="color: #94a3b8; font-size: 12px;">No status data available</div>
                        @endif
                      </div>

                      <!-- Health Status -->
                      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 8px;">
                          <i class="fa fa-heartbeat mr-1"></i> Overall Health
                        </div>
                        @php
                          $healthColor = $isHealthy ? '#10b981' : '#ef4444';
                          $healthIcon = $isHealthy ? 'fa-check-circle' : 'fa-exclamation-triangle';
                        @endphp
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                          <i class="fa {{ $healthIcon }}" style="color: {{ $healthColor }}; font-size: 18px;"></i>
                          <span style="font-size: 16px; font-weight: 700; color: {{ $healthColor }};">
                            {{ $isHealthy ? 'HEALTHY' : 'UNHEALTHY' }}
                          </span>
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                          <strong>Status:</strong> Bot health is currently <strong>{{ $isHealthy ? 'GOOD ✓' : 'PROBLEMATIC ✗' }}</strong>
                        </div>
                      </div>
                    </div>

                    <!-- Detailed Validation Matrix -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                      <div style="font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                        <i class="fa fa-check-list mr-2"></i> Validation Checklist
                      </div>
                      <div style="display: grid; gap: 10px;">
                        <!-- Connection -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border-radius: 8px; border-left: 3px solid {{ $acc->active ? '#10b981' : '#ef4444' }};">
                          <span style="font-size: 12px; color: #1e293b;">
                            <i class="fa {{ $acc->active ? 'fa-check-circle' : 'fa-times-circle' }}" style="color: {{ $acc->active ? '#10b981' : '#ef4444' }}; margin-right: 6px;"></i>
                            Connection Status
                          </span>
                          <span style="font-size: 11px; font-weight: 700; color: {{ $acc->active ? '#10b981' : '#ef4444' }};">
                            {{ $acc->active ? '✓ ACTIVE' : '✗ OFFLINE' }}
                          </span>
                        </div>

                        <!-- Verification -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border-radius: 8px; border-left: 3px solid {{ $acc->is_verified ? '#10b981' : '#f59e0b' }};">
                          <span style="font-size: 12px; color: #1e293b;">
                            <i class="fa {{ $acc->is_verified ? 'fa-check-circle' : 'fa-clock' }}" style="color: {{ $acc->is_verified ? '#10b981' : '#f59e0b' }}; margin-right: 6px;"></i>
                            Account Verification
                          </span>
                          <span style="font-size: 11px; font-weight: 700; color: {{ $acc->is_verified ? '#10b981' : '#f59e0b' }};">
                            {{ $acc->is_verified ? '✓ VERIFIED' : '⏳ PENDING' }}
                          </span>
                        </div>

                        <!-- Bot Running -->
                        @php
                          $botRunning = $lastStatus && in_array($lastStatus->status, ['running', 'active']);
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border-radius: 8px; border-left: 3px solid {{ $botRunning ? '#10b981' : '#ef4444' }};">
                          <span style="font-size: 12px; color: #1e293b;">
                            <i class="fa {{ $botRunning ? 'fa-check-circle' : 'fa-times-circle' }}" style="color: {{ $botRunning ? '#10b981' : '#ef4444' }}; margin-right: 6px;"></i>
                            Bot Running
                          </span>
                          <span style="font-size: 11px; font-weight: 700; color: {{ $botRunning ? '#10b981' : '#ef4444' }};">
                            {{ $botRunning ? '✓ RUNNING' : '✗ STOPPED' }}
                          </span>
                        </div>

                        <!-- Consecutive Losses -->
                        @php
                          $lossesOk = !$lastStatus || $lastStatus->consecutive_losses <= 3;
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fff; border-radius: 8px; border-left: 3px solid {{ $lossesOk ? '#10b981' : '#ef4444' }};">
                          <span style="font-size: 12px; color: #1e293b;">
                            <i class="fa {{ $lossesOk ? 'fa-check-circle' : 'fa-exclamation-circle' }}" style="color: {{ $lossesOk ? '#10b981' : '#ef4444' }}; margin-right: 6px;"></i>
                            Consecutive Losses
                          </span>
                          <span style="font-size: 11px; font-weight: 700; color: {{ $lossesOk ? '#10b981' : '#ef4444' }};">
                            {{ $lastStatus ? $lastStatus->consecutive_losses : 0 }} {{ $lossesOk ? '✓ OK' : '⚠️ HIGH' }}
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Summary -->
                    @php
                      $validationScore = 0;
                      if ($acc->active) $validationScore += 25;
                      if ($acc->is_verified) $validationScore += 25;
                      if ($botRunning) $validationScore += 25;
                      if ($lossesOk) $validationScore += 25;
                    @endphp
                    <div style="background: {{ $validationScore === 100 ? '#f0fdf4' : ($validationScore >= 75 ? '#fffbeb' : '#fef2f2') }}; border: 1px solid {{ $validationScore === 100 ? '#a7f3d0' : ($validationScore >= 75 ? '#fde68a' : '#fecaca') }}; border-radius: 10px; padding: 16px; text-align: center;">
                      <div style="font-size: 12px; color: #64748b; margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Validation Score</div>
                      <div style="font-size: 32px; font-weight: 700; color: {{ $validationScore === 100 ? '#10b981' : ($validationScore >= 75 ? '#f59e0b' : '#ef4444') }};">
                        {{ $validationScore }}%
                      </div>
                      <div style="font-size: 12px; color: #64748b; margin-top: 8px;">
                        {{ $validationScore === 100 ? '✓ All systems operational' : ($validationScore >= 75 ? '⚠️ Minor issues detected' : '✗ Critical issues found') }}
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                  </div>
                </div>
              </div>
            </div>

          @empty
            <tr>
              <td colspan="8">
                <div class="accp-empty">
                  <div class="ico"><i class="fa fa-inbox"></i></div>
                  <p style="font-size:15px;font-weight:600;margin-bottom:6px;color:#475569;">No trading accounts connected yet</p>
                  <p style="font-size:12px;margin:0;">Click the "Connect Account" button to add your first trading account</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>{{-- end main card --}}

</div>{{-- end .accp --}}


{{-- ── ADD MODAL ── --}}
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('user.accounts.store') }}"
          class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      @csrf
      <div class="modal-header" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;border-radius:14px 14px 0 0;">
        <h5 class="modal-title" style="font-weight:700;"><i class="fa fa-plus-circle mr-2"></i>Connect New Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div id="addPlatformWarning" class="alert alert-warning border-0 d-none mb-3" role="alert" style="border-radius:8px;padding:10px 12px;font-size:12px;">
          <i class="fa fa-exclamation-circle mr-2"></i>
          <strong>Unsupported Platform</strong> — We currently only support MetaTrader 5 (MT5). Please select MT5 to continue.
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
              <span style="color:#dc2626;">*</span> Trading Platform
              <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
            </label>
            <select class="form-control addPlatformSelect" name="platform" required style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
              <option value="MT5" selected>MetaTrader 5 (MT5)</option>
              <option value="MT4">MetaTrader 4 (MT4)</option>
              <option value="cTrader">cTrader</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
              <span style="color:#dc2626;">*</span> Account Type
              <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
            </label>
            <select class="form-control" name="account_type" required style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
              <option value="">Select Type</option>
              <option value="Real">Real Account</option>
              <option value="Demo">Demo Account</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
            <span style="color:#dc2626;">*</span> Server Address
            <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
          </label>
          <input class="form-control" name="server" required placeholder="e.g., FBS-Real or FBS-Demo" value="{{ old('server') }}" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          <small style="font-size:11px;color:#10b981;font-weight:500;">
            <i class="fa fa-check-circle" style="color:#10b981;margin-right:4px;"></i>
            Examples: FBS-Real, FBS-Demo, FBS-Real-MetaTrader 5. Check your broker's account details.
          </small>
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Account Login</label>
          <input class="form-control" name="login" required placeholder="e.g., 123456789" value="{{ old('login') }}" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Password</label>
          <input class="form-control" name="password" type="password" required placeholder="Account password" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          <small class="text-muted" style="font-size:11px;"><i class="fa fa-shield mr-1"></i>Encrypted and secure — never displayed in the UI.</small>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Currency (Optional)</label>
            <input class="form-control" name="meta[currency]" placeholder="e.g., USD" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          </div>
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Leverage (Optional)</label>
            <input class="form-control" name="meta[leverage]" placeholder="e.g., 1:500" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          </div>
        </div>
      </div>
      <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #f1f5f9;border-radius:0 0 14px 14px;">
        <button class="btn btn-light" data-bs-dismiss="modal" type="button" style="border-radius:8px;font-size:13px;">Cancel</button>
        <button class="btn text-white" type="submit" style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:8px;padding:8px 24px;font-weight:600;font-size:13px;">
          <i class="fa fa-check mr-1"></i> Connect Account
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ── EDIT MODAL ── --}}
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" id="accpEditForm" class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      @csrf @method('PUT')
      <div class="modal-header" style="background:linear-gradient(135deg,#0891b2,#0e7490);color:#fff;border:none;border-radius:14px 14px 0 0;">
        <h5 class="modal-title" style="font-weight:700;"><i class="fa fa-edit mr-2"></i>Edit Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div id="editPlatformWarning" class="alert alert-warning border-0 d-none mb-3" role="alert" style="border-radius:8px;padding:10px 12px;font-size:12px;">
          <i class="fa fa-exclamation-circle mr-2"></i>
          <strong>Unsupported Platform</strong> — We currently only support MetaTrader 5 (MT5). Please select MT5 to continue.
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
              <span style="color:#dc2626;">*</span> Trading Platform
              <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
            </label>
            <select class="form-control editPlatformSelect" name="platform" id="accpEditPlatform" required style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
              <option value="MT5" selected>MetaTrader 5 (MT5)</option>
              <option value="MT4">MetaTrader 4 (MT4)</option>
              <option value="cTrader">cTrader</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
              <span style="color:#dc2626;">*</span> Account Type
              <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
            </label>
            <select class="form-control" name="account_type" id="accpEditType" required style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
              <option value="">Select Type</option>
              <option value="Real">Real Account</option>
              <option value="Demo">Demo Account</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
            <span style="color:#dc2626;">*</span> Server Address
            <span style="font-size:10px;color:#ef4444;font-weight:700;">(REQUIRED)</span>
          </label>
          <input class="form-control" name="server" id="accpEditServer" required placeholder="e.g., FBS-Real or FBS-Demo" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          <small style="font-size:11px;color:#10b981;font-weight:500;">
            <i class="fa fa-check-circle" style="color:#10b981;margin-right:4px;"></i>
            Examples: FBS-Real, FBS-Demo, FBS-Real-MetaTrader 5. Check your broker's account details.
          </small>
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Account Login</label>
          <input class="form-control" id="accpEditLogin" disabled style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:#f8fafc;">
          <small class="text-muted" style="font-size:11px;"><i class="fa fa-info-circle mr-1"></i>Login cannot be changed. Remove and reconnect if needed.</small>
        </div>
        <div class="mb-3">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Password (Optional)</label>
          <input class="form-control" name="password" type="password" placeholder="Leave blank to keep current" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          <small class="text-muted" style="font-size:11px;">Only fill this if you want to update the password</small>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Currency</label>
            <input class="form-control" name="meta[currency]" id="accpEditCurrency" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          </div>
          <div class="col-md-6 mb-3">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Leverage</label>
            <input class="form-control" name="meta[leverage]" id="accpEditLeverage" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
          </div>
        </div>
      </div>
      <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #f1f5f9;border-radius:0 0 14px 14px;">
        <button class="btn btn-light" data-bs-dismiss="modal" type="button" style="border-radius:8px;font-size:13px;">Cancel</button>
        <button class="btn text-white" type="submit" style="background:linear-gradient(135deg,#0891b2,#0e7490);border:none;border-radius:8px;padding:8px 24px;font-weight:600;font-size:13px;">
          <i class="fa fa-save mr-1"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ── CONFIRM TOGGLE MODAL ── --}}
<div class="modal fade" id="accpToggleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div class="modal-header" style="background:linear-gradient(135deg,#fb923c,#f97316);border:none;border-radius:14px 14px 0 0;">
        <h5 class="modal-title text-white" style="font-weight:700;"><i class="fa fa-exclamation-triangle mr-2"></i>Confirm Action</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <div style="font-size:46px;color:#f59e0b;margin-bottom:14px;"><i class="fa fa-power-off"></i></div>
        <h5 id="accpToggleTitle" style="font-weight:700;margin-bottom:8px;"></h5>
        <p id="accpToggleText" class="text-muted mb-0" style="font-size:13px;"></p>
      </div>
      <div class="modal-footer justify-content-center" style="background:#f8fafc;border-top:1px solid #f1f5f9;border-radius:0 0 14px 14px;">
        <button class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
        <button id="accpToggleConfirm" class="btn text-white px-4"
                style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:8px;font-weight:600;">
          Confirm
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── DELETE CONFIRMATION MODAL ── --}}
<div class="modal fade" id="accpDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div class="modal-header" style="background:linear-gradient(135deg,#ef4444,#dc2626);border:none;border-radius:14px 14px 0 0;">
        <h5 class="modal-title text-white" style="font-weight:700;"><i class="fa fa-trash mr-2"></i>Delete Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <div style="font-size:46px;color:#ef4444;margin-bottom:14px;"><i class="fa fa-exclamation-circle"></i></div>
        <h5 style="font-weight:700;margin-bottom:8px;">Remove this account?</h5>
        <p class="text-muted mb-0" style="font-size:13px;">This action cannot be undone. The account and all associated data will be permanently removed.</p>
      </div>
      <div class="modal-footer justify-content-center" style="background:#f8fafc;border-top:1px solid #f1f5f9;border-radius:0 0 14px 14px;">
        <button class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
        <button id="accpDeleteConfirm" class="btn text-white px-4"
                style="background:linear-gradient(135deg,#ef4444,#dc2626);border:none;border-radius:8px;font-weight:600;">
          Delete
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  (function () {
    'use strict';

    /* ── PLATFORM VALIDATION ── */
    var addPlatformSelect = document.querySelector('.addPlatformSelect');
    var addPlatformWarning = document.getElementById('addPlatformWarning');
    var addAccountForm = document.querySelector('form[action="{{ route("user.accounts.store") }}"]');
    
    var editPlatformSelect = document.querySelector('.editPlatformSelect');
    var editPlatformWarning = document.getElementById('editPlatformWarning');
    var editAccountForm = document.getElementById('accpEditForm');

    function validatePlatform(platformValue, warningEl) {
      if (platformValue === 'MT4' || platformValue === 'cTrader') {
        warningEl.classList.remove('d-none');
        return false;
      } else {
        warningEl.classList.add('d-none');
        return true;
      }
    }

    // Add Modal Platform Change
    if (addPlatformSelect) {
      addPlatformSelect.addEventListener('change', function () {
        validatePlatform(this.value, addPlatformWarning);
      });
    }

    // Edit Modal Platform Change
    if (editPlatformSelect) {
      editPlatformSelect.addEventListener('change', function () {
        validatePlatform(this.value, editPlatformWarning);
      });
    }

    // Add Modal Form Submit Prevention
    if (addAccountForm) {
      addAccountForm.addEventListener('submit', function (e) {
        var platform = addPlatformSelect ? addPlatformSelect.value : null;
        if (platform && (platform === 'MT4' || platform === 'cTrader')) {
          e.preventDefault();
          addPlatformWarning.classList.remove('d-none');
          addPlatformSelect.focus();
          return false;
        }
      });
    }

    // Edit Modal Form Submit Prevention
    if (editAccountForm) {
      editAccountForm.addEventListener('submit', function (e) {
        var platform = editPlatformSelect ? editPlatformSelect.value : null;
        if (platform && (platform === 'MT4' || platform === 'cTrader')) {
          e.preventDefault();
          editPlatformWarning.classList.remove('d-none');
          editPlatformSelect.focus();
          return false;
        }
      });
    }

    /* ── EXPANDABLE ROWS ── */
  document.querySelectorAll('.accp-row').forEach(function (row) {
    row.addEventListener('click', function (e) {
      if (e.target.closest('.accp-actions')) return;
      var id     = this.dataset.id;
      var detail = document.getElementById('accp-detail-' + id);
      if (!detail) return;
      var isOpen = detail.style.display !== 'none';
      document.querySelectorAll('.accp-detail-row').forEach(function (d) { d.style.display = 'none'; });
      document.querySelectorAll('.accp-row').forEach(function (r) { r.classList.remove('accp-expanded'); });
      if (!isOpen) {
        detail.style.display = 'table-row';
        this.classList.add('accp-expanded');
      }
    });
  });

  /* ── SEARCH ── */
  var searchInput = document.getElementById('accpSearch');
  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      var q = this.value.toLowerCase();
      document.querySelectorAll('#accpTable tbody tr.accp-row').forEach(function (tr) {
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

  /* ── EDIT MODAL POPULATE ── */
  document.querySelectorAll('.accp-edit-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.getElementById('accpEditPlatform').value = this.dataset.platform || 'MT5';
      document.getElementById('accpEditServer').value   = this.dataset.server   || '';
      document.getElementById('accpEditLogin').value    = this.dataset.login    || '';
      document.getElementById('accpEditType').value     = this.dataset.type     || '';
      document.getElementById('accpEditCurrency').value = this.dataset.currency || '';
      document.getElementById('accpEditLeverage').value = this.dataset.leverage || '';
      document.getElementById('accpEditForm').action    = "{{ url('user/accounts') }}/" + this.dataset.id;
      
      // Show warning if existing account has unsupported platform
      var platformValue = this.dataset.platform || 'MT5';
      if (platformValue === 'MT4' || platformValue === 'cTrader') {
        document.getElementById('editPlatformWarning').classList.remove('d-none');
      } else {
        document.getElementById('editPlatformWarning').classList.add('d-none');
      }
    });
  });

  /* ── TOGGLE CONFIRMATION ── */
  var toggleHref    = null;
  var toggleModalEl = document.getElementById('accpToggleModal');
  // Create ONE cached instance - reusing it prevents backdrop stacking
  var toggleModal   = toggleModalEl ? new bootstrap.Modal(toggleModalEl) : null;

  document.querySelectorAll('.accp-toggle-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      // stopImmediatePropagation stops ALL listeners on this element AND bubbling
      // This prevents the row-expand tr listener from firing
      e.stopImmediatePropagation();
      if (!toggleModal) return;

      toggleHref = this.getAttribute('href');
      var isDeactivate = this.dataset.active === '1';

      document.getElementById('accpToggleTitle').innerText = isDeactivate
        ? 'Deactivate Trading Account?'
        : 'Activate Trading Account?';
      document.getElementById('accpToggleText').innerText = isDeactivate
        ? 'The trading bot and connection will be stopped for this account.'
        : 'The system will reconnect the trading bot to this account.';

      toggleModal.show();
    });
  });

  document.getElementById('accpToggleConfirm').addEventListener('click', function () {
    if (!toggleHref) return;
    var href = toggleHref;
    toggleHref = null; // clear immediately so double-clicks can't fire twice
    // hide() properly removes the backdrop before we navigate
    toggleModal.hide();
    // Wait for Bootstrap's hide animation to fully complete, THEN navigate
    // {once: true} auto-removes this listener after it fires once
    toggleModalEl.addEventListener('hidden.bs.modal', function () {
      window.location.href = href;
    }, { once: true });
  });

  /* ── DELETE CONFIRMATION ── */
  var deleteFormAction = null;
  var deleteCsrf = null;
  var deleteModalEl = document.getElementById('accpDeleteModal');
  var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

  document.querySelectorAll('.accp-delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      if (!deleteModal) return;
      deleteFormAction = this.getAttribute('data-form-action');
      deleteCsrf = this.getAttribute('data-csrf');
      deleteModal.show();
    });
  });

  var deleteConfirmBtn = document.getElementById('accpDeleteConfirm');
  if (deleteConfirmBtn) {
    deleteConfirmBtn.addEventListener('click', function () {
      if (!deleteFormAction || !deleteCsrf) return;
      var formAction = deleteFormAction;
      var csrf = deleteCsrf;
      deleteFormAction = null;
      deleteCsrf = null;
      deleteModal.hide();
      deleteModalEl.addEventListener('hidden.bs.modal', function () {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = formAction;
        form.style.display = 'none';
        form.innerHTML = '<input type="hidden" name="_token" value="' + csrf + '"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
      }, { once: true });
    });
  }

  })();
});
</script>
@endpush