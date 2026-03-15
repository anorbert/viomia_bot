@extends('layouts.admin')

@section('title', 'Trading Accounts — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #111827 0%, #1a2235 100%) !important; border: 1px solid rgba(26,187,156,0.15) !important; border-top: 3px solid #1ABB9C !important; border-radius: 14px !important; padding: 24px !important; margin-bottom: 24px !important; box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 20px rgba(26,187,156,0.1) !important; position: relative; overflow: hidden; }
.vi-header::before { content: ''; position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(26,187,156,0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
.vi-header > * { position: relative; z-index: 1; }
.vi-header-title { font-size: 22px !important; font-weight: 900 !important; color: #f1f5f9 !important; letter-spacing: -0.5px; }
.vi-header-sub { font-size: 13px !important; color: #94a3b8 !important; margin-top: 4px; font-weight: 500; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 14px !important; overflow: hidden; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important; transition: all 0.3s ease; }
.vi-panel:hover { border-color: rgba(26,187,156,0.2) !important; box-shadow: 0 8px 32px rgba(26,187,156,0.1) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 12px; padding: 16px 20px !important; border-bottom: 1px solid rgba(255,255,255,0.06) !important; background: linear-gradient(90deg, #1a2235 0%, rgba(26,187,156,0.03) 100%) !important; }
.vi-panel-title { font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.4px; color: #94a3b8 !important; flex: 1; }
.vi-panel-body { padding: 20px !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 12px 16px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.3px; text-transform: uppercase; color: #5a6b7a !important; text-align: left; background: rgba(26,187,156,0.04) !important; border-bottom: 2px solid rgba(26,187,156,0.1) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04) !important; transition: all 0.2s ease; }
.vi-table tbody tr:hover { background-color: rgba(26,187,156,0.06) !important; }
.vi-table tbody td { padding: 13px 16px; color: #cbd5e1 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 700 !important; font-size: 13px; font-family: 'Monaco', 'Courier New', monospace; }
.vi-badge { padding: 5px 12px; border-radius: 8px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; backdrop-filter: blur(10px); }
.vi-badge-active { background: linear-gradient(135deg, rgba(34,197,94,0.15) 0%, rgba(34,197,94,0.08) 100%); color: #4ade80 !important; border: 1px solid rgba(34,197,94,0.3); }
.vi-badge-inactive { background: linear-gradient(135deg, rgba(107,114,128,0.12) 0%, rgba(107,114,128,0.06) 100%); color: #a1a5b0 !important; border: 1px solid rgba(107,114,128,0.2); }
.vi-badge-real { background: linear-gradient(135deg, rgba(59,158,255,0.15) 0%, rgba(59,158,255,0.08) 100%); color: #60a5fa !important; border: 1px solid rgba(59,158,255,0.3); }
.vi-badge-demo { background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(139,92,246,0.08) 100%); color: #c4b5fd !important; border: 1px solid rgba(139,92,246,0.3); }
.vi-btn { padding: 10px 18px; border-radius: 9px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn:hover { transform: translateY(-2px); }
.vi-btn-edit { background: linear-gradient(135deg, rgba(245,158,11,0.15) 0%, rgba(245,158,11,0.08) 100%); color: #fbbf24 !important; border: 1px solid rgba(245,158,11,0.3); }
.vi-btn-edit:hover { background: linear-gradient(135deg, rgba(245,158,11,0.25) 0%, rgba(245,158,11,0.15) 100%); box-shadow: 0 4px 14px rgba(245,158,11,0.2); }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="vi-header">
      <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏦 Account Management</div>
        <div class="vi-header-title">Trading Accounts</div>
        <div class="vi-header-sub">Manage and monitor all linked trading accounts and their performance</div>
      </div>
      <a href="{{ route('admin.accounts.create') }}" class="vi-btn" style="background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%); color: #fff; margin-left: auto; box-shadow: 0 4px 15px rgba(26,187,156,0.3); border: none;">
        <i class="fa fa-plus-circle"></i> Add Account
      </a>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="vi-panel">
            <div class="vi-panel-head">
                <i class="fa fa-list" style="color:#3B9EFF; font-size:14px;"></i>
                <div class="vi-panel-title">Connected Accounts</div>
            </div>
            <div class="vi-panel-body">
                <!-- Data Table -->
                <div class="vi-table-container" style="overflow-x:auto;">
                    <table class="vi-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Login</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Platform</th>
                                <th>Server</th>
                                <th>Status</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $key => $account)
                                <tr style="transition: all 0.2s ease;">
                                    <td style="font-size:11px; color:#6b7a8a; font-weight:600;">{{ $key + 1 }}</td>
                                    <td class="td-sym">{{ $account->login }}</td>
                                    <td style="font-size:11px;">{{ $account->User->name ?? 'N/A' }}</td>
                                    <td>
                                        @if(strtolower($account->account_type) === 'real')
                                            <span class="vi-badge vi-badge-real">REAL</span>
                                        @else
                                            <span class="vi-badge vi-badge-demo">DEMO</span>
                                        @endif
                                    </td>
                                    <td style="font-weight:700;">{{ strtoupper($account->platform) }}</td>
                                    <td style="font-size:11px;">{{ $account->server }}</td>
                                    <td>
                                        @if($account->active)
                                            <span class="vi-badge vi-badge-active">ACTIVE</span>
                                        @else
                                            <span class="vi-badge vi-badge-inactive">INACTIVE</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('admin.accounts.edit', $account) }}" class="vi-btn vi-btn-edit">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center; padding:20px;">No accounts found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection