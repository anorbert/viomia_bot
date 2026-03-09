@extends('layouts.app')

@section('content')
<style>
    .navbar-viomia {
        position: absolute;
        top: 0;
        width: 100%;
        background: transparent !important;
        border-bottom: none !important;
    }

    .navbar-viomia .nav-link-custom {
        color: rgba(255,255,255,0.8) !important;
    }

    .navbar-viomia .nav-link-custom:hover {
        color: #00a884 !important;
    }

    .navbar-viomia .nav-link-active {
        color: white !important;
        border-bottom: 2px solid #00a884 !important;
    }

    body {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Note: Body background is now handled by layouts.app 
       to ensure consistency across Login, Register, and Terms.
    */
    body {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        font-family: 'Inter', -apple-system, sans-serif;
    }
    
    .login-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 380px; 
        padding: 25px 30px;
        border: none;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-header img { 
        max-width: 100px; 
        margin-bottom: 10px;
    }

    .login-header h4 { 
        color: #1a202c; 
        font-weight: 700; 
        margin-bottom: 2px;
        font-size: 19px;
    }

    .login-header p {
        font-size: 12px;
        margin-bottom: 20px;
        color: #718096;
    }

    .form-group label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        color: #a0aec0;
        display: block;
    }

    .form-control {
        height: 42px;
        font-size: 14px;
        border: 1px solid #cbd5e0;
        background-color: #f8fafc;
        transition: all 0.2s;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: #00a884;
        box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .input-group.has-error .form-control {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .input-group.has-error .input-group-text {
        border-color: #dc3545;
        background-color: #ffe6e6;
        color: #dc3545;
    }

    .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e0;
        color: #a0aec0;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
        border: none;
        height: 44px;
        font-weight: 700;
        color: white;
        width: 100%;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 168, 132, 0.3);
        color: white;
    }

    .register-prompt {
        margin-top: 15px;
        font-size: 13px;
        color: #718096;
    }

    .register-link {
        color: #00a884;
        font-weight: 700;
        text-decoration: none;
    }

    .back-link {
        margin-top: 15px;
        font-size: 12px;
        text-align: center;
    }

    .back-link a {
        color: #00a884;
        font-weight: 700;
        text-decoration: none;
    }

    .footer-text {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 20px;
        border-top: 1px solid #edf2f7;
        padding-top: 15px;
    }
</style>

<div class="login-card">
    <div class="login-header text-center">
        <img src="{{ asset('logo.png') }}" alt="Viomia Logo">
        <h4>System Access</h4>
        <p>Viomia Trading Technologies</p>
    </div>

    <form method="POST" action="{{ route('user_login.store') }}">
        @csrf

        {{-- Phone Input --}}
        <div class="form-group mb-3">
            <label class="uppercase">PHONE NUMBER</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="tel" name="phone" class="form-control" placeholder="078xxxxxxx" required value="{{ old('phone') }}" autofocus>
            </div>
        </div>

        {{-- PIN Input --}}
        <div class="form-group mb-3">
            <label class="uppercase">SECURITY PIN</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" name="pin" class="form-control" placeholder="••••" required maxlength="10">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label small text-secondary" for="remember" style="cursor: pointer;">Remember</label>
            </div>
            <a href="#" class="small text-success font-weight-bold">Forgot PIN?</a>
        </div>

        <button type="submit" class="btn btn-gradient shadow-sm">SIGN IN</button>

        <div class="register-prompt text-center">
            New here? <a href="{{ route('user_register') }}" class="register-link">Create Account</a>
        </div>

        <div class="back-link">
            <a href="/">← Back to Home</a>
        </div>

        <div class="footer-text text-center">
            <p class="mb-1">© {{ date('Y') }} Viomia Trading Technologies</p>
            <span class="small"><i class="fa fa-check-circle text-success"></i> Secure Connection</span>
        </div>
    </form>
</div>

<script>
    // Display validation errors as Toastr notifications and highlight error fields
    document.addEventListener('DOMContentLoaded', function() {
        // Map field names to input field selectors
        const errorFields = {
            'phone': 'input[name="phone"]',
            'pin': 'input[name="pin"]'
        };

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', 'Validation Error', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000
                });
            @endforeach

            // Highlight error fields with red border
            @foreach($errors->keys() as $key)
                const selector = errorFields['{{ $key }}'];
                if (selector) {
                    const field = document.querySelector(selector);
                    if (field) {
                        field.classList.add('is-invalid');
                        field.closest('.input-group')?.classList.add('has-error');
                    }
                }
            @endforeach
        @endif
        
        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
        
        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Success', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
    });
</script>
@endsection
