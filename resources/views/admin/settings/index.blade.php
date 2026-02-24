@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">
                <i class="fa fa-cog text-primary"></i> System Settings
            </h2>
            <p class="text-muted">Configure general, trading, and security settings</p>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle"></i> Please fix the errors below
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.save') }}" id="settingsForm">
        @csrf

        {{-- SECTION 1: General Settings --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title section-header">
                        <h2 style="margin: 0;">
                            <i class="fa fa-info-circle"></i> General Settings
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label required-field">System Name</label>
                                    <input type="text" class="form-control @error('system_name') is-invalid @enderror" 
                                           name="system_name" value="{{ old('system_name', $settings->system_name) }}" 
                                           placeholder="e.g., Trading Bot Pro">
                                    @error('system_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label required-field">Support Email</label>
                                    <input type="email" class="form-control @error('support_email') is-invalid @enderror" 
                                           name="support_email" value="{{ old('support_email', $settings->support_email) }}" 
                                           placeholder="support@example.com">
                                    @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Support Phone</label>
                                    <input type="text" class="form-control @error('support_phone') is-invalid @enderror" 
                                           name="support_phone" value="{{ old('support_phone', $settings->support_phone) }}" 
                                           placeholder="+1 (555) 123-4567">
                                    @error('support_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Company Website</label>
                                    <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                           name="company_website" value="{{ old('company_website', $settings->company_website) }}" 
                                           placeholder="https://example.com">
                                    @error('company_website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Default Bot</label>
                                    <select class="form-select @error('default_bot') is-invalid @enderror" name="default_bot">
                                        <option value="">-- Select a Bot --</option>
                                        @foreach($bots as $bot)
                                            <option value="{{ $bot->id }}" {{ old('default_bot', $settings->default_bot) == $bot->id ? 'selected' : '' }}>
                                                {{ $bot->name }} (v{{ $bot->version ?? '1.0' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('default_bot')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Theme Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('theme_color') is-invalid @enderror" 
                                               name="theme_color" value="{{ old('theme_color', $settings->theme_color) }}" 
                                               style="max-width: 80px;">
                                        <input type="text" class="form-control @error('theme_color') is-invalid @enderror" 
                                               value="{{ old('theme_color', $settings->theme_color) }}" disabled>
                                    </div>
                                    @error('theme_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: Account & Access Control --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title section-header">
                        <h2 style="margin: 0;">
                            <i class="fa fa-lock"></i> Account & Access Control
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label required-field">Max Accounts per User</label>
                                    <input type="number" class="form-control @error('max_accounts') is-invalid @enderror" 
                                           name="max_accounts" value="{{ old('max_accounts', $settings->max_accounts) }}" 
                                           min="1" max="1000">
                                    @error('max_accounts')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label required-field">Max API Keys per User</label>
                                    <input type="number" class="form-control @error('max_api_keys') is-invalid @enderror" 
                                           name="max_api_keys" value="{{ old('max_api_keys', $settings->max_api_keys) }}" 
                                           min="1" max="100">
                                    @error('max_api_keys')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label required-field">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control @error('session_timeout') is-invalid @enderror" 
                                           name="session_timeout" value="{{ old('session_timeout', $settings->session_timeout) }}" 
                                           min="5" max="1440">
                                    @error('session_timeout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="setting-toggle-grid">
                                    <div class="setting-toggle-item">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-checkbox" type="checkbox" name="enable_2fa" 
                                                   id="enable_2fa" {{ old('enable_2fa', $settings->enable_2fa) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_2fa">
                                                <span class="toggle-label">
                                                    <i class="fa fa-shield"></i> Enable Two-Factor Authentication
                                                </span>
                                                <small class="toggle-description">Require 2FA for all admin accounts</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="setting-toggle-item">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-checkbox" type="checkbox" name="maintenance_mode" 
                                                   id="maintenance_mode" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="maintenance_mode">
                                                <span class="toggle-label">
                                                    <i class="fa fa-wrench"></i> Maintenance Mode
                                                </span>
                                                <small class="toggle-description">Disable user access while maintaining admin access</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Notification Email</label>
                                    <input type="email" class="form-control @error('notification_email') is-invalid @enderror" 
                                           name="notification_email" value="{{ old('notification_email', $settings->notification_email) }}" 
                                           placeholder="admin@example.com">
                                    <small class="text-muted">Email for system alerts and notifications</small>
                                    @error('notification_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Trading Settings --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title section-header">
                        <h2 style="margin: 0;">
                            <i class="fa fa-line-chart"></i> Trading Settings
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="setting-toggle-grid">
                                    <div class="setting-toggle-item">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-checkbox" type="checkbox" name="enable_trading" 
                                                   id="enable_trading" {{ old('enable_trading', $settings->enable_trading) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_trading">
                                                <span class="toggle-label">
                                                    <i class="fa fa-exchange"></i> Enable Trading
                                                </span>
                                                <small class="toggle-description">Allow users to execute trades</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="setting-toggle-item">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-checkbox" type="checkbox" name="enable_withdrawals" 
                                                   id="enable_withdrawals" {{ old('enable_withdrawals', $settings->enable_withdrawals) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_withdrawals">
                                                <span class="toggle-label">
                                                    <i class="fa fa-money"></i> Enable Withdrawals
                                                </span>
                                                <small class="toggle-description">Allow users to withdraw funds</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Trading Hours Start</label>
                                    <input type="time" class="form-control @error('trading_hours_start') is-invalid @enderror" 
                                           name="trading_hours_start" value="{{ old('trading_hours_start', $settings->trading_hours_start) }}">
                                    @error('trading_hours_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Trading Hours End</label>
                                    <input type="time" class="form-control @error('trading_hours_end') is-invalid @enderror" 
                                           name="trading_hours_end" value="{{ old('trading_hours_end', $settings->trading_hours_end) }}">
                                    @error('trading_hours_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Max Daily Trades</label>
                                    <input type="number" class="form-control @error('max_daily_trades') is-invalid @enderror" 
                                           name="max_daily_trades" value="{{ old('max_daily_trades', $settings->max_daily_trades) }}" 
                                           min="1" placeholder="No limit">
                                    <small class="text-muted">Leave empty for no limit</small>
                                    @error('max_daily_trades')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Minimum Trade Size</label>
                                    <input type="number" step="0.01" class="form-control @error('min_trade_size') is-invalid @enderror" 
                                           name="min_trade_size" value="{{ old('min_trade_size', $settings->min_trade_size) }}" 
                                           min="0.01">
                                    @error('min_trade_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Maximum Trade Size</label>
                                    <input type="number" step="0.01" class="form-control @error('max_trade_size') is-invalid @enderror" 
                                           name="max_trade_size" value="{{ old('max_trade_size', $settings->max_trade_size) }}" 
                                           min="0.01">
                                    @error('max_trade_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Daily Loss Limit</label>
                                    <input type="number" step="0.01" class="form-control @error('max_loss_limit') is-invalid @enderror" 
                                           name="max_loss_limit" value="{{ old('max_loss_limit', $settings->max_loss_limit) }}" 
                                           min="0" placeholder="No limit">
                                    <small class="text-muted">Maximum daily loss allowed (leave empty for no limit)</small>
                                    @error('max_loss_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="row">
            <div class="col-md-12">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Save All Settings
                    </button>
                    <button type="reset" class="btn btn-secondary btn-lg">
                        <i class="fa fa-undo"></i> Reset Form
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .section-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header h2 {
        color: #111827;
        font-size: 16px;
        font-weight: 600;
    }

    .section-header i {
        margin-right: 8px;
        color: #3b82f6;
    }

    .x_panel {
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .x_title {
        padding: 16px;
    }

    .x_content {
        padding: 24px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .required-field::after {
        content: ' *';
        color: #ef4444;
    }

    .form-control, .form-select {
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.2s ease;
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

    /* Checkbox Toggle Styling */
    .setting-toggle-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
    }

    .setting-toggle-item {
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        transition: all 0.3s ease;
    }

    .setting-toggle-item:hover {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 0;
    }

    .form-check-input.toggle-checkbox {
        width: 52px;
        height: 32px;
        margin-top: 2px;
        cursor: pointer;
        border: 2px solid #d1d5db;
        border-radius: 16px;
        flex-shrink: 0;
        background-color: #e5e7eb;
        position: relative;
        transition: all 0.3s ease;
        appearance: none;
        -webkit-appearance: none;
    }

    .form-check-input.toggle-checkbox::before {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: white;
        top: 3px;
        left: 3px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-check-input.toggle-checkbox:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    .form-check-input.toggle-checkbox:checked::before {
        left: 25px;
    }

    .form-check-input.toggle-checkbox:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    .form-check-label {
        cursor: pointer;
        margin-bottom: 0;
        flex: 1;
        padding: 2px 0;
    }

    .toggle-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #111827;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .toggle-label i {
        color: #3b82f6;
        font-size: 16px;
    }

    .setting-toggle-item:hover .toggle-label i {
        color: #10b981;
    }

    .toggle-description {
        color: #6b7280;
        font-size: 12px;
        font-weight: 400;
        display: block;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 10px 24px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-lg {
        padding: 12px 28px;
        font-size: 15px;
    }

    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:active {
        background-color: #1d4ed8;
    }

    .btn-secondary {
        background-color: #6b7280;
        border-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        border-color: #4b5563;
    }

    .btn-secondary:active {
        background-color: #374151;
    }

    .input-group .form-control-color {
        padding: 6px;
        cursor: pointer;
        border-radius: 6px;
    }

    .input-group .form-control-color::-webkit-color-swatch-wrapper {
        padding: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .setting-toggle-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection
