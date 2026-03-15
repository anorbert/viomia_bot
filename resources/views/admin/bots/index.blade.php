@extends('layouts.admin')

@section('title', 'Bots Management — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: 2.5px solid #3B9EFF !important; border-radius: 12px !important; padding: 18px 24px !important; margin-bottom: 20px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.35) !important; }
.vi-header-title { font-size: 18px !important; font-weight: 800 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 3px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 13px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-panel-body { padding: 18px !important; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 800 !important; font-size: 13px; }
.vi-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-block; }
.vi-badge-active { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-inactive { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #3B9EFF !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #2986d9 !important; box-shadow: 0 4px 14px rgba(59,158,255,0.32) !important; }
.vi-btn-settings { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-settings:hover { background-color: rgba(139,92,246,0.2) !important; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#3B9EFF; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🤖 Bot Management</div>
        <div class="vi-header-title">Trading Bots</div>
        <div class="vi-header-sub">Manage and monitor all trading bots and their performance</div>
    </div>
    <button class="vi-btn vi-btn-primary" data-toggle="modal" data-target="#createBotModal" style="margin-left:auto;">
        <i class="fa fa-plus-circle"></i> Add New Bot
    </button>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#3B9EFF; font-size:14px;"></i>
        <div class="vi-panel-title">Active Bots</div>
    </div>
    <div class="vi-panel-body">
        <!-- Data Table -->
        <div class="vi-table-container" style="overflow-x:auto;">
            <table class="vi-table" id="botsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bot Name</th>
                        <th>Version</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bots as $key => $bot)
                        <tr>
                            <td style="font-size:11px; color:#4b5563;">{{ $key + 1 }}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:36px; height:36px; border-radius:8px; background-color:rgba(59,158,255,0.15); display:flex; align-items:center; justify-content:center; color:#3B9EFF; font-weight:800;">
                                        <i class="fa fa-robot"></i>
                                    </div>
                                    <div>
                                        <div class="td-sym">{{ $bot->name }}</div>
                                        <div style="font-size:10px; color:#4b5563; margin-top:2px;">Updated: {{ $bot->updated_at?->format('M d, Y') ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code style="background-color:rgba(26,187,156,0.1); color:#1ABB9C; padding:2px 6px; border-radius:4px; font-size:10px;">{{ $bot->version }}</code>
                            </td>
                            <td style="font-size:11px;">{{ $bot->address ?? 'Local' }}</td>
                            <td>
                                @if($bot->status === 'Active')
                                    <span class="vi-badge vi-badge-active">ACTIVE</span>
                                @else
                                    <span class="vi-badge vi-badge-inactive">INACTIVE</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.bots.edit', $bot) }}" class="vi-btn vi-btn-primary">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="{{ route('admin.bots.settings', $bot) }}" class="vi-btn vi-btn-settings" style="margin-left:8px;">
                                    <i class="fa fa-cog"></i> Settings
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px;">No bots found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= CREATE BOT MODAL ================= --}}
<div class="modal fade" id="createBotModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('admin.bots.store') }}">
            @csrf

            <div class="modal-content" style="background-color:#111827; border:1px solid rgba(255,255,255,0.1); border-radius:12px;">

                <div class="modal-header" style="background-color:#1a2235; border-bottom:1px solid rgba(255,255,255,0.1); border-radius:12px 12px 0 0;">
                    <h5 class="modal-title" style="color:#f1f5f9; font-weight:800; font-size:14px;">
                        <i class="fa fa-robot" style="color:#3B9EFF; margin-right:8px;"></i>Add New Bot
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" style="color:#94a3b8;">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:18px;">

                    <div class="form-group">
                        <label style="color:#94a3b8; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Bot Name</label>
                        <input type="text" name="name" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.12); color:#f1f5f9; border-radius:8px; padding:10px 14px; font-size:12px;" placeholder="Enter bot name" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="color:#94a3b8; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Version</label>
                            <input type="text" name="version" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.12); color:#f1f5f9; border-radius:8px; padding:10px 14px; font-size:12px;" placeholder="1.0.0" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label style="color:#94a3b8; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Status</label>
                            <select name="status" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.12); color:#f1f5f9; border-radius:8px; padding:10px 14px; font-size:12px;">
                                <option value="Active" style="background-color:#111827;">Active</option>
                                <option value="Inactive" style="background-color:#111827;">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="color:#94a3b8; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Address / Path</label>
                        <input type="text" name="address" class="form-control" style="background-color:#1a2235; border:1px solid rgba(255,255,255,0.12); color:#f1f5f9; border-radius:8px; padding:10px 14px; font-size:12px;" placeholder="Enter address or path">
                    </div>

                </div>

                <div class="modal-footer" style="background-color:#1a2235; border-top:1px solid rgba(255,255,255,0.1); border-radius:0 0 12px 12px;">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color:rgba(239,68,68,0.13); color:#fca5a5; border:1px solid rgba(239,68,68,0.3); border-radius:6px; font-weight:700; padding:6px 14px;">
                        Cancel
                    </button>
                    <button type="submit" class="btn" style="background-color:#3B9EFF; color:#fff; border:none; border-radius:6px; font-weight:700; padding:6px 14px;">
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
        .addClass('form-control form-control-sm')
        .css({'width':'auto', 'background-color':'#1a2235', 'border':'1px solid rgba(255,255,255,0.12)', 'color':'#f1f5f9', 'border-radius':'8px'});

    $('.dataTables_length select')
        .addClass('form-control form-control-sm')
        .css({'width':'auto', 'background-color':'#1a2235', 'border':'1px solid rgba(255,255,255,0.12)', 'color':'#f1f5f9', 'border-radius':'8px'});
});
</script>
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