@extends('layouts.admin')

@section('title', 'Trade Executions - AI Analytics')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #1ABB9C !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
.vi-header-title { font-size: 18px !important; font-weight: 800 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-stat-card { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; display: flex; align-items: center; gap: 15px; }
.vi-stat-icon { font-size: 24px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
.vi-stat-content h4 { margin: 0; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.vi-stat-content .value { margin: 6px 0 0 0; font-size: 20px; color: #f1f5f9; font-weight: 800; }
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
.vi-badge-win { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-loss { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-pending { background-color: rgba(245,158,11,0.13) !important; color: #F59E0B !important; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">⚡ Trade Execution</div>
        <div class="vi-header-title">Trade Executions</div>
        <div class="vi-header-sub">Recorded trade executions with performance metrics</div>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(59,158,255,0.13); color:#3B9EFF;">
                <i class="fa fa-exchange" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Total Executions</h4>
                <div class="value">{{ number_format($totalExecutions) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(34,197,94,0.13); color:#22C55E;">
                <i class="fa fa-check" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Wins</h4>
                <div class="value">{{ number_format($winCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(239,68,68,0.13); color:#EF4444;">
                <i class="fa fa-times" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Losses</h4>
                <div class="value">{{ number_format($lossCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="vi-stat-card">
            <div class="vi-stat-icon" style="background-color:rgba(245,158,11,0.13); color:#F59E0B;">
                <i class="fa fa-dollar" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Total Profit</h4>
                <div class="value" style="color: {{ ($totalProfit ?? 0) >= 0 ? '#22C55E' : '#EF4444' }};">{{ number_format($totalProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-list" style="color:#3B9EFF; font-size:14px;"></i>
                <div class="vi-panel-title">Trade Execution Details</div>
            </div>
            <div class="vi-panel-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.ai.executions.index') }}" class="form-inline mb-4" style="gap:10px;">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search symbol or ticket..." value="{{ request('search') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <div class="form-group">
                        <select name="symbol" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Symbols</option>
                            @foreach($symbols as $symbol)
                                <option value="{{ $symbol }}" {{ request('symbol') == $symbol ? 'selected' : '' }}>
                                    {{ $symbol }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="result" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Results</option>
                            <option value="WIN" {{ request('result') == 'WIN' ? 'selected' : '' }}>Win</option>
                            <option value="LOSS" {{ request('result') == 'LOSS' ? 'selected' : '' }}>Loss</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <div class="form-group">
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>

                    <button type="submit" class="btn" style="background-color:#1ABB9C; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-weight:700;">Filter</button>
                    <a href="{{ route('admin.ai.executions.index') }}" class="btn" style="background-color:#222d42; color:#94a3b8; border:1px solid rgba(255,255,255,0.07); padding:6px 16px; border-radius:6px; font-weight:700;">Reset</a>
                </form>

                <!-- Data Table -->
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Symbol</th>
                                <th>Account ID</th>
                                <th>Decision</th>
                                <th>Entry Price</th>
                                <th>Profit/Loss</th>
                                <th>Result</th>
                                <th>Confidence</th>
                                <th>Session</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($executions as $execution)
                                <tr>
                                    <td style="color:#3B9EFF; font-weight:700;">{{ $execution->ticket }}</td>
                                    <td class="td-sym">{{ $execution->symbol }}</td>
                                    <td style="font-size:11px;">{{ $execution->account_id }}</td>
                                    <td>
                                        @if($execution->decision === 'BUY')
                                            <span class="vi-badge-buy">{{ $execution->decision }}</span>
                                        @else
                                            <span class="vi-badge-sell">{{ $execution->decision }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size:11px; color:#3B9EFF;">{{ number_format($execution->entry_price, 5) }}</td>
                                    <td style="font-weight:700; color: {{ ($execution->profit_loss ?? 0) >= 0 ? '#22C55E' : '#EF4444' }};">
                                        {{ number_format($execution->profit_loss ?? 0, 2) }}
                                    </td>
                                    <td>
                                        @if($execution->result)
                                            @if($execution->result === 'WIN')
                                                <span class="vi-badge-win">{{ $execution->result }}</span>
                                            @else
                                                <span class="vi-badge-loss">{{ $execution->result }}</span>
                                            @endif
                                        @else
                                            <span class="vi-badge-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:4px;">
                                            <div style="width:40px; background-color:#222d42; border-radius:3px; height:4px;">
                                                <div style="width:{{ min(($execution->ml_confidence ?? 0) * 100, 100) }}%; background-color:#3B9EFF; height:100%; border-radius:3px;"></div>
                                            </div>
                                            <span style="font-size:10px;">{{ number_format($execution->ml_confidence ?? 0, 0) }}%</span>
                                        </div>
                                    </td>
                                    <td style="font-size:11px;">{{ $execution->session_name }}</td>
                                    <td style="font-size:11px;">{{ $execution->created_at->format('M d, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align:center; padding:20px;">No executions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center" style="margin-top:20px;">
                    {{ $executions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
