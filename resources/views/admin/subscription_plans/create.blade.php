@extends('layouts.admin')

@section('title', 'Create Subscription Plan — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-breadcrumb { font-size: 11px; color: #4b5563; display: flex; align-items: center; gap: 6px; }
.vi-breadcrumb a { color: #1ABB9C; text-decoration: none; transition: color 0.2s; }
.vi-breadcrumb a:hover { color: #15a085; }
.vi-form-container { max-width: 700px; margin: 0 auto; }
</style>
@endpush

@section('content')
<div class="vi-form-container">
    <!-- Header -->
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💎 Billing & Plans</div>
            <div class="vi-header-title">Create New Subscription Plan</div>
            <div class="vi-header-sub">Set up a new subscription tier with pricing, features, and configuration</div>
        </div>
        <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Plans
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.subscription_plans.store') }}" method="POST" novalidate>
        @csrf

        @include('admin.subscription_plans._form', ['plan' => null])

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; margin-top: 0; padding: 0;">
            <button type="submit" class="vi-btn vi-btn-primary" style="margin-top: 20px;">
                <i class="fa fa-check-circle"></i> Create Plan
            </button>
            <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary" style="margin-top: 20px;">
                <i class="fa fa-times"></i> Cancel
            </a>
        </div>
    </form>

    <!-- Help Section -->
    <div style="margin-top: 24px; padding: 16px; background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 8px;">
        <div style="display: flex; gap: 12px;">
            <i class="fa fa-info-circle" style="color: #1ABB9C; flex-shrink: 0; margin-top: 2px;"></i>
            <div style="color: #94a3b8; font-size: 12px; line-height: 1.6;">
                <strong style="color: #f1f5f9;">💡 Pro Tips:</strong><br>
                • Use descriptive plan names that clearly indicate the tier level (e.g., Starter, Professional, Enterprise)<br>
                • Plan slugs should be URL-friendly with lowercase letters, numbers, and hyphens<br>
                • Add 3-5 key features that differentiate this plan from others<br>
                • Currency symbols will be displayed to users based on the plan currency<br>
                • Use the sort order to control which plans appear first to customers
            </div>
        </div>
    </div>
</div>
@endsection
