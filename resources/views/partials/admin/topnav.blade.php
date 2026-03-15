<div class="top_nav">
    @php
        $totalUsers     = \App\Models\User::count();
        $pendingAccounts= \App\Models\Account::where('is_verified', false)->count();
        $activeBots     = \App\Models\EaBot::count();
        $openTrades     = \App\Models\TradeLog::where('status', 'open')->count();
        $openSupport    = \App\Models\SupportTicket::whereIn('status', ['open', 'pending'])->count();
        $recentTickets  = \App\Models\SupportTicket::whereIn('status', ['open', 'pending'])->latest()->limit(3)->get();
        $systemErrors   = \App\Models\ErrorLog::where('error_at', '>=', now()->subHours(24))->count();
        $alertCount     = $pendingAccounts + $systemErrors;
    @endphp

    <style>
        /* ── VIOMIA ADMIN NAV ── */
        :root {
            --nav-h:        58px;
            --teal:         #1ABB9C;
            --teal-dim:     rgba(26,187,156,0.12);
            --teal-glow:    rgba(26,187,156,0.25);
            --ink:          #0d1117;
            --ink-soft:     #1e2530;
            --muted:        #6b7584;
            --border:       #e8eaed;
            --surface:      #ffffff;
            --surface-2:    #f7f8fa;
            --red:          #ef4444;
            --amber:        #f59e0b;
            --blue:         #3b82f6;
        }

        .v-navbar {
            height: var(--nav-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px 0 16px;
            position: relative;
            font-family: 'DM Sans', system-ui, sans-serif;
        }

        /* thin teal rule at very top */
        .v-navbar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--teal);
        }

        /* ── LEFT ── */
        .v-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .v-toggle {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--muted);
            cursor: pointer;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            font-size: 15px;
        }
        .v-toggle:hover {
            background: var(--teal-dim);
            border-color: var(--teal);
            color: var(--teal);
        }

        .v-divider { width: 1px; height: 22px; background: var(--border); }

        /* status pill */
        .v-status {
            display: flex; align-items: center; gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(26,187,156,0.08);
            border: 1px solid rgba(26,187,156,0.2);
            font-size: 11px; font-weight: 600;
            color: #0f8a73;
            letter-spacing: 0.3px;
        }
        .v-status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--teal);
            animation: vPulse 2s ease infinite;
        }
        @keyframes vPulse {
            0%,100% { box-shadow: 0 0 0 0 var(--teal-glow); }
            50%      { box-shadow: 0 0 0 5px transparent; }
        }

        /* stat chip */
        .v-stat {
            display: flex; align-items: center; gap-5px;
            font-size: 12px; color: var(--muted);
        }
        .v-stat strong { color: var(--ink); font-weight: 700; margin: 0 2px; }

        /* ── RIGHT ── */
        .v-right {
            display: flex; align-items: center; gap: 4px;
        }

        /* icon button base */
        .v-icon-btn {
            position: relative;
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 9px;
            color: var(--muted);
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            font-size: 17px;
            text-decoration: none !important;
        }
        .v-icon-btn:hover { background: var(--surface-2); color: var(--ink); }

        /* badge on icon */
        .v-badge {
            position: absolute;
            top: 4px; right: 4px;
            min-width: 16px; height: 16px;
            padding: 0 4px;
            border-radius: 8px;
            font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            line-height: 1;
            border: 1.5px solid var(--surface);
        }
        .v-badge-red    { background: var(--red);   color: #fff; }
        .v-badge-amber  { background: var(--amber); color: #fff; }

        /* ── DROPDOWNS ── */
        .v-dropdown {
            position: relative;
        }
        .v-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 310px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
            z-index: 9999;
            animation: vDropIn 0.18s ease;
        }
        @keyframes vDropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .v-dropdown.open .v-dropdown-menu { display: block; }

        .v-drop-header {
            padding: 12px 14px 10px;
            border-bottom: 1px solid var(--border);
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.8px;
            color: var(--muted);
            display: flex; align-items: center; gap: 7px;
        }
        .v-drop-header i { color: var(--teal); font-size: 13px; }

        .v-drop-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            text-decoration: none !important;
            transition: background 0.12s;
            cursor: pointer;
        }
        .v-drop-item:last-of-type { border-bottom: none; }
        .v-drop-item:hover { background: var(--surface-2); }

        .v-drop-icon {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0; margin-top: 1px;
        }
        .v-drop-icon.teal  { background: rgba(26,187,156,0.10); color: var(--teal); }
        .v-drop-icon.amber { background: rgba(245,158,11,0.10); color: var(--amber); }
        .v-drop-icon.red   { background: rgba(239,68,68,0.10);  color: var(--red); }
        .v-drop-icon.blue  { background: rgba(59,130,246,0.10); color: var(--blue); }

        .v-drop-title {
            font-size: 12px; font-weight: 600; color: var(--ink); line-height: 1.3;
        }
        .v-drop-sub {
            font-size: 11px; color: var(--muted); margin-top: 1px; line-height: 1.4;
        }
        .v-drop-action {
            font-size: 10px; font-weight: 700; color: var(--teal); margin-top: 3px;
        }

        .v-drop-footer {
            padding: 10px 14px;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .v-drop-footer a {
            font-size: 12px; font-weight: 700; color: var(--teal);
            text-decoration: none;
        }
        .v-drop-footer a:hover { text-decoration: underline; }

        /* support ticket avatar */
        .v-ticket-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
            border: 1.5px solid var(--border);
        }

        /* priority pip */
        .v-pip {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 10px;
            font-size: 9.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.4px;
        }
        .v-pip-red   { background: rgba(239,68,68,0.10);  color: #dc2626; }
        .v-pip-amber { background: rgba(245,158,11,0.10); color: #b45309; }
        .v-pip-blue  { background: rgba(59,130,246,0.10); color: #1d4ed8; }

        /* ── PROFILE BUTTON ── */
        .v-profile-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 5px 8px 5px 5px;
            border-radius: 10px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            text-decoration: none !important;
            color: var(--ink) !important;
        }
        .v-profile-btn:hover {
            background: var(--surface-2);
            border-color: #d1d5db;
        }
        .v-avatar {
            width: 30px; height: 30px;
            border-radius: 7px;
            object-fit: cover;
            border: 1.5px solid var(--border);
        }
        .v-profile-name { font-size: 12px; font-weight: 700; color: var(--ink); line-height: 1.2; }
        .v-profile-role { font-size: 10px; color: var(--teal); font-weight: 600; }
        .v-caret { font-size: 10px; color: var(--muted); margin-left: 2px; }

        /* profile dropdown wider */
        .v-profile-menu { width: 260px; }

        .v-profile-hero {
            padding: 14px;
            background: linear-gradient(135deg, #0d1117 0%, #1e2530 100%);
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .v-profile-hero-avatar {
            width: 40px; height: 40px; border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(26,187,156,0.4);
        }
        .v-profile-hero-name { font-size: 13px; font-weight: 700; color: #fff; }
        .v-profile-hero-role {
            font-size: 10px; color: var(--teal); font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.6px;
        }

        .v-menu-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            text-decoration: none !important;
            color: var(--ink-soft) !important;
            font-size: 12.5px; font-weight: 600;
            transition: background 0.12s, border-left 0.12s;
        }
        .v-menu-item:last-of-type { border-bottom: none; }
        .v-menu-item:hover {
            background: var(--surface-2);
            border-left: 2.5px solid var(--teal);
            padding-left: calc(14px - 2.5px);
            color: var(--ink) !important;
        }
        .v-menu-item i {
            width: 18px; text-align: center; font-size: 14px; flex-shrink: 0;
        }
        .v-menu-item small { display: block; font-size: 10.5px; color: var(--muted); font-weight: 400; margin-top: 0px; }

        .v-menu-divider { height: 1px; background: var(--border); margin: 4px 0; }

        .v-menu-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px;
            text-decoration: none !important;
            color: var(--red) !important;
            font-size: 12.5px; font-weight: 600;
            transition: background 0.12s;
            cursor: pointer;
        }
        .v-menu-logout:hover { background: rgba(239,68,68,0.06); }
        .v-menu-logout i { width: 18px; text-align: center; font-size: 14px; }

        /* responsive */
        @media (max-width: 768px) {
            .v-hide-sm { display: none !important; }
            .v-navbar { padding: 0 12px 0 10px; }
        }

        /* close on outside click handled via JS below */
    </style>

    <div class="v-navbar">

        <!-- ── LEFT ── -->
        <div class="v-left">
            <a id="menu_toggle" class="v-toggle">
                <i class="fa fa-bars"></i>
            </a>

            <div class="v-divider v-hide-sm"></div>

            <div class="v-status v-hide-sm">
                <span class="v-status-dot"></span>
                System Active
            </div>

            <div class="v-stat v-hide-sm" style="font-size:12px;color:#6b7584;">
                <i class="fa fa-users" style="color:#1ABB9C;margin-right:4px;"></i>
                <strong>{{ $totalUsers }}</strong> Users
            </div>
        </div>

        <!-- ── RIGHT ── -->
        <div class="v-right">

            {{-- ── ALERTS DROPDOWN ── --}}
            <div class="v-dropdown" id="vDropAlerts">
                <a class="v-icon-btn" onclick="vToggle('vDropAlerts')" title="Alerts">
                    <i class="fa fa-bell-o"></i>
                    @if($alertCount > 0)
                        <span class="v-badge v-badge-red">{{ $alertCount }}</span>
                    @endif
                </a>
                <div class="v-dropdown-menu">
                    <div class="v-drop-header">
                        <i class="fa fa-exclamation-circle"></i> Admin Alerts
                    </div>

                    @if($pendingAccounts > 0)
                    <a class="v-drop-item" href="{{ route('admin.accounts.pending') }}">
                        <div class="v-drop-icon amber"><i class="fa fa-clock-o"></i></div>
                        <div>
                            <div class="v-drop-title">{{ $pendingAccounts }} Pending Account{{ $pendingAccounts !== 1 ? 's' : '' }}</div>
                            <div class="v-drop-sub">Awaiting verification</div>
                            <div class="v-drop-action">Review Now →</div>
                        </div>
                    </a>
                    @endif

                    <a class="v-drop-item" href="{{ route('admin.bots.index') }}">
                        <div class="v-drop-icon teal"><i class="fa fa-check-circle"></i></div>
                        <div>
                            <div class="v-drop-title">{{ $activeBots }} Active Bot{{ $activeBots !== 1 ? 's' : '' }}</div>
                            <div class="v-drop-sub">Trading normally &nbsp;<span style="font-size:10px;background:rgba(26,187,156,0.1);color:#0f8a73;padding:1px 6px;border-radius:8px;font-weight:700;">LIVE</span></div>
                        </div>
                    </a>

                    <a class="v-drop-item" href="{{ route('admin.dashboard') }}">
                        <div class="v-drop-icon blue"><i class="fa fa-bar-chart"></i></div>
                        <div>
                            <div class="v-drop-title">{{ $openTrades }} Open Position{{ $openTrades !== 1 ? 's' : '' }}</div>
                            <div class="v-drop-sub">Active trades</div>
                        </div>
                    </a>

                    @if($systemErrors > 0)
                    <a class="v-drop-item" href="{{ route('admin.dashboard') }}">
                        <div class="v-drop-icon red"><i class="fa fa-exclamation-triangle"></i></div>
                        <div>
                            <div class="v-drop-title" style="color:#dc2626;">{{ $systemErrors }} Error{{ $systemErrors !== 1 ? 's' : '' }} in last 24h</div>
                            <div class="v-drop-sub">System warnings — review logs</div>
                        </div>
                    </a>
                    @endif

                    <div class="v-drop-footer">
                        <a href="{{ route('admin.dashboard') }}">View Dashboard →</a>
                    </div>
                </div>
            </div>

            {{-- ── SUPPORT DROPDOWN ── --}}
            <div class="v-dropdown" id="vDropSupport">
                <a class="v-icon-btn" onclick="vToggle('vDropSupport')" title="Support Tickets">
                    <i class="fa fa-envelope-o"></i>
                    @if($openSupport > 0)
                        <span class="v-badge v-badge-amber">{{ $openSupport }}</span>
                    @endif
                </a>
                <div class="v-dropdown-menu">
                    <div class="v-drop-header">
                        <i class="fa fa-headphones"></i>
                        Support Tickets
                        <span style="margin-left:auto;background:var(--teal-dim);color:var(--teal);padding:1px 8px;border-radius:10px;font-size:10px;font-weight:700;">{{ $openSupport }} open</span>
                    </div>

                    @forelse($recentTickets as $ticket)
                    <a class="v-drop-item" href="{{ route('admin.support_tickets.index') }}">
                        <img class="v-ticket-avatar"
                             src="{{ $ticket->user->profile_photo ? asset('storage/'.$ticket->user->profile_photo) : asset('img/bot_logo.png') }}"
                             alt="">
                        <div style="flex:1;min-width:0;">
                            <div class="v-drop-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ Str::limit($ticket->subject, 36) }}
                            </div>
                            <div class="v-drop-sub">{{ Str::limit($ticket->user->name, 24) }}</div>
                            <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
                                @php
                                    $priClass = match($ticket->priority ?? 'normal') {
                                        'high'   => 'v-pip-red',
                                        'medium' => 'v-pip-amber',
                                        default  => 'v-pip-blue',
                                    };
                                @endphp
                                <span class="v-pip {{ $priClass }}">{{ ucfirst($ticket->priority ?? 'normal') }}</span>
                                <span style="font-size:10px;color:var(--muted);">{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="v-drop-item" style="justify-content:center;color:var(--muted);font-size:12px;">
                        <i class="fa fa-check-circle" style="color:var(--teal);margin-right:6px;"></i> No pending tickets
                    </div>
                    @endforelse

                    <div class="v-drop-footer">
                        <a href="{{ route('admin.support_tickets.index') }}">All {{ $openSupport }} Ticket{{ $openSupport !== 1 ? 's' : '' }} →</a>
                    </div>
                </div>
            </div>

            <div style="width:1px;height:22px;background:var(--border);margin:0 6px;"></div>

            {{-- ── PROFILE DROPDOWN ── --}}
            <div class="v-dropdown" id="vDropProfile">
                <a class="v-profile-btn" onclick="vToggle('vDropProfile')">
                    <img class="v-avatar"
                         src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : asset('img/bot_logo.png') }}"
                         alt="">
                    <div class="v-hide-sm">
                        <div class="v-profile-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                        <div class="v-profile-role">Admin</div>
                    </div>
                    <i class="fa fa-angle-down v-caret v-hide-sm"></i>
                </a>

                <div class="v-dropdown-menu v-profile-menu">
                    {{-- Hero --}}
                    <div class="v-profile-hero">
                        <img class="v-profile-hero-avatar"
                             src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : asset('img/bot_logo.png') }}"
                             alt="">
                        <div>
                            <div class="v-profile-hero-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <div class="v-profile-hero-role">Administrator</div>
                        </div>
                    </div>

                    <a class="v-menu-item" href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-th-large" style="color:#3b82f6;"></i>
                        <div>Dashboard <small>System overview</small></div>
                    </a>
                    <a class="v-menu-item" href="{{ route('admin.users.index') }}">
                        <i class="fa fa-users" style="color:#10b981;"></i>
                        <div>Manage Users <small>{{ $totalUsers }} total accounts</small></div>
                    </a>
                    <a class="v-menu-item" href="{{ route('admin.support_tickets.index') }}">
                        <i class="fa fa-ticket" style="color:#f59e0b;"></i>
                        <div>
                            Support Tickets
                            <small>Handle user requests</small>
                        </div>
                        @if($openSupport > 0)
                            <span style="margin-left:auto;background:rgba(245,158,11,0.12);color:#b45309;padding:1px 7px;border-radius:10px;font-size:10px;font-weight:700;">{{ $openSupport }}</span>
                        @endif
                    </a>
                    <a class="v-menu-item" href="{{ route('admin.accounts.index') }}">
                        <i class="fa fa-credit-card" style="color:#06b6d4;"></i>
                        <div>Trading Accounts <small>View linked accounts</small></div>
                    </a>
                    <a class="v-menu-item" href="{{ route('admin.settings.index') }}">
                        <i class="fa fa-cog" style="color:#8b5cf6;"></i>
                        <div>System Settings <small>Configure platform</small></div>
                    </a>
                    <a class="v-menu-item" href="{{ route('user.profile.index') }}">
                        <i class="fa fa-user-circle" style="color:#06b6d4;"></i>
                        <div>My Profile <small>Account settings</small></div>
                    </a>
                    <a class="v-menu-item" href="{{ route('help') }}">
                        <i class="fa fa-question-circle" style="color:#3b82f6;"></i>
                        <div>Help &amp; Docs <small>Documentation &amp; support</small></div>
                    </a>

                    <div class="v-menu-divider"></div>

                    <a class="v-menu-logout"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('v-logout-form').submit();">
                        <i class="fa fa-sign-out"></i>
                        <div style="font-size:12.5px;">Sign Out <small style="display:block;font-size:10.5px;color:#f87171;font-weight:400;">End admin session</small></div>
                    </a>
                </div>
            </div>

        </div>{{-- /v-right --}}
    </div>{{-- /v-navbar --}}

    <form id="v-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    <script>
        function vToggle(id) {
            var all = document.querySelectorAll('.v-dropdown');
            all.forEach(function(el) {
                if (el.id !== id) el.classList.remove('open');
            });
            document.getElementById(id).classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.v-dropdown')) {
                document.querySelectorAll('.v-dropdown').forEach(function(el) {
                    el.classList.remove('open');
                });
            }
        });
    </script>

</div>{{-- /top_nav --}}