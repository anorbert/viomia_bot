@extends('layouts.admin')

@section('styles')
{{-- DataTables Core --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>
    /* Strict 10px Professional UI */
    :root { 
        --senior-green: #1a7e33; 
        --text-main: #344767;
        --table-font: 10px; 
    }
    
    body { font-size: var(--table-font); background-color: #f8f9fa; }

    /* Nav / Breadcrumb Scaling */
    .breadcrumb { font-size: 9px; letter-spacing: 0.5px; }
    .breadcrumb-item i { font-size: 10px; }

    /* Table Micro-Design */
    #tradesTable { border-collapse: separate; border-spacing: 0 4px; width: 100% !important; border: none; }
    
    #tradesTable thead th { 
        font-size: 9px;
        text-transform: uppercase; 
        font-weight: 800;
        letter-spacing: 1px;
        color: #8898aa;
        background-color: transparent;
        border: none;
        padding: 8px 10px;
    }

    #tradesTable tbody tr { 
        background-color: #ffffff; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    #tradesTable tbody tr:hover { background-color: #f1f8f4 !important; }

    #tradesTable td { 
        font-size: var(--table-font);
        padding: 6px 10px; 
        border: none; 
        vertical-align: middle;
        color: var(--text-main);
    }

    /* Column Specific Styles */
    .mono { 
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace; 
        font-weight: 600; 
        letter-spacing: -0.2px;
    }

    .badge-trade { 
        font-size: 8px !important; 
        padding: 3px 6px; 
        border-radius: 4px; 
        font-weight: 700; 
        letter-spacing: 0.3px;
    }

    /* Profit/Loss Styling */
    .profit-up { color: #2dce89 !important; font-weight: 700; }
    .profit-down { color: #f5365c !important; font-weight: 700; }

    /* Custom Icons */
    .asset-icon {
        width: 22px; height: 22px;
        display: inline-flex;
        align-items: center; justify-content: center;
        background: #e9ecef;
        border-radius: 4px;
        margin-right: 8px;
        color: #525f7f;
        font-size: 9px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-3">
    
    {{-- nav --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted"><i class="fa fa-th-large mr-1"></i> DASHBOARD</a></li>
            <li class="breadcrumb-item active text-success font-weight-bold" aria-current="page">TRADING LOG</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
        <h6 class="mb-0 font-weight-bold" style="font-size: 11px; color: #172b4d;">
            <i class="fa fa-database text-muted mr-2"></i>TRADE EXECUTION HISTORY
        </h6>
    </div>

    <div class="table-responsive">
        <table id="tradesTable" class="table align-middle">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Ticket</th>
                    <th>Type</th>
                    <th>Asset</th>
                    <th>Size</th>
                    <th>Entry</th>
                    <th>Risk/Reward</th>
                    <th>Net P/L ($)</th>
                    <th>Time (UTC)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trades as $log)
                    <tr>
                        <td class="text-center text-muted font-weight-bold" style="width:35px;"></td>
                        <td class="mono" style="color: #8898aa;">#{{ $log->ticket }}</td>
                        <td>
                            <span class="badge badge-trade {{ $log->type == 'buy' ? 'badge-success' : 'badge-danger' }}">
                                <i class="fa fa-{{ $log->type == 'buy' ? 'chevron-circle-up' : 'chevron-circle-down' }} mr-1"></i>
                                {{ strtoupper($log->type) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="asset-icon font-weight-bold">{{ substr($log->symbol, 0, 2) }}</div>
                                <span class="font-weight-bold">{{ strtoupper($log->symbol) }}</span>
                            </div>
                        </td>
                        <td class="mono">{{ number_format($log->lots, 2) }}</td>
                        <td class="mono">{{ number_format($log->open_price, 2) }}</td>
                        <td>
                            <span class="text-danger mono" style="font-size: 9px;">{{ number_format($log->sl, 2) }}</span>
                            <span class="text-muted mx-1">/</span>
                            <span class="text-success mono" style="font-size: 9px;">{{ number_format($log->tp, 2) }}</span>
                        </td>
                        <td class="mono {{ $log->profit >= 0 ? 'profit-up' : 'profit-down' }}">
                            {{ $log->profit >= 0 ? '+' : '' }}{{ number_format($log->profit, 2) }}
                        </td>
                        <td class="text-muted small">
                            {{ $log->created_at->format('M d, H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var t = $('#tradesTable').DataTable({
            "pageLength": 15,
            "order": [[ 0, 'asc' ]],
            "dom": '<"d-flex justify-content-between align-items-center mb-1"lf>rtip',
            "language": {
                "search": "",
                "searchPlaceholder": "Filter trades...",
                "lengthMenu": "_MENU_",
                "paginate": { "previous": "«", "next": "»" }
            }
        });

        // Dynamic Numbering
        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        // Custom styling for search bar to match 10px UI
        $('.dataTables_filter input').addClass('form-control form-control-sm border-0 shadow-sm').css({
            'font-size': '10px', 'width': '160px', 'border-radius': '6px'
        });
        $('.dataTables_length select').addClass('form-control form-control-sm border-0 shadow-sm').css('font-size', '10px');
    });
</script>
@endpush