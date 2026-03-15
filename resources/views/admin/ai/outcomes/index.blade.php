@extends('layouts.admin')

@section('title', 'Trade Outcomes - AI Analytics')

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
.vi-badge-win { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-loss { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📊 Trade Results</div>
        <div class="vi-header-title">Trade Outcomes</div>
        <div class="vi-header-sub">Final trade results with profit/loss details</div>
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
                <h4>Total Trades</h4>
                <div class="value">{{ number_format($totalOutcomes) }}</div>
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
            <div class="vi-stat-icon" style="background-color:rgba(34,197,94,0.13); color:#22C55E;">
                <i class="fa fa-percent" style="font-size:20px;"></i>
            </div>
            <div class="vi-stat-content">
                <h4>Win Rate</h4>
                <div class="value">{{ $winRate }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-check-circle" style="color:#22C55E; font-size:14px;"></i>
                <div class="vi-panel-title">Trade Outcome Details</div>
            </div>
            <div class="vi-panel-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.ai.outcomes.index') }}" class="form-inline mb-4" style="gap:10px;">
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
                    <a href="{{ route('admin.ai.outcomes.index') }}" class="btn" style="background-color:#222d42; color:#94a3b8; border:1px solid rgba(255,255,255,0.07); padding:6px 16px; border-radius:6px; font-weight:700;">Reset</a>
                </form>

                <!-- Data Table -->
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Symbol</th>
                                <th>Account ID</th>
                                <th>Profit</th>
                                <th>Result</th>
                                <th>Recorded At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outcomes as $outcome)
                                <tr>
                                    <td style="color:#3B9EFF; font-weight:700;">{{ $outcome->ticket }}</td>
                                    <td class="td-sym">{{ $outcome->symbol }}</td>
                                    <td style="font-size:11px;">{{ $outcome->account_id }}</td>
                                    <td style="font-weight:700; color: {{ $outcome->profit >= 0 ? '#22C55E' : '#EF4444' }};">
                                        {{ number_format($outcome->profit, 2) }}
                                    </td>
                                    <td>
                                        @if($outcome->result === 'WIN')
                                            <span class="vi-badge-win">{{ $outcome->result }}</span>
                                        @else
                                            <span class="vi-badge-loss">{{ $outcome->result }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size:11px;">{{ $outcome->recorded_at->format('M d, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:20px;">No outcomes found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center" style="margin-top:20px;">
                    {{ $outcomes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
