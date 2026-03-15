@extends('layouts.admin')

@section('title', 'Client - ' . $client->name . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; margin-bottom: 16px; }
.vi-panel-head { padding: 16px 20px; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 16px 20px; }
.vi-info-item { }
.vi-info-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 6px; }
.vi-info-value { font-size: 13px; font-weight: 600; color: #f1f5f9; }
.vi-section { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 12px; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.vi-table thead th { padding: 10px; font-size: 9.5px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #4b5563; text-align: left; background-color: #1a2235; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-table tbody tr:hover { background-color: #1a2235; }
.vi-table tbody td { padding: 10px; color: #94a3b8; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-active { background-color: rgba(34,197,94,0.13); color: #22C55E; }
.vi-btn { padding: 8px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👥 Client Details</div>
        <div class="vi-header-title">{{ $client->name }}</div>
    </div>
    <div style="margin-left: auto; display: flex; gap: 8px;">
        <a href="{{ route('admin.clients.edit', $client) }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.clients.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-chevron-left"></i> Back
        </a>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 4px;">Client Information</div>
        <div style="font-size: 11px; color: #4b5563;">Member since {{ $client->created_at->format('M d, Y') }}</div>
    </div>

    <div class="vi-info-grid">
        <div class="vi-info-item">
            <div class="vi-info-label">Client Name</div>
            <div class="vi-info-value">{{ $client->name }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Email Address</div>
            <div class="vi-info-value">{{ $client->email }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Phone Number</div>
            <div class="vi-info-value">{{ $client->phone ?? 'Not provided' }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Status</div>
            <div class="vi-info-value">
                @if($client->status === 'active' || $client->is_active)
                    <span class="vi-badge vi-badge-active">ACTIVE</span>
                @else
                    <span class="vi-badge" style="background-color: rgba(107,114,128,0.13); color: #9CA3AF;">INACTIVE</span>
                @endif
            </div>
        </div>
        @if($client->user)
            <div class="vi-info-item">
                <div class="vi-info-label">Assigned User</div>
                <div class="vi-info-value">{{ $client->user->name }}<br><span style="font-size:10px; color:#4b5563;">{{ $client->user->email }}</span></div>
            </div>
        @endif
        <div class="vi-info-item">
            <div class="vi-info-label">Created At</div>
            <div class="vi-info-value">{{ $client->created_at->format('M d, Y H:i A') }}<br><span style="font-size:10px; color:#4b5563;">{{ $client->created_at->diffForHumans() }}</span></div>
        </div>
    </div>

    @if($client->address || $client->city || $client->country)
        <div class="vi-section">
            <div class="vi-section-title">📍 Address Information</div>
            <div class="vi-info-grid">
                @if($client->address)
                    <div class="vi-info-item">
                        <div class="vi-info-label">Street Address</div>
                        <div class="vi-info-value">{{ $client->address }}</div>
                    </div>
                @endif
                @if($client->city)
                    <div class="vi-info-item">
                        <div class="vi-info-label">City</div>
                        <div class="vi-info-value">{{ $client->city }}</div>
                    </div>
                @endif
                @if($client->country)
                    <div class="vi-info-item">
                        <div class="vi-info-label">Country</div>
                        <div class="vi-info-value">{{ $client->country }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($client->accounts && count($client->accounts) > 0)
        <div class="vi-section">
            <div class="vi-section-title">📊 Trading Accounts ({{ count($client->accounts) }})</div>
            <table class="vi-table">
                <thead>
                    <tr>
                        <th>Account Number</th>
                        <th>Platform</th>
                        <th>Type</th>
                        <th>Broker</th>
                        <th>Status</th>
                        <th style="text-align:right;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->accounts as $account)
                        <tr>
                            <td>
                                <a href="{{ route('admin.accounts.show', $account) }}" style="color: #1ABB9C; text-decoration: none;">
                                    {{ $account->account_number ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ strtoupper($account->platform) }}</td>
                            <td>{{ $account->account_type }}</td>
                            <td>{{ $account->broker_server ?? 'N/A' }}</td>
                            <td>
                                @if($account->status === 'active')
                                    <span class="vi-badge vi-badge-active">ACTIVE</span>
                                @else
                                    <span class="vi-badge">{{ $account->status }}</span>
                                @endif
                            </td>
                            <td style="text-align:right; color:#22C55E; font-weight:600;">${{ number_format($account->balance ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div style="display: flex; gap: 10px; justify-content: flex-end;">
    <a href="{{ route('admin.clients.edit', $client) }}" class="vi-btn vi-btn-primary">
        <i class="fa fa-pencil"></i> Edit Client
    </a>
</div>

@endsection
