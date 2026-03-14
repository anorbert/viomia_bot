@extends('layouts.admin')

@section('title', 'Client Management — ' . config('app.name'))

@push('styles')
<style>
/* ════════════════════════════════════════
   VIOMIA · CLIENT MANAGEMENT
   Twilight dark — boosted text contrast
   ════════════════════════════════════════ */

.right_col {
    background-color: #1e2a3a !important;
    padding: 20px 24px !important;
    min-height: 100vh;
}

/* ── PAGE HEADER ── */
.cm-header {
    display: flex !important; align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap; gap: 12px;
    background-color: #253347 !important;
    border: 1px solid rgba(255,255,255,0.13) !important;
    border-top: 3px solid #1ABB9C !important;
    border-radius: 12px !important;
    padding: 16px 22px !important; margin-bottom: 18px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important;
    position: relative; overflow: hidden;
}
.cm-header::after {
    content: ''; position: absolute; right: -40px; top: -40px;
    width: 160px; height: 160px; border-radius: 50%;
    background: radial-gradient(circle, rgba(26,187,156,0.15) 0%, transparent 70%);
    pointer-events: none;
}
/* ▲ Bright white title */
.cm-header-title { font-size: 17px !important; font-weight: 900 !important; color: #ffffff !important; letter-spacing: -.3px; }
/* ▲ Clear subtitle */
.cm-header-sub   { font-size: 12px !important; color: #a8c0d4 !important; margin-top: 3px; }

.cm-add-btn {
    display: inline-flex !important; align-items: center; gap: 6px;
    background-color: #1ABB9C !important; color: #fff !important;
    border: none !important; border-radius: 8px !important;
    padding: 8px 16px !important; font-size: 12px !important; font-weight: 700 !important;
    text-decoration: none !important; white-space: nowrap;
    transition: background .15s, box-shadow .15s;
}
.cm-add-btn:hover {
    background-color: #15a085 !important; color: #fff !important;
    box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important;
}

/* ── TABLE PANEL ── */
.cm-panel {
    background-color: #253347 !important;
    border: 1px solid rgba(255,255,255,0.13) !important;
    border-radius: 12px !important; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important;
}

.cm-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    padding: 13px 18px !important;
    background-color: #2c3d52 !important;
    border-bottom: 1px solid rgba(255,255,255,0.10) !important;
}
.cm-toolbar-label {
    font-size: 11px !important; font-weight: 800 !important;
    text-transform: uppercase; letter-spacing: 1.3px;
    /* ▲ brighter label */
    color: #c5d8e8 !important;
    display: flex; align-items: center; gap: 7px;
}
.cm-toolbar-label-ico {
    width: 24px; height: 24px; border-radius: 6px;
    background-color: rgba(26,187,156,0.18); color: #1ABB9C;
    display: flex; align-items: center; justify-content: center; font-size: 11px;
}

/* DataTables */
#clientsTable_wrapper .dataTables_filter label,
#clientsTable_wrapper .dataTables_length label {
    /* ▲ visible labels */
    color: #a8c0d4 !important; font-size: 11.5px !important; font-weight: 600 !important;
}
#clientsTable_wrapper .dataTables_filter input {
    background-color: #1e2a3a !important;
    border: 1px solid rgba(255,255,255,0.18) !important;
    border-radius: 8px !important; color: #e8f4ff !important;
    font-size: 12px !important; padding: 5px 12px !important; margin-left: 6px;
}
#clientsTable_wrapper .dataTables_filter input::placeholder { color: #6a8aA0 !important; }
#clientsTable_wrapper .dataTables_filter input:focus {
    outline: none !important; border-color: rgba(26,187,156,0.55) !important;
    box-shadow: 0 0 0 3px rgba(26,187,156,0.12) !important;
}
#clientsTable_wrapper .dataTables_length select {
    background-color: #1e2a3a !important;
    border: 1px solid rgba(255,255,255,0.18) !important;
    border-radius: 8px !important; color: #e8f4ff !important;
    font-size: 12px !important; padding: 4px 8px !important; margin: 0 5px;
}
#clientsTable_wrapper .dataTables_info { color: #7a96ab !important; font-size: 11.5px !important; }
#clientsTable_wrapper .dataTables_paginate {
    display: flex; align-items: center; gap: 8px;
}
#clientsTable_wrapper .dataTables_paginate .paginate_button {
    min-width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    color: #a8c0d4 !important; border: 1.5px solid rgba(255,255,255,0.15) !important;
    background: transparent !important; border-radius: 8px !important;
    padding: 0 !important; font-size: 12px !important; margin: 0 2px !important;
    font-weight: 600 !important; transition: all .2s !important; cursor: pointer;
}
#clientsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
    background-color: rgba(26,187,156,0.16) !important;
    color: #1ABB9C !important; border-color: rgba(26,187,156,0.50) !important;
    box-shadow: 0 2px 8px rgba(26,187,156,0.15) !important;
}
#clientsTable_wrapper .dataTables_paginate .paginate_button.current,
#clientsTable_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%) !important; 
    color: #fff !important; border-color: #1ABB9C !important; font-weight: 800 !important;
    box-shadow: 0 4px 12px rgba(26,187,156,0.30) !important;
}
#clientsTable_wrapper .dataTables_paginate .paginate_button.disabled,
#clientsTable_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    color: #3e5670 !important; border-color: rgba(255,255,255,0.08) !important;
    cursor: not-allowed !important; opacity: 0.5 !important;
}

