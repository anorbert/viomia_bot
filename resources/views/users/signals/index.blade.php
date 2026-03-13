@extends('layouts.user')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<style>
    .right_col { background: #f3f2ef !important; padding: 20px 25px !important; }
    .ln-card { 
        background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; 
        margin-bottom: 20px; box-shadow: 0 0.1rem 0.3rem rgba(0,0,0,0.05); 
    }
    
    /* Reduced Row Height & Padding */
    .table-clean thead th { 
        background: #2A3F54; padding: 8px 12px !important; 
        border-bottom: 1px solid #eee; font-size: 10px; 
        text-transform: uppercase; color: #fff; 
    }
    .table-clean td { 
        vertical-align: middle !important; padding: 6px 12px !important; 
        border-top: 1px solid #f3f2ef !important; font-size: 12px;
    }

    /* Column Sizing */
    .index-cell { width: 30px; color: #adb5bd; font-weight: 600; font-size: 11px; }
    
    /* Premium Badge Styling */
    .badge-status { padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: 700; display: inline-block; text-transform: uppercase; }
    .bg-success-light { background-color: #dcfce7; color: #15803d; }
    .bg-danger-light { background-color: #fee2e2; color: #b91c1c; }
    .bg-warning-light { background-color: #fef9c3; color: #854d0e; }
    .bg-gray-light { background-color: #f3f4f6; color: #374151; }
    
    /* Filter Styling */
    .form-control-sm { border-radius: 6px; border: 1px solid #d1d5db; }
    .raw-text-row { background-color: #fcfcfc !important; }
    .raw-text-row td { padding: 4px 12px 8px 45px !important; border-top: none !important; }
</style>

<div class="container-fluid">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 class="mb-1" style="font-weight: 700; color: #2A3F54;">Trade History</h3>
            <p class="text-muted mb-0">All executed trades from your connected trading accounts.</p>
        </div>
        <div class="col-md-4 col-12">
            <div style="background: #f9fafb; padding: 12px; border-radius: 6px; display: flex; gap: 20px; align-items: center;">
                <!-- Daily -->
                <div style="flex: 1; border-right: 1px solid #e5e7eb; padding-right: 15px;">
                    <small class="text-muted d-block" style="font-size: 10px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Today</small>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div class="font-weight-bold {{ $dailyNet >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 13px;">
                            {{ $dailyNet >= 0 ? '+' : '' }}{{ number_format($dailyNet, 2) }}
                        </div>
                        <small style="font-size: 9px; color: #999;">
                            <span class="text-success">+{{ number_format($dailyProfit, 2) }}</span> / <span class="text-danger">-{{ number_format($dailyLoss, 2) }}</span>
                        </small>
                    </div>
                </div>
                <!-- Weekly -->
                <div style="flex: 1; border-right: 1px solid #e5e7eb; padding-right: 15px;">
                    <small class="text-muted d-block" style="font-size: 10px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Week</small>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div class="font-weight-bold {{ $weeklyNet >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 13px;">
                            {{ $weeklyNet >= 0 ? '+' : '' }}{{ number_format($weeklyNet, 2) }}
                        </div>
                        <small style="font-size: 9px; color: #999;">
                            <span class="text-success">+{{ number_format($weeklyProfit, 2) }}</span> / <span class="text-danger">-{{ number_format($weeklyLoss, 2) }}</span>
                        </small>
                    </div>
                </div>
                <!-- Monthly -->
                <div style="flex: 1;">
                    <small class="text-muted d-block" style="font-size: 10px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Month</small>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div class="font-weight-bold {{ $monthlyNet >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 13px;">
                            {{ $monthlyNet >= 0 ? '+' : '' }}{{ number_format($monthlyNet, 2) }}
                        </div>
                        <small style="font-size: 9px; color: #999;">
                            <span class="text-success">+{{ number_format($monthlyProfit, 2) }}</span> / <span class="text-danger">-{{ number_format($monthlyLoss, 2) }}</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="ln-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('user.signals.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted mb-1">Search</label>
                        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Ticket, Symbol...">
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted mb-1">Symbol</label>
                        <select name="symbol" class="form-control form-control-sm">
                            <option value="">All Symbols</option>
                            @foreach($symbols as $s)
                                <option value="{{ $s }}" {{ $symbol===$s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted mb-1">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            @foreach(['open','closed','cancelled'] as $st)
                                <option value="{{ $st }}" {{ $status===$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-sm btn-success px-3 mr-1" style="font-weight:600;"><i class="fa fa-filter mr-1"></i> Filter</button>
                        <a href="{{ route('user.signals.index') }}" class="btn btn-sm btn-light border px-3" style="font-weight:600;">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="ln-card">
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-clean w-100" id="signalsTable">
                    <thead>
                        <tr>
                            <th class="no-sort">#</th>
                            <th>Date</th>
                            <th>Ticket</th>
                            <th>Symbol</th>
                            <th>Type</th>
                            <th>Lots</th>
                            <th>Entry</th>
                            <th>SL</th>
                            <th>TP</th>
                            <th>Status</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trades as $i => $trade)
                            @php
                                $statusClass = match($trade->status){
                                    'open' => 'bg-warning-light',
                                    'closed' => 'bg-success-light',
                                    'cancelled' => 'bg-danger-light',
                                    default => 'bg-gray-light'
                                };
                                $typeColor = $trade->type === 'buy' ? 'text-success' : 'text-danger';
                                $profitColor = $trade->profit > 0 ? 'text-success' : ($trade->profit < 0 ? 'text-danger' : 'text-muted');
                            @endphp
                            <tr>
                                <td class="index-cell">{{ $trades->firstItem() + $i }}</td>
                                <td class="text-muted" style="font-size: 12px;">{{ optional($trade->created_at)->format('d M, Y H:i') }}</td>
                                <td class="font-weight-bold">{{ $trade->ticket }}</td>
                                <td class="font-weight-bold text-primary">{{ $trade->symbol }}</td>
                                <td>
                                    <span class="badge {{ $typeColor }}" style="font-weight: 800; font-size: 10px;">
                                        <i class="fa fa-caret-{{ $trade->type === 'buy' ? 'up' : 'down' }} mr-1"></i>{{ ucfirst($trade->type) }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">{{ $trade->lots }}</td>
                                <td class="font-weight-bold">{{ $trade->open_price }}</td>
                                <td class="text-danger">{{ $trade->sl }}</td>
                                <td class="text-success">{{ $trade->tp }}</td>
                                <td>
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ ucfirst($trade->status) }}
                                    </span>
                                </td>
                                <td class="font-weight-bold {{ $profitColor }}">
                                    @if($trade->profit)
                                        @if($trade->profit > 0)
                                            <i class="fa fa-arrow-up mr-1"></i>Profit: +{{ number_format($trade->profit, 2) }}
                                        @else
                                            <i class="fa fa-arrow-down mr-1"></i>Loss: {{ number_format($trade->profit, 2) }}
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">No trades recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($trades->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $trades->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    let dataTable = $('#signalsTable').DataTable({
        "paging": true,
        "pageLength": 10,
        "searching": true,
        "info": true,
        "lengthChange": false,
        "columnDefs": [
            { "orderable": false, "targets": [0, 10] }
        ]
    });

    // Auto-refresh table every 5 seconds
    setInterval(function() {
        let currentUrl = new URL(window.location.href);
        let queryString = currentUrl.search;
        
        fetch(window.location.pathname + queryString + (queryString ? '&' : '?') + '_ajax=1')
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let newDoc = parser.parseFromString(html, 'text/html');
                let newTable = newDoc.querySelector('#signalsTable tbody');
                
                if (newTable) {
                    // Update table body with new data
                    document.querySelector('#signalsTable tbody').innerHTML = newTable.innerHTML;
                    
                    // Reinitialize DataTable
                    dataTable.destroy();
                    dataTable = $('#signalsTable').DataTable({
                        "paging": true,
                        "pageLength": 10,
                        "searching": true,
                        "info": true,
                        "lengthChange": false,
                        "columnDefs": [
                            { "orderable": false, "targets": [0, 10] }
                        ]
                    });
                }
            })
            .catch(error => console.log('Auto-refresh error:', error));
    }, 5000); // Refresh every 5 seconds
});
</script>
@endpush