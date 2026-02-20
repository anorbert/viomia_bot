@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold">Admin Users</h4>
            <div class="text-muted small">Manage system users and their roles.</div>
        </div>
        <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#createUserModal">
            <i class="fa fa-plus-circle mr-1"></i> Add New User
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3" style="width: 50px;">#</th>
                            <th>User</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-right px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            {{-- We can keep showing ID for internal reference, or change to $loop->iteration --}}
                            <td class="px-3 text-muted">{{ $u->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($u->profile_photo)
                                        <img src="{{ asset('storage/' . $u->profile_photo) }}" 
                                             class="rounded-circle mr-2 shadow-sm" 
                                             style="width:35px; height:35px; object-fit:cover;">
                                    @else
                                        <img src="{{ asset('img/bot_logo.png') }}" 
                                             class="rounded-circle mr-2 border" 
                                             style="width:35px; height:35px;">
                                    @endif
                                    <span class="font-weight-bold">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td>{{ $u->phone_number }}</td>
                            <td class="text-muted">{{ $u->email ?? '-' }}</td>
                            <td>
                                <span class="badge badge-info px-2 py-1">{{ $u->role->name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-right px-3">
                                <div class="btn-group shadow-sm">
                                    {{-- UPDATED: Passing the whole object $u tells Laravel to use the UUID --}}
                                    <a class="btn btn-sm btn-white text-warning border" href="{{ route('admin.users.edit', $u) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    {{-- UPDATED: Passing the object $u for the destroy route --}}
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-white text-danger border" onclick="return confirm('Delete this user?')" title="Delete">
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

{{-- Create User Modal remains the same as it uses the POST store route --}}
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('admin.users.store') }}" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createUserLabel"><i class="fa fa-user-plus mr-2"></i>Add New User</h5>
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
                    <button class="btn btn-secondary btn-sm px-3" data-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-success btn-sm px-4 shadow-sm" type="submit">Save User</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection