@extends('layouts.admin')

@section('title', 'AI Intelligence Center — ' . config('app.name'))

@push('styles')
<style>
/* ════════════════════════════════════════════════════
   VIOMIA AI TRADING DASHBOARD
   All backgrounds use explicit hex + !important
   to beat Gentelella's custom.min.css overrides
   ════════════════════════════════════════════════════ */

/* ── Force dark page context ── */
.right_col {
    background-color: #0a0e17 !important;
    padding: 20px 24px !important;
    min-height: 100vh;
}

/* ── Reset Gentelella body color for this page ── */
.right_col *,
.right_col *::before,
.right_col *::after {
    box-sizing: border-box;
}

/* ── COLOR TOKENS (also as hex for fallback) ── */
:root {
    --vi-teal:       #1ABB9C;
    --vi-teal-dim:   rgba(26,187,156,0.13);
    --vi-teal-glow:  rgba(26,187,156,0.30);
    --vi-blue:       #3B9EFF;
    --vi-blue-dim:   rgba(59,158,255,0.13);
    --vi-amber:      #F59E0B;
    --vi-amber-dim:  rgba(245,158,11,0.13);
    --vi-green:      #22C55E;
    --vi-green-dim:  rgba(34,197,94,0.13);
    --vi-red:        #EF4444;
    --vi-red-dim:    rgba(239,68,68,0.13);
    --vi-purple:     #A78BFA;
    --vi-purple-dim: rgba(167,139,250,0.13);

    --vi-bg:         #0a0e17;
    --vi-surface:    #111827;
    --vi-surface2:   #1a2235;
    --vi-surface3:   #222d42;
    --vi-border:     rgba(255,255,255,0.07);

    --vi-t1: #f1f5f9;
    --vi-t2: #94a3b8;
    --vi-t3: #4b5563;
    --vi-t4: #374151;
}

/* ── PAGE HEADER ── */
.vi-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap;
    gap: 14px;
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-top: 2.5px solid #1ABB9C !important;
    border-radius: 12px !important;
    padding: 18px 24px !important;
    margin-bottom: 20px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important;
    position: relative !important;
    overflow: hidden !important;
}
.vi-header::after {
    content: '';
    position: absolute; right: -40px; top: -40px;
    width: 180px; height: 180px; border-radius: 50%;
    background: radial-gradient(circle, rgba(26,187,156,0.20) 0%, transparent 70%);
    pointer-events: none;
}

.vi-header-badge {
    background-color: rgba(26,187,156,0.13) !important;
    border: 1px solid rgba(26,187,156,0.3) !important;
    color: #1ABB9C !important;
    font-size: 9.5px; font-weight: 800;
    letter-spacing: 2px; text-transform: uppercase;
    padding: 3px 10px; border-radius: 20px;
    margin-bottom: 6px; display: inline-block;
}
.vi-header-title {
    font-size: 20px !important; font-weight: 800 !important;
    color: #f1f5f9 !important;
    letter-spacing: -.3px; line-height: 1.15;
}
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-header-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.vi-clock {
    font-size: 11px; font-weight: 700;
    color: #94a3b8 !important;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    padding: 6px 14px; border-radius: 20px;
    font-variant-numeric: tabular-nums;
}
.vi-online-badge {
    display: inline-flex !important; align-items: center; gap: 7px;
    background-color: rgba(26,187,156,0.13) !important;
    border: 1px solid rgba(26,187,156,0.3) !important;
    color: #1ABB9C !important;
    font-size: 11px; font-weight: 800;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 6px 16px; border-radius: 20px;
}
.vi-online-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background-color: #1ABB9C; flex-shrink: 0;
    animation: viPing 1.8s ease-in-out infinite;
}
@keyframes viPing {
    0%  { box-shadow: 0 0 0 0   rgba(26,187,156,0.5); }
    70% { box-shadow: 0 0 0 7px rgba(26,187,156,0);   }
    100%{ box-shadow: 0 0 0 0   rgba(26,187,156,0);   }
}

/* ── SECTION LABEL ── */
.vi-section {
    font-size: 9.5px !important; font-weight: 800 !important;
    letter-spacing: 2.5px; text-transform: uppercase;
    color: #4b5563 !important;
    margin: 22px 0 12px;
    display: flex; align-items: center; gap: 10px;
}
.vi-section::after {
    content:''; flex:1; height:1px;
    background-color: rgba(255,255,255,0.07);
}

