<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <!-- Site Title -->
    <div class="navbar nav_title" style="border: 0;">
      <a href="" class="site_title">
        <i class="fa fa-robot"></i> <span>Trading Bot</span>
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
            <a href="{{url('/user/dashboard')}}">
              <i class="fa fa-home"></i> Dashboard
            </a>
          </li>

          <li>
            <a><i class="fa fa-exchange"></i> Trading Activity <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="">Trade History</a></li>
              <li><a href="">Open Positions</a></li>
              
            </ul>
          </li>

          <li>
            <a><i class="fa fa-credit-card"></i> Payments <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="">All Payments</a></li>
              <li><a href="">Reports</a></li>
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
