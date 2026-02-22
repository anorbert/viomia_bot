@extends('layouts.app')

@section('title', 'Register | Viomia Trading Intelligence')

@section('content')
<style>
    body {
        /* Matching the Login Page Gradient */
        /*background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);*/
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        font-family: 'Inter', -apple-system, sans-serif;
        padding: 20px; /* Padding for mobile view */
    }
    
    .register-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 420px; /* Slightly wider for longer inputs */
        padding: 25px 30px;
        border: none;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-section img { 
        max-width: 90px; 
        margin-bottom: 10px;
    }

    .header-section h4 { 
        color: #1a202c; 
        font-weight: 700; 
        margin-bottom: 2px;
        font-size: 20px;
    }

    .header-section p {
        font-size: 13px;
        margin-bottom: 20px;
        color: #718096;
    }

    /* Professional Form Controls */
    .form-group label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        color: #a0aec0;
        display: block;
        text-transform: uppercase;
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

    .input-group-text {
        background-color: #f8fafc;
        border-color: #cbd5e0;
        color: #a0aec0;
        font-size: 14px;
    }

    /* Gradient Submit Button */
    .btn-gradient {
        background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
        border: none;
        height: 46px;
        font-weight: 700;
        color: white;
        width: 100%;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 168, 132, 0.3);
        color: white;
    }

    .login-prompt {
        margin-top: 15px;
        font-size: 13px;
        color: #718096;
        text-align: center;
    }

    .login-link {
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
        text-align: center;
    }
</style>

<div class="register-card">
    <div class="header-section text-center">
        <img src="{{ asset('logo.png') }}" alt="Viomia Logo">
        <h4>Create Account</h4>
        <p>Join Viomia Trading Intelligence</p>
    </div>

    <form method="POST" action="{{ route('user_register.store') }}">
        @csrf

        {{-- Full Name --}}
        <div class="form-group mb-3">
            <label>Full Name</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="Enter full name" value="{{ old('name') }}" required autofocus>
            </div>
            @error('name') <small class="text-danger mt-1 d-block" style="font-size: 11px;">{{ $message }}</small> @enderror
        </div>

        {{-- Phone Number --}}
        <div class="form-group mb-3">
            <label>Phone Number</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                       placeholder="e.g. 078xxxxxxx" value="{{ old('phone_number') }}" required>
            </div>
            @error('phone_number') <small class="text-danger mt-1 d-block" style="font-size: 11px;">{{ $message }}</small> @enderror
        </div>

        {{-- Grid for PINs to keep height short --}}
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-3">
                    <label>Security PIN</label>
                    <input type="password" name="pin" class="form-control @error('pin') is-invalid @enderror" 
                           placeholder="••••" required maxlength="10" pattern="\d{10}">
                    @error('pin') <small class="text-danger mt-1 d-block" style="font-size: 11px;">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-3">
                    <label>Confirm PIN</label>
                    <input type="password" name="pin_confirmation" class="form-control" 
                           placeholder="••••" required maxlength="10" pattern="\d{10}">
                </div>
            </div>
        </div>
        {{-- Terms and Conditions Checkbox --}}
<div class="custom-control custom-checkbox mb-4">
    <input type="checkbox" class="custom-control-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
    <label class="custom-control-label small text-muted" for="terms" style="cursor: pointer;">
        I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-success font-weight-bold">Terms & Conditions</a> and Privacy Policy.
    </label>
    @error('terms')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>

<button type="submit" class="btn btn-gradient shadow-sm">REGISTER ACCOUNT</button>

        <div class="login-prompt">
            Already have an account? <a href="{{ route('login') }}" class="login-link">Login here</a>
        </div>

        <div class="footer-text">
            <p class="mb-1">© {{ date('Y') }} Viomia Trading Technologies</p>
            <span class="small"><i class="fa fa-shield text-success"></i> Data Encryption Active</span>
        </div>
    </form>
</div>
@endsection