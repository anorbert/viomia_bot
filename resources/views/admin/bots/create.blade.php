@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Add Bot</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.bots.store') }}">
            @csrf

            <div class="form-group">
                <label>Bot Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            <div class="form-group">
                <label>Version</label>
                <input type="text" class="form-control" name="version">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="file" class="form-control" name="address">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>

            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection
