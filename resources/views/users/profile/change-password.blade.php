@extends('layouts.user')

@section('content')
<style>
    /* Change Password Page Styling */
    .ln-card { 
        background: #fff; border-radius: 10px; border: 1px solid #e0e0e0; 
        margin-bottom: 15px; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.05); 
    }
    
    .form-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a939f;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #1ABB9C;
        box-shadow: 0 0 0 3px rgba(26, 187, 156, 0.1);
    }
    
    .password-strength {
        height: 4px;
        border-radius: 4px;
        margin-top: 6px;
        transition: all 0.3s ease;
    }
    
    .strength-weak { background: #dc3545; }
    .strength-fair { background: #ffc107; }
    .strength-strong { background: #28a745; }
    
    .requirement-item {
        font-size: 12px;
        padding: 6px 0;
        color: #8a939f;
    }
    
    .requirement-item.met {
        color: #28a745;
    }
    
    .requirement-item i {
        margin-right: 6px;
        width: 14px;
        text-align: center;
    }
    
    .info-box {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%);
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 10px;
        border-left: 3px solid #1ABB9C;
    }
    
    .info-box small {
        color: #2A3F54;
        font-size: 11px;
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
    }
    
    .info-box span {
        color: #8a939f;
        font-size: 10px;
        display: block;
    }
</style>

<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
            <h3 style="font-weight: 700; color: #2A3F54; margin: 0; font-size: 20px;">
                <i class="fa fa-lock text-primary mr-2"></i>Change Password
            </h3>
        </div>
        <div class="col-md-6 col-12 text-md-right mt-3 mt-md-0">
            <a href="{{ route('user.profile.index') }}" style="color: #1ABB9C; font-weight: 600; text-decoration: none;">
                <i class="fa fa-arrow-left mr-1"></i> Back to Profile
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="ln-card" style="padding: 12px 15px; background: #fee2e2; border-left: 3px solid #dc3545; margin-bottom: 15px;">
            <div style="color: #b91c1c; font-size: 12px; font-weight: 600;">
                <i class="fa fa-exclamation-circle mr-2"></i>Validation Errors
            </div>
            <ul style="margin: 6px 0 0 20px; padding: 0; color: #7f1d1d; font-size: 11px;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 3px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="ln-card" style="padding: 12px 15px; background: #dcfce7; border-left: 3px solid #28a745; margin-bottom: 15px;">
            <div style="color: #15803d; font-size: 12px; font-weight: 600;">
                <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form action="{{ route('user.profile.update-password', Auth::user()->id) }}" method="POST" class="ln-card" id="form-password">
                @csrf
                @method('POST')
                
                <div style="padding: 20px;">
                    {{-- Current Password --}}
                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               name="current_password" id="current_password" required>
                        @error('current_password')
                            <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr style="border-top: 1px solid #e0e0e0; margin: 15px 0;">

                    {{-- New Password --}}
                    <div style="margin-bottom: 15px;">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" id="new_password" required onkeyup="checkPassword();">
                        
                        <!-- Password Strength Meter -->
                        <div id="strength-meter" style="display: none; margin-top: 6px;">
                            <div class="password-strength" id="strength-bar"></div>
                            <small id="strength-text" style="font-size: 11px; margin-top: 3px; display: block; font-weight: 600;"></small>
                        </div>

                        @error('password')
                            <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               name="password_confirmation" id="confirm_password" required>
                        @error('password_confirmation')
                            <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </form>

            {{-- Password Requirements --}}
            <div class="ln-card">
                <div style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); color: #fff; padding: 12px 15px; border-radius: 10px 10px 0 0;">
                    <h5 style="font-weight: 700; margin: 0; font-size: 13px;">
                        <i class="fa fa-shield mr-2"></i>Password Requirements
                    </h5>
                </div>
                <div style="padding: 15px;">
                    <div class="requirement-item" id="req-length">
                        <i class="fa fa-times"></i>At least 8 characters
                    </div>
                    <div class="requirement-item" id="req-uppercase">
                        <i class="fa fa-times"></i>One uppercase letter (A-Z)
                    </div>
                    <div class="requirement-item" id="req-lowercase">
                        <i class="fa fa-times"></i>One lowercase letter (a-z)
                    </div>
                    <div class="requirement-item" id="req-number">
                        <i class="fa fa-times"></i>One number (0-9)
                    </div>
                    <div class="requirement-item" id="req-special">
                        <i class="fa fa-times"></i>One special character (!@#$%^&*)
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            {{-- Security Tips --}}
            <div class="ln-card">
                <div style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: #fff; padding: 12px 15px; border-radius: 10px 10px 0 0;">
                    <h5 style="font-weight: 700; margin: 0; font-size: 13px;">
                        <i class="fa fa-lightbulb mr-2"></i>Security Tips
                    </h5>
                </div>
                <div style="padding: 15px;">
                    <div class="info-box">
                        <small><i class="fa fa-check-circle"></i> Use unique passwords</small>
                        <span>Don't reuse passwords from other accounts</span>
                    </div>
                    <div class="info-box">
                        <small><i class="fa fa-check-circle"></i> Avoid personal info</small>
                        <span>Don't use your name, email, or birth date</span>
                    </div>
                    <div class="info-box">
                        <small><i class="fa fa-check-circle"></i> Mix character types</small>
                        <span>Use uppercase, lowercase, numbers, and symbols</span>
                    </div>
                    <div class="info-box">
                        <small><i class="fa fa-check-circle"></i> Keep it secret</small>
                        <span>Never share your password with anyone</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="ln-card">
                <div style="padding: 15px;">
                    <button type="submit" form="form-password" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px; margin-bottom: 8px;">
                        <i class="fa fa-save mr-2"></i>Update Password
                    </button>
                    <a href="{{ route('user.profile.index') }}" class="btn btn-outline-secondary w-100" style="color: #8a939f; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px;">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function checkPassword() {
    const password = document.getElementById('new_password').value;
    
    // Check requirements
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };
    
    // Update requirement display
    updateRequirementUI('req-length', requirements.length);
    updateRequirementUI('req-uppercase', requirements.uppercase);
    updateRequirementUI('req-lowercase', requirements.lowercase);
    updateRequirementUI('req-number', requirements.number);
    updateRequirementUI('req-special', requirements.special);
    
    // Update strength meter
    updateStrengthMeter(password, requirements);
}

function updateRequirementUI(elementId, met) {
    const element = document.getElementById(elementId);
    if (met) {
        element.classList.add('met');
        element.innerHTML = element.innerHTML.replace('fa-times', 'fa-check');
    } else {
        element.classList.remove('met');
        element.innerHTML = element.innerHTML.replace('fa-check', 'fa-times');
    }
}

function updateStrengthMeter(password, requirements) {
    const meter = document.getElementById('strength-meter');
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    
    if (password.length === 0) {
        meter.style.display = 'none';
        return;
    }
    
    meter.style.display = 'block';
    
    // Calculate strength
    let strength = 0;
    if (requirements.length) strength += 20;
    if (requirements.uppercase) strength += 20;
    if (requirements.lowercase) strength += 20;
    if (requirements.number) strength += 20;
    if (requirements.special) strength += 20;
    
    bar.style.width = strength + '%';
    
    if (strength <= 40) {
        bar.className = 'password-strength strength-weak';
        text.textContent = '⚠️ Weak - Add more variety';
        text.style.color = '#dc3545';
    } else if (strength <= 70) {
        bar.className = 'password-strength strength-fair';
        text.textContent = '⚡ Fair - Good progress';
        text.style.color = '#ffc107';
    } else {
        bar.className = 'password-strength strength-strong';
        text.textContent = '✓ Strong - Excellent!';
        text.style.color = '#28a745';
    }
}
</script>

@endsection
