@extends('layouts.user')

@section('content')

<style>
  .sub * { box-sizing: border-box; }
  .sub {
    font-family: 'Inter', sans-serif;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 20px;
    color: #1e293b;
  }

  /* ── PAGE HEADER ── */
  .sub-pg-hdr        { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
  .sub-pg-hdr h1     { font-size: 19px; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
  .sub-pg-hdr p      { font-size: 12px; color: #64748b; margin: 0; }
  .btn-sub-browse {
    padding: 8px 16px; border: 1.5px solid #6366f1; color: #6366f1; border-radius: 8px;
    font-size: 12px; font-weight: 600; background: #fff; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; text-decoration: none; white-space: nowrap;
    font-family: 'Inter', sans-serif;
  }
  .btn-sub-browse:hover { background: #f5f3ff; color: #4f46e5; }

  /* ── CARD ── */
  .sub-card         { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px; overflow: hidden; }
  .sub-card-body    { padding: 24px; }

  /* ── STATUS BADGE ── */
  .sub-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 10px; }
  .sub-badge .b-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
  .b-active    { background: #dcfce7; color: #15803d; }
  .b-pending   { background: #fef9c3; color: #854d0e; }
  .b-cancelled { background: #fee2e2; color: #b91c1c; }
  .b-inactive  { background: #f3f4f6; color: #374151; }

  /* ── CURRENT PLAN LAYOUT ── */
  .sub-plan-grid {
    display: grid;
    grid-template-columns: 1fr 1px 280px;
    gap: 0;
  }
  @media (max-width: 768px) {
    .sub-plan-grid { grid-template-columns: 1fr; }
    .sub-divider-v { display: none; }
    .sub-plan-left  { padding-right: 0; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 20px; }
    .sub-plan-right { padding-left: 0; }
  }
  .sub-divider-v  { background: #f1f5f9; }
  .sub-plan-left  { padding-right: 28px; }
  .sub-plan-right { padding-left: 28px; }

  .sub-plan-name { font-size: 22px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
  .sub-plan-desc { font-size: 13px; color: #64748b; margin: 0 0 20px; }

  /* ── META GRID ── */
  .sub-meta           { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; }
  .sub-meta-item label {
    font-size: 9.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px; color: #94a3b8; display: block; margin-bottom: 3px;
  }
  .sub-meta-item .val { font-size: 13px; font-weight: 600; color: #0f172a; }
  .val-green { color: #10b981 !important; }
  .val-blue  { color: #3b82f6 !important; }

  /* ── FEATURES BOX ── */
  .sub-features-box     { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px; padding: 16px; margin-bottom: 18px; }
  .sub-features-box h6  { font-size: 11.5px; font-weight: 700; color: #0f172a; margin: 0 0 12px; display: flex; align-items: center; gap: 7px; }
  .sub-features-box h6 i { color: #10b981; }
  .sub-feat-list        { list-style: none; padding: 0; margin: 0; }
  .sub-feat-list li     { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #475569; padding: 5px 0; border-bottom: 1px solid #f1f5f9; }
  .sub-feat-list li:last-child { border-bottom: none; }
  .sub-feat-list li i   { color: #10b981; font-size: 10.5px; flex-shrink: 0; }

  /* ── ACTION BUTTONS ── */
  .sub-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .btn-sub-upgrade {
    padding: 9px 12px; background: #6366f1; color: #fff; border: none;
    border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-family: 'Inter', sans-serif;
  }
  .btn-sub-upgrade:hover { background: #4f46e5; }
  .btn-sub-renew {
    padding: 9px 12px; background: #fff; color: #475569; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-family: 'Inter', sans-serif;
  }
  .btn-sub-renew:hover { background: #f8fafc; }

  /* ── EMPTY STATE ── */
  .sub-empty       { text-align: center; padding: 44px; color: #94a3b8; }
  .sub-empty .ico  { font-size: 38px; opacity: 0.25; margin-bottom: 12px; }
  .sub-empty h4    { font-size: 15px; font-weight: 700; color: #475569; margin-bottom: 6px; }
  .sub-empty p     { font-size: 12.5px; max-width: 380px; margin: 0 auto; }

  /* ── SECTION HEADING ── */
  .sub-section-hdr { font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 16px; }

  /* ── PLAN CARDS GRID ── */
  .sub-plans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  @media (max-width: 900px) { .sub-plans-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 580px) { .sub-plans-grid { grid-template-columns: 1fr; } }

  .sub-plan-card {
    background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;
    overflow: hidden; display: flex; flex-direction: column;
    transition: box-shadow 0.15s, transform 0.15s;
  }
  .sub-plan-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.09); transform: translateY(-3px); }
  .sub-plan-card.featured { border: 2px solid #6366f1; }

  .sub-plan-card-top  { padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
  .sub-plan-card-body { padding: 16px 20px 20px; flex: 1; display: flex; flex-direction: column; }

  .sub-pc-badge    { display: inline-block; padding: 2px 8px; background: #ede9fe; color: #6d28d9; font-size: 9.5px; font-weight: 700; border-radius: 6px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.3px; }
  .sub-pc-name     { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
  .sub-pc-desc     { font-size: 11px; color: #94a3b8; }

  .sub-pc-price    { display: flex; align-items: baseline; gap: 4px; margin: 14px 0 2px; }
  .sub-pc-amount   { font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1; }
  .sub-pc-currency { font-size: 13px; font-weight: 600; color: #64748b; }
  .sub-pc-interval { font-size: 11px; color: #94a3b8; margin-bottom: 14px; }

  .sub-pc-feats    { list-style: none; padding: 0; margin: 0 0 16px; flex: 1; }
  .sub-pc-feats li { display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: #475569; padding: 4px 0; border-bottom: 1px solid #f8fafc; }
  .sub-pc-feats li:last-child { border-bottom: none; }
  .sub-pc-feats li i { font-size: 10px; color: #10b981; flex-shrink: 0; }

  .btn-sub-get {
    display: block; width: 100%; padding: 10px 12px;
    background: #10b981; color: #fff; border: none;
    border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif; text-align: center; text-decoration: none;
    transition: opacity 0.15s;
  }
  .btn-sub-get:hover { opacity: 0.88; color: #fff; }
  .sub-plan-card.featured .btn-sub-get { background: #6366f1; }

  /* ── MODAL ── */
  .sub-modal-overlay {
    position: fixed; inset: 0; background: rgba(15,23,42,0.45);
    display: none; align-items: center; justify-content: center; z-index: 2000; padding: 16px;
  }
  .sub-modal-overlay.open { display: flex; }
  .sub-modal {
    background: #fff; border-radius: 14px; width: 100%; max-width: 460px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;
    animation: sub-modal-in 0.18s ease;
  }
  @keyframes sub-modal-in { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
  .sub-modal-hdr {
    background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;
    padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;
  }
  .sub-modal-hdr h5 { font-size: 14px; font-weight: 700; margin: 0; }
  .sub-modal-close  { background: none; border: none; color: rgba(255,255,255,0.8); font-size: 18px; cursor: pointer; line-height: 1; }
  .sub-modal-close:hover { color: #fff; }
  .sub-modal-body   { padding: 20px; }
  .sub-modal-footer { padding: 12px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 8px; }

  .sub-plan-summary {
    display: flex; align-items: center; justify-content: space-between;
    background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px;
    padding: 14px 16px; margin-bottom: 18px;
  }
  .sub-plan-summary-name  { font-size: 14px; font-weight: 700; color: #0f172a; }
  .sub-plan-summary-label { font-size: 11px; color: #94a3b8; margin-top: 2px; }
  .sub-plan-summary-price { font-size: 18px; font-weight: 800; color: #6366f1; }

  .sub-form-group       { margin-bottom: 16px; }
  .sub-form-label       { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; display: block; margin-bottom: 5px; }
  .sub-form-control {
    width: 100%; padding: 8px 11px; border: 1px solid #e2e8f0; border-radius: 8px;
    font-size: 13px; font-family: 'Inter', sans-serif; color: #0f172a;
    background: #fff; outline: none;
  }
  .sub-form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
  .sub-form-hint        { font-size: 11px; color: #94a3b8; margin-top: 4px; }

  .sub-info-box {
    background: #e0f2fe; color: #0369a1; border-radius: 8px;
    padding: 11px 14px; font-size: 11.5px; display: flex; align-items: flex-start; gap: 8px;
    margin-top: 4px;
  }
  .sub-info-box i { flex-shrink: 0; margin-top: 1px; }

  .btn-sub-cancel {
    padding: 8px 16px; background: #fff; color: #475569; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12.5px; font-weight: 500; cursor: pointer;
    font-family: 'Inter', sans-serif;
  }
  .btn-sub-cancel:hover { background: #f8fafc; }
  .btn-sub-confirm {
    padding: 8px 20px; background: #10b981; color: #fff; border: none;
    border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer;
    font-family: 'Inter', sans-serif;
  }
  .btn-sub-confirm:hover { background: #059669; }
</style>

<div class="sub">

  {{-- ── PAGE HEADER ── --}}
  <div class="sub-pg-hdr">
    <div>
      <h1><i class="fa fa-credit-card mr-2"></i>My Subscription</h1>
      <p>Manage your billing and bot access permissions</p>
    </div>
    @if(Route::has('user.plans.index'))
      <a href="{{ route('user.plans.index') }}" class="btn-sub-browse">
        <i class="fa fa-shopping-cart"></i> Browse All Plans
      </a>
    @endif
  </div>

  {{-- ── PAYMENT REQUIRED NOTIFICATION ── --}}
  @if($isSubscriptionUnpaid)
  <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-start;">
    <div style="font-size: 20px;">💳</div>
    <div style="flex: 1;">
      <h6 style="font-size: 14px; font-weight: 700; color: #991b1b; margin: 0 0 6px;">Payment Required</h6>
      <p style="font-size: 12px; color: #7f1d1d; margin: 0 0 10px;">Your selected plan <strong>{{ $subscription->plan->name }}</strong> requires payment. Complete your payment to activate the subscription and access all features.</p>
      <button id="completePaymentBtn" style="display: inline-block; padding: 8px 16px; background: #dc2626; color: #fff; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
        <i class="fa fa-credit-card"></i> Complete Payment
      </button>
    </div>
  </div>
  @endif

  {{-- ── NO SUBSCRIPTION CHOSEN NOTIFICATION ── --}}
  @if($noSubscriptionChosen)
  <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-start;">
    <div style="font-size: 20px;">📋</div>
    <div style="flex: 1;">
      <h6 style="font-size: 14px; font-weight: 700; color: #1e40af; margin: 0 0 6px;">Choose a Subscription Plan</h6>
      <p style="font-size: 12px; color: #1e3a8a; margin: 0 0 10px;">You haven't selected a subscription plan yet. Browse available plans below and choose one that suits your trading needs.</p>
    </div>
  </div>
  @endif

  {{-- ── CURRENT SUBSCRIPTION ── --}}
  {{-- Only show if subscription is active and paid --}}
  @if($subscription && $subscription->plan && !$isSubscriptionUnpaid)
  <div class="sub-card">
    <div class="sub-card-body">
        @php
          $plan    = $subscription->plan;
          $features = is_array($plan->features) ? $plan->features : [];

          $statusClass = match($subscription->status ?? '') {
            'active'    => 'b-active',
            'pending'   => 'b-pending',
            'cancelled' => 'b-cancelled',
            default     => 'b-inactive',
          };
        @endphp

        <div class="sub-plan-grid">

          {{-- LEFT — plan details --}}
          <div class="sub-plan-left">
            <span class="sub-badge {{ $statusClass }}">
              <span class="b-dot"></span>{{ ucfirst($subscription->status) }}
            </span>
            <div class="sub-plan-name">{{ $plan->name }}</div>
            <div class="sub-plan-desc">
              {{ $plan->description ?? 'Premium trading signals and automated execution.' }}
            </div>

            <div class="sub-meta">
              <div class="sub-meta-item">
                <label>Started On</label>
                <div class="val">{{ optional($subscription->starts_at)->format('d M Y, H:i') ?? '—' }}</div>
              </div>
              <div class="sub-meta-item">
                <label>Renewal Date</label>
                <div class="val val-blue">{{ optional($subscription->ends_at)->format('d M Y, H:i') ?? '—' }}</div>
              </div>
              <div class="sub-meta-item">
                <label>Profit Share</label>
                <div class="val val-green">{{ $plan->profit_share ?? '0' }}%</div>
              </div>
              <div class="sub-meta-item">
                <label>Reference</label>
                <div class="val">#{{ $subscription->reference ?? 'N/A' }}</div>
              </div>
            </div>
          </div>

          {{-- VERTICAL DIVIDER --}}
          <div class="sub-divider-v"></div>

          {{-- RIGHT — features + actions --}}
          <div class="sub-plan-right">
            <div class="sub-features-box">
              <h6><i class="fa fa-check-circle"></i> Included Features</h6>
              <ul class="sub-feat-list">
                @forelse($features as $f)
                  <li>
                    <i class="fa fa-check"></i>
                    {{ is_array($f) ? ($f['name'] ?? 'Custom Feature') : $f }}
                  </li>
                @empty
                  <li style="color:#94a3b8;font-style:italic;">Standard access features apply.</li>
                @endforelse
              </ul>
            </div>

            <div class="sub-actions">
              <button type="button" class="btn-sub-upgrade"
                      data-toggle="modal" data-target="#changePlanModal">
                <i class="fa fa-arrow-up"></i> Upgrade
              </button>
              <button type="button" class="btn-sub-renew"
                      data-toggle="modal" data-target="#renewPlanModal">
                <i class="fa fa-refresh"></i> Renew
              </button>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
  @endif

  {{-- ── AVAILABLE PLANS ── --}}
  <div class="sub-section-hdr">
    @if($noSubscriptionChosen)
      Choose Your Plan
    @elseif($isSubscriptionUnpaid)
      Complete Payment or Upgrade to Premium
    @else
      Available Plans
    @endif
  </div>

  <div class="sub-plans-grid">
    @forelse($plans as $p)
      @php
        $pFeatures = is_array($p->features) ? $p->features : [];
        $isFeatured = ($p->is_featured ?? false) || (($p->badge ?? '') !== '');
        
        // Skip plans based on user's current subscription (only if paid)
        $shouldSkip = false;
        $planNameLower = strtolower($p->name);
        
        // Only apply hiding logic if subscription is paid/active
        if ($subscription && $subscription->plan && !$isSubscriptionUnpaid) {
          $userPlanNameLower = strtolower($subscription->plan->name);
          
          // Don't show the plan user already chose
          if ($planNameLower === $userPlanNameLower) {
            $shouldSkip = true;
          }
          
          // If user chose Entry Plan, show ONLY Pro plan
          if ($userPlanNameLower === 'entry plan' && $planNameLower !== 'pro' && $planNameLower !== 'professional') {
            $shouldSkip = true;
          }
        }
        
        // Highlight better plans when user has Entry Plan or unpaid subscription
        $shouldHighlight = false;
        if ($isSubscriptionUnpaid || $noSubscriptionChosen) {
          if ($planNameLower === 'pro' || $planNameLower === 'professional') {
            $shouldHighlight = true;
          }
        }
      @endphp

      @if(!$shouldSkip)
      <div class="sub-plan-card {{ $isFeatured || $shouldHighlight ? 'featured' : '' }}" 
           @if($shouldHighlight) style="border: 2px solid #10b981; box-shadow: 0 8px 28px rgba(16,185,129,0.2);" @endif>

        <div class="sub-plan-card-top">
          @if($isFeatured || $shouldHighlight)
            <div class="sub-pc-badge" @if($shouldHighlight) style="background: #dcfce7; color: #15803d;" @endif>
              {{ ($shouldHighlight && !$isFeatured) ? '⭐ RECOMMENDED' : ($p->badge ?? 'Most Popular') }}
            </div>
          @endif
          <div class="sub-pc-name">{{ $p->name }}</div>
          <div class="sub-pc-desc">{{ Str::limit($p->description ?? '', 70) }}</div>
        </div>

        <div class="sub-plan-card-body">
          <div class="sub-pc-price">
            <span class="sub-pc-currency">{{ $p->currency }}</span>
            <span class="sub-pc-amount">{{ number_format((float) $p->price, 0) }}</span>
          </div>
          <div class="sub-pc-interval">per {{ $p->billing_interval }}</div>

          <ul class="sub-pc-feats">
            @foreach(array_slice($pFeatures, 0, 5) as $f)
              <li>
                <i class="fa fa-bolt"></i>
                {{ is_array($f) ? ($f['name'] ?? 'Premium Feature') : $f }}
              </li>
            @endforeach
          </ul>

          <a class="btn-sub-get subscribeBtn"
             href="{{ route('user.plans.show', $p->slug) }}"
             data-slug="{{ $p->slug }}"
             data-name="{{ $p->name }}"
             data-price="{{ $p->currency }} {{ number_format((float) $p->price, 2) }}"
             @if($shouldHighlight) style="background: #10b981; font-weight: 700; font-size: 13px;" @endif>
            {{ $shouldHighlight ? '⭐ Choose This Plan' : 'Get Started' }}
          </a>
        </div>

      </div>
      @endif

    @empty
      <div style="grid-column:1/-1;text-align:center;padding:40px;color:#94a3b8;font-size:13px;">
        No plans available at the moment.
      </div>
    @endforelse
  </div>

</div>{{-- end .sub --}}

{{-- ── SUBSCRIPTION PAYMENT MODAL ── --}}
@if($isSubscriptionUnpaid && $subscription)
<div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 3000;" id="subscriptionPaymentModal">
    <div style="background: #fff; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
            <h5 style="font-size: 14px; font-weight: 700; margin: 0;">Complete Subscription Payment</h5>
            <button type="button" id="closeSubscriptionPaymentModal" style="background: none; border: none; color: rgba(255,255,255,0.8); font-size: 18px; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 20px;">
            {{-- Payment Amount --}}
            <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-radius: 6px; border-left: 4px solid #f59e0b;">
                <p style="font-size: 12px; color: #7a8fa6; margin-bottom: 4px; font-weight: 600;">SUBSCRIPTION AMOUNT</p>
                <p style="font-size: 16px; color: #2A3F54; font-weight: 700; margin-bottom: 0;">{{ $subscription->plan->currency }} {{ number_format($subscription->plan->price, 2) }}</p>
            </div>

            {{-- Payment Method Selection --}}
            <div style="margin-bottom: 20px;">
                <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 10px;">SELECT PAYMENT METHOD</label>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <label style="flex: 1; cursor: pointer; min-width: 100px;">
                        <input type="radio" name="subPaymentMethod" value="momo" checked style="margin-right: 5px;">
                        <span style="font-size: 12px; color: #2A3F54; font-weight: 600;">🟠 MOMO</span>
                    </label>
                    <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                        <input type="radio" name="subPaymentMethod" value="binance" disabled style="margin-right: 5px;">
                        <span style="font-size: 12px; color: #999; font-weight: 600;">🔶 BINANCE</span>
                    </label>
                    <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                        <input type="radio" name="subPaymentMethod" value="visa" disabled style="margin-right: 5px;">
                        <span style="font-size: 12px; color: #999; font-weight: 600;">💳 VISA</span>
                    </label>
                    <label style="flex: 1; cursor: not-allowed; min-width: 100px; opacity: 0.5;">
                        <input type="radio" name="subPaymentMethod" value="paypal" disabled style="margin-right: 5px;">
                        <span style="font-size: 12px; color: #999; font-weight: 600;">🅿️ PAYPAL</span>
                    </label>
                </div>
            </div>

            {{-- MOMO Details Section --}}
            <div id="subMomoDetails" style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border-radius: 6px; border: 1px solid #ffc107; display: block;">
                <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 8px;">MOMO PHONE NUMBER</label>
                <input type="text" id="subMomoPhone" placeholder="e.g., +250 7XX XXX XXX" style="width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px; margin-bottom: 10px;" required>
                
                <label style="font-size: 12px; color: #2A3F54; font-weight: 600; display: block; margin-bottom: 8px;">ACCOUNT NAME</label>
                <input type="text" id="subMomoName" placeholder="Full name on MOMO account" style="width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;" required>
            </div>

            <div style="padding: 12px; background: #e3f2fd; border-radius: 6px; margin-bottom: 15px;">
                <p style="font-size: 11px; color: #1565c0; margin: 0;">ℹ️ Select your preferred payment method above and click "Pay Now" to proceed.</p>
            </div>
        </div>

        <div style="border-top: 1px solid #e0e0e0; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" id="cancelSubscriptionPayment" style="padding: 8px 14px; background: #fff; color: #475569; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Cancel</button>
            <button type="button" id="confirmSubscriptionPaymentBtn" style="padding: 8px 14px; background: #10b981; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Pay Now</button>
        </div>
    </div>
</div>
@endif



{{-- ── SUBSCRIBE MODAL ── --}}
<div class="sub-modal-overlay" id="subModalOverlay">
  <div class="sub-modal">

    <div class="sub-modal-hdr">
      <h5><i class="fa fa-lock mr-2"></i>Complete Subscription</h5>
      <button class="sub-modal-close" id="subModalClose">&times;</button>
    </div>

    <form method="POST" id="subscribeForm" action="#">
      @csrf

      <div class="sub-modal-body">

        {{-- Plan summary --}}
        <div class="sub-plan-summary">
          <div>
            <div class="sub-plan-summary-name" id="subModalPlanName"></div>
            <div class="sub-plan-summary-label">Subscription Plan</div>
          </div>
          <div class="sub-plan-summary-price" id="subModalPlanPrice"></div>
        </div>

        {{-- Payment method --}}
        <div class="sub-form-group">
          <label class="sub-form-label">Payment Method</label>
          <select name="provider" class="sub-form-control" id="subProvider" required>
            <option value="momo">Mobile Money (MoMo)</option>
            <option value="binance">Binance Pay (Crypto)</option>
          </select>
        </div>

        {{-- MoMo phone — shown/hidden based on provider --}}
        <div class="sub-form-group" id="subPhoneGroup">
          <label class="sub-form-label">MoMo Phone Number</label>
          <input type="text" name="phone" class="sub-form-control" placeholder="07xxxxxxxx">
          <div class="sub-form-hint">Required for MoMo push notification.</div>
        </div>

        <div class="sub-info-box">
          <i class="fa fa-info-circle"></i>
          Activation is automatic once the transaction is confirmed on the blockchain / network.
        </div>

      </div>

      <div class="sub-modal-footer">
        <button type="button" class="btn-sub-cancel" id="subModalCancelBtn">Cancel</button>
        <button type="submit" class="btn-sub-confirm">
          <i class="fa fa-check mr-1"></i> Confirm &amp; Pay
        </button>
      </div>

    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
  'use strict';

  var overlay    = document.getElementById('subModalOverlay');
  var closeBtn   = document.getElementById('subModalClose');
  var cancelBtn  = document.getElementById('subModalCancelBtn');
  var form       = document.getElementById('subscribeForm');
  var planName   = document.getElementById('subModalPlanName');
  var planPrice  = document.getElementById('subModalPlanPrice');
  var provider   = document.getElementById('subProvider');
  var phoneGroup = document.getElementById('subPhoneGroup');

  /* Open modal when a "Get Started" card button is clicked */
  document.querySelectorAll('.subscribeBtn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      planName.textContent  = this.dataset.name  || '';
      planPrice.textContent = this.dataset.price || '';
      form.action = "{{ url('/user/checkout') }}/" + (this.dataset.slug || '');
      overlay.classList.add('open');
    });
  });

  /* Close helpers */
  function closeModal() { overlay.classList.remove('open'); }
  closeBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });

  /* Show/hide phone field based on provider */
  provider.addEventListener('change', function () {
    phoneGroup.style.display = this.value === 'momo' ? 'block' : 'none';
  });

})();

  // Subscription Payment Modal Handling
  document.addEventListener('DOMContentLoaded', function() {
    var completePaymentBtn = document.getElementById('completePaymentBtn');
    var paymentModal = document.getElementById('subscriptionPaymentModal');
    var closePaymentModal = document.getElementById('closeSubscriptionPaymentModal');
    var cancelPayment = document.getElementById('cancelSubscriptionPayment');
    var confirmPaymentBtn = document.getElementById('confirmSubscriptionPaymentBtn');
    
    if (!completePaymentBtn) return;
    
    // Open modal on button click
    completePaymentBtn.addEventListener('click', function() {
      if (paymentModal) {
        paymentModal.style.display = 'flex';
      }
    });
    
    // Close modal functions
    function closeModal() {
      if (paymentModal) {
        paymentModal.style.display = 'none';
      }
    }
    
    if (closePaymentModal) {
      closePaymentModal.addEventListener('click', closeModal);
    }
    
    if (cancelPayment) {
      cancelPayment.addEventListener('click', closeModal);
    }
    
    // Close when clicking outside modal
    if (paymentModal) {
      paymentModal.addEventListener('click', function(e) {
        if (e.target === paymentModal) {
          closeModal();
        }
      });
    }
    
    // Payment confirmation
    if (confirmPaymentBtn) {
      confirmPaymentBtn.addEventListener('click', function() {
        var selectedMethod = document.querySelector('input[name="subPaymentMethod"]:checked').value;
        
        if (!selectedMethod) {
          alert('Please select a payment method');
          return;
        }
        
        if (selectedMethod !== 'momo') {
          alert('This payment method is coming soon. Only MOMO is available now.');
          return;
        }
        
        var momoPhone = document.getElementById('subMomoPhone').value.trim();
        var momoName = document.getElementById('subMomoName').value.trim();
        
        if (!momoPhone) {
          alert('Please enter MOMO phone number');
          return;
        }
        
        if (!momoName) {
          alert('Please enter account name');
          return;
        }
        
        // Show loading state
        confirmPaymentBtn.disabled = true;
        confirmPaymentBtn.textContent = 'Processing...';
        
        // Send payment request to server
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route("user.subscriptions.payment") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            subscription_id: '{{ $subscription->id ?? "" }}',
            payment_method: selectedMethod,
            momo_phone: momoPhone,
            momo_name: momoName,
            amount: parseFloat('{{ $subscription->plan->price ?? 0 }}')
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Payment initiated successfully
            closeModal();
            
            // Show success message
            alert(data.message || 'Payment request has been sent to your phone. Please complete the payment.');
            
            // Redirect to payment pending page if redirect_url is provided
            if (data.redirect_url) {
              setTimeout(() => {
                location.href = data.redirect_url;
              }, 1500);
            } else {
              // Reload page
              setTimeout(() => {
                location.reload();
              }, 1500);
            }
          } else {
            // Payment initiation failed
            confirmPaymentBtn.disabled = false;
            confirmPaymentBtn.textContent = 'Pay Now';
            alert('Error: ' + (data.message || 'Failed to initiate payment. Please try again.'));
          }
        })
        .catch(error => {
          confirmPaymentBtn.disabled = false;
          confirmPaymentBtn.textContent = 'Pay Now';
          console.error('Error:', error);
          alert('Error processing payment: ' + (error.message || 'Please try again'));
        });
      });
    }
  });
</script>
@endpush