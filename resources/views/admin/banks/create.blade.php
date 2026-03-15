@extends('layouts.admin')

@section('title', 'Create Payment Processor — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; padding: 24px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-form-group { margin-bottom: 18px; }
.vi-form-group label { display: block; font-size: 11px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8 !important; margin-bottom: 8px; }
.vi-form-control { width: 100% !important; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9 !important; padding: 10px 12px !important; border-radius: 6px !important; font-size: 12px !important; transition: all 0.2s; }
.vi-form-control:focus { border-color: #1ABB9C !important; background-color: rgba(26,187,156,0.05) !important; outline: none; }
.vi-form-control::placeholder { color: #4b5563; }
.vi-btn { padding: 10px 20px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-error { color: #EF4444; font-size: 10px; margin-top: 4px; display: block; }
.vi-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 768px) { .vi-form-row { grid-template-columns: 1fr; } }
.vi-section { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-section:last-child { border-bottom: none; }
.vi-section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏦 Payment Systems</div>
        <div class="vi-header-title">Add Payment Processor</div>
    </div>
    <a href="{{ route('admin.banks.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-chevron-left"></i> Back
    </a>
</div>

@if($errors->any())
    <div style="background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
        <i class="fa fa-exclamation-circle"></i> <strong>Validation errors:</strong>
        <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 11px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="vi-panel">
    <form method="POST" action="{{ route('admin.banks.store') }}">
        @csrf

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-info-circle"></i> Processor Information</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Processor Name <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="vi-form-control @error('name') is-invalid @enderror" 
                           placeholder="e.g., Momo Payment, Binance, Bank Transfer" value="{{ old('name') }}" required>
                    @error('name')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Processor Code <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="code" class="vi-form-control @error('code') is-invalid @enderror" 
                           placeholder="e.g., MOMO, BINANCE, BANK" value="{{ old('code') }}" required>
                    @error('code')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Account/Reference</label>
                    <input type="text" name="account_reference" class="vi-form-control @error('account_reference') is-invalid @enderror" 
                           placeholder="Account number or merchant ID" value="{{ old('account_reference') }}">
                    @error('account_reference')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>API Key/Credentials</label>
                    <input type="password" name="api_key" class="vi-form-control @error('api_key') is-invalid @enderror" 
                           placeholder="Keep confidential">
                    @error('api_key')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-dollar-sign"></i> Financial Details</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Current Balance <span style="color:#EF4444;">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #94a3b8; font-weight: 600;">$</span>
                        <input type="number" name="balance" class="vi-form-control @error('balance') is-invalid @enderror" 
                               placeholder="0.00" value="{{ old('balance', 0) }}" step="0.01" required>
                    </div>
                    @error('balance')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Transaction Fee (%) <span style="color:#EF4444;">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" name="fee_percentage" class="vi-form-control @error('fee_percentage') is-invalid @enderror" 
                               placeholder="0.00" value="{{ old('fee_percentage', 0) }}" step="0.01" required>
                        <span style="color: #94a3b8; font-weight: 600;">%</span>
                    </div>
                    @error('fee_percentage')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-group">
                <label>Description</label>
                <textarea name="description" class="vi-form-control @error('description') is-invalid @enderror" 
                          placeholder="Additional details about this payment processor..." rows="4">{{ old('description') }}</textarea>
                @error('description')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-toggle-on"></i> Status</div>

            <div class="vi-form-group">
                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 0;">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <span style="font-size: 12px; color: #94a3b8; text-transform: none; font-weight: 600; letter-spacing: 0;">
                        Active - Allow transactions through this processor
                    </span>
                </label>
            </div>

            <div style="background-color: rgba(26,187,156,0.05); padding: 12px; border-radius: 6px; border-left: 3px solid #1ABB9C; font-size: 11px; color: #94a3b8;">
                <i class="fa fa-info-circle" style="color:#1ABB9C;"></i> 
                Only active processors will be available for customer transactions.
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.banks.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-times"></i> Cancel
            </a>
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-check-circle"></i> Add Processor
            </button>
        </div>
    </form>
</div>

@endsection
