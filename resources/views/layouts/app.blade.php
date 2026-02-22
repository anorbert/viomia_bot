<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Secure Access | Viomia Trading Bot')</title>
    
    <link href="{{ asset('logo.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Using the high-end gradient we discussed */
            background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
            color: #2a3e52;
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Changed to column to accommodate Nav */
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        /* --- Generated Nav Styling --- */
        .navbar-viomia {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-link-custom {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-left: 20px;
            transition: color 0.2s;
        }
        .nav-link-custom:hover { color: #00a884; }
        .nav-link-active { color: #fff; border-bottom: 2px solid #00a884; padding-bottom: 5px; }

        /* --- Card & Form Styling --- */
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 400px;
            padding: 35px;
            border: none;
            margin-top: 60px; /* Space for Nav on mobile */
        }
        
        .form-control {
            height: 48px;
            border-radius: 8px;
            border: 1.5px solid #e1e8ed;
            font-size: 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #00a884;
            box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.1);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #00a884 0%, #008f70 100%);
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            transition: transform 0.2s;
        }
        .btn-primary-custom:hover { transform: translateY(-1px); color: #fff; }
    </style>
</head>
<body>

    <nav class="navbar-viomia">
        <a href="/">
            <img src="{{ asset('logo.png') }}" alt="Viomia" style="height: 35px; filter: brightness(0) invert(1);">
        </a>
        <div>
            <a href="{{ route('login') }}" class="nav-link-custom {{ request()->routeIs('login') ? 'nav-link-active' : '' }}">Login</a>
            <a href="{{ route('register') }}" class="nav-link-custom {{ request()->routeIs('register') ? 'nav-link-active' : '' }}">Register</a>
        </div>
    </nav>

    @yield('content')

    <script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        // Display Validation Errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif

        // Display Session Success/Warning
        @if(session('success')) toastr.success('{{ session('success') }}'); @endif
        @if(session('warning')) toastr.warning('{{ session('warning') }}'); @endif
    </script>
</body>
</html>