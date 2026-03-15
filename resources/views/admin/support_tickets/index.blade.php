@extends('layouts.admin')

@section('title', 'Support Tickets — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; color: #1ABB9C; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 800 !important; font-size: 13px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-open { background-color: rgba(59,158,255,0.13) !important; color: #3B9EFF !important; }
.vi-badge-in_progress { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-resolved { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-closed { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; }
.vi-badge-critical { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-high { background-color: rgba(249,115,22,0.13) !important; color: #F97316 !important; }
.vi-badge-medium { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-low { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-btn { padding: 6px 12px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; border: 1px solid rgba(239,68,68,0.25) !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.2) !important; }
.vi-filter { display: flex; gap: 8px; align-items: flex-end; padding: 16px 18px; background-color: rgba(26,187,156,0.05); border-bottom: 1px solid rgba(255,255,255,0.07); flex-wrap: wrap; }
.vi-filter-group { display: flex; flex-direction: column; gap: 4px; }
.vi-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4b5563; }
.vi-filter select, .vi-filter input { background-color: #1a2235 !important; color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); padding: 7px 10px; border-radius: 6px; font-size: 11px; min-width: 140px; }
.vi-filter select:focus, .vi-filter input:focus { border-color: #1ABB9C !important; outline: none; box-shadow: 0 0 0 3px rgba(26,187,156,0.1); }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🎫 Support System</div>
        <div class="vi-header-title">Customer Support Tickets</div>
        <div class="vi-header-sub">Manage and resolve customer inquiries and support requests</div>
    </div>
    <a href="{{ route('admin.support_tickets.create') }}" class="vi-btn vi-btn-primary" style="margin-left:auto;">
        <i class="fa fa-plus-circle"></i> Create Ticket
    </a>
</div>

@if(session('success'))
    <div style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Statistics Dashboard -->
<div class="vi-stats-row">
    @php
        $totalTickets = $tickets->total();
        $openTickets = $tickets->where('status', 'open')->count();
        $inProgressTickets = $tickets->where('status', 'in_progress')->count();
        $resolvedTickets = $tickets->where('status', 'resolved')->count();
        $criticalTickets = $tickets->where('priority', 'Critical')->count();
    @endphp
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-cube" style="color:#1ABB9C;"></i> Total Tickets</div>
        <div class="vi-stat-value">{{ $totalTickets }}</div>
    </div>
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-folder-open" style="color:#3B9EFF;"></i> Open</div>
        <div class="vi-stat-value" style="color: #3B9EFF;">{{ $openTickets }}</div>
    </div>
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-spinner" style="color:#FB923C;"></i> In Progress</div>
        <div class="vi-stat-value" style="color: #FB923C;">{{ $inProgressTickets }}</div>
    </div>
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-check" style="color:#22C55E;"></i> Resolved</div>
        <div class="vi-stat-value" style="color: #22C55E;">{{ $resolvedTickets }}</div>
    </div>
    <div class="vi-stat-card">
        <div class="vi-stat-label"><i class="fa fa-exclamation-triangle" style="color:#EF4444;"></i> Critical</div>
        <div class="vi-stat-value" style="color: #EF4444;">{{ $criticalTickets }}</div>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
        <div class="vi-panel-title">All Tickets ({{ $tickets->total() }})</div>
    </div>
    
    <div class="vi-filter">
        <div class="vi-filter-group" style="flex: 1; min-width: 200px;">
            <div class="vi-filter-label">Search</div>
            <input type="text" id="searchInput" placeholder="Ticket ID or title..." style="width: 100%;">
        </div>
        <div class="vi-filter-group">
            <div class="vi-filter-label">Status</div>
            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>
        <div class="vi-filter-group">
            <div class="vi-filter-label">Priority</div>
            <select id="priorityFilter">
                <option value="">All Priority</option>
                <option value="Critical">Critical</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
        </div>
        <div class="vi-filter-group">
            <div class="vi-filter-label">Category</div>
            <select id="categoryFilter">
                <option value="">All Category</option>
                <option value="Technical">Technical</option>
                <option value="Billing">Billing</option>
                <option value="Trading">Trading</option>
                <option value="General">General</option>
            </select>
        </div>
    </div>

    <div class="vi-panel-body" style="overflow-x:auto;">
        <table class="vi-table" id="ticketsTable">
            <thead>
                <tr>
                    <th style="width:10%;">ID</th>
                    <th style="width:20%;">Title</th>
                    <th style="width:12%;">Category</th>
                    <th style="width:12%;">Priority</th>
                    <th style="width:12%;">Status</th>
                    <th style="width:15%;">User</th>
                    <th style="width:15%;">Created</th>
                    <th style="text-align:right; width:20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr class="ticket-row" data-status="{{ $ticket->status }}" data-priority="{{ $ticket->priority }}" data-category="{{ $ticket->category }}">
                        <td>
                            <code style="background-color:rgba(26,187,156,0.1); color:#1ABB9C; padding:3px 8px; border-radius:4px; font-size:10px;">{{ substr($ticket->id, 0, 8) }}</code>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                @if($ticket->priority === 'Critical')
                                    <i class="fa fa-exclamation-triangle" style="color:#EF4444;"></i>
                                @elseif($ticket->priority === 'High')
                                    <i class="fa fa-exclamation-circle" style="color:#F97316;"></i>
                                @else
                                    <i class="fa fa-comment" style="color:#94a3b8;"></i>
                                @endif
                                <span class="td-sym">{{ Str::limit($ticket->title, 35) }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:11px; padding:3px 8px; background-color:rgba(139,92,246,0.1); color:#A78BFA; border-radius:4px; font-weight:600;">
                                {{ $ticket->category }}
                            </span>
                        </td>
                        <td>
                            @if($ticket->priority === 'Critical')
                                <span class="vi-badge vi-badge-critical">CRITICAL</span>
                            @elseif($ticket->priority === 'High')
                                <span class="vi-badge vi-badge-high">HIGH</span>
                            @elseif($ticket->priority === 'Medium')
                                <span class="vi-badge vi-badge-medium">MEDIUM</span>
                            @else
                                <span class="vi-badge vi-badge-low">LOW</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status === 'open')
                                <span class="vi-badge vi-badge-open">OPEN</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="vi-badge vi-badge-in_progress">IN PROGRESS</span>
                            @elseif($ticket->status === 'resolved')
                                <span class="vi-badge vi-badge-resolved">RESOLVED ✓</span>
                            @else
                                <span class="vi-badge vi-badge-closed">CLOSED</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:11px;">
                                <div class="td-sym">{{ $ticket->user->name }}</div>
                                <div style="color:#4b5563; margin-top:2px; font-size:10px;">{{ $ticket->user->email }}</div>
                            </div>
                        </td>
                        <td style="font-size:10px; color:#4b5563;">{{ $ticket->created_at->format('M d, Y') }}<br><span style="font-size:9px;">{{ $ticket->created_at->diffForHumans() }}</span></td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.support_tickets.show', $ticket) }}" class="vi-btn vi-btn-primary" style="margin-right:6px;">
                                <i class="fa fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.support_tickets.edit', $ticket) }}" class="vi-btn vi-btn-secondary">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px !important;">
                            <i class="fa fa-inbox" style="font-size:32px; color:#4b5563; margin-bottom:10px; display:block;"></i>
                            <div style="color:#94a3b8; font-weight:600;">No support tickets found</div>
                            <p style="color:#4b5563; font-size:11px; margin-top:6px;">Start creating tickets to manage customer support</p>
                            <a href="{{ route('admin.support_tickets.create') }}" class="vi-btn vi-btn-primary" style="margin-top:12px;">
                                <i class="fa fa-plus"></i> Create First Ticket
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div style="display: flex; justify-content: flex-end; margin-top: 20px;">
    {{ $tickets->links() }}
</div>

<script>
    // Simple client-side filtering
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('priorityFilter').addEventListener('change', filterTable);
    document.getElementById('categoryFilter').addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        
        const rows = document.querySelectorAll('.ticket-row');
        rows.forEach(row => {
            const title = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const priority = row.getAttribute('data-priority');
            const category = row.getAttribute('data-category');
            
            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesPriority = !priorityFilter || priority === priorityFilter;
            const matchesCategory = !categoryFilter || category === categoryFilter;
            
            row.style.display = (matchesSearch && matchesStatus && matchesPriority && matchesCategory) ? '' : 'none';
        });
    }
</script>

@endsection
