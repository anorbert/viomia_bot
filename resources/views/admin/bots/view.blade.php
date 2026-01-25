<div class="p-2">
    <table class="table table-bordered mb-0">
        <tr>
            <th style="width:160px;">ID</th>
            <td>#{{ $bot->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $bot->name }}</td>
        </tr>
        <tr>
            <th>Version</th>
            <td><span class="badge bg-light text-dark">{{ $bot->version }}</span></td>
        </tr>
        <tr>
            <th>Address / Downloads</th>
            <td>{{ $bot->address ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge {{ $bot->status === 'Active' ? 'bg-success' : 'bg-danger' }}">
                    {{ $bot->status }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $bot->description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Created</th>
            <td>{{ $bot->created_at }}</td>
        </tr>
        <tr>
            <th>Updated</th>
            <td>{{ $bot->updated_at }}</td>
        </tr>
    </table>
</div>
