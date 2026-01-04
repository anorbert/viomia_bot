@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Edit Bot</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.bots.update', $bot) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Bot Name</label>
                <input class="form-control" name="name" value="{{ $bot->name }}">
            </div>

            <div class="form-group">
                <label>Version</label>
                <input class="form-control" name="version" value="{{ $bot->version }}">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description">{{ $bot->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="active" {{ $bot->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $bot->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
