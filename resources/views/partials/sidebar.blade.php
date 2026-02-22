<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <div class="navbar nav_title d-flex align-items-center" style="border: 0; padding-left: 15px;">
  <a href="{{ route('admin.dashboard') }}" class="site_title d-flex align-items-center">
    <div class="bg-success d-flex align-items-center justify-content-center rounded mr-2" style="width: 30px; height: 30px;">
        <i class="fa fa-robot text-white" style="font-size: 16px;"></i>
    </div>
    <span class="font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">
        {{ config('app.name', 'BOT MANAGER') }}
    </span>
  </a>
</div>

    <div class="clearfix"></div>

    <div class="profile clearfix mb-3">
      <div class="profile_pic">
        @if(Auth::user()->profile_photo)
          <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
               alt="..." class="img-circle profile_img shadow-sm" 
               style="width: 56px; height: 56px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
        @else
          <img src="{{ asset('img/bot_logo.png') }}" 
               alt="..." class="img-circle profile_img shadow-sm" style="border: 2px solid rgba(255,255,255,0.2);">
        @endif
      </div>
      <div class="profile_info" style="padding: 10px 10px 10px;">
        <span class="text-muted small uppercase" style="font-size: 10px; letter-spacing: 1px;">CONNECTED AS</span>
        <h2 class="font-weight-bold" style="font-size: 12px; margin-top: 2px;">{{ Auth::user()->name ?? 'Admin' }}</h2>
      </div>
    </div>

    <br />

    @php
      $is = fn($pattern) => request()->routeIs($pattern);
      $open = fn($patterns) => collect((array)$patterns)->contains(fn($p) => request()->routeIs($p));
    @endphp

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3 class="text-uppercase" style="font-size: 10px; padding-left: 20px; color: #5A738E; letter-spacing: 1.5px;">MANAGEMENT</h3>
        <ul class="nav side-menu">

          {{-- Dashboard --}}
          <li class="{{ $is('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
              <i class="fa fa-th-large"></i> Dashboard
            </a>
          </li>

          {{-- Clients Management --}}
          @php $clientsOpen = $open(['admin.clients.*']); @endphp
          <li class="{{ $clientsOpen ? 'active' : '' }}">
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-users"></i> Clients</span>
              <span class="fa fa-chevron-down small"></span>
            </a>
            <ul class="nav child_menu" style="{{ $clientsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('admin.clients.index') ? 'current-page' : '' }}">
                <a href="{{ route('admin.clients.index') }}">All Clients</a>
              </li>
              <li><a href="#" onclick="return false;">Subscriptions</a></li>
            </ul>
          </li>

          {{-- Trading Accounts --}}
          @php $accountsOpen = $open(['admin.accounts.*']); @endphp
          <li class="{{ $accountsOpen ? 'active' : '' }}">
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-exchange"></i> Trading Accounts</span>
              <span class="fa fa-chevron-down small"></span>
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
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-terminal"></i> Bots</span>
              <span class="fa fa-chevron-down small"></span>
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

          <li class="divider" style="height: 1px; background: rgba(255,255,255,0.05); margin: 10px 20px;"></li>
          <h3 class="text-uppercase" style="font-size: 10px; padding-left: 20px; color: #5A738E; letter-spacing: 1.5px;">ACTIVITY & FINANCE</h3>

          {{-- Trading Activity --}}
          @php $tradingOpen = $open(['admin.signals.*','admin.trades.*']); @endphp
          <li class="{{ $tradingOpen ? 'active' : '' }}">
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-line-chart"></i> Trading Activity</span>
              <span class="fa fa-chevron-down small"></span>
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
          @php $paymentsOpen = $open(['admin.payments.*','admin.banks.*','admin.subscription_plans.*']); @endphp
          <li class="{{ $paymentsOpen ? 'active' : '' }}">
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-credit-card"></i> Payments</span>
              <span class="fa fa-chevron-down small"></span>
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

          <li class="divider" style="height: 1px; background: rgba(255,255,255,0.05); margin: 10px 20px;"></li>
          
          {{-- Settings --}}
          @php $settingsOpen = $open(['admin.users.*','admin.roles.*','admin.settings.*']); @endphp
          <li class="{{ $settingsOpen ? 'active' : '' }}">
            <a class="d-flex justify-content-between align-items-center">
              <span><i class="fa fa-sliders"></i> System Settings</span>
              <span class="fa fa-chevron-down small"></span>
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

    <div class="sidebar-footer hidden-small" style="background: #172D44;">
      <a data-toggle="tooltip" data-placement="top" title="Settings" href="{{ route('admin.settings.index') }}">
        <i class="fa fa-cog"></i>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="FullScreen" href="#" onclick="toggleFullScreen(); return false;">
        <i class="fa fa-arrows-alt"></i>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Lock" href="#" onclick="return false;">
        <i class="fa fa-lock"></i>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout" 
         href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa fa-power-off text-danger"></i>
      </a>
    </div>
  </div>
</div>