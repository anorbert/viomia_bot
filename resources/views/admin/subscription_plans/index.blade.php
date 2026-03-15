@extends('layouts.admin')

@section('title', 'Subscription Plans — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-filter-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
.vi-filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px; }
.vi-filter-group { display: flex; flex-direction: column; gap: 6px; }
.vi-filter-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
.vi-filter-input { background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; }
.vi-filter-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-filter-buttons { display: flex; gap: 8px; align-items: flex-end; }
.vi-filter-buttons button { padding: 8px 14px; border-radius: 6px; border: none; cursor: pointer; font-weight: 700; transition: all 0.2s; }
.vi-analytics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-chart-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
.vi-chart-title { font-size: 13px; font-weight: 700; color: #f1f5f9; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.vi-chart--container { height: 240px; position: relative; }
.vi-draggable-card { cursor: grab; position: relative; transition: all 0.3s; }
.vi-draggable-card:hover { border-color: #1ABB9C !important; }
.vi-draggable-card.dragging { opacity: 0.5; transform: scale(0.98); }
.vi-draggable-card.drag-over { border-color: #1ABB9C; background-color: rgba(26,187,156,0.05); }
.vi-drag-handle { position: absolute; top: 12px; left: 12px; cursor: grab; opacity: 0; transition: opacity 0.2s; }
.vi-draggable-card:hover .vi-drag-handle { opacity: 1; color: #1ABB9C; }
.vi-comparison-btn { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(139,92,246,0.25); color: #A78BFA; padding: 8px 14px; border-radius: 6px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
.vi-comparison-btn:hover { border-color: #A78BFA; background: rgba(139,92,246,0.1); }
.vi-comparison-active { background: rgba(139,92,246,0.2) !important; border-color: #A78BFA !important; }
.vi-tab-buttons { display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.07); padding-bottom: 12px; }
.vi-tab-btn { padding: 8px 16px; border: none; background: transparent; color: #94a3b8; cursor: pointer; font-weight: 600; border-bottom: 2px solid transparent; transition: all 0.2s; }
.vi-tab-btn.active { color: #1ABB9C; border-color: #1ABB9C; }
.vi-tab-btn:hover { color: #f1f5f9; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; padding: 0; }
.vi-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.15) !important; border-radius: 14px !important; padding: 24px !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; cursor: default; display: flex; flex-direction: column; box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important; position: relative; overflow: hidden; }
.vi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #1ABB9C, transparent); opacity: 0; transition: opacity 0.3s; }
.vi-card:hover { border-color: #1ABB9C !important; transform: translateY(-6px) !important; box-shadow: 0 12px 32px rgba(26,187,156,0.2) !important; }
.vi-card:hover::before { opacity: 1; }
.vi-card-title { font-size: 16px !important; font-weight: 800 !important; color: #f1f5f9 !important; margin-bottom: 8px; }
.vi-card-price { font-size: 36px !important; font-weight: 900 !important; color: #1ABB9C !important; margin: 12px 0 2px 0; }
.vi-card-period { font-size: 11px !important; color: #4b5563 !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
.vi-card-desc { font-size: 13px !important; color: #94a3b8 !important; margin: 16px 0; line-height: 1.6; flex: 1; }
.vi-features { list-style: none; padding: 0; margin: 12px 0 16px 0; font-size: 12px; }
.vi-features li { padding: 6px 0; color: #94a3b8 !important; display: flex; align-items: flex-start; gap: 8px; }
.vi-features li:before { content: '✓'; color: #22C55E; font-weight: bold; font-size: 13px; min-width: 16px; }
.vi-card-footer { display: flex; gap: 6px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-card-footer a, .vi-card-footer button { flex: 1; }
.vi-badge { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; display: inline-block; text-transform: uppercase; letter-spacing: 1px; }
.vi-badge-active { background-color: rgba(34,197,94,0.15) !important; color: #22C55E !important; border: 1px solid rgba(34,197,94,0.3); }
.vi-badge-inactive { background-color: rgba(107,114,128,0.15) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.3); }
.vi-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px; }
.vi-stat-card { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.2); border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); transition: all 0.3s ease; }
.vi-stat-card:hover { border-color: #1ABB9C; transform: translateY(-2px); }
.vi-stat-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.vi-stat-value { font-size: 28px; font-weight: 900; color: #1ABB9C; }
.vi-btn { padding: 8px 14px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; width: 100%; justify-content: center; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; transform: translateY(-1px); }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; border: 1px solid rgba(239,68,68,0.25) !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.2) !important; }
.empty-state { padding: 60px 20px; text-align: center; background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 14px; }
.empty-state i { font-size: 56px; color: #4b5563; opacity: 0.4; display: block; margin-bottom: 16px; }
.empty-state p { color: #94a3b8; font-size: 15px; margin-bottom: 20px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💎 Billing & Plans</div>
        <div class="vi-header-title">Subscription Plans</div>
        <div class="vi-header-sub">Create and manage subscription tiers for users and traders</div>
    </div>
    <div style="margin-left: auto; display: flex; gap: 8px;">
        <form action="{{ route('admin.subscription_plans.index') }}" method="GET" style="display: inline;">
            <button type="submit" name="view" value="comparison" class="vi-comparison-btn" title="Compare plans side-by-side">
                <i class="fa fa-columns"></i> Compare Plans
            </button>
        </form>
        <a href="{{ route('admin.subscription_plans.create') }}" class="vi-btn vi-btn-primary" style="width: auto;">
            <i class="fa fa-plus-circle"></i> Create Plan
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Tab Navigation -->
<div class="vi-tab-buttons">
    <button class="vi-tab-btn active" onclick="switchTab('overview')">📊 Overview</button>
    <button class="vi-tab-btn" onclick="switchTab('plans')">💼 Plans</button>
    <button class="vi-tab-btn" onclick="switchTab('analytics')">📈 Analytics</button>
    <button class="vi-tab-btn" onclick="switchTab('subscribers')">👥 Subscribers</button>
</div>

<!-- Overview Tab -->
<div id="overview-tab" class="tab-content">
    <!-- Statistics Cards -->
    <div class="vi-stats-row">
        <div class="vi-stat-card">
            <div class="vi-stat-label"><i class="fa fa-cube" style="color:#1ABB9C;"></i> Total Plans</div>
            <div class="vi-stat-value">{{ $plans->count() }}</div>
        </div>
        <div class="vi-stat-card">
            <div class="vi-stat-label"><i class="fa fa-check-circle" style="color:#22C55E;"></i> Active Plans</div>
            <div class="vi-stat-value" style="color: #22C55E;">{{ $plans->where('is_active', true)->count() }}</div>
        </div>
        <div class="vi-stat-card">
            <div class="vi-stat-label"><i class="fa fa-eye" style="color:#FB923C;"></i> Visible Plans</div>
            <div class="vi-stat-value" style="color: #FB923C;">{{ $plans->where('is_visible', true)->count() }}</div>
        </div>
        <div class="vi-stat-card">
            <div class="vi-stat-label"><i class="fa fa-star" style="color:#A78BFA;"></i> Recommended</div>
            <div class="vi-stat-value" style="color: #A78BFA;">{{ $plans->where('is_recommended', true)->count() }}</div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="vi-analytics-grid">
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-users" style="color: #22C55E;"></i> Total Subscribers</div>
            <div style="font-size: 32px; font-weight: 900; color: #22C55E; margin-top: 12px;">
                {{ $plans->sum(function($p) { return $p->subscriptions_count ?? 0; }) }}
            </div>
        </div>
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-dollar" style="color: #1ABB9C;"></i> Monthly Revenue</div>
            <div style="font-size: 32px; font-weight: 900; color: #1ABB9C; margin-top: 12px;">
                ${{ number_format($plans->sum(function($p) { $subs = $p->subscriptions_count ?? 0; return $subs * $p->price; }), 0) }}
            </div>
        </div>
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-trending-up" style="color: #FB923C;"></i> Avg Price/Plan</div>
            <div style="font-size: 32px; font-weight: 900; color: #FB923C; margin-top: 12px;">
                ${{ number_format($plans->count() > 0 ? $plans->avg('price') : 0, 2) }}
            </div>
        </div>
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-star" style="color: #A78BFA;"></i> Featured Plans</div>
            <div style="font-size: 32px; font-weight: 900; color: #A78BFA; margin-top: 12px;">
                {{ $plans->where('is_recommended', true)->count() }}
            </div>
        </div>
    </div>
</div>

<!-- Plans Tab -->
<div id="plans-tab" class="tab-content" style="display: none;">
    <!-- Advanced Filters -->
    <div class="vi-filter-panel">
        <div style="font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-sliders"></i> Advanced Filters
        </div>
        
        <form id="filterForm" action="{{ route('admin.subscription_plans.index') }}" method="GET">
            <div class="vi-filter-row">
                <div class="vi-filter-group">
                    <label class="vi-filter-label">Search Plans</label>
                    <input type="text" name="search" class="vi-filter-input" placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                
                <div class="vi-filter-group">
                    <label class="vi-filter-label">Status</label>
                    <select name="status" class="vi-filter-input">
                        <option value="">All Plans</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="vi-filter-group">
                    <label class="vi-filter-label">Visibility</label>
                    <select name="visibility" class="vi-filter-input">
                        <option value="">All Plans</option>
                        <option value="visible" {{ request('visibility') == 'visible' ? 'selected' : '' }}>Visible</option>
                        <option value="hidden" {{ request('visibility') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>

                <div class="vi-filter-group">
                    <label class="vi-filter-label">Price Range</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="number" name="price_min" class="vi-filter-input" placeholder="Min" value="{{ request('price_min') }}" style="flex: 1;">
                        <input type="number" name="price_max" class="vi-filter-input" placeholder="Max" value="{{ request('price_max') }}" style="flex: 1;">
                    </div>
                </div>
            </div>

            <div class="vi-filter-buttons">
                <button type="submit" style="background-color: #1ABB9C; color: white;">
                    <i class="fa fa-search"></i> Apply Filters
                </button>
                <a href="{{ route('admin.subscription_plans.index') }}" style="background-color: rgba(107,114,128,0.2); color: #9CA3AF;">
                    <i class="fa fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Plans Grid (Draggable) -->
    @if($plans->count() > 0)
    <div class="vi-grid" id="plansGrid">
        @foreach($plans as $plan)
        <div class="vi-card vi-draggable-card" draggable="true" data-plan-id="{{ $plan->id }}" data-sort-order="{{ $plan->sort_order ?? 0 }}">
            <div class="vi-drag-handle" title="Drag to reorder">
                <i class="fa fa-grip-horizontal"></i>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; gap: 12px;">
                <div class="vi-card-title">{{ $plan->name }}</div>
                @if($plan->is_active)
                    <span class="vi-badge vi-badge-active">Active</span>
                @else
                    <span class="vi-badge vi-badge-inactive">Inactive</span>
                @endif
            </div>

            <div class="vi-card-price">${{ number_format($plan->price, 2) }}</div>
            <div class="vi-card-period">{{ $plan->billing_interval ?? 'monthly' }} billing</div>

            @if($plan->description)
                <div class="vi-card-desc">{{ $plan->description }}</div>
            @endif

            @if($plan->features && count($features = (is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [])) > 0)
                <ul class="vi-features">
                    @foreach(array_slice($features, 0, 4) as $feature)
                        <li>{{ Str::limit($feature, 28) }}</li>
                    @endforeach
                    @if(count($features) > 4)
                        <li style="color: #1ABB9C; opacity: 0.8; padding-top: 8px;">+{{ count($features) - 4 }} more features</li>
                    @endif
                </ul>
            @endif

            <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.07);">
                <div style="font-size: 11px; color: #4b5563; margin-bottom: 10px;">
                    👥 {{ $plan->subscriptions_count ?? 0 }} subscribers
                </div>
            </div>

            <div class="vi-card-footer">
                <a href="{{ route('admin.subscription_plans.show', $plan) }}" class="vi-btn vi-btn-secondary">
                    <i class="fa fa-eye"></i> View
                </a>
                <a href="{{ route('admin.subscription_plans.edit', $plan) }}" class="vi-btn vi-btn-secondary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.subscription_plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button class="vi-btn vi-btn-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @if($plans->hasPages())
    <div style="display: flex; justify-content: center; margin-top: 24px;">
        {{ $plans->links() }}
    </div>
    @endif

    @else

    <div class="empty-state">
        <i class="fa fa-inbox"></i>
        <p>No subscription plans found. Create one to get started.</p>
        <a href="{{ route('admin.subscription_plans.create') }}" class="vi-btn vi-btn-primary" style="display: inline-flex; width: auto;">
            <i class="fa fa-plus-circle"></i> Create New Plan
        </a>
    </div>

    @endif
</div>

<!-- Analytics Tab -->
<div id="analytics-tab" class="tab-content" style="display: none;">
    <div class="vi-analytics-grid">
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-chart-pie"></i> Subscriber Distribution</div>
            <div class="vi-chart--container">
                <canvas id="subscriberChart"></canvas>
            </div>
        </div>
        
        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-chart-bar"></i> Revenue by Plan</div>
            <div class="vi-chart--container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="vi-chart-card">
            <div class="vi-chart-title"><i class="fa fa-chart-line"></i> Plan Performance</div>
            <div class="vi-chart--container" style="height: 320px;">
                <div style="padding: 20px; color: #94a3b8;">
                    <table style="width: 100%; font-size: 12px;">
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <td style="padding: 8px 0; color: #f1f5f9; font-weight: 600;">Plan Name</td>
                            <td style="padding: 8px 0; text-align: right;">Subscribers</td>
                            <td style="padding: 8px 0; text-align: right;">Revenue</td>
                        </tr>
                        @foreach($plans->sortByDesc(function($p) { return ($p->subscriptions_count ?? 0) * $p->price; })->take(10) as $plan)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.07);">
                            <td style="padding: 8px 0;">{{ Str::limit($plan->name, 20) }}</td>
                            <td style="padding: 8px 0; text-align: right; color: #22C55E;">{{ $plan->subscriptions_count ?? 0 }}</td>
                            <td style="padding: 8px 0; text-align: right; color: #1ABB9C;">${{ number_format(($plan->subscriptions_count ?? 0) * $plan->price, 0) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscribers Tab -->
<div id="subscribers-tab" class="tab-content" style="display: none;">
    <div class="vi-chart-card">
        <div class="vi-chart-title"><i class="fa fa-users"></i> Subscribers by Plan</div>
        <div style="padding: 20px; color: #94a3b8;">
            <table style="width: 100%; font-size: 12px;">
                <tr style="border-bottom: 1px solid rgba(26,187,156,0.2);">
                    <td style="padding: 12px; color: #f1f5f9; font-weight: 600;">Plan</td>
                    <td style="padding: 12px; text-align: right; color: #f1f5f9; font-weight: 600;">Subscribers</td>
                    <td style="padding: 12px; text-align: right; color: #f1f5f9; font-weight: 600;">Monthly Revenue</td>
                    <td style="padding: 12px; text-align: center; color: #f1f5f9; font-weight: 600;">% of Total</td>
                </tr>
                @php
                    $totalSubscribers = $plans->sum(function($p) { return $p->subscriptions_count ?? 0; });
                @endphp
                @foreach($plans->sortByDesc(function($p) { return $p->subscriptions_count ?? 0; }) as $plan)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.07);">
                    <td style="padding: 12px; color: #f1f5f9; font-weight: 600;">{{ $plan->name }}</td>
                    <td style="padding: 12px; text-align: right; color: #22C55E; font-weight: 600;">{{ $plan->subscriptions_count ?? 0 }}</td>
                    <td style="padding: 12px; text-align: right; color: #1ABB9C; font-weight: 600;">${{ number_format(($plan->subscriptions_count ?? 0) * $plan->price, 0) }}</td>
                    <td style="padding: 12px; text-align: center; color: #A78BFA; font-weight: 600;">
                        {{ $totalSubscribers > 0 ? round((($plan->subscriptions_count ?? 0) / $totalSubscribers) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').style.display = 'block';
    
    // Update button states
    document.querySelectorAll('.vi-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Initialize charts if on analytics tab
    if (tabName === 'analytics') {
        initCharts();
    }
}

function initCharts() {
    // Subscriber Distribution Chart
    const ctxSubscriber = document.getElementById('subscriberChart');
    if (ctxSubscriber && !ctxSubscriber.chart) {
        const subscriberData = {
            @foreach($plans as $plan)
                '{{ $plan->name }}': {{ $plan->subscriptions_count ?? 0 }},
            @endforeach
        };
        
        ctxSubscriber.chart = new Chart(ctxSubscriber, {
            type: 'doughnut',
            data: {
                labels: Object.keys(subscriberData),
                datasets: [{
                    data: Object.values(subscriberData),
                    backgroundColor: ['#1ABB9C', '#22C55E', '#FB923C', '#A78BFA', '#ef4444'],
                    borderColor: '#111827',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { size: 11 } }
                    }
                }
            }
        });
    }
    
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue && !ctxRevenue.chart) {
        const revenueData = {
            @foreach($plans as $plan)
                '{{ $plan->name }}': {{ ($plan->subscriptions_count ?? 0) * $plan->price }},
            @endforeach
        };
        
        ctxRevenue.chart = new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: Object.keys(revenueData),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: Object.values(revenueData),
                    backgroundColor: '#1ABB9C',
                    borderColor: 'rgba(26,187,156,0.5)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { size: 11 } }
                    }
                },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.07)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.07)' } }
                }
            }
        });
    }
}

// Drag and Drop functionality
let draggedElement = null;

document.querySelectorAll('.vi-draggable-card').forEach(card => {
    card.addEventListener('dragstart', (e) => {
        draggedElement = card;
        card.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });

    card.addEventListener('dragend', (e) => {
        card.classList.remove('dragging');
        document.querySelectorAll('.vi-draggable-card').forEach(c => {
            c.classList.remove('drag-over');
        });
    });

    card.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        if (card !== draggedElement) {
            card.classList.add('drag-over');
        }
    });

    card.addEventListener('dragleave', (e) => {
        card.classList.remove('drag-over');
    });

    card.addEventListener('drop', (e) => {
        e.preventDefault();
        if (card !== draggedElement) {
            const allCards = Array.from(document.querySelectorAll('.vi-draggable-card'));
            const draggedIndex = allCards.indexOf(draggedElement);
            const targetIndex = allCards.indexOf(card);
            
            if (draggedIndex < targetIndex) {
                card.parentNode.insertBefore(draggedElement, card.nextSibling);
            } else {
                card.parentNode.insertBefore(draggedElement, card);
            }
            
            // Save new order
            saveNewOrder();
        }
        card.classList.remove('drag-over');
    });
});

function saveNewOrder() {
    const cards = document.querySelectorAll('.vi-draggable-card');
    const orders = Array.from(cards).map((card, index) => ({
        id: card.dataset.planId,
        sort_order: index
    }));
    
    // Send to server
    fetch('{{ route("admin.subscription_plans.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ orders: orders })
    }).then(response => {
        if (response.ok) {
            console.log('Plans reordered successfully');
        }
    });
}

// Load analytics on page load if analytics tab was selected
if (window.location.hash === '#analytics') {
    switchTab('analytics');
}
</script>

@endsection
