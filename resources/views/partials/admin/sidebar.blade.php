<div class="col-md-3 left_col">
  <div class="scroll-view" style="display:flex;flex-direction:column;height:100vh;overflow:hidden;">

    <style>
      /* ── VIOMIA ADMIN SIDEBAR ── */
      :root {
        --s-bg:        #0d1117;
        --s-bg2:       #141b24;
        --s-border:    rgba(255,255,255,0.07);
        --s-teal:      #1ABB9C;
        --s-teal-dim:  rgba(26,187,156,0.12);
        --s-teal-glow: rgba(26,187,156,0.22);
        --s-muted:     rgba(255,255,255,0.38);
        --s-text:      rgba(255,255,255,0.78);
        --s-active-bg: rgba(26,187,156,0.13);
        --s-hover-bg:  rgba(255,255,255,0.05);
        --s-radius:    8px;
        --s-w:         240px;
      }

      /* Sidebar shell */
      .v-sidebar {
        width: var(--s-w);
        min-height: 100vh;
        background: var(--s-bg);
        display: flex;
        flex-direction: column;
        position: relative;
        font-family: 'DM Sans', system-ui, sans-serif;
        overflow: hidden;
      }

      /* decorative arc */
      .v-sidebar::after {
        content: '';
        position: absolute;
        bottom: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        border: 40px solid rgba(26,187,156,0.04);
        pointer-events: none;
      }

      /* ── LOGO ── */
      .vs-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 16px 16px;
        border-bottom: 1px solid var(--s-border);
        text-decoration: none !important;
        position: relative;
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
        color: #fff; letter-spacing: 0.6px;
        text-transform: uppercase;
        line-height: 1.1;
      }
      .vs-logo-tag {
        font-size: 9px; color: var(--s-teal);
        font-weight: 600; letter-spacing: 1.2px;
        text-transform: uppercase;
      }

      /* ── PROFILE ── */
      .vs-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--s-border);
      }
      .vs-profile-av {
        width: 38px; height: 38px;
        border-radius: 10px;
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
        font-size: 12.5px; font-weight: 700; color: #fff;
        margin-top: 1px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      }
      .vs-online {
        width: 8px; height: 8px;
        border-radius: 50%; background: var(--s-teal);
        border: 1.5px solid var(--s-bg);
        position: absolute;
        bottom: 1px; right: 1px;
      }

      /* ── NAV SCROLL AREA ── */
      .vs-nav {
        flex: 1;
        overflow-y: auto;
        padding: 10px 0 10px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.08) transparent;
      }
      .vs-nav::-webkit-scrollbar { width: 4px; }
      .vs-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

      /* section heading */
      .vs-section-label {
        font-size: 9.5px;
        font-weight: 700;
        color: var(--s-muted);
        text-transform: uppercase;
        letter-spacing: 1.6px;
        padding: 12px 18px 6px;
      }

      .vs-divider {
        height: 1px;
        background: var(--s-border);
        margin: 10px 18px 4px;
      }

      /* ── NAV ITEMS ── */
      .vs-item {
        list-style: none;
        margin: 1px 0;
      }

      /* top-level link */
      .vs-link {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 16px;
        border-radius: var(--s-radius);
        margin: 0 8px;
        color: var(--s-text);
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none !important;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        user-select: none;
        position: relative;
      }
      .vs-link:hover {
        background: var(--s-hover-bg);
        color: #fff;
      }
      .vs-item.active > .vs-link,
      .vs-link.active {
        background: var(--s-active-bg);
        color: var(--s-teal);
      }
      .vs-item.active > .vs-link::before {
        content: '';
        position: absolute;
        left: 0; top: 20%; bottom: 20%;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: var(--s-teal);
        margin-left: -8px;
      }

      /* icon wrapper */
      .vs-icon {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; flex-shrink: 0;
        background: var(--s-hover-bg);
        transition: background 0.15s;
      }
      .vs-item.active > .vs-link .vs-icon,
      .vs-link.active .vs-icon {
        background: var(--s-teal-dim);
        color: var(--s-teal);
      }

      /* chevron */
      .vs-chevron {
        margin-left: auto;
        font-size: 10px;
        color: var(--s-muted);
        transition: transform 0.2s;
      }
      .vs-item.active > .vs-link .vs-chevron,
      .vs-item.open > .vs-link .vs-chevron {
        transform: rotate(180deg);
        color: var(--s-teal);
      }

      /* child menu */
      .vs-children {
        display: none;
        list-style: none;
        padding: 3px 0 4px 0;
        margin: 0 8px 0 calc(8px + 20px);
        border-left: 1.5px solid rgba(26,187,156,0.2);
      }
      .vs-item.active > .vs-children,
      .vs-item.open > .vs-children {
        display: block;
      }
      .vs-child-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 6px;
        color: var(--s-muted);
        font-size: 12px;
        font-weight: 500;
        text-decoration: none !important;
        transition: background 0.12s, color 0.12s;
        position: relative;
      }
      .vs-child-link::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.4;
        flex-shrink: 0;
      }
      .vs-child-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.04);
      }
      .vs-child-link.current {
        color: var(--s-teal);
        font-weight: 700;
      }
      .vs-child-link.current::before {
        opacity: 1;
        background: var(--s-teal);
        box-shadow: 0 0 6px var(--s-teal-glow);
      }

      /* ── FOOTER ── */
      .vs-footer {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 12px 8px;
        border-top: 1px solid var(--s-border);
        background: #080e14;
        flex-shrink: 0;
      }
      .vs-footer-btn {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        color: var(--s-muted);
        font-size: 15px;
        text-decoration: none !important;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        background: none; border: none;
      }
      .vs-footer-btn:hover {
        background: var(--s-hover-bg);
        color: #fff;
      }
      .vs-footer-btn.danger:hover {
        background: rgba(239,68,68,0.12);
        color: #ef4444;
      }
    </style>

    <div class="v-sidebar">

      {{-- ── LOGO ── --}}
      <a href="{{ route('admin.dashboard') }}" class="vs-logo">
        <div class="vs-logo-icon">
          <i class="fa fa-terminal"></i>
        </div>
        <div>
          <div class="vs-logo-name">{{ config('app.name', 'Bot Manager') }}</div>
          <div class="vs-logo-tag">Admin Panel</div>
        </div>
      </a>

      {{-- ── PROFILE ── --}}
      <div class="vs-profile">
        <div style="position:relative;flex-shrink:0;">
          @if(Auth::user()->profile_photo)
            <img class="vs-profile-av"
                 src="{{ asset('storage/'.Auth::user()->profile_photo) }}" alt="">
          @else
            <img class="vs-profile-av"
                 src="{{ asset('img/bot_logo.png') }}" alt="">
          @endif
          <span class="vs-online"></span>
        </div>
        <div style="min-width:0;">
          <div class="vs-profile-label">Connected as</div>
          <div class="vs-profile-name">{{ Auth::user()->name ?? 'Admin' }}</div>
        </div>
      </div>

      {{-- ── NAV ── --}}
      @php
        $is   = fn($p) => request()->routeIs($p);
        $open = fn($ps) => collect((array)$ps)->contains(fn($p) => request()->routeIs($p));
      @endphp

      <nav class="vs-nav">
        <ul style="list-style:none;padding:0;margin:0;">

          {{-- SECTION: MANAGEMENT --}}
          <div class="vs-section-label">Management</div>

          {{-- Dashboard --}}
          <li class="vs-item {{ $is('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="vs-link">
              <span class="vs-icon"><i class="fa fa-th-large"></i></span>
              Dashboard
            </a>
          </li>

          {{-- Clients --}}
          @php $clientsOpen = $open(['admin.clients.*']); @endphp
          <li class="vs-item {{ $clientsOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-users"></i></span>
              Clients
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.clients.index') }}"
                   class="vs-child-link {{ $is('admin.clients.index') ? 'current' : '' }}">
                  All Clients
                </a>
              </li>
            </ul>
          </li>

          {{-- Trading Accounts --}}
          @php $accountsOpen = $open(['admin.accounts.*']); @endphp
          <li class="vs-item {{ $accountsOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-exchange"></i></span>
              Trading Accounts
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.accounts.index') }}"
                   class="vs-child-link {{ $is('admin.accounts.index') ? 'current' : '' }}">
                  Connected Accounts
                </a>
              </li>
              <li>
                <a href="{{ route('admin.accounts.pending') }}"
                   class="vs-child-link {{ $is('admin.accounts.pending') ? 'current' : '' }}">
                  Pending Verification
                </a>
              </li>
            </ul>
          </li>

          {{-- Bots --}}
          @php $botsOpen = $open(['admin.bots.*']); @endphp
          <li class="vs-item {{ $botsOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-terminal"></i></span>
              Bots
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.bots.index') }}"
                   class="vs-child-link {{ $is('admin.bots.index') ? 'current' : '' }}">
                  All Bots
                </a>
              </li>
              <li>
                <a href="{{ route('admin.bots.logs') }}"
                   class="vs-child-link {{ $is('admin.bots.logs') ? 'current' : '' }}">
                  Bot Logs
                </a>
              </li>
            </ul>
          </li>

          {{-- SECTION: ACTIVITY & FINANCE --}}
          <div class="vs-divider"></div>
          <div class="vs-section-label">Activity &amp; Finance</div>

          {{-- Trading Activity --}}
          @php $tradingOpen = $open(['admin.signals.*','admin.trades.*']); @endphp
          <li class="vs-item {{ $tradingOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-line-chart"></i></span>
              Trading Activity
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.signals.index') }}"
                   class="vs-child-link {{ $is('admin.signals.index') ? 'current' : '' }}">
                  Signals
                </a>
              </li>
              <li>
                <a href="{{ route('admin.trades.index') }}"
                   class="vs-child-link {{ $is('admin.trades.index') ? 'current' : '' }}">
                  Trades
                </a>
              </li>
              <li>
                <a href="{{ route('admin.trades.statistics') }}"
                   class="vs-child-link {{ $is('admin.trades.statistics') ? 'current' : '' }}">
                  Statistics
                </a>
              </li>
            </ul>
          </li>

          {{-- Payments --}}
          @php $paymentsOpen = $open(['admin.payments.*','admin.banks.*','admin.subscription_plans.*']); @endphp
          <li class="vs-item {{ $paymentsOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-credit-card"></i></span>
              Payments
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.banks.index') }}"
                   class="vs-child-link {{ $is('admin.banks.index') ? 'current' : '' }}">
                  All Banks
                </a>
              </li>
              <li>
                <a href="{{ route('admin.payments.index') }}"
                   class="vs-child-link {{ $is('admin.payments.index') ? 'current' : '' }}">
                  All Payments
                </a>
              </li>
              <li>
                <a href="{{ route('admin.subscription_plans.index') }}"
                   class="vs-child-link {{ $is('admin.subscription_plans.index') ? 'current' : '' }}">
                  Subscription Plans
                </a>
              </li>
              <li>
                <a href="{{ route('admin.payments.reports') }}"
                   class="vs-child-link {{ $is('admin.payments.reports') ? 'current' : '' }}">
                  Payment Reports
                </a>
              </li>
            </ul>
          </li>

          {{-- SECTION: SYSTEM --}}
          <div class="vs-divider"></div>
          <div class="vs-section-label">System</div>

          {{-- Settings --}}
          @php $settingsOpen = $open(['admin.users.*','admin.roles.*','admin.settings.*']); @endphp
          <li class="vs-item {{ $settingsOpen ? 'active' : '' }}" data-toggle-group>
            <a class="vs-link" onclick="vsSub(this)">
              <span class="vs-icon"><i class="fa fa-sliders"></i></span>
              System Settings
              <i class="fa fa-chevron-down vs-chevron"></i>
            </a>
            <ul class="vs-children">
              <li>
                <a href="{{ route('admin.users.index') }}"
                   class="vs-child-link {{ $is('admin.users.index') ? 'current' : '' }}">
                  Admin Users
                </a>
              </li>
              <li>
                <a href="{{ route('admin.roles.index') }}"
                   class="vs-child-link {{ $is('admin.roles.index') ? 'current' : '' }}">
                  Roles &amp; Permissions
                </a>
              </li>
              <li>
                <a href="{{ route('admin.settings.index') }}"
                   class="vs-child-link {{ $is('admin.settings.index') ? 'current' : '' }}">
                  General Settings
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>

      {{-- ── FOOTER ── --}}
      <div class="vs-footer">
        <a class="vs-footer-btn" href="{{ route('admin.settings.index') }}"
           title="Settings" data-toggle="tooltip" data-placement="top">
          <i class="fa fa-cog"></i>
        </a>
        <a class="vs-footer-btn" href="#" title="Fullscreen"
           onclick="toggleFullScreen(); return false;"
           data-toggle="tooltip" data-placement="top">
          <i class="fa fa-arrows-alt"></i>
        </a>
        <a class="vs-footer-btn" href="#" title="Lock"
           onclick="return false;"
           data-toggle="tooltip" data-placement="top">
          <i class="fa fa-lock"></i>
        </a>
        <a class="vs-footer-btn danger"
           href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           title="Sign Out" data-toggle="tooltip" data-placement="top">
          <i class="fa fa-power-off"></i>
        </a>
      </div>

    </div>{{-- /v-sidebar --}}

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    <script>
      function vsSub(el) {
        var li = el.closest('.vs-item');
        var isOpen = li.classList.contains('open');
        // close all siblings
        li.closest('ul').querySelectorAll('.vs-item.open').forEach(function(s) {
          if (s !== li) s.classList.remove('open');
        });
        li.classList.toggle('open', !isOpen);
      }
    </script>

  </div>
</div>