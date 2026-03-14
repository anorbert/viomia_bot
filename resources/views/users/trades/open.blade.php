@extends('layouts.user')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

  .opos * { box-sizing: border-box; }
  .opos {
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    color: #1e293b;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
  }

  /* ── PAGE HEADER ── */
  .opos-hdr { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
  .opos-hdr h1 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .opos-breadcrumb { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
  .opos-breadcrumb a { color: #6366f1; text-decoration: none; }
  .opos-breadcrumb a:hover { text-decoration: underline; }

  /* ── ALERTS ── */
  .opos-alert-success {
    background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 8px;
    padding: 10px 14px; font-size: 12.5px; color: #15803d;
    display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
  }

  /* ── MAIN CARD ── */
  .opos-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }

  /* ── FILTER BAR ── */
  .opos-filter {
    background: #fcfcfc; padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .opos-search-wrap { position: relative; flex: 1; min-width: 220px; display: flex; gap: 8px; }
  .opos-search-item { flex: 1; min-width: 200px; }
  .opos-search-item select, .opos-search-item input {
    width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif;
    outline: none; color: #1e293b;
  }
  .opos-search-item input:focus, .opos-search-item select:focus { border-color: #6366f1; }
  .opos-stats { display: flex; gap: 20px; }
  .opos-stat { text-align: right; }
  .opos-stat-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .opos-stat-val { font-size: 16px; font-weight: 700; line-height: 1.2; margin-top: 2px; }

  /* ── TABLE ── */
  .opos-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .opos-tbl thead th {
    background: #f8fafc; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; font-weight: 700; padding: 10px 12px; border-bottom: 2px solid #f1f5f9; white-space: nowrap;
  }
  .opos-tbl tbody tr.opos-row { border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.15s; }
  .opos-tbl tbody tr.opos-row:hover { background: #f8f9ff; }
  .opos-tbl tbody tr.opos-row.opos-expanded { background: #f0f4ff; }
  .opos-tbl tbody td { padding: 10px 12px; vertical-align: middle; }

  /* ── BADGES ── */
  .opos-badge {
    font-size: 9.5px; padding: 3px 9px; border-radius: 12px;
    font-weight: 700; display: inline-flex; align-items: center; gap: 3px;
  }
  .b-buy    { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-sell   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-profit { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-loss   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }

  /* ── DETAIL ROW ── */
  .opos-detail-row td { padding: 0; background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
  .opos-detail-inner {
    padding: 20px; display: grid;
    grid-template-columns: repeat(4, 1fr); gap: 14px;
  }
  .opos-detail-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
  .opos-detail-hdr  { padding: 10px 14px; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
  .opos-detail-body { padding: 14px; }
  .opos-dm { margin-bottom: 12px; }
  .opos-dm:last-child { margin-bottom: 0; }
  .opos-dm-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
  .opos-dm-val { font-size: 14px; font-weight: 700; color: #0f172a; }

  /* ── EMPTY STATE ── */
  .opos-empty { text-align: center; padding: 48px; color: #94a3b8; }
  .opos-empty .ico { font-size: 40px; opacity: 0.3; margin-bottom: 12px; }

  /* ── FILTERS SECTION ── */
  .opos-filters-wrap { background: #fcfcfc; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  .opos-filter-item { display: flex; gap: 8px; align-items: center; }
  .opos-filter-badge { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
  .opos-filter-badge .close { cursor: pointer; opacity: 0.6; font-weight: bold; }
  .opos-filter-badge .close:hover { opacity: 1; }
</style>

<div class="opos">

  {{-- ── HEADER ── --}}
  <div class="opos-hdr">
    <div>
      <h1><i class="fa fa-list mr-2 text-info"></i> Open Positions</h1>
      <div class="opos-breadcrumb">
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span style="color:#1e293b;">Open Positions</span>
      </div>
    </div>
  </div>

  {{-- ── ALERTS ── --}}
  @if(session('success'))
    <div class="opos-alert-success">
      <i class="fa fa-check-circle" style="font-size:15px;"></i>
      <div><strong>Success!</strong> {{ session('success') }}</div>
    </div>
  @endif

  {{-- ── MAIN CARD ── --}}
  <div class="opos-card">

    {{-- Filter Bar --}}
    <div class="opos-filter">
      <div class="opos-search-wrap">
        <div class="opos-search-item">
          <input type="text" id="oposSearch" placeholder="Search by ticket, symbol...">
        </div>
        <div class="opos-search-item">
          <select id="oposSymbolFilter">
            <option value="">All Symbols</option>
            @foreach($symbols as $sym)
              <option value="{{ $sym }}" {{ $symbol === $sym ? 'selected' : '' }}>{{ $sym }}</option>
            @endforeach
          </select>
        </div>
        <div class="opos-search-item">
          <select id="oposTypeFilter">
            <option value="">All Types</option>
            <option value="BUY" {{ $type === 'BUY' ? 'selected' : '' }}>BUY</option>
            <option value="SELL" {{ $type === 'SELL' ? 'selected' : '' }}>SELL</option>
          </select>
        </div>
      </div>
      <div class="opos-stats">
        <div class="opos-stat">
          <div class="opos-stat-lbl">Total Open</div>
          <div class="opos-stat-val" style="color:#0f172a;">{{ $positions->total() }}</div>
        </div>
        <div class="opos-stat">
          <div class="opos-stat-lbl">Buys</div>
          <div class="opos-stat-val" style="color:#10b981;">{{ $buyCount }}</div>
        </div>
        <div class="opos-stat">
          <div class="opos-stat-lbl">Sells</div>
          <div class="opos-stat-val" style="color:#ef4444;">{{ $sellCount }}</div>
        </div>
        <div class="opos-stat">
          <div class="opos-stat-lbl">Profit/Loss</div>
          <div class="opos-stat-val" style="color:{{ $totalProfit >= 0 ? '#10b981' : '#ef4444' }};">${{ number_format($totalProfit, 2) }}</div>
        </div>
      </div>
    </div>

    {{-- Active Filters --}}
    @if($symbol || $type)
      <div class="opos-filters-wrap">
        @if($symbol)
          <div class="opos-filter-item">
            <span style="color:#64748b;font-size:11px;font-weight:600;">Filters:</span>
            <span class="opos-filter-badge">
              Symbol: <strong>{{ $symbol }}</strong>
              <a href="{{ route('user.trades.open', array_merge(request()->query(), ['symbol' => null])) }}" class="close">×</a>
            </span>
          </div>
        @endif
        @if($type)
          <div class="opos-filter-item">
            <span class="opos-filter-badge">
              Type: <strong>{{ $type }}</strong>
              <a href="{{ route('user.trades.open', array_merge(request()->query(), ['type' => null])) }}" class="close">×</a>
            </span>
          </div>
        @endif
      </div>
    @endif

    {{-- Table --}}
    <div style="overflow-x:auto;">
      <table class="opos-tbl" id="oposTable">
        <thead>
          <tr>
            <th style="width:44px;">#</th>
            <th>Ticket</th>
            <th>Symbol</th>
            <th style="text-align:center;">Type</th>
            <th style="text-align:right;">Lots</th>
            <th style="text-align:right;">Open Price</th>
            <th style="text-align:right;">Current</th>
            <th style="text-align:right;">Profit/Loss</th>
            <th style="text-align:center;">Status</th>
            <th>Opened</th>
          </tr>
        </thead>
        <tbody>
          @forelse($positions as $key => $pos)
            @php
              $profitColor = ($pos->profit ?? 0) >= 0 ? '#10b981' : '#ef4444';
              $profitBadge = ($pos->profit ?? 0) >= 0 ? 'b-profit' : 'b-loss';
            @endphp
            <tr class="opos-row" data-id="{{ $pos->id }}">
              <td>
                <div style="width:30px;height:30px;border-radius:50%;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#64748b;">
                  {{ $positions->firstItem() + $key }}
                </div>
              </td>

              <td>
                <div style="font-weight:700;font-size:13px;color:#0f172a;font-family:monospace;">{{ $pos->ticket ?? '—' }}</div>
              </td>

              <td>
                <div style="font-weight:700;font-size:13px;color:#0f172a;">{{ $pos->symbol ?? '—' }}</div>
              </td>

              <td style="text-align:center;">
                <span class="opos-badge {{ $pos->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                  <i class="fa {{ $pos->type === 'BUY' ? 'fa-arrow-up' : 'fa-arrow-down' }}" style="font-size:8px;"></i>
                  {{ strtoupper($pos->type ?? '—') }}
                </span>
              </td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">{{ $pos->lots ?? '—' }}</td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">${{ number_format($pos->open_price ?? 0, 4) }}</td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">${{ number_format($pos->close_price ?? 0, 4) }}</td>

              <td style="text-align:right;">
                <div style="font-weight:700;font-size:13px;color:{{ $profitColor }};">${{ number_format($pos->profit ?? 0, 2) }}</div>
              </td>

              <td style="text-align:center;">
                <span class="opos-badge" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;">
                  <i class="fa fa-circle" style="font-size:7px;"></i> {{ ucfirst($pos->status ?? 'open') }}
                </span>
              </td>

              <td style="font-size:11px;color:#64748b;">{{ $pos->created_at?->diffForHumans() ?? '—' }}</td>
            </tr>

            {{-- Expandable Detail Row --}}
            <tr class="opos-detail-row" id="opos-detail-{{ $pos->id }}" style="display:none;">
              <td colspan="10">
                <div class="opos-detail-inner">

                  {{-- Position Info --}}
                  <div class="opos-detail-card">
                    <div class="opos-detail-hdr" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                      <i class="fa fa-info-circle mr-2"></i> Position Info
                    </div>
                    <div class="opos-detail-body">
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Ticket</div>
                        <div class="opos-dm-val" style="font-family:monospace;font-size:12px;">{{ $pos->ticket ?? 'N/A' }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Symbol</div>
                        <div class="opos-dm-val">{{ $pos->symbol ?? 'N/A' }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Type</div>
                        <div style="margin-top:4px;">
                          <span class="opos-badge {{ $pos->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                            {{ strtoupper($pos->type ?? 'N/A') }}
                          </span>
                        </div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Volume</div>
                        <div class="opos-dm-val">{{ $pos->lots ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Pricing Info --}}
                  <div class="opos-detail-card">
                    <div class="opos-detail-hdr" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                      <i class="fa fa-dollar mr-2"></i> Pricing
                    </div>
                    <div class="opos-detail-body">
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Open Price</div>
                        <div class="opos-dm-val">${{ number_format($pos->open_price ?? 0, 4) }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Current Price</div>
                        <div class="opos-dm-val">${{ number_format($pos->close_price ?? 0, 4) }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">SL (Stop Loss)</div>
                        <div class="opos-dm-val">{{ $pos->sl ?? 'N/A' }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">TP (Take Profit)</div>
                        <div class="opos-dm-val">{{ $pos->tp ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- P&L Info --}}
                  <div class="opos-detail-card">
                    <div class="opos-detail-hdr" style="background:linear-gradient(135deg,{{ $profitColor === '#10b981' ? '#10b981' : '#ef4444' }},{{ $profitColor === '#10b981' ? '#059669' : '#dc2626' }});">
                      <i class="fa {{ $profitColor === '#10b981' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-2"></i> Performance
                    </div>
                    <div class="opos-detail-body">
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Profit/Loss</div>
                        <div class="opos-dm-val" style="color:{{ $profitColor }};">${{ number_format($pos->profit ?? 0, 2) }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Status</div>
                        <div style="margin-top:4px;">
                          <span class="opos-badge" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;">
                            {{ ucfirst($pos->status ?? 'open') }}
                          </span>
                        </div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Opened</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $pos->created_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                      </div>
                      <div class="opos-dm">
                        <div class="opos-dm-lbl">Age</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $pos->created_at?->diffForHumans() ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Full Details --}}
                  <div style="grid-column: 1 / -1; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <div style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                      <i class="fa fa-list mr-2"></i> Full Position Data
                    </div>
                    <div style="padding: 14px;">
                      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; font-size: 12px;">
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Account ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $pos->account_id ?? 'N/A' }}</div>
                        </div>
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Trade ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $pos->id ?? 'N/A' }}</div>
                        </div>
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Last Updated</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $pos->updated_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="10">
                <div class="opos-empty">
                  <div class="ico"><i class="fa fa-folder-open-o"></i></div>
                  <p style="font-size:15px;font-weight:600;margin-bottom:6px;color:#475569;">No open positions</p>
                  <p style="font-size:12px;margin:0;">All your positions are currently closed or you haven't made any trades yet</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($positions->hasPages())
      <div style="padding: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; gap: 8px;">
        {{ $positions->links('pagination::bootstrap-4') }}
      </div>
    @endif

  </div>{{-- end main card --}}

</div>{{-- end .opos --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  (function () {
    'use strict';

    /* ── EXPANDABLE ROWS ── */
    document.querySelectorAll('.opos-row').forEach(function (row) {
      row.addEventListener('click', function () {
        var id     = this.dataset.id;
        var detail = document.getElementById('opos-detail-' + id);
        if (!detail) return;
        var isOpen = detail.style.display !== 'none';
        document.querySelectorAll('.opos-detail-row').forEach(function (d) { d.style.display = 'none'; });
        document.querySelectorAll('.opos-row').forEach(function (r) { r.classList.remove('opos-expanded'); });
        if (!isOpen) {
          detail.style.display = 'table-row';
          this.classList.add('opos-expanded');
        }
      });
    });

    /* ── SEARCH ── */
    var searchInput = document.getElementById('oposSearch');
    if (searchInput) {
      searchInput.addEventListener('keyup', function () {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#oposTable tbody tr.opos-row').forEach(function (tr) {
          tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
      });
    }

    /* ── FILTER SELECTS ── */
    var symbolFilter = document.getElementById('oposSymbolFilter');
    var typeFilter = document.getElementById('oposTypeFilter');

    function applyFilters() {
      var symbolVal = symbolFilter ? symbolFilter.value : '';
      var typeVal = typeFilter ? typeFilter.value : '';
      
      var params = new URLSearchParams();
      if (symbolVal) params.append('symbol', symbolVal);
      if (typeVal) params.append('type', typeVal);
      
      var url = '{{ route("user.trades.open") }}' + (params.toString() ? '?' + params.toString() : '');
      window.location.href = url;
    }

    if (symbolFilter) {
      symbolFilter.addEventListener('change', applyFilters);
    }
    if (typeFilter) {
      typeFilter.addEventListener('change', applyFilters);
    }

  })();
});
</script>
@endpush
