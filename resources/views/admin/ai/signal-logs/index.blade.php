@extends('layouts.admin')

@section('title', 'Signals Sent - AI Analytics')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #1ABB9C !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
.vi-header-title { font-size: 18px !important; font-weight: 800 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 13px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-panel-body { padding: 18px !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 800 !important; font-size: 13px; }
.vi-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
.vi-badge-buy { background-color: rgba(26,187,156,0.13) !important; color: #1ABB9C !important; }
.vi-badge-sell { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-success { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-failed { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-pending { background-color: rgba(245,158,11,0.13) !important; color: #F59E0B !important; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📡 Signal Management</div>
        <div class="vi-header-title">Signal Logs</div>
        <div class="vi-header-sub">Trading signals sent to execution systems</div>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-share-alt" style="color:#3B9EFF; font-size:14px;"></i>
                <div class="vi-panel-title">Signal Delivery Details</div>
            </div>
            <div class="vi-panel-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.ai.signal-logs.index') }}" class="form-inline mb-4" style="gap:10px;">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search symbol..." value="{{ request('search') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <div class="form-group">
                        <select name="decision" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Signals</option>
                            @foreach($uniqueDecisions as $decision)
                                <option value="{{ $decision }}" {{ request('decision') == $decision ? 'selected' : '' }}>
                                    {{ $decision }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="push_status" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Status</option>
                            <option value="SUCCESS" {{ request('push_status') == 'SUCCESS' ? 'selected' : '' }}>Success</option>
                            <option value="FAILED" {{ request('push_status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                            <option value="PENDING" {{ request('push_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <div class="form-group">
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <button type="submit" class="btn" style="background-color:#1ABB9C; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-weight:700;">Filter</button>
                    <a href="{{ route('admin.ai.signal-logs.index') }}" class="btn" style="background-color:#222d42; color:#94a3b8; border:1px solid rgba(255,255,255,0.07); padding:6px 16px; border-radius:6px; font-weight:700;">Reset</a>
                </form>

                <!-- Data Table -->
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Signal Type</th>
                                <th>Entry Price</th>
                                <th>Push Status</th>
                                <th>Response</th>
                                <th>Pushed At</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($signals as $signal)
                                <tr>
                                    <td class="td-sym">{{ $signal->symbol }}</td>
                                    <td>
                                        @if($signal->decision === 'BUY')
                                            <span class="vi-badge-buy">{{ $signal->decision }}</span>
                                        @else
                                            <span class="vi-badge-sell">{{ $signal->decision }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size:11px; color:#3B9EFF;">{{ number_format($signal->entry, 5) }}</td>
                                    <td>
                                        @if($signal->push_status === 'SUCCESS')
                                            <span class="vi-badge-success">{{ $signal->push_status }}</span>
                                        @elseif($signal->push_status === 'FAILED')
                                            <span class="vi-badge-failed">{{ $signal->push_status }}</span>
                                        @else
                                            <span class="vi-badge-pending">{{ $signal->push_status }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size:11px; color:#94a3b8;">
                                        @if($signal->laravel_resp)
                                            <span style="opacity:0.7;">{{ substr($signal->laravel_resp, 0, 40) }}...</span>
                                        @else
                                            <span style="opacity:0.5;">—</span>
                                        @endif
                                    </td>
                                    <td style="font-size:11px;">{{ $signal->pushed_at?->format('M d, h:i A') ?? '—' }}</td>
                                    <td style="font-size:11px;">{{ $signal->created_at?->format('M d, h:i A') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center; padding:20px;">No signals found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center" style="margin-top:20px;">
                    {{ $signals->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
