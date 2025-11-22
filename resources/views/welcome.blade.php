@extends('layouts.app')

@section('content')
<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form method="POST" action="{{ route('user_login.store') }}">
                        @csrf
                        <h1>Login</h1>

                        {{-- Phone Input --}}
                        <div>
                            <input 
                                type="tel" 
                                name="phone" 
                                class="form-control" 
                                placeholder="Phone (e.g. 078xxxxxxx)" 
                                required 
                                value="{{ old('phone') }}" 
                                style="height: 50px; font-size: 18px;"
                            />
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- PIN Input --}}
                        <div>
                            <input 
                                type="password" 
                                name="pin" 
                                class="form-control" 
                                placeholder="4-digit PIN" 
                                required 
                                maxlength="4" 
                                pattern="\d{4}" 
                                title="Enter exactly 4 digits"
                                style="height: 50px; font-size: 18px;" 
                            />
                            @error('pin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-check text-left" style="margin-top: 10px; margin-bottom: 10px;">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        {{-- Submit --}}
                        <div>
                            <button 
                                type="submit" 
                                class="btn btn-primary submit" 
                                style="height: 50px; font-size: 18px;"
                            >
                                Log in
                            </button>
                            <a class="reset_pass" href="#">Lost your password?</a>
                        </div>

                        <div class="clearfix"></div>

                        {{-- Footer --}}
                        <div class="separator">
                            <br />
                            <div>
                                <h1><i class="fa fa-university"></i> Parking System</h1>
                                <p>© {{ date('Y') }} All Rights Reserved. Privacy and Terms</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
@endsection
