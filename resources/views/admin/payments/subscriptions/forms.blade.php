@php
    // $plan may be null in create
    $isEdit = isset($plan) && $plan;
@endphp

<style>
    .form-section {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title i {
        font-size: 16px;
        color: #3b82f6;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .form-label small {
        display: block;
        font-weight: 400;
        color: #6b7280;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-control, .form-select {
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        background-color: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
        font-weight: 500;
    }

    .text-muted {
        color: #6b7280;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-check {
        margin-top: 8px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        border: 1.5px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 2px;
    }

    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .form-check-label {
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        margin-left: 6px;
    }

    .badge-required {
        display: inline-block;
        background-color: #fee2e2;
        color: #991b1b;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 4px;
    }
</style>

{{-- Basic Information Section --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="fa fa-info-circle"></i> Basic Information
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Plan Name
                <span class="badge-required">Required</span>
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $plan->name ?? '') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="e.g. Starter, Pro, Enterprise">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">
                Slug
                <span class="badge-required">Required</span>
                <small>URL-friendly identifier (lowercase, no spaces)</small>
            </label>
            <input type="text"
                   name="slug"
                   value="{{ old('slug', $plan->slug ?? '') }}"
                   class="form-control @error('slug') is-invalid @enderror"
                   placeholder="e.g. starter, pro, enterprise">
            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Brief description of this plan for customers...">{{ old('description', $plan->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Pricing & Billing Section --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="fa fa-credit-card"></i> Pricing & Billing
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Currency
                <span class="badge-required">Required</span>
            </label>
            <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                @php $currency = old('currency', $plan->currency ?? 'RWF'); @endphp
                <option value="">-- Select Currency --</option>
                <option value="RWF" {{ $currency === 'RWF' ? 'selected' : '' }}>RWF - Rwandan Franc</option>
                <option value="USD" {{ $currency === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                <option value="EUR" {{ $currency === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
            </select>
            @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">
                Price
                <span class="badge-required">Required</span>
            </label>
            <input type="number"
                   step="0.01"
                   name="price"
                   value="{{ old('price', $plan->price ?? 0) }}"
                   class="form-control @error('price') is-invalid @enderror"
                   placeholder="0.00"
                   min="0">
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">
                Billing Interval
                <span class="badge-required">Required</span>
            </label>
            @php $interval = old('billing_interval', $plan->billing_interval ?? 'monthly'); @endphp
            <select name="billing_interval" class="form-select @error('billing_interval') is-invalid @enderror">
                <option value="">-- Select Interval --</option>
                <option value="daily" {{ $interval === 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ $interval === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            @error('billing_interval') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">
                Duration Days
                <small>Leave empty to use billing interval</small>
            </label>
            <input type="number"
                   name="duration_days"
                   value="{{ old('duration_days', $plan->duration_days ?? '') }}"
                   class="form-control @error('duration_days') is-invalid @enderror"
                   placeholder="e.g. 30"
                   min="1">
            @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Profit Share (%)</label>
            <input type="number"
                   name="profit_share"
                   value="{{ old('profit_share', $plan->profit_share ?? 0) }}"
                   class="form-control @error('profit_share') is-invalid @enderror"
                   placeholder="0"
                   min="0"
                   max="100">
            @error('profit_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Plan Limitations Section --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="fa fa-lock"></i> Plan Limitations
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">
                Maximum Accounts Allowed
                <span class="badge-required">Required</span>
                <small>Number of trading accounts permitted under this plan</small>
            </label>
            <input type="number"
                   name="max_accounts"
                   min="1"
                   value="{{ old('max_accounts', $plan->max_accounts ?? 1) }}"
                   class="form-control @error('max_accounts') is-invalid @enderror"
                   placeholder="e.g. 5">
            @error('max_accounts') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                   class="form-control @error('sort_order') is-invalid @enderror"
                   placeholder="0">
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Advanced Features Section --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="fa fa-cog"></i> Advanced Features (JSON)
    </div>

    @php
        $featuresOld = old('features');
        $featuresDefault = isset($plan) && $plan && is_array($plan->features)
            ? json_encode($plan->features, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
            : '';
        $featuresValue = is_string($featuresOld) ? $featuresOld : $featuresDefault;
    @endphp

    <div class="mb-3">
        <label class="form-label">Features Configuration</label>
        <textarea name="features" 
                  rows="8" 
                  class="form-control font-monospace @error('features') is-invalid @enderror" 
                  placeholder='{
  "max_bots": 3,
  "signals_per_day": 200,
  "api_calls_per_hour": 1000,
  "custom_strategies": true
}'>{{ $featuresValue }}</textarea>
        <small class="text-muted"><i class="fa fa-info-circle"></i> Enter valid JSON format. Optional - can be empty.</small>
        @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Status Section --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="fa fa-toggle-on"></i> Status
    </div>

    <div class="form-check">
        @php $active = old('is_active', ($plan->is_active ?? true)) ? true : false; @endphp
        <input class="form-check-input"
               type="checkbox"
               name="is_active"
               value="1"
               id="is_active"
               {{ $active ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            <strong>Active Plan</strong>
            <br>
            <small style="color: #6b7280;">Enable this plan for customers to purchase</small>
        </label>
        @error('is_active') <div class="text-danger small ms-2" style="margin-top: 4px;">{{ $message }}</div> @enderror
    </div>
</div>
