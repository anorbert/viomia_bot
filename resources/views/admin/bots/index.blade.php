@extends('layouts.admin')

@section('content')
<div class="container py-4">

    {{-- Page Header (NO d-flex) --}}
    <div class="row mb-3">
        <div class="col-md-8 col-12">
            <h2 class="mb-1">Bots Management</h2>
            <div class="text-muted small">
                Create, update, and monitor EA bots. Status updates every 30 seconds.
            </div>
        </div>
        <div class="col-md-4 col-12 text-md-end mt-3 mt-md-0">
            {{-- Bootstrap 3/4 compatible modal trigger --}}
            <button class="btn btn-success" data-toggle="modal" data-target="#createBotModal">
                <i class="fa fa-plus"></i> Create Bot
            </button>
        </div>
    </div>

    {{-- Controls --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" id="tableSearch" class="form-control"
                               placeholder="Search bots by name, version, address...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-light text-dark me-1">
                        Total: {{ count($bots) }}
                    </span>
                    <span class="badge bg-success me-1">Active</span>
                    <span class="badge bg-danger">Inactive</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="bots-table" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:80px">ID</th>
                            <th>Name</th>
                            <th style="width:140px">Version</th>
                            <th>Downloads / Address</th>
                            <th style="width:140px">Status</th>
                            <th style="width:180px" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bots as $bot)
                        <tr id="bot-row-{{ $bot->id }}">
                            <td class="fw-bold" data-order="{{ $bot->id }}">#{{ $bot->id }}</td>

                            <td>
                                <div class="fw-semibold">{{ $bot->name }}</div>
                                <div class="text-muted small">
                                    Updated: {{ $bot->updated_at?->diffForHumans() ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">{{ $bot->version }}</span>
                            </td>

                            <td>
                                <span class="text-muted">{{ $bot->address ?? '-' }}</span>
                            </td>

                            <td class="bot-status-cell">
                                <span class="badge {{ $bot->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $bot->status == 'Active' ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="text-muted small bot-lastping">Last ping: —</div>
                            </td>

                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-sm btn-outline-primary view-bot" title="Details" data-id="${bot.id}">
                                    <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-warning edit-bot" title="Edit" data-id="${bot.id}">
                                    <i class="fa fa-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.bots.settings', $bot->id) }}" title="Settings" class="btn btn-sm btn-outline-info" data-id="{{ $bot->id }}">
                                        <i class="fa fa-gear"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-muted small mt-2">
                Tip: If status looks wrong, verify your endpoint <code>/admin/bots/status/{id}</code> returns
                <code>is_healthy</code> and <code>last_ping</code>.
            </div>
        </div>
    </div>

</div>

{{-- =========================
     Create Bot Modal
========================== --}}
<div class="modal fade" id="createBotModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Create New Bot</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createBotForm" method="POST" action="{{ route('admin.bots.store') }}">
                <div class="modal-body">
                    @csrf

                    <div class="form-group">
                        <label>Bot Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Viomia Gold Bot" required>
                        <small class="text-muted">Use a unique, recognizable name.</small>
                    </div>

                    <div class="form-group">
                        <label>Version</label>
                        <input type="text" name="version" class="form-control" placeholder="e.g. v1.0.3" required>
                    </div>

                    <div class="form-group">
                        <label>Address / Downloads</label>
                        <input type="text" name="address" class="form-control" placeholder="Link or local path">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"
                                  placeholder="Short notes about this bot..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Create Bot
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- =========================
     Shared AJAX Modal
     (loads view/edit partial blades)
========================== --}}
<div class="modal fade" id="ajaxBotModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="ajaxBotModalTitle">Loading...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="ajaxBotModalBody">
                <div class="text-muted">Loading...</div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

    // DataTable
    var table = $('#bots-table').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 10,
        lengthChange: false,
        info: true,
        searching: true
    });

    // External search
    $('#tableSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Poll status every 30s
    setInterval(() => {
        $('#bots-table tbody tr').each(function(){
            let botId = $(this).attr('id').split('-')[2];

            $.getJSON(`/admin/bots/status/${botId}`, function(data){
                let badgeClass = data.is_healthy ? 'bg-success' : 'bg-danger';
                let badgeText  = data.is_healthy ? 'Active' : 'Inactive';

                let $row = $(`#bot-row-${botId}`);
                $row.find('.bot-status-cell').find('span.badge')
                    .attr('class', `badge ${badgeClass}`)
                    .text(badgeText);

                $row.find('.bot-lastping').text(
                    data.last_ping ? `Last ping: ${data.last_ping}` : 'Last ping: —'
                );
            });
        });
    }, 30000);

    // Create bot via AJAX
    $(document).on('submit', '#createBotForm', function(e){
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: $(this).serialize(),
            success: function(bot){

                $('#createBotModal').modal('hide');
                $('#createBotForm')[0].reset();

                let badgeClass = (bot.status === 'Active') ? 'bg-success' : 'bg-danger';

                let newRow = `
                    <tr id="bot-row-${bot.id}">
                        <td class="fw-bold" data-order="${bot.id}">#${bot.id}</td>
                        <td>
                            <div class="fw-semibold">${bot.name}</div>
                            <div class="text-muted small">Updated: just now</div>
                        </td>
                        <td><span class="badge bg-light text-dark">${bot.version}</span></td>
                        <td><span class="text-muted">${bot.address ?? '-'}</span></td>
                        <td class="bot-status-cell">
                            <span class="badge ${badgeClass}">${bot.status}</span>
                            <div class="text-muted small bot-lastping">Last ping: —</div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary view-bot" data-id="${bot.id}">
                                    <i class="fa fa-eye"></i> View
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning edit-bot" data-id="${bot.id}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                table.row.add($(newRow)).draw(false);
            },
            error: function(xhr){
                let msg = 'Failed to create bot. Check input and server logs.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    });

    // View bot
    $(document).on('click', '.view-bot', function (e) {
        e.preventDefault(); // ✅ stops refresh for <a href="">
        e.stopPropagation(); // ✅ prevents DataTables row click interference

        let id = $(this).data('id');

        $('#ajaxBotModalTitle').text('Bot Details');
        $('#ajaxBotModalBody').html('<div class="text-muted"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
        $('#ajaxBotModal').modal('show');

        $.ajax({
            url: `{{ url('admin/bots') }}/${id}`,
            method: 'GET',
            cache: false,
            success: function (html) {
                if (!html || html.trim() === '') {
                    $('#ajaxBotModalBody').html('<div class="alert alert-warning">No content returned.</div>');
                    return;
                }
                $('#ajaxBotModalBody').html(html);
            },
            error: function (xhr) {
                console.error('VIEW ERROR', xhr);
                $('#ajaxBotModalBody').html(
                    `<div class="alert alert-danger">
                        Failed to load details (HTTP ${xhr.status}).
                    </div>`
                );
            }
        });
    });

    // Edit bot
    $(document).on('click', '.edit-bot', function (e) {
        e.preventDefault(); // ✅ stops refresh
        e.stopPropagation();

        let id = $(this).data('id');

        $('#ajaxBotModalTitle').text('Edit Bot');
        $('#ajaxBotModalBody').html('<div class="text-muted"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
        $('#ajaxBotModal').modal('show');

        $.ajax({
            url: `{{ url('admin/bots') }}/${id}/edit`,
            method: 'GET',
            cache: false,
            success: function (html) {
                if (!html || html.trim() === '') {
                    $('#ajaxBotModalBody').html('<div class="alert alert-warning">No edit form returned.</div>');
                    return;
                }
                $('#ajaxBotModalBody').html(html);
            },
            error: function (xhr) {
                console.error('EDIT ERROR', xhr);
                $('#ajaxBotModalBody').html(
                    `<div class="alert alert-danger">
                        Failed to load edit form (HTTP ${xhr.status}).
                    </div>`
                );
            }
        });
    });

    // Submit edit form via AJAX (form lives inside partial)
    $(document).on('submit', '#editBotForm', function (e) {
        e.preventDefault();

        let $form = $(this);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST', // Laravel reads PUT from _method
            data: $form.serialize(),
            success: function (bot) {

                let badgeClass = bot.status === 'Active' ? 'bg-success' : 'bg-danger';
                let badgeText  = bot.status === 'Active' ? 'Active' : 'Inactive';

                let $row = $(`#bot-row-${bot.id}`);
                $row.find('td:nth-child(2) .fw-semibold').text(bot.name);
                $row.find('td:nth-child(3) .badge').text(bot.version);
                $row.find('td:nth-child(4) .text-muted').text(bot.address ?? '-');
                $row.find('.bot-status-cell span.badge').attr('class', `badge ${badgeClass}`).text(badgeText);

                // refresh DataTables cache for that row
                table.row($row).invalidate().draw(false);

                $('#ajaxBotModal').modal('hide');
            },
            error: function (xhr) {
                let msg = 'Update failed.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                // edit partial must include this element
                $('#edit-error').show().html(msg);
            }
        });
    });

});
</script>
@endsection
