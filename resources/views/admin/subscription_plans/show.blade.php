@extends('layouts.admin')

@section('title', 'Plan - ' . $plan->name . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; margin-bottom: 16px; }
.vi-panel-head { padding: 18px 20px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border-bottom: 1px solid rgba(26,187,156,0.2); border-left: 3px solid #1ABB9C; }
.vi-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 18px 20px; }
.vi-info-item { }
.vi-info-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 6px; }
.vi-info-value { font-size: 13px; font-weight: 600; color: #f1f5f9; }
.vi-section { padding: 18px 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #1ABB9C; margin-bottom: 12px; }
.feature-list { list-style: none; padding: 0; }
.feature-list li { padding: 8px 0; color: #94a3b8; font-size: 12px; display: flex; align-items: center; gap: 8px; }
.feature-list li:before { content: '✓'; color: #22C55E; font-weight: bold; font-size: 14px; }
.vi-badge { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; display: inline-block; text-transform: uppercase; letter-spacing: 1px; }
.vi-badge-active { background-color: rgba(34,197,94,0.15); color: #22C55E; border: 1px solid rgba(34,197,94,0.3); }
.vi-btn { padding: 8px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.price-display { font-size: 36px; font-weight: 900; color: #1ABB9C; }
.billing-period { font-size: 12px; color: #4b5563; margin-top: 4px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💎 Billing & Plans</div>
        <div class="vi-header-title">{{ $plan->name }}</div>
        <div class="vi-header-sub">Subscription plan configuration and details</div>
    </div>
    <div style="margin-left: auto; display: flex; gap: 8px;">
        <a href="{{ route('admin.subscription_plans.edit', $plan) }}" class="vi-btn vi-btn-primary">
            <i class="fa fa-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-chevron-left"></i> Back
        </a>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 4px;">Plan Details</div>
                <div style="font-size: 11px; color: #4b5563;">Created {{ $plan->created_at->format('M d, Y') }}</div>
            </div>
            @if($plan->is_active)
                <span class="vi-badge vi-badge-active">ACTIVE</span>
            @else
                <span class="vi-badge" style="background-color: rgba(107,114,128,0.13); color: #9CA3AF;">INACTIVE</span>
            @endif
        </div>
    </div>

    <div class="vi-section">
        <div class="vi-section-title">💰 Pricing</div>
        <div class="vi-info-grid">
            <div class="vi-info-item">
                <div style="display: flex; align-items: baseline; gap: 8px;">
                    <div class="price-display">${{ number_format($plan->price, 2) }}</div>
                    <div class="billing-period">per month</div>
                </div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Duration</div>
                <div class="vi-info-value">{{ $plan->billing_cycle ?? '1 month' }}</div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Trial Days</div>
                <div class="vi-info-value">{{ $plan->trial_days ?? 0 }} days</div>
            </div>
        </div>
    </div>

    <div class="vi-section">
        <div class="vi-section-title">📋 Plan Information</div>
        <div class="vi-info-grid">
            <div class="vi-info-item">
                <div class="vi-info-label">Plan Name</div>
                <div class="vi-info-value">{{ $plan->name }}</div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Plan Slug</div>
                <div class="vi-info-value"><code style="background-color: #1a2235; padding: 3px 8px; border-radius: 4px; color: #1ABB9C;">{{ $plan->slug }}</code></div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Sort Order</div>
                <div class="vi-info-value">{{ $plan->sort_order ?? 'N/A' }}</div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Subscription Count</div>
                <div class="vi-info-value">{{ $plan->subscriptions_count ?? 0 }} {{ Str::plural('user', $plan->subscriptions_count ?? 0) }}</div>
            </div>
        </div>
    </div>

    @if($plan->description)
        <div class="vi-section">
            <div class="vi-section-title">📝 Description</div>
            <div style="background-color: #1a2235; padding: 12px; border-radius: 6px; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                {{ $plan->description }}
            </div>
        </div>
    @endif

    @if($plan->features)
        <div class="vi-section">
            <div class="vi-section-title">✨ Included Features</div>
            <ul class="feature-list">
                @foreach(is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="vi-section">
        <div class="vi-section-title">⚙️ Configuration</div>
        <div class="vi-info-grid">
            <div class="vi-info-item">
                <div class="vi-info-label">Status</div>
                <div class="vi-info-value">
                    @if($plan->is_active)
                        <span class="vi-badge vi-badge-active">ACTIVE</span>
                    @else
                        <span class="vi-badge" style="background-color: rgba(107,114,128,0.13); color: #9CA3AF;">INACTIVE</span>
                    @endif
                </div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Visibility</div>
                <div class="vi-info-value">
                    @if($plan->is_visible ?? true)
                        <span style="color: #22C55E;">🟢 Visible on website</span>
                    @else
                        <span style="color: #9CA3AF;">🔒 Hidden from public</span>
                    @endif
                </div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Recommended</div>
                <div class="vi-info-value">
                    @if($plan->is_recommended ?? false)
                        <span style="color: #F97316;">⭐ Yes, featured</span>
                    @else
                        <span style="color: #4b5563;">Not featured</span>
                    @endif
                </div>
            </div>
            <div class="vi-info-item">
                <div class="vi-info-label">Created</div>
                <div class="vi-info-value">{{ $plan->created_at->format('M d, Y H:i A') }}<br><span style="font-size:10px; color:#4b5563;">{{ $plan->created_at->diffForHumans() }}</span></div>
            </div>
        </div>
    </div>
</div>

<div style="display: flex; gap: 10px; justify-content: flex-end;">
    <a href="{{ route('admin.subscription_plans.edit', $plan) }}" class="vi-btn vi-btn-primary">
        <i class="fa fa-pencil"></i> Edit Plan
    </a>
</div>

@endsection
