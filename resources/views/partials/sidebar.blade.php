<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <!-- Site Title -->
    <div class="navbar nav_title" style="border: 0;">
      <a href="" class="site_title">
        <i class="fa fa-car"></i> <span>Parking Manager</span>
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- Profile Info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="{{ asset('img/pfr_logo2.png') }}" alt="Profile Picture" class="img-circle profile_img">
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

          <li>
            <a href="{{route('admin.dashboard')}}">
              <i class="fa fa-home"></i> Dashboard
            </a>
          </li>

          <li>
            <a><i class="fa fa-th"></i> Parking Management <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{route('zones.index')}}">Zones</a></li>
              <li><a href="{{route('slots.index')}}">Parking Slots</a></li>
              <li><a href="{{route('vehicles.index')}}">Exempted Vehicles</a></li>
              <li><a href="{{route('logs.index')}}">Entry & Exit Logs</a></li>
            </ul>
          </li>

          <li>
            <a><i class="fa fa-credit-card"></i> Payments <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{route('admin.payments.index')}}">All Payments</a></li>
              <li><a href="{{route('admin.reports.index')}}">Reports</a></li>
            </ul>
          </li>

          <li>
            <a><i class="fa fa-cogs"></i> Settings <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{route('staff.index')}}">Users</a></li>
              <li><a href="{{route('rates.index')}}">Parking Rates</a></li>
              <li><a href="">System Settings</a></li>
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
         href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>

  </div>
</div>
