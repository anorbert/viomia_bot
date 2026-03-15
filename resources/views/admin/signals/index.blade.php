@extends('layouts.admin')

@section('title', 'Trading Signals — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 600 !important; font-size: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-generated { background-color: rgba(26,187,156,0.13) !important; color: #1ABB9C !important; }
.vi-badge-active { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-inactive { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; }
.vi-badge-pending { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; color: #1ABB9C; }
.vi-form-input { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 8px 10px; border-radius: 6px; font-size: 12px; transition: all 0.2s; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.empty-state { padding: 60px 20px; text-align: center; }
.empty-state i { font-size: 48px; color: #4b5563; opacity: 0.5; display: block; margin-bottom: 16px; }
.empty-state p { color: #94a3b8; font-size: 14px; margin-bottom: 20px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📡 Trading Signals</div>
        <div class="vi-header-title">All Signals</div>
        <div class="vi-header-sub">Track and manage trading signals</div>
    </div>
</div>

@if(session('success'))
    <div style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Statistics Cards -->
<div class="vi-stats-grid">
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-signal" style="color:#1ABB9C;"></i> Total Signals</div>
        <div class="vi-stat-value">{{ $signals->total() ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-check-circle" style="color:#22C55E;"></i> Generated Today</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $todaySignals ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-arrow-up" style="color:#22C55E;"></i> Buy Signals</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $buySignals ?? 0 }}</div>
    </div>

    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-arrow-down" style="color:#ef4444;"></i> Sell Signals</div>
        <div class="vi-stat-value" style="color: #ef4444;">{{ $sellSignals ?? 0 }}</div>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Signal List</div>
    </div>

    <div style="overflow-x:auto;">
        <table class="vi-table">
            <thead>
                <tr>
                    <th style="width:12%;">Symbol</th>
                    <th style="width:10%;">Type</th>
                    <th style="width:12%;">Strength</th>
                    <th style="width:15%;">Price</th>
                    <th style="width:15%;">Timestamp</th>
                    <th style="width:15%;">Status</th>
                    <th style="text-align:right; width:15%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($signals as $signal)
                <tr>
                    <td>
                        <div class="td-sym">{{ $signal->symbol ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($signal->type === 'buy')
                            <span class="vi-badge" style="background-color: rgba(34,197,94,0.13); color: #22C55E;">
                                <i class="fa fa-arrow-up"></i> BUY
                            </span>
                        @else
                            <span class="vi-badge" style="background-color: rgba(239,68,68,0.13); color: #ef4444;">
                                <i class="fa fa-arrow-down"></i> SELL
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="td-sym">{{ $signal->strength ?? 'N/A' }}</div>
                    </td>
                    <td style="color: #22C55E; font-weight: 600;">
                        {{ $signal->price ? '$' . number_format($signal->price, 4) : 'N/A' }}
                    </td>
                    <td>
                        {{ $signal->created_at ? $signal->created_at->format('M d, Y H:i') : 'N/A' }}
                    </td>
                    <td>
                        @if($signal->is_active)
                            <span class="vi-badge vi-badge-active">Active</span>
                        @else
                            <span class="vi-badge vi-badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @if(Route::has('admin.signals.show'))
                            <a href="{{ route('admin.signals.show', $signal) }}" class="vi-btn vi-btn-secondary" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <div class="empty-state">
                            <i class="fa fa-inbox"></i>
                            <p>No signals found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($signals->hasPages())
<div style="display: flex; justify-content: center; margin-top: 20px;">
    {{ $signals->links() }}
</div>
@endif

@endsection
