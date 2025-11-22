@extends('layouts.app')

@section('content')
<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form method="POST" action="">
                        @csrf
                        <h1>Login</h1>

                        <div>
                            <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <input type="password" name="password" class="form-control" placeholder="Password" required />
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary submit">Log in</button>
                            <a class="reset_pass" href="">Lost your password?</a>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <p class="change_link">New to site?
                                <a href="" class="to_register"> Create Account </a>
                            </p>

                            <div class="clearfix"></div>
                            <br />

                            <div>
                                <h1><i class="fa fa-university"></i> Prison Fellowship Rwanda</h1>
                                <p>© {{ date('Y') }} All Rights Reserved. PFR. Privacy and Terms</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
@endsection
