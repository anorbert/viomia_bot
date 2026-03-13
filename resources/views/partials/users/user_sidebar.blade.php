<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Mono:wght@700&display=swap');

  .usb * { box-sizing: border-box; margin: 0; padding: 0; }

  .usb {
    width: 260px;
    height: 100vh;
    background: #0f172a;
    display: flex;
    flex-direction: column;
    font-family: 'Inter', sans-serif;
    position: relative;
    overflow: hidden;
  }

  /* ── PROFILE ── */
  .usb-profile {
    margin: 14px 12px 0;
    background: #1e293b;
    border: 1px solid rgba(99,102,241,0.2);
    border-radius: 12px;
    padding: 13px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }
  .usb-ava {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #312e81;
    border: 2px solid rgba(165,180,252,0.4);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Space Mono', monospace;
    font-size: 12px;
    color: #a5b4fc;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
  }
  .usb-ava img { width: 100%; height: 100%; object-fit: cover; }
  .usb-ava-initials { font-family: 'Space Mono', monospace; font-size: 12px; color: #a5b4fc; }
  .usb-dot {
    position: absolute;
    bottom: 0; right: 0;
    width: 10px; height: 10px;
    background: #10b981;
    border: 2px solid #0f172a;
    border-radius: 50%;
  }
  .usb-greeting { font-size: 8px; color: #06b6d4; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; }
  .usb-name { font-size: 12px; color: #f1f5f9; font-weight: 600; margin-top: 2px; }
  .usb-plan { font-size: 10px; color: #818cf8; margin-top: 1px; }

  /* ── SCROLL ── */
  .usb-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 16px 0 8px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.05) transparent;
  }
  .usb-scroll::-webkit-scrollbar { width: 3px; }
  .usb-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 4px; }

  /* ── SECTION LABEL ── */
  .usb-section {
    font-size: 9px;
    color: #334155;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 0 16px 8px;
    font-family: 'Space Mono', monospace;
  }

  /* ── ITEMS ── */
  .usb-item { margin: 0 10px 4px; border-radius: 8px; overflow: hidden; }

  .usb-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    cursor: pointer;
    color: #94a3b8;
    font-size: 12.5px;
    font-weight: 500;
    border-left: 3px solid transparent;
    text-decoration: none;
    user-select: none;
    transition: background 0.15s, color 0.15s;
  }
  .usb-link:hover { background: rgba(255,255,255,0.04); color: #e2e8f0; text-decoration: none; }

  /* Accent variants per section */
  .usb-link.blue  { color: #60a5fa; background: rgba(59,130,246,0.08);  border-left-color: #3b82f6; font-weight: 600; }
  .usb-link.cyan  { color: #22d3ee; background: rgba(6,182,212,0.08);   border-left-color: #06b6d4; font-weight: 600; }
  .usb-link.pink  { color: #f472b6; background: rgba(236,72,153,0.08);  border-left-color: #ec4899; font-weight: 600; }
  .usb-link.green { color: #4ade80; background: rgba(16,185,129,0.08);  border-left-color: #10b981; font-weight: 600; }
  .usb-link.amber { color: #fbbf24; background: rgba(245,158,11,0.08);  border-left-color: #f59e0b; font-weight: 600; }
  .usb-link.violet{ color: #c4b5fd; background: rgba(139,92,246,0.08);  border-left-color: #8b5cf6; font-weight: 600; }

  .usb-icon { width: 18px; font-size: 13px; text-align: center; flex-shrink: 0; opacity: 0.65; }
  .usb-link.blue .usb-icon,
  .usb-link.cyan .usb-icon,
  .usb-link.pink .usb-icon,
  .usb-link.green .usb-icon,
  .usb-link.amber .usb-icon,
  .usb-link.violet .usb-icon { opacity: 1; }

  .usb-lbl { flex: 1; }
  .usb-chev { font-size: 9px; color: #334155; transition: transform 0.2s, color 0.2s; flex-shrink: 0; }
  .usb-link.usb-open .usb-chev { transform: rotate(180deg); color: #64748b; }

  /* ── SUBMENU ── */
  .usb-sub { display: none; padding: 4px 0 6px 31px; }
  .usb-sub.usb-open { display: block; }

  .usb-slink {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    font-size: 12px;
    color: #475569;
    text-decoration: none;
    border-radius: 6px;
    transition: color 0.15s, padding-left 0.15s;
    cursor: pointer;
  }
  .usb-slink:hover { color: #cbd5e1; padding-left: 16px; text-decoration: none; }
  .usb-slink.usb-active { color: #93c5fd; font-weight: 600; }

  .usb-sdot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.5; flex-shrink: 0; }

  .usb-divider { height: 1px; background: rgba(255,255,255,0.04); margin: 8px 16px; }

  /* ── FOOTER ── */
  .usb-footer {
    border-top: 1px solid rgba(255,255,255,0.05);
    background: rgba(0,0,0,0.25);
    padding: 12px 16px;
    flex-shrink: 0;
  }
  .usb-fbtns { display: flex; gap: 8px; justify-content: center; margin-bottom: 10px; }

  .usb-fbtn {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    cursor: pointer;
    color: #475569;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    position: relative;
  }
  .usb-fbtn:hover { background: rgba(255,255,255,0.07); color: #94a3b8; }
  .usb-fbtn.danger:hover { background: rgba(239,68,68,0.12); color: #f87171; }

  .usb-fbtn::after {
    content: attr(data-tip);
    position: absolute;
    bottom: 120%; left: 50%; transform: translateX(-50%);
    background: #1e293b;
    color: #cbd5e1;
    font-size: 10px;
    padding: 3px 7px;
    border-radius: 4px;
    white-space: nowrap;
    opacity: 0; pointer-events: none;
    transition: opacity 0.15s;
    border: 1px solid rgba(255,255,255,0.07);
    font-family: 'Inter', sans-serif;
  }
  .usb-fbtn:hover::after { opacity: 1; }

  .usb-ver {
    text-align: center;
    font-size: 9px;
    color: #1e3a5f;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }
  .usb-ver .brand { color: #1d4ed8; font-weight: 700; }
</style>

@php
  $is   = fn($pattern)  => request()->routeIs($pattern);
  $open = fn($patterns) => collect((array)$patterns)->contains(fn($p) => request()->routeIs($p));

  $hour = now()->hour;
  $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');

  $name      = Auth::user()->name ?? 'User';
  $nameParts = explode(' ', trim($name));
  $displayName = $nameParts[0];
  if (count($nameParts) > 1) {
    $displayName .= ' ' . substr($nameParts[1], 0, 1) . '.';
  }
  $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1)));
@endphp

<div class="usb">

  {{-- ── PROFILE ── --}}
  <div class="usb-profile">
    <div class="usb-ava">
      @if(Auth::user()->profile_photo)
        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Avatar">
      @else
        <span class="usb-ava-initials">{{ $initials }}</span>
      @endif
      <div class="usb-dot"></div>
    </div>
    <div>
      <div class="usb-greeting">{{ $greeting }}</div>
      <div class="usb-name">{{ $displayName }}</div>
      <div class="usb-plan">💎 {{ Auth::user()->phone_number ?? 'Premium User' }}</div>
    </div>
  </div>

  {{-- ── NAVIGATION ── --}}
  <div class="usb-scroll">
    <div style="height:12px;"></div>
    <div class="usb-section">Main Menu</div>

    {{-- Dashboard --}}
    <div class="usb-item">
      <a href="{{ route('user.dashboard') }}"
         class="usb-link {{ $is('user.dashboard') ? 'blue' : '' }}">
        <span class="usb-icon"><i class="fa fa-home"></i></span>
        <span class="usb-lbl">Dashboard</span>
        @if($is('user.dashboard'))
          <span style="width:6px;height:6px;background:#3b82f6;border-radius:50%;"></span>
        @endif
      </a>
    </div>

    {{-- My Accounts --}}
    @php $accountsOpen = $open(['user.accounts.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $accountsOpen ? 'cyan usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-link"></i></span>
        <span class="usb-lbl">My Accounts</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $accountsOpen ? 'usb-open' : '' }}">
        <a href="{{ route('user.accounts.index') }}"
           class="usb-slink {{ $is('user.accounts.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Connected Accounts
        </a>
      </div>
    </div>

    {{-- Signals --}}
    @php $signalsOpen = $open(['user.signals.*','user.executions.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $signalsOpen ? 'pink usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-bolt"></i></span>
        <span class="usb-lbl">Signals</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $signalsOpen ? 'usb-open' : '' }}">
        <a href="{{ route('user.signals.index') }}"
           class="usb-slink {{ $is('user.signals.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>All Signals
        </a>
        <a href="{{ route('user.executions.index') }}"
           class="usb-slink {{ $is('user.executions.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>EA Executions
        </a>
      </div>
    </div>

    {{-- Trading Activity --}}
    @php $tradingOpen = $open(['user.trades.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $tradingOpen ? 'green usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-exchange"></i></span>
        <span class="usb-lbl">Trading Activity</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $tradingOpen ? 'usb-open' : '' }}">
        <a href="{{ route('user.trades.open') }}"
           class="usb-slink {{ $is('user.trades.open') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Open Positions
        </a>
        <a href="{{ route('user.trades.history') }}"
           class="usb-slink {{ $is('user.trades.history') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Trade History
        </a>
      </div>
    </div>

    {{-- Billing --}}
    @php $billingOpen = $open(['user.payments.*','user.subscriptions.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $billingOpen ? 'amber usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-credit-card"></i></span>
        <span class="usb-lbl">Billing</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $billingOpen ? 'usb-open' : '' }}">
        <a href="{{ route('user.subscriptions.index') }}"
           class="usb-slink {{ $is('user.subscriptions.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>My Subscription
        </a>
        <a href="{{ route('user.payments.index') }}"
           class="usb-slink {{ $is('user.payments.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>History & Invoices
        </a>
        <a href="{{ route('user.weekly-report.index') }}"
           class="usb-slink {{ $is('user.weekly-report.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Weekly Report
        </a>
      </div>
    </div>

    {{-- My Account --}}
    @php $profileOpen = $open(['user.profile.*','user.password.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $profileOpen ? 'violet usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-user"></i></span>
        <span class="usb-lbl">My Account</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $profileOpen ? 'usb-open' : '' }}">
        <a href="{{ route('user.profile.index') }}"
           class="usb-slink {{ $is('user.profile.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Profile
        </a>
        <a href="{{ route('user.password.index') }}"
           class="usb-slink {{ $is('user.password.index') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Change Password
        </a>
      </div>
    </div>

    <div class="usb-divider"></div>

    {{-- Support --}}
    @php $supportOpen = $open(['help','user.support.*']); @endphp
    <div class="usb-item">
      <div class="usb-link {{ $supportOpen ? 'cyan usb-open' : '' }}" onclick="usbToggle(this)">
        <span class="usb-icon"><i class="fa fa-life-ring"></i></span>
        <span class="usb-lbl">Support</span>
        <span class="usb-chev">▾</span>
      </div>
      <div class="usb-sub {{ $supportOpen ? 'usb-open' : '' }}">
        <a href="{{ route('help') }}"
           class="usb-slink {{ $is('help') ? 'usb-active' : '' }}">
          <span class="usb-sdot"></span>Help & FAQ
        </a>
        <a href="mailto:support@viomiabot.com" class="usb-slink">
          <span class="usb-sdot"></span>Email Support
        </a>
      </div>
    </div>

  </div>{{-- end scroll --}}

  {{-- ── FOOTER ── --}}
  <div class="usb-footer">
    <div class="usb-fbtns">
      <a href="{{ route('user.profile.index') }}" class="usb-fbtn" data-tip="Settings">
        <i class="fa fa-cog"></i>
      </a>
      <a href="#" class="usb-fbtn" data-tip="Fullscreen"
         onclick="document.documentElement.requestFullscreen?.(); return false;">
        <i class="fa fa-window-maximize"></i>
      </a>
      <a href="{{ route('logout') }}" class="usb-fbtn danger" data-tip="Logout"
         onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();">
        <i class="fa fa-sign-out"></i>
      </a>
    </div>
    <div class="usb-ver">
      v1.0.0 &nbsp;|&nbsp; Developed by <span class="brand">VIOMIA</span>
    </div>
  </div>

</div>

{{-- Logout form --}}
<form id="logout-form-user" action="{{ route('logout') }}" method="POST" style="display:none;">
  @csrf
</form>

<script>
  function usbToggle(el) {
    var isOpen = el.classList.contains('usb-open');
    document.querySelectorAll('.usb-link.usb-open').forEach(function(l) {
      l.classList.remove('usb-open');
      if (l.nextElementSibling) l.nextElementSibling.classList.remove('usb-open');
    });
    if (!isOpen) {
      el.classList.add('usb-open');
      if (el.nextElementSibling) el.nextElementSibling.classList.add('usb-open');
    }
  }
</script>