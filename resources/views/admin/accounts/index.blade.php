@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Refined Professional Alerts --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center py-3" role="alert">
            <i class="fa fa-check-circle fa-lg mr-3"></i>
            <div><strong>Operation Successful:</strong> {{ session('success') }}</div>
            <button type="button" class="close ml-auto" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold text-dark">Trading Accounts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="#" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accounts List</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.accounts.create') }}" class="btn text-white shadow-sm border-0" 
           style="background: linear-gradient(45deg, #1e7e34, #28a745); font-weight: 600; border-radius: 8px; padding: 10px 20px;">
            <i class="fa fa-plus-circle mr-2"></i> Add New Account
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-0"> {{-- Removed padding for edge-to-edge header --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="accountsTable" style="width:100%">
                    <thead style="background-color: #fcfcfc; border-bottom: 2px solid #f1f1f1;">
                        <tr>
                            <th class="text-uppercase small font-weight-bold text-muted px-4 py-3" style="width: 80px;">S/N</th>
                            <th class="text-uppercase small font-weight-bold text-muted">Account & Owner</th>
                            <th class="text-uppercase small font-weight-bold text-muted text-center">Type</th>
                            <th class="text-uppercase small font-weight-bold text-muted">Platform Details</th>
                            <th class="text-uppercase small font-weight-bold text-muted text-center">Status</th>
                            <th class="text-uppercase small font-weight-bold text-muted text-right">Current Balance</th>
                            <th class="text-uppercase small font-weight-bold text-muted text-right px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($accounts as $key => $acc)
                        <tr style="transition: background 0.3s ease;">
                            {{-- Professional Numbering --}}
                            <td class="px-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-light text-muted font-weight-bold" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; border: 1px solid #eee;">
                                    {{ $key + 1 }}
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-soft-success mr-3 d-flex align-items-center justify-content-center rounded" 
                                         style="width: 40px; height: 40px; background-color: #e8f5e9;">
                                        <i class="fa fa-university text-success"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 1rem;">{{ $acc->login }}</div>
                                        <div class="text-muted small"><i class="fa fa-user-o mr-1"></i> {{ $acc->User->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($acc->account_type == 'Demo')
                                    <span class="badge badge-pill border-info text-info px-3 py-1" 
                                          style="background-color: #f0faff; font-size: 10px; border: 1px solid !important;">
                                        <i class="fa fa-flask mr-1"></i> DEMO
                                    </span>
                                @else
                                    <span class="badge badge-pill border-warning text-warning px-3 py-1" 
                                          style="background-color: #fffdf0; font-size: 10px; border: 1px solid !important; color: #856404 !important;">
                                        <i class="fa fa-shield mr-1"></i> REAL
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-windows text-info mr-2" title="Platform Type"></i>
                                    <div>
                                        <div class="text-dark font-weight-bold" style="font-size: 0.85rem;">{{ strtoupper($acc->platform) }}</div>
                                        <div class="text-muted extra-small" style="font-size: 0.7rem;">
                                            <i class="fa fa-server mr-1"></i> {{ $acc->server }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($acc->active)
                                    <span class="badge badge-pill border-success text-success px-3 py-2" 
                                          style="background-color: #f1fbf3; font-size: 0.65rem; border: 1px solid;">
                                        <i class="fa fa-circle mr-1 pulse-green"></i> LIVE
                                    </span>
                                @else
                                    <span class="badge badge-pill border-secondary text-secondary px-3 py-2" 
                                          style="background-color: #f8f9fa; font-size: 0.65rem; border: 1px solid;">
                                        <i class="fa fa-circle mr-1 text-muted"></i> INACTIVE
                                    </span>
                                @endif
                            </td>

                            <td class="text-right">
                                <div class="d-inline-block text-right">
                                    <div class="text-dark font-weight-bold" style="font-size: 1.1rem;">
                                        <small class="text-muted mr-1">$</small>{{ number_format($acc->snapshots->balance ?? 0, 2) }}
                                    </div>
                                    <div class="extra-small text-success" style="font-size: 0.7rem; margin-top: -5px;">
                                        <i class="fa fa-briefcase"></i> Trading Funds
                                    </div>
                                </div>
                            </td>

                            <td class="text-right px-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.accounts.edit', $acc) }}" 
                                       class="btn btn-sm btn-white text-warning border shadow-sm mr-2" 
                                       style="border-radius: 6px;" title="Modify Account">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.accounts.destroy', $acc) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-white text-danger border shadow-sm" 
                                                style="border-radius: 6px;"
                                                onclick="return confirm('Archive this account?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Professional Status Pulse */
    .pulse-green {
        color: #28a745;
        animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
        0% { opacity: 1; }
        50% { opacity: 0.3; }
        100% { opacity: 1; }
    }
    .bg-soft-success { background-color: rgba(40, 167, 69, 0.1); }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#accountsTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "dom": '<"d-flex justify-content-between align-items-center p-3"f l>rt<"d-flex justify-content-between align-items-center p-3"i p>',
            "language": {
                "search": "",
                "searchPlaceholder": "Search accounts or owners...",
            }
        });
        
        // Refine DataTables Inputs
        $('.dataTables_filter input').addClass('form-control border-0 shadow-sm').css({
            'background': '#f1f3f5',
            'border-radius': '8px',
            'width': '250px'
        });
    });
</script>
@endpush