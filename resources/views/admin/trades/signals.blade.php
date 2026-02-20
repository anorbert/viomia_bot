@extends('layouts.admin')

@section('styles')
<style>
    /* Global 10px Senior Professional UI */
    :root { 
        --senior-green: #1a7e33; 
        --ui-font: 10px; 
    }
    
    body { font-size: var(--ui-font); font-family: 'Inter', sans-serif; }

    /* Nav & Breadcrumb */
    .breadcrumb-item + .breadcrumb-item::before { content: "\f105"; font-family: 'FontAwesome'; font-size: 8px; }

    /* Table Micro-Styling */
    #signalsTable { border-collapse: separate; border-spacing: 0 4px; width: 100% !important; }
    
    #signalsTable thead th { 
        font-size: 9px;
        text-transform: uppercase; 
        font-weight: 800;
        letter-spacing: 0.05rem;
        color: #adb5bd;
        padding: 10px 12px;
        border: none;
    }

    #signalsTable tbody tr { 
        background-color: #ffffff; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }

    #signalsTable tbody tr:hover { background-color: #f1f8f4; }

    #signalsTable td { 
        font-size: var(--ui-font);
        padding: 10px 12px; 
        border: none; 
        vertical-align: middle;
        color: #344767;
    }

    /* Icon Containers */
    .icon-box {
        width: 22px; height: 22px;
        display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 4px;
        margin-right: 8px;
        background: #f8f9fa;
    }

    /* Directional Badges */
    .dir-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    .dir-buy { background: #e6f6ec; color: #1a7e33; }
    .dir-sell { background: #fdecf0; color: #d6293e; }

    /* Price Typography */
    .mono { font-family: 'Roboto Mono', monospace; font-weight: 600; }

    /* Status Dot */
    .status-indicator { width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .status-online { background: #1a7e33; box-shadow: 0 0 5px rgba(26, 126, 51, 0.5); }
</style>
@endsection

@section('content')
<div class="container-fluid py-3">
    
    {{-- nav --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-muted"><i class="fa fa-home mr-1"></i> Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-success font-weight-bold" aria-current="page">
                <i class="fa fa-bolt mr-1"></i> Trading Signals
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="mb-0 font-weight-bold text-dark"><i class="fa fa-signal text-muted mr-2"></i>Active Signals Terminal</h6>
        </div>
        <a href="{{ route('admin.signals.create') }}" class="btn btn-success btn-sm shadow-sm px-3 border-0 font-weight-bold">
            <i class="fa fa-plus-circle mr-1"></i> CREATE SIGNAL
        </a>
    </div>

    {{-- Table Area --}}
    <div class="table-responsive">
        <table class="table align-middle" id="signalsTable">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Asset</th>
                    <th>Execution</th>
                    <th>Entry Price</th>
                    <th>Risk (SL)</th>
                    <th>Reward (TP)</th>
                    <th>TF</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($signals as $signal)
                    <tr>
                        <td class="text-center text-muted font-weight-bold" style="width:40px;"></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-box"><i class="fa fa-line-chart text-success" style="font-size: 10px;"></i></div>
                                <span class="font-weight-bold">{{ strtoupper($signal->symbol) }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="dir-badge {{ $signal->direction == 'buy' ? 'dir-buy' : 'dir-sell' }}">
                                <i class="fa fa-{{ $signal->direction == 'buy' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                {{ strtoupper($signal->direction) }}
                            </span>
                        </td>
                        <td class="mono text-dark">{{ $signal->entry }}</td>
                        <td class="mono text-danger"><i class="fa fa-caret-down mr-1"></i>{{ $signal->sl }}</td>
                        <td class="mono text-success"><i class="fa fa-caret-up mr-1"></i>{{ $signal->tp }}</td>
                        <td>
                            <span class="text-muted"><i class="fa fa-clock-o mr-1"></i>{{ $signal->timeframe ?? 'H1' }}</span>
                        </td>
                        <td>
                            @if($signal->active)
                                <span class="status-indicator status-online"></span>
                                <span class="text-success font-weight-bold">ACTIVE</span>
                            @else
                                <span class="text-muted"><i class="fa fa-times-circle mr-1"></i> CLOSED</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group border bg-white rounded">
                                <a href="{{ route('admin.signals.edit', $signal) }}" class="btn btn-sm text-secondary" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <form action="{{ route('admin.signals.destroy', $signal) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger border-left" onclick="return confirm('Archive signal?')" title="Delete">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var t = $('#signalsTable').DataTable({
            "pageLength": 10,
            "dom": '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
            "columnDefs": [ { "orderable": false, "targets": [0, 8] } ],
            "language": {
                "search": "",
                "searchPlaceholder": "Filter terminal...",
                "lengthMenu": "_MENU_",
                "paginate": { "previous": "Prev", "next": "Next" }
            }
        });

        // Dynamic Numbering
        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            });
        }).draw();

        // Style the search box to match 10px theme
        $('.dataTables_filter input').addClass('form-control form-control-sm border-0 shadow-xs').css('font-size', '10px');
    });
</script>
@endpush