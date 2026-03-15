@extends('layouts.admin')

@section('title', 'Create Subscription Plan — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { padding: 16px 20px; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-form-group { margin-bottom: 18px; }
.vi-form-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; display: block; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-textarea { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; font-family: inherit; min-height: 100px; resize: vertical; transition: all 0.2s; }
.vi-form-textarea:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
.vi-checkbox { display: flex; align-items: center; gap: 10px; padding: 10px; background-color: #1a2235; border: 1px solid rgba(255,255,255,0.07); border-radius: 6px; cursor: pointer; }
.vi-checkbox input { width: 16px; height: 16px; cursor: pointer; }
.vi-checkbox label { cursor: pointer; flex: 1; margin: 0; }
.vi-error { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 12px; }
.vi-error-list { list-style: none; padding: 0; margin: 0; }
.vi-error-list li { padding: 4px 0; }
.vi-btn { padding: 8px 16px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.feature-input-group { display: flex; gap: 8px; margin-bottom: 8px; }
.feature-input-group input { flex: 1; }
.feature-input-group button { padding: 8px 12px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">💎 Billing & Plans</div>
        <div class="vi-header-title">Create New Plan</div>
        <div class="vi-header-sub">Add a new subscription tier for your users</div>
    </div>
    <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-chevron-left"></i> Back
    </a>
</div>

@if($errors->any())
<div class="vi-error">
    <strong>Fix the following errors:</strong>
    <ul class="vi-error-list">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.subscription_plans.store') }}" method="POST">
    @csrf

    <div class="vi-panel">
        <div class="vi-panel-head">
            <div style="font-size: 14px; font-weight: 600; color: #f1f5f9;">Basic Information</div>
        </div>
        <div style="padding: 20px;">

            <div class="vi-grid">
                <div class="vi-form-group">
                    <label class="vi-form-label">Plan Name *</label>
                    <input type="text" class="vi-form-input" name="name" value="{{ old('name') }}" placeholder="e.g., Professional Plan" required>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Plan Slug *</label>
                    <input type="text" class="vi-form-input" name="slug" value="{{ old('slug') }}" placeholder="e.g., professional-plan" pattern="[a-z0-9-]+" required>
                    <small style="color: #4b5563; font-size: 11px;">Lowercase, numbers, and hyphens only</small>
                </div>
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Description</label>
                <textarea class="vi-form-textarea" name="description" placeholder="Brief description of what this plan offers...">{{ old('description') }}</textarea>
            </div>

        </div>
    </div>

    <div class="vi-panel">
        <div class="vi-panel-head">
            <div style="font-size: 14px; font-weight: 600; color: #f1f5f9;">💰 Pricing & Billing</div>
        </div>
        <div style="padding: 20px;">

            <div class="vi-grid">
                <div class="vi-form-group">
                    <label class="vi-form-label">Price *</label>
                    <input type="number" class="vi-form-input" name="price" value="{{ old('price', '0') }}" step="0.01" min="0" placeholder="0.00" required>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Currency *</label>
                    <input type="text" class="vi-form-input" name="currency" value="{{ old('currency', 'USD') }}" placeholder="USD" maxlength="10" required>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Billing Interval *</label>
                    <select class="vi-form-input" name="billing_interval" required>
                        <option value="daily" {{ old('billing_interval') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('billing_interval') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('billing_interval') === 'monthly' ? 'selected' : '' }} selected>Monthly</option>
                        <option value="yearly" {{ old('billing_interval') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Duration (days)</label>
                    <input type="number" class="vi-form-input" name="duration_days" value="{{ old('duration_days') }}" min="1" placeholder="Leave blank for unlimited">
                </div>
            </div>

        </div>
    </div>

    <div class="vi-panel">
        <div class="vi-panel-head">
            <div style="font-size: 14px; font-weight: 600; color: #f1f5f9;">✨ Features</div>
        </div>
        <div style="padding: 20px;">
            <div id="features-container">
                @php
                    $oldFeatures = old('features', []);
                    if (is_string($oldFeatures)) {
                        $oldFeatures = json_decode($oldFeatures, true) ?? [];
                    }
                @endphp
                @forelse($oldFeatures as $feature)
                    <div class="feature-input-group">
                        <input type="text" class="vi-form-input" name="features[]" value="{{ $feature }}" placeholder="Feature name...">
                        <button type="button" class="vi-btn vi-btn-danger" onclick="this.parentElement.remove();">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @empty
                    <div class="feature-input-group">
                        <input type="text" class="vi-form-input" name="features[]" placeholder="Feature name...">
                        <button type="button" class="vi-btn vi-btn-danger" onclick="this.parentElement.remove();">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="vi-btn vi-btn-secondary" onclick="addFeature();" style="margin-top: 10px;">
                <i class="fa fa-plus"></i> Add Feature
            </button>
        </div>
    </div>

    <div class="vi-panel">
        <div class="vi-panel-head">
            <div style="font-size: 14px; font-weight: 600; color: #f1f5f9;">⚙️ Configuration</div>
        </div>
        <div style="padding: 20px;">

            <div class="vi-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                <div class="vi-form-group">
                    <label class="vi-form-label">Sort Order</label>
                    <input type="number" class="vi-form-input" name="sort_order" value="{{ old('sort_order', '0') }}" min="0">
                </div>
            </div>

            <div class="vi-checkbox">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                <label for="is_active"><strong>Active</strong> - This plan is available to users</label>
            </div>

        </div>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button type="submit" class="vi-btn vi-btn-primary">
            <i class="fa fa-save"></i> Create Plan
        </button>
        <a href="{{ route('admin.subscription_plans.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-times"></i> Cancel
        </a>
    </div>

</form>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'feature-input-group';
    div.innerHTML = `
        <input type="text" class="vi-form-input" name="features[]" placeholder="Feature name...">
        <button type="button" class="vi-btn vi-btn-danger" onclick="this.parentElement.remove();">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}
</script>

@endsection
