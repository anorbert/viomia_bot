@extends('layouts.admin')

@section('title', 'All Payments — ' . config('app.name'))

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
.vi-badge-completed { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-pending { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-failed { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-filter { display: flex; gap: 8px; align-items: center; padding: 12px 18px; background-color: #1a2235; border-bottom: 1px solid rgba(255,255,255,0.07); flex-wrap: wrap; }
.vi-filter select, .vi-filter input { background-color: #111827 !important; color: #94a3b8; border: 1px solid rgba(255,255,255,0.07); padding: 6px 10px; border-radius: 6px; font-size: 11px; }
.vi-filter select:focus, .vi-filter input:focus { border-color: #1ABB9C !important; outline: none; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💳 Payment System</div>
        <div class="vi-header-title">All Transactions</div>
        <div class="vi-header-sub">Track and manage all payment transactions</div>
    </div>
    <a href="{{ route('admin.payments.reports') }}" class="vi-btn vi-btn-primary" style="margin-left:auto;">
        <i class="fa fa-chart-bar"></i> View Reports
    </a>
</div>

@if(session('success'))
    <div style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">Transaction List</div>
    </div>

    <div style="overflow-x:auto;">
        <table class="vi-table">
            <thead>
                <tr>
                    <th style="width:12%;">Reference ID</th>
                    <th style="width:15%;">User</th>
                    <th style="width:15%;">Plan</th>
                    <th style="width:10%;">Amount</th>
                    <th style="width:10%;">Provider</th>
                    <th style="width:12%;">Status</th>
                    <th style="width:15%;">Date</th>
                    <th style="text-align:right; width:12%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <code style="background-color:rgba(26,187,156,0.1); color:#1ABB9C; padding:3px 8px; border-radius:4px; font-size:10px;">
                            {{ substr($payment->reference, 0, 12) }}...
                        </code>
                    </td>
                    <td>
                        <div class="td-sym">{{ $payment->user->name ?? 'N/A' }}</div>
                        <div style="font-size:10px; color:#4b5563;">{{ $payment->user->email ?? 'N/A' }}</div>
                    </td>
                    <td class="td-sym">{{ $payment->plan->name ?? 'N/A' }}</td>
                    <td>
                        <span style="color:#22C55E; font-weight:700;">${{ number_format($payment->amount, 2) }}</span>
                    </td>
                    <td>
                        <span style="background:rgba(26,187,156,0.2); color:#1ABB9C; padding:3px 8px; border-radius:4px; font-size:10px; font-weight:600;">
                            {{ ucfirst($payment->provider) }}
                        </span>
                    </td>
                    <td>
                        @if($payment->status === 'success')
                            <span class="vi-badge vi-badge-completed">
                                <i class="fa fa-check-circle"></i> COMPLETED
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="vi-badge vi-badge-pending">
                                <i class="fa fa-hourglass-half"></i> PENDING
                            </span>
                        @else
                            <span class="vi-badge vi-badge-failed">
                                <i class="fa fa-times-circle"></i> FAILED
                            </span>
                        @endif
                    </td>
                    <td>
                        {{ $payment->created_at?->format('M d, Y') ?? '—' }}<br>
                        <span style="font-size:10px; color:#4b5563;">{{ $payment->created_at?->format('H:i:s') ?? '—' }}</span>
                    </td>
                    <td style="text-align:right;">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="vi-btn vi-btn-secondary" title="View Details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i class="fa fa-inbox" style="font-size: 32px; opacity: 0.5; display: block; margin-bottom: 10px;"></i>
                        <p style="margin: 0;">No payment transactions found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($payments->hasPages())
<div style="display: flex; justify-content: center; margin-top: 20px;">
    {{ $payments->links() }}
</div>
@endif

@endsection
