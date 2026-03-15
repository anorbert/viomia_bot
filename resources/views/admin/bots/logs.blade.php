@extends('layouts.admin')

@section('title', 'Bot Logs — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #A78BFA !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
.vi-header-title { font-size: 18px !important; font-weight: 800 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 13px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-panel-body { padding: 18px !important; }
.vi-filter { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
.vi-filter-group { display: flex; align-items: center; gap: 8px; background-color: rgba(139,92,246,0.08); padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(139,92,246,0.15); }
.vi-filter-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
.vi-filter-select { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.12) !important; color: #f1f5f9 !important; padding: 6px 10px !important; border-radius: 6px !important; font-size: 11px !important; }
.vi-filter-btn { background-color: #A78BFA !important; color: #fff !important; border: none; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 11px; cursor: pointer; }
.vi-filter-btn:hover { background-color: #9f7aea !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; font-size: 11px; }
.vi-badge { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-error { background-color: rgba(239,68,68,0.13) !important; color: #fca5a5 !important; }
.vi-badge-status { background-color: rgba(59,158,255,0.13) !important; color: #3B9EFF !important; }
.vi-badge-trade { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-timestamp { font-family: monospace; font-size: 10px; color: #4b5563; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#A78BFA; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📊 Activity Monitoring</div>
        <div class="vi-header-title">Bot Logs & Activity</div>
        <div class="vi-header-sub">Monitor bot performance metrics, error logs, and trading activity</div>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-filter" style="color:#A78BFA; font-size:14px;"></i>
        <div class="vi-panel-title">Filter Logs</div>
    </div>
    <div class="vi-panel-body">
        <form method="GET" class="vi-filter">
            <div class="vi-filter-group">
                <label class="vi-filter-label">Log Type</label>
                <select name="type" class="vi-filter-select">
                    <option value="error" {{ $type === 'error' ? 'selected' : '' }}>Error Logs</option>
                    <option value="status" {{ $type === 'status' ? 'selected' : '' }}>Status Changes</option>
                    <option value="trade" {{ $type === 'trade' ? 'selected' : '' }}>Trade Activity</option>
                </select>
            </div>

            <div class="vi-filter-group">
                <label class="vi-filter-label">Bot Instance</label>
                <select name="bot_id" class="vi-filter-select">
                    <option value="">-- All Bots --</option>
                    @foreach($bots as $bot)
                        <option value="{{ $bot->id }}" {{ $botId == $bot->id ? 'selected' : '' }}>
                            {{ $bot->account->name ?? 'Unknown' }} ({{ $bot->account->login ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="vi-filter-btn">
                <i class="fa fa-search"></i> Apply Filter
            </button>
        </form>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#A78BFA; font-size:14px;"></i>
        <div class="vi-panel-title">
            @if($type === 'error')
                Error Logs
            @elseif($type === 'status')
                Status Change History
            @else
                Trade Activity
            @endif
        </div>
    </div>
    <div class="vi-panel-body">
        <!-- Data Table -->
        <div class="vi-table-container" style="overflow-x:auto;">
            <table class="vi-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>
                            @if($type === 'error')
                                Message
                            @elseif($type === 'status')
                                Status Change
                            @else
                                Trade Details
                            @endif
                        </th>
                        <th>Bot Instance</th>
                        <th style="width:200px;">Timestamp</th>
                        <th style="text-align:right; width:80px;">Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $key => $log)
                        <tr>
                            <td style="color:#4b5563;">{{ $key + 1 }}</td>
                            <td>
                                @if($type === 'error')
                                    <div style="color:#f1f5f9; font-weight:600;">{{ class_basename($log->error_class ?? 'Unknown') }}</div>
                                    <div style="color:#4b5563; font-size:10px; margin-top:4px;">{{ Str::limit($log->error_message ?? 'N/A', 80) }}</div>
                                @elseif($type === 'status')
                                    <div style="color:#f1f5f9; font-weight:600;">{{ $log->old_status ?? 'N/A' }} <i class="fa fa-arrow-right" style="margin:0 6px; opacity:.5;"></i> {{ $log->new_status ?? 'N/A' }}</div>
                                    <div style="color:#4b5563; font-size:10px; margin-top:4px;">{{ Str::limit($log->reason ?? 'No reason provided', 60) }}</div>
                                @else
                                    <div style="color:#f1f5f9; font-weight:600;">{{ ucfirst($log->trade_type ?? 'N/A') }} @ {{ $log->entry_price ?? 'N/A' }}</div>
                                    <div style="color:#4b5563; font-size:10px; margin-top:4px;">{{ $log->symbol ?? 'N/A' }} • Lot: {{ $log->lot_size ?? 'N/A' }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $botInstance = null;
                                    if(isset($log->botStatus) && $log->botStatus) {
                                        $botInstance = $log->botStatus;
                                    } else {
                                        $botInstance = $bots->where('id', $log->bot_status_id)->first();
                                    }
                                @endphp
                                <div style="color:#f1f5f9; font-weight:600;">{{ $botInstance?->account?->name ?? 'Unknown' }}</div>
                                <div style="color:#4b5563; font-size:10px; margin-top:2px;">{{ $botInstance?->account?->login ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="vi-timestamp">{{ $log->created_at?->format('M d, Y H:i:s') ?? 'N/A' }}</div>
                            </td>
                            <td style="text-align:right;">
                                @if($type === 'error')
                                    <span class="vi-badge vi-badge-error">ERROR</span>
                                @elseif($type === 'status')
                                    <span class="vi-badge vi-badge-status">STATUS</span>
                                @else
                                    <span class="vi-badge vi-badge-trade">TRADE</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px 14px;">
                                <div style="color:#7a96ab; font-size:12px;">
                                    <i class="fa fa-inbox" style="font-size:32px; margin-bottom:12px; display:block; opacity:.5;"></i>
                                    No logs found for the selected filters
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs && $logs->hasPages())
            <div style="margin-top:20px; display:flex; justify-content:flex-end;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
