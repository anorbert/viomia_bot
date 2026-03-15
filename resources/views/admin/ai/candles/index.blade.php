@extends('layouts.admin')

@section('title', 'Market Data - AI Analytics')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; justify-content: space-between !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #1ABB9C !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
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
.vi-badge-trend { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
.vi-badge-up { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-down { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-rsi-hot { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-rsi-cold { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📊 Market Data</div>
        <div class="vi-header-title">Candle Logs (Market Data)</div>
        <div class="vi-header-sub">Real-time OHLC data with technical indicators</div>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-line-chart" style="color:#1ABB9C; font-size:14px;"></i>
                <div class="vi-panel-title">Market Data Analysis</div>
            </div>
            <div class="vi-panel-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.ai.candles.index') }}" class="form-inline mb-4" style="gap:10px;">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search symbol..." value="{{ request('search') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>
                    <div class="form-group">
                        <select name="session" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Sessions</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session }}" {{ request('session') == $session ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="trend" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Trends</option>
                            <option value="1" {{ request('trend') == '1' ? 'selected' : '' }}>Uptrend</option>
                            <option value="0" {{ request('trend') == '0' ? 'selected' : '' }}>Downtrend</option>
                        </select>
                    </div>
                    <button type="submit" class="btn" style="background-color:#1ABB9C; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-weight:700;">Filter</button>
                    <a href="{{ route('admin.ai.candles.index') }}" class="btn" style="background-color:#222d42; color:#94a3b8; border:1px solid rgba(255,255,255,0.07); padding:6px 16px; border-radius:6px; font-weight:700;">Reset</a>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Price</th>
                                <th>RSI</th>
                                <th>ATR</th>
                                <th>Trend</th>
                                <th>Support</th>
                                <th>Resistance</th>
                                <th>Session</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candles as $candle)
                                <tr>
                                    <td class="td-sym">{{ $candle->symbol }}</td>
                                    <td>{{ number_format($candle->price, 5) }}</td>
                                    <td>
                                        <span class="vi-badge-trend {{ $candle->rsi > 70 ? 'vi-badge-rsi-hot' : ($candle->rsi < 30 ? 'vi-badge-rsi-cold' : 'vi-badge-up') }}">
                                            {{ number_format($candle->rsi, 2) }}
                                        </span>
                                    </td>
                                    <td style="color:#f1f5f9;">{{ number_format($candle->atr, 5) }}</td>
                                    <td>
                                        <span class="vi-badge-trend {{ $candle->trend == 1 ? 'vi-badge-up' : 'vi-badge-down' }}">
                                            {{ $candle->trend == 1 ? 'Uptrend' : 'Downtrend' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($candle->support, 5) }}</td>
                                    <td>{{ number_format($candle->resistance, 5) }}</td>
                                    <td style="color:#f1f5f9;">{{ $candle->session }}</td>
                                    <td><small>{{ $candle->created_at->format('Y-m-d H:i:s') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center" style="padding:30px; color:#4b5563;">No market data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $candles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
