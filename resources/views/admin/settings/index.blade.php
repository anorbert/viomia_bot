@extends('layouts.admin')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-tabs { display: flex; gap: 0; border-bottom: 1px solid rgba(255,255,255,0.07); margin-bottom: 20px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border-radius: 12px 12px 0 0; }
.vi-tab { padding: 14px 20px; border: none; background: transparent; color: #94a3b8; cursor: pointer; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 3px solid transparent; transition: all 0.3s; }
.vi-tab:hover { color: #f1f5f9; }
.vi-tab.active { color: #1ABB9C; border-bottom-color: #1ABB9C; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
.vi-panel-title { font-size: 15px; font-weight: 700; color: #f1f5f9; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.vi-panel-title i { color: #1ABB9C; font-size: 16px; }
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-toggle-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
.vi-toggle-item { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 8px; padding: 16px; transition: all 0.2s; }
.vi-toggle-item:hover { border-color: rgba(26,187,156,0.4); background: rgba(26,187,156,0.08); }
.vi-toggle-item input[type=checkbox]:checked + label { color: #1ABB9C; }
.vi-toggle-label { font-weight: 600; color: #f1f5f9; display: block; margin-bottom: 4px; }
.vi-toggle-desc { font-size: 11px; color: #4b5563; display: block; }
.vi-error { background-color: rgba(239,68,68,0.1); color: #EF4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; padding: 12px; font-size: 12px; margin-bottom: 12px; }
.vi-success { background-color: rgba(34,197,94,0.1); color: #22C55E; border: 1px solid rgba(34,197,94,0.2); border-radius: 6px; padding: 12px; font-size: 12px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.vi-buttons { display: flex; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.tab-content { display: none; }
.tab-content.active { display: block; }
</style>
@endpush

@section('content')

<div style="max-width: 1400px; margin: 0 auto; padding: 0 15px;">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">⚙️ System Administration</div>
            <div class="vi-header-title">System Settings</div>
            <div class="vi-header-sub">Configure general, trading, security, and system-wide settings</div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="vi-success">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="vi-error">
            <i class="fa fa-exclamation-circle"></i> <strong>Please fix the following errors:</strong>
            <ul style="list-style: none; padding: 0; margin: 8px 0 0 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="vi-tabs">
        <button class="vi-tab active" onclick="switchTab('general')">📋 General</button>
        <button class="vi-tab" onclick="switchTab('security')">🔐 Security</button>
        <button class="vi-tab" onclick="switchTab('trading')">📈 Trading</button>
        <button class="vi-tab" onclick="switchTab('notifications')">🔔 Notifications</button>
    </div>

    <form method="POST" action="{{ route('admin.settings.save') }}" id="settingsForm">
        @csrf

        <!-- GENERAL TAB -->
        <div id="general-tab" class="tab-content active">
            <div class="vi-panel">
                <div class="vi-panel-title"><i class="fa fa-info-circle"></i> General Information</div>
                <div class="vi-form-row">
                    <div class="vi-form-group">
                        <label class="vi-form-label">System Name <span class="required">*</span></label>
                        <input type="text" class="vi-form-input @error('system_name') is-invalid @enderror" 
                               name="system_name" value="{{ old('system_name', $settings->system_name) }}" 
                               placeholder="e.g., Trading Bot Pro">
                        @error('system_name')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Support Email <span class="required">*</span></label>
                        <input type="email" class="vi-form-input @error('support_email') is-invalid @enderror" 
                               name="support_email" value="{{ old('support_email', $settings->support_email) }}" 
                               placeholder="support@example.com">
                        @error('support_email')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Support Phone</label>
                        <input type="text" class="vi-form-input @error('support_phone') is-invalid @enderror" 
                               name="support_phone" value="{{ old('support_phone', $settings->support_phone) }}" 
                               placeholder="+1 (555) 123-4567">
                        @error('support_phone')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Company Website</label>
                        <input type="url" class="vi-form-input @error('company_website') is-invalid @enderror" 
                               name="company_website" value="{{ old('company_website', $settings->company_website) }}" 
                               placeholder="https://example.com">
                        @error('company_website')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Default Bot</label>
                        <select class="vi-form-input @error('default_bot') is-invalid @enderror" name="default_bot">
                            <option value="">-- Select a Bot --</option>
                            @foreach($bots as $bot)
                                <option value="{{ $bot->id }}" {{ old('default_bot', $settings->default_bot) == $bot->id ? 'selected' : '' }}>
                                    {{ $bot->name }} (v{{ $bot->version ?? '1.0' }})
                                </option>
                            @endforeach
                        </select>
                        @error('default_bot')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Theme Color</label>
                        <input type="color" class="vi-form-input @error('theme_color') is-invalid @enderror" 
                               name="theme_color" value="{{ old('theme_color', $settings->theme_color) }}" 
                               style="padding: 6px 8px; height: 40px;">
                        @error('theme_color')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Current color: {{ old('theme_color', $settings->theme_color) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECURITY TAB -->
        <div id="security-tab" class="tab-content">
            <div class="vi-panel">
                <div class="vi-panel-title"><i class="fa fa-lock"></i> Access Control</div>
                <div class="vi-form-row">
                    <div class="vi-form-group">
                        <label class="vi-form-label">Max Accounts per User <span class="required">*</span></label>
                        <input type="number" class="vi-form-input @error('max_accounts') is-invalid @enderror" 
                               name="max_accounts" value="{{ old('max_accounts', $settings->max_accounts) }}" 
                               min="1" max="1000">
                        @error('max_accounts')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Max API Keys per User <span class="required">*</span></label>
                        <input type="number" class="vi-form-input @error('max_api_keys') is-invalid @enderror" 
                               name="max_api_keys" value="{{ old('max_api_keys', $settings->max_api_keys) }}" 
                               min="1" max="100">
                        @error('max_api_keys')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Session Timeout (minutes) <span class="required">*</span></label>
                        <input type="number" class="vi-form-input @error('session_timeout') is-invalid @enderror" 
                               name="session_timeout" value="{{ old('session_timeout', $settings->session_timeout) }}" 
                               min="5" max="1440">
                        @error('session_timeout')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Auto-logout inactive sessions after this time</div>
                    </div>

                    <div class="vi-form-group" style="grid-column: 1 / -1;">
                        <label class="vi-form-label">Notification Email</label>
                        <input type="email" class="vi-form-input @error('notification_email') is-invalid @enderror" 
                               name="notification_email" value="{{ old('notification_email', $settings->notification_email) }}" 
                               placeholder="admin@example.com">
                        @error('notification_email')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Email for system alerts and notifications</div>
                    </div>
                </div>

                <div class="vi-panel-title" style="margin-top: 24px;"><i class="fa fa-shield"></i> Security Features</div>
                <div class="vi-toggle-grid">
                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="enable_2fa" name="enable_2fa" class="vi-toggle-switch" 
                                   {{ old('enable_2fa', $settings->enable_2fa) ? 'checked' : '' }} 
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="enable_2fa">
                                    <i class="fa fa-shield" style="color: #1ABB9C;"></i> Two-Factor Authentication
                                </label>
                                <div class="vi-toggle-desc">Require 2FA for all admin accounts</div>
                            </div>
                        </div>
                    </div>

                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" class="vi-toggle-switch" 
                                   {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="maintenance_mode">
                                    <i class="fa fa-wrench" style="color: #FB923C;"></i> Maintenance Mode
                                </label>
                                <div class="vi-toggle-desc">Disable user access while maintaining admin access</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRADING TAB -->
        <div id="trading-tab" class="tab-content">
            <div class="vi-panel">
                <div class="vi-panel-title"><i class="fa fa-chart-line"></i> Trading Features</div>
                <div class="vi-toggle-grid">
                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="enable_trading" name="enable_trading" class="vi-toggle-switch" 
                                   {{ old('enable_trading', $settings->enable_trading) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="enable_trading">
                                    <i class="fa fa-exchange" style="color: #3B9EFF;"></i> Enable Trading
                                </label>
                                <div class="vi-toggle-desc">Allow users to execute trades</div>
                            </div>
                        </div>
                    </div>

                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="enable_withdrawals" name="enable_withdrawals" class="vi-toggle-switch" 
                                   {{ old('enable_withdrawals', $settings->enable_withdrawals) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="enable_withdrawals">
                                    <i class="fa fa-money" style="color: #22C55E;"></i> Enable Withdrawals
                                </label>
                                <div class="vi-toggle-desc">Allow users to withdraw funds</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="vi-panel-title" style="margin-top: 24px;"><i class="fa fa-clock"></i> Trading Hours</div>
                <div class="vi-form-row">
                    <div class="vi-form-group">
                        <label class="vi-form-label">Trading Hours Start</label>
                        <input type="time" class="vi-form-input @error('trading_hours_start') is-invalid @enderror" 
                               name="trading_hours_start" value="{{ old('trading_hours_start', $settings->trading_hours_start) }}">
                        @error('trading_hours_start')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Trading Hours End</label>
                        <input type="time" class="vi-form-input @error('trading_hours_end') is-invalid @enderror" 
                               name="trading_hours_end" value="{{ old('trading_hours_end', $settings->trading_hours_end) }}">
                        @error('trading_hours_end')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="vi-panel-title" style="margin-top: 24px;"><i class="fa fa-sliders"></i> Trade Limits</div>
                <div class="vi-form-row">
                    <div class="vi-form-group">
                        <label class="vi-form-label">Max Daily Trades</label>
                        <input type="number" class="vi-form-input @error('max_daily_trades') is-invalid @enderror" 
                               name="max_daily_trades" value="{{ old('max_daily_trades', $settings->max_daily_trades) }}" 
                               min="1" placeholder="No limit">
                        @error('max_daily_trades')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Leave empty for no limit</div>
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Minimum Trade Size</label>
                        <input type="number" step="0.01" class="vi-form-input @error('min_trade_size') is-invalid @enderror" 
                               name="min_trade_size" value="{{ old('min_trade_size', $settings->min_trade_size) }}" 
                               min="0.01">
                        @error('min_trade_size')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group">
                        <label class="vi-form-label">Maximum Trade Size</label>
                        <input type="number" step="0.01" class="vi-form-input @error('max_trade_size') is-invalid @enderror" 
                               name="max_trade_size" value="{{ old('max_trade_size', $settings->max_trade_size) }}" 
                               min="0.01">
                        @error('max_trade_size')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="vi-form-group" style="grid-column: 1 / -1;">
                        <label class="vi-form-label">Daily Loss Limit</label>
                        <input type="number" step="0.01" class="vi-form-input @error('max_loss_limit') is-invalid @enderror" 
                               name="max_loss_limit" value="{{ old('max_loss_limit', $settings->max_loss_limit) }}" 
                               min="0" placeholder="No limit">
                        @error('max_loss_limit')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Maximum daily loss allowed (leave empty for no limit)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTIFICATIONS TAB -->
        <div id="notifications-tab" class="tab-content">
            <div class="vi-panel">
                <div class="vi-panel-title"><i class="fa fa-bell"></i> Notification Preferences</div>
                <div style="background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <div style="font-size: 12px; color: #94a3b8; line-height: 1.6;">
                        <i class="fa fa-info-circle" style="color: #1ABB9C;"></i> Configure how you receive system notifications and alerts
                    </div>
                </div>
                <div class="vi-toggle-grid">
                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="email_alerts" name="email_alerts" class="vi-toggle-switch" 
                                   {{ old('email_alerts', $settings->email_alerts) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="email_alerts">
                                    <i class="fa fa-envelope" style="color: #3B9EFF;"></i> Email Notifications
                                </label>
                                <div class="vi-toggle-desc">Receive alerts via email</div>
                            </div>
                        </div>
                    </div>

                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="sms_alerts" name="sms_alerts" class="vi-toggle-switch" 
                                   {{ old('sms_alerts', $settings->sms_alerts) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="sms_alerts">
                                    <i class="fa fa-mobile" style="color: #FB923C;"></i> SMS Notifications
                                </label>
                                <div class="vi-toggle-desc">Receive SMS alerts for critical events</div>
                            </div>
                        </div>
                    </div>

                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="in_app_alerts" name="in_app_alerts" class="vi-toggle-switch" 
                                   {{ old('in_app_alerts', $settings->in_app_alerts) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="in_app_alerts">
                                    <i class="fa fa-bell" style="color: #A78BFA;"></i> In-App Notifications
                                </label>
                                <div class="vi-toggle-desc">Show notifications in dashboard</div>
                            </div>
                        </div>
                    </div>

                    <div class="vi-toggle-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="daily_summary" name="daily_summary" class="vi-toggle-switch" 
                                   {{ old('daily_summary', $settings->daily_summary) ? 'checked' : '' }}
                                   style="width: 44px; height: 24px; cursor: pointer;">
                            <div>
                                <label class="vi-toggle-label" for="daily_summary">
                                    <i class="fa fa-calendar" style="color: #22C55E;"></i> Daily Summary
                                </label>
                                <div class="vi-toggle-desc">Receive daily summary email</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="vi-buttons" style="margin-top: 28px;">
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-save" style="font-size: 12px;"></i> Save All Settings
            </button>
            <button type="reset" class="vi-btn vi-btn-secondary">
                <i class="fa fa-undo" style="font-size: 12px;"></i> Reset Form
            </button>
        </div>
    </form>
</div>

<style>
.vi-toggle-switch { 
    accent-color: #1ABB9C; 
    cursor: pointer;
}

.vi-toggle-switch:checked { 
    background-color: #1ABB9C; 
}

.required {
    color: #ef4444;
    margin-left: 2px;
}

/* Responsive grid */
@media (max-width: 768px) {
    .vi-form-row {
        grid-template-columns: 1fr !important;
    }
    
    .vi-toggle-grid {
        grid-template-columns: 1fr !important;
    }
    
    .vi-buttons {
        flex-direction: column;
    }
    
    .vi-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function switchTab(tabName) {
    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.vi-tab');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>

@endsection
