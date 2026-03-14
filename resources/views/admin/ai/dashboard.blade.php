@extends('layouts.admin')

@section('content')

{{-- ================================================================
     VIOMIA AI — Advanced Intelligence Dashboard
     Gentelella-native structure · Enhanced UI layer on top
     ================================================================ --}}

<style>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* ════════════════════════════════════════
   ROOT TOKENS
   ════════════════════════════════════════ */
:root {
    --teal:      #1abb9c;
    --teal-lt:   #eafaf6;
    --teal-md:   #d1f2eb;
    --blue:      #3598dc;
    --blue-lt:   #ebf5fb;
    --orange:    #e87e04;
    --orange-lt: #fef5e7;
    --green:     #26c281;
    --green-lt:  #eafaf1;
    --red:       #e74c3c;
    --red-lt:    #fdf1f0;
    --purple:    #8e44ad;
    --purple-lt: #f5eef8;
    --text-1:    #1a2535;
    --text-2:    #5d6d7e;
    --text-3:    #95a5a6;
    --text-4:    #bdc3c7;
    --border:    #e8ecf1;
    --bg:        #f0f2f5;
    --surface:   #ffffff;
    --radius:    8px;
    --shadow-sm: 0 1px 4px rgba(0,0,0,.06);
    --shadow-md: 0 4px 20px rgba(0,0,0,.09);
    --shadow-lg: 0 8px 32px rgba(0,0,0,.12);
    --font:      'Plus Jakarta Sans', 'Helvetica Neue', Arial, sans-serif;
}

/* ════ GLOBAL OVERRIDES ════ */
.right_col          { background: var(--bg) !important; font-family: var(--font) !important; }
*, *::before, *::after { box-sizing: border-box; }

/* ════════════════════════════════════════
   PAGE HEADER
   ════════════════════════════════════════ */
.vi-header {
    background: var(--surface);
    border-radius: var(--radius);
    border-left: 4px solid var(--teal);
    padding: 20px 24px;
    margin-bottom: 22px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
}
.vi-header-title {
    font-size: 19px;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -.2px;
    margin: 0 0 3px;
    display: flex;
    align-items: center;
    gap: 9px;
}
.vi-header-title .icon-wrap {
    width: 34px; height: 34px;
    background: var(--teal-lt);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--teal);
    font-size: 16px;
    flex-shrink: 0;
}
.vi-header-sub  { font-size: 12px; color: var(--text-3); margin: 0; padding-left: 43px; }

.vi-header-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.vi-clock-wrap {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    letter-spacing: .4px;
}

.vi-badge-online {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--teal-lt);
    border: 1.5px solid var(--teal);
    color: var(--teal);
    font-size: 11px; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 6px 16px; border-radius: 20px;
}
.vi-badge-online .dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--teal);
    box-shadow: 0 0 0 0 rgba(26,187,156,.5);
    animation: vi-ping 1.8s ease-in-out infinite;
}
@keyframes vi-ping {
    0%   { box-shadow: 0 0 0 0 rgba(26,187,156,.55); }
    70%  { box-shadow: 0 0 0 7px rgba(26,187,156,0); }
    100% { box-shadow: 0 0 0 0 rgba(26,187,156,0);   }
}

/* ════════════════════════════════════════
   SECTION LABELS
   ════════════════════════════════════════ */
.vi-section {
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--text-4);
    margin: 22px 0 12px;
    display: flex; align-items: center; gap: 10px;
}
.vi-section::after { content:''; flex:1; height:1px; background: var(--border); }

/* ════════════════════════════════════════
   KPI TILES  (override Gentelella)
   ════════════════════════════════════════ */
.tile_count .tile_stats_count {
    background: var(--surface) !important;
    border-radius: var(--radius) !important;
    border: 1px solid var(--border) !important;
    border-top: none !important;
    border-left: none !important;
    padding: 0 !important;
    margin-bottom: 16px !important;
    box-shadow: var(--shadow-sm) !important;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s !important;
    position: relative;
}
.tile_count .tile_stats_count:hover {
    box-shadow: var(--shadow-md) !important;
    transform: translateY(-3px);
}

/* Coloured top strip */
.tile_count .tile_stats_count::before {
    content: '';
    display: block;
    height: 4px;
    width: 100%;
    border-radius: var(--radius) var(--radius) 0 0;
}
.tile_count .tile_stats_count.vi-teal::before   { background: var(--teal);   }
.tile_count .tile_stats_count.vi-blue::before   { background: var(--blue);   }
.tile_count .tile_stats_count.vi-orange::before { background: var(--orange); }
.tile_count .tile_stats_count.vi-green::before  { background: var(--green);  }
.tile_count .tile_stats_count.vi-red::before    { background: var(--red);    }
.tile_count .tile_stats_count.vi-purple::before { background: var(--purple); }

/* Inner padding wrapper */
.vi-tile-inner { padding: 16px 20px 0; }

