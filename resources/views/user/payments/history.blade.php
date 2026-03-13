@extends('layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="fas fa-receipt"></i> Payment History
            </h2>

            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            {{ $payment->created_at->format('M d, Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $payment->plan->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1">
                                            {{ substr($payment->reference, 0, 12) }}...
                                        </code>
                                    </td>
                                    <td>
                                        @if($payment->status === 'paid')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Paid
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->paid_at->format('M d, Y') }}
                                            </small>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($payment->provider) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                    class="btn btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#auditLogModal"
                                                    onclick="loadAuditLog({{ $payment->id }})">
                                                <i class="fas fa-history"></i> Log
                                            </button>
                                            @if($payment->status === 'pending')
                                                <a href="{{ $payment->checkout_url }}" 
                                                   class="btn btn-outline-success" 
                                                   target="_blank">
                                                    <i class="fas fa-external-link-alt"></i> Pay
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav class="d-flex justify-content-center">
                    {{ $payments->links() }}
                </nav>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No payment history found.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Audit Log Modal -->
<div class="modal fade" id="auditLogModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Audit Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="auditLogContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadAuditLog(paymentId) {
    fetch(`/api/payments/${paymentId}/audit-log`)
        .then(response => response.json())
        .then(data => {
            let html = '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead><tr><th>Action</th><th>Date</th><th>Status</th><th>IP Address</th></tr></thead>';
            html += '<tbody>';

            if (data.logs && data.logs.length > 0) {
                data.logs.forEach(log => {
                    html += `<tr>
                        <td><strong>${log.action}</strong></td>
                        <td><small>${new Date(log.created_at).toLocaleString()}</small></td>
                        <td>
                            <span class="badge bg-secondary">
                                ${log.old_status || 'N/A'} → ${log.new_status || 'N/A'}
                            </span>
                        </td>
                        <td><small class="text-muted">${log.ip_address || 'N/A'}</small></td>
                    </tr>`;
                });
            } else {
                html += '<tr><td colspan="4" class="text-center text-muted">No audit logs found</td></tr>';
            }

            html += '</tbody></table></div>';
            document.getElementById('auditLogContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('auditLogContent').innerHTML = 
                '<div class="alert alert-danger">Failed to load audit log</div>';
        });
}
</script>
@endpush

@endsection
