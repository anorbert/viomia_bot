@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Edit User</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf @method('PUT')

                <div class="mb-2">
                    <label>Name</label>
                    <input class="form-control" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-2">
                    <label>Phone Number</label>
                    <input class="form-control" name="phone_number" value="{{ $user->phone_number }}" required>
                </div>

                <div class="mb-2">
                    <label>Email</label>
                    <input class="form-control" name="email" value="{{ $user->email }}">
                </div>

                <div class="mb-2">
                    <label>Role</label>
                    <select class="form-control" name="role_id" required>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ $user->role_id == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <div class="mb-2">
                    <label>New Password (optional)</label>
                    <input class="form-control" name="password" type="password">
                </div>

                <div class="mb-3">
                    <label>Confirm New Password</label>
                    <input class="form-control" name="password_confirmation" type="password">
                </div>

                <button class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
