@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>System Settings</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.settings.save') }}">
            @csrf

            <div class="form-group">
                <label>System Name</label>
                <input class="form-control" name="system_name" value="{{ $settings->system_name }}">
            </div>

            <div class="form-group">
                <label>Support Email</label>
                <input class="form-control" name="support_email" value="{{ $settings->support_email }}">
            </div>

            <div class="form-group">
                <label>Default Bot</label>
                <select class="form-control" name="default_bot">
                    @foreach($bots as $bot)
                        <option value="{{ $bot->id }}" {{ $settings->default_bot == $bot->id ? 'selected' : '' }}>
                            {{ $bot->name }} ({{ $bot->version }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
@endsection
