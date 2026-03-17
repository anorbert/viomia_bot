@extends('layouts.admin')

@section('title', 'Payment #' . $payment->id . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 1200px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-details-item { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 6px; padding: 14px; }
.vi-details-label { font-size: 10px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.vi-details-value { font-size: 13px; color: #f1f5f9; font-weight: 600; }
.vi-status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.vi-status-success { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-pending { background: rgba(251,146,60,0.15); color: #FB923C; }
.vi-status-failed { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-status-completed { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 14px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-amount-display { font-size: 32px; font-weight: 900; color: #1ABB9C; }
.vi-amount-currency { font-size: 14px; color: #4b5563; margin-left: 4px; }
.vi-buttons { display: flex; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); flex-wrap: wrap; }
.vi-btn { padding: 10px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-primary:disabled { background-color: #094d3e !important; opacity: 0.6 !important; cursor: not-allowed !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.15) !important; color: #EF4444 !important; border: 1px solid rgba(239,68,68,0.25) !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.25) !important; }
.transaction-details { background: rgba(26,187,156,0.02); border: 1px solid rgba(26,187,156,0.1); border-radius: 8px; padding: 16px; margin-bottom: 20px; }
.transaction-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.07); }
.transaction-row:last-child { border-bottom: none; }
.transaction-label { color: #94a3b8; font-size: 12px; font-weight: 600; }
.transaction-value { color: #f1f5f9; font-weight: 600; font-size: 13px; }
.code-block { background: #1a2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 12px; font-family: monospace; font-size: 11px; color: #1ABB9C; word-break: break-all; max-height: 300px; overflow-y: auto; }
.timeline { position: relative; padding: 20px 0; }
.timeline-item { position: relative; padding-left: 40px; margin-bottom: 20px; }
.timeline-item::before { content: ''; position: absolute; left: 0; top: 0; width: 12px; height: 12px; background: #1ABB9C; border-radius: 50%; border: 2px solid #111827; }
.timeline-item::after { content: ''; position: absolute; left: 5px; top: 12px; width: 2px; height: calc(100% + 8px); background: rgba(26,187,156,0.2); }
.timeline-item:last-child::after { display: none; }
.timeline-time { font-size: 11px; color: #4b5563; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.timeline-content { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.user-card { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 8px; padding: 16px; }
.user-card-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
.user-info { margin-bottom: 10px; display: flex; justify-content: space-between; }
.user-info-label { font-size: 11px; color: #4b5563; font-weight: 600; }
.user-info-value { font-size: 12px; color: #f1f5f9; font-weight: 600; }
.success-msg { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.error-msg { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
@media (max-width: 768px) {
  .two-column { grid-template-columns: 1fr; }
  .vi-buttons { flex-direction: column; }
  .vi-btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="success-msg">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-msg">
            <i class="fa fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💳 Payments</div>
            <div class="vi-header-title">Payment #{{ substr($payment->id, 0, 8) }}</div>
            <div class="vi-header-sub">Complete transaction details and management</div>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back
        </a>
    </div>

    <!-- Main Payment Overview -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-credit-card"></i> Payment Summary</div>

        <!-- Status and Amount Grid -->
        <div class="vi-details-grid" style="margin-bottom: 24px;">
            <div class="vi-details-item">
                <div class="vi-details-label">Status</div>
                <div class="vi-details-value">
                    @if($payment->status === 'success')
                        <span class="vi-status-badge vi-status-completed">
                            <i class="fa fa-check-circle"></i> COMPLETED
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="vi-status-badge vi-status-pending">
                            <i class="fa fa-hourglass-half"></i> PENDING
                        </span>
                    @else
                        <span class="vi-status-badge vi-status-failed">
                            <i class="fa fa-times-circle"></i> FAILED
                        </span>
                    @endif
                </div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Amount</div>
                <div class="vi-details-value">
                    <span class="vi-amount-display">{{ number_format($payment->amount, 2) }}</span>
                    <span class="vi-amount-currency">{{ $payment->currency ?? 'USD' }}</span>
                </div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Payment Date</div>
                <div class="vi-details-value">
                    <i class="fa fa-calendar"></i> {{ $payment->created_at?->format('M d, Y') ?? '—' }}<br>
                    <span style="font-size: 11px; color: #4b5563;">{{ $payment->created_at?->format('H:i:s A') ?? '—' }}</span>
                </div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Payment Method</div>
                <div class="vi-details-value">
                    <i class="fa fa-{{ $payment->provider === 'momo' ? 'mobile' : 'bitcoin' }}"></i>
                    {{ ucfirst($payment->provider ?? 'Unknown') }}
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="transaction-details">
            <div class="transaction-row">
                <span class="transaction-label"><i class="fa fa-key"></i> Reference ID</span>
                <span class="transaction-value">{{ $payment->reference ?? 'N/A' }}</span>
            </div>
            <div class="transaction-row">
                <span class="transaction-label"><i class="fa fa-shuffle"></i> Transaction ID</span>
                <span class="transaction-value">{{ $payment->transaction_id ?? 'N/A' }}</span>
            </div>
            @if($payment->plan)
            <div class="transaction-row">
                <span class="transaction-label"><i class="fa fa-package"></i> Plan</span>
                <span class="transaction-value">{{ $payment->plan->name ?? 'N/A' }}</span>
            </div>
            @endif
            <div class="transaction-row">
                <span class="transaction-label"><i class="fa fa-info-circle"></i> Description</span>
                <span class="transaction-value">{{ $payment->description ?? 'Payment transaction' }}</span>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: User & Provider Details -->
    <div class="two-column">
        <!-- User Information -->
        <div class="vi-panel" style="margin-bottom: 0;">
            <div class="vi-panel-title"><i class="fa fa-user"></i> Payer Information</div>
            <div class="user-card">
                <div class="user-card-title">Account Details</div>
                @if($payment->user)
                    <div class="user-info">
                        <span class="user-info-label">Full Name</span>
                        <span class="user-info-value">{{ $payment->user->name }}</span>
                    </div>
                    <div class="user-info">
                        <span class="user-info-label">Email</span>
                        <span class="user-info-value">{{ $payment->user->email }}</span>
                    </div>
                    <div class="user-info">
                        <span class="user-info-label">Phone</span>
                        <span class="user-info-value">{{ $payment->user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="user-info">
                        <span class="user-info-label">Member Since</span>
                        <span class="user-info-value">{{ $payment->user?->created_at?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.07);">
                        <a href="#" class="vi-btn vi-btn-secondary" style="width: 100%; justify-content: center;">
                            <i class="fa fa-user-circle"></i> View Profile
                        </a>
                    </div>
                @else
                    <div style="color: #94a3b8; font-size: 12px;">User information not available</div>
                @endif
            </div>
        </div>

        <!-- Payment Provider Details -->
        <div class="vi-panel" style="margin-bottom: 0;">
            <div class="vi-panel-title"><i class="fa fa-server"></i> Provider Details</div>
            <div class="user-card">
                <div class="user-card-title">Payment Gateway Info</div>
                <div class="user-info">
                    <span class="user-info-label">Provider</span>
                    <span class="user-info-value">{{ ucfirst($payment->provider ?? 'Unknown') }}</span>
                </div>
                <div class="user-info">
                    <span class="user-info-label">Status</span>
                    <span class="user-info-value">
                        @if($payment->status === 'success')
                            <span style="color: #22C55E;">✓ Successful</span>
                        @elseif($payment->status === 'pending')
                            <span style="color: #FB923C;">⏳ Pending</span>
                        @else
                            <span style="color: #EF4444;">✗ Failed</span>
                        @endif
                    </span>
                </div>
                <div class="user-info">
                    <span class="user-info-label">Transaction ID</span>
                    <span class="user-info-value">{{ $payment->provider_txn_id ?? 'Pending' }}</span>
                </div>
                <div class="user-info">
                    <span class="user-info-label">Processing Time</span>
                    <span class="user-info-value">
                        @if($payment->paid_at)
                            {{ $payment->paid_at->diffInMinutes($payment->created_at) }} min
                        @else
                            Pending
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadata (if available) -->
    @if($payment->metadata)
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-code"></i> Metadata</div>
        <div class="code-block">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</div>
    </div>
    @endif

    <!-- Payment Notes -->
    @if($payment->notes)
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-sticky-note"></i> Notes</div>
        <div style="color: #94a3b8; font-size: 13px; line-height: 1.6; padding: 12px; background: rgba(26,187,156,0.02); border-radius: 6px;">
            {{ $payment->notes }}
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-cogs"></i> Actions</div>
        <div class="vi-buttons">
            @if($payment->status !== 'success')
            <form method="POST" action="{{ route('admin.payments.update', $payment->id) }}" style="margin: 0;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="success">
                <button type="submit" class="vi-btn vi-btn-primary" onclick="return confirm('Mark this payment as completed?')">
                    <i class="fa fa-check"></i> Mark as Completed
                </button>
            </form>
            @endif

            @if($payment->status !== 'failed')
            <form method="POST" action="{{ route('admin.payments.update', $payment->id) }}" style="margin: 0;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="failed">
                <button type="submit" class="vi-btn vi-btn-danger" onclick="return confirm('Mark this payment as failed?')">
                    <i class="fa fa-times"></i> Mark as Failed
                </button>
            </form>
            @endif

            @if($payment->user && $payment->status === 'success')
            <button class="vi-btn vi-btn-primary" onclick="resendNotification()">
                <i class="fa fa-envelope"></i> Resend Notification
            </button>
            @endif

            <a href="{{ route('admin.payments.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Payments
            </a>
        </div>
    </div>
</div>

<!-- Resend Notification Modal -->
<div id="resendModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #111827; border: 1px solid rgba(26,187,156,0.2); border-radius: 12px; padding: 28px; max-width: 400px; width: 90%;">
        <div style="font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 12px;">
            <i class="fa fa-envelope" style="color: #1ABB9C; margin-right: 8px;"></i> Resend Notification
        </div>
        <div style="font-size: 12px; color: #94a3b8; margin-bottom: 20px;">
            Send payment confirmation to {{ $payment->user->email ?? 'user' }}?
        </div>
        <div style="display: flex; gap: 12px;">
            <form method="POST" action="#" id="resendForm" style="flex: 1;">
                @csrf
                <button type="submit" class="vi-btn vi-btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa fa-check"></i> Send
                </button>
            </form>
            <button onclick="document.getElementById('resendModal').style.display='none';" class="vi-btn vi-btn-secondary" style="flex: 1;">
                <i class="fa fa-times"></i> Cancel
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resendNotification() {
    document.getElementById('resendModal').style.display = 'flex';
    document.getElementById('resendForm').action = "{{ route('admin.payments.resend', $payment->id) }}";
}

// Close modal when clicking outside
document.getElementById('resendModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>
@endpush

@endsection
