<!-- Subscription Plan Form Partial -->

<style>
.vi-form-group { margin-bottom: 20px; }
.vi-form-label { 
    font-size: 11px; 
    font-weight: 700; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    color: #4b5563; 
    margin-bottom: 8px; 
    display: block; 
}
.vi-form-input, .vi-form-textarea {
    width: 100%;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: #f1f5f9;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 13px;
    transition: all 0.2s ease;
    font-family: inherit;
}
.vi-form-input:focus, .vi-form-textarea:focus {
    border-color: #1ABB9C !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important;
}
.vi-form-input.is-invalid, .vi-form-textarea.is-invalid {
    border-color: #ef4444 !important;
}
.vi-form-input.is-invalid:focus, .vi-form-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important;
}
.vi-form-textarea {
    resize: vertical;
    min-height: 80px;
}
.vi-form-error {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}
.vi-form-help {
    color: #4b5563;
    font-size: 11px;
    margin-top: 4px;
    display: block;
}
.vi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.vi-panel {
    background: linear-gradient(135deg, #1a2235 0%, #111827 100%);
    border: 1px solid rgba(26,187,156,0.15);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
    margin-bottom: 20px;
}
.vi-panel-head {
    padding: 18px 20px;
    background: linear-gradient(135deg, #0f172a 0%, #1a2235 100%);
    border-bottom: 1px solid rgba(26,187,156,0.2);
    border-left: 3px solid #1ABB9C;
}
.vi-panel-head div {
    font-size: 14px;
    font-weight: 600;
    color: #f1f5f9;
}
</style>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div>📋 Basic Information</div>
    </div>
    <div style="padding: 20px;">
        <div class="vi-grid">
            <div class="vi-form-group">
                <label class="vi-form-label">Plan Name <span style="color: #ef4444;">*</span></label>
                <input type="text" class="vi-form-input @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', $plan->name ?? '') }}" 
                       placeholder="e.g., Professional Plan" required>
                @error('name')<span class="vi-form-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Plan Slug <span style="color: #ef4444;">*</span></label>
                <input type="text" class="vi-form-input @error('slug') is-invalid @enderror" 
                       name="slug" value="{{ old('slug', $plan->slug ?? '') }}" 
                       placeholder="e.g., professional-plan" pattern="[a-z0-9-]+" required>
                @error('slug')<span class="vi-form-error">{{ $message }}</span>@enderror
                <span class="vi-form-help">Lowercase, numbers, and hyphens only</span>
            </div>
        </div>

        <div class="vi-form-group">
            <label class="vi-form-label">Description</label>
            <textarea class="vi-form-textarea @error('description') is-invalid @enderror" 
                      name="description" placeholder="Brief description of what this plan offers...">{{ old('description', $plan->description ?? '') }}</textarea>
            @error('description')<span class="vi-form-error">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div>💰 Pricing & Billing</div>
    </div>
    <div style="padding: 20px;">
        <div class="vi-grid">
            <div class="vi-form-group">
                <label class="vi-form-label">Price <span style="color: #ef4444;">*</span></label>
                <input type="number" class="vi-form-input @error('price') is-invalid @enderror" 
                       name="price" value="{{ old('price', $plan->price ?? '0') }}" 
                       step="0.01" min="0" placeholder="0.00" required>
                @error('price')<span class="vi-form-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Currency <span style="color: #ef4444;">*</span></label>
                <input type="text" class="vi-form-input @error('currency') is-invalid @enderror" 
                       name="currency" value="{{ old('currency', $plan->currency ?? 'USD') }}" 
                       placeholder="USD" maxlength="10" required>
                @error('currency')<span class="vi-form-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Billing Interval <span style="color: #ef4444;">*</span></label>
                <select class="vi-form-input @error('billing_interval') is-invalid @enderror" name="billing_interval" required>
                    <option value="">-- Select Interval --</option>
                    <option value="daily" {{ (old('billing_interval', $plan->billing_interval ?? '') === 'daily') ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ (old('billing_interval', $plan->billing_interval ?? '') === 'weekly') ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ (old('billing_interval', $plan->billing_interval ?? '') === 'monthly') ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ (old('billing_interval', $plan->billing_interval ?? '') === 'yearly') ? 'selected' : '' }}>Yearly</option>
                </select>
                @error('billing_interval')<span class="vi-form-error">{{ $message }}</span>@enderror
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">Duration (days)</label>
                <input type="number" class="vi-form-input @error('duration_days') is-invalid @enderror" 
                       name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? '') }}" 
                       min="1" placeholder="Leave blank for unlimited">
                @error('duration_days')<span class="vi-form-error">{{ $message }}</span>@enderror
                <span class="vi-form-help">Days until subscription expires (optional)</span>
            </div>
        </div>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div>✨ Features</div>
    </div>
    <div style="padding: 20px;">
        <div id="features-container" style="display: flex; flex-direction: column; gap: 10px;">
            @php
                $features = old('features');
                if ($features === null && isset($plan)) {
                    $features = is_string($plan->features) ? json_decode($plan->features, true) ?? [] : ($plan->features ?? []);
                } elseif (is_string($features)) {
                    $features = json_decode($features, true) ?? [];
                }
                if (!is_array($features)) {
                    $features = [];
                }
            @endphp
            
            @if(count($features) > 0)
                @foreach($features as $idx => $feature)
                    <div class="feature-input-group" style="display: flex; gap: 8px; align-items: center;">
                        <span style="width: 28px; height: 28px; background-color: rgba(26,187,156,0.15); border: 1px solid rgba(26,187,156,0.3); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #1ABB9C; font-size: 12px; font-weight: 700;">{{ $idx + 1 }}</span>
                        <input type="text" class="vi-form-input" name="features[]" value="{{ $feature }}" placeholder="Feature name..." style="flex: 1; margin: 0;">
                        <button type="button" class="vi-btn-remove" onclick="this.parentElement.remove();" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; border-radius: 6px; cursor: pointer; transition: all 0.2s; font-size: 16px;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            @else
                <div class="feature-input-group" style="display: flex; gap: 8px; align-items: center;">
                    <span style="width: 28px; height: 28px; background-color: rgba(26,187,156,0.15); border: 1px solid rgba(26,187,156,0.3); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #1ABB9C; font-size: 12px; font-weight: 700;">1</span>
                    <input type="text" class="vi-form-input" name="features[]" placeholder="Feature name..." style="flex: 1; margin: 0;">
                    <button type="button" class="vi-btn-remove" onclick="this.parentElement.remove();" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; border-radius: 6px; cursor: pointer; transition: all 0.2s; font-size: 16px;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            @endif
        </div>
        <button type="button" class="vi-btn-add" onclick="addFeature();" style="margin-top: 12px; display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background-color: rgba(26,187,156,0.15); border: 1px solid rgba(26,187,156,0.3); color: #1ABB9C; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 12px; transition: all 0.2s;">
            <i class="fa fa-plus"></i> Add Feature
        </button>
    </div>
