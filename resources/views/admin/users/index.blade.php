@extends('layouts.admin')

@section('title', 'Admin Users — ' . config('app.name'))

@push('styles')
<style>
    .right_col { background-color: #1e2a3a !important; padding: 20px 24px !important; min-height: 100vh; }
    .usr-header { display: flex !important; align-items: center !important; justify-content: space-between !important;
        flex-wrap: wrap; gap: 12px; background-color: #253347 !important; border: 1px solid rgba(255,255,255,0.13) !important;
        border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 16px 22px !important;
        margin-bottom: 18px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important; }
    .usr-header-title { font-size: 17px !important; font-weight: 900 !important; color: #ffffff !important; }
    .usr-add-btn { display: inline-flex !important; align-items: center; gap: 6px; background-color: #1ABB9C !important;
        color: #fff !important; border: none !important; border-radius: 8px !important; padding: 8px 16px !important;
        font-size: 12px !important; font-weight: 700 !important; text-decoration: none !important; transition: all .15s; }
    .usr-add-btn:hover { background-color: #15a085 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
    
    .usr-panel { background-color: #253347 !important; border: 1px solid rgba(255,255,255,0.13) !important;
        border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important; }
    .usr-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        padding: 13px 18px !important; background-color: #2c3d52 !important; border-bottom: 1px solid rgba(255,255,255,0.10) !important; }
    
    #usersTable_wrapper .dataTables_filter label, #usersTable_wrapper .dataTables_length label {
        color: #a8c0d4 !important; font-size: 11.5px !important; font-weight: 600 !important; }
    #usersTable_wrapper .dataTables_filter input { background-color: #1e2a3a !important;
        border: 1px solid rgba(255,255,255,0.18) !important; border-radius: 8px !important; color: #e8f4ff !important;
        font-size: 12px !important; padding: 5px 12px !important; }
    #usersTable_wrapper .dataTables_filter input:focus { outline: none !important;
        border-color: rgba(26,187,156,0.55) !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.12) !important; }
    #usersTable_wrapper .dataTables_length select { background-color: #1e2a3a !important;
        border: 1px solid rgba(255,255,255,0.18) !important; border-radius: 8px !important; color: #e8f4ff !important;
        font-size: 12px !important; padding: 4px 8px !important; }
    #usersTable_wrapper .dataTables_info { color: #7a96ab !important; font-size: 11.5px !important; }
    #usersTable_wrapper .dataTables_paginate { display: flex; align-items: center; gap: 8px; }
    #usersTable_wrapper .dataTables_paginate .paginate_button { min-width: 32px; height: 32px; display: flex;
        align-items: center; justify-content: center; color: #a8c0d4 !important; border: 1.5px solid rgba(255,255,255,0.15) !important;
        background: transparent !important; border-radius: 8px !important; padding: 0 !important; font-size: 12px !important;
        margin: 0 2px !important; font-weight: 600 !important; transition: all .2s !important; }
    #usersTable_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
        background-color: rgba(26,187,156,0.16) !important; color: #1ABB9C !important; border-color: rgba(26,187,156,0.50) !important; }
    #usersTable_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%) !important;
        color: #fff !important; border-color: #1ABB9C !important; font-weight: 800 !important; }
    
    #usersTable { border-collapse: collapse !important; width: 100% !important; }
    #usersTable thead tr { background-color: #2c3d52 !important; }
    #usersTable thead th { padding: 10px 14px !important; font-size: 9px !important; font-weight: 800 !important;
        text-transform: uppercase !important; letter-spacing: 1.3px !important; color: #8ab0c8 !important;
        border-bottom: 1.5px solid rgba(255,255,255,0.10) !important; }
    #usersTable tbody tr { border-bottom: 1px solid rgba(255,255,255,0.06) !important; transition: background .12s !important; }
    #usersTable tbody tr:hover { background-color: #2c3d52 !important; }
    #usersTable tbody td { padding: 11px 14px !important; vertical-align: middle !important;
        background-color: transparent !important; border: none !important; color: #e8f4ff !important; font-size: 12px; }
    
    .usr-btn-edit { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px;
        border-radius: 7px; background-color: rgba(245,158,11,0.18) !important; border: 1px solid rgba(245,158,11,0.32) !important;
        color: #fcd34d !important; transition: all .15s; cursor: pointer; text-decoration: none !important; font-size: 12px; }
    .usr-btn-edit:hover { background-color: rgba(245,158,11,0.28) !important; box-shadow: 0 2px 8px rgba(245,158,11,0.22) !important; }
    .usr-btn-delete { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px;
        border-radius: 7px; background-color: rgba(239,68,68,0.18) !important; border: 1px solid rgba(239,68,68,0.30) !important;
        color: #fca5a5 !important; transition: all .15s; cursor: pointer; font-size: 12px; }
    .usr-btn-delete:hover { background-color: rgba(239,68,68,0.28) !important; box-shadow: 0 2px 8px rgba(239,68,68,0.22) !important; }
    
    .usr-bottom-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        padding: 11px 18px; border-top: 1px solid rgba(255,255,255,0.08); background-color: #2c3d52 !important; }
    .usr-anim { animation: usrIn .35s ease both; }
    @keyframes usrIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
</style>
@endpush

@section('content')

<div class="usr-header usr-anim">
    <div>
        <div class="usr-header-title">Admin Users</div>
        <div style="font-size: 12px; color: #a8c0d4; margin-top: 2px;">Manage system users and their roles</div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="usr-add-btn">
        <i class="fa fa-plus-circle"></i> Add User
    </a>
</div>

<div class="usr-panel usr-anim">
    <div class="usr-toolbar">
        <div style="font-size: 11px; color: #c5d8e8; font-weight: 800; text-transform: uppercase; letter-spacing: 1.3px;">
            <i class="fa fa-users" style="margin-right: 6px; color: #1ABB9C;"></i> All Users
        </div>
        <div id="usr-dt-controls" style="display:flex;align-items:center;gap:10px;"></div>
    </div>

    <div style="overflow-x:auto;">
        <table id="usersTable" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:42px;">#</th>
                    <th>User Information</th>
                    <th>Email & Phone</th>
                    <th>Last Login</th>
                    <th style="text-align:center;">Role</th>
                    <th style="text-align:right;width:90px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $u)
                <tr>
                    <td><span style="font-weight:700;color:#4e6d85;font-size:12px;">{{ $key + 1 }}</span></td>

                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($u->profile_photo)
                                <img src="{{ asset('storage/'.$u->profile_photo) }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1.5px solid rgba(255,255,255,0.15);">
                            @else
                                <div style="width:36px;height:36px;border-radius:8px;background-color:rgba(26,187,156,0.15);display:flex;align-items:center;justify-content:center;color:#1ABB9C;font-weight:800;font-size:14px;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <div style="min-width:0;">
                                <div style="font-size:13px;font-weight:800;color:#ffffff;">{{ $u->name }}</div>
                                <div style="font-size:10px;color:#7a96ab;margin-top:1px;">Created: {{ $u->created_at?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div>
                            <div style="font-size:11px;color:#90afc4;"><i class="fa fa-envelope" style="margin-right:4px;opacity:.6;"></i>{{ $u->email ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#90afc4;margin-top:2px;"><i class="fa fa-phone" style="margin-right:4px;opacity:.6;"></i>{{ $u->phone_number ?? 'N/A' }}</div>
                        </div>
                    </td>

                    <td>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#ddeeff;">{{ $u->getLastLoginDisplay() }}</div>
                            <div style="font-size:10px;color:#7a96ab;margin-top:2px;">{{ $u->getDaysSinceLastLogin() ?? 0 }} days ago</div>
                        </div>
                    </td>

                    <td style="text-align:center;">
                        <span style="display:inline-block;background-color:rgba(99,102,241,0.20);color:#c7d2fe;border:1px solid rgba(99,102,241,0.35);font-size:9px;font-weight:800;padding:3px 10px;border-radius:6px;text-transform:uppercase;">
                            {{ $u->role->name ?? 'User' }}
                        </span>
                    </td>

                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                            <a href="{{ route('admin.users.edit', $u->uuid) }}" class="usr-btn-edit" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $u->uuid) }}" method="POST" style="display:inline;margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="usr-btn-delete" title="Delete" onclick="return confirm('Remove this user?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 15,
        columnDefs: [{ orderable: false, targets: [5] }],
        language: {
            search: '', searchPlaceholder: 'Search users...',
            lengthMenu: '_MENU_ per page',
            info: 'Showing _START_ – _END_ of _TOTAL_ users',
            paginate: {
                previous: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>',
            }
        },
        initComplete: function () {
            $('#usersTable_wrapper .dataTables_filter').detach().appendTo('#usr-dt-controls');
            $('#usersTable_wrapper .dataTables_length').detach().appendTo('#usr-dt-controls');
        },
        dom: 'rt<"usr-bottom-bar"ip>',
    });
});
</script>
@endpush