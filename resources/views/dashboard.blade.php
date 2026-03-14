{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Control Center — ' . config('app.name'))

@push('styles')
<style>
/* ════════════════════════════════════════════════
   VIOMIA · TRADING BOT CONTROL CENTER
   Dark terminal aesthetic — all hex + !important
   ════════════════════════════════════════════════ */

.right_col {
    background-color: #0a0e17 !important;
    padding: 20px 24px !important;
    min-height: 100vh;
}

:root {
    --d-teal:       #1ABB9C;
    --d-teal-dim:   rgba(26,187,156,0.13);
    --d-blue:       #3B9EFF;
    --d-blue-dim:   rgba(59,158,255,0.13);
    --d-amber:      #F59E0B;
    --d-amber-dim:  rgba(245,158,11,0.13);
    --d-green:      #22C55E;
    --d-green-dim:  rgba(34,197,94,0.13);
    --d-red:        #EF4444;
    --d-red-dim:    rgba(239,68,68,0.13);
    --d-purple:     #A78BFA;
    --d-purple-dim: rgba(167,139,250,0.13);
    --d-bg:         #0a0e17;
    --d-s1:         #111827;
    --d-s2:         #1a2235;
    --d-s3:         #222d42;
    --d-b:          rgba(255,255,255,0.07);
    --d-b2:         rgba(255,255,255,0.12);
    --d-t1:         #f1f5f9;
    --d-t2:         #94a3b8;
    --d-t3:         #4b5563;
}

/* ── PAGE HEADER ── */
.db-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap;
    gap: 12px;
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-top: 2.5px solid #1ABB9C !important;
    border-radius: 12px !important;
    padding: 16px 22px !important;
    margin-bottom: 18px !important;
    position: relative; overflow: hidden;
}
.db-header::after {
    content: '';
    position: absolute; right: -40px; top: -40px;
    width: 160px; height: 160px; border-radius: 50%;
    background: radial-gradient(circle, rgba(26,187,156,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.db-header-title {
    font-size: 18px !important; font-weight: 900 !important;
    color: #f1f5f9 !important; letter-spacing: -.3px; line-height: 1.1;
}
.db-header-sub { font-size: 11.5px !important; color: #94a3b8 !important; margin-top: 2px; }
.db-header-right { display: flex; align-items: center; gap: 8px; }

.db-live-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background-color: rgba(34,197,94,0.13) !important;
    border: 1px solid rgba(34,197,94,0.3) !important;
    color: #22C55E !important;
    font-size: 10.5px; font-weight: 800;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 5px 13px; border-radius: 20px;
}
.db-live-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background-color: #22C55E; flex-shrink: 0;
    animation: dbPing 1.8s ease-in-out infinite;
}
@keyframes dbPing {
    0%  { box-shadow: 0 0 0 0   rgba(34,197,94,.55); }
    70% { box-shadow: 0 0 0 6px rgba(34,197,94,0);   }
    100%{ box-shadow: 0 0 0 0   rgba(34,197,94,0);   }
}
.db-alert-badge {
    background-color: rgba(245,158,11,0.13) !important;
    border: 1px solid rgba(245,158,11,0.25) !important;
    color: #F59E0B !important;
    font-size: 10.5px; font-weight: 800;
    padding: 5px 13px; border-radius: 20px;
}

/* ── SECTION LABEL ── */
.db-section {
    font-size: 9.5px !important; font-weight: 800 !important;
    letter-spacing: 2.5px; text-transform: uppercase;
    color: #4b5563 !important;
    margin: 20px 0 11px;
    display: flex; align-items: center; gap: 10px;
}
.db-section::after { content:''; flex:1; height:1px; background-color: rgba(255,255,255,0.07); }

/* ── KPI TILE (overrides Gentelella tile_stats_count) ── */
.tile_stats_count {
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 12px !important;
    margin-bottom: 14px !important;
    padding: 0 !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important;
    overflow: hidden !important;
    position: relative !important;
    transition: transform .2s, box-shadow .2s !important;
}
.tile_stats_count:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 26px rgba(0,0,0,0.45) !important;
}

.db-tile-inner { padding: 16px 18px 0; }

.tile_stats_count .count_top {
    font-size: 9px !important; font-weight: 800 !important;
    text-transform: uppercase !important; letter-spacing: 1.5px !important;
    color: #4b5563 !important;
    display: flex; align-items: center; gap: 6px;
    margin-bottom: 8px;
}
.tile_stats_count .count {
    font-size: 30px !important; font-weight: 900 !important;
    color: #f1f5f9 !important; line-height: 1 !important;
    letter-spacing: -1.5px; margin-bottom: 4px !important;
}
.tile_stats_count .count.green { color: #22C55E !important; }
.tile_stats_count .count.red   { color: #EF4444 !important; }
.tile_stats_count .count.blue  { color: #3B9EFF !important; }
.tile_stats_count .count.amber { color: #F59E0B !important; }

.tile_stats_count .count_bottom {
    font-size: 10.5px !important; color: #4b5563 !important;
    display: block; padding-bottom: 14px;
}
.tile_stats_count .count_bottom.text-success { color: #22C55E !important; }
.tile_stats_count .count_bottom.text-danger  { color: #EF4444 !important; }

/* accent bar */
.db-accent { height: 3px; width: 100%; display: block; }

/* ghost icon */
.db-ghost {
    position: absolute; right: 14px; top: 18px;
    font-size: 44px; opacity: 0.04;
    color: #f1f5f9; pointer-events: none;
}

/* substats under tile */
.db-substats {
    display: flex; border-top: 1px solid rgba(255,255,255,0.07);
    margin-top: 6px;
}
.db-sub {
    flex: 1; display: flex; align-items: center; gap: 7px;
    padding: 10px 12px;
}
.db-sub + .db-sub { border-left: 1px solid rgba(255,255,255,0.07); }
.db-sub-ico {
    width: 26px; height: 26px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; flex-shrink: 0;
}
.db-sub-v { font-size: 13px !important; font-weight: 800 !important; color: #f1f5f9 !important; line-height: 1; }
.db-sub-v.pos { color: #22C55E !important; }
.db-sub-v.neg { color: #EF4444 !important; }
.db-sub-l { font-size: 8.5px !important; font-weight: 700 !important; color: #4b5563 !important; text-transform: uppercase; letter-spacing: .7px; margin-top: 2px; }

/* sparkline */
.db-spk { height: 40px; overflow: hidden; }
.db-spk canvas { display: block; width: 100% !important; }

/* ── SMALL STAT CARD ── */
.db-mini-card {
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 12px !important;
    padding: 14px 16px !important;
    margin-bottom: 14px !important;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.25) !important;
}
.db-mini-card h5 {
    font-size: 9.5px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.4px;
    color: #4b5563 !important; margin-bottom: 8px;
}
.db-mini-card h3 {
    font-size: 22px !important; font-weight: 900 !important;
    color: #f1f5f9 !important; margin: 0;
}
.db-mini-card h3.green { color: #22C55E !important; }
.db-mini-card h3.red   { color: #EF4444 !important; }

/* ── PANEL ── */
.db-panel {
    background-color: #111827 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 12px !important;
    overflow: hidden;
    margin-bottom: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.28) !important;
    transition: box-shadow .2s;
}
.db-panel:hover { box-shadow: 0 6px 26px rgba(0,0,0,0.42) !important; }

.db-panel-head {
    display: flex !important; align-items: center !important;
    gap: 9px;
    padding: 12px 18px !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    background-color: #1a2235 !important;
}
.db-panel-title {
    font-size: 11px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.2px;
    color: #94a3b8 !important; flex: 1;
    display: flex; align-items: center; gap: 7px;
}
.db-panel-ico {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
}
.db-panel-body { padding: 18px !important; }
.db-chip {
    font-size: 9px; font-weight: 800;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 2px 9px; border-radius: 12px;
}

/* ── LIVE SNAPSHOT STATS ── */
.db-snap-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}
.db-snap-item {
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 10px;
    padding: 14px 16px;
    text-align: center;
}
.db-snap-label { font-size: 9px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #4b5563 !important; margin-bottom: 6px; }
.db-snap-val   { font-size: 22px !important; font-weight: 900 !important; color: #f1f5f9 !important; line-height: 1; }
.db-snap-val.pos { color: #22C55E !important; }
.db-snap-val.neg { color: #EF4444 !important; }

/* ── SIGNAL / EXEC CARDS ── */
.db-alert-card {
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    border-radius: 10px; padding: 14px 16px;
}
.db-alert-card-label {
    font-size: 9px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.3px;
    color: #4b5563 !important; margin-bottom: 6px;
}
.db-alert-card-val {
    font-size: 13.5px !important; font-weight: 700 !important;
    color: #f1f5f9 !important; line-height: 1.4;
}
.db-alert-card-sub { font-size: 11px !important; color: #4b5563 !important; margin-top: 4px; }

/* ── EXPOSURE TABLE ── */
.db-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.db-table thead th {
    padding: 9px 12px;
    font-size: 9px !important; font-weight: 800 !important;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: #4b5563 !important; text-align: left;
    background-color: #1a2235 !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
}
.db-table thead th.r { text-align: right; }
.db-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); transition: background .12s; }
.db-table tbody tr:last-child { border-bottom: none; }
.db-table tbody tr:hover { background-color: #1a2235 !important; }
.db-table tbody td { padding: 9px 12px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.db-table tbody td.r  { text-align: right; }
.db-table tbody td.sym { color: #f1f5f9 !important; font-weight: 800 !important; }
.db-table tbody td.pos { color: #22C55E !important; font-weight: 700 !important; }
.db-table tbody td.neg { color: #EF4444 !important; font-weight: 700 !important; }

/* ── ERROR LIST ── */
.db-error-item {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.db-error-item:last-child { border-bottom: none; }
.db-error-type { font-size: 12px !important; font-weight: 700 !important; color: #EF4444 !important; }
.db-error-msg  { font-size: 11px !important; color: #4b5563 !important; margin-top: 2px; }
.db-error-time {
    font-size: 9.5px; font-weight: 700;
    color: #4b5563 !important;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.07) !important;
    padding: 2px 7px; border-radius: 8px;
    white-space: nowrap; flex-shrink: 0;
}

/* ── CHART SUMMARY BAR ── */
.db-chart-meta {
    display: flex; gap: 24px; flex-wrap: wrap;
    margin-bottom: 14px; padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.db-cm-label { font-size: 9px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #4b5563 !important; margin-bottom: 2px; }
.db-cm-val   { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; letter-spacing: -.5px; }
.db-cm-val.pos { color: #22C55E !important; }
.db-cm-val.neg { color: #EF4444 !important; }

/* ── BEST/WORST ── */
.db-bw { display: flex; justify-content: center; gap: 28px; }
.db-bw-item { text-align: center; }
.db-bw-label { font-size: 9px !important; font-weight: 700 !important; text-transform: uppercase; letter-spacing: 1px; color: #4b5563 !important; margin-bottom: 4px; }
.db-bw-val   { font-size: 20px !important; font-weight: 900 !important; line-height: 1; }

/* ── FULLCALENDAR DARK ── */
#calendar .fc-toolbar h2 { color: #f1f5f9 !important; font-size: 15px !important; font-weight: 800 !important; }
#calendar .fc-toolbar .fc-button { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.10) !important; color: #94a3b8 !important; font-size: 11px !important; }
#calendar .fc-toolbar .fc-button:hover { background-color: rgba(26,187,156,0.12) !important; color: #1ABB9C !important; border-color: rgba(26,187,156,0.3) !important; }
#calendar .fc-toolbar .fc-state-active { background-color: rgba(26,187,156,0.15) !important; color: #1ABB9C !important; border-color: rgba(26,187,156,0.3) !important; }
#calendar .fc-day-header { background-color: #1a2235 !important; color: #4b5563 !important; font-size: 10px !important; font-weight: 800 !important; letter-spacing: 1px; text-transform: uppercase; border-color: rgba(255,255,255,0.07) !important; }
#calendar .fc-day { background-color: #111827 !important; border-color: rgba(255,255,255,0.07) !important; }
#calendar .fc-today { background-color: rgba(26,187,156,0.06) !important; }
#calendar .fc-day-number { color: #94a3b8 !important; font-size: 11px !important; }
#calendar { background-color: transparent !important; }
#calendar .fc-event { border-radius: 6px !important; font-size: 11px !important; font-weight: 700 !important; padding: 3px 6px !important; }

/* animations */
@keyframes dbUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
.db-anim   { animation: dbUp .4s ease both; }
.db-anim-1 { animation-delay:.04s; }
.db-anim-2 { animation-delay:.08s; }
.db-anim-3 { animation-delay:.12s; }
.db-anim-4 { animation-delay:.16s; }
.db-anim-5 { animation-delay:.20s; }

@media (max-width: 767px) {
    .db-snap-grid { grid-template-columns: 1fr 1fr; }
    .db-header { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush


@section('content')

{{-- ═══ PAGE HEADER ═══ --}}
<div class="db-header db-anim">
    <div>
        <div class="db-header-title">Trading Bot Control Center</div>
        <div class="db-header-sub">Live system · signals · execution · performance overview</div>
    </div>
    <div class="db-header-right">
        <div class="db-live-badge"><span class="db-live-dot"></span>Live</div>
        <div class="db-alert-badge">
            <i class="fa fa-bell-o" style="margin-right:4px;"></i>
            Alerts: <strong id="alertsCount">{{ $alertsCount ?? 0 }}</strong>
        </div>
    </div>
</div>


{{-- ═══ PRIMARY KPIs ═══ --}}
<div class="db-section">Primary Metrics</div>
<div class="row tile_count db-anim db-anim-1">

    {{-- Today's Profit --}}
    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:{{ $todaysProfit >= 0 ? '#22C55E' : '#EF4444' }};"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-usd" style="color:{{ $todaysProfit >= 0 ? '#22C55E' : '#EF4444' }};"></i> Today's Profit</span>
                <div class="count {{ $todaysProfit > 0 ? 'green' : ($todaysProfit < 0 ? 'red' : '') }}" id="todaysProfit">
                    {{ number_format($todaysProfit, 2) }}
                </div>
                <span class="count_bottom">Net P/L (USD)</span>
            </div>
            <div class="db-ghost"><i class="fa fa-usd"></i></div>
            <div class="db-spk"><canvas id="spk-profit" height="40"></canvas></div>
        </div>
    </div>

    {{-- Trades Today --}}
    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#3B9EFF;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-exchange" style="color:#3B9EFF;"></i> Trades Today</span>
                <div class="count blue" id="tradesToday">{{ $todaysTrades }}</div>
                <span class="count_bottom">Total executions</span>
            </div>
            <div class="db-ghost"><i class="fa fa-exchange"></i></div>
            <div class="db-substats">
                <div class="db-sub">
                    <span class="db-sub-ico" style="background-color:rgba(34,197,94,0.13);color:#22C55E;"><i class="fa fa-check"></i></span>
                    <div><div class="db-sub-v pos" id="winsCount">{{ $winsCount ?? '—' }}</div><div class="db-sub-l">Wins</div></div>
                </div>
                <div class="db-sub">
                    <span class="db-sub-ico" style="background-color:rgba(239,68,68,0.13);color:#EF4444;"><i class="fa fa-times"></i></span>
                    <div><div class="db-sub-v neg" id="lossCount">{{ $lossCount ?? '—' }}</div><div class="db-sub-l">Losses</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Win Rate --}}
    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#A78BFA;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-bullseye" style="color:#A78BFA;"></i> Win Rate</span>
                <div class="count" style="color:{{ $winRate >= 60 ? '#22C55E' : ($winRate < 40 ? '#EF4444' : '#f1f5f9') }} !important;" id="winRate">
                    {{ $winRate }}%
                </div>
                <span class="count_bottom">Wins / trades ratio</span>
            </div>
            <div class="db-ghost"><i class="fa fa-trophy"></i></div>
            <div class="db-spk"><canvas id="spk-winrate" height="40"></canvas></div>
        </div>
    </div>

    {{-- Active Bots --}}
    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#1ABB9C;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-terminal" style="color:#1ABB9C;"></i> Active Bots</span>
                <div class="count">{{ $activeBots }}</div>
                <span class="count_bottom {{ $serverHealth === 'OK' ? 'text-success' : 'text-danger' }}">
                    Server: <span id="serverHealth">{{ $serverHealth }}</span>
                </span>
            </div>
            <div class="db-ghost"><i class="fa fa-terminal"></i></div>
            <div class="db-substats">
                <div class="db-sub">
                    <span class="db-sub-ico" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-server"></i></span>
                    <div>
                        <div class="db-sub-v" style="color:{{ $serverHealth === 'OK' ? '#22C55E' : '#EF4444' }} !important;">{{ $serverHealth }}</div>
                        <div class="db-sub-l">Health</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ═══ ADVANCED KPIs ═══ --}}
<div class="db-section">Risk & Edge Metrics</div>
<div class="row tile_count db-anim db-anim-2">

    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#F59E0B;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-calculator" style="color:#F59E0B;"></i> Profit Factor</span>
                <div class="count amber" id="profitFactor">{{ $profitFactor ?? '—' }}</div>
                <span class="count_bottom">GrossProfit / GrossLoss</span>
            </div>
            <div class="db-ghost"><i class="fa fa-calculator"></i></div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:{{ ($expectancy ?? 0) >= 0 ? '#22C55E' : '#EF4444' }};"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-line-chart" style="color:{{ ($expectancy ?? 0) >= 0 ? '#22C55E' : '#EF4444' }};"></i> Expectancy</span>
                <div class="count {{ ($expectancy ?? 0) > 0 ? 'green' : (($expectancy ?? 0) < 0 ? 'red' : '') }}" id="expectancy">
                    {{ number_format($expectancy ?? 0, 2) }}
                </div>
                <span class="count_bottom">Avg edge per trade</span>
            </div>
            <div class="db-ghost"><i class="fa fa-line-chart"></i></div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#22C55E;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-arrow-up" style="color:#22C55E;"></i> Avg Win</span>
                <div class="count green" id="avgWin">{{ number_format($avgWin, 2) }}</div>
                <span class="count_bottom">USD per winning trade</span>
            </div>
            <div class="db-ghost"><i class="fa fa-arrow-up"></i></div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="tile_stats_count">
            <span class="db-accent" style="background-color:#EF4444;"></span>
            <div class="db-tile-inner">
                <span class="count_top"><i class="fa fa-arrow-down" style="color:#EF4444;"></i> Avg Loss</span>
                <div class="count red" id="avgLoss">{{ number_format($avgLoss, 2) }}</div>
                <span class="count_bottom">USD per losing trade</span>
            </div>
            <div class="db-ghost"><i class="fa fa-arrow-down"></i></div>
        </div>
    </div>

</div>


{{-- ═══ SECONDARY KPIs ═══ --}}
<div class="db-section">Business Overview</div>
<div class="row db-anim db-anim-3">

    <div class="col-md-2 col-6">
        <div class="db-mini-card">
            <h5>Clients</h5>
            <h3>{{ $totalClients }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="db-mini-card">
            <h5>New (7d)</h5>
            <h3 class="green">+{{ $newClients }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="db-mini-card">
            <h5>Accounts</h5>
            <h3>{{ $connectedAccounts }}</h3>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="db-mini-card">
            <h5>Avg Duration</h5>
            <h3 style="color:#3B9EFF !important;">{{ round($avgTradeDuration, 1) }}<span style="font-size:13px;font-weight:600;color:#4b5563 !important;"> min</span></h3>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="db-mini-card">
            <h5>Best vs Worst (7d)</h5>
            <div class="db-bw">
                <div class="db-bw-item">
                    <div class="db-bw-label">Best Day</div>
                    <div class="db-bw-val" style="color:#22C55E !important;">+{{ number_format($bestDayPnL, 2) }}</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.07);margin:0 4px;"></div>
                <div class="db-bw-item">
                    <div class="db-bw-label">Worst Day</div>
                    <div class="db-bw-val" style="color:#EF4444 !important;">{{ number_format($worstDayPnL, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ═══ LIVE OPS ═══ --}}
<div class="db-section">Live Operations</div>
<div class="row db-anim db-anim-4">

    <div class="col-md-8">
        <div class="db-panel">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-bolt"></i></span>
                    Live Trading Snapshot
                </div>
                <span class="db-chip" style="background-color:rgba(34,197,94,0.13);color:#22C55E;">● Live</span>
            </div>
            <div class="db-panel-body">

                {{-- 4-column live stats --}}
                <div class="db-snap-grid">
                    <div class="db-snap-item">
                        <div class="db-snap-label">Open Positions</div>
                        <div class="db-snap-val" id="openPositions">{{ $live['openPositions'] ?? 0 }}</div>
                    </div>
                    <div class="db-snap-item">
                        <div class="db-snap-label">Floating P/L</div>
                        <div class="db-snap-val {{ ($live['floatingPnL'] ?? 0) >= 0 ? 'pos' : 'neg' }}" id="floatingPnL">
                            {{ number_format($live['floatingPnL'] ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="db-snap-item">
                        <div class="db-snap-label">Signal Queue</div>
                        <div class="db-snap-val" id="signalQueue">{{ $live['signalQueue'] ?? 0 }}</div>
                    </div>
                    <div class="db-snap-item">
                        <div class="db-snap-label">Exec Success (1h)</div>
                        <div class="db-snap-val" id="execSuccessRate">{{ $live['execSuccessRate'] ?? '—' }}</div>
                    </div>
                </div>

                {{-- Signal + Execution cards --}}
                <div class="row" style="gap:0;">
                    <div class="col-md-6" style="padding-right:8px;">
                        <div class="db-alert-card" style="border-left:2px solid #1ABB9C;">
                            <div class="db-alert-card-label"><i class="fa fa-rss" style="margin-right:5px;color:#1ABB9C;"></i>Last Signal</div>
                            <div class="db-alert-card-val" id="lastSignalText">{{ $live['lastSignalText'] ?? '—' }}</div>
                            <div class="db-alert-card-sub"><i class="fa fa-clock-o" style="margin-right:4px;"></i>Received: <span id="lastSignalAge">{{ $live['lastSignalAge'] ?? '—' }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding-left:8px;">
                        <div class="db-alert-card" style="border-left:2px solid #F59E0B;">
                            <div class="db-alert-card-label"><i class="fa fa-bolt" style="margin-right:5px;color:#F59E0B;"></i>Last Execution</div>
                            <div class="db-alert-card-val" id="lastExecText">{{ $live['lastExecText'] ?? '—' }}</div>
                            <div class="db-alert-card-sub"><i class="fa fa-check-circle" style="margin-right:4px;"></i>Status: <span id="lastExecStatus">{{ $live['lastExecStatus'] ?? '—' }}</span></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="db-panel" style="height:calc(100% - 16px);">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;"><i class="fa fa-pie-chart"></i></span>
                    Exposure by Symbol
                </div>
            </div>
            <div style="padding:0;max-height:270px;overflow-y:auto;">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th class="r">Lots</th>
                            <th class="r">P/L</th>
                        </tr>
                    </thead>
                    <tbody id="exposureTable">
                        @forelse(($live['exposure'] ?? []) as $row)
                        <tr>
                            <td class="sym">{{ $row['symbol'] }}</td>
                            <td class="r">{{ number_format($row['lots'], 2) }}</td>
                            <td class="r {{ $row['pnl'] >= 0 ? 'pos' : 'neg' }}">{{ number_format($row['pnl'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:20px;color:#4b5563 !important;">No exposure</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


{{-- ═══ CHARTS ═══ --}}
<div class="db-section">Performance Analytics</div>
<div class="row db-anim db-anim-5">

    <div class="col-md-8">
        <div class="db-panel">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;"><i class="fa fa-area-chart"></i></span>
                    Profit Curve — 12 Months
                </div>
                <span class="db-chip" style="background-color:rgba(59,158,255,0.13);color:#3B9EFF;">P&amp;L Trend</span>
            </div>
            <div class="db-panel-body">
                @php $totalPnl = array_sum($monthlyProfit ?? []); @endphp
                <div class="db-chart-meta">
                    <div>
                        <div class="db-cm-label">12-Month Total</div>
                        <div class="db-cm-val {{ $totalPnl >= 0 ? 'pos' : 'neg' }}">
                            {{ ($totalPnl >= 0 ? '+' : '') }}{{ number_format($totalPnl, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="db-cm-label">Best Month</div>
                        <div class="db-cm-val">{{ isset($monthlyProfit) ? ($months[array_search(max($monthlyProfit), $monthlyProfit)] ?? '—') : '—' }}</div>
                    </div>
                    <div>
                        <div class="db-cm-label">Avg / Month</div>
                        <div class="db-cm-val">{{ number_format($totalPnl / 12, 2) }}</div>
                    </div>
                </div>
                <canvas id="profitChart" height="110"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="db-panel">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(239,68,68,0.13);color:#EF4444;"><i class="fa fa-exclamation-triangle"></i></span>
                    Errors Today
                </div>
                <span class="db-chip" style="background-color:rgba(239,68,68,0.13);color:#EF4444;">By Type</span>
            </div>
            <div class="db-panel-body">
                <canvas id="errorChart" height="110"></canvas>
            </div>
        </div>
    </div>

</div>


{{-- ═══ FEEDS ═══ --}}
<div class="db-section">Activity Feed</div>
<div class="row">

    <div class="col-md-8">
        <div class="db-panel">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(26,187,156,0.13);color:#1ABB9C;"><i class="fa fa-calendar"></i></span>
                    Daily Trading Journal
                </div>
            </div>
            <div class="db-panel-body">
                <div id='calendar' style="min-height:600px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="db-panel">
            <div class="db-panel-head">
                <div class="db-panel-title">
                    <span class="db-panel-ico" style="background-color:rgba(239,68,68,0.13);color:#EF4444;"><i class="fa fa-bug"></i></span>
                    Recent Errors
                </div>
            </div>
            <div class="db-panel-body" style="max-height:300px;overflow-y:auto;" id="recentErrors">
                <div style="text-align:center;padding:20px;color:#4b5563;">
                    <i class="fa fa-spinner fa-spin" style="display:block;font-size:20px;margin-bottom:8px;opacity:.4;"></i>
                    Loading...
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>

{{-- Pass journal data --}}
<script>window.tradingJournalData = @json($journalData ?? []);</script>

<script>
/* ── Chart.js dark defaults ── */
Chart.defaults.font.family = "'DM Sans','Helvetica Neue',Arial,sans-serif";
Chart.defaults.font.size   = 11.5;
Chart.defaults.color       = '#4b5563';

/* ── Sparklines ── */
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
const rnd = (n,lo,hi) => Array.from({length:n}, () => lo + Math.random()*(hi-lo));
sparkline('spk-profit',  rnd(14, -200, 800), '{{ $todaysProfit >= 0 ? "#22C55E" : "#EF4444" }}');
sparkline('spk-winrate', rnd(14, 40, 85),    '#A78BFA');

/* ── Profit Curve ── */
(function () {
    const ctx    = document.getElementById('profitChart').getContext('2d');
    const months = @json($months ?? []);
    const data   = @json($monthlyProfit ?? []);

    const areaGrad = ctx.createLinearGradient(0, 0, 0, 260);
    areaGrad.addColorStop(0,    'rgba(59,158,255,0.22)');
    areaGrad.addColorStop(0.65, 'rgba(59,158,255,0.04)');
    areaGrad.addColorStop(1,    'rgba(59,158,255,0.00)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { type: 'bar', label: 'P&L', data,
                  backgroundColor: data.map(v => v >= 0 ? 'rgba(34,197,94,0.18)' : 'rgba(239,68,68,0.18)'),
                  borderColor: data.map(v => v >= 0 ? '#22C55E' : '#EF4444'),
                  borderWidth: 1.5, borderRadius: 4, order: 2 },
                { type: 'line', label: 'Trend', data,
                  borderColor: '#3B9EFF', borderWidth: 2.5, backgroundColor: areaGrad,
                  pointBackgroundColor: '#111827', pointBorderColor: '#3B9EFF',
                  pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 6,
                  fill: true, tension: 0.4, order: 1 }
            ]
        },
        options: {
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend:{ display:false },
                tooltip:{ backgroundColor:'#1a2235', titleColor:'#94a3b8', bodyColor:'#f1f5f9',
                          borderColor:'rgba(255,255,255,0.1)', borderWidth:1, padding:12, cornerRadius:8 } },
            scales: {
                x: { grid:{display:false}, border:{color:'rgba(255,255,255,0.06)'}, ticks:{color:'#374151',padding:5} },
                y: { grid:{color:'rgba(255,255,255,0.04)',drawTicks:false},
                     border:{color:'rgba(255,255,255,0.06)',dash:[3,4]}, ticks:{color:'#374151',padding:5},
                     afterDataLimits(s){ s.max=Math.max(s.max,0); s.min=Math.min(s.min,0); } }
            }
        }
    });
})();

/* ── Error Chart ── */
let errorChart = new Chart(document.getElementById('errorChart'), {
    type: 'bar',
    data: { labels: [], datasets: [{ data: [],
        backgroundColor: 'rgba(239,68,68,0.22)', borderColor: '#EF4444',
        borderWidth: 1.5, borderRadius: 4 }] },
    options: {
        responsive: true,
        plugins: { legend:{ display:false },
            tooltip:{ backgroundColor:'#1a2235', bodyColor:'#f1f5f9',
                      borderColor:'rgba(255,255,255,0.1)', borderWidth:1, padding:10, cornerRadius:8 } },
        scales: {
            x: { grid:{display:false}, border:{color:'rgba(255,255,255,0.06)'}, ticks:{color:'#374151'} },
            y: { grid:{color:'rgba(255,255,255,0.04)'}, border:{color:'rgba(255,255,255,0.06)'}, ticks:{color:'#374151'} }
        }
    }
});

function renderErrors(list) {
    const el = document.getElementById('recentErrors');
    if (!el) return;
    if (!list || list.length === 0) {
        el.innerHTML = `<div style="text-align:center;padding:24px;color:#4b5563;font-size:12px;"><i class="fa fa-check-circle" style="color:#22C55E;display:block;font-size:22px;margin-bottom:8px;"></i>No errors today</div>`;
        return;
    }
    el.innerHTML = list.map(e => `
        <div class="db-error-item">
            <div>
                <div class="db-error-type">${e.type}</div>
                <div class="db-error-msg">${e.msg}</div>
            </div>
            <span class="db-error-time">${e.at}</span>
        </div>
    `).join('');
}

function renderErrorChart(breakdown) {
    errorChart.data.labels   = (breakdown || []).map(x => x.type);
    errorChart.data.datasets[0].data = (breakdown || []).map(x => x.total);
    errorChart.update();
}

/* ── Real-time refresh ── */
async function refreshMetrics() {
    try {
        const res = await fetch("{{ route('admin.dashboard.metrics') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const d = await res.json();

        document.getElementById('todaysProfit').innerText  = Number(d.profit).toFixed(2);
        document.getElementById('tradesToday').innerText   = d.trades;
        document.getElementById('winRate').innerText       = (d.winRate ?? 0) + '%';
        document.getElementById('avgWin').innerText        = Number(d.avgWin ?? 0).toFixed(2);
        document.getElementById('avgLoss').innerText       = Number(d.avgLoss ?? 0).toFixed(2);
        document.getElementById('profitFactor').innerText  = d.profitFactor ?? '—';
        document.getElementById('expectancy').innerText    = Number(d.expectancy ?? 0).toFixed(2);

        if (d.live) {
            document.getElementById('openPositions').innerText  = d.live.openPositions ?? 0;
            document.getElementById('signalQueue').innerText    = d.live.signalQueue ?? 0;
            document.getElementById('execSuccessRate').innerText= d.live.execSuccessRate ?? '—';
            document.getElementById('lastSignalText').innerText = d.live.lastSignalText ?? '—';
            document.getElementById('lastSignalAge').innerText  = d.live.lastSignalAge ?? '—';
            document.getElementById('lastExecText').innerText   = d.live.lastExecText ?? '—';
            document.getElementById('lastExecStatus').innerText = d.live.lastExecStatus ?? '—';

            const fp = Number(d.live.floatingPnL ?? 0);
            const fpEl = document.getElementById('floatingPnL');
            fpEl.innerText = fp.toFixed(2);
            fpEl.className = 'db-snap-val ' + (fp >= 0 ? 'pos' : 'neg');

            const tbody = document.getElementById('exposureTable');
            if (tbody) {
                const rows = (d.live.exposure || []).map(r => `
                    <tr>
                        <td class="sym">${r.symbol}</td>
                        <td class="r">${Number(r.lots).toFixed(2)}</td>
                        <td class="r ${Number(r.pnl) >= 0 ? 'pos' : 'neg'}">${Number(r.pnl).toFixed(2)}</td>
                    </tr>
                `).join('');
                tbody.innerHTML = rows || `<tr><td colspan="3" style="text-align:center;padding:20px;color:#4b5563;">No exposure</td></tr>`;
            }

            renderErrors(d.live.recentErrors);
            renderErrorChart(d.live.errorBreakdown);
        }

        if (d.journalFullData && typeof d.journalFullData === 'object') {
            window.tradingJournalData = d.journalFullData;
            renderCalendar();
        }

    } catch (e) { console.error('Dashboard update error:', e); }
}

refreshMetrics();
setInterval(refreshMetrics, 5000);
</script>

{{-- FullCalendar --}}
<script>
$(document).ready(function () {

    function buildCalendarEvents() {
        const events = [];
        const data   = window.tradingJournalData || {};
        for (const dateStr in data) {
            const d = data[dateStr];
            if (d.trades > 0 || d.pnl !== 0) {
                events.push({
                    title: `${d.trades} ⇆  $${Math.abs(d.pnl).toFixed(2)}`,
                    start: dateStr,
                    allDay: true,
                    extendedProps: { trades: d.trades, pnl: d.pnl }
                });
            }
        }
        return events;
    }

    window.renderCalendar = function () {
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('addEventSource', buildCalendarEvents());
    };

    $('#calendar').fullCalendar({
        header: {
            left:   'prev,next today',
            center: 'title',
            right:  'month,agendaWeek,agendaDay'
        },
        defaultDate: moment(),
        editable:    false,
        eventLimit:  true,
        events:      buildCalendarEvents(),
        eventRender: function (event, element) {
            const pnl = event.extendedProps.pnl;
            if (pnl > 0) {
                element.css({ 'background-color':'rgba(34,197,94,0.18)', 'border-color':'#22C55E', 'color':'#22C55E' });
            } else if (pnl < 0) {
                element.css({ 'background-color':'rgba(239,68,68,0.18)', 'border-color':'#EF4444', 'color':'#EF4444' });
            } else {
                element.css({ 'background-color':'rgba(255,255,255,0.06)', 'border-color':'rgba(255,255,255,0.15)', 'color':'#94a3b8' });
            }
            element.css({ 'font-weight':'700', 'border-radius':'6px', 'padding':'4px 6px' });
        }
    });

});
</script>
@endpush