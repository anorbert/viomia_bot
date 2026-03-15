@extends('layouts.admin')

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
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-select { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; cursor: pointer; }
.vi-form-select:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-radio-group { display: flex; gap: 20px; margin-bottom: 16px; }
.vi-radio { display: flex; align-items: center; gap: 8px; }
.vi-radio input[type=radio] { width: 16px; height: 16px; cursor: pointer; accent-color: #1ABB9C; }
.vi-radio label { cursor: pointer; font-weight: 600; color: #f1f5f9; margin: 0; font-size: 12px; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-status-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-status-active { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-inactive { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏛️ Trading Accounts</div>
            <div class="vi-header-title">Edit Trading Account</div>
            <div class="vi-header-sub">Update account configuration and settings</div>
        </div>
        <a href="{{ route('admin.accounts.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Accounts
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($errors->all() as $error)
                <li style="padding: 3px 0;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-exchange"></i> Account Information</div>

        <div class="vi-info-box">
            <div class="vi-info-box-title">Account Number</div>
            <div class="vi-info-box-text">{{ $account->login }}</div>
        </div>

        <form method="POST" action="{{ route('admin.accounts.update', $account) }}">
            @csrf
            @method('PUT')

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">Account Number <span class="required">*</span></label>
                    <input type="text" name="login" class="vi-form-input @error('login') is-invalid @enderror"
                           value="{{ old('login', $account->login) }}" required>
                    @error('login')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Trading Platform <span class="required">*</span></label>
                    <div class="vi-radio-group">
                        <div class="vi-radio">
                            <input type="radio" id="mt4" name="platform" value="mt4" {{ strtolower(old('platform', $account->platform)) == 'mt4' ? 'checked' : '' }} required>
                            <label for="mt4"><i class="fa fa-windows"></i> MT4</label>
                        </div>
                        <div class="vi-radio">
                            <input type="radio" id="mt5" name="platform" value="mt5" {{ strtolower(old('platform', $account->platform)) == 'mt5' ? 'checked' : '' }} required>
                            <label for="mt5"><i class="fa fa-windows"></i> MT5</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">Broker Server <span class="required">*</span></label>
                    <input type="text" name="server" class="vi-form-input @error('server') is-invalid @enderror"
                           placeholder="e.g., FBS-Real"
                           value="{{ old('server', $account->server) }}" required>
                    @error('server')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Account Status <span class="required">*</span></label>
                    <select name="active" class="vi-form-select @error('active') is-invalid @enderror" required>
                        <option value="1" {{ old('active', $account->active) == 1 || old('active', $account->active) === true ? 'selected' : '' }}>Active (Connected)</option>
                        <option value="0" {{ old('active', $account->active) == 0 || old('active', $account->active) === false ? 'selected' : '' }}>Inactive (Disconnected)</option>
                    </select>
                    @error('active')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vi-form-group" style="grid-column: 1 / -1;">
                <label class="vi-form-label">Investor Password <span class="required">*</span></label>
                <input type="password" name="password" class="vi-form-input @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       value="{{ old('password', $account->password) }}" required>
                @error('password')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Read-only password for monitoring</div>
            </div>

            <div class="vi-info-box" style="margin-top: 20px; background: rgba(251,146,60,0.05); border-color: rgba(251,146,60,0.15); border-left-color: #FB923C;">
                <div class="vi-info-box-title" style="color: #FB923C;">Important</div>
                <div class="vi-info-box-text" style="color: #FB923C;">Ensure password is correct before saving to avoid connection errors</div>
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('.vi-form-input');
    if (firstInput) firstInput.focus();
});
</script>

@endsection