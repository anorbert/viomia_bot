<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Viomia Trading Bot')</title>

    <link href="{{ asset('logo.png') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --phx-bg: #f5f7fa;
            --phx-sidebar-width: 280px; /* Expanded sidebar for better menu visibility */
            --phx-primary: #3874ff;
            --phx-border: #e3e6ed;
            --phx-text: #141824;
            --phx-text-muted: #5e6e82;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--phx-bg);
            color: var(--phx-text);
            margin: 0;
            font-size: 12px; /* Micro-UI Base */
            overflow: hidden;
        }

        .phx-main-wrapper { display: flex; height: 100vh; width: 100vw; }

        .phx-sidebar {
            width: var(--phx-sidebar-width);
            min-width: var(--phx-sidebar-width);
            background: #fff;
            border-right: 1px solid var(--phx-border);
            height: 100%;
            overflow-y: auto;
            padding: 1rem;
        }

        .phx-content-container {
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .phx-navbar {
            height: 55px; /* Shorter Navbar */
            background: #fff;
            border-bottom: 1px solid var(--phx-border);
            /*display: flex;*/
            align-items: center;
            padding: 0 1.5rem;
            flex-shrink: 0;
        }

        .phx-page-content { padding: 1.5rem; flex-grow: 1; }
        .content-limit { max-width: 1400px; margin: 0 auto; width: 100%; }

        /* UI Micro Components */
        .phx-card {
            background: #fff;
            border: 1px solid var(--phx-border);
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            margin-bottom: 1rem;
        }

        .text-micro {
            font-size: 10px !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 800;
            color: var(--phx-text-muted);
        }

        .badge-phx {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }
        
        /* Pulse for Live indicator */
        .pulse {
            height: 6px; width: 6px; border-radius: 50%; display: inline-block;
            background: #25b89a; margin-right: 4px;
            animation: pulse-op 2s infinite;
        }
        @keyframes pulse-op { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        /* Scrollbar styling for a cleaner look */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="phx-main-wrapper">
        <aside class="phx-sidebar">
            @include('partials.users.user_sidebar')
        </aside>

        <main class="phx-content-container">
            <nav class="phx-navbar">
                @include('partials.users.topnav')
            </nav>

            <div class="phx-page-content">
                <div class="content-limit">
                    @yield('content')
                </div>
                @include('partials.users.footer')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>