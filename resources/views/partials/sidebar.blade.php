<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <!-- Site Title -->
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{ route('admin.dashboard') }}" class="site_title">
        <i class="fa fa-robot"></i> <span>Bot Manager</span>
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- Profile Info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="{{ asset('img/bot_logo.png') }}" alt="Profile Picture" class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2>{{ Auth::user()->name ?? 'Admin' }}</h2>
      </div>
    </div>

    <br />

    @php
      // helper shortcuts for active/open states
      $is = fn($pattern) => request()->routeIs($pattern);
      $open = fn($patterns) => collect((array)$patterns)->contains(fn($p) => request()->routeIs($p));
    @endphp

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Main Menu</h3>
        <ul class="nav side-menu">

          {{-- Dashboard --}}
          <li class="{{ $is('admin.dashboard') ? 'active current-page' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
              <i class="fa fa-home"></i> Dashboard
            </a>
          </li>

          {{-- Clients Management --}}
          @php $clientsOpen = $open(['admin.clients.*']); @endphp
          <li class="{{ $clientsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-users"></i> Clients <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $clientsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.clients.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.clients.index') }}">All Clients</a>
              </li>
              <li>
                <a href="#" onclick="return false;">Subscriptions</a>
              </li>
            </ul>
          </li>

          {{-- Trading Accounts --}}
          @php $accountsOpen = $open(['admin.accounts.*']); @endphp
          <li class="{{ $accountsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-link"></i> Trading Accounts <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $accountsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.accounts.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.accounts.index') }}">Connected Accounts</a>
              </li>
              <li class="{{ $is('admin.accounts.pending') ? 'current-page' : '' }}">
                <a href="{{ route('admin.accounts.pending') }}">Pending Verification</a>
              </li>
            </ul>
          </li>

          {{-- Bots --}}
          @php $botsOpen = $open(['admin.bots.*']); @endphp
          <li class="{{ $botsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-robot"></i> Bots <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $botsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.bots.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.bots.index') }}">All Bots</a>
              </li>
              <li class="{{ $is('admin.bots.logs') ? 'current-page' : '' }}">
                <a href="{{ route('admin.bots.logs') }}">Bot Logs</a>
              </li>
            </ul>
          </li>

          {{-- Trading Activity --}}
          @php $tradingOpen = $open(['admin.signals.*','admin.trades.*']); @endphp
          <li class="{{ $tradingOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-chart-line"></i> Trading Activity <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $tradingOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.signals.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.signals.index') }}">Signals</a>
              </li>
              <li class="{{ $is('admin.trades.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.trades.index') }}">Trades</a>
              </li>
              <li class="{{ $is('admin.trades.statistics') ? 'current-page' : '' }}">
                <a href="{{ route('admin.trades.statistics') }}">Statistics</a>
              </li>
              <li class="{{ $is('admin.trades.symbols') ? 'current-page' : '' }}">
                <a href="{{ route('admin.trades.symbols') }}">Symbols</a>
              </li>
            </ul>
          </li>

          {{-- Payments --}}
          @php $paymentsOpen = $open(['admin.payments.*']); @endphp
          <li class="{{ $paymentsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-credit-card"></i> Payments <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $paymentsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.banks.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.banks.index') }}">All Banks</a>
              </li>
              <li class="{{ $is('admin.payments.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.payments.index') }}">All Payments</a>
              </li>
              <li class="{{ $is('admin.subscription_plans.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.subscription_plans.index') }}">Subscription Plans</a>
              </li>
              <li class="{{ $is('admin.payments.reports') ? 'current-page' : '' }}">
                <a href="{{ route('admin.payments.reports') }}">Payment Reports</a>
              </li>
            </ul>
          </li>

          {{-- Settings --}}
          @php $settingsOpen = $open(['admin.users.*','admin.roles.*','admin.settings.*']); @endphp
          <li class="{{ $settingsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-cogs"></i> System Settings <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $settingsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.users.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.users.index') }}">Admin Users</a>
              </li>
              <li class="{{ $is('admin.roles.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.roles.index') }}">Roles & Permissions</a>
              </li>
              <li class="{{ $is('admin.settings.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.settings.index') }}">General Settings</a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </div>

    <!-- Footer Buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings" href="{{ route('admin.settings.index') }}">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>

      <a data-toggle="tooltip" data-placement="top" title="FullScreen" href="#" onclick="toggleFullScreen(); return false;">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
      </a>

      <a data-toggle="tooltip" data-placement="top" title="Lock" href="#" onclick="return false;">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
      </a>

      <a data-toggle="tooltip" data-placement="top" title="Logout"
         href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>

  </div>
</div>

<script>
  function toggleFullScreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  }
</script>
