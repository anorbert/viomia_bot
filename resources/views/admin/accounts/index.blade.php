@extends('layouts.admin')

@section('title', 'Trading Accounts — ' . config('app.name'))

@push('styles')
<style>
    /* ════════════════════════════════════════
       VIOMIA · ACCOUNTS MANAGEMENT
       Dark theme with teal accents
       ════════════════════════════════════════ */

    .right_col {
        background-color: #1e2a3a !important;
        padding: 20px 24px !important;
        min-height: 100vh;
    }

    /* ── PAGE HEADER ── */
    .acc-header {
        display: flex !important; align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap; gap: 12px;
        background-color: #253347 !important;
        border: 1px solid rgba(255,255,255,0.13) !important;
        border-top: 3px solid #1ABB9C !important;
        border-radius: 12px !important;
        padding: 16px 22px !important; margin-bottom: 18px !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important;
    }
    .acc-header-title { font-size: 17px !important; font-weight: 900 !important; color: #ffffff !important; }
    .acc-header-sub { font-size: 12px !important; color: #a8c0d4 !important; margin-top: 2px; }
    .acc-add-btn {
        display: inline-flex !important; align-items: center; gap: 6px;
        background-color: #1ABB9C !important; color: #fff !important;
        border: none !important; border-radius: 8px !important;
        padding: 8px 16px !important; font-size: 12px !important; font-weight: 700 !important;
        text-decoration: none !important; transition: all .15s;
    }
    .acc-add-btn:hover {
        background-color: #15a085 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important;
    }

    /* ── TABLE PANEL ── */
    .acc-panel {
        background-color: #253347 !important;
        border: 1px solid rgba(255,255,255,0.13) !important;
        border-radius: 12px !important; overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important;
    }

    .acc-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        padding: 13px 18px !important;
        background-color: #2c3d52 !important;
        border-bottom: 1px solid rgba(255,255,255,0.10) !important;
    }

    /* DataTables */
    #accountsTable_wrapper .dataTables_filter label,
    #accountsTable_wrapper .dataTables_length label {
        color: #a8c0d4 !important; font-size: 11.5px !important; font-weight: 600 !important;
    }
    #accountsTable_wrapper .dataTables_filter input {
        background-color: #1e2a3a !important;
        border: 1px solid rgba(255,255,255,0.18) !important;
        border-radius: 8px !important; color: #e8f4ff !important;
        font-size: 12px !important; padding: 5px 12px !important;
    }
    #accountsTable_wrapper .dataTables_filter input:focus {
        outline: none !important; border-color: rgba(26,187,156,0.55) !important;
        box-shadow: 0 0 0 3px rgba(26,187,156,0.12) !important;
    }
    #accountsTable_wrapper .dataTables_length select {
        background-color: #1e2a3a !important;
        border: 1px solid rgba(255,255,255,0.18) !important;
        border-radius: 8px !important; color: #e8f4ff !important;
        font-size: 12px !important; padding: 4px 8px !important;
    }
    #accountsTable_wrapper .dataTables_info { color: #7a96ab !important; font-size: 11.5px !important; }
    #accountsTable_wrapper .dataTables_paginate {
        display: flex; align-items: center; gap: 8px;
    }
    #accountsTable_wrapper .dataTables_paginate .paginate_button {
        min-width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        color: #a8c0d4 !important; border: 1.5px solid rgba(255,255,255,0.15) !important;
        background: transparent !important; border-radius: 8px !important;
        padding: 0 !important; font-size: 12px !important; margin: 0 2px !important;
        font-weight: 600 !important; transition: all .2s !important;
    }
    #accountsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
        background-color: rgba(26,187,156,0.16) !important;
        color: #1ABB9C !important; border-color: rgba(26,187,156,0.50) !important;
    }
    #accountsTable_wrapper .dataTables_paginate .paginate_button.current,
    #accountsTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%) !important; 
        color: #fff !important; border-color: #1ABB9C !important; font-weight: 800 !important;
    }

    /* ── TABLE ── */
    #accountsTable { border-collapse: collapse !important; width: 100% !important; }
    #accountsTable thead tr { background-color: #2c3d52 !important; }
    #accountsTable thead th {
        padding: 10px 14px !important; font-size: 9px !important; font-weight: 800 !important;
        text-transform: uppercase !important; letter-spacing: 1.3px !important;
        color: #8ab0c8 !important;
        border-bottom: 1.5px solid rgba(255,255,255,0.10) !important;
    }
    #accountsTable tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        transition: background .12s !important;
    }
    #accountsTable tbody tr:hover { background-color: #2c3d52 !important; }
    #accountsTable tbody td {
        padding: 11px 14px !important; vertical-align: middle !important;
        background-color: transparent !important; border: none !important;
        color: #e8f4ff !important; font-size: 12px;
    }

    .acc-sn { font-size: 12px !important; font-weight: 700 !important; color: #4e6d85 !important; }
    .acc-name  { font-size: 13px !important; font-weight: 800 !important; color: #ffffff !important; }
    .acc-email { font-size: 11px !important; color: #90afc4 !important; }
    .acc-badge {
        display: inline-block;
        background-color: rgba(26,187,156,0.20) !important; color: #5eead4 !important;
        border: 1px solid rgba(26,187,156,0.35) !important;
        font-size: 8.5px !important; font-weight: 800 !important;
        padding: 2px 8px; border-radius: 6px;
        text-transform: uppercase; letter-spacing: .4px;
    }
    .acc-badge.real {
        background-color: rgba(34,197,94,0.20) !important; color: #86efac !important;
        border-color: rgba(34,197,94,0.30) !important;
    }

    .acc-btn-edit {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 7px;
        background-color: rgba(245,158,11,0.18) !important;
        border: 1px solid rgba(245,158,11,0.32) !important; color: #fcd34d !important;
        transition: all .15s; cursor: pointer; text-decoration: none !important; font-size: 12px;
    }
    .acc-btn-edit:hover {
        background-color: rgba(245,158,11,0.28) !important;
        box-shadow: 0 2px 8px rgba(245,158,11,0.22) !important;
    }
    .acc-btn-delete {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 7px;
        background-color: rgba(239,68,68,0.18) !important;
        border: 1px solid rgba(239,68,68,0.30) !important; color: #fca5a5 !important;
        transition: all .15s; cursor: pointer; font-size: 12px;
    }
    .acc-btn-delete:hover {
        background-color: rgba(239,68,68,0.28) !important;
        box-shadow: 0 2px 8px rgba(239,68,68,0.22) !important;
    }

    .acc-bottom-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; padding: 11px 18px;
        border-top: 1px solid rgba(255,255,255,0.08);
        background-color: #2c3d52 !important;
    }

    @keyframes accUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
    .acc-anim   { animation: accUp .35s ease both; }
    .acc-anim-1 { animation-delay: .04s; }

    @media (max-width: 767px) {
        .acc-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush


@section('content')

<div class="acc-header acc-anim">
    <div>
        <div class="acc-header-title">Trading Accounts</div>
        <div class="acc-header-sub">Manage and monitor all linked trading accounts and their performance.</div>
    </div>
    <a href="{{ route('admin.accounts.create') }}" class="acc-add-btn">
        <i class="fa fa-plus-circle"></i> Add Account
    </a>
</div>

<div class="acc-panel acc-anim acc-anim-1">
    <div class="acc-toolbar">
        <div style="font-size: 11px; color: #c5d8e8; font-weight: 800; text-transform: uppercase; letter-spacing: 1.3px;">
            <i class="fa fa-university" style="margin-right: 6px; color: #1ABB9C;"></i> All Accounts
        </div>
        <div id="acc-dt-controls" style="display:flex;align-items:center;gap:10px;"></div>
    </div>

    <div style="overflow-x:auto;">
        <table id="accountsTable" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:42px;">#</th>
                    <th>Account & Owner</th>
                    <th style="text-align:center;width:90px;">Type</th>
                    <th>Platform</th>
                    <th style="text-align:center;width:90px;">Status</th>
                    <th style="text-align:right;width:120px;">Balance</th>
                    <th style="text-align:right;width:90px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $key => $acc)
                <tr>
                    <td><span class="acc-sn">{{ $key + 1 }}</span></td>

                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:8px;background-color:rgba(26,187,156,0.15);display:flex;align-items:center;justify-content:center;color:#1ABB9C;font-weight:800;">
                                <i class="fa fa-university"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="acc-name">{{ $acc->login }}</div>
                                <div class="acc-email"><i class="fa fa-user" style="margin-right:3px;opacity:.6;"></i>{{ $acc->User->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>

                    <td style="text-align:center;">
                        <span class="acc-badge {{ strtolower($acc->account_type) === 'real' ? 'real' : '' }}">
                            {{ strtoupper($acc->account_type) }}
                        </span>
                    </td>

                    <td>
                        <div>
                            <div style="color:#e8f4ff;font-weight:700;font-size:12px;">{{ strtoupper($acc->platform) }}</div>
                            <div style="font-size:10px;color:#7a96ab;margin-top:2px;">
                                <i class="fa fa-server" style="margin-right:3px;opacity:.5;"></i>{{ $acc->server }}
                            </div>
                        </div>
                    </td>

                    <td style="text-align:center;">
                        @if($acc->active)
                            <span style="display:inline-block;background-color:rgba(34,197,94,0.18);color:#86efac;border:1px solid rgba(34,197,94,0.32);font-size:9px;font-weight:800;padding:3px 10px;border-radius:20px;text-transform:uppercase;">
                                <i class="fa fa-circle" style="font-size:6px;margin-right:4px;"></i> Active
                            </span>
                        @else
                            <span style="display:inline-block;background-color:rgba(255,255,255,0.08);color:#7a96ab;border:1px solid rgba(255,255,255,0.14);font-size:9px;font-weight:800;padding:3px 10px;border-radius:20px;text-transform:uppercase;">
                                <i class="fa fa-circle" style="font-size:6px;margin-right:4px;"></i> Inactive
                            </span>
                        @endif
                    </td>

                    <td style="text-align:right;color:#4ade80;font-weight:800;">
                        ${{ number_format($acc->snapshots->balance ?? 0, 2) }}
                    </td>

                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                            <a href="{{ route('admin.accounts.edit', $acc->uuid) }}" class="acc-btn-edit" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <form action="{{ route('admin.accounts.destroy', $acc->uuid) }}" method="POST" style="display:inline;margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="acc-btn-delete" title="Delete" onclick="return confirm('Remove this account?')">
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
    $('#accountsTable').DataTable({
        responsive:  true,
        pageLength:  15,
        columnDefs:  [{ orderable: false, targets: [6] }],
        language: {
            search: '', searchPlaceholder: 'Search accounts...',
            lengthMenu: '_MENU_ per page',
            info: 'Showing _START_ – _END_ of _TOTAL_ accounts',
            paginate: {
                previous: '<i class="fa fa-chevron-left"></i>',
                next:     '<i class="fa fa-chevron-right"></i>',
            }
        },
        initComplete: function () {
            $('#accountsTable_wrapper .dataTables_filter').detach().appendTo('#acc-dt-controls');
            $('#accountsTable_wrapper .dataTables_length').detach().appendTo('#acc-dt-controls');
        },
        dom: 'rt<"acc-bottom-bar"ip>',
    });
});
</script>
@endpush