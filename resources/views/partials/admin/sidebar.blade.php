<div class="col-md-3 left_col" style="padding:0;">

  <style>
    /* ── VIOMIA ADMIN SIDEBAR ── */
    :root {
      --s-bg:        #0d1117;
      --s-border:    rgba(255,255,255,0.07);
      --s-teal:      #1ABB9C;
      --s-teal-dim:  rgba(26,187,156,0.12);
      --s-teal-glow: rgba(26,187,156,0.22);
      --s-muted:     rgba(255,255,255,0.38);
      --s-text:      rgba(255,255,255,0.78);
      --s-active-bg: rgba(26,187,156,0.13);
      --s-hover-bg:  rgba(255,255,255,0.05);
    }

    /* ── SHELL: fixed height, flex column ── */
    .v-sidebar {
      width: 240px;
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      background: var(--s-bg);
      display: flex;
      flex-direction: column;
      font-family: 'DM Sans', system-ui, sans-serif;
      overflow: hidden;
      z-index: 1000;
    }

    /* decorative arc */
    .v-sidebar::after {
      content: '';
      position: absolute;
      bottom: -60px; right: -60px;
      width: 200px; height: 200px;
      border-radius: 50%;
      border: 36px solid rgba(26,187,156,0.04);
      pointer-events: none;
    }

    /* ── LOGO ── */
    .vs-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 17px 16px 15px;
      border-bottom: 1px solid var(--s-border);
      text-decoration: none !important;
      position: relative;
      flex-shrink: 0;
    }
    .vs-logo::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: var(--s-teal);
    }
    .vs-logo-icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: var(--s-teal);
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: #fff; flex-shrink: 0;
    }
    .vs-logo-name {
      font-size: 13px; font-weight: 800;
      color: #fff; letter-spacing: 0.5px;
      text-transform: uppercase; line-height: 1.1;
    }
    .vs-logo-tag {
      font-size: 9px; color: var(--s-teal);
      font-weight: 700; letter-spacing: 1.2px;
      text-transform: uppercase;
    }

    /* ── PROFILE ── */
    .vs-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      border-bottom: 1px solid var(--s-border);
      flex-shrink: 0;
    }
    .vs-profile-av {
      width: 36px; height: 36px;
      border-radius: 9px;
      object-fit: cover;
      border: 2px solid rgba(26,187,156,0.35);
      flex-shrink: 0;
    }
    .vs-profile-label {
      font-size: 9px; color: var(--s-teal);
      font-weight: 700; letter-spacing: 1.4px;
      text-transform: uppercase;
    }
    .vs-profile-name {
      font-size: 12px; font-weight: 700; color: #fff;
      margin-top: 1px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .vs-online {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--s-teal);
      border: 1.5px solid var(--s-bg);
      position: absolute;
      bottom: 0; right: 0;
    }

    /* ── SCROLLABLE NAV AREA ── */
    .vs-nav {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 8px 0 8px;
      scrollbar-width: thin;
      scrollbar-color: rgba(255,255,255,0.09) transparent;
      /* ensure it fills and scrolls correctly */
      min-height: 0;
    }
    .vs-nav::-webkit-scrollbar { width: 4px; }
    .vs-nav::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.1);
      border-radius: 4px;
    }

    /* section label */
    .vs-section-label {
      font-size: 9.5px; font-weight: 700;
      color: var(--s-muted);
      text-transform: uppercase;
      letter-spacing: 1.6px;
      padding: 12px 18px 5px;
    }

    .vs-divider {
      height: 1px;
      background: var(--s-border);
      margin: 10px 18px 4px;
    }

    /* ── NAV ITEM ── */
    .vs-item { list-style: none; margin: 1px 0; }

    .vs-link {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 8px 14px;
      border-radius: 8px;
      margin: 0 8px;
      color: var(--s-text);
      font-size: 12.5px; font-weight: 600;
      text-decoration: none !important;
      transition: background 0.14s, color 0.14s;
      cursor: pointer;
      user-select: none;
      position: relative;
    }
    .vs-link:hover { background: var(--s-hover-bg); color: #fff; }

    .vs-item.active > .vs-link {
      background: var(--s-active-bg);
      color: var(--s-teal);
    }
    .vs-item.active > .vs-link::before {
      content: '';
      position: absolute;
      left: -8px; top: 22%; bottom: 22%;
      width: 3px; border-radius: 0 3px 3px 0;
      background: var(--s-teal);
    }

    /* icon box */
    .vs-icon {
      width: 27px; height: 27px;
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; flex-shrink: 0;
      background: rgba(255,255,255,0.05);
      transition: background 0.14s, color 0.14s;
    }
    .vs-item.active > .vs-link .vs-icon,
    .vs-item.open   > .vs-link .vs-icon {
      background: var(--s-teal-dim);
      color: var(--s-teal);
    }

    /* chevron */
    .vs-chev {
      margin-left: auto;
      font-size: 10px;
      color: var(--s-muted);
      transition: transform 0.2s, color 0.2s;
      flex-shrink: 0;
    }
    .vs-item.active > .vs-link .vs-chev,
    .vs-item.open   > .vs-link .vs-chev {
      transform: rotate(180deg);
      color: var(--s-teal);
    }

    /* ── CHILD MENU ── */
    .vs-children {
      display: none;
      list-style: none;
      padding: 2px 0 4px;
      margin: 0 8px 0 calc(8px + 19px);
      border-left: 1.5px solid rgba(26,187,156,0.18);
    }
    .vs-item.active > .vs-children,
    .vs-item.open   > .vs-children { display: block; }

    .vs-cl {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 12px;
      border-radius: 6px;
      color: var(--s-muted);
      font-size: 12px; font-weight: 500;
      text-decoration: none !important;
      transition: background 0.12s, color 0.12s;
    }
    .vs-cl::before {
      content: '';
      width: 5px; height: 5px;
      border-radius: 50%;
      background: currentColor;
      opacity: 0.35;
      flex-shrink: 0;
    }
    .vs-cl:hover { color: #fff; background: rgba(255,255,255,0.04); }
    .vs-cl.current { color: var(--s-teal); font-weight: 700; }
    .vs-cl.current::before {
      opacity: 1;
      background: var(--s-teal);
      box-shadow: 0 0 5px var(--s-teal-glow);
    }

    /* ── FOOTER ── */
    .vs-footer {
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 11px 8px;
      border-top: 1px solid var(--s-border);
      background: #080e14;
      flex-shrink: 0;
    }
    .vs-fbtn {
      width: 36px; height: 36px;
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      color: var(--s-muted);
      font-size: 15px;
      text-decoration: none !important;
      transition: background 0.14s, color 0.14s;
      cursor: pointer;
      background: none; border: none;
    }
    .vs-fbtn:hover { background: var(--s-hover-bg); color: #fff; }
    .vs-fbtn.danger:hover { background: rgba(239,68,68,0.12); color: #ef4444; }
  </style>

  <div class="v-sidebar">

    {{-- LOGO --}}
    <a href="{{ route('admin.dashboard') }}" class="vs-logo">
      <div class="vs-logo-icon"><i class="fa fa-terminal"></i></div>
      <div>
        <div class="vs-logo-name">{{ config('app.name', 'Bot Manager') }}</div>
        <div class="vs-logo-tag">Admin Panel</div>
      </div>
    </a>

    {{-- PROFILE --}}
    <div class="vs-profile">
      <div style="position:relative;flex-shrink:0;">
        @if(Auth::user()->profile_photo)
          <img class="vs-profile-av" src="{{ asset('storage/'.Auth::user()->profile_photo) }}" alt="">
        @else
          <img class="vs-profile-av" src="{{ asset('img/bot_logo.png') }}" alt="">
        @endif
        <span class="vs-online"></span>
      </div>
      <div style="min-width:0;">
        <div class="vs-profile-label">Connected as</div>
        <div class="vs-profile-name">{{ Auth::user()->name ?? 'Admin' }}</div>
      </div>
    </div>

    {{-- SCROLLABLE NAV --}}
    @php
      $is   = fn($p)  => request()->routeIs($p);
      $open = fn($ps) => collect((array)$ps)->contains(fn($p) => request()->routeIs($p));
    @endphp

    <nav class="vs-nav">
      <ul style="list-style:none;padding:0;margin:0;">

        {{-- ── MANAGEMENT ── --}}
        <div class="vs-section-label">Management</div>

        {{-- Dashboard --}}
        <li class="vs-item {{ $is('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="vs-link">
            <span class="vs-icon"><i class="fa fa-th-large"></i></span>
            Dashboard
          </a>
        </li>

        {{-- Clients --}}
        @php $cOpen = $open(['admin.clients.*']); @endphp
        <li class="vs-item {{ $cOpen ? 'active' : '' }}" id="vsg-clients">
          <a class="vs-link" onclick="vsSub('vsg-clients')">
            <span class="vs-icon"><i class="fa fa-users"></i></span>
            Clients
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.clients.index') }}"
                   class="vs-cl {{ $is('admin.clients.index') ? 'current' : '' }}">All Clients</a></li>
          </ul>
        </li>

        {{-- Trading Accounts --}}
        @php $aOpen = $open(['admin.accounts.*']); @endphp
        <li class="vs-item {{ $aOpen ? 'active' : '' }}" id="vsg-accounts">
          <a class="vs-link" onclick="vsSub('vsg-accounts')">
            <span class="vs-icon"><i class="fa fa-exchange"></i></span>
            Trading Accounts
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.accounts.index') }}"
                   class="vs-cl {{ $is('admin.accounts.index') ? 'current' : '' }}">Connected Accounts</a></li>
            <li><a href="{{ route('admin.accounts.pending') }}"
                   class="vs-cl {{ $is('admin.accounts.pending') ? 'current' : '' }}">Pending Verification</a></li>
          </ul>
        </li>

        {{-- Bots --}}
        @php $bOpen = $open(['admin.bots.*']); @endphp
        <li class="vs-item {{ $bOpen ? 'active' : '' }}" id="vsg-bots">
          <a class="vs-link" onclick="vsSub('vsg-bots')">
            <span class="vs-icon"><i class="fa fa-terminal"></i></span>
            Bots
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.bots.index') }}"
                   class="vs-cl {{ $is('admin.bots.index') ? 'current' : '' }}">All Bots</a></li>
            <li><a href="{{ route('admin.bots.logs') }}"
                   class="vs-cl {{ $is('admin.bots.logs') ? 'current' : '' }}">Bot Logs</a></li>
          </ul>
        </li>

        {{-- ── ACTIVITY & FINANCE ── --}}
        <div class="vs-divider"></div>
        <div class="vs-section-label">Activity &amp; Finance</div>

        {{-- Trading Activity --}}
        @php $tOpen = $open(['admin.signals.*','admin.trades.*']); @endphp
        <li class="vs-item {{ $tOpen ? 'active' : '' }}" id="vsg-trading">
          <a class="vs-link" onclick="vsSub('vsg-trading')">
            <span class="vs-icon"><i class="fa fa-line-chart"></i></span>
            Trading Activity
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.signals.index') }}"
                   class="vs-cl {{ $is('admin.signals.index') ? 'current' : '' }}">Signals</a></li>
            <li><a href="{{ route('admin.trades.index') }}"
                   class="vs-cl {{ $is('admin.trades.index') ? 'current' : '' }}">Trades</a></li>
            <li><a href="{{ route('admin.trades.statistics') }}"
                   class="vs-cl {{ $is('admin.trades.statistics') ? 'current' : '' }}">Statistics</a></li>
          </ul>
        </li>

        {{-- AI Analytics --}}
        @php
          $aiOpen = $open([
            'admin.ai.dashboard','admin.ai.candles','admin.ai.candles.index',
            'admin.ai.decisions','admin.ai.decisions.index',
            'admin.ai.signals','admin.ai.signal-logs.index',
            'admin.ai.executions','admin.ai.executions.index',
            'admin.ai.outcomes','admin.ai.outcomes.index',
            'admin.ai.performance','admin.ai.sessions',
          ]);
        @endphp
        <li class="vs-item {{ $aiOpen ? 'active' : '' }}" id="vsg-ai">
          <a class="vs-link" onclick="vsSub('vsg-ai')">
            <span class="vs-icon"><i class="fa fa-cube"></i></span>
            AI Analytics
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.ai.dashboard') }}"
                   class="vs-cl {{ $is('admin.ai.dashboard') ? 'current' : '' }}">AI Dashboard</a></li>
            <li><a href="{{ route('admin.ai.candles.index') }}"
                   class="vs-cl {{ $is('admin.ai.candles.index') ? 'current' : '' }}">Market Data</a></li>
            <li><a href="{{ route('admin.ai.decisions.index') }}"
                   class="vs-cl {{ $is('admin.ai.decisions.index') ? 'current' : '' }}">AI Decisions</a></li>
            <li><a href="{{ route('admin.ai.signal-logs.index') }}"
                   class="vs-cl {{ $is('admin.ai.signal-logs.index') ? 'current' : '' }}">Signals Sent</a></li>
            <li><a href="{{ route('admin.ai.executions.index') }}"
                   class="vs-cl {{ $is('admin.ai.executions.index') ? 'current' : '' }}">Trade Executions</a></li>
            <li><a href="{{ route('admin.ai.outcomes.index') }}"
                   class="vs-cl {{ $is('admin.ai.outcomes.index') ? 'current' : '' }}">Trade Outcomes</a></li>
            <li><a href="{{ route('admin.ai.performance') }}"
                   class="vs-cl {{ $is('admin.ai.performance') ? 'current' : '' }}">AI Performance</a></li>
          </ul>
        </li>

        {{-- Payments --}}
        @php $pOpen = $open(['admin.payments.*','admin.banks.*','admin.subscription_plans.*']); @endphp
        <li class="vs-item {{ $pOpen ? 'active' : '' }}" id="vsg-payments">
          <a class="vs-link" onclick="vsSub('vsg-payments')">
            <span class="vs-icon"><i class="fa fa-credit-card"></i></span>
            Payments
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.banks.index') }}"
                   class="vs-cl {{ $is('admin.banks.index') ? 'current' : '' }}">All Banks</a></li>
            <li><a href="{{ route('admin.payments.index') }}"
                   class="vs-cl {{ $is('admin.payments.index') ? 'current' : '' }}">All Payments</a></li>
            <li><a href="{{ route('admin.subscription_plans.index') }}"
                   class="vs-cl {{ $is('admin.subscription_plans.index') ? 'current' : '' }}">Subscription Plans</a></li>
            <li><a href="{{ route('admin.payments.reports') }}"
                   class="vs-cl {{ $is('admin.payments.reports') ? 'current' : '' }}">Payment Reports</a></li>
          </ul>
        </li>

        {{-- ── SYSTEM ── --}}
        <div class="vs-divider"></div>
        <div class="vs-section-label">System</div>

        {{-- Settings --}}
        @php $sOpen = $open(['admin.users.*','admin.roles.*','admin.settings.*']); @endphp
        <li class="vs-item {{ $sOpen ? 'active' : '' }}" id="vsg-settings">
          <a class="vs-link" onclick="vsSub('vsg-settings')">
            <span class="vs-icon"><i class="fa fa-sliders"></i></span>
            System Settings
            <i class="fa fa-chevron-down vs-chev"></i>
          </a>
          <ul class="vs-children">
            <li><a href="{{ route('admin.users.index') }}"
                   class="vs-cl {{ $is('admin.users.index') ? 'current' : '' }}">Admin Users</a></li>
            <li><a href="{{ route('admin.roles.index') }}"
                   class="vs-cl {{ $is('admin.roles.index') ? 'current' : '' }}">Roles &amp; Permissions</a></li>
            <li><a href="{{ route('admin.settings.index') }}"
                   class="vs-cl {{ $is('admin.settings.index') ? 'current' : '' }}">General Settings</a></li>
          </ul>
        </li>

        {{-- bottom padding spacer --}}
        <div style="height:12px;"></div>

      </ul>
    </nav>

    {{-- FOOTER --}}
    <div class="vs-footer">
      <a class="vs-fbtn" href="{{ route('admin.settings.index') }}"
         title="Settings" data-toggle="tooltip" data-placement="top">
        <i class="fa fa-cog"></i>
      </a>
      <a class="vs-fbtn" href="#"
         onclick="toggleFullScreen(); return false;"
         title="Fullscreen" data-toggle="tooltip" data-placement="top">
        <i class="fa fa-arrows-alt"></i>
      </a>
      <a class="vs-fbtn" href="#" onclick="return false;"
         title="Lock" data-toggle="tooltip" data-placement="top">
        <i class="fa fa-lock"></i>
      </a>
      <a class="vs-fbtn danger"
         href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
         title="Sign Out" data-toggle="tooltip" data-placement="top">
        <i class="fa fa-power-off"></i>
      </a>
    </div>

  </div>{{-- /v-sidebar --}}

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

  <script>
    function vsSub(id) {
      var li = document.getElementById(id);
      if (!li) return;
      var isOpen = li.classList.contains('open');
      // collapse all others
      document.querySelectorAll('.vs-item[id]').forEach(function(el) {
        if (el !== li) el.classList.remove('open');
      });
      li.classList.toggle('open', !isOpen);
    }
  </script>

</div>