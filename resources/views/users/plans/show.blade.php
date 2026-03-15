@extends('layouts.user')

@push('styles')
<style>
body { background-color: #0a0e17 !important; color: #f1f5f9; }
.container { max-width: 900px; }
.vi-header { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-top: 3px solid #1ABB9C; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); }
.vi-header-title { font-size: 28px; font-weight: 900; color: #f1f5f9; margin-bottom: 8px; }
.vi-header-sub { font-size: 14px; color: #94a3b8; }
.vi-breadcrumb { font-size: 12px; color: #4b5563; margin-bottom: 16px; }
.vi-breadcrumb a { color: #1ABB9C; text-decoration: none; }
.vi-breadcrumb a:hover { text-decoration: underline; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-section-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-section-title i { color: #1ABB9C; }
.vi-price-display { font-size: 42px; font-weight: 900; color: #1ABB9C; line-height: 1; margin-bottom: 4px; }
.vi-price-period { font-size: 13px; color: #4b5563; }
.vi-feature-list { list-style: none; padding: 0; margin: 0; }
.vi-feature-list li { padding: 10px 0; color: #94a3b8; font-size: 13px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.vi-feature-list li:last-child { border-bottom: none; }
.vi-feature-list li:before { content: '✓'; color: #22C55E; font-weight: bold; font-size: 16px; }
.vi-btn { padding: 12px 24px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s; }
.vi-btn-primary { background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%); color: #fff; box-shadow: 0 4px 12px rgba(26,187,156,0.25); }
.vi-btn-primary:hover { box-shadow: 0 6px 20px rgba(26,187,156,0.35); }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13); color: #9CA3AF; border: 1px solid rgba(107,114,128,0.25); }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2); }
.vi-description { color: #94a3b8; font-size: 14px; line-height: 1.8; margin-bottom: 20px; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 14px; margin-bottom: 16px; }
.vi-info-icon { color: #1ABB9C; margin-right: 8px; }
.vi-info-text { font-size: 12px; color: #94a3b8; }
.row { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
.col { }
.flex-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="vi-header">
        <div class="vi-breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a> /
            <a href="{{ route('user.plans.index') }}">Subscription Plans</a> /
            <span>{{ $plan->name }}</span>
        </div>
        <div class="vi-header-title">{{ $plan->name }}</div>
        <div class="vi-header-sub">Complete plan details and subscription information</div>
    </div>

    <div class="row">
        <!-- Left: Plan Details -->
        <div class="col">
            <!-- Description Section -->
            <div class="vi-panel">
                <div class="vi-section-title"><i class="fa fa-info-circle"></i> About This Plan</div>
                <p class="vi-description">
                    {{ $plan->description ?? 'No description available for this plan.' }}
                </p>
            </div>

            <!-- Features Section -->
            <div class="vi-panel">
                <div class="vi-section-title"><i class="fa fa-star"></i> Included Features</div>
                
                @php 
                    $features = is_array($plan->features) ? $plan->features : (is_string($plan->features) ? json_decode($plan->features, true) ?? [] : []);
                @endphp

                @if(count($features) > 0)
                    <ul class="vi-feature-list">
                        @foreach($features as $feature)
                            <li>{{ is_array($feature) ? $feature['name'] ?? $feature : $feature }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="vi-info-box">
                        <i class="fa fa-info-circle vi-info-icon"></i>
                        <span class="vi-info-text">No features listed for this plan yet.</span>
                    </div>
                @endif
            </div>

            <!-- Plan Information -->
            <div class="vi-panel">
                <div class="vi-section-title"><i class="fa fa-cog"></i> Plan Configuration</div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 4px;">Billing Interval</div>
                        <div style="font-size: 14px; color: #f1f5f9; font-weight: 600;">{{ ucfirst($plan->billing_interval ?? 'monthly') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 4px;">Duration</div>
                        <div style="font-size: 14px; color: #f1f5f9; font-weight: 600;">
                            @if($plan->duration_days)
                                {{ $plan->duration_days }} days
                            @else
                                Unlimited
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Box -->
            <div class="vi-info-box" style="background: rgba(34,197,94,0.05); border-left-color: #22C55E;">
                <i class="fa fa-bolt" style="color: #22C55E; margin-right: 8px;"></i>
                <span style="font-size: 12px; color: #22C55E; font-weight: 600;">Instant Activation</span>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">
                    Your access is activated immediately after successful payment confirmation.
                </div>
            </div>
        </div>

        <!-- Right: Pricing & CTA -->
        <div class="col">
            <div class="vi-panel">
                <div class="vi-section-title"><i class="fa fa-credit-card"></i> Pricing & Checkout</div>

                <!-- Price Display -->
                <div style="padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.07); margin-bottom: 20px;">
                    <div class="vi-price-display">
                        {{ $plan->currency ?? 'NGN' }}
                        {{ number_format((float)$plan->price, 2) }}
                    </div>
                    <div class="vi-price-period">
                        per {{ $plan->billing_interval ?? 'month' }}
                    </div>
                </div>

                <!-- Order Summary -->
                <div style="background: rgba(26,187,156,0.02); border: 1px solid rgba(26,187,156,0.1); border-radius: 6px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="color: #94a3b8; font-size: 12px;">Plan Cost</span>
                        <span style="color: #f1f5f9; font-weight: 600;">{{ $plan->currency ?? 'NGN' }} {{ number_format((float)$plan->price, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.05);">
                        <span style="color: #1ABB9C; font-weight: 700;">Total</span>
                        <span style="color: #1ABB9C; font-weight: 900; font-size: 16px;">{{ $plan->currency ?? 'NGN' }} {{ number_format((float)$plan->price, 2) }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex-actions">
                    @if(auth()->check())
                        <form action="{{ route('user.subscriptions.store') }}" method="POST" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="vi-btn vi-btn-primary" style="width: 100%; justify-content: center;">
                                <i class="fa fa-check-circle"></i> Subscribe Now
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="vi-btn vi-btn-primary" style="flex: 1; justify-content: center;">
                            <i class="fa fa-sign-in"></i> Login to Subscribe
                        </a>
                    @endif
                    
                    <a href="{{ route('user.plans.index') }}" class="vi-btn vi-btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <!-- Info Messages -->
                <div class="vi-info-box" style="margin-top: 20px;">
                    <i class="fa fa-shield" style="color: #1ABB9C; margin-right: 8px;"></i>
                    <span style="font-size: 12px; color: #1ABB9C; font-weight: 600;">Secure Payment</span>
                    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                        Your payment information is encrypted and secure. You can cancel anytime.
                    </div>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->subscriptions)
            <div class="vi-panel">
                <div class="vi-section-title"><i class="fa fa-user"></i> Your Status</div>
                @php
                    $userSubscription = auth()->user()->subscriptions()
                        ->where('plan_id', $plan->id)
                        ->first();
                @endphp

                @if($userSubscription)
                    <div style="background: rgba(34,197,94,0.05); border-left: 3px solid #22C55E; padding: 12px; border-radius: 6px;">
                        <div style="font-size: 12px; font-weight: 700; color: #22C55E; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-check-circle"></i> You are subscribed to this plan
                        </div>
                        <div style="font-size: 11px; color: #4b5563; margin-top: 6px;">
                            Active since {{ $userSubscription->created_at->format('M d, Y') }}
                        </div>
                    </div>
                @else
                    <div style="background: rgba(251,146,60,0.05); border-left: 3px solid #FB923C; padding: 12px; border-radius: 6px;">
                        <div style="font-size: 12px; font-weight: 700; color: #FB923C; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-info-circle"></i> You are not yet subscribed
                        </div>
                        <div style="font-size: 11px; color: #4b5563; margin-top: 6px;">
                            Click the button above to start your subscription
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
