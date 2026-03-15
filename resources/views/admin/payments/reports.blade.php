@extends('layouts.admin')

@section('title', 'Payment Reports — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-form-input { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 8px 10px; border-radius: 6px; font-size: 12px; transition: all 0.2s; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; transition: all 0.2s; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 600; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-completed { background-color: rgba(34,197,94,0.13); color: #22C55E; }
.vi-badge-pending { background-color: rgba(251,146,60,0.13); color: #FB923C; }
.vi-badge-failed { background-color: rgba(239,68,68,0.13); color: #EF4444; }
.vi-btn { padding: 8px 14px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; align-items: end; padding: 12px 18px; background-color: #1a2235; border-bottom: 1px solid rgba(255,255,255,0.07); flex-wrap: wrap; }
.vi-filter-group { display: flex; flex-direction: column; }
.vi-filter-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 6px; }
.provider-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; padding: 16px; }
.provider-card { background: rgba(26,34,53,0.5); border: 1px solid rgba(26,187,156,0.2); border-radius: 8px; padding: 12px; }
.provider-card-title { font-size: 11px; color: #94a3b8; margin-bottom: 6px; }
.provider-card-amount { font-size: 18px; font-weight: 900; color: #1ABB9C; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📊 Analytics</div>
        <div class="vi-header-title">Payment Reports</div>
        <div class="vi-header-sub">Comprehensive payment transaction analytics and insights</div>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-list"></i> View All
    </a>
</div>

<!-- Statistics Cards -->
<div class="vi-stats-grid">
    <!-- Total Transactions -->
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-exchange" style="color:#1ABB9C;"></i> Total Transactions</div>
        <div class="vi-stat-value" style="color: #1ABB9C;">{{ $stats['total_transactions'] }}</div>
    </div>

    <!-- Total Amount -->
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-dollar" style="color:#22C55E;"></i> Total Amount</div>
        <div class="vi-stat-value" style="color: #22C55E;">${{ number_format($stats['total_amount'], 2) }}</div>
    </div>

    <!-- Completed -->
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-check-circle" style="color:#22C55E;"></i> Completed</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $stats['completed'] }}</div>
    </div>

    <!-- Pending -->
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-clock-o" style="color:#FB923C;"></i> Pending</div>
        <div class="vi-stat-value" style="color: #FB923C;">{{ $stats['pending'] }}</div>
    </div>

    <!-- Failed -->
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-times-circle" style="color:#EF4444;"></i> Failed</div>
        <div class="vi-stat-value" style="color: #EF4444;">{{ $stats['failed'] }}</div>
    </div>
</div>

<!-- Amount by Provider -->
@if($stats['amount_by_provider']->count() > 0)
<div class="vi-panel" style="margin-bottom: 20px;">
    <div class="vi-panel-head">
        <i class="fa fa-server" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Revenue by Provider</div>
    </div>
    <div class="provider-grid">
        @foreach($stats['amount_by_provider'] as $provider)
        <div class="provider-card">
            <div class="provider-card-title">{{ ucfirst($provider->provider) }}</div>
            <div class="provider-card-amount">${{ number_format($provider->total, 2) }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Filters -->
<div class="vi-panel">
    <form method="GET" action="{{ route('admin.payments.reports') }}" class="vi-filters">
        <div class="vi-filter-group">
            <label class="vi-filter-label">Search</label>
            <input type="text" class="vi-form-input" name="search" value="{{ request('search') }}" placeholder="Reference or Email...">
        </div>

        <div class="vi-filter-group">
            <label class="vi-filter-label">Status</label>
            <select class="vi-form-input" name="status">
                <option value="">All Statuses</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>✓ Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>✗ Failed</option>
            </select>
        </div>

        <div class="vi-filter-group">
            <label class="vi-filter-label">Provider</label>
            <select class="vi-form-input" name="provider">
                <option value="">All Providers</option>
                <option value="momo" {{ request('provider') === 'momo' ? 'selected' : '' }}>MTN MoMo</option>
                <option value="binance" {{ request('provider') === 'binance' ? 'selected' : '' }}>Binance</option>
                <option value="paypal" {{ request('provider') === 'paypal' ? 'selected' : '' }}>PayPal</option>
            </select>
        </div>

        <div class="vi-filter-group">
            <label class="vi-filter-label">Start Date</label>
            <input type="date" class="vi-form-input" name="start_date" value="{{ request('start_date') }}">
        </div>

        <div class="vi-filter-group">
            <label class="vi-filter-label">End Date</label>
            <input type="date" class="vi-form-input" name="end_date" value="{{ request('end_date') }}">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="vi-btn vi-btn-primary" style="flex: 1;">
                <i class="fa fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.payments.reports') }}" class="vi-btn vi-btn-secondary" style="flex: 1; justify-content: center;">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Transaction Details</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="vi-table">
            <thead>
                <tr>
                    <th style="width:13%;">Reference</th>
                    <th style="width:15%;">User</th>
                    <th style="width:14%;">Plan</th>
                    <th style="width:10%;">Amount</th>
                    <th style="width:10%;">Provider</th>
                    <th style="width:12%;">Status</th>
                    <th style="width:15%;">Date</th>
                    <th style="text-align:right; width:11%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>
                        <code style="background: rgba(26,187,156,0.1); color:#1ABB9C; padding:3px 8px; border-radius:4px;font-size:10px;">
                            {{ substr($transaction->reference, 0, 10) }}...
                        </code>
                    </td>
                    <td>
                        <div class="td-sym">{{ $transaction->user->name ?? 'N/A' }}</div>
                        <div style="font-size:10px; color:#4b5563;">{{ Str::limit($transaction->user->email ?? 'N/A', 25) }}</div>
                    </td>
                    <td>{{ $transaction->plan->name ?? 'N/A' }}</td>
                    <td>
                        <span style="color:#22C55E; font-weight:700;">${{ number_format($transaction->amount, 2) }}</span>
                    </td>
                    <td>
                        <span style="background:rgba(26,187,156,0.2); color:#1ABB9C; padding:3px 8px; border-radius:4px; font-size:10px; font-weight:600;">
                            {{ ucfirst($transaction->provider) }}
                        </span>
                    </td>
                    <td>
                        @if($transaction->status === 'success')
                            <span class="vi-badge vi-badge-completed">
                                <i class="fa fa-check-circle"></i> COMPLETED
                            </span>
                        @elseif($transaction->status === 'pending')
                            <span class="vi-badge vi-badge-pending">
                                <i class="fa fa-hourglass-half"></i> PENDING
                            </span>
                        @else
                            <span class="vi-badge vi-badge-failed">
                                <i class="fa fa-times-circle"></i> FAILED
                            </span>
                        @endif
                    </td>
                    <td style="font-size:11px;">
                        {{ $transaction->created_at->format('M d, Y') }}<br>
                        <span style="color:#4b5563;">{{ $transaction->created_at->format('H:i') }}</span>
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.payments.show', $transaction->id) }}" class="vi-btn vi-btn-secondary" title="View">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 50px; text-align: center; color: #94a3b8;">
                        <i class="fa fa-inbox" style="font-size: 40px; opacity: 0.5; display: block; margin-bottom: 12px;"></i>
                        <p style="margin: 0; font-size: 13px;">No payment transactions found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($transactions->hasPages())
<div style="display: flex; justify-content: center; margin-top: 20px;">
    {{ $transactions->links() }}
</div>
@endif

@endsection
