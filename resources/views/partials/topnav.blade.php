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
                    <div class="dropdown-menu dropdown-usermenu border-0 shadow pull-right mt-2" aria-labelledby="navbarDropdown" style="min-width: 240px;">
                        <!-- Profile Section -->
                        <a class="dropdown-item py-3 px-3 rounded-top" href="{{ route('user.profile.index') }}" 
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-user mr-3" style="font-size: 16px; width: 20px; color: #5B7A9E;"></i>
                                <div>
                                    <div class="font-weight-600" style="font-size: 14px;">Profile</div>
                                    <small class="text-muted" style="font-size: 12px;">Manage your account</small>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Settings Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('user.profile.edit', Auth::user()->id) }}"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-cog mr-3" style="font-size: 16px; width: 20px; color: #5B7A9E;"></i>
                                    <div>
                                        <div class="font-weight-600" style="font-size: 14px;">Settings</div>
                                        <small class="text-muted" style="font-size: 12px;">Configure preferences</small>
                                    </div>
                                </div>
                                <span class="badge badge-danger" style="font-size: 11px; padding: 4px 8px;">50%</span>
                            </div>
                        </a>
                        
                        <!-- Help Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('help') }}"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-question-circle mr-3" style="font-size: 16px; width: 20px; color: #5B7A9E;"></i>
                                <div>
                                    <div class="font-weight-600" style="font-size: 14px;">Help & Support</div>
                                    <small class="text-muted" style="font-size: 12px;">Get assistance</small>
                                </div>
                            </div>
                        </a>
                        
                        <div class="dropdown-divider my-2"></div>
                        
                        <!-- Logout Section -->
                        <a class="dropdown-item py-3 px-3 rounded-bottom" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #E74C3C;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-sign-out mr-3" style="font-size: 16px; width: 20px; color: #E74C3C;"></i>
                                <div>
                                    <div class="font-weight-600" style="font-size: 14px;">Sign Out</div>
                                    <small class="text-muted" style="font-size: 12px;">End your session</small>
                                </div>
                            </div>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    
                    <style>
                        .dropdown-usermenu .dropdown-item:hover {
                            background-color: #f8f9fa !important;
                            border-left-color: #5B7A9E !important;
                            color: #2c3e50 !important;
                        }
                        
                        .dropdown-usermenu .dropdown-item:hover i {
                            color: #2c3e50 !important;
                        }
                    </style>
                </li>
            </ul>
        </nav>
    </div>
</div>