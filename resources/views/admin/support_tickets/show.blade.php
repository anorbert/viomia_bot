@extends('layouts.admin')

@section('title', 'Support Ticket #' . substr($ticket->id, 0, 8) . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; margin-bottom: 16px; }
.vi-panel-head { padding: 16px 20px; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 16px 20px; }
.vi-info-item { }
.vi-info-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 6px; }
.vi-info-value { font-size: 13px; font-weight: 600; color: #f1f5f9; }
.vi-section { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-open { background-color: rgba(59,158,255,0.13) !important; color: #3B9EFF !important; }
.vi-badge-in_progress { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-badge-resolved { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-critical { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; }
.vi-badge-high { background-color: rgba(249,115,22,0.13) !important; color: #F97316 !important; }
.vi-badge-medium { background-color: rgba(251,146,60,0.13) !important; color: #FB923C !important; }
.vi-btn { padding: 8px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🎫 Support</div>
        <div class="vi-header-title">Ticket #{{ substr($ticket->id, 0, 8) }}</div>
    </div>
    <div style="margin-left: auto; display: flex; gap: 8px;">
        <a href="{{ route('admin.support_tickets.edit', $ticket) }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.support_tickets.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-chevron-left"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600; margin-bottom: 16px;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="vi-panel">
    <div class="vi-panel-head">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <div>
                <div style="font-size: 14px; font-weight: 800; color: #f1f5f9; margin-bottom: 4px;">{{ $ticket->title }}</div>
                <div style="font-size: 11px; color: #4b5563;">Created {{ $ticket->created_at->diffForHumans() }} • Updated {{ $ticket->updated_at->diffForHumans() }}</div>
            </div>
            <div style="display: flex; gap: 8px;">
                @if($ticket->priority === 'Critical')
                    <span class="vi-badge vi-badge-critical">CRITICAL</span>
                @elseif($ticket->priority === 'High')
                    <span class="vi-badge vi-badge-high">HIGH</span>
                @else
                    <span class="vi-badge vi-badge-medium">MEDIUM</span>
                @endif
                @if($ticket->status === 'open')
                    <span class="vi-badge vi-badge-open">OPEN</span>
                @elseif($ticket->status === 'in_progress')
                    <span class="vi-badge vi-badge-in_progress">IN PROGRESS</span>
                @else
                    <span class="vi-badge vi-badge-resolved">RESOLVED ✓</span>
                @endif
            </div>
        </div>
    </div>

    <div class="vi-info-grid">
        <div class="vi-info-item">
            <div class="vi-info-label">Category</div>
            <div class="vi-info-value">{{ $ticket->category }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Priority</div>
            <div class="vi-info-value">{{ $ticket->priority }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Status</div>
            <div class="vi-info-value">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</div>
        </div>
        <div class="vi-info-item">
            <div class="vi-info-label">Customer</div>
            <div class="vi-info-value">{{ $ticket->user->name }}<br><span style="font-size:10px; color:#4b5563;">{{ $ticket->user->email }}</span></div>
        </div>
    </div>

    <div class="vi-section">
        <div class="vi-section-title">📝 Issue Description</div>
        <div style="background-color: #1a2235; padding: 12px; border-radius: 6px; color: #94a3b8; font-size: 12px; line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;">{{ $ticket->description }}</div>
    </div>

    @if($ticket->admin_notes)
        <div class="vi-section">
            <div class="vi-section-title">💬 Admin Notes</div>
            <div style="background-color: rgba(26,187,156,0.05); padding: 12px; border-radius: 6px; color: #94a3b8; font-size: 12px; line-height: 1.6; border-left: 3px solid #1ABB9C; white-space: pre-wrap; word-wrap: break-word;">{{ $ticket->admin_notes }}</div>
        </div>
    @endif
</div>

<div style="display: flex; gap: 10px; justify-content: flex-end;">
    <a href="{{ route('admin.support_tickets.edit', $ticket) }}" class="vi-btn vi-btn-primary">
        <i class="fa fa-pencil"></i> Edit Ticket
    </a>
    <form method="POST" action="{{ route('admin.support_tickets.destroy', $ticket) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="vi-btn" style="background-color: rgba(239,68,68,0.13) !important; color: #EF4444; border: 1px solid rgba(239,68,68,0.25);">
            <i class="fa fa-trash"></i> Delete
        </button>
    </form>
</div>

@endsection
