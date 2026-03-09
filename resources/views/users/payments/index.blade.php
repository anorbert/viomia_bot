@extends('layouts.user')

@section('content')
<style>
    /* Payments Page Styling */
    .ln-card { 
        background: #fff; border-radius: 10px; border: 1px solid #e0e0e0; 
        margin-bottom: 25px; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.05); 
    }
    
    /* Status Badge Styling */
    .badge-status { 
        padding: 6px 14px; border-radius: 20px; font-size: 11px; 
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .bg-success-light { background-color: #dcfce7; color: #15803d; }
    .bg-warning-light { background-color: #fef9c3; color: #854d0e; }
    .bg-danger-light { background-color: #fee2e2; color: #b91c1c; }
    .bg-pending-light { background-color: #e0e7ff; color: #3730a3; }
    
    /* Filter Card */
    .filter-card {
        background: #f8f9fa; border-radius: 10px; border: 1px solid #e0e0e0;
    }
    
    /* Table Enhancements */
    .table-payments {
        --bs-table-bg: transparent;
        --bs-table-border-color: #f0f0f0;
    }
    .table-payments thead th {
        background: #f8f9fa;
        color: #5e6e82;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e3e6ed;
        padding: 12px;
    }
    .table-payments tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-payments tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Amount Column */
    .amount-badge {
        font-weight: 700;
        color: #1ABB9C;
        font-size: 13px;
    }
    
    /* Action Buttons */
    .btn-action {
        padding: 5px 10px;
        font-size: 11px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .empty-state-icon i {
        font-size: 36px;
        color: #9ca3af;
    }
    
    /* Stats Cards */
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .stat-card h6 {
        font-size: 11px;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .stat-subtext {
        font-size: 12px;
        opacity: 0.85;
    }
</style>

<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 class="mb-1" style="font-weight: 700; color: #2A3F54;">
                <i class="fa fa-credit-card text-primary mr-2"></i>Payments & Invoices
            </h3>
            <p class="text-muted mb-0">Track your payment history and download invoices</p>
        </div>
        <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0">
            <a href="#" class="btn btn-outline-primary" style="border-radius: 8px; font-weight: 600;">
                <i class="fa fa-download mr-1"></i> Export Payments
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); box-shadow: 0 4px 12px rgba(26, 187, 156, 0.3);">
                <h6><i class="fa fa-check-circle mr-1"></i>Successful</h6>
                <div class="stat-value">{{ $stats['successful'] ?? 0 }}</div>
                <div class="stat-subtext">Total completed payments</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);">
                <h6><i class="fa fa-clock-o mr-1"></i>Pending</h6>
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-subtext">Awaiting processing</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);">
                <h6><i class="fa fa-times-circle mr-1"></i>Failed</h6>
                <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
                <div class="stat-subtext">Requires attention</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%); box-shadow: 0 4px 12px rgba(52, 73, 94, 0.3);">
                <h6><i class="fa fa-ban mr-1"></i>Cancelled</h6>
                <div class="stat-value">{{ $stats['cancelled'] ?? 0 }}</div>
                <div class="stat-subtext">Revoked transactions</div>
            </div>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="ln-card filter-card">
        <div class="card-body p-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-uppercase text-muted">Search</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" 
                           placeholder="Reference, amount, plan..." style="border-radius: 8px; border: 1px solid #e0e0e0;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Status Filter</label>
                    <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                        <option value="">All Status</option>
                        @foreach(['success','pending','failed','cancelled'] as $st)
                            <option value="{{ $st }}" {{ ($status ?? '') ===$st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ route('user.payments.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="ln-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-payments mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 15%;">Reference</th>
                            <th style="width: 12%;">Method</th>
                            <th style="width: 12%;">Amount</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 16%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $i => $p)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ $i+1 }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ optional($p->created_at)->format('d M Y') ?? '-' }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        {{ optional($p->created_at)->format('H:i A') ?? '' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #2A3F54;">
                                        #{{ $p->reference ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $methodIcon = match($p->method ?? 'unknown') {
                                            'bank_transfer' => 'fa-university',
                                            'card' => 'fa-credit-card',
                                            'crypto' => 'fa-bitcoin',
                                            'wallet' => 'fa-wallet',
                                            default => 'fa-money'
                                        };
                                    @endphp
                                    <i class="fa {{ $methodIcon }} text-primary mr-1"></i>
                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $p->method ?? '-')) }}</small>
                                </td>
                                <td>
                                    <span class="amount-badge">
                                        ${{ number_format($p->amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($p->status ?? 'pending') {
                                            'success' => 'bg-success-light',
                                            'pending' => 'bg-pending-light',
                                            'failed' => 'bg-danger-light',
                                            'cancelled' => 'bg-danger-light',
                                            default => 'bg-pending-light'
                                        };
                                        $statusIcon = match($p->status ?? 'pending') {
                                            'success' => 'fa-check-circle',
                                            'pending' => 'fa-hourglass-half',
                                            'failed' => 'fa-times-circle',
                                            'cancelled' => 'fa-ban',
                                            default => 'fa-question-circle'
                                        };
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        <i class="fa {{ $statusIcon }} mr-1"></i>{{ ucfirst($p->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="#" class="btn btn-action btn-outline-primary" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-action btn-outline-secondary" title="Download Invoice">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        @if(($p->status ?? 'pending') === 'failed')
                                            <a href="#" class="btn btn-action btn-outline-warning" title="Retry Payment">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fa fa-inbox"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No Payments Found</h5>
                                        <p class="text-muted mb-3">You haven't made any payments yet.</p>
                                        <a href="{{ route('user.plans.index') ?? '#' }}" class="btn btn-primary btn-sm" style="border-radius: 8px;">
                                            <i class="fa fa-shopping-cart mr-1"></i>Browse Plans
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination (if using pagination) --}}
    @if(method_exists($payments, 'links'))
        <div class="d-flex justify-content-end mt-4">
            {{ $payments->links() }}
        </div>
    @endif

</div>

@endsection
