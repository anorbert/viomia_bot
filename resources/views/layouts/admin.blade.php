<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', config('app.name', 'Viomia Trading Bot'))</title>

    <link href="{{ asset('logo.png') }}" rel="icon">

    {{-- Core --}}
    <link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    {{-- DataTables --}}
    <link href="{{ asset('gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

    {{-- Gentelella theme --}}
    <link href="{{ asset('gentelella/build/css/custom.min.css') }}" rel="stylesheet">

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- FullCalendar --}}
    <link href="{{ asset('gentelella/vendors/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('gentelella/vendors/fullcalendar/dist/fullcalendar.print.css') }}" rel="stylesheet" media="print">

    {{-- Select2 --}}
    <link href="{{ asset('gentelella/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">

    <style>
        /* ── VIOMIA ADMIN — GLOBAL LAYOUT OVERRIDES ── */

        /* Font stack */
        body, .nav-md, .container.body, .right_col,
        h1, h2, h3, h4, h5, h6,
        .site_title, .nav.side-menu > li > a,
        input, button, select, textarea {
            font-family: 'DM Sans', 'Google Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }

        /* ── PRELOADER ── */
        #preloader {
            position: fixed;
            inset: 0;
            background: #fff;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 16px;
            transition: opacity 0.4s ease;
        }
        #preloader.hiding { opacity: 0; pointer-events: none; }

        .vl-spinner {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 3px solid #e8eaed;
            border-top-color: #1ABB9C;
            animation: vlSpin 0.75s linear infinite;
        }
        @keyframes vlSpin { to { transform: rotate(360deg); } }

        .vl-brand {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 700;
            color: #0d1117; letter-spacing: 0.3px;
        }
        .vl-brand-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #1ABB9C;
            animation: vlPulse 1.5s ease infinite;
        }
        @keyframes vlPulse {
            0%,100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.4); opacity: 0.6; }
        }

        /* ── LAYOUT SHELL ── */
        .container.body { background: #f0f2f5; }

        /* Right column spacing & background */
        .right_col {
            background: #f0f2f5 !important;
            padding: 24px 28px !important;
            min-height: 100vh;
        }

        /* ── PAGE HEADER convention ── */
        .v-page-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e8eaed;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .v-page-title {
            font-size: 20px; font-weight: 800;
            color: #0d1117; line-height: 1.2;
            letter-spacing: -0.3px;
        }
        .v-page-sub {
            font-size: 13px; color: #6b7584; margin-top: 2px;
        }
        .v-breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #8a939f; margin-top: 4px;
        }
        .v-breadcrumb a {
            color: #1ABB9C; text-decoration: none; font-weight: 600;
        }
        .v-breadcrumb a:hover { text-decoration: underline; }
        .v-breadcrumb-sep { color: #c8cdd6; font-size: 10px; }

        /* ── CARD ── */
        .v-card {
            background: #fff;
            border: 1px solid #e8eaed;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .v-card-title {
            font-size: 14px; font-weight: 700;
            color: #0d1117; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .v-card-title::before {
            content: '';
            width: 3px; height: 16px;
            border-radius: 2px;
            background: #1ABB9C;
            display: block;
        }

        /* ── STAT CARDS ── */
        .v-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .v-stat-card {
            background: #fff;
            border: 1px solid #e8eaed;
            border-radius: 12px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .v-stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }
        .v-stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--stat-color, #1ABB9C);
        }
        .v-stat-label {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: #8a939f; margin-bottom: 8px;
        }
        .v-stat-value {
            font-size: 26px; font-weight: 800;
            color: #0d1117; line-height: 1;
        }
        .v-stat-delta {
            font-size: 11px; margin-top: 6px; font-weight: 600;
        }
        .v-stat-delta.up   { color: #16a34a; }
        .v-stat-delta.down { color: #dc2626; }

        /* ── BADGE UTILITIES ── */
        .v-badge-success { background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
        .v-badge-warning { background:#fef9c3; color:#854d0e; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
        .v-badge-danger  { background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
        .v-badge-info    { background:#dbeafe; color:#1d4ed8; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
        .v-badge-teal    { background:rgba(26,187,156,0.12); color:#0f8a73; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }

        /* ── BUTTON UTILITIES ── */
        .v-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 700;
            border: none; cursor: pointer;
            transition: all 0.15s; text-decoration: none !important;
        }
        .v-btn-primary  { background: #1ABB9C; color: #fff; }
        .v-btn-primary:hover { background: #15a085; color: #fff; box-shadow: 0 4px 12px rgba(26,187,156,0.3); }
        .v-btn-outline  { background: transparent; color: #1ABB9C; border: 1.5px solid #1ABB9C; }
        .v-btn-outline:hover { background: rgba(26,187,156,0.06); color: #1ABB9C; }
        .v-btn-danger   { background: #ef4444; color: #fff; }
        .v-btn-danger:hover { background: #dc2626; color: #fff; }
        .v-btn-sm { padding: 5px 11px; font-size: 11.5px; border-radius: 6px; }

        /* ── TABLE OVERRIDES ── */
        .v-table-wrap { border-radius: 10px; overflow: hidden; border: 1px solid #e8eaed; }
        .v-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .v-table thead tr { background: #0d1117; }
        .v-table thead th {
            padding: 10px 14px;
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.55);
            text-align: left; border: none;
        }
        .v-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f0f2f5;
            color: #1e2530; vertical-align: middle;
        }
        .v-table tbody tr:last-child td { border-bottom: none; }
        .v-table tbody tr:hover td { background: #f7f8fa; }

        /* ── SELECT2 THEME ── */
        .select2-container--default .select2-selection--single {
            border: 1px solid #e8eaed !important;
            border-radius: 8px !important;
            height: 38px !important;
            display: flex !important; align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important; color: #0d1117 !important; padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .select2-dropdown { border: 1px solid #e8eaed !important; border-radius: 8px !important; box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important; overflow: hidden; }
        .select2-container--default .select2-results__option--highlighted { background: #1ABB9C !important; }

        /* ── TOASTR OVERRIDES ── */
        #toast-container > div {
            opacity: 1 !important;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important;
            border-radius: 10px !important;
            border: none !important;
            font-size: 13px !important;
            padding: 14px 18px !important;
        }
        #toast-container > .toast-success { background: #0f8a73 !important; }
        #toast-container > .toast-error   { background: #dc2626 !important; }
        #toast-container > .toast-warning { background: #b45309 !important; }
        #toast-container > .toast-info    { background: #1d4ed8 !important; }

        /* ── ANIMATIONS ── */
        @keyframes vFadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .v-animate { animation: vFadeUp 0.3s ease both; }
        .v-animate-d1 { animation-delay: 0.05s; }
        .v-animate-d2 { animation-delay: 0.10s; }
        .v-animate-d3 { animation-delay: 0.15s; }
        .v-animate-d4 { animation-delay: 0.20s; }

        /* scrollbar global */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>

    @stack('styles')
</head>

<body class="nav-md">

    {{-- PRELOADER --}}
    <div id="preloader">
        <div class="vl-spinner"></div>
        <div class="vl-brand">
            <span class="vl-brand-dot"></span>
            {{ config('app.name', 'Viomia') }}
        </div>
    </div>

    <div class="container body">
        <div class="main_container">

            @include('partials.admin.topnav')
            @include('partials.admin.sidebar')

            <div class="right_col" role="main">
                @yield('content')
            </div>

            @include('partials.admin.footer')

        </div>
    </div>

    {{-- ── SCRIPTS ── --}}
    <script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    {{-- DataTables --}}
    <script src="{{ asset('gentelella/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- Moment + FullCalendar --}}
    <script src="{{ asset('gentelella/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('gentelella/vendors/fullcalendar/dist/fullcalendar.min.js') }}"></script>

    {{-- Gentelella core (after jQuery) --}}
    <script src="{{ asset('gentelella/build/js/custom.min.js') }}"></script>

    <script>
        /* ── Preloader ── */
        $(window).on('load', function () {
            var $p = $('#preloader');
            setTimeout(function () {
                $p.addClass('hiding');
                setTimeout(function () { $p.hide(); }, 400);
            }, 200);
        });

        /* ── Toastr config ── */
        toastr.options = {
            closeButton:   true,
            progressBar:   true,
            positionClass: "toast-top-right",
            timeOut:       "5000",
            extendedTimeOut: "2000",
            newestOnTop:   true,
        };

        @if(Session::has('success')) toastr.success("{{ Session::get('success') }}"); @endif
        @if(Session::has('error'))   toastr.error("{{ Session::get('error') }}"); @endif
        @if(Session::has('info'))    toastr.info("{{ Session::get('info') }}"); @endif
        @if(Session::has('warning')) toastr.warning("{{ Session::get('warning') }}"); @endif

        /* ── Fullscreen toggle ── */
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        }

        /* ── CSRF for all AJAX ── */
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        /* ── Auto-init DataTables ── */
        $(function () {
            if ($.fn.DataTable) {
                $('table.v-datatable').DataTable({
                    responsive:  true,
                    pageLength:  25,
                    language: {
                        search:         '<i class="fa fa-search"></i>',
                        searchPlaceholder: 'Search...',
                        paginate: {
                            previous: '<i class="fa fa-chevron-left"></i>',
                            next:     '<i class="fa fa-chevron-right"></i>',
                        }
                    },
                    dom: '<"v-dt-top d-flex justify-content-between align-items-center mb-3"fl>rt<"v-dt-bot d-flex justify-content-between align-items-center mt-3"ip>',
                });
            }

            /* Auto-init Select2 */
            if ($.fn.select2) {
                $('.v-select2').select2({ width: '100%' });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>