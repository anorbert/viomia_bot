@extends('layouts.general')

@section('content')
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh; padding: 40px 20px;">
    <div class="container" style="max-width: 700px;">
        <a href="{{ route('user.profile.index') }}" class="btn btn-light btn-sm mb-4 shadow-sm" style="border-radius: 20px;">
            <i class="fa fa-arrow-left mr-2"></i> Back to Profile
        </a>

        <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h3 class="mb-0 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-lock mr-3" style="font-size: 24px;"></i> Change Password
                </h3>
                <p class="mt-2 mb-0" style="opacity: 0.9; font-size: 14px;">Secure your account with a strong new password</p>
            </div>

            <div class="card-body p-5">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border: none; margin-bottom: 20px;">
                        <h5 class="alert-heading mb-3"><i class="fa fa-exclamation-circle mr-2"></i> Validation Errors</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li class="mb-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="top: 12px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border: none; margin-bottom: 20px;">
                        <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card mb-4" style="background: #fef3f5; border: none; border-radius: 10px; border-left: 4px solid #f5576c;">
                    <div class="card-body p-3">
                        <h6 class="mb-3 font-weight-bold" style="color: #2c3e50;"><i class="fa fa-shield mr-2"></i> Password Requirements</h6>
                        <ul class="mb-0 pl-3" style="list-style: none;">
                <form action="{{ route('user.profile.update-password', Auth::user()->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <!-- Current Password -->
                    <div class="form-group mb-4">
                        <label for="current_password" class="font-weight-bold" style="color: #2c3e50;">
                            <i class="fa fa-key mr-2"></i> Current Password
                        </label>
                        <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required
                                   style="padding: 12px 16px; border: 1px solid #e5e5e5; border-radius: 8px;">
                            <div class="input-group-append">
                                <button class="btn" type="button" onclick="togglePassword('current_password')" 
                                        style="background: white; border: 1px solid #e5e5e5; color: #f5576c; border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="fa fa-eye" id="icon-current"></i>
                                </button>
                            </div>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group mb-4">
                        <label for="password" class="font-weight-bold" style="color: #2c3e50;">
                            <i class="fa fa-lock mr-2"></i> New Password
                        </label>
                        <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required onkeyup="checkPasswordRequirements(); checkPasswordStrength();"
                                   style="padding: 12px 16px; border: 1px solid #e5e5e5; border-radius: 8px;">
                            <div class="input-group-append">
                                <button class="btn" type="button" onclick="togglePassword('password')" 
                                        style="background: white; border: 1px solid #e5e5e5; color: #f5576c; border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="fa fa-eye" id="icon-password"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror

                        <!-- Password Strength Meter -->
                        <div id="strength-meter" class="mt-3" style="display: none;">
                            <small class="text-muted d-block mb-2">Password Strength:</small>
                            <div class="progress" style="height: 8px; border-radius: 4px;">
                                <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%; transition: all 0.3s ease; border-radius: 4px;"></div>
                            </div>
                            <small id="strength-text" class="d-block mt-2" style="font-weight: bold;"></small>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-5">
                        <label for="password_confirmation" class="font-weight-bold" style="color: #2c3e50;">
                            <i class="fa fa-check-circle mr-2"></i> Confirm Password
                        </label>
                        <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required
                                   style="padding: 12px 16px; border: 1px solid #e5e5e5; border-radius: 8px;">
                            <div class="input-group-append">
                                <button class="btn" type="button" onclick="togglePassword('password_confirmation')" 
                                        style="background: white; border: 1px solid #e5e5e5; color: #f5576c; border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="fa fa-eye" id="icon-confirm"></i>
                                </button>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Security Tips -->
                    <div class="card mb-4" style="background: #f0f8ff; border: none; border-radius: 10px; border-left: 4px solid #4facfe;">
                        <div class="card-body p-3">
                            <h6 class="mb-3 font-weight-bold" style="color: #2c3e50;"><i class="fa fa-lightbulb mr-2" style="color: #ffc107;"></i> Security Tips</h6>
                            <ul class="mb-0 pl-3" style="list-style: none; font-size: 13px;">
                                <li class="mb-2" style="color: #555;"><i class="fa fa-shield mr-2"></i> Use a unique password you haven't used before</li>
                                <li class="mb-2" style="color: #555;"><i class="fa fa-shield mr-2"></i> Avoid using personal information in your password</li>
                                <li style="color: #555;"><i class="fa fa-shield mr-2"></i> Change your password regularly for better security</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row" style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #e5e5e5;">
                        <div class="col-sm-6 mb-2">
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('icon-' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordRequirements() {
    const password = document.getElementById('password').value;
    
    const requirements = {
        length: password.length >= 8,
        case: /[a-z]/.test(password) && /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^a-zA-Z0-9]/.test(password)
    };
    
    updateRequirement('req-length', requirements.length);
    updateRequirement('req-case', requirements.case);
    updateRequirement('req-number', requirements.number);
    updateRequirement('req-special', requirements.special);
}

function updateRequirement(elementId, met) {
    const element = document.getElementById(elementId);
    if (met) {
        element.style.color = '#28a745';
    } else {
        element.style.color = '#666';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthMeter = document.getElementById('strength-meter');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    if (password.length === 0) {
        strengthMeter.style.display = 'none';
        return;
    }
    
    strengthMeter.style.display = 'block';
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const percentage = (strength / 6) * 100;
    strengthBar.style.width = percentage + '%';
    
    const strengthEmojis = ['⚠️', '🔓', '🔑', '✓'];
    
    if (percentage <= 33) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = '⚠️ Weak - Please add more characters and variety';
        strengthText.className = 'text-danger';
    } else if (percentage <= 66) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = '⚡ Fair - Good, but could be stronger';
        strengthText.className = 'text-warning';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = '✓ Strong - Excellent password!';
        strengthText.className = 'text-success';
    }
}
</script>

<style>
    .form-control:focus {
        border-color: #f5576c !important;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.15) !important;
    }
    
    .form-control {
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        border-color: #f5576c;
        color: #f5576c;
    }

    .form-control.is-invalid {
        border-color: #dc3545
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Strong';
        strengthText.className = 'text-success';
    }
}
</script>

<style>
    .bg-gradient-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .rounded-lg {
        border-radius: 8px;
    }
    
    .form-control:focus,
    .btn-outline-secondary:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
    }
</style>
@endsection
