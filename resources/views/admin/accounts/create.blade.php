@extends('layouts.admin')

@section('content')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 900px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input, .vi-form-select { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus, .vi-form-select:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid, .vi-form-select.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus, .vi-form-select.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-form-hint.success { color: #22C55E; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-radio-group { display: flex; gap: 20px; margin-bottom: 16px; }
.vi-radio { display: flex; align-items: center; gap: 8px; }
.vi-radio input[type=radio] { width: 16px; height: 16px; cursor: pointer; accent-color: #1ABB9C; }
.vi-radio label { cursor: pointer; font-weight: 600; color: #f1f5f9; margin: 0; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-success-alert { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px; }
.vi-error-alert { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px; }
.vi-platform-badges { display: flex; gap: 8px; margin-bottom: 16px; }
.vi-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-badge-info { background: rgba(59,158,255,0.15); color: #3B9EFF; }
.vi-badge-success { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
</style>
@endpush

<div class="vi-form-container">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏛️ Trading Accounts</div>
            <div class="vi-header-title">Add Trading Account</div>
            <div class="vi-header-sub">Link a new MT4/MT5 terminal to the dashboard for account management</div>
        </div>
        <a href="{{ route('admin.accounts.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Accounts
        </a>
    </div>

    @if(session('success'))
        <div class="vi-success-alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
    <div class="vi-error-alert">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($errors->all() as $error)
                <li style="padding: 3px 0;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Account Setup Information -->
    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-lightbulb"></i> Quick Setup Guide</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <div style="font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">1. Select Client</div>
                <div style="font-size: 12px; color: #94a3b8;">Choose the client who owns this account</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">2. Choose Platform</div>
                <div style="font-size: 12px; color: #94a3b8;">Select MT4 or MT5 trading platform</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">3. Account Details</div>
                <div style="font-size: 12px; color: #94a3b8;">Enter server, type, and credentials</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">4. Deploy</div>
                <div style="font-size: 12px; color: #94a3b8;">Verify and deploy account to system</div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="vi-panel">
        <form method="POST" action="{{ route('admin.accounts.store') }}">
            @csrf

            <!-- Client Selection -->
            <div class="vi-panel-title" style="margin-bottom: 16px;"><i class="fa fa-user"></i> Client Account Owner</div>

            <div class="vi-form-group" style="margin-bottom: 20px;">
                <label class="vi-form-label">
                    Select Client
                    <span class="required">*</span>
                </label>
                <select class="vi-form-select @error('client_id') is-invalid @enderror" name="client_id" required>
                    <option value="">-- Select a Client --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} ({{ $client->email }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Account will be linked to this client</div>
            </div>

            <!-- Trading Platform Selection -->
            <div class="vi-panel-title" style="margin-bottom: 16px;"><i class="fa fa-chart-line"></i> Trading Platform</div>

            <div class="vi-form-group" style="margin-bottom: 20px;">
                <label class="vi-form-label">
                    Platform Type
                    <span class="required">*</span>
                </label>
                <div class="vi-radio-group">
                    <div class="vi-radio">
                        <input type="radio" id="mt4" name="platform" value="mt4" {{ old('platform') == 'mt4' ? 'checked' : '' }}>
                        <label for="mt4"><i class="fa fa-windows" style="color: #1ABB9C;"></i> MT4</label>
                    </div>
                    <div class="vi-radio">
                        <input type="radio" id="mt5" name="platform" value="mt5" {{ old('platform', 'mt5') == 'mt5' ? 'checked' : '' }} required>
                        <label for="mt5"><i class="fa fa-windows" style="color: #1ABB9C;"></i> MT5 (Recommended)</label>
                    </div>
                </div>
                @error('platform')
                    <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Account Configuration -->
            <div class="vi-panel-title" style="margin-bottom: 16px;"><i class="fa fa-cogs"></i> Account Configuration</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">
                        Account Type
                        <span class="required">*</span>
                    </label>
                    <select class="vi-form-select" name="account_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Real" {{ old('account_type') == 'Real' ? 'selected' : '' }}>Real (Live Account)</option>
                        <option value="Demo" {{ old('account_type') == 'Demo' ? 'selected' : '' }}>Demo (Practice Account)</option>
                    </select>
                    @error('account_type')
                        <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">
                        Broker Server
                        <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="broker_server" 
                           class="vi-form-input @error('broker_server') is-invalid @enderror" 
                           value="{{ old('broker_server') }}" 
                           placeholder="e.g., FBS-Real or FBS-Demo"
                           required>
                    @error('broker_server')
                        <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <div class="vi-form-hint success"><i class="fa fa-check-circle"></i> Examples: FBS-Real, FBS-Demo, FBS-Real-MT5</div>
                </div>
            </div>

            <!-- Credentials Section -->
            <div class="vi-panel-title" style="margin-bottom: 16px;"><i class="fa fa-lock"></i> Account Credentials</div>

            <div class="vi-info-box">
                <div class="vi-info-box-title">Security Notice</div>
                <div class="vi-info-box-text">
                    Enter the Investor Password for this MT4/MT5 account. Do not use the master password.
                </div>
            </div>

            <div class="vi-form-group">
                <label class="vi-form-label">
                    Investor Password
                    <span class="required">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       class="vi-form-input @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       required>
                @error('password')
                    <div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Read-only password for account monitoring</div>
            </div>

            <!-- Verification Section -->
            <div class="vi-panel-title" style="margin-bottom: 16px;"><i class="fa fa-clipboard-list"></i> Verification Checklist</div>

            <div style="background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-radius: 6px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: #94a3b8; line-height: 1.8;">
                    <div style="display: flex; gap: 8px; padding: 6px 0;"><span style="color: #1ABB9C;">✓</span> Client account exists</div>
                    <div style="display: flex; gap: 8px; padding: 6px 0;"><span style="color: #1ABB9C;">✓</span> Platform selected (MT4 or MT5)</div>
                    <div style="display: flex; gap: 8px; padding: 6px 0;"><span style="color: #1ABB9C;">✓</span> Account type confirmed (Real/Demo)</div>
                    <div style="display: flex; gap: 8px; padding: 6px 0;"><span style="color: #1ABB9C;">✓</span> Broker server specified</div>
                    <div style="display: flex; gap: 8px; padding: 6px 0;"><span style="color: #1ABB9C;">✓</span> Investor password entered</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-check-circle"></i> Deploy Account
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Important Information Footer -->
    <div style="background: rgba(251,146,60,0.05); border: 1px solid rgba(251,146,60,0.15); border-radius: 8px; padding: 16px; color: #FB923C; font-size: 12px; line-height: 1.6;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-triangle"></i> Important Information</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 4px 0;">• Use Investor/Read-only password, not the master password</li>
            <li style="padding: 4px 0;">• Account must exist on the specified broker server</li>
            <li style="padding: 4px 0;">• Once deployed, account details cannot be changed (create new account instead)</li>
            <li style="padding: 4px 0;">• Verify all credentials before submitting to avoid connection errors</li>
        </ul>
    </div>
</div>

@endsection