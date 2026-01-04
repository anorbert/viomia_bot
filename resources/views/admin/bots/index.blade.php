@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Bots Management</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createBotModal">
            + Create Bot
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover" id="bots-table">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Version</th>
                        <th>Downloads / Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bots as $bot)
                    <tr id="bot-row-{{ $bot->id }}">
                        <td>{{ $bot->id }}</td>
                        <td>{{ $bot->name }}</td>
                        <td>{{ $bot->version }}</td>
                        <td>{{ $bot->address ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $bot->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $bot->status }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary view-bot" data-id="{{ $bot->id }}">View</button>
                            <button class="btn btn-sm btn-warning edit-bot" data-id="{{ $bot->id }}">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Bot Modal -->
<div class="modal fade" id="createBotModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createBotForm">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Bot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Version</label>
                        <input type="text" name="version" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Address / Downloads</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create Bot</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    // Initialize DataTable
    var table = $('#bots-table').DataTable({
        responsive: true,
        order: [[0, 'desc']],
    });

    // Poll bot status every 30s
    setInterval(() => {
        $('#bots-table tbody tr').each(function(){
            let botId = $(this).attr('id').split('-')[2];
            $.getJSON(`/admin/bots/status/${botId}`, function(data){
                let statusBadge = data.is_healthy ? 'bg-success' : 'bg-danger';
                $(`#bot-row-${botId} td:nth-child(5)`).html(
                    `<span class="badge ${statusBadge}">${data.is_healthy ? 'Active' : 'Inactive'}</span>`
                );
            });
        });
    }, 30000);

    // Create bot via AJAX and append to table
    $('#createBotForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.bots.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                // Close modal
                $('#createBotModal').modal('hide');
                $('#createBotForm')[0].reset();

                // Add new bot row dynamically
                let bot = res; // Make sure controller returns created bot JSON
                let statusBadge = bot.status == 'Active' ? 'bg-success' : 'bg-secondary';
                let newRow = `
                    <tr id="bot-row-${bot.id}">
                        <td>${bot.id}</td>
                        <td>${bot.name}</td>
                        <td>${bot.version}</td>
                        <td>${bot.address ?? '-'}</td>
                        <td><span class="badge ${statusBadge}">${bot.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary view-bot" data-id="${bot.id}">View</button>
                            <button class="btn btn-sm btn-warning edit-bot" data-id="${bot.id}">Edit</button>
                        </td>
                    </tr>
                `;
                table.row.add($(newRow)).draw(false);
            },
            error: function(err){
                alert('Failed to create bot. Check input.');
            }
        });
    });
});
</script>
@endsection
