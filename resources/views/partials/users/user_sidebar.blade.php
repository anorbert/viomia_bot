<div class="col-md-12 left_col">
  <div class="left_col scroll-view" style="background: linear-gradient(180deg, #1f2937, #111827); padding: 0;">

    <!-- Site Title -->
    {{-- <div class="navbar nav_title" style="border: 0; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%); padding: 18px 15px; border-radius: 0; margin: 0; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);">
      <a href="{{ route('user.dashboard') }}" class="site_title" style="color: #fff; display: flex; align-items: center; gap: 12px; text-decoration: none; transition: all 0.3s ease;">
        <div style="background: rgba(255,255,255,0.2); width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
          <i class="fa fa-robot" style="font-size: 22px;"></i>
        </div>
        <div>
          <span style="font-weight: 800; font-size: 15px; display: block; letter-spacing: 0.3px;">TRADING BOT</span>
          <span style="font-size: 9px; opacity: 0.85; display: block; font-weight: 500;">Professional Trading</span>
        </div>
      </a>
    </div> --}}

    <div class="clearfix"></div>

    <!-- Profile Info -->
    <div class="profile clearfix" style="background: linear-gradient(135deg, #374151 0%, #2d3748 100%); padding: 16px 15px; margin: 15px 15px 0; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
      <div class="profile_pic">
        <div style="position: relative; display: inline-block; flex-shrink: 0;">
          @if(Auth::user()->profile_photo)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                 alt="..." class="img-circle profile_img" 
                 style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); display: block; border-radius: 50%;">
          @else
            <img src="{{ asset('img/bot_logo.png') }}" 
                 alt="..." class="img-circle profile_img" 
                 style="width: 48px; height: 48px; border: 2px solid #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); display: block; border-radius: 50%;">
          @endif
          <div style="position: absolute; bottom: 0; right: 0; width: 14px; height: 14px; background: #10b981; border: 2px solid #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
        </div>
      </div>
      <div class="profile_info" style="flex: 1;">
        @php
          $hour = now()->hour;
          if ($hour < 12) {
            $greeting = 'Good Morning';
          } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
          } else {
            $greeting = 'Good Evening';
          }
          
          $name = Auth::user()->name ?? 'User';
          $nameParts = explode(' ', trim($name));
          $displayName = $nameParts[0];
          if (count($nameParts) > 1) {
            $displayName .= ' ' . substr($nameParts[1], 0, 1) . '.';
          }
        @endphp
        <span style="font-size: 7px; color: #06b6d4; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">{{ $greeting }}</span>
        <h2 style="font-size: 12px; margin: 0 0 2px 0; color: #f1f5f9; font-weight: 800;">{{ $displayName }}</h2>
        <small style="color: #93c5fd; font-weight: 600; display: block; font-size: 9px;">
          💎 {{ Auth::user()->phone_number ?? 'Premium User' }}
        </small>
      </div>
    </div>

    <div style="height: 20px;"></div>

    @php
      $is = fn($pattern) => request()->routeIs($pattern);
      $open = fn($patterns) => collect((array)$patterns)->contains(fn($p) => request()->routeIs($p));
    @endphp

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3 style="color: #9ca3af; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; padding: 0 15px 12px; margin: 0; border-bottom: 2px solid #374151;">
          <i class="fa fa-compass" style="margin-right: 6px; color: #3b82f6;"></i>Main Menu
        </h3>
        <ul class="nav side-menu" style="padding: 12px 0;">

          {{-- Dashboard --}}
          <li class="{{ $is('user.dashboard') ? 'active current-page' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $is('user.dashboard') ? 'linear-gradient(90deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $is('user.dashboard') ? '#3b82f6' : 'transparent' }}; transition: all 0.3s ease;">
            <a href="{{ route('user.dashboard') }}" style="color: {{ $is('user.dashboard') ? '#60a5fa' : '#cbd5e1' }}; font-weight: {{ $is('user.dashboard') ? '700' : '500' }}; padding: 13px 15px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; text-decoration: none;">
              <i class="fa fa-home" style="font-size: 16px; width: 22px; text-align: center;"></i>
              <span style="font-size: 13px;">Dashboard</span>
              @if($is('user.dashboard'))
                <span style="margin-left: auto; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 8px rgba(59, 130, 246, 0.4);"></span>
              @endif
            </a>
          </li>

          {{-- Accounts --}}
          @php $accountsOpen = $open(['user.accounts.*']); @endphp
          <li class="{{ $accountsOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $accountsOpen ? 'linear-gradient(90deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $accountsOpen ? '#06b6d4' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $accountsOpen ? '#22d3ee' : '#cbd5e1' }}; font-weight: {{ $accountsOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-link" style="font-size: 16px; width: 22px; text-align: center; color: #06b6d4;"></i> My Accounts</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $accountsOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $accountsOpen ? 'display:block; background: linear-gradient(90deg, rgba(6, 182, 212, 0.1), rgba(6, 182, 212, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #06b6d4; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('user.accounts.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.accounts.index') }}" style="color: {{ $is('user.accounts.index') ? '#22d3ee' : '#cbd5e1' }}; font-weight: {{ $is('user.accounts.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-check-circle" style="color: #06b6d4; font-size: 13px;"></i><span style="font-size: 13px;">Connected Accounts</span>
                </a>
              </li>
            </ul>
          </li>

          {{-- Signals --}}
          @php $signalsOpen = $open(['user.signals.*','user.executions.*']); @endphp
          <li class="{{ $signalsOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $signalsOpen ? 'linear-gradient(90deg, rgba(236, 72, 153, 0.15), rgba(236, 72, 153, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $signalsOpen ? '#ec4899' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $signalsOpen ? '#f472b6' : '#cbd5e1' }}; font-weight: {{ $signalsOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-bolt" style="font-size: 16px; width: 22px; text-align: center; color: #ec4899;"></i> Signals</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $signalsOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $signalsOpen ? 'display:block; background: linear-gradient(90deg, rgba(236, 72, 153, 0.1), rgba(236, 72, 153, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #ec4899; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('user.signals.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.signals.index') }}" style="color: {{ $is('user.signals.index') ? '#f472b6' : '#cbd5e1' }}; font-weight: {{ $is('user.signals.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-zap" style="color: #ec4899; font-size: 13px;"></i><span style="font-size: 13px;">All Signals</span>
                </a>
              </li>
              <li class="{{ $is('user.executions.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.executions.index') }}" style="color: {{ $is('user.executions.index') ? '#f472b6' : '#cbd5e1' }}; font-weight: {{ $is('user.executions.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-send" style="color: #ec4899; font-size: 13px;"></i><span style="font-size: 13px;">EA Executions</span>
                </a>
              </li>
            </ul>
          </li>

          {{-- Trading Activity --}}
          @php $tradingOpen = $open(['user.trades.*']); @endphp
          <li class="{{ $tradingOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $tradingOpen ? 'linear-gradient(90deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $tradingOpen ? '#10b981' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $tradingOpen ? '#4ade80' : '#cbd5e1' }}; font-weight: {{ $tradingOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-exchange" style="font-size: 16px; width: 22px; text-align: center; color: #10b981;"></i> Trading Activity</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $tradingOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $tradingOpen ? 'display:block; background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #10b981; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('user.trades.open') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.trades.open') }}" style="color: {{ $is('user.trades.open') ? '#4ade80' : '#cbd5e1' }}; font-weight: {{ $is('user.trades.open') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-arrow-up" style="color: #10b981; font-size: 13px;"></i><span style="font-size: 13px;">Open Positions</span>
                </a>
              </li>
              <li class="{{ $is('user.trades.history') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.trades.history') }}" style="color: {{ $is('user.trades.history') ? '#4ade80' : '#cbd5e1' }}; font-weight: {{ $is('user.trades.history') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-history" style="color: #10b981; font-size: 13px;"></i><span style="font-size: 13px;">Trade History</span>
                </a>
              </li>
            </ul>
          </li>

          {{-- Billing --}}
          @php $billingOpen = $open(['user.payments.*','user.subscriptions.*']); @endphp
          <li class="{{ $billingOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $billingOpen ? 'linear-gradient(90deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $billingOpen ? '#f59e0b' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $billingOpen ? '#fbbf24' : '#cbd5e1' }}; font-weight: {{ $billingOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-credit-card" style="font-size: 16px; width: 22px; text-align: center; color: #f59e0b;"></i> Billing</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $billingOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $billingOpen ? 'display:block; background: linear-gradient(90deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #f59e0b; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('user.subscriptions.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.subscriptions.index') }}" style="color: {{ $is('user.subscriptions.index') ? '#fbbf24' : '#cbd5e1' }}; font-weight: {{ $is('user.subscriptions.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-star" style="color: #f59e0b; font-size: 13px;"></i><span style="font-size: 13px;">My Subscription</span>
                </a>
              </li>
              <li class="{{ $is('user.payments.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.payments.index') }}" style="color: {{ $is('user.payments.index') ? '#fbbf24' : '#cbd5e1' }}; font-weight: {{ $is('user.payments.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-money" style="color: #f59e0b; font-size: 13px;"></i><span style="font-size: 13px;">Payments</span>
                </a>
              </li>
            </ul>
          </li>

          {{-- Account Settings --}}
          @php $profileOpen = $open(['user.profile.*','user.password.*']); @endphp
          <li class="{{ $profileOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $profileOpen ? 'linear-gradient(90deg, rgba(139, 92, 246, 0.15), rgba(139, 92, 246, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $profileOpen ? '#8b5cf6' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $profileOpen ? '#c4b5fd' : '#cbd5e1' }}; font-weight: {{ $profileOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-user" style="font-size: 16px; width: 22px; text-align: center; color: #8b5cf6;"></i> My Account</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $profileOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $profileOpen ? 'display:block; background: linear-gradient(90deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #8b5cf6; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('user.profile.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.profile.index') }}" style="color: {{ $is('user.profile.index') ? '#c4b5fd' : '#cbd5e1' }}; font-weight: {{ $is('user.profile.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-id-card" style="color: #8b5cf6; font-size: 13px;"></i><span style="font-size: 13px;">Profile</span>
                </a>
              </li>
              <li class="{{ $is('user.password.index') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('user.password.index') }}" style="color: {{ $is('user.password.index') ? '#c4b5fd' : '#cbd5e1' }}; font-weight: {{ $is('user.password.index') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-lock" style="color: #8b5cf6; font-size: 13px;"></i><span style="font-size: 13px;">Change Password</span>
                </a>
              </li>
            </ul>
          </li>

          {{-- Support & Help --}}
          @php $supportOpen = $open(['help','user.support.*']); @endphp
          <li class="{{ $supportOpen ? 'active' : '' }}" style="border-radius: 8px; margin: 0 12px 8px; background: {{ $supportOpen ? 'linear-gradient(90deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.08))' : 'transparent' }}; border-left: 4px solid {{ $supportOpen ? '#06b6d4' : 'transparent' }}; transition: all 0.3s ease;">
            <a onclick="toggleDropdown(this)" style="color: {{ $supportOpen ? '#22d3ee' : '#cbd5e1' }}; font-weight: {{ $supportOpen ? '700' : '500' }}; padding: 13px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; text-decoration: none;">
              <span style="display: flex; align-items: center; gap: 12px;"><i class="fa fa-life-ring" style="font-size: 16px; width: 22px; text-align: center; color: #06b6d4;"></i> Support</span>
              <span class="fa fa-chevron-down" style="font-size: 12px; transition: transform 0.3s; transform: {{ $supportOpen ? 'rotate(180deg)' : 'rotate(0)' }};"></span>
            </a>
            <ul class="nav child_menu" style="{{ $supportOpen ? 'display:block; background: linear-gradient(90deg, rgba(6, 182, 212, 0.1), rgba(6, 182, 212, 0.05)); border-radius: 8px; margin: 6px 12px 8px; border-left: 4px solid #06b6d4; padding: 8px 0;' : 'display: none;' }}">
              <li class="{{ $is('help') ? 'current-page' : '' }}" style="padding: 0;">
                <a href="{{ route('help') }}" style="color: {{ $is('help') ? '#22d3ee' : '#cbd5e1' }}; font-weight: {{ $is('help') ? '700' : '500' }}; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-question-circle" style="color: #06b6d4; font-size: 13px;"></i><span style="font-size: 13px;">Help & FAQ</span>
                </a>
              </li>
              <li style="padding: 0;">
                <a href="mailto:support@viomiabot.com" style="color: #cbd5e1; font-weight: 500; padding: 9px 12px 9px 45px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; text-decoration: none;">
                  <i class="fa fa-envelope" style="color: #06b6d4; font-size: 13px;"></i><span style="font-size: 13px;">Email Support</span>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </div>

    <!-- Footer Buttons -->
    <div class="sidebar-footer hidden-small" style="background: linear-gradient(180deg, #1f2937, #111827); border-top: 2px solid #374151; padding: 12px; display: flex; flex-direction: column; gap: 10px; align-items: center; margin-top: auto;">
      <!-- Action Buttons Row 1 -->
      <div style="display: flex; gap: 10px; width: 100%; justify-content: center;">
        <a data-toggle="tooltip" data-placement="top" title="Settings" href="{{ route('user.profile.index') }}" class="footer-btn" style="width: 24px; height: 24px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2); border: 1px solid rgba(139, 92, 246, 0.3); position: relative; overflow: hidden;">
          <i class="fa fa-cog" style="font-size: 10px; position: relative; z-index: 2;"></i>
        </a>

        <a data-toggle="tooltip" data-placement="top" title="Full Screen" href="#" onclick="toggleFullScreen(); return false;" class="footer-btn" style="width: 24px; height: 24px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 6px rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.3); position: relative; overflow: hidden;">
          <i class="fa fa-window-maximize" style="font-size: 10px; position: relative; z-index: 2;"></i>
        </a>

        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();" class="footer-btn" style="width: 24px; height: 24px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.3); position: relative; overflow: hidden;">
          <i class="fa fa-sign-out" style="font-size: 10px; position: relative; z-index: 2;"></i>
        </a>
      </div>
      
      <!-- Version Info Row 2 -->
      <div style="text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 4px;">
        <div style="font-size: 8px; color: #9ca3af; font-weight: 600;">v1.0.0</div>
        <div style="width: 1px; height: 12px; background: #374151;"></div>
        <div style="font-size: 8px; color: #6b7280;">Developed by <span style="color: #3b82f6; font-weight: 700;">VIOMIA</span></div>
      </div>

      <form id="logout-form-user" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>

  </div>
</div>

<script>
  // Sidebar dropdown toggle function
  function toggleDropdown(element) {
    event.preventDefault();
    const menu = element.nextElementSibling;
    const chevron = element.querySelector('.fa-chevron-down');
    
    if (menu && menu.classList.contains('child_menu')) {
      const isVisible = menu.style.display === 'block';
      menu.style.display = isVisible ? 'none' : 'block';
      
      if (chevron) {
        chevron.style.transform = isVisible ? 'rotate(0)' : 'rotate(180deg)';
      }
    }
  }
  
  function toggleFullScreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  }
</script>
