@extends('layouts.admin')

@section('title', 'Plan Comparison — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.comparison-table { width: 100%; background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
.comparison-row { display: grid; grid-template-columns: 180px repeat(auto-fit, minmax(200px, 1fr)); border-bottom: 1px solid rgba(255,255,255,0.07); }
.comparison-row:last-child { border-bottom: none; }
.comparison-header { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); padding: 16px; font-weight: 700; color: #f1f5f9; border-right: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; gap: 8px; }
.comparison-label { padding: 16px; font-weight: 600; color: #94a3b8; border-right: 1px solid rgba(255,255,255,0.07); background-color: rgba(26,187,156,0.05); display: flex; align-items: center; gap: 8px; }
.comparison-cell { padding: 16px; color: #f1f5f9; display: flex; align-items: center; justify-content: center; text-align: center; }
.comparison-cell.feature { justify-content: flex-start; }
.feature-list { list-style: none; padding: 0; margin: 0; text-align: left; }
.feature-list li { padding: 4px 0; color: #94a3b8; font-size: 12px; display: flex; align-items: center; gap: 6px; }
.feature-list li.included { color: #22C55E; }
.feature-list li.included:before { content: '✓'; font-weight: bold; color: #22C55E; }
.feature-list li.excluded:before { content: '✕'; font-weight: bold; color: #ef4444; }
.price-cell { font-size: 28px; font-weight: 900; color: #1ABB9C; }
.period-cell { font-size: 12px; color: #4b5563; }
.vi-btn { padding: 8px 14px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-badge { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; display: inline-block; text-transform: uppercase; letter-spacing: 1px; }
.vi-badge-active { background-color: rgba(34,197,94,0.15) !important; color: #22C55E !important; border: 1px solid rgba(34,197,94,0.3); }
.vi-badge-inactive { background-color: rgba(107,114,128,0.15) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.3); }
.comparison-actions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }
.recommended-tag { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #A78BFA 0%, #9f7aea 100%); color: white; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🔄 Plan Comparison</div>
        <div class="vi-header-title">Compare Subscription Plans</div>
        <div class="vi-header-sub">Side-by-side comparison of all plans with features and pricing</div>
    </div>
    <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-chevron-left"></i> Back to Plans
    </a>
</div>

<!-- Comparison Table -->
<div style="overflow-x: auto;">
    <div class="comparison-table">
        <!-- Header Row with Plan Names -->
        <div class="comparison-row">
            <div class="comparison-header">Plan Name</div>
            @foreach($plans as $plan)
                <div class="comparison-header" style="position: relative;">
                    {{ $plan->name }}
                    @if($plan->is_recommended)
                        <div class="recommended-tag">⭐ Featured</div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Status Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-toggle-on"></i> Status</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    @if($plan->is_active)
                        <span class="vi-badge vi-badge-active">Active</span>
                    @else
                        <span class="vi-badge vi-badge-inactive">Inactive</span>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Price Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-dollar"></i> Price</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    <div>
                        <div class="price-cell">${{ number_format($plan->price, 2) }}</div>
                        <div class="period-cell">per {{ $plan->billing_interval ?? 'month' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Duration Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-calendar"></i> Duration</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    {{ $plan->duration ?? 'N/A' }} days
                </div>
            @endforeach
        </div>

        <!-- Trial Days Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-star"></i> Trial</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    {{ $plan->trial_days ?? 0 }} days
                </div>
            @endforeach
        </div>

        <!-- Subscribers Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-users"></i> Subscribers</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    <strong style="color: #22C55E;">{{ $plan->subscriptions_count ?? 0 }}</strong>
                </div>
            @endforeach
        </div>

        <!-- Monthly Revenue Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-chart-line"></i> Revenue</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    <strong style="color: #1ABB9C;">${{ number_format(($plan->subscriptions_count ?? 0) * $plan->price, 0) }}</strong>
                </div>
            @endforeach
        </div>

        <!-- Visibility Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-eye"></i> Visibility</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    {{ $plan->is_visible ? '👁️ Visible' : '🔒 Hidden' }}
                </div>
            @endforeach
        </div>

        <!-- Description Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-align-left"></i> Description</div>
            @foreach($plans as $plan)
                <div class="comparison-cell feature">
                    <div style="text-align: left; font-size: 12px;">{{ Str::limit($plan->description ?? 'No description', 80) }}</div>
                </div>
            @endforeach
        </div>

        <!-- Features Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-star"></i> Features</div>
            @foreach($plans as $plan)
                <div class="comparison-cell feature">
                    @if($plan->features && count($features = (is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [])) > 0)
                        <ul class="feature-list">
                            @foreach($features as $feature)
                                <li class="included">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="color: #4b5563;">No features listed</div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sort Order Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-sort"></i> Sort Order</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    {{ $plan->sort_order ?? 'N/A' }}
                </div>
            @endforeach
        </div>

        <!-- Actions Row -->
        <div class="comparison-row">
            <div class="comparison-label"><i class="fa fa-cog"></i> Actions</div>
            @foreach($plans as $plan)
                <div class="comparison-cell">
                    <div class="comparison-actions">
                        <a href="{{ route('admin.subscription_plans.show', $plan) }}" class="vi-btn vi-btn-secondary" style="width: auto;">
                            <i class="fa fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.subscription_plans.edit', $plan) }}" class="vi-btn vi-btn-primary" style="width: auto;">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Legend -->
<div style="margin-top: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
    <div style="background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 16px;">
        <div style="font-weight: 700; color: #f1f5f9; margin-bottom: 8px;">📊 Key Metrics</div>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: #94a3b8;">
            <li style="padding: 4px 0;"><strong>Subscribers:</strong> Total active subscribers for this plan</li>
            <li style="padding: 4px 0;"><strong>Revenue:</strong> Monthly revenue generated (Subscribers × Price)</li>
            <li style="padding: 4px 0;"><strong>Featured:</strong> Plans marked as recommended on public website</li>
        </ul>
    </div>
    <div style="background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 16px;">
        <div style="font-weight: 700; color: #f1f5f9; margin-bottom: 8px;">💼 Plan Configuration</div>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: #94a3b8;">
            <li style="padding: 4px 0;"><strong>Duration:</strong> How long the plan subscription lasts</li>
            <li style="padding: 4px 0;"><strong>Trial:</strong> Free trial period in days before charging</li>
            <li style="padding: 4px 0;"><strong>Visibility:</strong> Whether plan is shown to public users</li>
        </ul>
    </div>
</div>

@endsection
