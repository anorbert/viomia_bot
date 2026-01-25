@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="mb-0">Admin Users</h4>
            <div class="text-muted small">Manage system users and their roles.</div>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal">
                <i class="fa fa-plus"></i> Add User
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->phone_number }}</td>
                            <td>{{ $u->email ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $u->role->name ?? 'N/A' }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-warning" href="{{ route('admin.users.edit', $u->id) }}">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">
                                            <i class="fa fa-trash"></i> Delete
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
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserLabel">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label>Name</label>
                    <input class="form-control" name="name" required>
                </div>

                <div class="mb-2">
                    <label>Phone Number</label>
                    <input class="form-control" name="phone_number" required>
                </div>

                <div class="mb-2">
                    <label>Email (optional)</label>
                    <input class="form-control" name="email" type="email">
                </div>

                <div class="mb-2">
                    <label>Role</label>
                    <select class="form-control" name="role_id" required>
                        <option value="">-- Select --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password" required>
                </div>

                <div class="mb-2">
                    <label>Confirm Password</label>
                    <input class="form-control" name="password_confirmation" type="password" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
