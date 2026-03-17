@extends('layouts.admin')

@section('title', 'AI Decisions - AI Analytics')

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
.vi-badge-info { background-color: rgba(59,158,255,0.13) !important; color: #3B9EFF !important; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🧠 AI Intelligence</div>
        <div class="vi-header-title">AI Trading Decisions</div>
        <div class="vi-header-sub">AI algorithm decisions with confidence scores and risk ratios</div>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-lightbulb-o" style="color:#F59E0B; font-size:14px;"></i>
                <div class="vi-panel-title">AI Decision Details</div>
            </div>
            <div class="vi-panel-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.ai.decisions.index') }}" class="form-inline mb-4" style="gap:10px;">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search symbol..." value="{{ request('search') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>
                    <div class="form-group">
                        <select name="decision" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#94a3b8; padding:6px 12px;">
                            <option value="">All Decisions</option>
                            @foreach($uniqueDecisions as $decision)
                                <option value="{{ $decision }}" {{ request('decision') == $decision ? 'selected' : '' }}>{{ $decision }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>
                    <div class="form-group">
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.07); color:#f1f5f9; padding:6px 12px;">
                    </div>
                    <button type="submit" class="btn" style="background-color:#1ABB9C; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-weight:700;">Filter</button>
                    <a href="{{ route('admin.ai.decisions.index') }}" class="btn" style="background-color:#222d42; color:#94a3b8; border:1px solid rgba(255,255,255,0.07); padding:6px 16px; border-radius:6px; font-weight:700;">Reset</a>
                </form>

                <!-- Data Table -->
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Decision</th>
                                <th>Confidence</th>
                                <th>Score</th>
                                <th>Entry</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>R:R Ratio</th>
                                <th>Decided At</th>
                                <th>Web Sentiment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($decisions as $decision)
                                <tr>
                                    <td class="td-sym">{{ $decision->symbol }}</td>
                                    <td>
                                        @if($decision->decision === 'BUY')
                                            <span class="vi-badge-buy">{{ $decision->decision }}</span>
                                        @else
                                            <span class="vi-badge-sell">{{ $decision->decision }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <div style="width:60px; background-color:#222d42; border-radius:4px; height:6px;">
                                                <div style="width:{{ min($decision->confidence * 100, 100) }}%; background-color: {{ $decision->confidence >= 0.7 ? '#22C55E' : ($decision->confidence >= 0.5 ? '#F59E0B' : '#EF4444') }}; height:100%; border-radius:4px;"></div>
                                            </div>
                                            <span style="font-size:11px;">{{ round($decision->confidence * 100, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td style="color:#94a3b8;">{{ $decision->score }}/100</td>
                                    <td style="font-size:11px; color:#3B9EFF;">{{ number_format($decision->entry, 5) }}</td>
                                    <td style="font-size:11px; color:#EF4444;">{{ number_format($decision->stop_loss, 5) }}</td>
                                    <td style="font-size:11px; color:#22C55E;">{{ number_format($decision->take_profit, 5) }}</td>
                                    <td style="font-weight:700; color:#F59E0B;">{{ number_format($decision->rr_ratio, 2) }}:1</td>
                                    <td style="font-size:11px;">{{ $decision->decided_at?->format('M d, h:i A') ?? '—' }}</td>
                                    <td>
                                        @if($decision->web_sentiment === 'POSITIVE')
                                            <span class="vi-badge-buy">+</span>
                                        @elseif($decision->web_sentiment === 'NEGATIVE')
                                            <span class="vi-badge-sell">−</span>
                                        @else
                                            <span style="background-color:#475569; color:#cbd5e1; padding:4px 8px; border-radius:4px; font-size:11px;">Neutral</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align:center; padding:20px;">No decisions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center" style="margin-top:20px;">
                    {{ $decisions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
