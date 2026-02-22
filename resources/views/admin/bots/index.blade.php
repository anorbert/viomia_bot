@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold">Bots Management</h4>
            <div class="text-muted small">
                Manage trading bots, versions and health status.
            </div>
        </div>

        {{-- GREEN GRADIENT BUTTON --}}
        <button class="btn text-white btn-sm shadow-sm border-0"
                style="background: linear-gradient(45deg, #1e7e34, #28a745); font-weight: 500;"
                data-toggle="modal"
                data-target="#createBotModal">
            <i class="fa fa-plus-circle mr-1"></i> Add New Bot
        </button>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0"
                       id="botsTable"
                       style="width:100%">

                    <thead style="background-color:#f8f9fa; border-bottom:2px solid #dee2e6;">
                        <tr>
                            <th class="text-uppercase small font-weight-bold text-secondary px-3" style="width:60px;">ID</th>
                            <th class="text-uppercase small font-weight-bold text-secondary">Bot Information</th>
                            <th class="text-uppercase small font-weight-bold text-secondary">Version</th>
                            <th class="text-uppercase small font-weight-bold text-secondary">Address</th>
                            <th class="text-uppercase small font-weight-bold text-secondary">Status</th>
                            <th class="text-uppercase small font-weight-bold text-secondary text-right px-3">Management</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach($bots as $bot)
                        <tr style="transition: all 0.2s;">
                            <td class="px-3 text-muted font-weight-bold">#{{ $bot->id }}</td>

                            <td>
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                        <i class="fa fa-robot text-success mr-1"></i>{{ $bot->name }}
                                    </div>
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">
                                        Updated: {{ $bot->updated_at?->format('M d, Y') ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge badge-pill badge-light border px-3 py-2" style="font-size:0.75rem;">
                                    <i class="fa fa-code-branch mr-1"></i>{{ $bot->version }}
                                </span>
                            </td>

                            <td>
                                <span class="text-muted">
                                    <i class="fa fa-map-marker-alt mr-1"></i>{{ $bot->address ?? 'Local' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-pill {{ $bot->status == 'Active' ? 'badge-success' : 'badge-danger' }} px-3 py-2" style="font-size:0.75rem;">
                                    <i class="fa fa-circle mr-1" style="font-size:8px;"></i> {{ $bot->status }}
                                </span>
                            </td>

                            <td class="text-right px-3">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.bots.edit', $bot) }}" class="btn btn-sm btn-outline-warning mr-2 border-0 shadow-sm" style="background-color:#fff9e6;" title="Edit Bot">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('admin.bots.settings', $bot) }}" class="btn btn-sm btn-outline-info border-0 shadow-sm" style="background-color:#eef8ff;" title="Settings">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>


{{-- ================= CREATE BOT MODAL ================= --}}
<div class="modal fade" id="createBotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.bots.store') }}" class="w-100">
            @csrf

            <div class="modal-content border-0 shadow">

                {{-- GREEN GRADIENT HEADER --}}
                <div class="modal-header text-white"
                     style="background: linear-gradient(45deg,#1e7e34,#2bb14e) !important;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-robot mr-2"></i>Add New Bot
                    </h5>
                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body py-3">
                    <div class="row">

                        {{-- Bot Name --}}
                        <div class="col-md-12 mb-2">
                            <label class="small font-weight-bold">Bot Name</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fa fa-robot text-success"></i>
                                    </span>
                                </div>
                                <input class="form-control border-left-0" name="name" placeholder="Enter bot name" required>
                            </div>
                        </div>

                        {{-- Version --}}
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Version</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fa fa-code-branch text-primary"></i>
                                    </span>
                                </div>
                                <input class="form-control border-left-0" name="version" placeholder="1.0.0" required>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Status</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fa fa-circle text-success"></i>
                                    </span>
                                </div>
                                <select class="form-control border-left-0" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12 mb-2">
                            <label class="small font-weight-bold">Address / Path</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fa fa-map-marker-alt text-danger"></i>
                                    </span>
                                </div>
                                <input class="form-control border-left-0" name="address" placeholder="Enter address or path">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button class="btn text-white btn-sm px-3 shadow-sm border-0"
                            type="button"
                            data-dismiss="modal"
                            style="background: linear-gradient(45deg,#dc3545,#b02a37);">
                        Cancel
                    </button>

                    <button class="btn text-white btn-sm px-4 shadow-sm border-0"
                            type="submit"
                            style="background: linear-gradient(45deg,#28a745,#218838);">
                        Save Bot
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function() {

    $('#botsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, "desc"]],
        columnDefs: [{ orderable: false, targets: 5 }],
        language: {
            search: "",
            searchPlaceholder: "Search bots...",
            lengthMenu: "_MENU_ per page",
            paginate: { previous: "Prev", next: "Next" }
        }
    });

    $('.dataTables_filter input')
        .addClass('form-control form-control-sm d-inline-block')
        .css('width','auto');

    $('.dataTables_length select')
        .addClass('form-control form-control-sm d-inline-block')
        .css('width','auto');

});
</script>
@endpush