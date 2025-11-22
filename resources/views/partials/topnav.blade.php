<!-- Top Navigation -->
<div class="top_nav">
    <div class="nav_menu d-flex justify-content-between align-items-center px-3">
        <!-- Toggle Menu Icon -->
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <!-- Right-side Nav -->
        <nav class="nav navbar-nav">
            <ul class="navbar-right d-flex align-items-center mb-0">

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown open pl-3">
                    <a href="#" class="user-profile dropdown-toggle" id="navbarDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('img/pfr_logo2.png') }}" alt="User Image"> 
                        {{ Auth::user()->name ?? 'Guest' }}
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="">Profile</a>
                        <a class="dropdown-item" href="#">
                            <span class="badge bg-red pull-right">50%</span>
                            <span>Settings</span>
                        </a>
                        <a class="dropdown-item" href="#">Help</a>
                        <a class="dropdown-item" href=""
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out pull-right"></i> Log Out
                        </a>

                        <form id="logout-form" action="" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>

                <!-- Notifications -->
                <li role="presentation" class="nav-item dropdown open ml-3">
                    <a href="#" class="dropdown-toggle info-number" id="navbarDropdown1"
                        data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="badge bg-green">3</span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                        <li class="nav-item">
                            <a class="dropdown-item">
                                <span class="image">
                                    <img src="{{ asset('img/user.png') }}" alt="Profile Image" />
                                </span>
                                <span>
                                    <span>System Alert</span>
                                    <span class="time">2 mins ago</span>
                                </span>
                                <span class="message">New vehicle entered Zone A</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="text-center">
                                <a class="dropdown-item">
                                    <strong>See All Notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</div>
<!-- /Top Navigation -->
