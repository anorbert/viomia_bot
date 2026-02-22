@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">
                <i class="fa fa-hourglass-half text-warning"></i> Pending Account Verification
            </h2>
            <p class="text-muted">Review and verify registered trading accounts awaiting confirmation</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="badge bg-warning text-dark" style="font-size: 16px; padding: 8px 12px;">
                {{ $pendingAccounts->count() ?? 0 }} Pending
            </div>
        </div>
    </div>

    {{-- Filters & Actions --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Filter by User</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- All Users --</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Platform</label>
                            <select name="platform" class="form-select">
                                <option value="">-- All Platforms --</option>
                                <option value="mt4" {{ request('platform') == 'mt4' ? 'selected' : '' }}>MT4</option>
                                <option value="mt5" {{ request('platform') == 'mt5' ? 'selected' : '' }}>MT5</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.accounts.pending') }}" class="btn btn-secondary">
                                <i class="fa fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Accounts List --}}
    @forelse($pendingAccounts as $key => $account)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title" style="border-bottom: 2px solid #fbbf24;">
                        <h2 style="margin-bottom: 0; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700;">PENDING</span>
                            <span>{{ $account->user->name ?? 'Unknown' }}</span>
                            <small class="text-muted">({{ $account->user->email ?? '' }})</small>
                        </h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="row">
                            {{-- Left Column: Account Details --}}
                            <div class="col-md-7">
                                <div class="account-details">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="width: 40%; font-weight: 600; color: #374151;">
                                                    <i class="fa fa-id-card text-primary"></i> Account ID
                                                </td>
                                                <td style="color: #555;">{{ $account->id }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%; font-weight: 600; color: #374151;">
                                                    <i class="fa fa-mobile text-primary"></i> Platform
                                                </td>
                                                <td>
                                                    <span class="badge" style="background: {{ $account->platform === 'mt4' ? '#dbeafe' : '#e0f2fe' }}; color: {{ $account->platform === 'mt4' ? '#1e40af' : '#0369a1' }};">
                                                        {{ strtoupper($account->platform) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 600; color: #374151;">
                                                    <i class="fa fa-server text-primary"></i> Server
                                                </td>
                                                <td style="color: #555;">{{ $account->server ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 600; color: #374151;">
                                                    <i class="fa fa-user-shield text-primary"></i> Login
                                                </td>
                                                <td style="color: #555; font-family: monospace;">{{ $account->login }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 600; color: #374151;">
                                                    <i class="fa fa-lock text-primary"></i> Password
                                                </td>
                                                <td style="color: #555; font-family: monospace;">
                                                    <span id="password-{{ $account->id }}" style="display: none;">{{ $account->password }}</span>
                                                    <span id="password-masked-{{ $account->id }}">••••••••</span>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                            onclick="togglePassword({{ $account->id }})">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 600; color: #374151;">
                                                    <i class="fa fa-layer-group text-primary"></i> Account Type
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($account->account_type ?? 'Standard') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 600; color: #374151;">
                                                    <i class="fa fa-calendar text-primary"></i> Submitted
                                                </td>
                                                <td style="color: #555;">
                                                    {{ $account->created_at ? $account->created_at->format('M d, Y H:i') : 'N/A' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Right Column: Actions --}}
                            <div class="col-md-5">
                                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                                    <h5 style="margin-bottom: 16px; color: #111827;">
                                        <i class="fa fa-tasks"></i> Verification Actions
                                    </h5>

                                    {{-- Verification Form --}}
                                    <form action="{{ route('admin.accounts.verify', $account->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Verification Notes (Optional)</label>
                                            <textarea name="verification_notes" 
                                                      class="form-control" 
                                                      rows="3" 
                                                      placeholder="Add any notes about this account verification..."></textarea>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" 
                                                    class="btn btn-success btn-lg" 
                                                    onclick="return confirm('Confirm verification of this account?')">
                                                <i class="fa fa-check-circle"></i> Approve & Verify
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Reject Form --}}
                                    <form action="{{ route('admin.accounts.reject', $account->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rejection Reason</label>
                                            <textarea name="rejection_reason" 
                                                      class="form-control" 
                                                      rows="3" 
                                                      placeholder="Explain why this account is being rejected..."
                                                      required></textarea>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    onclick="return confirm('Reject this account? The user will be notified.')">
                                                <i class="fa fa-times-circle"></i> Reject Account
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Status Badge --}}
                                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                                        <small class="text-muted">
                                            <i class="fa fa-info-circle"></i> 
                                            Approving will activate this account for trading.<br>
                                            Rejecting will notify the user to resubmit.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="x_panel">
            <div class="x_content text-center py-5">
                <i class="fa fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px; display: block;"></i>
                <h4 class="text-muted mb-2">No Pending Accounts</h4>
                <p class="text-muted">All trading accounts have been verified. Check back later for new submissions.</p>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($pendingAccounts && $pendingAccounts->hasPages())
        <div class="row">
            <div class="col-md-12">
                <nav>
                    {{ $pendingAccounts->links() }}
                </nav>
            </div>
        </div>
    @endif
</div>

<style>
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-success {
        background-color: #10b981;
        border-color: #10b981;
    }

    .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .btn-danger {
        background-color: #ef4444;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        border-color: #dc2626;
    }

    .badge {
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }

    .account-details table td {
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .account-details table tr:last-child td {
        border-bottom: none;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .x_panel {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .x_title {
        padding: 16px;
        background: #fff;
        border-radius: 8px 8px 0 0;
    }

    .x_content {
        padding: 24px;
    }
</style>

<script>
function togglePassword(accountId) {
    const passwordEl = document.getElementById('password-' + accountId);
    const maskedEl = document.getElementById('password-masked-' + accountId);
    const btn = event.target.closest('button');

    if (passwordEl.style.display === 'none') {
        passwordEl.style.display = 'inline';
        maskedEl.style.display = 'none';
        btn.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        passwordEl.style.display = 'none';
        maskedEl.style.display = 'inline';
        btn.innerHTML = '<i class="fa fa-eye"></i>';
    }
}
</script>

@endsection