.tile_stats_count .count_top {
    font-size: 10.5px !important; font-weight: 700 !important;
    letter-spacing: 1.2px !important; text-transform: uppercase !important;
    color: var(--text-3) !important;
    display: flex !important; align-items: center; gap: 6px;
    margin-bottom: 10px;
}
.tile_stats_count .count {
    font-size: 32px !important; font-weight: 800 !important;
    color: var(--text-1) !important; line-height: 1 !important;
    margin-bottom: 4px !important; letter-spacing: -1px;
}
.tile_stats_count .count.green { color: var(--green) !important; }
.tile_stats_count .count.red   { color: var(--red)   !important; }
.tile_stats_count .count.blue  { color: var(--blue)  !important; }

.tile_stats_count .count_bottom { font-size: 11px !important; color: var(--text-4) !important; display: block; }

/* Trend pill */
.vi-trend {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 700;
    padding: 2px 8px; border-radius: 12px;
    margin-left: 6px;
}
.vi-trend.up   { background: var(--green-lt); color: var(--green); }
.vi-trend.down { background: var(--red-lt);   color: var(--red);   }
.vi-trend.flat { background: var(--bg);       color: var(--text-3); }

/* Sparkline canvas at bottom of tile */
.vi-sparkline-wrap {
    margin-top: 14px;
    height: 44px;
    overflow: hidden;
    border-radius: 0 0 var(--radius) var(--radius);
    position: relative;
}
.vi-sparkline-wrap canvas { display: block; width: 100% !important; }

/* Ghost icon */
.vi-tile-ghost {
    position: absolute; right: 16px; top: 18px;
    font-size: 40px; opacity: .055; pointer-events: none;
    color: var(--text-1);
}

/* Sub-stats row inside tile */
.vi-tile-substats {
    display: flex;
    align-items: center;
    gap: 0;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}
.vi-substat {
    display: flex;
    align-items: center;
    gap: 9px;
    flex: 1;
    padding: 0 6px;
}
.vi-substat-divider {
    width: 1px; height: 32px;
    background: var(--border);
    flex-shrink: 0;
}
.vi-substat-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; flex-shrink: 0;
}
.vi-substat-icon.teal   { background: var(--teal-lt);   color: var(--teal);   }
.vi-substat-icon.blue   { background: var(--blue-lt);   color: var(--blue);   }
.vi-substat-icon.orange { background: var(--orange-lt); color: var(--orange); }
.vi-substat-icon.green  { background: var(--green-lt);  color: var(--green);  }
.vi-substat-icon.red    { background: var(--red-lt);    color: var(--red);    }
.vi-substat-icon.purple { background: var(--purple-lt); color: var(--purple); }
.vi-substat-val {
    font-size: 14px; font-weight: 800;
    color: var(--text-1); line-height: 1;
}
.vi-substat-val.pos { color: var(--green); }
.vi-substat-val.neg { color: var(--red);   }
.vi-substat-label {
    font-size: 10px; font-weight: 600;
    color: var(--text-4); letter-spacing: .5px;
    margin-top: 2px; text-transform: uppercase;
}

/* ════════════════════════════════════════
   x_panel  (override Gentelella)
   ════════════════════════════════════════ */
.x_panel {
    border-radius: var(--radius) !important;
    border: 1px solid var(--border) !important;
    box-shadow: var(--shadow-sm) !important;
    background: var(--surface) !important;
    overflow: hidden;
    transition: box-shadow .2s;
}
.x_panel:hover { box-shadow: var(--shadow-md) !important; }

