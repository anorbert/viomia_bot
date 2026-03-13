@extends('layouts.app')

@section('title', 'Register | Viomia Trading Intelligence')

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

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23a0aec0' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 30px;
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

    .btn-gradient:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 168, 132, 0.3);
        color: white;
    }
    
    .btn-gradient:disabled {
        background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
        cursor: not-allowed;
        opacity: 0.6;
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
                <input type="text" name="name" class="form-control" 
                       placeholder="Enter full name" value="{{ old('name') }}" required autofocus>
            </div>
        </div>

        {{-- Phone Number with Country Code --}}
        <div class="form-group mb-3">
            <label>Country & Phone Number</label>
            <div class="row">
                <div class="col-4">
                    <select name="country_code" class="form-control" required id="countryCode">
                        <option value="">Select Country</option>
                        <option value="+250" {{ old('country_code') === '+250' ? 'selected' : '' }}>Rwanda (+250)</option>
                        <option value="+257" {{ old('country_code') === '+257' ? 'selected' : '' }}>Burundi (+257)</option>
                        <option value="+243" {{ old('country_code') === '+243' ? 'selected' : '' }}>DRC (+243)</option>
                        <option value="+33" {{ old('country_code') === '+33' ? 'selected' : '' }}>France (+33)</option>
                        <option value="+44" {{ old('country_code') === '+44' ? 'selected' : '' }}>UK (+44)</option>
                        <option value="+1" {{ old('country_code') === '+1' ? 'selected' : '' }}>USA (+1)</option>
                    </select>
                </div>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        </div>
                        <input type="tel" name="phone_number" class="form-control" 
                               placeholder="e.g. 788275364" value="{{ old('phone_number') }}" required pattern="[0-9]{9,10}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid for PINs to keep height short --}}
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-3">
                    <label>Security PIN (4-6 digits)</label>
                    <input type="password" name="pin" class="form-control" 
                           placeholder="••••" required minlength="4" maxlength="6" pattern="[0-9]{4,6}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-3">
                    <label>Confirm PIN</label>
                    <input type="password" name="pin_confirmation" class="form-control" 
                           placeholder="••••" required minlength="4" maxlength="6" pattern="[0-9]{4,6}">
                </div>
            </div>
        </div>
        {{-- Subscription Plan Selection (Optional) --}}
        <div class="form-group mb-3">
            <label>Choose Subscription Plan (Optional)</label>
            <select name="subscription_plan_id" class="form-control">
                <option value="">Start with Free Demo Plan</option>
                @php
                    $plans = \App\Models\SubscriptionPlan::where('active', true)->get();
                @endphp
                @forelse($plans as $plan)
                    @if($plan->price > 0)
                        <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - {{ $plan->currency }} {{ number_format($plan->price, 2) }}
                        </option>
                    @endif
                @empty
                @endforelse
            </select>
            <small class="text-muted" style="display: block; margin-top: 4px;">You can always upgrade or change your plan later</small>
        </div>

        {{-- Terms and Conditions Checkbox --}}
        <div class="custom-control custom-checkbox mb-4">
            <input type="checkbox" class="custom-control-input" id="terms" name="terms" required>
            <label class="custom-control-label small text-muted" for="terms" style="cursor: pointer;">
                I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-success font-weight-bold">Terms & Conditions</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-success font-weight-bold">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" id="registerBtn" class="btn btn-gradient shadow-sm" disabled>REGISTER ACCOUNT</button>

        <div class="login-prompt">
            Already have an account? <a href="{{ route('login') }}" class="login-link">Login here</a>
        </div>

        <div class="footer-text">
            <p class="mb-1">© {{ date('Y') }} Viomia Trading Technologies</p>
            <span class="small"><i class="fa fa-shield text-success"></i> Data Encryption Active</span>
        </div>
    </form>
</div>

<script>
    // Display validation errors as Toastr notifications and highlight error fields
    document.addEventListener('DOMContentLoaded', function() {
        // Map field names to input field selectors
        const errorFields = {
            'name': 'input[name="name"]',
            'phone_number': 'input[name="phone_number"]',
            'pin': 'input[name="pin"]',
            'pin_confirmation': 'input[name="pin_confirmation"]',
            'terms': 'input[name="terms"]'
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
        
        @if(session('warning'))
            toastr.warning('{{ session('warning') }}', 'Warning', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
        
        // Handle Register button enable/disable based on terms checkbox
        const termsCheckbox = document.getElementById('terms');
        const registerBtn = document.getElementById('registerBtn');
        
        if (termsCheckbox && registerBtn) {
            // Enable/disable button on checkbox change
            termsCheckbox.addEventListener('change', function() {
                registerBtn.disabled = !this.checked;
            });
            
            // Set initial state
            registerBtn.disabled = !termsCheckbox.checked;
        }
    });
</script>
@endsection