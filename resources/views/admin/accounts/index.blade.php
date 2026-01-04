@extends('layouts.admin')

@section('content')

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success:</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Validation Issues:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif


<div class="x_panel">
    <div class="x_title d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Trading Accounts</h2>
        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Account
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        @if($accounts->count() === 0)
            <div class="alert alert-info text-center">
                No trading accounts found.  
                <a href="{{ route('admin.accounts.create') }}">Create your first account</a>.
            </div>
        @else

        <table class="table table-hover table-striped table-bordered">
            <thead class="bg-dark text-white">
            <tr>
                <th>Client</th>
                <th>Account Number</th>
                <th>Platform</th>
                <th>Server</th>
                <th>Status</th>
                <th>Balance</th>
                <th style="width: 140px;">Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach($accounts as $acc)
                <tr>
                    <td>
                        <strong>{{ $acc->User->name }}</strong><br>
                        <small class="text-muted">{{ $acc->User->email }}</small>
                    </td>

                    <td>
                        <span class="badge badge-secondary" style="font-size:14px">
                            {{ $acc->login }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-info">
                            {{ strtoupper($acc->platform) }}
                        </span>
                    </td>

                    <td>{{ $acc->server }}</td>

                    <td>
                        <span class="badge badge-{{ $acc->active ? 'success' : 'danger' }}">
                            {{ $acc->active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>


                    <td>
                        <strong>${{ number_format($acc->snapshots->balance, 2) }}</strong>
                    </td>

                    <td>
                        <form action="{{ route('admin.accounts.destroy', $acc) }}"
                              method="POST"
                              style="display:inline-block">
                            @csrf @method('DELETE')                            

                        <a href="{{ route('admin.accounts.edit', $acc) }}"
                           class="btn btn-warning btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this account?')"
                                    title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif

    </div>
</div>

@endsection