</div>

<div class="vi-panel">
    <div class="vi-panel-head">
        <div>⚙️ Configuration</div>
    </div>
    <div style="padding: 20px;">
        <div class="vi-grid">
            <div class="vi-form-group">
                <label class="vi-form-label">Sort Order</label>
                <input type="number" class="vi-form-input @error('sort_order') is-invalid @enderror" 
                       name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? '0') }}" min="0">
                @error('sort_order')<span class="vi-form-error">{{ $message }}</span>@enderror
                <span class="vi-form-help">Lower numbers appear first</span>
            </div>
        </div>

        <div style="margin-top: 16px; padding: 16px; background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 8px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin: 0;">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ (old('is_active', $plan->is_active ?? false) ? 'checked' : '') }} style="width: 18px; height: 18px; cursor: pointer;">
                <span style="color: #f1f5f9; font-weight: 600; font-size: 13px;">Plan is Active</span>
                <span style="color: #4b5563; font-size: 11px; margin-left: auto;">Users can subscribe to this plan</span>
            </label>
        </div>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const count = container.children.length + 1;
    const div = document.createElement('div');
    div.className = 'feature-input-group';
    div.style.cssText = 'display: flex; gap: 8px; align-items: center;';
    div.innerHTML = `
        <span style="width: 28px; height: 28px; background-color: rgba(26,187,156,0.15); border: 1px solid rgba(26,187,156,0.3); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #1ABB9C; font-size: 12px; font-weight: 700;">${count}</span>
        <input type="text" class="vi-form-input" name="features[]" placeholder="Feature name..." style="flex: 1; margin: 0;">
        <button type="button" class="vi-btn-remove" onclick="this.parentElement.remove();" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; border-radius: 6px; cursor: pointer; transition: all 0.2s; font-size: 16px;">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

// Add hover effects to buttons
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.vi-btn-remove');
    removeButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(239,68,68,0.2)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'rgba(239,68,68,0.1)';
        });
    });

    const addButton = document.querySelector('.vi-btn-add');
    if (addButton) {
        addButton.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(26,187,156,0.25)';
        });
        addButton.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'rgba(26,187,156,0.15)';
        });
    }
});
</script>