/* ── KPI CARD ── */
.vi-kpi {
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 12px !important;
    padding: 0 !important;
    margin-bottom: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important;
    overflow: hidden;
    position: relative;
    transition: transform .2s, box-shadow .2s;
}
.vi-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 28px rgba(0,0,0,0.45) !important;
}
.vi-kpi-accent { height: 3px; width: 100%; display: block; }
.vi-kpi-body { padding: 18px 20px 0; }

.vi-kpi-label {
    font-size: 9.5px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.5px;
    color: #4b5563 !important;
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px;
}
.vi-kpi-value {
    font-size: 34px !important; font-weight: 900 !important;
    color: #f1f5f9 !important; line-height: 1;
    letter-spacing: -1.5px; margin-bottom: 4px;
}
.vi-kpi-sub { font-size: 11px !important; color: #4b5563 !important; margin-bottom: 14px; }

.vi-kpi-ghost {
    position: absolute; right: 16px; top: 22px;
    font-size: 52px; opacity: 0.04;
    color: #f1f5f9; pointer-events: none;
}

/* substats row */
.vi-substats {
    display: flex; align-items: stretch;
    border-top: 1px solid rgba(255,255,255,0.07);
    margin-top: 4px;
}
.vi-substat {
    flex: 1; display: flex; align-items: center; gap: 8px;
    padding: 12px 12px;
}
.vi-substat + .vi-substat { border-left: 1px solid rgba(255,255,255,0.07); }
.vi-substat-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; flex-shrink: 0;
}
.vi-substat-v { font-size: 14px !important; font-weight: 800 !important; color: #f1f5f9 !important; line-height: 1; }
.vi-substat-v.pos { color: #22C55E !important; }
.vi-substat-v.neg { color: #EF4444 !important; }
.vi-substat-l { font-size: 9px !important; font-weight: 700 !important; color: #4b5563 !important; text-transform: uppercase; letter-spacing: .8px; margin-top: 2px; }

.vi-sparkline { height: 46px; overflow: hidden; }
.vi-sparkline canvas { display: block; width: 100% !important; }

/* ── PANEL ── */
.vi-panel {
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 12px !important;
    overflow: hidden;
    margin-bottom: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important;
    transition: box-shadow .2s;
}
.vi-panel:hover { box-shadow: 0 6px 28px rgba(0,0,0,0.45) !important; }

.vi-panel-head {
    display: flex !important; align-items: center !important;
    gap: 10px; flex-wrap: wrap;
    padding: 13px 18px !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    background-color: #1a2235 !important;
}
.vi-panel-title {
    font-size: 11.5px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.2px;
    color: #94a3b8 !important; flex: 1;
    display: flex; align-items: center; gap: 8px;
}
.vi-panel-icon {
    width: 26px; height: 26px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
}
.vi-panel-body { padding: 18px !important; }

.vi-chip {
    font-size: 9.5px; font-weight: 800;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 3px 10px; border-radius: 12px;
    white-space: nowrap;
}

/* ── LIVE FEED ── */
.vi-feed {
    display: flex; align-items: flex-start; gap: 14px;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 10px;
    padding: 16px;
}
.vi-feed-ico {
    width: 48px; height: 48px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.vi-feed-symbol { font-size: 18px !important; font-weight: 900 !important; color: #f1f5f9 !important; line-height: 1; }
.vi-decision {
    display: inline-flex; align-items: center;
    font-size: 9.5px; font-weight: 800;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 2px 9px; border-radius: 12px;
    margin-left: 8px; vertical-align: middle;
}
.vi-decision.BUY, .vi-decision.LONG   { background-color: rgba(26,187,156,0.15)  !important; color: #1ABB9C !important; }
.vi-decision.SELL, .vi-decision.SHORT { background-color: rgba(239,68,68,0.15)   !important; color: #EF4444 !important; }
.vi-decision.HOLD                     { background-color: rgba(245,158,11,0.15)  !important; color: #F59E0B !important; }

.vi-feed-meta { font-size: 12px !important; color: #94a3b8 !important; margin-top: 5px; line-height: 2; }
.vi-feed-meta strong { color: #f1f5f9 !important; font-weight: 700; }
.vi-feed-time { font-size: 10.5px !important; color: #4b5563 !important; margin-top: 6px; }

.vi-feed-stats {
    display: flex; gap: 0;
    margin-top: 14px; padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.07);
}
.vi-feed-stat { flex: 1; padding: 0 10px; }
.vi-feed-stat:first-child { padding-left: 0; }
.vi-feed-stat + .vi-feed-stat { border-left: 1px solid rgba(255,255,255,0.07); }
.vi-feed-stat-l { font-size: 9px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1px; color: #4b5563 !important; margin-bottom: 3px; }
.vi-feed-stat-v { font-size: 15px !important; font-weight: 900 !important; color: #f1f5f9 !important; }

/* ── CHART SUMMARY ── */
.vi-chart-summary {
    display: flex; gap: 28px; flex-wrap: wrap;
    margin-bottom: 16px; padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.vi-summary-label { font-size: 9.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #4b5563 !important; margin-bottom: 2px; }
.vi-summary-val   { font-size: 22px !important; font-weight: 900 !important; color: #f1f5f9 !important; letter-spacing: -.5px; }
.vi-summary-val.pos { color: #22C55E !important; }
.vi-summary-val.neg { color: #EF4444 !important; }

/* ── SIGNAL LEGEND ── */
.vi-sig-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.vi-sig-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.vi-sig-name { font-size: 12px !important; color: #94a3b8 !important; flex: 1; font-weight: 600; }
.vi-sig-bar  { flex: 2; height: 5px; background-color: rgba(255,255,255,0.07); border-radius: 3px; overflow: hidden; }
.vi-sig-fill { height: 100%; border-radius: 3px; }
.vi-sig-pct  { font-size: 10px !important; color: #4b5563 !important; min-width: 32px; text-align: right; }
.vi-sig-cnt  { font-size: 12px !important; color: #f1f5f9 !important; font-weight: 800; min-width: 28px; text-align: right; }

/* doughnut center */
.vi-doughnut-wrap { position: relative; max-width: 190px; margin: 0 auto; }
.vi-doughnut-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    pointer-events: none;
}
.vi-doughnut-center .big   { font-size: 24px !important; font-weight: 900 !important; color: #f1f5f9 !important; line-height: 1; }
.vi-doughnut-center .small { font-size: 9.5px !important; font-weight: 700 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; margin-top: 3px; }

/* ── SYMBOL TABLE ── */
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th {
    padding: 10px 14px;
    font-size: 9.5px !important; font-weight: 800 !important;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #4b5563 !important; text-align: left;
    background-color: #1a2235 !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    white-space: nowrap;
}
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; transition: background .12s; }
.vi-table tbody tr:last-child { border-bottom: none !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 800 !important; font-size: 13px; }
.vi-table .td-rank { color: #4b5563 !important; font-size: 11px; }
.vi-table .td-pos  { color: #22C55E !important; font-weight: 800 !important; }
.vi-table .td-neg  { color: #EF4444 !important; font-weight: 800 !important; }

.vi-count-pill {
    display: inline-block;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    color: #94a3b8 !important;
    font-size: 11px; font-weight: 700;
    padding: 2px 9px; border-radius: 10px;
}

.vi-mbar      { height: 6px; background-color: #222d42; border-radius: 3px; overflow: hidden; min-width: 70px; }
.vi-mbar-fill { height: 100%; border-radius: 3px; transition: width .6s ease; }
.vi-mbar-pos  { background: linear-gradient(90deg, rgba(34,197,94,.4), #22C55E); }
.vi-mbar-neg  { background: linear-gradient(90deg, rgba(239,68,68,.4), #EF4444); }

/* ── SESSION ── */
.vi-session-item { margin-bottom: 14px; }
.vi-session-item:last-child { margin-bottom: 0; }
.vi-session-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.vi-session-name { font-size: 12.5px !important; color: #f1f5f9 !important; font-weight: 700; }
.vi-session-val  { font-size: 12px !important; color: #f1f5f9 !important; font-weight: 900; }
.vi-session-bar  { height: 8px; background-color: #222d42; border-radius: 4px; overflow: hidden; }
.vi-session-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #3b82f6, #3B9EFF); transition: width .7s cubic-bezier(.4,0,.2,1); }

/* W/L */
.vi-wl-title  { font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.5px; text-transform: uppercase; color: #4b5563 !important; margin-bottom: 12px; }
.vi-wl-bar    { display: flex; height: 12px; border-radius: 6px; overflow: hidden; gap: 2px; }
.vi-wl-win    { background-color: #22C55E; transition: flex .7s ease; }
.vi-wl-loss   { background-color: #EF4444; }
.vi-wl-labels { display: flex; justify-content: space-between; margin-top: 8px; font-size: 11.5px; font-weight: 800; }
.vi-wl-labels .w { color: #22C55E !important; }
.vi-wl-labels .l { color: #EF4444 !important; }

.vi-wr-row   { display: flex; align-items: center; gap: 18px; margin-bottom: 18px; }
.vi-wr-val   { font-size: 28px !important; font-weight: 900 !important; color: #f1f5f9 !important; line-height: 1; }
.vi-wr-label { font-size: 10px !important; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; color: #4b5563 !important; margin-top: 3px; }
.vi-wr-sub   { font-size: 11.5px !important; color: #94a3b8 !important; margin-top: 8px; line-height: 1.8; }

/* empty state */
.vi-empty { text-align: center; color: #4b5563 !important; font-size: 12.5px !important; padding: 30px 0; }
.vi-empty i { font-size: 28px; display: block; margin-bottom: 10px; opacity: .35; color: #4b5563 !important; }

/* animations */
@keyframes viUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
.vi-anim   { animation: viUp .4s ease both; }
.vi-anim-1 { animation-delay: .05s; }
.vi-anim-2 { animation-delay: .10s; }
.vi-anim-3 { animation-delay: .15s; }
.vi-anim-4 { animation-delay: .20s; }
.vi-anim-5 { animation-delay: .25s; }
.vi-anim-6 { animation-delay: .30s; }

/* scrollbar inside dark page */
.right_col ::-webkit-scrollbar-thumb { background: #222d42; }

@media (max-width: 767px) {
    .vi-header { flex-direction: column; align-items: flex-start; }
    .vi-chart-summary { gap: 16px; }
}
</style>
@endpush


@section('content')

{{-- PAGE HEADER --}}
<div class="row">
  <div class="col-md-12">
    <div class="vi-header vi-anim">
      <div>
        <div class="vi-header-badge">AI Intelligence</div>
        <div class="vi-header-title">VIOMIA AI Trading Center</div>
        <div class="vi-header-sub">Decisions &nbsp;·&nbsp; Signals &nbsp;·&nbsp; Executions &nbsp;·&nbsp; Performance</div>
      </div>
      <div class="vi-header-right">
        <div class="vi-clock"><i class="fa fa-clock-o" style="margin-right:5px;opacity:.6;"></i><span id="vi-clock">—</span></div>
        <div class="vi-online-badge"><span class="vi-online-dot"></span>AI Online</div>
      </div>
    </div>
  </div>
</div>


{{-- KPI TILES --}}
<div class="vi-section">Key Performance Metrics</div>
<div class="row">

  <div class="col-md-4 col-sm-6">
    <div class="vi-kpi vi-anim vi-anim-1">
      <span class="vi-kpi-accent" style="background-color:#1ABB9C;"></span>
      <div class="vi-kpi-body">
        <div class="vi-kpi-label"><i class="fa fa-exchange" style="color:#1ABB9C;"></i> Activity Today</div>
        <div class="vi-kpi-value">{{ $signalsToday + $executionsToday }}</div>
        <div class="vi-kpi-sub">Total signals &amp; executions</div>
      </div>
      <div class="vi-kpi-ghost"><i class="fa fa-exchange"></i></div>
      <div class="vi-substats">
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-rss"></i></span>
          <div><div class="vi-substat-v">{{ $signalsToday }}</div><div class="vi-substat-l">Signals</div></div>
        </div>
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;"><i class="fa fa-bolt"></i></span>
          <div><div class="vi-substat-v">{{ $executionsToday }}</div><div class="vi-substat-l">Executions</div></div>
        </div>
      </div>
      <div class="vi-sparkline"><canvas id="spk1" height="46"></canvas></div>
    </div>
  </div>

  <div class="col-md-4 col-sm-6">
    <div class="vi-kpi vi-anim vi-anim-2">
      <span class="vi-kpi-accent" style="background-color:{{ $profitToday >= 0 ? '#22C55E' : '#EF4444' }};"></span>
      <div class="vi-kpi-body">
        <div class="vi-kpi-label">
          <i class="fa fa-line-chart" style="color:{{ $profitToday >= 0 ? '#22C55E' : '#EF4444' }};"></i> Trading Performance
        </div>
        <div class="vi-kpi-value" style="color:{{ $winRate >= 60 ? '#22C55E' : ($winRate < 40 ? '#EF4444' : '#f1f5f9') }} !important;">
          {{ $winRate }}<span style="font-size:16px;font-weight:600;">%</span>
        </div>
        <div class="vi-kpi-sub">Win rate &amp; profitability</div>
      </div>
      <div class="vi-kpi-ghost"><i class="fa fa-trophy"></i></div>
      <div class="vi-substats">
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(245,158,11,0.13);color:#F59E0B;"><i class="fa fa-bullseye"></i></span>
          <div><div class="vi-substat-v">{{ $winRate }}%</div><div class="vi-substat-l">Win Rate</div></div>
        </div>
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:{{ $profitToday >= 0 ? 'rgba(34,197,94,0.13)' : 'rgba(239,68,68,0.13)' }};color:{{ $profitToday >= 0 ? '#22C55E' : '#EF4444' }};"><i class="fa fa-usd"></i></span>
          <div>
            <div class="vi-substat-v {{ $profitToday >= 0 ? 'pos' : 'neg' }}">{{ number_format($profitToday, 2) }}</div>
            <div class="vi-substat-l">AI P&amp;L</div>
          </div>
        </div>
      </div>
      <div class="vi-sparkline"><canvas id="spk2" height="46"></canvas></div>
    </div>
  </div>

  <div class="col-md-4 col-sm-6">
    <div class="vi-kpi vi-anim vi-anim-3">
      <span class="vi-kpi-accent" style="background-color:#A78BFA;"></span>
      <div class="vi-kpi-body">
        <div class="vi-kpi-label"><i class="fa fa-cog fa-spin" style="color:#A78BFA;animation-duration:4s;"></i> AI Quality</div>
        <div class="vi-kpi-value">{{ $avgConfidence }}</div>
        <div class="vi-kpi-sub">Avg confidence · RR {{ $avgRR }}</div>
      </div>
      <div class="vi-kpi-ghost"><i class="fa fa-microchip"></i></div>
      <div class="vi-substats">
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(167,139,250,0.13);color:#A78BFA;"><i class="fa fa-brain"></i></span>
          <div><div class="vi-substat-v">{{ $avgRR }}</div><div class="vi-substat-l">Avg RR</div></div>
        </div>
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(34,197,94,0.13);color:#22C55E;"><i class="fa fa-check"></i></span>
          <div><div class="vi-substat-v pos">{{ $winsToday }}</div><div class="vi-substat-l">Wins</div></div>
        </div>
        <div class="vi-substat">
          <span class="vi-substat-icon" style="background-color:rgba(239,68,68,0.13);color:#EF4444;"><i class="fa fa-times"></i></span>
          <div><div class="vi-substat-v neg">{{ $lossToday }}</div><div class="vi-substat-l">Losses</div></div>
        </div>
      </div>
      <div class="vi-sparkline"><canvas id="spk3" height="46"></canvas></div>
    </div>
  </div>

</div>


{{-- LIVE FEED --}}
<div class="vi-section">Live Feed</div>
<div class="row">

  <div class="col-md-6">
    <div class="vi-panel vi-anim vi-anim-4">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-rss"></i></span>
          Last AI Signal
        </div>
        @if($lastSignal)
          <span class="vi-chip" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;">● Live</span>
        @endif
      </div>
      <div class="vi-panel-body">
        @if($lastSignal)
        <div class="vi-feed">
          <div class="vi-feed-ico" style="background-color:rgba(26,187,156,0.13);">📡</div>
          <div style="flex:1;min-width:0;">
            <div>
              <span class="vi-feed-symbol">{{ $lastSignal->symbol }}</span>
              @php $d1 = strtoupper($lastSignal->decision ?? ''); @endphp
              <span class="vi-decision {{ $d1 }}">{{ $d1 }}</span>
            </div>
            <div class="vi-feed-meta">Entry &nbsp;<strong>{{ $lastSignal->entry ?? '—' }}</strong></div>
            <div class="vi-feed-time"><i class="fa fa-clock-o" style="margin-right:4px;"></i>{{ ($lastSignal->pushed_at ? \Carbon\Carbon::parse($lastSignal->pushed_at)->diffForHumans() : '—') }}</div>
            <div class="vi-feed-stats">
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Symbol</div><div class="vi-feed-stat-v">{{ $lastSignal->symbol }}</div></div>
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Decision</div><div class="vi-feed-stat-v">{{ $d1 ?: '—' }}</div></div>
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Entry</div><div class="vi-feed-stat-v">{{ $lastSignal->entry ?? '—' }}</div></div>
            </div>
          </div>
        </div>
        @else
        <div class="vi-empty"><i class="fa fa-rss"></i>No signal recorded yet</div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="vi-panel vi-anim vi-anim-4">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(245,158,11,0.13);color:#F59E0B;"><i class="fa fa-bolt"></i></span>
          Last Execution
        </div>
        @if($lastExecution)
          <span class="vi-chip" style="background-color:rgba(245,158,11,0.13);color:#F59E0B;">Executed</span>
        @endif
      </div>
      <div class="vi-panel-body">
        @if($lastExecution)
        <div class="vi-feed" style="border-left:2px solid #F59E0B;">
          <div class="vi-feed-ico" style="background-color:rgba(245,158,11,0.13);">⚡</div>
          <div style="flex:1;min-width:0;">
            <div>
              <span class="vi-feed-symbol">{{ $lastExecution->symbol }}</span>
              @php $d2 = strtoupper($lastExecution->decision ?? ''); @endphp
              <span class="vi-decision {{ $d2 }}">{{ $d2 }}</span>
            </div>
            <div class="vi-feed-meta">Confidence &nbsp;<strong>{{ $lastExecution->ml_confidence ?? '—' }}</strong></div>
            @if(isset($lastExecution->created_at))
            <div class="vi-feed-time"><i class="fa fa-clock-o" style="margin-right:4px;"></i>{{ \Carbon\Carbon::parse($lastExecution->created_at)->diffForHumans() }}</div>
            @endif
            <div class="vi-feed-stats">
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Symbol</div><div class="vi-feed-stat-v">{{ $lastExecution->symbol ?? '—' }}</div></div>
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Decision</div><div class="vi-feed-stat-v">{{ $d2 ?: '—' }}</div></div>
              <div class="vi-feed-stat"><div class="vi-feed-stat-l">Confidence</div><div class="vi-feed-stat-v">{{ $lastExecution->ml_confidence ?? '—' }}</div></div>
            </div>
          </div>
        </div>
        @else
        <div class="vi-empty"><i class="fa fa-bolt"></i>No execution recorded yet</div>
        @endif
      </div>
    </div>
  </div>

</div>


{{-- PERFORMANCE CHARTS --}}
<div class="vi-section">Performance Analytics</div>
<div class="row">

  <div class="col-md-8">
    <div class="vi-panel vi-anim vi-anim-5">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;"><i class="fa fa-area-chart"></i></span>
          AI Profit Curve — 12 Months
        </div>
        <span class="vi-chip" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;">P&amp;L Trend</span>
      </div>
      <div class="vi-panel-body">
        @php
          $totalPnl   = array_sum($monthlyProfit);
          $bestMonth  = $months[array_search(max($monthlyProfit), $monthlyProfit)] ?? '—';
          $worstMonth = $months[array_search(min($monthlyProfit), $monthlyProfit)] ?? '—';
        @endphp
        <div class="vi-chart-summary">
          <div>
            <div class="vi-summary-label">12-Month Total</div>
            <div class="vi-summary-val {{ $totalPnl >= 0 ? 'pos' : 'neg' }}">
              {{ ($totalPnl >= 0 ? '+' : '') }}{{ number_format($totalPnl, 2) }}
            </div>
          </div>
          <div>
            <div class="vi-summary-label">Best Month</div>
            <div class="vi-summary-val">{{ $bestMonth }}</div>
          </div>
          <div>
            <div class="vi-summary-label">Worst Month</div>
            <div class="vi-summary-val">{{ $worstMonth }}</div>
          </div>
          <div>
            <div class="vi-summary-label">Avg / Month</div>
            <div class="vi-summary-val">{{ number_format($totalPnl / 12, 2) }}</div>
          </div>
        </div>
        <canvas id="profitChart" height="110"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="vi-panel vi-anim vi-anim-5">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(167,139,250,0.13);color:#A78BFA;"><i class="fa fa-pie-chart"></i></span>
          Signal Distribution
        </div>
      </div>
      <div class="vi-panel-body">
        <div class="vi-doughnut-wrap">
          <canvas id="signalChart" height="180"></canvas>
          <div class="vi-doughnut-center">
            <div class="big">{{ $signalBreakdown->sum('total') }}</div>
            <div class="small">Total</div>
          </div>
        </div>
        <div style="margin-top:18px;">
          @php $sigTotal = $signalBreakdown->sum('total') ?: 1; @endphp
          @foreach($signalBreakdown as $s)
          @php
            $pct    = round($s->total / $sigTotal * 100);
            $sl     = strtoupper($s->decision);
            $sColor = match(true) {
              in_array($sl,['BUY','LONG'])    => '#1ABB9C',
              in_array($sl,['SELL','SHORT'])  => '#EF4444',
              $sl === 'HOLD'                  => '#F59E0B',
              default                         => '#3B9EFF',
            };
          @endphp
          <div class="vi-sig-row">
            <div class="vi-sig-dot" style="background-color:{{ $sColor }};"></div>
            <div class="vi-sig-name">{{ $s->decision }}</div>
            <div class="vi-sig-bar"><div class="vi-sig-fill" style="width:{{ $pct }}%;background-color:{{ $sColor }};"></div></div>
            <div class="vi-sig-pct">{{ $pct }}%</div>
            <div class="vi-sig-cnt">{{ $s->total }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</div>


{{-- BREAKDOWN --}}
<div class="vi-section">Breakdown</div>
<div class="row">

  <div class="col-md-7">
    <div class="vi-panel vi-anim vi-anim-6">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-bar-chart"></i></span>
          Top Symbols Performance
        </div>
        <span class="vi-chip" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;">Top 10</span>
      </div>
      @php $maxPnl = $symbolPerformance->max(fn($s) => abs($s->pnl)) ?: 1; @endphp
      <table class="vi-table">
        <thead>
          <tr>
            <th width="32">#</th><th>Symbol</th><th>Trades</th><th>P&amp;L</th><th width="120">Distribution</th>
          </tr>
        </thead>
        <tbody>
          @forelse($symbolPerformance as $i => $s)
          <tr>
            <td class="td-rank">{{ $i + 1 }}</td>
            <td class="td-sym">{{ $s->symbol }}</td>
            <td><span class="vi-count-pill">{{ $s->trades }}</span></td>
            <td class="{{ $s->pnl >= 0 ? 'td-pos' : 'td-neg' }}">{{ ($s->pnl >= 0 ? '+' : '') }}{{ number_format($s->pnl, 2) }}</td>
            <td>
              <div class="vi-mbar">
                <div class="vi-mbar-fill {{ $s->pnl >= 0 ? 'vi-mbar-pos' : 'vi-mbar-neg' }}"
                     style="width:{{ round(abs($s->pnl) / $maxPnl * 100) }}%;"></div>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="padding:24px;text-align:center;color:#4b5563 !important;">No symbol data available</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-5">
    <div class="vi-panel vi-anim vi-anim-6">
      <div class="vi-panel-head">
        <div class="vi-panel-title">
          <span class="vi-panel-icon" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;"><i class="fa fa-clock-o"></i></span>
          Session Activity
        </div>
      </div>
      <div class="vi-panel-body">
        @php $maxTrades = $sessionStats->max('trades') ?: 1; @endphp
        @forelse($sessionStats as $s)
        <div class="vi-session-item">
          <div class="vi-session-row">
            <span class="vi-session-name">{{ $s->session_name }}</span>
            <span class="vi-session-val">{{ $s->trades }}<span style="font-size:9.5px;font-weight:600;color:#4b5563;margin-left:3px;">trades</span></span>
          </div>
          <div class="vi-session-bar">
            <div class="vi-session-fill" style="width:{{ round($s->trades / $maxTrades * 100) }}%;"></div>
          </div>
        </div>
        @empty
        <div class="vi-empty"><i class="fa fa-clock-o"></i>No session data</div>
        @endforelse

        @if($executionsToday > 0)
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.07);">
          <div class="vi-wl-title">Today's Win / Loss Ratio</div>
          <div class="vi-wr-row">
            <canvas id="winRateRing" width="84" height="84" style="flex-shrink:0;"></canvas>
            <div>
              <div class="vi-wr-val">{{ $winRate }}%</div>
              <div class="vi-wr-label">Win Rate</div>
              <div class="vi-wr-sub">
                <span style="color:#22C55E;font-weight:800;">{{ $winsToday }} wins</span>
                &nbsp;·&nbsp;
                <span style="color:#EF4444;font-weight:800;">{{ $lossToday }} losses</span>
              </div>
            </div>
          </div>
          <div class="vi-wl-bar">
            <div class="vi-wl-win"  style="flex:{{ $winsToday }};"></div>
            <div class="vi-wl-loss" style="flex:{{ max($lossToday, 0) }};"></div>
          </div>
          <div class="vi-wl-labels">
            <span class="w"><i class="fa fa-check-circle"></i> {{ $winsToday }} Wins</span>
            <span class="l">{{ $lossToday }} Losses <i class="fa fa-times-circle"></i></span>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
/* Live clock */
(function tick() {
    const pad = n => String(n).padStart(2,'0'), d = new Date();
    const el = document.getElementById('vi-clock');
    if (el) el.textContent =
        d.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}) +
        '  ' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
    setTimeout(tick, 1000);
})();

/* Chart.js dark defaults */
Chart.defaults.font.family = "'DM Sans','Helvetica Neue',Arial,sans-serif";
Chart.defaults.font.size   = 11.5;
Chart.defaults.color       = '#4b5563';

/* Sparklines */
function sparkline(id, data, color) {
    const el = document.getElementById(id);
    if (!el) return;
    new Chart(el, {
        type: 'line',
        data: {
            labels: data.map((_,i) => i),
            datasets: [{ data, borderColor: color, borderWidth: 2, pointRadius: 0,
                         fill: 'origin', backgroundColor: color + '18', tension: 0.5 }]
        },
        options: {
            responsive: false, animation: false,
            plugins: { legend:{ display:false }, tooltip:{ enabled:false } },
            scales: { x:{ display:false }, y:{ display:false } },
            layout: { padding: 0 }
        }
    });
}

const rnd = (n, lo, hi) => Array.from({ length: n }, () => lo + Math.random() * (hi - lo));
sparkline('spk1', rnd(14, 3, 22),    '#1ABB9C');
sparkline('spk2', rnd(14, 40, 88),   '{{ $profitToday >= 0 ? "#22C55E" : "#EF4444" }}');
sparkline('spk3', rnd(14, 0.5, 1.0), '#A78BFA');

/* Profit curve */
(function () {
    const ctx    = document.getElementById('profitChart').getContext('2d');
    const months = @json($months);
    const data   = @json($monthlyProfit);

    const areaGrad = ctx.createLinearGradient(0, 0, 0, 260);
    areaGrad.addColorStop(0,    'rgba(59,158,255,0.22)');
    areaGrad.addColorStop(0.65, 'rgba(59,158,255,0.04)');
    areaGrad.addColorStop(1,    'rgba(59,158,255,0.00)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { type: 'bar', label: 'Monthly P&L', data,
                  backgroundColor: data.map(v => v >= 0 ? 'rgba(34,197,94,0.18)' : 'rgba(239,68,68,0.18)'),
                  borderColor: data.map(v => v >= 0 ? '#22C55E' : '#EF4444'),
                  borderWidth: 1.5, borderRadius: 4, order: 2 },
                { type: 'line', label: 'Trend', data,
                  borderColor: '#3B9EFF', borderWidth: 2.5, backgroundColor: areaGrad,
                  pointBackgroundColor: '#111827', pointBorderColor: '#3B9EFF',
                  pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 7,
                  fill: true, tension: 0.4, order: 1 }
            ]
        },
        options: {
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a2235', titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9', borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1, padding: 12, cornerRadius: 8,
                    callbacks: { label: c => '  ' + (c.dataset.label === 'Trend' ? 'Trend' : 'P&L') + ':  ' + c.parsed.y.toFixed(2) }
                }
            },
            scales: {
                x: { grid:{ display:false }, border:{ color:'rgba(255,255,255,0.06)' }, ticks:{ color:'#374151', padding:6 } },
                y: { grid:{ color:'rgba(255,255,255,0.04)', drawTicks:false },
                     border:{ color:'rgba(255,255,255,0.06)', dash:[3,4] }, ticks:{ color:'#374151', padding:6 },
                     afterDataLimits(sc) { sc.max = Math.max(sc.max,0); sc.min = Math.min(sc.min,0); } }
            }
        }
    });
})();

/* Signal doughnut */
(function () {
    const labels  = @json($signalBreakdown->pluck('decision'));
    const data    = @json($signalBreakdown->pluck('total'));
    const palette = labels.map(l => {
        const u = String(l).toUpperCase();
        if (['BUY','LONG'].includes(u))   return '#1ABB9C';
        if (['SELL','SHORT'].includes(u)) return '#EF4444';
        if (u === 'HOLD')                 return '#F59E0B';
        return '#3B9EFF';
    });
    new Chart(document.getElementById('signalChart'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data,
            backgroundColor: palette.map(c => c + '26'),
            borderColor: palette, borderWidth: 2.5, hoverOffset: 8 }] },
        options: {
            cutout: '70%',
            plugins: { legend:{ display:false },
                tooltip:{ backgroundColor:'#1a2235', titleColor:'#94a3b8', bodyColor:'#f1f5f9',
                          borderColor:'rgba(255,255,255,0.1)', borderWidth:1, padding:12, cornerRadius:8 } },
            animation: { animateRotate:true, duration:900 }
        }
    });
})();

/* Win rate ring */
(function () {
    const el = document.getElementById('winRateRing');
    if (!el) return;
    new Chart(el, {
        type: 'doughnut',
        data: { datasets: [{ data: [{{ $winRate }}, {{ 100 - $winRate }}],
            backgroundColor: ['#22C55E', '#1a2235'], borderWidth: 0 }] },
        options: { cutout:'78%',
            plugins:{ legend:{display:false}, tooltip:{enabled:false} },
            animation:{ animateRotate:true, duration:1000 } }
    });
})();
</script>
@endpush