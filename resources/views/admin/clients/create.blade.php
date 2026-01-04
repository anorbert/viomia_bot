@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Add Client</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.clients.store') }}">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input class="form-control" name="name">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" type="password">
            </div>

            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection
