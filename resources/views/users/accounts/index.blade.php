@extends('layouts.user')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<style>
    /* Styling consistency with Dashboard */
    .right_col { background: #f3f2ef !important; padding: 20px 25px !important; }
    .ln-card { 
        background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; 
        margin-bottom: 20px; box-shadow: 0 0.1rem 0.3rem rgba(0,0,0,0.05); 
    }
    
    /* Reduced Row Height & Padding */
    .table-clean thead th { 
        background: #f9fafb; 
        padding: 8px 12px !important; /* Reduced from 12px */
        border-bottom: 1px solid #eee; 
        font-size: 10px; text-transform: uppercase; color: #73879C; 
    }
    .table-clean td { 
        vertical-align: middle !important; 
        padding: 6px 12px !important; /* Significantly reduced height */
        border-top: 1px solid #f3f2ef !important;
        font-size: 13px;
    }

    /* Numbering Column Styling */
    .index-cell { width: 30px; color: #adb5bd; font-weight: 600; font-size: 11px; }

    /* DataTable Customization */
    .dataTables_wrapper .row { margin-bottom: 10px; align-items: center; }
    .dataTables_filter input { 
        border-radius: 6px; border: 1px solid #d1d5db; padding: 4px 10px; margin-left: 10px; font-size: 12px;
    }

    /* Badge styling */
    .badge-status { padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: 700; display: inline-block; }
    .bg-success-light { background-color: #dcfce7; color: #15803d; }
    .bg-danger-light { background-color: #fee2e2; color: #b91c1c; }
    .bg-gray-light { background-color: #f3f4f6; color: #374151; }

    .pagination .page-item .page-link { padding: 4px 10px; font-size: 12px; }
    .pagination .page-item.active .page-link { background-color: #1ABB9C; border-color: #1ABB9C; }
</style>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 class="mb-1" style="font-weight: 700; color: #2A3F54;">Trading Accounts</h3>
            <p class="text-muted mb-0">Manage your connected brokerage accounts and bot permissions.</p>
        </div>
        <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0">
            <button class="btn btn-success px-4 shadow-sm" style="border-radius: 6px; font-weight: 600; font-size: 13px;" data-toggle="modal" data-target="#addAccountModal">
                <i class="fa fa-plus-circle mr-1"></i> Connect Account
            </button>
        </div>
    </div>

    <div class="ln-card">
        <div class="ln-card-body p-2"> <div class="table-responsive">
                <table class="table table-hover mb-0 table-clean w-100" id="accountsTable">
                    <thead>
                        <tr>
                            <th class="no-sort">#</th> <th>Account Login</th>
                            <th>Platform</th>
                            <th>Server</th>
                            <th>Meta</th>
                            <th>Health</th>
                            <th>Status</th>
                            <th class="text-right no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $acc)
                            <tr>
                                <td class="index-cell"></td> <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 bg-light d-flex align-items-center justify-content-center" style="width:28px; height:28px; border-radius:4px;">
                                            <i class="fa fa-university text-muted" style="font-size: 11px;"></i>
                                        </div>
                                        <div>
                                            <span class="d-block font-weight-bold" style="color:#2A3F54;">{{ $acc->login }}</span>
                                            <small class="text-uppercase text-muted" style="font-size: 8px;">{{ $acc->account_type ?? 'Standard' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info" style="font-size: 9px; padding: 2px 5px;">{{ $acc->platform }}</span></td>
                                <td class="text-dark" style="font-size: 12px;">{{ $acc->server }}</td>
                                <td>
                                    @php
                                        $meta = $acc->meta ?? [];
                                        $currency = $meta['currency'] ?? null;
                                        $lev = $meta['leverage'] ?? null;
                                    @endphp
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $currency ? "$currency" : '' }}{{ ($currency && $lev) ? ' | ' : '' }}{{ $lev ? "1:$lev" : '' }}
                                        {{ (!$currency && !$lev) ? '—' : '' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge-status {{ $acc->connected ? 'bg-success-light' : 'bg-danger-light' }}">
                                        {{ $acc->connected ? 'CONNECTED' : 'OFFLINE' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $acc->active ? 'bg-success-light' : 'bg-gray-light' }}" id="status-badge-{{ $acc->id }}">
                                        {{ $acc->active ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default editBtn" 
                                            data-id="{{ $acc->id }}" data-login="{{ $acc->login }}"
                                            data-platform="{{ $acc->platform }}" data-server="{{ $acc->server }}"
                                            data-type="{{ $acc->account_type }}" data-currency="{{ $meta['currency'] ?? '' }}"
                                            data-leverage="{{ $meta['leverage'] ?? '' }}" data-toggle="modal" data-target="#editAccountModal">
                                            <i class="fa fa-pencil text-primary"></i>
                                        </button>
                                        <button class="btn btn-xs btn-default toggleBtn" data-id="{{ $acc->id }}">
                                            <i class="fa fa-power-off text-warning"></i>
                                        </button>
                                        <form method="POST" action="{{ route('user.accounts.destroy', $acc->id) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs btn-default" onclick="return confirm('Disconnect account?')">
                                                <i class="fa fa-trash text-danger"></i>
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
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#accountsTable').DataTable({
        "pageLength": 10,
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Filter...",
            "paginate": {
                "previous": "<i class='fa fa-angle-left'></i>",
                "next": "<i class='fa fa-angle-right'></i>"
            }
        },
        "order": [[ 1, 'asc' ]], // Default sort on Login, numbering is handled separately
        "columnDefs": [
            { "orderable": false, "targets": [0, 7] } // Disable sort for # and Actions
        ],
        "dom": '<"row px-3 pt-2"<"col-sm-6"l><"col-sm-6"f>>rt<"row px-3 pb-2"<"col-sm-5"i><"col-sm-7"p>>'
    });

    // Handle dynamic numbering
    table.on('order.dt search.dt', function () {
        let i = 1;
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();

    // Your existing Modal Fill and Toggle logic below...
    $('.editBtn').on('click', function() {
        const ds = this.dataset;
        $('#editPlatform').val(ds.platform || 'MT5');
        $('#editServer').val(ds.server || '');
        $('#editLogin').val(ds.login || '');
        $('#editType').val(ds.type || '');
        $('#editCurrency').val(ds.currency || '');
        $('#editLeverage').val(ds.leverage || '');
        $('#editForm').attr('action', "{{ url('user/accounts') }}/" + ds.id);
    });
});
</script>
@endpush