.x_title {
    border-bottom: 1px solid var(--border) !important;
    padding: 14px 20px !important;
    background: #fafbfc !important;
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap;
    gap: 8px;
}
.x_title h2 {
    font-size: 12.5px !important; font-weight: 700 !important;
    color: var(--text-1) !important; letter-spacing: .3px !important;
    text-transform: uppercase; margin: 0; flex: 1;
    display: flex; align-items: center; gap: 7px;
}
.x_title h2 .ti { /* title icon */
    width: 26px; height: 26px; border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.ti.teal   { background: var(--teal-lt);   color: var(--teal);   }
.ti.blue   { background: var(--blue-lt);   color: var(--blue);   }
.ti.orange { background: var(--orange-lt); color: var(--orange); }
.ti.red    { background: var(--red-lt);    color: var(--red);    }
.ti.purple { background: var(--purple-lt); color: var(--purple); }
.ti.green  { background: var(--green-lt);  color: var(--green);  }

.x_content { padding: 20px !important; }

/* Panel badge */
.vi-pbadge {
    font-size: 10px; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; padding: 3px 10px;
    border-radius: 12px; white-space: nowrap;
}
.vi-pbadge.teal   { background: var(--teal-lt);   color: var(--teal);   }
.vi-pbadge.blue   { background: var(--blue-lt);   color: var(--blue);   }
.vi-pbadge.orange { background: var(--orange-lt); color: var(--orange); }
.vi-pbadge.red    { background: var(--red-lt);    color: var(--red);    }
.vi-pbadge.green  { background: var(--green-lt);  color: var(--green);  }

/* ════════════════════════════════════════
   LIVE FEED CARDS
   ════════════════════════════════════════ */
.vi-feed-card {
    display: flex; align-items: flex-start; gap: 14px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
}
.vi-feed-icon {
    width: 48px; height: 48px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.vi-feed-icon.teal   { background: var(--teal-lt); }
.vi-feed-icon.orange { background: var(--orange-lt); }

.vi-feed-symbol {
    font-size: 18px; font-weight: 800;
    color: var(--text-1); line-height: 1;
    margin-bottom: 4px;
}
.vi-decision-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; padding: 2px 9px;
    border-radius: 12px; margin-left: 6px; vertical-align: middle;
}
.vi-decision-pill.BUY,
.vi-decision-pill.LONG  { background: var(--teal-lt);   color: var(--teal);   }
.vi-decision-pill.SELL,
.vi-decision-pill.SHORT { background: var(--red-lt);    color: var(--red);    }
.vi-decision-pill.HOLD  { background: var(--orange-lt); color: var(--orange); }

.vi-feed-meta { font-size: 12px; color: var(--text-2); margin-top: 5px; line-height: 1.9; }
.vi-feed-meta strong { color: var(--text-1); font-weight: 700; }
.vi-feed-time { font-size: 11px; color: var(--text-4); margin-top: 7px; }

/* Stat row inside feed card */
.vi-feed-stats {
    display: flex; gap: 16px;
    margin-top: 14px; padding-top: 14px;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}
.vi-feed-stat-item { flex:1; min-width: 80px; }
.vi-feed-stat-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-4); margin-bottom: 3px; }
.vi-feed-stat-val   { font-size: 15px; font-weight: 800; color: var(--text-1); }

/* ════════════════════════════════════════
   CHARTS
   ════════════════════════════════════════ */
.vi-chart-container { position: relative; }

/* Profit chart total overlay */
.vi-chart-summary {
    display: flex; gap: 24px; flex-wrap: wrap;
    margin-bottom: 16px; padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}
.vi-chart-summary-item {}
.vi-chart-summary-label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-4); margin-bottom: 2px; }
.vi-chart-summary-val   { font-size: 22px; font-weight: 800; color: var(--text-1); letter-spacing: -.5px; }
.vi-chart-summary-val.pos { color: var(--green); }
.vi-chart-summary-val.neg { color: var(--red);   }

/* Signal doughnut legend */
.vi-sig-legend { margin-top: 16px; }
.vi-sig-row    { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.vi-sig-dot    { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.vi-sig-name   { font-size: 12px; color: var(--text-2); flex:1; font-weight: 600; }
.vi-sig-bar    { flex:2; height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; }
.vi-sig-fill   { height: 100%; border-radius: 3px; }
.vi-sig-count  { font-size: 12px; color: var(--text-1); font-weight: 700; min-width: 28px; text-align:right; }
.vi-sig-pct    { font-size: 10px; color: var(--text-4); min-width: 32px; text-align:right; }

/* Doughnut center text */
.vi-doughnut-wrap { position: relative; }
.vi-doughnut-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    pointer-events: none;
}
.vi-doughnut-center .big   { font-size: 24px; font-weight: 800; color: var(--text-1); line-height: 1; }
.vi-doughnut-center .small { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-4); margin-top: 3px; }

/* ════════════════════════════════════════
   SYMBOL TABLE
   ════════════════════════════════════════ */
