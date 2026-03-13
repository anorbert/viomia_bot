@extends('layouts.user')

@section('content')

<style>
  .pin * { box-sizing: border-box; }
  .pin {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    display: flex; align-items: center; justify-content: center; padding: 24px;
  }

  /* ── CARD ── */
  .pin-card {
    background: #fff; border-radius: 16px;
    width: 100%; max-width: 420px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.3);
    overflow: hidden;
  }
  .pin-accent { height: 5px; background: linear-gradient(90deg, #f59e0b, #6366f1, #06b6d4); }

  /* ── HEADER ── */
  .pin-hdr              { padding: 28px 32px 20px; border-bottom: 1px solid #f1f5f9; text-align: center; }
  .pin-icon             {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, #fef9c3, #fde68a);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px; font-size: 20px; color: #b45309;
  }
  .pin-hdr h2           { font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .pin-hdr p            { font-size: 11.5px; color: #64748b; margin: 0; }

  /* ── BODY ── */
  .pin-body             { padding: 24px 32px; }

  /* ── INFO BOX ── */
  .pin-info {
    display: flex; align-items: flex-start; gap: 9px;
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;
    padding: 11px 13px; margin-bottom: 22px; font-size: 11.5px; color: #1d4ed8;
  }
  .pin-info i { flex-shrink: 0; margin-top: 1px; }

  /* ── ALERTS ── */
  .pin-alert-danger {
    display: flex; align-items: flex-start; gap: 9px;
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;
    padding: 11px 13px; margin-bottom: 20px; font-size: 12px; color: #b91c1c;
  }
  .pin-alert-danger i { flex-shrink: 0; margin-top: 1px; }
  .pin-alert-danger ul { margin: 4px 0 0 14px; padding: 0; }
  .pin-alert-danger ul li { margin-bottom: 2px; }

  .pin-alert-success {
    display: flex; align-items: center; gap: 9px;
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
    padding: 11px 13px; margin-bottom: 20px; font-size: 12px; color: #15803d;
  }

  /* ── FORM GROUP ── */
  .pin-fg               { margin-bottom: 18px; }
  .pin-fg label         {
    display: flex; align-items: center; gap: 6px;
    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px; color: #64748b; margin-bottom: 7px;
  }
  .pin-fg label i       { font-size: 11px; }
  .pin-input-wrap       { position: relative; }
  .pin-input {
    width: 100%; padding: 11px 40px 11px 14px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 22px; font-family: 'Inter', sans-serif;
    letter-spacing: 6px; color: #0f172a; background: #fff;
    outline: none; transition: border-color 0.15s, box-shadow 0.15s;
  }
  .pin-input:focus      { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
  .pin-input.pin-ok     { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.08); }
  .pin-input.pin-bad    { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.08); }
  .pin-input.is-invalid { border-color: #ef4444; }

  .pin-eye              {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    font-size: 14px; color: #94a3b8; cursor: pointer; user-select: none;
  }
  .pin-eye:hover        { color: #475569; }

  /* ── DOT INDICATOR ── */
  .pin-dots             { display: flex; gap: 8px; justify-content: center; margin-top: 9px; }
  .pin-dot              { width: 10px; height: 10px; border-radius: 50%; background: #e2e8f0; transition: background 0.2s; }
  .pin-dot.filled       { background: #6366f1; }

  /* ── HINT / ERROR / MATCH MESSAGES ── */
  .pin-hint             { font-size: 11px; color: #94a3b8; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
  .invalid-feedback     { display: block; font-size: 11px; color: #ef4444; margin-top: 5px; }
  .pin-match            { font-size: 11px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
  .pin-match.ok         { color: #10b981; }
  .pin-match.bad        { color: #ef4444; }

  /* ── DIVIDER ── */
  .pin-divider          { border: none; border-top: 1px solid #f1f5f9; margin: 6px 0 20px; }

  /* ── ACTIONS ── */
  .pin-actions          { display: grid; grid-template-columns: 1fr auto; gap: 8px; margin-top: 22px; }
  .btn-pin-submit {
    padding: 11px 14px; background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff; border: none; border-radius: 9px;
    font-size: 13.5px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    transition: opacity 0.15s;
  }
  .btn-pin-submit:hover { opacity: 0.9; }
  .btn-pin-cancel {
    padding: 11px 16px; background: #fff; color: #475569;
    border: 1px solid #e2e8f0; border-radius: 9px; font-size: 13px;
    font-weight: 500; cursor: pointer; font-family: 'Inter', sans-serif;
    display: flex; align-items: center; gap: 6px; text-decoration: none;
  }
  .btn-pin-cancel:hover { background: #f8fafc; color: #0f172a; }

  /* ── SECURITY NOTE ── */
  .pin-security {
    display: flex; align-items: flex-start; gap: 9px;
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
    padding: 11px 13px; margin-top: 16px; font-size: 11.5px; color: #15803d;
  }
  .pin-security i { flex-shrink: 0; margin-top: 1px; }

  /* ── FOOTER ── */
  .pin-footer           { padding: 14px 32px 22px; text-align: center; border-top: 1px solid #f8fafc; }
  .pin-footer-brand     { font-size: 12.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; justify-content: center; gap: 6px; margin-bottom: 2px; }
  .pin-footer-brand i   { color: #6366f1; }
  .pin-footer-copy      { font-size: 10px; color: #94a3b8; }
</style>

<div class="pin">
<div class="pin-card">

  <div class="pin-accent"></div>

  {{-- ── HEADER ── --}}
  <div class="pin-hdr">
    <div class="pin-icon"><i class="fa fa-hashtag"></i></div>
    <h2>Change PIN</h2>
    <p>Update your 4–6 digit PIN used for login</p>
  </div>

  <div class="pin-body">

    {{-- ── SESSION ALERTS ── --}}
    @if(session('success'))
      <div class="pin-alert-success">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="pin-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <div>
          <strong>Please fix the following:</strong>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    {{-- ── INFO HINT ── --}}
    <div class="pin-info">
      <i class="fa fa-info-circle"></i>
      <span>Your PIN must be <strong>4–6 numeric digits</strong> only.</span>
    </div>

    {{-- ── FORM ── --}}
    <form method="POST" action="{{ route('user.change-pin.update') }}" id="pinForm" novalidate>
      @csrf

      {{-- Current PIN --}}
      <div class="pin-fg">
        <label for="current_password">
          <i class="fa fa-key" style="color:#f59e0b;"></i> Current PIN
        </label>
        <div class="pin-input-wrap">
          <input
            id="current_password"
            type="password"
            inputmode="numeric"
            pattern="[0-9]{4,6}"
            name="current_password"
            class="pin-input @error('current_password') is-invalid @enderror"
            maxlength="6"
            placeholder="••••"
            required
            autocomplete="current-password"
            oninput="pinSyncDots(this,'pinDots1')">
          <span class="pin-eye" onclick="pinToggleEye('current_password',this)">
            <i class="fa fa-eye-slash"></i>
          </span>
        </div>
        <div class="pin-dots" id="pinDots1">
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
        </div>
        @error('current_password')
          <span class="invalid-feedback"><i class="fa fa-times-circle"></i> {{ $message }}</span>
        @else
          <div class="pin-hint"><i class="fa fa-info-circle"></i> Enter your current 4–6 digit PIN</div>
        @enderror
      </div>

      <hr class="pin-divider">

      {{-- New PIN --}}
      <div class="pin-fg">
        <label for="new_password">
          <i class="fa fa-shield" style="color:#10b981;"></i> New PIN
        </label>
        <div class="pin-input-wrap">
          <input
            id="new_password"
            type="password"
            inputmode="numeric"
            pattern="[0-9]{4,6}"
            name="new_password"
            class="pin-input @error('new_password') is-invalid @enderror"
            maxlength="6"
            placeholder="••••"
            required
            autocomplete="new-password"
            oninput="pinSyncDots(this,'pinDots2'); pinCheckMatch();">
          <span class="pin-eye" onclick="pinToggleEye('new_password',this)">
            <i class="fa fa-eye-slash"></i>
          </span>
        </div>
        <div class="pin-dots" id="pinDots2">
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
        </div>
        @error('new_password')
          <span class="invalid-feedback"><i class="fa fa-times-circle"></i> {{ $message }}</span>
        @else
          <div class="pin-hint"><i class="fa fa-info-circle"></i> Must differ from current PIN · 4–6 digits only</div>
        @enderror
      </div>

      {{-- Confirm New PIN --}}
      <div class="pin-fg">
        <label for="new_password_confirmation">
          <i class="fa fa-check-circle" style="color:#3b82f6;"></i> Confirm New PIN
        </label>
        <div class="pin-input-wrap">
          <input
            id="new_password_confirmation"
            type="password"
            inputmode="numeric"
            pattern="[0-9]{4,6}"
            name="new_password_confirmation"
            class="pin-input"
            maxlength="6"
            placeholder="••••"
            required
            autocomplete="new-password"
            oninput="pinSyncDots(this,'pinDots3'); pinCheckMatch();">
          <span class="pin-eye" onclick="pinToggleEye('new_password_confirmation',this)">
            <i class="fa fa-eye-slash"></i>
          </span>
        </div>
        <div class="pin-dots" id="pinDots3">
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
          <div class="pin-dot"></div><div class="pin-dot"></div>
        </div>
        <div class="pin-match" id="pinMatchMsg" style="display:none;"></div>
      </div>

      {{-- Actions --}}
      <div class="pin-actions">
        <button type="submit" class="btn-pin-submit">
          <i class="fa fa-lock"></i> Update PIN
        </button>
        <a href="{{ route('user.dashboard') }}" class="btn-pin-cancel">
          <i class="fa fa-times"></i> Cancel
        </a>
      </div>

    </form>

    {{-- Security note --}}
    <div class="pin-security">
      <i class="fa fa-shield"></i>
      <span>Your PIN is encrypted and stored securely. Never share it with anyone.</span>
    </div>

  </div>{{-- end .pin-body --}}

  <div class="pin-footer">
    <div class="pin-footer-brand"><i class="fa fa-university"></i> Trading Platform</div>
    <div class="pin-footer-copy">&copy; {{ date('Y') }} All Rights Reserved &middot; Privacy &amp; Terms</div>
  </div>

</div>{{-- end .pin-card --}}
</div>{{-- end .pin --}}

@endsection

@push('scripts')
<script>
(function () {
  'use strict';

  /* ── FILL DOT INDICATORS ── */
  window.pinSyncDots = function (input, dotsId) {
    var dots = document.getElementById(dotsId).querySelectorAll('.pin-dot');
    dots.forEach(function (d, i) {
      d.classList.toggle('filled', i < input.value.length);
    });
  };

  /* ── SHOW / HIDE PIN ── */
  window.pinToggleEye = function (inputId, btn) {
    var inp = document.getElementById(inputId);
    var ico = btn.querySelector('i');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'fa fa-eye-slash' : 'fa fa-eye';
  };

  /* ── LIVE MATCH CHECK ── */
  window.pinCheckMatch = function () {
    var np  = document.getElementById('new_password').value;
    var cp  = document.getElementById('new_password_confirmation').value;
    var inp = document.getElementById('new_password_confirmation');
    var msg = document.getElementById('pinMatchMsg');

    if (!cp) {
      msg.style.display = 'none';
      inp.className = 'pin-input';
      return;
    }

    msg.style.display = 'flex';
    if (np === cp) {
      msg.className   = 'pin-match ok';
      msg.innerHTML   = '<i class="fa fa-check-circle"></i> PINs match';
      inp.className   = 'pin-input pin-ok';
    } else {
      msg.className   = 'pin-match bad';
      msg.innerHTML   = '<i class="fa fa-times-circle"></i> PINs do not match';
      inp.className   = 'pin-input pin-bad';
    }
  };

  /* ── ENFORCE DIGITS ONLY ── */
  ['current_password', 'new_password', 'new_password_confirmation'].forEach(function (id) {
    document.getElementById(id).addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  });

  /* ── FORM SUBMIT GUARD ── */
  document.getElementById('pinForm').addEventListener('submit', function (e) {
    var cur = document.getElementById('current_password').value;
    var np  = document.getElementById('new_password').value;
    var cp  = document.getElementById('new_password_confirmation').value;

    if (cur === np) {
      e.preventDefault();
      var msg = document.getElementById('pinMatchMsg');
      msg.style.display = 'flex';
      msg.className     = 'pin-match bad';
      msg.innerHTML     = '<i class="fa fa-times-circle"></i> New PIN must differ from current PIN';
      document.getElementById('new_password').focus();
      return;
    }

    if (np !== cp) {
      e.preventDefault();
      var msg = document.getElementById('pinMatchMsg');
      msg.style.display = 'flex';
      msg.className     = 'pin-match bad';
      msg.innerHTML     = '<i class="fa fa-times-circle"></i> PINs do not match';
      document.getElementById('new_password_confirmation').focus();
    }
  });

})();
</script>
@endpush