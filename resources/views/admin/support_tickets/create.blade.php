@extends('layouts.admin')

@section('title', 'Create Support Ticket — ' . config('app.name'))

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
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🎫 Support</div>
        <div class="vi-header-title">Create Support Ticket</div>
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
    <form method="POST" action="{{ route('admin.support_tickets.store') }}">
        @csrf

        <div class="vi-form-row">
            <div class="vi-form-group">
                <label>Customer User <span style="color:#EF4444;">*</span></label>
                <select name="user_id" class="vi-form-control @error('user_id') is-invalid @enderror" required>
                    <option value="">-- Select Customer --</option>
                    @foreach(\App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<span class="vi-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label>Priority Level <span style="color:#EF4444;">*</span></label>
                <select name="priority" class="vi-form-control @error('priority') is-invalid @enderror" required>
                    <option value="">-- Select Priority --</option>
                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>🟢 Low</option>
                    <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>🟠 High</option>
                    <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>🔴 Critical</option>
                </select>
                @error('priority')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-form-row">
            <div class="vi-form-group">
                <label>Category <span style="color:#EF4444;">*</span></label>
                <select name="category" class="vi-form-control @error('category') is-invalid @enderror" required>
                    <option value="">-- Select Category --</option>
                    <option value="Technical" {{ old('category') == 'Technical' ? 'selected' : '' }}>⚙️ Technical</option>
                    <option value="Billing" {{ old('category') == 'Billing' ? 'selected' : '' }}>💳 Billing</option>
                    <option value="Trading" {{ old('category') == 'Trading' ? 'selected' : '' }}>📈 Trading</option>
                    <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>💬 General</option>
                </select>
                @error('category')<span class="vi-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label>Ticket Title <span style="color:#EF4444;">*</span></label>
                <input type="text" name="title" class="vi-form-control @error('title') is-invalid @enderror" 
                       placeholder="Brief summary of the issue" value="{{ old('title') }}" required>
                @error('title')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-form-group">
            <label>Detailed Description <span style="color:#EF4444;">*</span></label>
            <textarea name="description" class="vi-form-control @error('description') is-invalid @enderror" 
                      placeholder="Provide detailed information about the issue..." rows="8" required>{{ old('description') }}</textarea>
            @error('description')<span class="vi-error">{{ $message }}</span>@enderror
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.support_tickets.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-times"></i> Cancel
            </a>
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-check-circle"></i> Create Ticket
            </button>
        </div>
    </form>
</div>

@endsection
