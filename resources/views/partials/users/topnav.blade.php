<div class="top_nav">
    <div class="nav_menu" style="background: linear-gradient(90deg, #ffffff, #f8fafc); height: 46px; border-bottom: 1px solid #e5e7eb; width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <!-- Left Section: Menu Toggle & Status -->
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            <div class="nav toggle">
                <a id="menu_toggle" class="text-dark" style="cursor: pointer; font-size: 16px; transition: all 0.3s ease; display: inline-block; padding: 4px;">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="d-none d-md-block">
                <span class="badge shadow-sm" style="font-size: 0.7rem; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 3px 8px; border-radius: 6px;">
                    <i class="fa fa-circle mr-1" style="font-size: 5px;"></i> Bot System: Active
                </span>
            </div>
        </div>

        <!-- Right Section: Notifications & Profile -->
        <nav style="display: flex; align-items: center;">
            <ul style="display: flex; align-items: center; list-style: none; margin: 0; padding: 0; gap: 6px;">
                
                {{-- Notifications Dropdown --}}
                <li style="position: relative; display: inline-flex; align-items: center;">
                    <a href="#" class="dropdown-toggle info-number" id="navbarDropdown1" 
                       data-toggle="dropdown" aria-expanded="false" style="font-size: 16px; color: #5A738E; transition: all 0.3s ease; display: inline-block; position: relative; text-decoration: none; padding: 4px 6px;">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge" style="position: absolute; top: -8px; right: -8px; font-size: 8px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; min-width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1" style="width: 350px; border-radius: 10px; overflow: hidden; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: none; position: absolute; top: 100%; left: 0; margin-top: 8px; background: white; z-index: 1000;">
                        <li class="nav-item border-bottom" style="background: linear-gradient(135deg, #eff6ff, #f0fbff);">
                            <a class="dropdown-item d-flex align-items-center py-3 px-3" style="border: none;">
                                <span class="image mr-3">
                                    <img src="{{ asset('img/user.png') }}" alt="Profile Image" class="rounded-circle" width="35" style="border: 2px solid #3b82f6;"/>
                                </span>
                                <div style="flex-grow: 1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="small" style="color: #1e293b;">Trade Opened</strong>
                                        <span class="time text-muted small" style="font-size: 11px;">2 mins</span>
                                    </div>
                                    <span class="message small text-muted">EURUSD +50 pips profit...</span>
                                </div>
                                <i class="fa fa-check-circle" style="color: #10b981; margin-left: 8px;"></i>
                            </a>
                        </li>
                        <li class="nav-item border-bottom" style="background: linear-gradient(135deg, #fef2f2, #fef5f5);">
                            <a class="dropdown-item d-flex align-items-center py-3 px-3" style="border: none;">
                                <span class="image mr-3">
                                    <img src="{{ asset('img/user.png') }}" alt="Profile Image" class="rounded-circle" width="35" style="border: 2px solid #ef4444;"/>
                                </span>
                                <div style="flex-grow: 1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="small" style="color: #1e293b;">Risk Alert</strong>
                                        <span class="time text-muted small" style="font-size: 11px;">15 mins</span>
                                    </div>
                                    <span class="message small text-muted">Margin usage above 70%</span>
                                </div>
                                <i class="fa fa-exclamation-circle" style="color: #ef4444; margin-left: 8px;"></i>
                            </a>
                        </li>
                        <li class="nav-item border-bottom" style="background: linear-gradient(135deg, #fffbeb, #fffcf2);">
                            <a class="dropdown-item d-flex align-items-center py-3 px-3" style="border: none;">
                                <span class="image mr-3">
                                    <img src="{{ asset('img/user.png') }}" alt="Profile Image" class="rounded-circle" width="35" style="border: 2px solid #f59e0b;"/>
                                </span>
                                <div style="flex-grow: 1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="small" style="color: #1e293b;">Signal Executed</strong>
                                        <span class="time text-muted small" style="font-size: 11px;">1 hour</span>
                                    </div>
                                    <span class="message small text-muted">BUY signal executed on GBPUSD</span>
                                </div>
                                <i class="fa fa-info-circle" style="color: #f59e0b; margin-left: 8px;"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="text-center py-3" style="background: #f9fafb; border-top: 1px solid #e5e7eb;">
                                <a class="small font-weight-bold" style="color: #3b82f6; text-decoration: none;">
                                    <i class="fa fa-angle-right mr-1"></i>See All Notifications
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

                {{-- User Profile Dropdown --}}
                <li style="position: relative; display: inline-flex; align-items: center; border-left: 1px solid #e5e7eb; padding-left: 6px;">
                    <a href="#" class="user-profile dropdown-toggle" id="navbarDropdown" 
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: #1e293b; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; padding: 0 4px;">
                        @if(Auth::user()->profile_photo)
                          <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                               alt="" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover; border: 2px solid #3b82f6; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);">
                        @else
                          <img src="{{ asset('img/pfr_logo2.png') }}" 
                               alt="" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover; border: 2px solid #3b82f6; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);">
                        @endif
                        <span class="d-none d-lg-inline" style="font-size: 11px;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; color: #5a738e;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu" aria-labelledby="navbarDropdown" style="min-width: 280px; border-radius: 10px; overflow: hidden; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; z-index: 1000;">
                        
                        <!-- Profile Header -->
                        <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 15px; text-align: center;">
                            @if(Auth::user()->profile_photo)
                              <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                   alt="" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; margin-bottom: 10px; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @else
                              <img src="{{ asset('img/pfr_logo2.png') }}" 
                                   alt="" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; margin-bottom: 10px; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @endif
                            <div class="fw-bold" style="font-size: 14px;">{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <small style="opacity: 0.9;">Premium User Account</small>
                        </div>
                        
                        <!-- Profile Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('user.profile.index') }}" 
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E; border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-user" style="font-size: 16px; width: 24px; color: #3b82f6;"></i>
                                <div style="margin-left: 12px;">
                                    <div class="font-weight-600" style="font-size: 14px; color: #1e293b;">Profile</div>
                                    <small class="text-muted" style="font-size: 11px;">Manage your account</small>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Settings Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('user.profile.edit', Auth::user()->id) }}"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E; border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-cog" style="font-size: 16px; width: 24px; color: #8b5cf6;"></i>
                                    <div style="margin-left: 12px;">
                                        <div class="font-weight-600" style="font-size: 14px; color: #1e293b;">Settings</div>
                                        <small class="text-muted" style="font-size: 11px;">Configure preferences</small>
                                    </div>
                                </div>
                                <span class="badge" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; font-size: 10px; padding: 4px 8px;">50%</span>
                            </div>
                        </a>
                        
                        <!-- Help Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('help') }}"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #5A738E; border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-question-circle" style="font-size: 16px; width: 24px; color: #06b6d4;"></i>
                                <div style="margin-left: 12px;">
                                    <div class="font-weight-600" style="font-size: 14px; color: #1e293b;">Help & Support</div>
                                    <small class="text-muted" style="font-size: 11px;">Get assistance</small>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Logout Section -->
                        <a class="dropdown-item py-3 px-3" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="transition: all 0.3s ease; border-left: 3px solid transparent; color: #E74C3C; border-radius: 0 0 10px 10px; background: linear-gradient(135deg, #fef2f2, #fef5f5);">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-sign-out" style="font-size: 16px; width: 24px; color: #E74C3C;"></i>
                                <div style="margin-left: 12px;">
                                    <div class="font-weight-600" style="font-size: 14px;">Sign Out</div>
                                    <small style="color: #E74C3C; font-size: 11px;">End your session</small>
                                </div>
                            </div>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    
                    <style>
                        .dropdown-usermenu .dropdown-item:hover {
                            background-color: #f0f9ff !important;
                            border-left-color: #3b82f6 !important;
                            color: #2c3e50 !important;
                            padding-left: 12px;
                        }
                        
                        .dropdown-usermenu .dropdown-item:hover i {
                            color: #3b82f6 !important;
                        }
                        
                        .dropdown-usermenu .dropdown-item:hover div {
                            color: #2c3e50 !important;
                        }
                        
                        .nav_menu .dropdown-toggle:hover {
                            color: #3b82f6 !important;
                        }
                    </style>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
  // Initialize dropdown toggles - PROFESSIONAL IMPLEMENTATION
  document.addEventListener('DOMContentLoaded', function() {
    // Get all dropdown toggles
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Find the dropdown menu (next sibling ul)
        const menu = this.nextElementSibling;
        
        if (menu && menu.classList.contains('dropdown-menu')) {
          const isVisible = menu.style.display === 'block';
          
          // Close all other dropdowns
          document.querySelectorAll('.dropdown-menu').forEach(m => {
            m.style.display = 'none';
          });
          
          // Toggle current dropdown
          menu.style.display = isVisible ? 'none' : 'block';
        }
      });
    });
    
    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
      const isClickInsideDropdown = e.target.closest('li');
      if (!isClickInsideDropdown) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
          menu.style.display = 'none';
        });
      }
    });
    
    // Close dropdowns on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
          menu.style.display = 'none';
        });
      }
    });
  });
</script>