/* ── TABLE ── */
#clientsTable { border-collapse: collapse !important; width: 100% !important; }
#clientsTable thead tr { background-color: #2c3d52 !important; }
#clientsTable thead th {
    padding: 10px 14px !important; font-size: 9px !important; font-weight: 800 !important;
    text-transform: uppercase !important; letter-spacing: 1.3px !important;
    /* ▲ readable column headers */
    color: #8ab0c8 !important;
    border-bottom: 1.5px solid rgba(255,255,255,0.10) !important;
    border-top: none !important; white-space: nowrap;
}
#clientsTable tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    transition: background .12s !important;
}
#clientsTable tbody tr:last-child { border-bottom: none !important; }
#clientsTable tbody tr:hover { background-color: #2c3d52 !important; }
#clientsTable tbody td {
    padding: 11px 14px !important; vertical-align: middle !important;
    background-color: transparent !important; border: none !important;
}

/* ▲ brighter serial */
.cm-sn { font-size: 12px !important; font-weight: 700 !important; color: #4e6d85 !important; }

/* ── CLIENT CELL ── */
.cm-avatar-wrap { position: relative; flex-shrink: 0; }
.cm-avatar { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; border: 2px solid rgba(255,255,255,0.15) !important; }
.cm-avatar-placeholder {
    width: 38px; height: 38px; border-radius: 10px;
    background-color: rgba(26,187,156,0.18) !important;
    border: 2px solid rgba(26,187,156,0.30) !important;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #1ABB9C !important;
}
.cm-online-dot {
    position: absolute; bottom: 0; right: 0;
    width: 9px; height: 9px; border-radius: 50%;
    background-color: #22C55E !important; border: 1.5px solid #253347 !important;
}
/* ▲ bright client name */
.cm-client-name  { font-size: 13.5px !important; font-weight: 800 !important; color: #ffffff !important; line-height: 1.2; }
/* ▲ visible email */
.cm-client-email { font-size: 11.5px !important; color: #90afc4 !important; margin-top: 2px; }

.cm-plan-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background-color: rgba(99,102,241,0.20) !important; color: #c7d2fe !important;
    border: 1px solid rgba(99,102,241,0.35) !important;
    font-size: 9px !important; font-weight: 800 !important;
    padding: 2px 8px; border-radius: 6px;
    text-transform: uppercase; letter-spacing: .5px; margin-top: 5px;
}
.cm-plan-badge.no-plan {
    background-color: rgba(239,68,68,0.18) !important; color: #fca5a5 !important;
    border-color: rgba(239,68,68,0.30) !important;
}
/* ▲ clear expiry */
.cm-plan-exp { font-size: 10.5px !important; color: #7a96ab !important; margin-top: 3px; }

.cm-bots-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background-color: rgba(59,158,255,0.18) !important; color: #93c5fd !important;
    border: 1px solid rgba(59,158,255,0.30) !important;
    font-size: 11.5px !important; font-weight: 800 !important;
    padding: 4px 12px; border-radius: 20px;
}

/* ── ACCOUNT ROW ── */
.cm-acc-row {
    display: flex; align-items: center;
    padding: 8px 10px; border-radius: 8px; margin-bottom: 5px;
    background-color: rgba(255,255,255,0.05) !important;
    border: 1px solid rgba(255,255,255,0.10) !important;
    gap: 0; transition: background .12s;
}
.cm-acc-row:last-child { margin-bottom: 0; }
.cm-acc-row:hover { background-color: rgba(255,255,255,0.09) !important; }
.acc-border-real { border-left: 3px solid #22C55E !important; }
.acc-border-demo { border-left: 3px solid #1ABB9C !important; }

/* ▲ bright platform name */
.cm-acc-platform { font-size: 12.5px !important; font-weight: 800 !important; color: #e8f4ff !important; line-height: 1; }
/* ▲ readable server */
.cm-acc-server   { font-size: 10px !important; color: #7a96ab !important; margin-top: 2px; }

.cm-acc-type-real {
    display: inline-block;
    background-color: rgba(34,197,94,0.18) !important; color: #86efac !important;
    border: 1px solid rgba(34,197,94,0.30) !important;
    font-size: 8.5px !important; font-weight: 800 !important;
    padding: 1px 6px; border-radius: 5px; text-transform: uppercase; letter-spacing: .4px; margin-left: 5px;
}
.cm-acc-type-demo {
    display: inline-block;
    background-color: rgba(26,187,156,0.18) !important; color: #5eead4 !important;
    border: 1px solid rgba(26,187,156,0.30) !important;
    font-size: 8.5px !important; font-weight: 800 !important;
    padding: 1px 6px; border-radius: 5px; text-transform: uppercase; letter-spacing: .4px; margin-left: 5px;
}
.cm-acc-sep { width: 1px; height: 20px; background-color: rgba(255,255,255,0.12); flex-shrink: 0; margin: 0 12px; }

/* ▲ bright login number */
.cm-acc-login {
    font-size: 13px !important; font-weight: 800 !important; color: #ddeeff !important;
    background-color: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    padding: 3px 10px; border-radius: 6px; letter-spacing: .8px;
}

.cm-acc-status { display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex: 1; }
.cm-status-dot { display: inline-flex; align-items: center; gap: 5px; font-size: 11px !important; font-weight: 700 !important; }
.cm-status-dot i { font-size: 7px; }
.cm-status-dot.active   { color: #4ade80 !important; }
.cm-status-dot.inactive { color: #4e6d85 !important; }
.cm-status-dot.conn     { color: #2dd4bf !important; }
.cm-status-dot.disc     { color: #3e5670 !important; }

.cm-no-accounts { font-size: 12px !important; color: #4e6d85 !important; font-style: italic; padding: 6px 2px; }

/* ── STATUS ── */
.cm-status-active {
    display: inline-block;
    background-color: rgba(34,197,94,0.18) !important; color: #4ade80 !important;
    border: 1px solid rgba(34,197,94,0.32) !important;
    font-size: 9.5px !important; font-weight: 800 !important;
    padding: 3px 10px; border-radius: 20px; letter-spacing: .8px; text-transform: uppercase;
}
.cm-status-inactive {
    display: inline-block;
    background-color: rgba(255,255,255,0.08) !important; color: #7a96ab !important;
    border: 1px solid rgba(255,255,255,0.14) !important;
    font-size: 9.5px !important; font-weight: 800 !important;
    padding: 3px 10px; border-radius: 20px; letter-spacing: .8px; text-transform: uppercase;
}

/* ── ACTIONS ── */
.cm-btn-edit {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px;
    background-color: rgba(245,158,11,0.18) !important;
    border: 1px solid rgba(245,158,11,0.32) !important; color: #fcd34d !important;
    transition: all .15s; cursor: pointer; text-decoration: none !important; font-size: 12px;
}
.cm-btn-edit:hover {
    background-color: rgba(245,158,11,0.28) !important; color: #fcd34d !important;
    box-shadow: 0 2px 8px rgba(245,158,11,0.22) !important;
}
.cm-btn-pause {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px;
    background-color: rgba(239,68,68,0.18) !important;
    border: 1px solid rgba(239,68,68,0.30) !important; color: #fca5a5 !important;
    transition: all .15s; cursor: pointer; font-size: 12px;
}
.cm-btn-pause:hover {
    background-color: rgba(239,68,68,0.28) !important;
    box-shadow: 0 2px 8px rgba(239,68,68,0.22) !important;
}
.cm-btn-activate {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px;
    background-color: rgba(34,197,94,0.18) !important;
    border: 1px solid rgba(34,197,94,0.30) !important; color: #86efac !important;
    transition: all .15s; cursor: pointer; font-size: 12px;
}
.cm-btn-activate:hover {
    background-color: rgba(34,197,94,0.28) !important;
    box-shadow: 0 2px 8px rgba(34,197,94,0.20) !important;
}

.cm-bottom-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; padding: 11px 18px;
    border-top: 1px solid rgba(255,255,255,0.08);
    background-color: #2c3d52 !important;
}

@keyframes cmUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
.cm-anim   { animation: cmUp .35s ease both; }
.cm-anim-1 { animation-delay: .04s; }
.cm-anim-2 { animation-delay: .09s; }

@media (max-width: 767px) {
    .cm-header { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush


@section('content')

<div class="cm-header cm-anim">
    <div>
        <div class="cm-header-title">Client Management</div>
        <div class="cm-header-sub">Monitor client bot activity, trading accounts, and subscription status.</div>
    </div>
    <a href="{{ route('admin.clients.create') }}" class="cm-add-btn">
        <i class="fa fa-plus-circle"></i> Add Client
    </a>
</div>

<div class="cm-panel cm-anim cm-anim-1">
    <div class="cm-toolbar">
        <div class="cm-toolbar-label">
            <span class="cm-toolbar-label-ico"><i class="fa fa-users"></i></span>
            All Clients
        </div>
        <div id="cm-dt-controls" style="display:flex;align-items:center;gap:10px;"></div>
    </div>

    <div style="overflow-x:auto;">
        <table id="clientsTable" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:42px;">#</th>
                    <th>Client &amp; Subscription</th>
                    <th style="text-align:center;width:80px;">Bots</th>
                    <th style="min-width:420px;">Linked Accounts</th>
                    <th style="text-align:center;width:100px;">Status</th>
                    <th style="text-align:right;width:90px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $key => $client)
                <tr>
                    <td><span class="cm-sn">{{ $key + 1 }}</span></td>

                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="cm-avatar-wrap">
                                @if($client->profile_photo)
                                    <img src="{{ asset('storage/'.$client->profile_photo) }}" class="cm-avatar" alt="">
                                @else
                                    <div class="cm-avatar-placeholder">{{ strtoupper(substr($client->name,0,1)) }}</div>
                                @endif
                                @if($client->is_active)<span class="cm-online-dot"></span>@endif
                            </div>
                            <div style="min-width:0;">
                                <div class="cm-client-name">{{ $client->name }}</div>
                                <div class="cm-client-email">{{ $client->email }}</div>
                                @if($client->phone ?? null)
                                    <div style="font-size:11px;color:#7a96ab;margin-top:1px;">
                                        <i class="fa fa-phone" style="margin-right:3px;opacity:.6;font-size:9px;"></i>{{ $client->phone }}
                                    </div>
                                @endif
                                <div style="margin-top:6px;">
                                    @if($client->currentSubscription)
                                        <span class="cm-plan-badge">
                                            <i class="fa fa-shield" style="font-size:8px;"></i>
                                            {{ strtoupper($client->currentSubscription->plan->name ?? 'Premium') }}
                                        </span>
                                        <div class="cm-plan-exp">
                                            <i class="fa fa-calendar-o" style="margin-right:3px;opacity:.6;"></i>
                                            Exp: {{ $client->currentSubscription->ends_at->format('d M, Y') }}
                                        </div>
                                    @else
                                        <span class="cm-plan-badge no-plan">
                                            <i class="fa fa-warning" style="font-size:8px;"></i> No Active Plan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <td style="text-align:center;">
                        <span class="cm-bots-badge"><i class="fa fa-terminal"></i> {{ $client->bots_count ?? '0' }}</span>
                    </td>

                    <td>
                        <div style="display:flex;flex-direction:column;gap:5px;">
                            @forelse($client->accounts as $acc)
                                @php $isReal = strtolower($acc->account_type) === 'real'; @endphp
                                <div class="cm-acc-row {{ $isReal ? 'acc-border-real' : 'acc-border-demo' }}">
                                    <div style="flex:1.2;min-width:0;">
                                        <div style="display:flex;align-items:center;">
                                            <span class="cm-acc-platform">{{ strtoupper($acc->platform) }}</span>
                                            @if($isReal)<span class="cm-acc-type-real">Real</span>
                                            @else<span class="cm-acc-type-demo">Demo</span>@endif
                                        </div>
                                        <div class="cm-acc-server">
                                            <i class="fa fa-server" style="margin-right:3px;opacity:.5;font-size:8px;"></i>{{ $acc->server }}
                                        </div>
                                    </div>
                                    <div class="cm-acc-sep"></div>
                                    <div style="flex:.9;text-align:center;">
                                        <span class="cm-acc-login">{{ $acc->login }}</span>
                                    </div>
                                    <div class="cm-acc-sep"></div>
                                    <div class="cm-acc-status">
                                        <span class="cm-status-dot {{ $acc->active ? 'active' : 'inactive' }}">
                                            <i class="fa fa-circle"></i>{{ $acc->active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="cm-status-dot {{ $acc->connected == 1 ? 'conn' : 'disc' }}">
                                            <i class="fa {{ $acc->connected == 1 ? 'fa-bolt' : 'fa-plug' }}"></i>
                                            {{ $acc->connected == 1 ? 'Connected' : 'Offline' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="cm-no-accounts">
                                    <i class="fa fa-unlink" style="margin-right:5px;opacity:.5;"></i>No linked accounts
                                </div>
                            @endforelse
                        </div>
                    </td>

                    <td style="text-align:center;">
                        @if($client->is_active)
                            <span class="cm-status-active">Active</span>
                        @else
                            <span class="cm-status-inactive">Inactive</span>
                        @endif
                    </td>

                    <td style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                            <a href="{{ route('admin.clients.edit', $client->uuid) }}" class="cm-btn-edit" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <form action="{{ route('admin.clients.destroy', $client->uuid) }}" method="POST" style="display:inline;margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="{{ $client->is_active ? 'cm-btn-pause' : 'cm-btn-activate' }}"
                                        title="{{ $client->is_active ? 'Deactivate' : 'Activate' }}"
                                        onclick="return confirm('Change client status?')">
                                    <i class="fa {{ $client->is_active ? 'fa-pause' : 'fa-play' }}"></i>
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
    $('#clientsTable').DataTable({
        responsive:  true,
        pageLength:  15,
        columnDefs:  [{ orderable: false, targets: [3, 5] }],
        language: {
            search: '', searchPlaceholder: 'Search clients...',
            lengthMenu: '_MENU_ per page',
            info: 'Showing _START_ – _END_ of _TOTAL_ clients',
            paginate: {
                previous: '<i class="fa fa-chevron-left"></i>',
                next:     '<i class="fa fa-chevron-right"></i>',
            }
        },
        initComplete: function () {
            $('#clientsTable_wrapper .dataTables_filter').detach().appendTo('#cm-dt-controls');
            $('#clientsTable_wrapper .dataTables_length').detach().appendTo('#cm-dt-controls');
        },
        dom: 'rt<"cm-bottom-bar"ip>',
    });
    $('#clientsTable_wrapper .dataTables_filter input').css({
        background:'#1e2a3a', border:'1px solid rgba(255,255,255,0.18)',
        borderRadius:'8px', color:'#e8f4ff', fontSize:'12px', padding:'5px 12px',
    });
    $('#clientsTable_wrapper .dataTables_length select').css({
        background:'#1e2a3a', border:'1px solid rgba(255,255,255,0.18)',
        borderRadius:'8px', color:'#e8f4ff', fontSize:'12px', padding:'4px 8px',
    });
});
</script>
@endpush