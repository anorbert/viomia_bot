@extends('layouts.admin')

@section('title', 'Add New Client — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 700px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 16px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-success-check { color: #22C55E; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 16px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-step { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.vi-step-num { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #1ABB9C 0%, #159d84 100%); color: white; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
.vi-step-content { }
.vi-step-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #f1f5f9; letter-spacing: 0.5px; }
.vi-step-desc { font-size: 11px; color: #4b5563; margin-top: 2px; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👥 Client Management</div>
            <div class="vi-header-title">Add New Client</div>
            <div class="vi-header-sub">Create a new client account with subscription and access credentials</div>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Clients
        </a>
    </div>

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

    <!-- Setup Steps -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-list-check"></i> Setup Steps</div>
        <div class="vi-step">
            <div class="vi-step-num">1</div>
            <div class="vi-step-content">
                <div class="vi-step-label">Personal Information</div>
                <div class="vi-step-desc">Enter client's name, email, and phone number</div>
            </div>
        </div>
        <div class="vi-step">
            <div class="vi-step-num">2</div>
            <div class="vi-step-content">
                <div class="vi-step-label">Security Setup</div>
                <div class="vi-step-desc">Set temporary password for first login</div>
            </div>
        </div>
        <div class="vi-step">
            <div class="vi-step-num">3</div>
            <div class="vi-step-content">
                <div class="vi-step-label">Account Created</div>
                <div class="vi-step-desc">Client receives welcome email with login details</div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="vi-panel">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf

            <!-- Personal Information Section -->
            <div class="vi-panel-title" style="margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07);"><i class="fa fa-user"></i> Personal Information</div>

            <!-- Full Name -->
            <div class="vi-form-group">
                <label class="vi-form-label">
                    Full Name
                    <span class="required">*</span>
                </label>
                <input type="text"
                       name="name"
                       class="vi-form-input @error('name') is-invalid @enderror"
                       placeholder="John Doe"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <div class="vi-error"><i class="fa fa-exclamation-circle vi-success-check"></i> {{ $message }}</div>
                @enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Full legal name of the client</div>
            </div>

            <!-- Email & Phone Row -->
            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">
                        Email Address
                        <span class="required">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           class="vi-form-input @error('email') is-invalid @enderror"
                           placeholder="client@example.com"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Phone Number</label>
                    <input type="tel"
                           name="phone_number"
                           class="vi-form-input"
                           placeholder="+1 (555) 123-4567"
                           value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Optional - for support contact</div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="vi-panel-title" style="margin-top: 24px; margin-bottom: 20px; padding-top: 20px; padding-bottom: 12px; border-top: 1px solid rgba(255,255,255,0.07); border-bottom: 1px solid rgba(255,255,255,0.07);"><i class="fa fa-lock"></i> Security Setup</div>

            <div class="vi-info-box">
                <div class="vi-info-box-title">Password Requirements</div>
                <div class="vi-info-box-text">
                    This is a temporary password. Client will be required to change it on first login.
                </div>
            </div>

            <!-- Password -->
            <div class="vi-form-group">
                <label class="vi-form-label">
                    Temporary Password
                    <span class="required">*</span>
                </label>
                <input type="password"
                       name="password"
                       class="vi-form-input @error('password') is-invalid @enderror"
                       placeholder="Minimum 8 characters"
                       minlength="8"
                       required>
                @error('password')
                    <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Must be at least 8 characters long (Letters, numbers, special characters)</div>
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-check-circle"></i> Create Client Account
                </button>
                <a href="{{ route('admin.clients.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Info Footer -->
    <div style="background: rgba(139,92,246,0.05); border: 1px solid rgba(139,92,246,0.15); border-radius: 8px; padding: 16px; margin-top: 20px; color: #94a3b8; font-size: 12px; line-height: 1.6;">
        <div style="font-weight: 700; color: #A78BFA; margin-bottom: 8px;"><i class="fa fa-lightbulb"></i> What Happens Next?</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 4px 0;">✓ Client account is created immediately</li>
            <li style="padding: 4px 0;">✓ Welcome email is sent with login credentials</li>
            <li style="padding: 4px 0;">✓ Client can start subscribing to plans</li>
            <li style="padding: 4px 0;">✓ Account settings can be modified anytime</li>
        </ul>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) nameInput.focus();
});
</script>
@endpush

