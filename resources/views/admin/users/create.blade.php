@extends('layouts.admin')

@section('title', 'Create User — ' . config('app.name'))

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
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👤 User Management</div>
        <div class="vi-header-title">Create New User</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
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
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-info-circle"></i> Basic Information</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Full Name <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="vi-form-control @error('name') is-invalid @enderror" 
                           placeholder="John Doe" value="{{ old('name') }}" required>
                    @error('name')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Email Address <span style="color:#EF4444;">*</span></label>
                    <input type="email" name="email" class="vi-form-control @error('email') is-invalid @enderror" 
                           placeholder="user@example.com" value="{{ old('email') }}" required>
                    @error('email')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" class="vi-form-control @error('phone') is-invalid @enderror" 
                           placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                    @error('phone')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="vi-form-control @error('country') is-invalid @enderror" 
                           placeholder="United States" value="{{ old('country') }}">
                    @error('country')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-lock"></i> Security</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Password <span style="color:#EF4444;">*</span></label>
                    <input type="password" name="password" class="vi-form-control @error('password') is-invalid @enderror" 
                           placeholder="••••••••" required>
                    @error('password')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Confirm Password <span style="color:#EF4444;">*</span></label>
                    <input type="password" name="password_confirmation" class="vi-form-control" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="vi-form-group">
                <label>Role <span style="color:#EF4444;">*</span></label>
                <select name="role_id" class="vi-form-control @error('role_id') is-invalid @enderror" required>
                    <option value="">-- Select Role --</option>
                    @foreach(\App\Models\Role::active()->get() as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-toggle-on"></i> Account Status</div>

            <div class="vi-form-group">
                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 0;">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <span style="font-size: 12px; color: #94a3b8; text-transform: none; font-weight: 600; letter-spacing: 0;">
                        Activate user account immediately
                    </span>
                </label>
            </div>

            <div style="background-color: rgba(26,187,156,0.05); padding: 12px; border-radius: 6px; border-left: 3px solid #1ABB9C; font-size: 11px; color: #94a3b8;">
                <i class="fa fa-info-circle" style="color:#1ABB9C;"></i> 
                Active users can log in and access the platform immediately.
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.users.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-times"></i> Cancel
            </a>
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-check-circle"></i> Create User
            </button>
        </div>
    </form>
</div>

@endsection
