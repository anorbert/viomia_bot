@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title">
        <h2>Clients</h2>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary pull-right">Add Client</a>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <table class="table table-striped table-bordered ">
            <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Bots</th>
                <th>Accounts</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $key=> $client)
                <tr>
                    <td>{{ $key+1}}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->bots_count?? 'Viomia' }}</td>
                    <td>{{ $client->accounts_count }}</td>
                    <td>{{ $client->status }}</td>
                    <td>                        
                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
