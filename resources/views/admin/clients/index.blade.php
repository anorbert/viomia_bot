@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 font-weight-bold">Client Management</h4>
            <div class="text-muted small">Monitor client bot activity and account status.</div>
        </div>
        {{-- Professional Green Gradient Button --}}
        <a href="{{ route('admin.clients.create') }}" class="btn text-white btn-sm shadow-sm border-0" 
           style="background: linear-gradient(45deg, #1e7e34, #28a745); font-weight: 500; padding: 8px 16px;">
            <i class="fa fa-plus-circle mr-1"></i> Add Client
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="clientsTable" style="width:100%">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <tr>
                            <th class="text-uppercase small font-weight-bold text-secondary px-3" style="width: 50px;">S/N</th>
                            <th class="text-uppercase small font-weight-bold text-secondary">Client Detail</th>
                            <th class="text-uppercase small font-weight-bold text-secondary text-center">Bots</th>
                            <th class="text-uppercase small font-weight-bold text-secondary text-center">Accounts</th>
                            <th class="text-uppercase small font-weight-bold text-secondary text-center">Status</th>
                            <th class="text-uppercase small font-weight-bold text-secondary text-right px-3">Management</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($clients as $key => $client)
                        <tr>
                            <td class="px-3 text-muted font-weight-bold">{{ $key + 1 }}</td>
                            <td>
                                {{-- PROFESSIONAL AVATAR SECTION --}}
                                <div class="d-flex align-items-center">
                                    <div class="position-relative d-inline-block mr-3">
                                        @if($client->profile_photo)
                                            <img src="{{ asset('storage/' . $client->profile_photo) }}" 
                                                 class="rounded-circle shadow-sm border border-white" 
                                                 style="width:42px; height:42px; object-fit:cover; border-width: 2px !important;">
                                        @else
                                            <div class="rounded-circle shadow-sm border border-white d-flex align-items-center justify-content-center bg-light" 
                                                 style="width:42px; height:42px; border-width: 2px !important;">
                                                 <img src="{{ asset('img/bot_logo.png') }}" style="width:24px; opacity: 0.7;">
                                            </div>
                                        @endif

                                        {{-- Activity Indicator --}}
                                        @if(strtolower($client->status) == 'active')
                                            <span class="position-absolute border border-white rounded-circle bg-success" 
                                                  style="width: 12px; height: 12px; bottom: 2px; right: 2px; border-width: 2px !important;" 
                                                  title="Active"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="letter-spacing: -0.2px; font-size: 0.95rem;">
                                            {{ $client->name }}
                                        </div>
                                        <div class="text-muted d-flex align-items-center" style="font-size: 0.75rem;">
                                            <i class="fa fa-envelope-o mr-1"></i> {{ $client->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-light border text-primary px-3 py-2" style="font-size: 0.75rem;">
                                    <i class="fa fa-android mr-1"></i> {{ $client->bots_count ?? 'Viomia' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="font-weight-bold text-dark">{{ $client->accounts_count }}</div>
                                <div class="extra-small text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Linked Accounts</div>
                            </td>
                            <td class="text-center">
                                @if(strtolower($client->status) == 'active')
                                    <span class="badge badge-success px-2 py-1 shadow-sm" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Active</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1" style="font-size: 10px; text-transform: uppercase;">{{ $client->status }}</span>
                                @endif
                            </td>
                            <td class="text-right px-3">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                       class="btn btn-sm btn-outline-warning mr-2 border-0 shadow-sm" 
                                       style="background-color: #fff9e6;" title="Edit Client">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger border-0 shadow-sm" 
                                                style="background-color: #fff5f5;"
                                                onclick="return confirm('Delete this client and all associated data?')" 
                                                title="Remove Client">
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#clientsTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "columnDefs": [ { "orderable": false, "targets": 5 } ],
            "language": {
                "search": "",
                "searchPlaceholder": "Filter clients...",
                "lengthMenu": "_MENU_ per page"
            }
        });

        // DataTable Input Styling
        $('.dataTables_filter input').addClass('form-control form-control-sm border-0 shadow-sm').css({
            'background': '#f8f9fa',
            'padding-left': '15px',
            'border-radius': '20px'
        });
        $('.dataTables_length select').addClass('form-control form-control-sm');
    });
</script>
@endpush