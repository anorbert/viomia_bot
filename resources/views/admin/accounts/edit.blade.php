@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Edit Trading Account</h2></div>

    <div class="x_content">
        <form method="POST" action="{{ route('admin.accounts.update', $account) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Account Number</label>
                <input class="form-control" name="account_number" value="{{ $account->account_number }}">
            </div>

            <div class="form-group">
                <label>Platform</label>
                <select class="form-control" name="platform">
                    <option value="mt4" {{ $account->platform == 'mt4' ? 'selected' : '' }}>MT4</option>
                    <option value="mt5" {{ $account->platform == 'mt5' ? 'selected' : '' }}>MT5</option>
                </select>
            </div>

            <div class="form-group">
                <label>Server</label>
                <input class="form-control" name="server" value="{{ $account->server }}">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" value="{{ $account->password }}">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="active" {{ $account->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $account->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
