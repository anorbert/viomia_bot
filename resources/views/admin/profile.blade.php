@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>My Profile</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf

            <div class="form-group">
                <label>Name</label>
                <input class="form-control" name="name" value="{{ auth()->user()->name }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="email" value="{{ auth()->user()->email }}">
            </div>

            <div class="form-group">
                <label>New Password (optional)</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button class="btn btn-success">Update Profile</button>
        </form>
    </div>
</div>
@endsection
