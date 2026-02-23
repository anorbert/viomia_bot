@extends('app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-danger p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fa fa-lock mr-2"></i> Change Password
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fa fa-exclamation-circle mr-2"></i> Validation Errors</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fa fa-info-circle mr-2"></i>
                        <strong>Password Requirements:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Minimum 8 characters</li>
                            <li>Must contain uppercase and lowercase letters</li>
                            <li>Should include numbers and special characters</li>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('user.profile.update-password', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- Current Password -->
                        <div class="form-group mb-3">
                            <label for="current_password" class="font-weight-bold">
                                <i class="fa fa-key mr-2"></i> Current Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="form-group mb-3">
                            <label for="password" class="font-weight-bold">
                                <i class="fa fa-lock mr-2"></i> New Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required onchange="checkPasswordStrength()">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="strength-meter" class="mt-2" style="display: none;">
                                <small class="text-muted">Password Strength:</small>
                                <div class="progress" style="height: 6px;">
                                    <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="strength-text" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="font-weight-bold">
                                <i class="fa fa-check-circle mr-2"></i> Confirm Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger btn-block rounded-lg font-weight-bold">
                                    <i class="fa fa-save mr-2"></i> Update Password
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('user.profile.index') }}" class="btn btn-outline-secondary btn-block rounded-lg font-weight-bold">
                                    <i class="fa fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
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
    
    if (percentage <= 33) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Weak';
        strengthText.className = 'text-danger';
    } else if (percentage <= 66) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Fair';
        strengthText.className = 'text-warning';
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
