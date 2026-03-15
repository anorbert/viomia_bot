@extends('layouts.admin')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 600px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-form-group { margin-bottom: 20px; }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; box-sizing: border-box; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-input:disabled { background-color: rgba(255,255,255,0.05) !important; opacity: 0.6; cursor: not-allowed; }
.vi-form-select { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; cursor: pointer; box-sizing: border-box; }
.vi-form-select:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-metadata { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-metadata-item { }
.vi-metadata-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #4b5563; letter-spacing: 0.5px; }
.vi-metadata-value { font-size: 12px; color: #f1f5f9; margin-top: 4px; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👥 Role Management</div>
            <div class="vi-header-title">Edit Role</div>
            <div class="vi-header-sub">Update role configuration</div>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Roles
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($errors->all() as $error)
                <li style="padding: 3px 0;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($role->isSystemRole())
    <div class="vi-info-box" style="background: rgba(251,146,60,0.05); border-color: rgba(251,146,60,0.15); border-left-color: #FB923C;">
        <div class="vi-info-box-title" style="color: #FB923C;"><i class="fa fa-shield"></i> System Role</div>
        <div class="vi-info-box-text" style="color: #FB923C;">This is a system role and cannot be deleted. Only the status can be changed.</div>
    </div>
    @endif

    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-edit"></i> Role Details</div>

        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="vi-form-group">
                <label class="vi-form-label">Role Name <span class="required">*</span></label>
                <input type="text" name="name" class="vi-form-input @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}"
                       {{ $role->isSystemRole() ? 'disabled' : '' }} required>
                @error('name')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Role identifier (e.g., admin, support, user)</div>
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Status <span class="required">*</span></label>
                <select name="status" class="vi-form-select @error('status') is-invalid @enderror" required>
                    <option value="Active" {{ old('status', $role->status) === 'Active' ? 'selected' : '' }}>Active (Users can be assigned)</option>
                    <option value="Inactive" {{ old('status', $role->status) === 'Inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                </select>
                @error('status')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Select whether this role is available for assignment</div>
            </div>

            <!-- Metadata Display -->
            <div class="vi-metadata">
                @if($role->created_at)
                <div class="vi-metadata-item">
                    <div class="vi-metadata-label"><i class="fa fa-calendar"></i> Created</div>
                    <div class="vi-metadata-value">{{ $role->created_at->format('M d, Y H:i') }}</div>
                </div>
                @endif
                @if($role->updated_at)
                <div class="vi-metadata-item">
                    <div class="vi-metadata-label"><i class="fa fa-refresh"></i> Last Updated</div>
                    <div class="vi-metadata-value">{{ $role->updated_at->format('M d, Y H:i') }}</div>
                </div>
                @endif
                <div class="vi-metadata-item">
                    <div class="vi-metadata-label"><i class="fa fa-users"></i> Assigned Users</div>
                    <div class="vi-metadata-value" style="background: rgba(26,187,156,0.1); padding: 4px 8px; border-radius: 4px; display: inline-block;">{{ $role->getUserCount() }}</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.roles.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