.vi-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.vi-table thead th {
    background: #fafbfc;
    padding: 10px 14px;
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    color: var(--text-3);
    border-bottom: 2px solid var(--border);
    text-align: left; white-space: nowrap;
}
.vi-table tbody tr { border-bottom: 1px solid #f5f7fa; transition: background .15s; }
.vi-table tbody tr:last-child { border-bottom: none; }
.vi-table tbody tr:hover { background: #fafbfc; }
.vi-table tbody td { padding: 11px 14px; color: var(--text-2); vertical-align: middle; }
.vi-table tbody td.bold { color: var(--text-1); font-weight: 700; }
.vi-table .pnl-pos { color: var(--green); font-weight: 700; }
.vi-table .pnl-neg { color: var(--red);   font-weight: 700; }
.vi-table .rank-num { color: var(--text-4); font-size: 11px; }

/* Mini bar */
.vi-mbar { height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; min-width: 60px; }
.vi-mbar-fill { height: 100%; border-radius: 3px; transition: width .6s ease; }
.vi-mbar-fill.pos { background: linear-gradient(90deg, #a8edca, var(--green)); }
.vi-mbar-fill.neg { background: linear-gradient(90deg, #f5b8b8, var(--red));   }

/* Trade count badge */
.vi-count-badge {
    display: inline-block;
    background: var(--bg);
    border: 1px solid var(--border);
    color: var(--text-2);
    font-size: 11px; font-weight: 700;
    padding: 1px 8px; border-radius: 10px;
}

/* ════════════════════════════════════════
   SESSION PANEL
   ════════════════════════════════════════ */
.vi-session-item { margin-bottom: 15px; }
.vi-session-item:last-child { margin-bottom: 0; }
.vi-session-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.vi-session-name  { font-size: 12.5px; color: var(--text-1); font-weight: 600; }
.vi-session-val   { font-size: 12px; color: var(--text-1); font-weight: 800; }
.vi-session-bar   { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
.vi-session-fill  {
    height: 100%; border-radius: 4px;
    background: linear-gradient(90deg, #5dade2, var(--blue));
    transition: width .7s cubic-bezier(.4,0,.2,1);
}

/* W/L ratio */
.vi-wl-section { margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border); }
.vi-wl-title   { font-size: 10.5px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: var(--text-4); margin-bottom: 10px; }
.vi-wl-bar     { display: flex; height: 12px; border-radius: 6px; overflow: hidden; gap: 2px; }
.vi-wl-win     { background: var(--green); transition: flex .6s ease; }
.vi-wl-loss    { background: var(--red);   }
.vi-wl-labels  { display: flex; justify-content: space-between; margin-top: 8px; font-size: 11px; font-weight: 700; }
.vi-wl-labels .w { color: var(--green); }
.vi-wl-labels .l { color: var(--red);   }

/* Win rate circle */
.vi-wr-row { display: flex; align-items: center; gap: 16px; margin-top: 16px; }
.vi-wr-circle-wrap { flex-shrink: 0; }
.vi-wr-circle-wrap canvas { display: block; }
.vi-wr-detail {}
.vi-wr-detail .big   { font-size: 26px; font-weight: 800; color: var(--text-1); line-height: 1; }
.vi-wr-detail .label { font-size: 11px; color: var(--text-4); font-weight: 600; letter-spacing: .5px; margin-top: 2px; }
.vi-wr-detail .sub   { font-size: 12px; color: var(--text-2); margin-top: 8px; line-height: 1.7; }

/* ════════════════════════════════════════
   EMPTY STATE
   ════════════════════════════════════════ */
.vi-empty { text-align: center; color: var(--text-4); font-size: 13px; padding: 28px 0; }
.vi-empty i { font-size: 30px; display: block; margin-bottom: 8px; opacity: .5; }

/* ════════════════════════════════════════
   FADE-IN ANIMATION
   ════════════════════════════════════════ */
@keyframes vi-fadeup { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }

.vi-header,
.tile_count .tile_stats_count,
.x_panel { animation: vi-fadeup .45s ease both; }

.tile_count .tile_stats_count:nth-child(1) { animation-delay:.04s; }
.tile_count .tile_stats_count:nth-child(2) { animation-delay:.08s; }
.tile_count .tile_stats_count:nth-child(3) { animation-delay:.12s; }
.tile_count .tile_stats_count:nth-child(4) { animation-delay:.16s; }

/* ════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════ */
@media (max-width: 767px) {
    .vi-header { flex-direction: column; align-items: flex-start; }
    .vi-chart-summary { gap: 16px; }
}
</style>


{{-- ═══ PAGE HEADER ═══ --}}
<div class="row">
<div class="col-md-12">
<div class="vi-header">
    <div>
        <div class="vi-header-title">
            <div class="icon-wrap"><i class="fa fa-brain"></i></div>
            VIOMIA AI Intelligence Center
        </div>
        <p class="vi-header-sub">AI decisions &nbsp;·&nbsp; signals &nbsp;·&nbsp; executions &nbsp;·&nbsp; learning performance</p>
    </div>
    <div class="vi-header-right">
        <div class="vi-clock-wrap"><i class="fa fa-clock-o" style="margin-right:5px"></i><span id="vi-clock">—</span></div>
        <div class="vi-badge-online"><span class="dot"></span>AI Online</div>
    </div>
</div>
</div>
</div>


{{-- ═══ KPI TILES — 3 COLUMNS ═══ --}}
<div class="vi-section">Key Performance Metrics</div>
<div class="row tile_count">

    {{-- TILE 1 · Signals + Executions --}}
    <div class="col-md-4 col-sm-12 tile_stats_count vi-teal">
        <div class="vi-tile-inner">
            <span class="count_top"><i class="fa fa-exchange"></i>Activity Today</span>
            <div class="count">{{ $signalsToday + $executionsToday }}</div>
            <span class="count_bottom">Total signals &amp; executions</span>
            <div class="vi-tile-substats">
                <div class="vi-substat">
                    <span class="vi-substat-icon teal"><i class="fa fa-satellite-dish"></i></span>
                    <div>
                        <div class="vi-substat-val">{{ $signalsToday }}</div>
                        <div class="vi-substat-label">Signals</div>
                    </div>
                </div>
                <div class="vi-substat-divider"></div>
                <div class="vi-substat">
                    <span class="vi-substat-icon blue"><i class="fa fa-bolt"></i></span>
                    <div>
                        <div class="vi-substat-val">{{ $executionsToday }}</div>
                        <div class="vi-substat-label">Executions</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vi-tile-ghost"><i class="fa fa-exchange"></i></div>
        <div class="vi-sparkline-wrap"><canvas id="spk1" height="44"></canvas></div>
    </div>

    {{-- TILE 2 · Win Rate + P&L --}}
    <div class="col-md-4 col-sm-12 tile_stats_count {{ $profitToday >= 0 ? 'vi-green' : 'vi-red' }}">
        <div class="vi-tile-inner">
            <span class="count_top"><i class="fa fa-line-chart"></i>Trading Performance</span>
            <div class="count {{ $winRate >= 60 ? 'green' : ($winRate < 40 ? 'red' : '') }}">
                {{ $winRate }}<span style="font-size:16px;font-weight:600">%</span>
            </div>
            <span class="count_bottom">Win rate &amp; profitability</span>
            <div class="vi-tile-substats">
                <div class="vi-substat">
                    <span class="vi-substat-icon orange"><i class="fa fa-bullseye"></i></span>
                    <div>
                        <div class="vi-substat-val">{{ $winRate }}%</div>
                        <div class="vi-substat-label">Win Rate</div>
                    </div>
                </div>
                <div class="vi-substat-divider"></div>
                <div class="vi-substat">
                    <span class="vi-substat-icon {{ $profitToday >= 0 ? 'green' : 'red' }}"><i class="fa fa-usd"></i></span>
                    <div>
                        <div class="vi-substat-val {{ $profitToday >= 0 ? 'pos' : 'neg' }}">{{ number_format($profitToday,2) }}</div>
                        <div class="vi-substat-label">AI P&amp;L</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vi-tile-ghost"><i class="fa fa-trophy"></i></div>
        <div class="vi-sparkline-wrap"><canvas id="spk2" height="44"></canvas></div>
    </div>

    {{-- TILE 3 · AI Quality + Wins/Losses --}}
    <div class="col-md-4 col-sm-12 tile_stats_count vi-purple">
        <div class="vi-tile-inner">
            <span class="count_top"><i class="fa fa-cog fa-spin" style="animation-duration:4s"></i>AI Quality Metrics</span>
            <div class="count">{{ $avgConfidence }}</div>
            <span class="count_bottom">Avg confidence · RR {{ $avgRR }} · {{ $winsToday }}W / {{ $lossToday }}L</span>
            <div class="vi-tile-substats">
                <div class="vi-substat">
                    <span class="vi-substat-icon purple"><i class="fa fa-brain"></i></span>
                    <div>
                        <div class="vi-substat-val">{{ $avgRR }}</div>
                        <div class="vi-substat-label">Avg RR</div>
                    </div>
                </div>
                <div class="vi-substat-divider"></div>
                <div class="vi-substat">
                    <span class="vi-substat-icon green"><i class="fa fa-check"></i></span>
                    <div>
                        <div class="vi-substat-val" style="color:var(--green)">{{ $winsToday }}</div>
                        <div class="vi-substat-label">Wins</div>
                    </div>
                </div>
                <div class="vi-substat-divider"></div>
                <div class="vi-substat">
                    <span class="vi-substat-icon red"><i class="fa fa-times"></i></span>
                    <div>
                        <div class="vi-substat-val" style="color:var(--red)">{{ $lossToday }}</div>
                        <div class="vi-substat-label">Losses</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vi-tile-ghost"><i class="fa fa-microchip"></i></div>
        <div class="vi-sparkline-wrap"><canvas id="spk3" height="44"></canvas></div>
    </div>

</div>


{{-- ═══ LIVE FEED ═══ --}}
<div class="vi-section">Live Feed</div>
<div class="row">

    <div class="col-md-6">
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti teal"><i class="fa fa-rss"></i></span>Last AI Signal</h2>
                @if($lastSignal)<span class="vi-pbadge teal">● Live</span>@endif
            </div>
            <div class="x_content">
                @if($lastSignal)
                <div class="vi-feed-card">
                    <div class="vi-feed-icon teal">📡</div>
                    <div style="flex:1;min-width:0">
                        <div>
                            <span class="vi-feed-symbol">{{ $lastSignal->symbol }}</span>
                            @php $dec = strtoupper($lastSignal->decision ?? ''); @endphp
                            <span class="vi-decision-pill {{ $dec }}">{{ $dec }}</span>
                        </div>
                        <div class="vi-feed-meta">
                            Entry Price &nbsp;<strong>{{ $lastSignal->entry ?? '—' }}</strong>
                        </div>
                        <div class="vi-feed-time"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($lastSignal->pushed_at)->diffForHumans() }}</div>
                        <div class="vi-feed-stats">
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Symbol</div>
                                <div class="vi-feed-stat-val">{{ $lastSignal->symbol ?? '—' }}</div>
                            </div>
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Decision</div>
                                <div class="vi-feed-stat-val">{{ $dec ?: '—' }}</div>
                            </div>
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Entry</div>
                                <div class="vi-feed-stat-val">{{ $lastSignal->entry ?? '—' }}</div>
                            </div>
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
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti orange"><i class="fa fa-bolt"></i></span>Last Execution</h2>
                @if($lastExecution)<span class="vi-pbadge orange">Executed</span>@endif
            </div>
            <div class="x_content">
                @if($lastExecution)
                <div class="vi-feed-card" style="border-left:3px solid var(--orange)">
                    <div class="vi-feed-icon orange">⚡</div>
                    <div style="flex:1;min-width:0">
                        <div>
                            <span class="vi-feed-symbol">{{ $lastExecution->symbol }}</span>
                            @php $dec2 = strtoupper($lastExecution->decision ?? ''); @endphp
                            <span class="vi-decision-pill {{ $dec2 }}">{{ $dec2 }}</span>
                        </div>
                        <div class="vi-feed-meta">
                            ML Confidence &nbsp;<strong>{{ $lastExecution->ml_confidence ?? '—' }}</strong>
                        </div>
                        @if(isset($lastExecution->created_at))
                        <div class="vi-feed-time"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($lastExecution->created_at)->diffForHumans() }}</div>
                        @endif
                        <div class="vi-feed-stats">
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Symbol</div>
                                <div class="vi-feed-stat-val">{{ $lastExecution->symbol ?? '—' }}</div>
                            </div>
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Decision</div>
                                <div class="vi-feed-stat-val">{{ $dec2 ?: '—' }}</div>
                            </div>
                            <div class="vi-feed-stat-item">
                                <div class="vi-feed-stat-label">Confidence</div>
                                <div class="vi-feed-stat-val">{{ $lastExecution->ml_confidence ?? '—' }}</div>
                            </div>
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


{{-- ═══ CHARTS ═══ --}}
<div class="vi-section">Performance Analytics</div>
<div class="row">

    {{-- Profit Curve --}}
    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti blue"><i class="fa fa-area-chart"></i></span>AI Profit Curve — 12 Months</h2>
                <span class="vi-pbadge blue">P&amp;L Trend</span>
            </div>
            <div class="x_content">
                <div class="vi-chart-summary">
                    @php
                        $totalPnl   = array_sum($monthlyProfit);
                        $bestMonth  = $months[array_search(max($monthlyProfit), $monthlyProfit)] ?? '—';
                        $worstMonth = $months[array_search(min($monthlyProfit), $monthlyProfit)] ?? '—';
                    @endphp
                    <div class="vi-chart-summary-item">
                        <div class="vi-chart-summary-label">12-Month Total</div>
                        <div class="vi-chart-summary-val {{ $totalPnl >= 0 ? 'pos' : 'neg' }}">
                            {{ ($totalPnl >= 0 ? '+' : '') }}{{ number_format($totalPnl, 2) }}
                        </div>
                    </div>
                    <div class="vi-chart-summary-item">
                        <div class="vi-chart-summary-label">Best Month</div>
                        <div class="vi-chart-summary-val" style="color:var(--text-1)">{{ $bestMonth }}</div>
                    </div>
                    <div class="vi-chart-summary-item">
                        <div class="vi-chart-summary-label">Worst Month</div>
                        <div class="vi-chart-summary-val" style="color:var(--text-1)">{{ $worstMonth }}</div>
                    </div>
                    <div class="vi-chart-summary-item">
                        <div class="vi-chart-summary-label">Avg / Month</div>
                        <div class="vi-chart-summary-val" style="color:var(--text-1)">{{ number_format($totalPnl/12, 2) }}</div>
                    </div>
                </div>
                <div class="vi-chart-container">
                    <canvas id="profitChart" height="110"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Signal Distribution --}}
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti purple"><i class="fa fa-pie-chart"></i></span>Signal Distribution</h2>
            </div>
            <div class="x_content">
                <div class="vi-doughnut-wrap" style="position:relative;max-width:200px;margin:0 auto">
                    <canvas id="signalChart" height="180"></canvas>
                    <div class="vi-doughnut-center">
                        <div class="big">{{ $signalBreakdown->sum('total') }}</div>
                        <div class="small">Total</div>
                    </div>
                </div>
                <div class="vi-sig-legend">
                    @php $sigTotal = $signalBreakdown->sum('total') ?: 1; @endphp
                    @foreach($signalBreakdown as $s)
                    @php
                        $pct    = round($s->total / $sigTotal * 100);
                        $sl     = strtoupper($s->decision);
                        $sColor = match(true) {
                            in_array($sl,['BUY','LONG'])    => '#1abb9c',
                            in_array($sl,['SELL','SHORT'])  => '#e74c3c',
                            $sl === 'HOLD'                  => '#e87e04',
                            default                         => '#3598dc',
                        };
                    @endphp
                    <div class="vi-sig-row">
                        <div class="vi-sig-dot" style="background:{{ $sColor }}"></div>
                        <div class="vi-sig-name">{{ $s->decision }}</div>
                        <div class="vi-sig-bar">
                            <div class="vi-sig-fill" style="width:{{ $pct }}%;background:{{ $sColor }}"></div>
                        </div>
                        <div class="vi-sig-pct">{{ $pct }}%</div>
                        <div class="vi-sig-count">{{ $s->total }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ═══ BREAKDOWN ═══ --}}
