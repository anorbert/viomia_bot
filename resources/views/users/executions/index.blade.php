@extends('layouts.user')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

  .exec * { box-sizing: border-box; }
  .exec {
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    color: #1e293b;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
  }

  /* ── PAGE HEADER ── */
  .exec-hdr { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
  .exec-hdr h1 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .exec-breadcrumb { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
  .exec-breadcrumb a { color: #6366f1; text-decoration: none; }
  .exec-breadcrumb a:hover { text-decoration: underline; }

  /* ── ALERTS ── */
  .exec-alert-success {
    background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 8px;
    padding: 10px 14px; font-size: 12.5px; color: #15803d;
    display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
  }

  /* ── MAIN CARD ── */
  .exec-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }

  /* ── FILTER BAR ── */
  .exec-filter {
    background: #fcfcfc; padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .exec-search-wrap { position: relative; flex: 1; min-width: 220px; }
  .exec-search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; }
  .exec-search-input {
    width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12.5px; font-family: 'Inter', sans-serif;
    outline: none; color: #1e293b;
  }
  .exec-search-input:focus { border-color: #6366f1; }
  .exec-stats { display: flex; gap: 20px; }
  .exec-stat { text-align: right; }
  .exec-stat-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .exec-stat-val { font-size: 16px; font-weight: 700; line-height: 1.2; margin-top: 2px; }

  /* ── TABLE ── */
  .exec-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .exec-tbl thead th {
    background: #f8fafc; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.7px;
    color: #64748b; font-weight: 700; padding: 10px 12px; border-bottom: 2px solid #f1f5f9; white-space: nowrap;
  }
  .exec-tbl tbody tr.exec-row { border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.15s; }
  .exec-tbl tbody tr.exec-row:hover { background: #f8f9ff; }
  .exec-tbl tbody tr.exec-row.exec-expanded { background: #f0f4ff; }
  .exec-tbl tbody td { padding: 10px 12px; vertical-align: middle; }

  /* ── BADGES ── */
  .exec-badge {
    font-size: 9.5px; padding: 3px 9px; border-radius: 12px;
    font-weight: 700; display: inline-flex; align-items: center; gap: 3px;
  }
  .b-received { background: #fef3c7; color: #92400e;  border: 1px solid #fcd34d; }
  .b-executed { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-failed   { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }
  .b-buy      { background: #f0fdf4; color: #15803d;  border: 1px solid #a7f3d0; }
  .b-sell     { background: #fef2f2; color: #dc2626;  border: 1px solid #fecaca; }

  /* ── DETAIL ROW ── */
  .exec-detail-row td { padding: 0; background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
  .exec-detail-inner {
    padding: 20px; display: grid;
    grid-template-columns: repeat(4, 1fr); gap: 14px;
  }
  .exec-detail-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
  .exec-detail-hdr  { padding: 10px 14px; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
  .exec-detail-body { padding: 14px; }
  .exec-dm { margin-bottom: 12px; }
  .exec-dm:last-child { margin-bottom: 0; }
  .exec-dm-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
  .exec-dm-val { font-size: 15px; font-weight: 700; color: #0f172a; }

  /* ── EMPTY STATE ── */
  .exec-empty { text-align: center; padding: 48px; color: #94a3b8; }
  .exec-empty .ico { font-size: 40px; opacity: 0.3; margin-bottom: 12px; }

  /* ── FILTERS SECTION ── */
  .exec-filters-wrap { background: #fcfcfc; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  .exec-filter-item { display: flex; gap: 8px; align-items: center; }
  .exec-filter-badge { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
  .exec-filter-badge .close { cursor: pointer; opacity: 0.6; font-weight: bold; }
  .exec-filter-badge .close:hover { opacity: 1; }
</style>

<div class="exec">

  {{-- ── HEADER ── --}}
  <div class="exec-hdr">
    <div>
      <h1><i class="fa fa-bolt mr-2 text-warning"></i> Signal Executions</h1>
      <div class="exec-breadcrumb">
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span style="color:#1e293b;">Signal Executions</span>
      </div>
    </div>
  </div>

  {{-- ── ALERTS ── --}}
  @if(session('success'))
    <div class="exec-alert-success">
      <i class="fa fa-check-circle" style="font-size:15px;"></i>
      <div><strong>Success!</strong> {{ session('success') }}</div>
    </div>
  @endif

  {{-- ── MAIN CARD ── --}}
  <div class="exec-card">

    {{-- Filter Bar --}}
    <div class="exec-filter">
      <div class="exec-search-wrap">
        <i class="fa fa-search exec-search-icon"></i>
        <input type="text" id="execSearch" class="exec-search-input"
               placeholder="Search by account ID, signal, symbol...">
      </div>
      <div class="exec-stats">
        <div class="exec-stat">
          <div class="exec-stat-lbl">Total Executions</div>
          <div class="exec-stat-val" style="color:#0f172a;">{{ $executions->total() }}</div>
        </div>
        <div class="exec-stat">
          <div class="exec-stat-lbl">Executed</div>
          <div class="exec-stat-val" style="color:#10b981;">{{ $executions->where('status', 'executed')->count() }}</div>
        </div>
        <div class="exec-stat">
          <div class="exec-stat-lbl">Failed</div>
          <div class="exec-stat-val" style="color:#ef4444;">{{ $executions->where('status', 'failed')->count() }}</div>
        </div>
      </div>
    </div>

    {{-- Active Filters --}}
    @if($status || $account)
      <div class="exec-filters-wrap">
        @if($status)
          <div class="exec-filter-item">
            <span style="color:#64748b;font-size:11px;font-weight:600;">Filters:</span>
            <span class="exec-filter-badge">
              Status: <strong>{{ ucfirst($status) }}</strong>
              <a href="{{ route('user.executions.index', array_merge(request()->query(), ['status' => null])) }}" class="close">×</a>
            </span>
          </div>
        @endif
        @if($account)
          <div class="exec-filter-item">
            <span class="exec-filter-badge">
              Account: <strong>{{ $account }}</strong>
              <a href="{{ route('user.executions.index', array_merge(request()->query(), ['account' => null])) }}" class="close">×</a>
            </span>
          </div>
        @endif
      </div>
    @endif

    {{-- Table --}}
    <div style="overflow-x:auto;">
      <table class="exec-tbl" id="execTable">
        <thead>
          <tr>
            <th style="width:44px;">#</th>
            <th>Timestamp</th>
            <th>Account ID</th>
            <th style="text-align:center;">Status</th>
            <th>Signal ID</th>
            <th>Symbol</th>
            <th style="text-align:center;">Type</th>
          </tr>
        </thead>
        <tbody>
          @forelse($executions as $key => $ex)
            <tr class="exec-row" data-id="{{ $ex->id }}">
              <td>
                <div style="width:30px;height:30px;border-radius:50%;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#64748b;">
                  {{ $executions->firstItem() + $key }}
                </div>
              </td>

              <td style="font-weight:500;color:#0f172a;">
                {{ $ex->created_at?->format('Y-m-d H:i:s') }}
                <div style="font-size:10px;color:#94a3b8;margin-top:2px;">{{ $ex->created_at?->diffForHumans() }}</div>
              </td>

              <td>
                <div style="font-weight:700;font-size:13px;color:#0f172a;font-family:monospace;">{{ $ex->account_id }}</div>
              </td>

              <td style="text-align:center;">
                @php
                  $statusBadge = match($ex->status){
                    'received' => 'b-received',
                    'executed' => 'b-executed',
                    'failed' => 'b-failed',
                    default => 'b-received'
                  };
                @endphp
                <span class="exec-badge {{ $statusBadge }}">
                  <i class="fa {{ $ex->status === 'executed' ? 'fa-check-circle' : ($ex->status === 'failed' ? 'fa-times-circle' : 'fa-clock-o') }}" style="font-size:8px;"></i>
                  {{ ucfirst($ex->status) }}
                </span>
              </td>

              <td>
                <div style="font-weight:600;color:#6366f1;font-size:11px;">#{{ $ex->whatsapp_signal_id ?? 'N/A' }}</div>
              </td>

              <td style="font-weight:700;color:#0f172a;">{{ $ex->signal?->symbol ?? '—' }}</td>

              <td style="text-align:center;">
                @if($ex->signal)
                  <span class="exec-badge {{ $ex->signal->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                    <i class="fa {{ $ex->signal->type === 'BUY' ? 'fa-arrow-up' : 'fa-arrow-down' }}" style="font-size:8px;"></i>
                    {{ strtoupper($ex->signal->type) }}
                  </span>
                @else
                  <span style="color:#94a3b8;">—</span>
                @endif
              </td>
            </tr>

            {{-- Expandable Detail Row --}}
            <tr class="exec-detail-row" id="exec-detail-{{ $ex->id }}" style="display:none;">
              <td colspan="7">
                <div class="exec-detail-inner">

                  {{-- Execution Info --}}
                  <div class="exec-detail-card">
                    <div class="exec-detail-hdr" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                      <i class="fa fa-info-circle mr-2"></i> Execution Details
                    </div>
                    <div class="exec-detail-body">
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Execution ID</div>
                        <div class="exec-dm-val" style="font-family:monospace;font-size:12px;">{{ $ex->id }}</div>
                      </div>
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Account</div>
                        <div class="exec-dm-val">{{ $ex->account_id }}</div>
                      </div>
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Status</div>
                        <div class="exec-dm-val">{{ ucfirst($ex->status) }}</div>
                      </div>
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Created</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $ex->created_at?->format('Y-m-d H:i:s') }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Signal Info --}}
                  @if($ex->signal)
                    <div class="exec-detail-card">
                      <div class="exec-detail-hdr" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                        <i class="fa fa-signal mr-2"></i> Signal Details
                      </div>
                      <div class="exec-detail-body">
                        <div class="exec-dm">
                          <div class="exec-dm-lbl">Signal ID</div>
                          <div class="exec-dm-val">#{{ $ex->signal->id }}</div>
                        </div>
                        <div class="exec-dm">
                          <div class="exec-dm-lbl">Symbol</div>
                          <div class="exec-dm-val" style="font-family:monospace;">{{ $ex->signal->symbol }}</div>
                        </div>
                        <div class="exec-dm">
                          <div class="exec-dm-lbl">Type</div>
                          <div style="margin-top:4px;">
                            <span class="exec-badge {{ $ex->signal->type === 'BUY' ? 'b-buy' : 'b-sell' }}">
                              {{ strtoupper($ex->signal->type) }}
                            </span>
                          </div>
                        </div>
                        <div class="exec-dm">
                          <div class="exec-dm-lbl">Price</div>
                          <div class="exec-dm-val">${{ number_format($ex->signal->entry_price ?? 0, 4) }}</div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="exec-detail-card">
                      <div class="exec-detail-hdr" style="background:linear-gradient(135deg,#9ca3af,#6b7280);">
                        <i class="fa fa-ban mr-2"></i> Signal Info
                      </div>
                      <div class="exec-detail-body">
                        <p style="color:#94a3b8;font-size:12px;margin:0;">Signal data unavailable</p>
                      </div>
                    </div>
                  @endif

                  {{-- Status Timeline --}}
                  <div class="exec-detail-card">
                    <div class="exec-detail-hdr" style="background:linear-gradient(135deg,#10b981,#059669);">
                      <i class="fa fa-history mr-2"></i> Timeline
                    </div>
                    <div class="exec-detail-body">
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Current Status</div>
                        @php
                          $statusIcon = match($ex->status){
                            'executed' => ['icon' => 'fa-check-circle', 'color' => '#10b981'],
                            'failed' => ['icon' => 'fa-times-circle', 'color' => '#ef4444'],
                            default => ['icon' => 'fa-clock-o', 'color' => '#f59e0b']
                          };
                        @endphp
                        <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                          <i class="fa {{ $statusIcon['icon'] }}" style="color:{{ $statusIcon['color'] }};font-size:16px;"></i>
                          <span style="font-weight:600;font-size:12px;color:{{ $statusIcon['color'] }};">{{ strtoupper($ex->status) }}</span>
                        </div>
                      </div>
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Received At</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $ex->created_at?->format('Y-m-d H:i:s') }}</div>
                      </div>
                      <div class="exec-dm">
                        <div class="exec-dm-lbl">Age</div>
                        <div style="font-size:12px;font-weight:500;color:#0f172a;">{{ $ex->created_at?->diffForHumans() }}</div>
                      </div>
                    </div>
                  </div>

                  {{-- Full Details --}}
                  <div style="grid-column: 1 / -1; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <div style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                      <i class="fa fa-list mr-2"></i> Full Execution Data
                    </div>
                    <div style="padding: 14px;">
                      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">WhatsApp Signal ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px;">{{ $ex->whatsapp_signal_id }}</div>
                        </div>
                        <div>
                          <span style="color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px;">Account ID</span>
                          <div style="color: #0f172a; font-weight: 600; margin-top: 4px; font-family: monospace;">{{ $ex->account_id }}</div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="7">
                <div class="exec-empty">
                  <div class="ico"><i class="fa fa-inbox"></i></div>
                  <p style="font-size:15px;font-weight:600;margin-bottom:6px;color:#475569;">No signal executions yet</p>
                  <p style="font-size:12px;margin:0;">Execution logs will appear here when signals are sent to your accounts</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($executions->hasPages())
      <div style="padding: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; gap: 8px;">
        {{ $executions->links('pagination::bootstrap-4') }}
      </div>
    @endif

  </div>{{-- end main card --}}

</div>{{-- end .exec --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  (function () {
    'use strict';

    /* ── EXPANDABLE ROWS ── */
    document.querySelectorAll('.exec-row').forEach(function (row) {
      row.addEventListener('click', function () {
        var id     = this.dataset.id;
        var detail = document.getElementById('exec-detail-' + id);
        if (!detail) return;
        var isOpen = detail.style.display !== 'none';
        document.querySelectorAll('.exec-detail-row').forEach(function (d) { d.style.display = 'none'; });
        document.querySelectorAll('.exec-row').forEach(function (r) { r.classList.remove('exec-expanded'); });
        if (!isOpen) {
          detail.style.display = 'table-row';
          this.classList.add('exec-expanded');
        }
      });
    });

    /* ── SEARCH ── */
    var searchInput = document.getElementById('execSearch');
    if (searchInput) {
      searchInput.addEventListener('keyup', function () {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#execTable tbody tr.exec-row').forEach(function (tr) {
          tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
      });
    }

  })();
});
</script>
@endpush
