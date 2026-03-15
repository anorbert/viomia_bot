@extends('layouts.admin')

@section('title', 'Pending Account Verification — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #111827 0%, #1a2235 100%) !important; border: 1px solid rgba(245,158,11,0.15) !important; border-top: 3px solid #F59E0B !important; border-radius: 14px !important; padding: 24px !important; margin-bottom: 24px !important; box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 20px rgba(245,158,11,0.08) !important; position: relative; overflow: hidden; }
.vi-header::before { content: ''; position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
.vi-header > * { position: relative; z-index: 1; }
.vi-header-title { font-size: 22px !important; font-weight: 900 !important; color: #f1f5f9 !important; letter-spacing: -0.5px; }
.vi-header-sub { font-size: 13px !important; color: #94a3b8 !important; margin-top: 4px; font-weight: 500; }
.vi-header-badge { background: linear-gradient(135deg, rgba(245,158,11,0.12) 0%, rgba(245,158,11,0.06) 100%) !important; border: 1px solid rgba(245,158,11,0.3) !important; color: #fbbf24 !important; padding: 8px 16px !important; border-radius: 9px !important; font-size: 12px !important; font-weight: 700 !important; margin-left: auto; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 14px !important; overflow: hidden; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important; transition: all 0.3s ease; }
.vi-panel:hover { border-color: rgba(245,158,11,0.2) !important; box-shadow: 0 8px 32px rgba(245,158,11,0.08) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 12px; padding: 16px 20px !important; border-bottom: 1px solid rgba(255,255,255,0.06) !important; background: linear-gradient(90deg, #1a2235 0%, rgba(245,158,11,0.03) 100%) !important; border-left: 4px solid #F59E0B !important; }
.vi-panel-title { font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.4px; color: #cbd5e1 !important; flex: 1; }
.vi-panel-body { padding: 24px !important; }
.vi-alert { padding: 14px 18px !important; border-radius: 10px !important; margin-bottom: 20px !important; display: flex !important; align-items: center !important; gap: 12px !important; animation: slideDown 0.3s ease; }
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.vi-alert-success { background: linear-gradient(135deg, rgba(34,197,94,0.1) 0%, rgba(34,197,94,0.05) 100%) !important; border: 1px solid rgba(34,197,94,0.3) !important; color: #4ade80 !important; }
.vi-alert-error { background: linear-gradient(135deg, rgba(239,68,68,0.1) 0%, rgba(239,68,68,0.05) 100%) !important; border: 1px solid rgba(239,68,68,0.3) !important; color: #fca5a5 !important; }
.vi-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
.vi-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 13px 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
.vi-detail-row:last-child { border-bottom: none; }
.vi-detail-label { font-size: 10px; font-weight: 800; text-transform: uppercase; color: #5a6b7a; letter-spacing: 1.2px; }
.vi-detail-value { color: #f1f5f9; font-weight: 600; font-size: 13px; }
.vi-input { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.12) !important; border-radius: 9px !important; color: #f1f5f9 !important; padding: 12px 16px !important; font-size: 12px !important; width: 100%; transition: all 0.2s ease; }
.vi-input:focus { outline: none !important; border-color: rgba(245,158,11,0.4) !important; box-shadow: 0 0 0 4px rgba(245,158,11,0.12) !important; }
.vi-btn { padding: 11px 18px; border-radius: 9px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn:hover { transform: translateY(-2px); }
.vi-btn-primary { background: linear-gradient(135deg, #22C55E 0%, #16a34a 100%); color: #fff !important; box-shadow: 0 4px 15px rgba(34,197,94,0.3); }
.vi-btn-primary:hover { box-shadow: 0 6px 24px rgba(34,197,94,0.4); }
.vi-btn-danger { background: linear-gradient(135deg, #EF4444 0%, #dc2626 100%); color: #fff !important; box-shadow: 0 4px 15px rgba(239,68,68,0.3); }
.vi-btn-danger:hover { box-shadow: 0 6px 24px rgba(239,68,68,0.4); }
.vi-btn-outline { background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15) !important; color: #cbd5e1; }
.vi-btn-outline:hover { background-color: rgba(255,255,255,0.14); color: #f1f5f9; }
.vi-badge { padding: 5px 12px; border-radius: 8px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; backdrop-filter: blur(10px); }
.vi-badge-mt4 { background: linear-gradient(135deg, rgba(59,158,255,0.15) 0%, rgba(59,158,255,0.08) 100%); color: #60a5fa !important; border: 1px solid rgba(59,158,255,0.3); }
.vi-badge-mt5 { background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(139,92,246,0.08) 100%); color: #c4b5fd !important; border: 1px solid rgba(139,92,246,0.3); }
.vi-badge-real { background: linear-gradient(135deg, rgba(34,197,94,0.15) 0%, rgba(34,197,94,0.08) 100%); color: #4ade80 !important; border: 1px solid rgba(34,197,94,0.3); }
.vi-badge-demo { background: linear-gradient(135deg, rgba(107,114,128,0.12) 0%, rgba(107,114,128,0.06) 100%); color: #a1a5b0 !important; border: 1px solid rgba(107,114,128,0.2); }
.vi-info-box { background: linear-gradient(135deg, rgba(26,187,156,0.08) 0%, rgba(26,187,156,0.04) 100%) !important; border: 1px solid rgba(26,187,156,0.25) !important; border-radius: 10px !important; padding: 14px !important; font-size: 11px !important; color: #cbd5e1 !important; margin-top: 14px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#F59E0B; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">⏳ Verification Queue</div>
        <div class="vi-header-title">Pending Account Verification</div>
        <div class="vi-header-sub">Review and approve trading accounts awaiting confirmation</div>
    </div>
    <div class="vi-header-badge">
        <i class="fa fa-hourglass-half"></i> {{ $pendingAccounts->count() ?? 0 }} Pending
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="vi-alert vi-alert-success">
        <i class="fa fa-check-circle" style="font-size:16px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="vi-alert vi-alert-error">
        <i class="fa fa-exclamation-circle" style="font-size:16px;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- Accounts List --}}
@forelse($pendingAccounts as $key => $account)
    <div class="vi-panel" style="border-top: 3px solid #F59E0B;">
        <div class="vi-panel-head">
            <span style="background-color:rgba(245,158,11,0.2); color:#F59E0B; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:800;">PENDING</span>
            <div class="vi-panel-title" style="flex:1; margin-left:8px;">{{ $account->user->name ?? 'Unknown' }} <span style="font-size:10px; color:#4b5563; font-weight:400; margin-left:8px;">({{ $account->user->email ?? '' }})</span></div>
        </div>

        <div class="vi-panel-body">
            <div class="vi-details-grid">
                {{-- Left Column: Account Details --}}
                <div class="vi-details-col">
                    <div style="margin-bottom:20px;">
                        <div style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,0.1);">Account Information</div>
                        
                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Account ID</span>
                            <span class="vi-detail-value" style="font-family:monospace;">{{ $account->id }}</span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Login</span>
                            <span class="vi-detail-value" style="font-family:monospace; color:#3B9EFF;">{{ $account->login }}</span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Platform</span>
                            <span>
                                @if($account->platform === 'mt4')
                                    <span class="vi-badge vi-badge-mt4">MT4</span>
                                @else
                                    <span class="vi-badge vi-badge-mt5">MT5</span>
                                @endif
                            </span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Server</span>
                            <span class="vi-detail-value">{{ $account->server ?? 'N/A' }}</span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Type</span>
                            <span>
                                @if(strtolower($account->account_type) === 'real')
                                    <span class="vi-badge vi-badge-real">REAL</span>
                                @else
                                    <span class="vi-badge vi-badge-demo">DEMO</span>
                                @endif
                            </span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Password</span>
                            <span style="display:flex; align-items:center; gap:8px;">
                                <code style="background-color:rgba(59,158,255,0.1); color:#3B9EFF; padding:2px 6px; border-radius:4px; font-size:10px;">
                                    <span id="password-{{ $account->id }}" style="display:none;">{{ $account->password }}</span>
                                    <span id="password-masked-{{ $account->id }}">••••••••</span>
                                </code>
                                <button type="button" class="vi-btn vi-btn-outline" style="padding:4px 8px; font-size:10px;" onclick="togglePassword({{ $account->id }})">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </span>
                        </div>

                        <div class="vi-detail-row">
                            <span class="vi-detail-label">Submitted</span>
                            <span class="vi-detail-value">{{ $account->created_at ? $account->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Actions --}}
                <div class="vi-details-col">
                    {{-- Verification Form --}}
                    {{-- Verification Actions Container --}}
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        {{-- Approve Form --}}
                        <form action="{{ route('admin.accounts.verify', $account->id) }}" method="POST">
                            @csrf
                            
                            <div style="background: linear-gradient(135deg, rgba(34,197,94,0.08) 0%, rgba(34,197,94,0.04) 100%); border: 1px solid rgba(34,197,94,0.25); border-radius: 10px; padding: 18px; overflow: hidden;">
                                <div style="font-size: 12px; font-weight: 800; color: #4ade80; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-check-circle-o"></i> Approve Account
                                </div>
                                <div class="vi-form-group">
                                    <label>Verification Notes <span style="color: #4b5563;">(Optional)</span></label>
                                    <textarea name="verification_notes" class="vi-input" rows="3" placeholder="Add any internal notes about this account verification..." style="resize: vertical;"></textarea>
                                </div>
                                <button type="submit" class="vi-btn vi-btn-primary" style="width: 100%; justify-content: center; padding: 13px;" onclick="return confirm('Approve and verify this account?')">
                                    <i class="fa fa-check"></i> Approve & Activate
                                </button>
                            </div>
                        </form>

                        {{-- Reject Form --}}
                        <form action="{{ route('admin.accounts.reject', $account->id) }}" method="POST">
                            @csrf
                            
                            <div style="background: linear-gradient(135deg, rgba(239,68,68,0.08) 0%, rgba(239,68,68,0.04) 100%); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 18px; overflow: hidden;">
                                <div style="font-size: 12px; font-weight: 800; color: #fca5a5; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-times-circle-o"></i> Reject Account
                                </div>
                                <div class="vi-form-group">
                                    <label>Rejection Reason <span style="color: #ef4444;">*</span></label>
                                    <textarea name="rejection_reason" class="vi-input" rows="3" placeholder="Provide clear reason for rejection..." required style="resize: vertical;"></textarea>
                                </div>
                                <button type="submit" class="vi-btn vi-btn-danger" style="width: 100%; justify-content: center; padding: 13px;" onclick="return confirm('Reject this account? User will be notified.')">
                                    <i class="fa fa-times"></i> Reject
                                </button>
                            </div>
                        </form>

                        {{-- Info Box --}}
                        <div class="vi-info-box">
                            <i class="fa fa-info-circle" style="color: #1ABB9C;"></i> 
                            <strong>Approval will activate</strong> the account and grant trading access. 
                            <strong>Rejection will notify</strong> the user to resubmit or contact support.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="vi-panel">
        <div class="vi-panel-body" style="text-align: center; padding: 60px 30px;">
            <div style="font-size: 64px; color: rgba(245,158,11,0.15); margin-bottom: 20px; animation: bounce 2s infinite;">
                <i class="fa fa-inbox"></i>
            </div>
            <div style="font-size: 18px; font-weight: 800; color: #f1f5f9; margin-bottom: 10px;">No Pending Accounts</div>
            <div style="font-size: 13px; color: #7a96ab; margin-bottom: 24px; line-height: 1.6;">
                All trading accounts have been verified and are active. Check back when new account submissions arrive.
            </div>
            <a href="{{ route('admin.accounts.index') }}" class="vi-btn vi-btn-outline">
                <i class="fa fa-arrow-left"></i> View All Accounts
            </a>
        </div>
    </div>
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
@endforelse

{{-- Pagination --}}
@if($pendingAccounts && $pendingAccounts->hasPages())
    <div style="margin-top: 32px; padding: 24px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 14px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;">
        <style>
            .pagination { margin: 0; display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }
            .page-link { background-color: transparent !important; border: 1px solid rgba(255,255,255,0.12) !important; color: #cbd5e1 !important; padding: 10px 14px !important; border-radius: 8px !important; font-weight: 600; font-size: 12px; text-decoration: none; transition: all 0.2s; }
            .page-link:hover { background-color: rgba(245,158,11,0.1) !important; border-color: rgba(245,158,11,0.3) !important; color: #fbbf24 !important; }
            .page-item.active .page-link { background: linear-gradient(135deg, #F59E0B 0%, #d97706 100%) !important; border-color: #F59E0B !important; color: #fff !important; }
            .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; }
        </style>
        {{ $pendingAccounts->links() }}
    </div>
@endif


<script>
function togglePassword(accountId) {
    const passwordSpan = document.getElementById('password-' + accountId);
    const maskedSpan = document.getElementById('password-masked-' + accountId);
    
    if (passwordSpan.style.display === 'none') {
        passwordSpan.style.display = 'inline';
        maskedSpan.style.display = 'none';
    } else {
        passwordSpan.style.display = 'none';
        maskedSpan.style.display = 'inline';
    }
}
</script>

@endsection