<div class="vi-section">Breakdown</div>
<div class="row">

    {{-- Symbol Performance --}}
    <div class="col-md-7">
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti teal"><i class="fa fa-bar-chart"></i></span>Top Symbols Performance</h2>
                <span class="vi-pbadge teal">Top 10</span>
            </div>
            <div class="x_content" style="padding:0 !important">
                @php $maxPnl = $symbolPerformance->max(fn($s) => abs($s->pnl)) ?: 1; @endphp
                <table class="vi-table">
                    <thead>
                        <tr>
                            <th width="32">#</th>
                            <th>Symbol</th>
                            <th>Trades</th>
                            <th>P&amp;L</th>
                            <th width="120">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($symbolPerformance as $i => $s)
                    <tr>
                        <td class="rank-num">{{ $i+1 }}</td>
                        <td class="bold">{{ $s->symbol }}</td>
                        <td><span class="vi-count-badge">{{ $s->trades }}</span></td>
                        <td class="{{ $s->pnl >= 0 ? 'pnl-pos' : 'pnl-neg' }}">
                            {{ ($s->pnl >= 0 ? '+' : '') }}{{ number_format($s->pnl,2) }}
                        </td>
                        <td>
                            <div class="vi-mbar">
                                <div class="vi-mbar-fill {{ $s->pnl >= 0 ? 'pos' : 'neg' }}"
                                     style="width:{{ round(abs($s->pnl)/$maxPnl*100) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="vi-empty">No data available</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Session + W/L --}}
    <div class="col-md-5">
        <div class="x_panel">
            <div class="x_title">
                <h2><span class="ti blue"><i class="fa fa-clock-o"></i></span>Session Activity</h2>
            </div>
            <div class="x_content">

                @php $maxTrades = $sessionStats->max('trades') ?: 1; @endphp
                @forelse($sessionStats as $s)
                <div class="vi-session-item">
                    <div class="vi-session-label">
                        <span class="vi-session-name">{{ $s->session_name }}</span>
                        <span class="vi-session-val">{{ $s->trades }}<span style="font-size:10px;font-weight:500;color:var(--text-4);margin-left:3px">trades</span></span>
                    </div>
                    <div class="vi-session-bar">
                        <div class="vi-session-fill" style="width:{{ round($s->trades/$maxTrades*100) }}%"></div>
                    </div>
                </div>
                @empty
                <div class="vi-empty"><i class="fa fa-clock-o"></i>No session data</div>
                @endforelse

                @if($executionsToday > 0)
                <div class="vi-wl-section">
                    <div class="vi-wl-title">Today's Win / Loss Ratio</div>

                    {{-- Win rate ring --}}
                    <div class="vi-wr-row">
                        <div class="vi-wr-circle-wrap">
                            <canvas id="winRateRing" width="90" height="90"></canvas>
                        </div>
                        <div class="vi-wr-detail">
                            <div class="big">{{ $winRate }}%</div>
                            <div class="label">Win Rate</div>
                            <div class="sub">
                                <span style="color:var(--green);font-weight:700">{{ $winsToday }} wins</span>
                                &nbsp;·&nbsp;
                                <span style="color:var(--red);font-weight:700">{{ $lossToday }} losses</span>
                            </div>
                        </div>
                    </div>

                    <div class="vi-wl-bar" style="margin-top:14px">
                        <div class="vi-wl-win"  style="flex:{{ $winsToday }}"></div>
                        <div class="vi-wl-loss" style="flex:{{ max($lossToday,0) }}"></div>
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


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ══════════════════════════════════
   LIVE CLOCK
   ══════════════════════════════════ */
