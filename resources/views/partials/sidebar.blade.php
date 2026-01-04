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

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Main Menu</h3>
        <ul class="nav side-menu">

          {{-- Dashboard --}}
          <li>
            <a href="{{ route('admin.dashboard') }}">
              <i class="fa fa-home"></i> Dashboard
            </a>
          </li>

          {{-- Clients Management --}}
          <li>
            <a><i class="fa fa-users"></i> Clients <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.clients.index') }}">All Clients</a></li>
              <li><a href="">Subscriptions</a></li>
            </ul>
          </li>

          {{-- Trading Accounts --}}
          <li>
            <a><i class="fa fa-link"></i> Trading Accounts <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.accounts.index') }}">Connected Accounts</a></li>
              <li><a href="{{ route('admin.accounts.pending') }}">Pending Verification</a></li>
            </ul>
          </li>

          {{-- Bots --}}
          <li>
            <a><i class="fa fa-robot"></i> Bots <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.bots.index') }}">All Bots</a></li>
              <li><a href="{{ route('admin.bots.logs') }}">Bot Logs</a></li>
              <li><a href="{{ route('admin.bots.settings') }}">Bot Settings</a></li>
            </ul>
          </li>

          {{-- Trading Activity --}}
          <li>
            <a><i class="fa fa-chart-line"></i> Trading Activity <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.signals.index') }}">Signals</a></li>
              <li><a href="{{ route('admin.trades.index') }}">Trades</a></li>
              <li><a href="{{ route('admin.trades.statistics') }}">Statistics</a></li>
              <li><a href="{{ route('admin.trades.symbols') }}">Symbols</a></li>
            </ul>
          </li>

          {{-- Payments --}}
          <li>
            <a><i class="fa fa-credit-card"></i> Payments <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.payments.index') }}">All Payments</a></li>
              <li><a href="{{ route('admin.payments.plans') }}">Subscription Plans</a></li>
              <li><a href="{{ route('admin.payments.reports') }}">Payment Reports</a></li>
            </ul>
          </li>

          {{-- Settings --}}
          <li>
            <a><i class="fa fa-cogs"></i> System Settings <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('admin.users.index') }}">Admin Users</a></li>
              <li><a href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
              <li><a href="{{ route('admin.settings.index') }}">General Settings</a></li>
            </ul>
          </li>

        </ul>
      </div>
    </div>

    <!-- Footer Buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout"
         href=""
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>

      <form id="logout-form" action="" method="POST" style="display: none;">
        @csrf
      </form>
    </div>

  </div>
</div>
