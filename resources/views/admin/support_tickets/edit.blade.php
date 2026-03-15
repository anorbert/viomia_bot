@extends('layouts.admin')

@section('title', 'Edit Support Ticket #' . substr($ticket->id, 0, 8) . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; padding: 24px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-form-group { margin-bottom: 18px; }
.vi-form-group label { display: block; font-size: 11px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8 !important; margin-bottom: 8px; }
.vi-form-control { width: 100% !important; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9 !important; padding: 10px 12px !important; border-radius: 6px !important; font-size: 12px !important; transition: all 0.2s; }
.vi-form-control:focus { border-color: #1ABB9C !important; background-color: rgba(26,187,156,0.05) !important; outline: none; }
.vi-form-control option { background-color: #111827; color: #f1f5f9; }
.vi-form-control::placeholder { color: #4b5563; }
.vi-btn { padding: 10px 20px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-error { color: #EF4444; font-size: 10px; margin-top: 4px; display: block; }
.vi-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 768px) { .vi-form-row { grid-template-columns: 1fr; } }
.vi-section { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-section:last-child { border-bottom: none; }
.vi-section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🎫 Support</div>
        <div class="vi-header-title">Edit Ticket #{{ substr($ticket->id, 0, 8) }}</div>
    </div>
    <a href="{{ route('admin.support_tickets.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-chevron-left"></i> Back
    </a>
</div>

@if($errors->any())
    <div style="background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
        <i class="fa fa-exclamation-circle"></i> <strong>Validation errors:</strong>
        <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 11px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="vi-panel">
    <form method="POST" action="{{ route('admin.support_tickets.update', $ticket) }}">
        @csrf
        @method('PUT')

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-info-circle"></i> Ticket Information</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Customer <span style="color:#EF4444;">*</span></label>
                    <input type="text" class="vi-form-control" value="{{ $ticket->user->name }} ({{ $ticket->user->email }})" disabled>
                </div>

                <div class="vi-form-group">
                    <label>Ticket ID</label>
                    <input type="text" class="vi-form-control" value="{{ $ticket->id }}" disabled>
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Category <span style="color:#EF4444;">*</span></label>
                    <select name="category" class="vi-form-control @error('category') is-invalid @enderror" required>
                        <option value="Technical" {{ $ticket->category == 'Technical' ? 'selected' : '' }}>⚙️ Technical</option>
                        <option value="Billing" {{ $ticket->category == 'Billing' ? 'selected' : '' }}>💳 Billing</option>
                        <option value="Trading" {{ $ticket->category == 'Trading' ? 'selected' : '' }}>📈 Trading</option>
                        <option value="General" {{ $ticket->category == 'General' ? 'selected' : '' }}>💬 General</option>
                    </select>
                    @error('category')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Priority Level <span style="color:#EF4444;">*</span></label>
                    <select name="priority" class="vi-form-control @error('priority') is-invalid @enderror" required>
                        <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>🟠 High</option>
                        <option value="Critical" {{ $ticket->priority == 'Critical' ? 'selected' : '' }}>🔴 Critical</option>
                    </select>
                    @error('priority')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-group">
                <label>Title <span style="color:#EF4444;">*</span></label>
                <input type="text" name="title" class="vi-form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $ticket->title) }}" required>
                @error('title')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-pencil"></i> Description & Notes</div>

            <div class="vi-form-group">
                <label>Issue Description <span style="color:#EF4444;">*</span></label>
                <textarea name="description" class="vi-form-control @error('description') is-invalid @enderror" 
                          rows="6" required>{{ old('description', $ticket->description) }}</textarea>
                @error('description')<span class="vi-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label>Admin Notes & Resolution</label>
                <textarea name="admin_notes" class="vi-form-control @error('admin_notes') is-invalid @enderror" 
                          placeholder="Add internal notes, troubleshooting steps, or resolution details here..." 
                          rows="6">{{ old('admin_notes', $ticket->admin_notes) }}</textarea>
                @error('admin_notes')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-cog"></i> Status & Resolution</div>

            <div class="vi-form-group">
                <label>Ticket Status <span style="color:#EF4444;">*</span></label>
                <select name="status" class="vi-form-control @error('status') is-invalid @enderror" required>
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>🔵 Open</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>✅ Resolved</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>⬜ Closed</option>
                </select>
                @error('status')<span class="vi-error">{{ $message }}</span>@enderror
            </div>

            <div style="background-color: rgba(26,187,156,0.05); padding: 12px; border-radius: 6px; border-left: 3px solid #1ABB9C; font-size: 11px; color: #94a3b8;">
                <i class="fa fa-info-circle" style="color:#1ABB9C;"></i> 
                <strong>Ticket Created:</strong> {{ $ticket->created_at->format('M d, Y \a\t H:i A') }} •
                <strong>Last Updated:</strong> {{ $ticket->updated_at->format('M d, Y \a\t H:i A') }}
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.support_tickets.show', $ticket) }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-times"></i> Cancel
            </a>
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-check-circle"></i> Save Changes
            </button>
        </div>
    </form>
</div>

@endsection