(function tick(){
    const d   = new Date();
    const pad = n => String(n).padStart(2,'0');
    const el  = document.getElementById('vi-clock');
    if(el) el.textContent =
        d.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}) +
        '  ' + pad(d.getHours())+':'+pad(d.getMinutes())+':'+pad(d.getSeconds());
    setTimeout(tick,1000);
})();

/* ══════════════════════════════════
   CHART.JS DEFAULTS
   ══════════════════════════════════ */
Chart.defaults.font.family = "'Plus Jakarta Sans','Helvetica Neue',Arial,sans-serif";
Chart.defaults.font.size   = 11.5;
Chart.defaults.color       = '#95a5a6';

/* ══════════════════════════════════
   SPARKLINES (8 tiles)
   ══════════════════════════════════ */
function sparkline(id, data, color, fill) {
    const el = document.getElementById(id);
    if(!el) return;
    new Chart(el, {
        type: 'line',
        data: {
            labels: data.map((_,i)=>i),
            datasets: [{
                data,
                borderColor: color,
                borderWidth: 1.8,
                pointRadius: 0,
                fill: fill ? 'origin' : false,
                backgroundColor: color + '22',
                tension: 0.4
            }]
        },
        options: {
            responsive: false,
            animation: false,
            plugins: { legend:{display:false}, tooltip:{enabled:false} },
            scales: {
                x: { display:false },
                y: { display:false }
            },
            layout: { padding: 0 }
        }
    });
}

