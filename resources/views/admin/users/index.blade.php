@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold">Admin Users</h4>
            <div class="text-muted small">Manage system users and their roles.</div>
        </div>
        {{-- UPDATED: GREEN GRADIENT BUTTON --}}
        <button class="btn text-white btn-sm shadow-sm border-0" 
                style="background: linear-gradient(45deg, #1e7e34, #28a745); font-weight: 500;" 
                data-toggle="modal" data-target="#createUserModal">
            <i class="fa fa-plus-circle mr-1"></i> Add New User
        </button>
    </div>

    <div class="card shadow-sm border-0">
    <div class="card-body p-3">
        <div class="table-responsive">
            {{-- Professional Table Styling --}}
            <table class="table table-hover align-middle mb-0" id="usersTable" style="width:100%">
                <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <tr>
                        <th class="text-uppercase small font-weight-bold text-secondary px-3" style="width: 50px;">ID</th>
                        <th class="text-uppercase small font-weight-bold text-secondary">User Information</th>
                        <th class="text-uppercase small font-weight-bold text-secondary">Contact</th>
                        <th class="text-uppercase small font-weight-bold text-secondary">Email Address</th>
                        <th class="text-uppercase small font-weight-bold text-secondary">Access Level</th>
                        <th class="text-uppercase small font-weight-bold text-secondary text-right px-3">Management</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($users as $u)
                    <tr style="transition: all 0.2s;">
                        <td class="px-3 text-muted font-weight-bold">#{{ $u->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    @if($u->profile_photo)
                                        <img src="{{ asset('storage/' . $u->profile_photo) }}" 
                                             class="rounded-circle mr-3 shadow-sm border" 
                                             style="width:40px; height:40px; object-fit:cover;">
                                    @else
                                        <img src="{{ asset('img/bot_logo.png') }}" 
                                             class="rounded-circle mr-3 border" 
                                             style="width:40px; height:40px;">
                                    @endif
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $u->name }}</div>
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">Created: {{ $u->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark"><i class="fa fa-phone-square mr-1 text-muted"></i> {{ $u->phone_number }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $u->email ?? 'N/A' }}</span>
                        </td>
                        <td>
                            {{-- Professional Status Badge --}}
                            <span class="badge badge-pill badge-light border text-info px-3 py-2" style="font-size: 0.75rem;">
                                <i class="fa fa-shield mr-1"></i> {{ $u->role->name ?? 'User' }}
                            </span>
                        </td>
                        <td class="text-right px-3">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.users.edit', $u) }}" 
                                   class="btn btn-sm btn-outline-warning mr-2 border-0 shadow-sm" 
                                   style="background-color: #fff9e6;" title="Edit Profile">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger border-0 shadow-sm" 
                                            style="background-color: #fff5f5;"
                                            onclick="return confirm('Are you sure you want to permanently delete this user?')" 
                                            title="Remove User">
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

{{-- Create User Modal --}}
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('admin.users.store') }}" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow">
                {{-- UPDATED: GREEN GRADIENT HEADER --}}
                <div class="modal-header text-white" style="background: linear-gradient(45deg, #1e7e34, #2bb14e) !important;">
                    <h5 class="modal-title font-weight-bold" id="createUserLabel">
                        <i class="fa fa-user-plus mr-2"></i>Add New User
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body py-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Name</label>
                            <input class="form-control form-control-sm" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Phone Number</label>
                            <input class="form-control form-control-sm" name="phone_number" placeholder="e.g. 012345678" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="small font-weight-bold">Email (optional)</label>
                            <input class="form-control form-control-sm" name="email" type="email" placeholder="email@example.com">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="small font-weight-bold">Role</label>
                            <select class="form-control form-control-sm" name="role_id" required>
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Password</label>
                            <input class="form-control form-control-sm" name="password" type="password" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Confirm Password</label>
                            <input class="form-control form-control-sm" name="password_confirmation" type="password" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    {{-- UPDATED: Red Gradient for Cancel --}}
                    <button class="btn text-white btn-sm px-3 shadow-sm border-0" 
                            data-dismiss="modal" 
                            type="button" 
                            style="background: linear-gradient(45deg, #dc3545, #b02a37);">
                        Cancel
                    </button>
                    
                    {{-- STAYS GREEN: Save Button --}}
                    <button class="btn text-white btn-sm px-4 shadow-sm border-0" 
                            type="submit" 
                            style="background: linear-gradient(45deg, #28a745, #218838);">
                        Save User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "order": [[ 0, "desc" ]],
            "columnDefs": [ { "orderable": false, "targets": 5 } ],
            "language": {
                "search": "",
                "searchPlaceholder": "Search users...",
                "lengthMenu": "_MENU_ per page",
                "paginate": { "previous": "Prev", "next": "Next" }
            }
        });

        $('.dataTables_filter input').addClass('form-control form-control-sm d-inline-block').css('width', 'auto');
        $('.dataTables_length select').addClass('form-control form-control-sm d-inline-block').css('width', 'auto');
    });
</script>
@endpush