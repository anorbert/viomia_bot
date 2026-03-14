<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https: data:;">
    <meta name="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Secure Access | Viomia Trading Bot')</title>
    
    <link href="{{ asset('logo.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #2a3e52;
            margin: 0;
            background: #ffffff;
            padding-top: 80px;
        }

        /* --- Generated Nav Styling --- */
        .navbar-viomia {
            width: 100%;
            padding: 20px 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 80px;
            z-index: 999;
            background: #0f0f0f;
            border-bottom: 1px solid #2a2a2a;
            position: fixed;
            top: 0;
        }

        .navbar-viomia > a {
            margin-right: auto;
        }

        .navbar-viomia > div {
            display: flex;
            gap: 60px;
        }

        .nav-link-custom {
            color: #b0b0b0;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s;
            white-space: nowrap;
        }
        .nav-link-custom:hover { color: #00a884; }
        .nav-link-active { color: #ffffff; border-bottom: 2px solid #00a884; padding-bottom: 5px; }

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
            border-color: #006d5b;
            box-shadow: 0 0 0 3px rgba(0, 109, 91, 0.1);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            transition: transform 0.2s;
        }
        .btn-primary-custom:hover { transform: translateY(-1px); color: #fff; }

        /* --- Mobile Responsive --- */
        @media (max-width: 1200px) {
            .navbar-viomia {
                padding: 15px 30px;
                gap: 40px;
            }

            .navbar-viomia > div {
                gap: 30px;
            }

            .nav-link-custom {
                font-size: 13px;
            }
        }

        @media (max-width: 992px) {
            .navbar-viomia {
                padding: 15px 20px;
                gap: 20px;
                flex-wrap: wrap;
            }

            .navbar-viomia > a {
                margin-right: 0;
            }

            .navbar-viomia > div {
                gap: 15px;
                width: 100%;
                justify-content: center;
            }

            .nav-link-custom {
                font-size: 12px;
            }

            body {
                padding-top: 120px;
            }
        }

        @media (max-width: 768px) {
            .navbar-viomia {
                padding: 12px 15px;
                gap: 10px;
                flex-direction: column;
            }

            .navbar-viomia > a {
                margin-bottom: 10px;
            }

            .navbar-viomia > div {
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-link-custom {
                font-size: 11px;
                padding: 5px 8px;
            }

            body {
                padding-top: 160px;
            }
        }

        @media (max-width: 576px) {
            .navbar-viomia {
                padding: 10px 10px;
            }

            .navbar-viomia > div {
                gap: 8px;
            }

            .nav-link-custom {
                font-size: 10px;
                padding: 4px 6px;
            }

            body {
                padding-top: 140px;
            }

            .login-card {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar-viomia">
        <a href="/">
            <img src="{{ asset('logo.png') }}" alt="Viomia" style="height: 35px; filter: brightness(0) invert(1);">
        </a>
        <div>
            <a href="/#features" class="nav-link-custom">Features</a>
            <a href="/#how-works" class="nav-link-custom">How It Works</a>
            <a href="#pricing" class="nav-link-custom">Pricing</a>
            <a href="/help" class="nav-link-custom">Help & Support</a>
            <a href="/technology" class="nav-link-custom">Technology</a>
            <a href="/risk-disclosure" class="nav-link-custom">Risk Disclosure</a>
            <a href="#contact" class="nav-link-custom">Contact</a>
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

    <!-- Auto-Logout Activity Tracker (10 minute inactivity timeout) -->
    <script src="{{ asset('js/activity-tracker.js') }}"></script>
</body>
</html>