/* Placeholder sparkline data — swap with real historical arrays from controller */
const rnd = (n,lo,hi) => Array.from({length:n},()=> lo + Math.random()*(hi-lo));

/* Tile 1 · Activity (signals + executions) */
sparkline('spk1', rnd(14, 3, 20),  '#1abb9c', true);
/* Tile 2 · Trading performance (win rate trend) */
sparkline('spk2', rnd(14, 40, 85), '{{ $profitToday >= 0 ? '#26c281' : '#e74c3c' }}', true);
/* Tile 3 · AI confidence */
sparkline('spk3', rnd(14, 0.5, 1.0),'#8e44ad', false);

/* ══════════════════════════════════
   PROFIT CURVE
   ══════════════════════════════════ */
const pCtx = document.getElementById('profitChart').getContext('2d');

/* Gradient fill — green above zero, red below */
const months        = @json($months);
const monthlyProfit = @json($monthlyProfit);

const areaGrad = pCtx.createLinearGradient(0, 0, 0, 300);
areaGrad.addColorStop(0,   'rgba(53,152,220,0.20)');
areaGrad.addColorStop(0.6, 'rgba(53,152,220,0.05)');
areaGrad.addColorStop(1,   'rgba(53,152,220,0.00)');

const profitColors = monthlyProfit.map(v => v >= 0 ? '#26c281' : '#e74c3c');

new Chart(pCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            /* Bar underneath */
            {
                type: 'bar',
                label: 'Monthly P&L',
                data: monthlyProfit,
                backgroundColor: profitColors.map(c => c + '30'),
                borderColor:     profitColors,
                borderWidth: 1.5,
                borderRadius: 4,
                order: 2
            },
            /* Line on top */
            {
                type: 'line',
                label: 'Trend',
                data: monthlyProfit,
                borderColor: '#3598dc',
                borderWidth: 2.5,
                backgroundColor: areaGrad,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3598dc',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4,
                order: 1
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a2535',
                titleColor: '#bdc3c7',
                bodyColor: '#fff',
                borderColor: '#2c3e50',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: ctx => (ctx.dataset.label === 'Trend' ? '  Trend: ' : '  P&L:   ') + ctx.parsed.y.toFixed(2)
                }
            }
        },
        scales: {
            x: {
                grid:   { display: false },
                border: { color: '#e8ecf1' },
                ticks:  { color: '#bdc3c7', padding: 6 }
            },
            y: {
                grid:   { color: '#f5f7fa', drawTicks: false },
                border: { color: '#e8ecf1', dash: [3,3] },
                ticks:  { color: '#bdc3c7', padding: 6 },
                /* Zero line */
                afterDataLimits(scale) {
                    scale.max = Math.max(scale.max, 0);
                    scale.min = Math.min(scale.min, 0);
                }
            }
        }
    }
});

