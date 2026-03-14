@extends('layouts.user')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

  .hist * { box-sizing: border-box; }
  .hist {
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    color: #1e293b;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
  }

  /* ── PAGE HEADER ── */
  .hist-hdr { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
  .hist-hdr h1 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .hist-breadcrumb { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
  .hist-breadcrumb a { color: #6366f1; text-decoration: none; }
  .hist-breadcrumb a:hover { text-decoration: underline; }

  /* ── MAIN CARD ── */
  .hist-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }

  /* ── FILTER BAR ── */
  .hist-filter {
    background: #fcfcfc; padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .hist-search-wrap { position: relative; flex: 1; min-width: 220px; display: flex; gap: 8px; flex-wrap: wrap; }
  .hist-search-item { flex: 1; min-width: 160px; }
  .hist-search-item input, .hist-search-item select {
    width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12px; font-family: 'Inter', sans-serif;
    outline: none; color: #1e293b;
  }
  .hist-search-item input:focus, .hist-search-item select:focus { border-color: #6366f1; }
  .hist-stats { display: flex; gap: 20px; }
  .hist-stat { text-align: right; }
  .hist-stat-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .hist-stat-val { font-size: 16px; font-weight: 700; line-height: 1.2; margin-top: 2px; }

  /* ── TABLE ── */
  .hist-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .hist-tbl thead th {
    background: #f8fafc; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; font-weight: 700; padding: 10px 12px; border-bottom: 2px solid #f1f5f9; white-space: nowrap;
  }
  .hist-tbl tbody tr.hist-row { border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.15s; }
  .hist-tbl tbody tr.hist-row:hover { background: #f8f9ff; }
  .hist-tbl tbody tr.hist-row.hist-expanded { background: #f0f4ff; }
  .hist-tbl tbody td { padding: 10px 12px; vertical-align: middle; }

  /* ── BADGES ── */
  .hist-badge {
    font-size: 9.5px; padding: 3px 9px; border-radius: 12px;
    font-weight: 700; display: inline-flex; align-items: center; gap: 3px;
  }
  .b-buy    { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-sell   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-profit { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-loss   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-closed { background: #f3f4f6; color: #374151;  border: 1px solid #d1d5db; }

  /* ── DETAIL ROW ── */
  .hist-detail-row td { padding: 0; background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
  .hist-detail-inner {
    padding: 20px; display: grid;
    grid-template-columns: repeat(4, 1fr); gap: 14px;
  }
  .hist-detail-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
  .hist-detail-hdr  { padding: 10px 14px; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
  .hist-detail-body { padding: 14px; }
  .hist-dm { margin-bottom: 12px; }
  .hist-dm:last-child { margin-bottom: 0; }
  .hist-dm-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
  .hist-dm-val { font-size: 14px; font-weight: 700; color: #0f172a; }

  /* ── EMPTY STATE ── */
  .hist-empty { text-align: center; padding: 48px; color: #94a3b8; }
  .hist-empty .ico { font-size: 40px; opacity: 0.3; margin-bottom: 12px; }

  /* ── FILTERS SECTION ── */
  .hist-filters-wrap { background: #fcfcfc; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  .hist-filter-item { display: flex; gap: 8px; align-items: center; }
  .hist-filter-badge { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
  .hist-filter-badge .close { cursor: pointer; opacity: 0.6; font-weight: bold; }
  .hist-filter-badge .close:hover { opacity: 1; }
</style>

<div class="hist">

  {{-- ── HEADER ── --}}
  <div class="hist-hdr">
    <div>
      <h1><i class="fa fa-history mr-2 text-success"></i> Trade History</h1>
      <div class="hist-breadcrumb">
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span style="color:#1e293b;">Trade History</span>
      </div>
    </div>
  </div>

  {{-- ── MAIN CARD ── --}}
  <div class="hist-card">

    {{-- Filter Bar --}}
    <div class="hist-filter">
      <div class="hist-search-wrap">
        <div class="hist-search-item">
          <input type="text" id="histSearch" placeholder="Search by ticket, symbol...">
        </div>
        <div class="hist-search-item">
          <input type="text" name="symbol" id="histSymbolFilter" placeholder="Symbol" value="{{ $symbol }}">
        </div>
        <div class="hist-search-item">
          <select id="histTypeFilter">
            <option value="">All Types</option>
            <option value="BUY" {{ $type === 'BUY' ? 'selected' : '' }}>BUY</option>
            <option value="SELL" {{ $type === 'SELL' ? 'selected' : '' }}>SELL</option>
          </select>
        </div>
        <div class="hist-search-item">
          <input type="date" name="from" id="histFromFilter" placeholder="From" value="{{ $from }}">
        </div>
        <div class="hist-search-item">
          <input type="date" name="to" id="histToFilter" placeholder="To" value="{{ $to }}">
        </div>
      </div>
      <div class="hist-stats">
        <div class="hist-stat">
          <div class="hist-stat-lbl">Total Trades</div>
          <div class="hist-stat-val" style="color:#0f172a;">{{ $trades->total() }}</div>
        </div>
        <div class="hist-stat">
          <div class="hist-stat-lbl">Profitable</div>
          <div class="hist-stat-val" style="color:#10b981;">{{ $trades->where('profit', '>', 0)->count() }}</div>
        </div>
        <div class="hist-stat">
          <div class="hist-stat-lbl">Losing</div>
          <div class="hist-stat-val" style="color:#ef4444;">{{ $trades->where('profit', '<', 0)->count() }}</div>
        </div>
      </div>
    </div>

    {{-- Active Filters --}}
    @if($q || $symbol || $type || $from || $to)
      <div class="hist-filters-wrap">
        @if($q)
          <div class="hist-filter-item">
            <span style="color:#64748b;font-size:11px;font-weight:600;">Filters:</span>
            <span class="hist-filter-badge">
              Search: <strong>{{ $q }}</strong>
              <a href="{{ route('user.trades.history', array_merge(request()->query(), ['q' => null])) }}" class="close">×</a>
            </span>
          </div>
        @endif
        @if($symbol)
          <span class="hist-filter-badge">
            Symbol: <strong>{{ $symbol }}</strong>
            <a href="{{ route('user.trades.history', array_merge(request()->query(), ['symbol' => null])) }}" class="close">×</a>
          </span>
        @endif
        @if($type)
          <span class="hist-filter-badge">
            Type: <strong>{{ $type }}</strong>
            <a href="{{ route('user.trades.history', array_merge(request()->query(), ['type' => null])) }}" class="close">×</a>
          </span>
        @endif
        @if($from)
          <span class="hist-filter-badge">
            From: <strong>{{ $from }}</strong>
            <a href="{{ route('user.trades.history', array_merge(request()->query(), ['from' => null])) }}" class="close">×</a>
          </span>
        @endif
        @if($to)
          <span class="hist-filter-badge">
            To: <strong>{{ $to }}</strong>
            <a href="{{ route('user.trades.history', array_merge(request()->query(), ['to' => null])) }}" class="close">×</a>
          </span>
        @endif
      </div>
    @endif

    {{-- Table --}}
    <div style="overflow-x:auto;">
      <table class="hist-tbl" id="histTable">
        <thead>
          <tr>
            <th style="width:44px;">#</th>
            <th>Ticket</th>
            <th>Symbol</th>
            <th style="text-align:center;">Type</th>
            <th style="text-align:right;">Lots</th>
            <th style="text-align:right;">Open Price</th>
            <th style="text-align:right;">Close Price</th>
            <th style="text-align:right;">Profit/Loss</th>
            <th>Closed</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trades as $key => $trade)
            @php
              $profitColor = ($trade->profit ?? 0) >= 0 ? '#10b981' : '#ef4444';
              $profitBadge = ($trade->profit ?? 0) >= 0 ? 'b-profit' : 'b-loss';
            @endphp
            <tr class="hist-row" data-id="{{ $trade->id }}">
              <td>
                <div style="width:30px;height:30px;border-radius:50%;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#64748b;">
                  {{ $trades->firstItem() + $key }}
                </div>
              </td>

              <td>
                <div style="font-weight:700;font-size:13px;color:#0f172a;font-family:monospace;">{{ $trade->ticket ?? '—' }}</div>
              </td>

              <td>
                <div style="font-weight:700;font-size:13px;color:#0f172a;">{{ $trade->symbol ?? '—' }}</div>
              </td>

              <td style="text-align:center;">
                <span class="hist-badge {{ $trade->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                  <i class="fa {{ $trade->type === 'BUY' ? 'fa-arrow-up' : 'fa-arrow-down' }}" style="font-size:8px;"></i>
                  {{ strtoupper($trade->type ?? '—') }}
                </span>
              </td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">{{ $trade->lots ?? '—' }}</td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">${{ number_format($trade->open_price ?? 0, 4) }}</td>

              <td style="text-align:right;font-weight:600;color:#0f172a;">${{ number_format($trade->close_price ?? 0, 4) }}</td>

              <td style="text-align:right;">
                <span class="hist-badge {{ $profitBadge }}">
                  <i class="fa {{ $profitColor === '#10b981' ? 'fa-arrow-up' : 'fa-arrow-down' }}" style="font-size:8px;"></i>
                  {{ number_format($trade->profit ?? 0, 2) }}
                </span>
              </td>

              <td style="font-size:11px;color:#64748b;">{{ $trade->updated_at?->format('M d, Y H:i') ?? '—' }}</td>
            </tr>

            {{-- Expandable Detail Row --}}
            <tr class="hist-detail-row" id="hist-detail-{{ $trade->id }}" style="display:none;">
              <td colspan="9">
                <div class="hist-detail-inner">

                  {{-- Trade Info --}}
                  <div class="hist-detail-card">
                    <div class="hist-detail-hdr" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                      <i class="fa fa-info-circle mr-2"></i> Trade Info
                    </div>
                    <div class="hist-detail-body">
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Ticket</div>
                        <div class="hist-dm-val" style="font-family:monospace;font-size:12px;">{{ $trade->ticket ?? 'N/A' }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Symbol</div>
                        <div class="hist-dm-val">{{ $trade->symbol ?? 'N/A' }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Type</div>
                        <div style="margin-top:4px;">
                          <span class="hist-badge {{ $trade->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                            {{ strtoupper($trade->type ?? 'N/A') }}
                          </span>
                        </div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Volume</div>
                        <div class="hist-dm-val">{{ $trade->lots ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Pricing Info --}}
                  <div class="hist-detail-card">
                    <div class="hist-detail-hdr" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                      <i class="fa fa-dollar mr-2"></i> Pricing
                    </div>
                    <div class="hist-detail-body">
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Open Price</div>
                        <div class="hist-dm-val">${{ number_format($trade->open_price ?? 0, 4) }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Close Price</div>
                        <div class="hist-dm-val">${{ number_format($trade->close_price ?? 0, 4) }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">SL (Stop Loss)</div>
                        <div class="hist-dm-val">{{ $trade->sl ?? 'N/A' }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">TP (Take Profit)</div>
                        <div class="hist-dm-val">{{ $trade->tp ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- P&L Info --}}
                  <div class="hist-detail-card">
                    <div class="hist-detail-hdr" style="background:linear-gradient(135deg,{{ $profitColor === '#10b981' ? '#10b981' : '#ef4444' }},{{ $profitColor === '#10b981' ? '#059669' : '#dc2626' }});">
                      <i class="fa {{ $profitColor === '#10b981' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-2"></i> Performance
                    </div>
                    <div class="hist-detail-body">
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Profit/Loss</div>
                        <div class="hist-dm-val" style="color:{{ $profitColor }};">${{ number_format($trade->profit ?? 0, 2) }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Status</div>
                        <div style="margin-top:4px;">
                          <span class="hist-badge b-closed">
                            <i class="fa fa-check-circle" style="font-size:8px;"></i> Closed
                          </span>
                        </div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Opened</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $trade->created_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                      </div>
                      <div class="hist-dm">
                        <div class="hist-dm-lbl">Duration</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $trade->created_at?->diffForHumans($trade->updated_at) ?? 'N/A' }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Full Details --}}
                  <div style="grid-column: 1 / -1; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <div style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                      <i class="fa fa-list mr-2"></i> Full Trade Data
                    </div>
                    <div style="padding: 14px;">
                      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; font-size: 12px;">
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Account ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $trade->account_id ?? 'N/A' }}</div>
                        </div>
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Trade ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $trade->id ?? 'N/A' }}</div>
                        </div>
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Closed At</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $trade->updated_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="9">
                <div class="hist-empty">
                  <div class="ico"><i class="fa fa-inbox"></i></div>
                  <p style="font-size:15px;font-weight:600;margin-bottom:6px;color:#475569;">No trade history</p>
                  <p style="font-size:12px;margin:0;">Once you execute trades, they will appear here with detailed performance metrics</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($trades->hasPages())
      <div style="padding: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; gap: 8px;">
        {{ $trades->links('pagination::bootstrap-4') }}
      </div>
    @endif

  </div>{{-- end main card --}}

</div>{{-- end .hist --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  (function () {
    'use strict';

    /* ── EXPANDABLE ROWS ── */
    document.querySelectorAll('.hist-row').forEach(function (row) {
      row.addEventListener('click', function () {
        var id     = this.dataset.id;
        var detail = document.getElementById('hist-detail-' + id);
        if (!detail) return;
        var isOpen = detail.style.display !== 'none';
        document.querySelectorAll('.hist-detail-row').forEach(function (d) { d.style.display = 'none'; });
        document.querySelectorAll('.hist-row').forEach(function (r) { r.classList.remove('hist-expanded'); });
        if (!isOpen) {
          detail.style.display = 'table-row';
          this.classList.add('hist-expanded');
        }
      });
    });

    /* ── SEARCH ── */
    var searchInput = document.getElementById('histSearch');
    if (searchInput) {
      searchInput.addEventListener('keyup', function () {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#histTable tbody tr.hist-row').forEach(function (tr) {
          tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
      });
    }

    /* ── FILTER SUBMISSION ── */
    var symbolFilter = document.getElementById('histSymbolFilter');
    var typeFilter = document.getElementById('histTypeFilter');
    var fromFilter = document.getElementById('histFromFilter');
    var toFilter = document.getElementById('histToFilter');

    function applyFilters() {
      var params = new URLSearchParams();
      if (symbolFilter && symbolFilter.value) params.append('symbol', symbolFilter.value);
      if (typeFilter && typeFilter.value) params.append('type', typeFilter.value);
      if (fromFilter && fromFilter.value) params.append('from', fromFilter.value);
      if (toFilter && toFilter.value) params.append('to', toFilter.value);
      
      var url = '{{ route("user.trades.history") }}' + (params.toString() ? '?' + params.toString() : '');
      window.location.href = url;
    }

    if (symbolFilter) symbolFilter.addEventListener('change', applyFilters);
    if (typeFilter) typeFilter.addEventListener('change', applyFilters);
    if (fromFilter) fromFilter.addEventListener('change', applyFilters);
    if (toFilter) toFilter.addEventListener('change', applyFilters);

  })();
});
</script>
@endpush
