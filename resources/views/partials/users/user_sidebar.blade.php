<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <!-- Site Title -->
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{ route('user.dashboard') }}" class="site_title">
        <i class="fa fa-robot"></i> <span>Trading Bot</span>
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
        <h2>{{ Auth::user()->name ?? 'User' }}</h2>
        <small style="color:#cbd5e1;">
          {{ Auth::user()->phone_number ?? '' }}
        </small>
      </div>
    </div>

    <br />

    @php
      $is = fn($pattern) => request()->routeIs($pattern);
      $open = fn($patterns) => collect((array)$patterns)->contains(fn($p) => request()->routeIs($p));
    @endphp

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Main Menu</h3>
        <ul class="nav side-menu">

          {{-- Dashboard --}}
          <li class="{{ $is('user.dashboard') ? 'active current-page' : '' }}">
            <a href="{{ route('user.dashboard') }}">
              <i class="fa fa-home"></i> Dashboard
            </a>
          </li>

          {{-- Accounts --}}
          @php $accountsOpen = $open(['user.accounts.*']); @endphp
          <li class="{{ $accountsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-link"></i> My Accounts <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $accountsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('user.accounts.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.accounts.index') }}">Connected Accounts</a>
              </li>
            </ul>
          </li>

          {{-- Signals --}}
          @php $signalsOpen = $open(['user.signals.*','user.executions.*']); @endphp
          <li class="{{ $signalsOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-bolt"></i> Signals <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $signalsOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('user.signals.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.signals.index') }}">All Signals</a>
              </li>
              <li class="{{ $is('user.executions.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.executions.index') }}">EA Executions</a>
              </li>
            </ul>
          </li>

          {{-- Trading Activity --}}
          @php $tradingOpen = $open(['user.trades.*']); @endphp
          <li class="{{ $tradingOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-exchange"></i> Trading Activity <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $tradingOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('user.trades.open') ? 'current-page' : '' }}">
                <a href="{{ route('user.trades.open') }}">Open Positions</a>
              </li>
              <li class="{{ $is('user.trades.history') ? 'current-page' : '' }}">
                <a href="{{ route('user.trades.history') }}">Trade History</a>
              </li>
            </ul>
          </li>

          {{-- Billing --}}
          @php $billingOpen = $open(['user.payments.*','user.subscriptions.*']); @endphp
          <li class="{{ $billingOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-credit-card"></i> Billing <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $billingOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('user.subscriptions.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.subscriptions.index') }}">My Subscription</a>
              </li>
              <li class="{{ $is('user.payments.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.payments.index') }}">Payments</a>
              </li>
            </ul>
          </li>

          {{-- Account Settings --}}
          @php $profileOpen = $open(['user.profile.*','user.password.*']); @endphp
          <li class="{{ $profileOpen ? 'active' : '' }}">
            <a>
              <i class="fa fa-user"></i> My Account <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ $profileOpen ? 'display:block;' : '' }}">
              <li class="{{ $is('user.profile.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.profile.index') }}">Profile</a>
              </li>
              <li class="{{ $is('user.password.index') ? 'current-page' : '' }}">
                <a href="{{ route('user.password.index') }}">Change Password</a>
              </li>
            </ul>
          </li>

          {{-- Optional Support --}}
          <li>
            <a href="#" onclick="return false;">
              <i class="fa fa-life-ring"></i> Support
            </a>
          </li>

        </ul>
      </div>
    </div>

    <!-- Footer Buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Profile" href="{{ route('user.profile.index') }}">
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
         onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>

      <form id="logout-form-user" action="{{ route('logout') }}" method="POST" style="display:none;">
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
