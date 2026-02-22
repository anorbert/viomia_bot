<div class="top_nav">
    <div class="nav_menu d-flex justify-content-between align-items-center px-4 shadow-sm" style="background: #fff; height: 57px;">
        
        <div class="d-flex align-items-center">
            <div class="nav toggle mr-3">
                <a id="menu_toggle" class="text-dark" style="cursor: pointer; font-size: 20px;">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="d-none d-md-block">
                <span class="badge badge-success shadow-sm" style="font-size: 0.75rem;">
                    <i class="fa fa-circle mr-1 animate-pulse"></i> Bot System: Active
                </span>
            </div>
        </div>

        <nav class="nav navbar-nav ml-auto">
            <ul class="navbar-right d-flex align-items-center mb-0 list-unstyled">
                
                <li role="presentation" class="nav-item dropdown mr-3">
                    <a href="#" class="dropdown-toggle info-number position-relative" id="navbarDropdown1" 
                       data-toggle="dropdown" aria-expanded="false" style="font-size: 18px; color: #5A738E;">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge bg-green position-absolute" style="top: -5px; right: -5px; font-size: 9px;">3</span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list border-0 shadow" role="menu" aria-labelledby="navbarDropdown1" style="width: 300px;">
                        <li class="nav-item border-bottom">
                            <a class="dropdown-item d-flex align-items-center py-2">
                                <span class="image mr-2">
                                    <img src="{{ asset('img/user.png') }}" alt="Profile Image" class="rounded-circle" width="30"/>
                                </span>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <strong class="small">System Alert</strong>
                                        <span class="time text-muted small">2 mins ago</span>
                                    </div>
                                    <span class="message small text-muted">New trade opened successfully...</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="text-center py-2">
                                <a class="dropdown-item small font-weight-bold text-primary">
                                    See All Notifications <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown pl-2" style="border-left: 1px solid #e5e5e5;">
                    <a href="#" class="user-profile dropdown-toggle d-flex align-items-center" id="navbarDropdown" 
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: #5A738E; font-weight: 600;">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                             alt="" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                        <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu border-0 shadow pull-right mt-2" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item py-2" href="#"><i class="fa fa-user mr-2"></i> Profile</a>
                        <a class="dropdown-item py-2" href="#">
                            <span class="badge bg-red pull-right text-white">50%</span>
                            <i class="fa fa-cog mr-2"></i> Settings
                        </a>
                        <a class="dropdown-item py-2" href="#"><i class="fa fa-question-circle mr-2"></i> Help</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out mr-2"></i> <strong>Log Out</strong>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>