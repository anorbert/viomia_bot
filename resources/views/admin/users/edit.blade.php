@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 700px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-select { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; cursor: pointer; }
.vi-form-select:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-status-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-status-active { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-inactive { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-details-item { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 6px; padding: 14px; }
.vi-details-label { font-size: 10px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.vi-details-value { font-size: 13px; color: #f1f5f9; font-weight: 600; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.success-message { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.vi-error-alert { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px; }
.vi-error-list { list-style: none; padding: 0; margin: 0; }
.vi-error-list li { padding: 3px 0; }
.vi-profile-photo { border-radius: 12px; border: 2px solid rgba(26,187,156,0.2); object-fit: cover; width: 140px; height: 140px; }
.vi-profile-section { display: flex; gap: 24px; margin-bottom: 24px; align-items: flex-start; }
.vi-photo-column { display: flex; flex-direction: column; align-items: center; gap: 12px; }
.vi-photo-upload { font-size: 11px; color: #4b5563; font-weight: 700; margin-bottom: 8px; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <!-- Header -->
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👥 User Management</div>
            <div class="vi-header-title">Edit User Profile</div>
            <div class="vi-header-sub">Update user account details and security settings</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Users
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="success-message">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="vi-error-alert">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul class="vi-error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- User Details Panel -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-user-circle"></i> Account Information</div>

        {{-- ── USER LOGIN TRACKING CARD ── --}}
        @include('components.login-info-card', ['user' => $user])

        <!-- User Details Grid -->
        <div class="vi-details-grid" style="margin-bottom: 24px;">
            <div class="vi-details-item">
                <div class="vi-details-label">User ID</div>
                <div class="vi-details-value">{{ $user->id }}</div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Role</div>
                <div class="vi-details-value">{{ $user->role->name ?? 'N/A' }}</div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Member Since</div>
                <div class="vi-details-value"><i class="fa fa-calendar"></i> {{ $user->created_at?->format('M j, Y') ?? '—' }}</div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Last Updated</div>
                <div class="vi-details-value"><i class="fa fa-clock"></i> {{ $user->updated_at?->format('M j, Y') ?? '—' }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="vi-profile-section">
                <div class="vi-photo-column">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="vi-profile-photo">
                    @else
                        <img src="{{ asset('img/bot_logo.png') }}" alt="Default Avatar" class="vi-profile-photo">
                    @endif
                    <div class="vi-photo-upload">Upload Photo</div>
                    <input type="file" name="profile_photo" accept="image/*" class="vi-form-input" style="width: 140px; padding: 4px 6px; font-size: 11px;">
                    <div class="vi-form-hint" style="font-size: 10px; margin-top: 4px;">
                        <i class="fa fa-info-circle"></i> JPG, PNG, Max 5MB
                    </div>
                </div>

                <div style="flex: 1;">
                    <div class="vi-form-row">
                        <div class="vi-form-group" style="grid-column: 1 / -1;">
                            <label class="vi-form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="vi-form-input @error('name') is-invalid @enderror"
                                   placeholder="Enter full name"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="vi-form-group">
                            <label class="vi-form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="vi-form-input @error('email') is-invalid @enderror"
                                   placeholder="user@example.com"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="vi-form-group">
                            <label class="vi-form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone_number" class="vi-form-input @error('phone_number') is-invalid @enderror"
                                   placeholder="+234 (Required)"
                                   value="{{ old('phone_number', $user->phone_number) }}" required>
                            @error('phone_number')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="vi-form-group">
                            <label class="vi-form-label">System Role <span class="required">*</span></label>
                            <select name="role_id" class="vi-form-select @error('role_id') is-invalid @enderror" required>
                                <option value="">Select a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ ($user->role_id == $role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="vi-info-box" style="margin-bottom: 20px;">
                <div class="vi-info-box-title">Password Update</div>
                <div class="vi-info-box-text">Leave blank to keep the current password unchanged</div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">New Password</label>
                    <input type="password" name="password" class="vi-form-input @error('password') is-invalid @enderror"
                           placeholder="••••••••"
                           value="{{ old('password') }}">
                    @error('password')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Minimum 8 characters recommended</div>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="vi-form-input @error('password_confirmation') is-invalid @enderror"
                           placeholder="••••••••"
                           value="{{ old('password_confirmation') }}">
                    @error('password_confirmation')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="vi-btn vi-btn-secondary">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

</div>

@endsection