/* ══════════════════════════════════
   SIGNAL DOUGHNUT
   ══════════════════════════════════ */
const sigLabels  = @json($signalBreakdown->pluck('decision'));
const sigData    = @json($signalBreakdown->pluck('total'));

const sigPalette = sigLabels.map(l => {
    const u = String(l).toUpperCase();
    if(['BUY','LONG'].includes(u))   return '#1abb9c';
    if(['SELL','SHORT'].includes(u)) return '#e74c3c';
    if(u === 'HOLD')                 return '#e87e04';
    return '#3598dc';
});

new Chart(document.getElementById('signalChart'), {
    type: 'doughnut',
    data: {
        labels: sigLabels,
        datasets: [{
            data:            sigData,
            backgroundColor: sigPalette.map(c => c + '28'),
            borderColor:     sigPalette,
            borderWidth: 2.5,
            hoverOffset: 8
        }]
    },
    options: {
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a2535',
                titleColor: '#bdc3c7',
                bodyColor: '#fff',
                borderColor: '#2c3e50',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8
            }
        },
        animation: { animateRotate: true, duration: 900 }
    }
});

/* ══════════════════════════════════
   WIN RATE RING (small gauge)
   ══════════════════════════════════ */
const wrEl = document.getElementById('winRateRing');
if(wrEl) {
    new Chart(wrEl, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $winRate }}, {{ 100 - $winRate }}],
                backgroundColor: ['#26c281', '#f0f3f7'],
                borderWidth: 0,
                hoverOffset: 0
            }]
        },
        options: {
            cutout: '78%',
            plugins: { legend:{display:false}, tooltip:{enabled:false} },
            animation: { animateRotate: true, duration: 1000 }
        }
    });
}
</script>
@endsection