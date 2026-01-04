@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title d-flex justify-content-between align-items-center">
        <h2><i class="fa fa-bolt"></i> Trading Signals</h2>
        <a href="{{ route('admin.signals.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Signal
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($signals->count() === 0)
            <div class="alert alert-info text-center">
                No signals found.  
                <a href="{{ route('admin.signals.create') }}">Create your first signal</a>.
            </div>
        @else

        <table class="table table-hover table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Symbol</th>
                    <th>Direction</th>
                    <th>Entry</th>
                    <th>SL</th>
                    <th>TP</th>
                    <th>Timeframe</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($signals as $signal)
                    <tr>
                        <td>{{ strtoupper($signal->symbol) }}</td>
                        <td>
                            <span class="badge {{ $signal->direction == 'buy' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($signal->direction) }}
                            </span>
                        </td>
                        <td>{{ $signal->entry }}</td>
                        <td>{{ $signal->sl }}</td>
                        <td>{{ $signal->tp }}</td>
                        <td>{{ $signal->timeframe ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $signal->active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $signal->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.signals.destroy', $signal) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                
                                <a href="{{ route('admin.signals.edit', $signal) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this signal?')">
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
