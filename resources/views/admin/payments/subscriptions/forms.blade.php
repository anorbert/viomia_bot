@php
    // $plan may be null in create
    $isEdit = isset($plan) && $plan;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Plan Name <span class="text-danger">*</span></label>
        <input type="text"
               name="name"
               value="{{ old('name', $plan->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror"
               placeholder="e.g. Starter, Pro">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text"
               name="slug"
               value="{{ old('slug', $plan->slug ?? '') }}"
               class="form-control @error('slug') is-invalid @enderror"
               placeholder="e.g. starter, pro">
        <small class="text-muted">Use lowercase, no spaces (alpha_dash).</small>
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Currency <span class="text-danger">*</span></label>
        <select name="currency" class="form-select @error('currency') is-invalid @enderror">
            @php $currency = old('currency', $plan->currency ?? 'RWF'); @endphp
            <option value="RWF" {{ $currency === 'RWF' ? 'selected' : '' }}>RWF</option>
            <option value="USD" {{ $currency === 'USD' ? 'selected' : '' }}>USD</option>
            <option value="EUR" {{ $currency === 'EUR' ? 'selected' : '' }}>EUR</option>
        </select>
        @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Price <span class="text-danger">*</span></label>
        <input type="number"
               step="0.01"
               name="price"
               value="{{ old('price', $plan->price ?? 0) }}"
               class="form-control @error('price') is-invalid @enderror"
               placeholder="0">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Billing Interval <span class="text-danger">*</span></label>
        @php $interval = old('billing_interval', $plan->billing_interval ?? 'monthly'); @endphp
        <select name="billing_interval" class="form-select @error('billing_interval') is-invalid @enderror">
            <option value="daily" {{ $interval === 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ $interval === 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ $interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ $interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
        @error('billing_interval') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Duration Days (optional)</label>
        <input type="number"
               name="duration_days"
               value="{{ old('duration_days', $plan->duration_days ?? '') }}"
               class="form-control @error('duration_days') is-invalid @enderror"
               placeholder="Leave empty to use interval">
        @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
               class="form-control @error('sort_order') is-invalid @enderror">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3 d-flex align-items-end">
        @php $active = old('is_active', ($plan->is_active ?? true)) ? true : false; @endphp
        <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   name="is_active"
                   value="1"
                   id="is_active"
                   {{ $active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Active
            </label>
        </div>
        @error('is_active') <div class="text-danger small ms-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description"
                  rows="3"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Short marketing description...">{{ old('description', $plan->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Features (JSON)</label>
        @php
            $featuresOld = old('features');
            $featuresDefault = isset($plan) && $plan && is_array($plan->features)
                ? json_encode($plan->features, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
                : '';
            $featuresValue = is_string($featuresOld) ? $featuresOld : $featuresDefault;
        @endphp

        <textarea name="features" rows="8" class="form-control font-monospace @error('features') is-invalid @enderror" placeholder='Example:
            {"max_bots": 3, "max_accounts": 2, "signals_per_day": 200}'>{{ $featuresValue }}</textarea>
        <small class="text-muted">Paste valid JSON. If invalid, system will save it as null (you can tighten validation later).</small>
        @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
