@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center mb-3">
        <h4 class="mb-0"><i class="fa fa-user-edit mr-2"></i>Edit User Profile</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2">
            <h6 class="mb-0 text-primary font-weight-bold">Account Details: {{ $user->name }}</h6>
        </div>

        <div class="card-body pt-3">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-3 text-center border-right">
                        <div class="mb-2">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle img-thumbnail" style="width:110px; height:110px; object-fit:cover;">
                            @else
                                <img src="{{ asset('img/bot_logo.png') }}" class="rounded-circle img-thumbnail" style="width:110px; height:110px;">
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">Update Photo</label>
                            <input type="file" class="form-control form-control-sm" name="profile_photo" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Full Name</label>
                                <input class="form-control form-control-sm" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Phone Number</label>
                                <input class="form-control form-control-sm" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Email</label>
                                <input class="form-control form-control-sm" type="email" name="email" value="{{ old('email', $user->email) }}">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">System Role</label>
                                <select class="form-control form-control-sm" name="role_id" required>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ $user->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-danger mb-1 font-weight-bold">Security Update</h6>
                                <p class="small text-muted mb-2">Leave blank to keep current password</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">New Password</label>
                                <input class="form-control form-control-sm" type="password" name="password">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Confirm Password</label>
                                <input class="form-control form-control-sm" type="password" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white px-0 pb-0 pt-3 mt-3 border-top d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm px-4 mr-2"><i class="fa fa-save mr-1"></i> Save Changes</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection