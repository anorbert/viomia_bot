{{-- resources/views/layouts/partials/top_nav.blade.php --}}

<div class="top_nav">
<div class="tnv-bar">

  <style>
    .tnv-bar * { box-sizing: border-box; }
    .tnv-bar {
      background: #fff;
      height: 50px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      font-family: 'Inter', sans-serif;
      position: relative;
      z-index: 500;
    }

    /* ── LEFT ── */
    .tnv-left { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

    .tnv-hamburger {
      width: 32px; height: 32px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 8px; cursor: pointer;
      color: #475569; font-size: 15px;
      transition: background 0.15s, color 0.15s;
    }
    .tnv-hamburger:hover { background: #f1f5f9; color: #0f172a; }

    .tnv-status {
      display: inline-flex; align-items: center; gap: 5px;
      background: #f0fdf4; border: 1px solid #bbf7d0;
      color: #15803d; font-size: 10px; font-weight: 600;
      padding: 3px 9px; border-radius: 20px; letter-spacing: 0.2px;
    }
    .tnv-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #10b981; flex-shrink: 0;
      animation: tnv-pulse 2s ease-in-out infinite;
    }
    @keyframes tnv-pulse {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.35; }
    }

    /* ── RIGHT ── */
    .tnv-right { display: flex; align-items: center; gap: 4px; }

    /* Icon button */
    .tnv-icon-btn {
      position: relative; width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 9px; cursor: pointer;
      color: #64748b; font-size: 15px;
      transition: background 0.15s, color 0.15s;
    }
    .tnv-icon-btn:hover { background: #f1f5f9; color: #0f172a; }
    .tnv-icon-btn.active { background: #eff6ff; color: #2563eb; }

    /* Notification count badge */
    .tnv-badge {
      position: absolute; top: 4px; right: 4px;
      min-width: 16px; height: 16px; border-radius: 50%;
      background: #ef4444; color: #fff;
      font-size: 8px; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      border: 2px solid #fff; line-height: 1;
    }
    .tnv-badge[data-count="0"] { display: none; }

    /* Vertical divider */
    .tnv-divider { width: 1px; height: 24px; background: #e5e7eb; margin: 0 4px; }

    /* Profile trigger */
    .tnv-profile {
      display: flex; align-items: center; gap: 8px;
      padding: 4px 8px 4px 4px; border-radius: 10px;
      cursor: pointer; transition: background 0.15s; user-select: none;
    }
    .tnv-profile:hover { background: #f1f5f9; }
    .tnv-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 11px; font-weight: 700;
      border: 2px solid #bfdbfe; flex-shrink: 0; overflow: hidden;
    }
    .tnv-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .tnv-name     { font-size: 12px; font-weight: 600; color: #0f172a; line-height: 1.2; }
    .tnv-role     { font-size: 10px; color: #94a3b8; line-height: 1.2; }
    .tnv-chevron  { font-size: 9px; color: #94a3b8; margin-left: 2px; transition: transform 0.2s; }
    .tnv-profile.open .tnv-chevron { transform: rotate(180deg); }

    /* ── DROPDOWN BASE ── */
    .tnv-dd {
      position: absolute; top: calc(100% + 6px);
      background: #fff; border-radius: 12px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 8px 30px rgba(0,0,0,0.10);
      z-index: 1050; display: none; overflow: hidden;
    }
    .tnv-dd.open { display: block; }

    /* ── NOTIFICATION DROPDOWN ── */
    .tnv-dd-notif  { right: 46px; width: 340px; }
    .tnv-dd-hdr {
      padding: 12px 16px; border-bottom: 1px solid #f1f5f9;
      display: flex; align-items: center; justify-content: space-between;
    }
    .tnv-dd-hdr-title { font-size: 12px; font-weight: 700; color: #0f172a; }
    .tnv-dd-hdr-link  { font-size: 10px; color: #3b82f6; text-decoration: none; font-weight: 600; }
    .tnv-dd-hdr-link:hover { text-decoration: underline; }

    .tnv-notif-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 11px 16px; border-bottom: 1px solid #f8fafc;
      cursor: pointer; transition: background 0.12s; text-decoration: none;
    }
    .tnv-notif-item:hover { background: #f8fafc; }
    .tnv-notif-item:last-of-type { border-bottom: none; }
    .tnv-notif-icon {
      width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 13px;
    }
    .ni-green { background: #f0fdf4; color: #10b981; }
    .ni-red   { background: #fef2f2; color: #ef4444; }
    .ni-amber { background: #fffbeb; color: #f59e0b; }
    .tnv-notif-body  { flex: 1; min-width: 0; }
    .tnv-notif-title { font-size: 12px; font-weight: 600; color: #0f172a; }
    .tnv-notif-msg   { font-size: 11px; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tnv-notif-time  { font-size: 10px; color: #94a3b8; white-space: nowrap; flex-shrink: 0; padding-top: 2px; }

    .tnv-dd-footer {
      padding: 10px 16px; text-align: center; background: #f9fafb;
      border-top: 1px solid #f1f5f9;
    }
    .tnv-dd-footer a { font-size: 11px; font-weight: 600; color: #3b82f6; text-decoration: none; }
    .tnv-dd-footer a:hover { text-decoration: underline; }

    /* ── USER DROPDOWN ── */
    .tnv-dd-user { right: 0; width: 270px; }
    .tnv-user-hdr {
      background: linear-gradient(135deg, #1d4ed8, #3b82f6);
      padding: 20px 16px; text-align: center; color: #fff;
    }
    .tnv-user-hdr-avatar {
      width: 52px; height: 52px; border-radius: 50%;
      background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.45);
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; font-weight: 700; color: #fff;
      margin: 0 auto 10px; overflow: hidden;
    }
    .tnv-user-hdr-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .tnv-user-hdr-name { font-size: 14px; font-weight: 700; }
    .tnv-user-hdr-role { font-size: 11px; opacity: 0.8; margin-top: 3px; }

    .tnv-menu-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 16px; border-bottom: 1px solid #f1f5f9;
      cursor: pointer; transition: background 0.12s, border-left-color 0.12s;
      border-left: 3px solid transparent; text-decoration: none; color: inherit;
    }
    .tnv-menu-item:hover { background: #f0f9ff; border-left-color: #3b82f6; }
    .tnv-menu-item:last-child { border-bottom: none; }
    .tnv-menu-icon {
      width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 13px;
    }
    .mi-blue   { background: #eff6ff; color: #3b82f6; }
    .mi-violet { background: #f5f3ff; color: #7c3aed; }
    .mi-cyan   { background: #ecfeff; color: #06b6d4; }
    .mi-red    { background: #fef2f2; color: #ef4444; }
    .tnv-menu-label { font-size: 12.5px; font-weight: 600; color: #0f172a; line-height: 1.2; }
    .tnv-menu-sub   { font-size: 10.5px; color: #94a3b8; margin-top: 1px; }
    .tnv-menu-badge {
      margin-left: auto; background: #f5f3ff; color: #7c3aed;
      font-size: 9.5px; font-weight: 700; padding: 2px 7px;
      border-radius: 8px; border: 1px solid #ede9fe; flex-shrink: 0;
    }
    .tnv-menu-item.danger:hover { background: #fef2f2; border-left-color: #ef4444; }
  </style>

  {{-- ── LEFT SECTION ── --}}
  <div class="tnv-left">
    <div class="nav toggle">
      <a id="menu_toggle" class="tnv-hamburger">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    
    @php
      // Check bot system status
      $botIsActive = false;
      $needsPayment = false;
      $paymentType = null;
      
      if (Auth::check()) {
        $user = Auth::user();
        $userId = Auth::id();
        
        // Check 1: User must have at least one registered account connected
        $hasRegisteredAccount = \App\Models\Account::where('user_id', $userId)->exists();
        
        // Check 2: User must have active paid subscription
        $subscription = \App\Models\UserSubscription::where('user_id', $userId)
          ->where('status', 'active')
          ->first();
        $paidSubscription = null;
        
        if ($subscription && $subscription->plan) {
          $paidSubscription = \App\Models\PaymentTransaction::where('user_id', $userId)
            ->where('subscription_plan_id', $subscription->plan->id)
            ->whereIn('status', ['paid', 'success'])
            ->first();
        }
        
        // Check 3: Calculate if more than 1 week has passed since account registration or subscription start
        $weekHasPassed = false;
        $referenceDate = null;
        
        if ($subscription && $subscription->starts_at) {
          $referenceDate = $subscription->starts_at;
        } else {
          $referenceDate = $user->created_at;
        }
        
        if ($referenceDate) {
          $weekHasPassed = $referenceDate->addWeek()->lessThanOrEqualTo(now());
        }
        
        // Check 4: Only enforce weekly payment check if 1+ week has passed
        $unpaidWeekly = false;
        if ($weekHasPassed) {
          $unpaidWeekly = \App\Models\WeeklyPayment::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('week_end', '<', now())
            ->exists();
        }
        
        // Bot is active if:
        // - Has registered account connected
        // - Has paid subscription
        // - AND either: less than 1 week passed OR (1+ week passed AND no unpaid weekly payments)
        if ($hasRegisteredAccount && $paidSubscription && (!$weekHasPassed || !$unpaidWeekly)) {
          $botIsActive = true;
        } else {
          $needsPayment = true;
          if ($weekHasPassed && $unpaidWeekly) {
            $paymentType = 'weekly';
          } elseif (!$paidSubscription) {
            $paymentType = 'subscription';
          } elseif (!$hasRegisteredAccount) {
            $paymentType = 'account';
          } else {
            $paymentType = 'subscription';
          }
        }
      }
    @endphp
    
    <div class="d-none d-sm-flex tnv-status" style="@if(!$botIsActive) background: #fef2f2; border-color: #fecaca; color: #991b1b; @endif">
      <span class="tnv-dot" style="@if(!$botIsActive) background: #dc2626; @endif"></span>
      <span>Bot System: @if($botIsActive) <strong>Active</strong> @else <strong>Inactive</strong> @endif</span>
      
      @if($needsPayment)
        @php
          $paymentRoute = match($paymentType) {
            'weekly' => route('user.weekly-report.index'),
            'account' => route('user.accounts.index'),
            default => route('user.subscriptions.index'),
          };
          $paymentLabel = match($paymentType) {
            'weekly' => 'Pay Weekly',
            'account' => 'Add Account',
            default => 'Subscribe',
          };
        @endphp
        <button style="margin-left: 8px; padding: 2px 8px; background: #dc2626; color: #fff; border: none; border-radius: 4px; font-size: 10px; font-weight: 600; cursor: pointer; white-space: nowrap;"
                onclick="window.location.href = '{{ $paymentRoute }}'">
          <i class="fa fa-credit-card"></i> {{ $paymentLabel }}
        </button>
      @endif
    </div>
  </div>

  {{-- ── RIGHT SECTION ── --}}
  <div class="tnv-right">

    {{-- Notifications --}}
    <div class="tnv-icon-btn" id="tnvNotifBtn">
      <i class="fa fa-bell-o"></i>
      <span class="tnv-badge" id="tnvNotifCount">3</span>
    </div>

    <div class="tnv-divider"></div>

    {{-- Profile trigger --}}
    <div class="tnv-profile" id="tnvProfileBtn">
      <div class="tnv-avatar">
        @if(Auth::user()->profile_photo)
          <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="">
        @else
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
        @endif
      </div>
      <div class="d-none d-lg-block">
        <div class="tnv-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
        <div class="tnv-role">Premium Account</div>
      </div>
      <span class="tnv-chevron">&#9660;</span>
    </div>

  </div>

  {{-- ── NOTIFICATION DROPDOWN ── --}}
  <div class="tnv-dd tnv-dd-notif" id="tnvNotifDd">
    <div class="tnv-dd-hdr">
      <span class="tnv-dd-hdr-title">Notifications</span>
      <a href="#" class="tnv-dd-hdr-link">Mark all read</a>
    </div>

    <a href="#" class="tnv-notif-item">
      <div class="tnv-notif-icon ni-green"><i class="fa fa-check"></i></div>
      <div class="tnv-notif-body">
        <div class="tnv-notif-title">Trade Opened</div>
        <div class="tnv-notif-msg">EURUSD +50 pips profit</div>
      </div>
      <div class="tnv-notif-time">2m ago</div>
    </a>

    <a href="#" class="tnv-notif-item">
      <div class="tnv-notif-icon ni-red"><i class="fa fa-exclamation"></i></div>
      <div class="tnv-notif-body">
        <div class="tnv-notif-title">Risk Alert</div>
        <div class="tnv-notif-msg">Margin usage above 70%</div>
      </div>
      <div class="tnv-notif-time">15m ago</div>
    </a>

    <a href="#" class="tnv-notif-item">
      <div class="tnv-notif-icon ni-amber"><i class="fa fa-info"></i></div>
      <div class="tnv-notif-body">
        <div class="tnv-notif-title">Signal Executed</div>
        <div class="tnv-notif-msg">BUY signal executed on GBPUSD</div>
      </div>
      <div class="tnv-notif-time">1h ago</div>
    </a>

    <div class="tnv-dd-footer">
      <a href="#">See all notifications &rarr;</a>
    </div>
  </div>

  {{-- ── USER DROPDOWN ── --}}
  <div class="tnv-dd tnv-dd-user" id="tnvUserDd">

    <div class="tnv-user-hdr">
      <div class="tnv-user-hdr-avatar">
        @if(Auth::user()->profile_photo)
          <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="">
        @else
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
        @endif
      </div>
      <div class="tnv-user-hdr-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
      <div class="tnv-user-hdr-role">Premium User Account</div>
    </div>

    <a class="tnv-menu-item" href="{{ route('user.profile.index') }}">
      <div class="tnv-menu-icon mi-blue"><i class="fa fa-user"></i></div>
      <div>
        <div class="tnv-menu-label">Profile</div>
        <div class="tnv-menu-sub">Manage your account</div>
      </div>
    </a>

    <a class="tnv-menu-item" href="{{ route('user.profile.edit', Auth::user()->id) }}">
      <div class="tnv-menu-icon mi-violet"><i class="fa fa-cog"></i></div>
      <div>
        <div class="tnv-menu-label">Settings</div>
        <div class="tnv-menu-sub">Configure preferences</div>
      </div>
      <span class="tnv-menu-badge">50%</span>
    </a>

    <a class="tnv-menu-item" href="{{ route('help') }}">
      <div class="tnv-menu-icon mi-cyan"><i class="fa fa-question-circle"></i></div>
      <div>
        <div class="tnv-menu-label">Help &amp; Support</div>
        <div class="tnv-menu-sub">Get assistance</div>
      </div>
    </a>

    <a class="tnv-menu-item danger" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('tnv-logout-form').submit();"
       style="background:#fef2f2;">
      <div class="tnv-menu-icon mi-red"><i class="fa fa-sign-out"></i></div>
      <div>
        <div class="tnv-menu-label" style="color:#ef4444;">Sign Out</div>
        <div class="tnv-menu-sub">End your session</div>
      </div>
    </a>

    <form id="tnv-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>

  </div>

</div>{{-- end .tnv-bar --}}
</div>{{-- end .top_nav --}}

<script>
(function () {
  'use strict';

  var notifBtn   = document.getElementById('tnvNotifBtn');
  var profileBtn = document.getElementById('tnvProfileBtn');
  var notifDd    = document.getElementById('tnvNotifDd');
  var userDd     = document.getElementById('tnvUserDd');

  function openDd(show, hide, trigger) {
    hide.classList.remove('open');
    document.querySelectorAll('.tnv-profile').forEach(function (el) { el.classList.remove('open'); });
    var wasOpen = show.classList.contains('open');
    show.classList.toggle('open');
    if (trigger) trigger.classList.toggle('open', !wasOpen);
  }

  notifBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    openDd(notifDd, userDd, null);
    notifBtn.classList.toggle('active', notifDd.classList.contains('open'));
  });

  profileBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    openDd(userDd, notifDd, profileBtn);
    notifBtn.classList.remove('active');
  });

  // Close on outside click
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.tnv-bar')) {
      notifDd.classList.remove('open');
      userDd.classList.remove('open');
      profileBtn.classList.remove('open');
      notifBtn.classList.remove('active');
    }
  });

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      notifDd.classList.remove('open');
      userDd.classList.remove('open');
      profileBtn.classList.remove('open');
      notifBtn.classList.remove('active');
    }
  });
})();
</script>