@extends('layouts.admin')

@section('content')
<div class="x_panel">
    <div class="x_title"><h2>Error Logs</h2></div>

    <div class="x_content">
        <ul class="list-group">
            @foreach($errors as $err)
                <li class="list-group-item">
                    <strong>{{ $err->created_at }}:</strong>
                    {{ $err->message }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
