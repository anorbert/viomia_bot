@extends('layouts.app')
@section('content')
    <body class="login">
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form method="POST" action="{{ route('user_login.update',$user->id) }}">
                        @csrf
                        @method('PUT')
                        <h1> Change Password</h1>

                        <div class="form-group">
                            <input id="current_password" value="{{ old('current_password') }}" type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                name="current_password" required placeholder="Enter your current password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <input id="new_password" value="{{ old('new_password') }}" type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                name="new_password" required placeholder="Enter your new password">
                            @error('new_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <input id="new_password_confirmation" value="{{ old('new_password_confirmation') }}" type="password" class="form-control" name="new_password_confirmation" required placeholder="Confirm your new password">
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Update Password</button>
                    </form>

                    <div class="clearfix"></div>

                    {{-- Footer --}}
                    <div class="separator">
                        <br />
                        <div>
                            <h1><i class="fa fa-university"></i> Parking System</h1>
                            <p>© {{ date('Y') }} All Rights Reserved. Privacy and Terms</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </body>
@endsection
