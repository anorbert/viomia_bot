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
        background: #f9fafb; padding: 8px 12px !important; 
        border-bottom: 1px solid #eee; font-size: 10px; 
        text-transform: uppercase; color: #73879C; 
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
            <h3 class="mb-1" style="font-weight: 700; color: #2A3F54;">Trading Signals</h3>
            <p class="text-muted mb-0">History of WhatsApp signals processed by the execution engine.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="ln-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('user.signals.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted mb-1">Search</label>
                        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Sender, Symbol...">
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
                            @foreach(['pending','executed','expired','failed'] as $st)
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
                            <th>Received</th>
                            <th>Symbol</th>
                            <th>Type</th>
                            <th>Entry</th>
                            <th>SL</th>
                            <th>TP</th>
                            <th>Sender</th>
                            <th>Status</th>
                            <th class="text-right">Execs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signals as $i => $sig)
                            @php
                                $statusClass = match($sig->status){
                                    'pending' => 'bg-warning-light',
                                    'executed' => 'bg-success-light',
                                    'failed' => 'bg-danger-light',
                                    default => 'bg-gray-light'
                                };
                            @endphp
                            <tr>
                                <td class="index-cell">{{ $signals->firstItem() + $i }}</td>
                                <td>
                                    <div class="font-weight-bold" style="color:#2A3F54;">{{ optional($sig->received_at)->format('d M, H:i') }}</div>
                                    <small class="text-muted" style="font-size: 9px;">ID: {{ Str::limit($sig->group_id, 10) }}</small>
                                </td>
                                <td class="font-weight-bold text-primary">{{ $sig->symbol }}</td>
                                <td>
                                    <span class="badge {{ $sig->type==='BUY' ? 'text-success' : 'text-danger' }}" style="font-weight: 800; font-size: 10px;">
                                        <i class="fa fa-caret-{{ $sig->type==='BUY' ? 'up' : 'down' }} mr-1"></i>{{ $sig->type }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">{{ $sig->entry }}</td>
                                <td class="text-danger">{{ $sig->stop_loss }}</td>
                                <td class="text-success">
                                    @php $tps = is_array($sig->take_profit) ? $sig->take_profit : []; @endphp
                                    {{ count($tps) ? implode(', ', $tps) : '—' }}
                                </td>
                                <td><small class="text-muted">{{ Str::limit($sig->sender, 15) }}</small></td>
                                <td>
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $sig->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="badge badge-secondary" style="font-size: 10px; border-radius: 10px;">{{ $sig->executions()->count() }}</span>
                                </td>
                            </tr>
                            @if($sig->raw_text)
                            <tr class="raw-text-row">
                                <td colspan="10">
                                    <div class="text-muted italic" style="font-size: 10.5px; border-left: 2px solid #e0e0e0; padding-left: 10px;">
                                        <i class="fa fa-whatsapp mr-1"></i> {{ $sig->raw_text }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">No signals recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($signals->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $signals->links() }}
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
    $('#signalsTable').DataTable({
        "paging": false,       // Disable DT pagination (Laravel handles this)
        "searching": false,    // Disable DT search (Your Filter form handles this)
        "info": false,         // Hide "Showing 1 to 10" text
        "lengthChange": false,
        "columnDefs": [
            { "orderable": false, "targets": [0, 9] } // Disable sorting on # and Execs
        ]
    });
});
</script>
@endpush