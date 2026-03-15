@extends('layouts.admin')

@section('title', 'Edit Subscription Plan: ' . $plan->name . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 700px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-details-item { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 6px; padding: 14px; }
.vi-details-label { font-size: 10px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.vi-details-value { font-size: 13px; color: #f1f5f9; font-weight: 600; }
.vi-status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.vi-status-active { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-inactive { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.success-message { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.vi-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px; }
.vi-error-list { list-style: none; padding: 0; margin: 0; }
.vi-error-list li { padding: 3px 0; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; border: 1px solid rgba(239,68,68,0.25) !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.2) !important; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <!-- Header -->
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💎 Billing & Plans</div>
            <div class="vi-header-title">Edit: {{ $plan->name }}</div>
            <div class="vi-header-sub">Update subscription tier details, pricing, and features</div>
        </div>
        <div style="margin-left:auto; display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('admin.subscription_plans.show', $plan) }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-eye"></i> View Plan
            </a>
            <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-chevron-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="success-message">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="vi-error">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul class="vi-error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Plan Details Panel -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-cube"></i> Plan Information</div>

        <div class="vi-info-box">
            <div class="vi-info-box-title">Plan ID</div>
            <div class="vi-info-box-text">{{ $plan->id }}</div>
        </div>

        <!-- Plan Details Grid -->
        <div class="vi-details-grid" style="margin-bottom: 24px;">
            <div class="vi-details-item">
                <div class="vi-details-label">Status</div>
                <div class="vi-details-value">
                    <span class="vi-status-badge {{ $plan->is_active ? 'vi-status-active' : 'vi-status-inactive' }}">
                        <i class="fa {{ $plan->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $plan->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Pricing</div>
                <div class="vi-details-value"><i class="fa fa-dollar"></i> {{ number_format($plan->price, 2) }} {{ $plan->currency }}</div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Billing Interval</div>
                <div class="vi-details-value">{{ ucfirst($plan->billing_interval) }}</div>
            </div>

            <div class="vi-details-item">
                <div class="vi-details-label">Created</div>
                <div class="vi-details-value"><i class="fa fa-calendar"></i> {{ $plan->created_at->format('M j, Y') }}</div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.subscription_plans.update', $plan) }}">
            @csrf
            @method('PUT')

            @include('admin.subscription_plans._form', ['plan' => $plan])

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Delete Panel -->
    <div class="vi-panel" style="border-left: 3px solid #ef4444;">
        <div class="vi-panel-title" style="color: #ef4444;"><i class="fa fa-warning" style="color: #ef4444;"></i> Danger Zone</div>

        <div class="vi-info-box" style="background: rgba(239,68,68,0.05); border-color: rgba(239,68,68,0.15); border-left-color: #ef4444;">
            <div class="vi-info-box-title" style="color: #ef4444;">Delete This Plan</div>
            <div class="vi-info-box-text" style="color: #ef4444;">This action cannot be undone. All associated data will be permanently removed.</div>
        </div>

        <form action="{{ route('admin.subscription_plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This will permanently delete the plan &quot;{{ $plan->name }}&quot; and cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="vi-btn vi-btn-danger">
                <i class="fa fa-trash"></i> Delete Plan
            </button>
        </form>
    </div>

</div>

@endsection
