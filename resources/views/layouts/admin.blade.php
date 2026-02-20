<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', config('app.name', 'Viomia Trading Bot'))</title>

    <link href="{{ asset('logo.png') }}" rel="icon">

    <link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    
    {{-- ADDED: DataTables CSS (Gentelella Vendors) --}}
    <link href="{{ asset('gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('gentelella/build/css/custom.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* --- Typography: Google Sans Flex --- */
        @font-face {
            font-family: 'Google Sans Flex';
            src: local('Google Sans Flex'), local('Google Sans');
            font-display: swap;
        }

        body, .nav-md, .container.body, .right_col, h1, h2, h3, h4, h5, h6, .site_title, .nav.side-menu > li > a {
            font-family: "Google Sans Flex", "Google Sans", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        }

        .left_col, .nav.side-menu > li > a {
            font-weight: 500;
            letter-spacing: 0.1px;
        }

        .site_title span {
            font-weight: 700 !important;
            letter-spacing: -0.5px;
        }

        .animate-pulse {
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        #preloader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: #fff;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #dc3545; /* RED LOADER TO MATCH BUTTONS */
            border-radius: 50%;
            width: 45px;
            height: 45px;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        #toast-container > div { opacity: 1; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; }
    </style>

    @stack('styles')
</head>

<body class="nav-md">
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="container body">
        <div class="main_container">
            @include('partials.sidebar')
            @include('partials.topnav')

            <div class="right_col" role="main" style="min-height: 100vh;">
                @yield('content')
            </div>

            @include('partials.footer')
        </div>
    </div>

    <script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    
    {{-- ADDED: DataTables JS (Gentelella Vendors) --}}
    <script src="{{ asset('gentelella/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(window).on('load', function() {
            $('#preloader').fadeOut('slow');
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        @if(Session::has('success')) toastr.success("{{ Session::get('success') }}"); @endif
        @if(Session::has('error')) toastr.error("{{ Session::get('error') }}"); @endif
        @if(Session::has('info')) toastr.info("{{ Session::get('info') }}"); @endif
        @if(Session::has('warning')) toastr.warning("{{ Session::get('warning') }}"); @endif
    </script>

    <script src="{{ asset('gentelella/build/js/custom.min.js') }}"></script>

    <script>
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        }
    </script>

    @stack('scripts')
</body>
</html>