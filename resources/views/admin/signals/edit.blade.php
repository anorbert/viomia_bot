@extends('layouts.admin')

@section('title', 'Edit Signal — ' . config('app.name'))

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
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">📈 Trading Signals</div>
        <div class="vi-header-title">Edit Signal</div>
    </div>
    <a href="{{ route('admin.signals.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
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
    <form method="POST" action="{{ route('admin.signals.update', $signal) }}">
        @csrf
        @method('PUT')

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-chart-line"></i> Signal Details</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Symbol <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="symbol" class="vi-form-control @error('symbol') is-invalid @enderror" 
                           placeholder="e.g., EURUSD" value="{{ old('symbol', $signal->symbol ?? '') }}" required>
                    @error('symbol')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Signal Type <span style="color:#EF4444;">*</span></label>
                    <select name="type" class="vi-form-control @error('type') is-invalid @enderror" required>
                        <option value="">-- Select Type --</option>
                        <option value="BUY" {{ old('type', $signal->type ?? '') == 'BUY' ? 'selected' : '' }}>🟢 BUY</option>
                        <option value="SELL" {{ old('type', $signal->type ?? '') == 'SELL' ? 'selected' : '' }}>🔴 SELL</option>
                    </select>
                    @error('type')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Entry Price <span style="color:#EF4444;">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #94a3b8; font-weight: 600;">$</span>
                        <input type="number" name="entry_price" class="vi-form-control @error('entry_price') is-invalid @enderror" 
                               placeholder="0.00000" value="{{ old('entry_price', $signal->entry_price ?? '') }}" step="0.00001" required>
                    </div>
                    @error('entry_price')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Stop Loss <span style="color:#EF4444;">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #94a3b8; font-weight: 600;">$</span>
                        <input type="number" name="stop_loss" class="vi-form-control @error('stop_loss') is-invalid @enderror" 
                               placeholder="0.00000" value="{{ old('stop_loss', $signal->stop_loss ?? '') }}" step="0.00001" required>
                    </div>
                    @error('stop_loss')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Take Profit <span style="color:#EF4444;">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #94a3b8; font-weight: 600;">$</span>
                        <input type="number" name="take_profit" class="vi-form-control @error('take_profit') is-invalid @enderror" 
                               placeholder="0.00000" value="{{ old('take_profit', $signal->take_profit ?? '') }}" step="0.00001" required>
                    </div>
                    @error('take_profit')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Risk/Reward Ratio</label>
                    <input type="number" name="risk_reward_ratio" class="vi-form-control @error('risk_reward_ratio') is-invalid @enderror" 
                           placeholder="e.g., 2.0" value="{{ old('risk_reward_ratio', $signal->risk_reward_ratio ?? '') }}" step="0.1" readonly>
                    @error('risk_reward_ratio')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="vi-section">
            <div class="vi-section-title"><i class="fa fa-info-circle"></i> Signal Information</div>

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label>Confidence Level (%)</label>
                    <input type="number" name="confidence_level" class="vi-form-control @error('confidence_level') is-invalid @enderror" 
                           placeholder="0-100" value="{{ old('confidence_level', $signal->confidence_level ?? 0) }}" min="0" max="100" step="1">
                    @error('confidence_level')<span class="vi-error">{{ $message }}</span>@enderror
                </div>

                <div class="vi-form-group">
                    <label>Status</label>
                    <select name="status" class="vi-form-control @error('status') is-invalid @enderror">
                        <option value="pending" {{ old('status', $signal->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $signal->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="executed" {{ old('status', $signal->status ?? '') == 'executed' ? 'selected' : '' }}>Executed</option>
                        <option value="cancelled" {{ old('status', $signal->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<span class="vi-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="vi-form-group">
                <label>Analysis Notes</label>
                <textarea name="analysis_notes" class="vi-form-control @error('analysis_notes') is-invalid @enderror" 
                          placeholder="Technical analysis and reasoning..." rows="4">{{ old('analysis_notes', $signal->analysis_notes ?? '') }}</textarea>
                @error('analysis_notes')<span class="vi-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.signals.index') }}" class="vi-btn vi-btn-secondary">
                <i class="fa fa-times"></i> Cancel
            </a>
            <button type="submit" class="vi-btn vi-btn-primary">
                <i class="fa fa-check-circle"></i> Update Signal
            </button>
        </div>
    </form>
</div>

<script>
    // Calculate R:R ratio automatically
    const entryInput = document.querySelector('input[name="entry_price"]');
    const slInput = document.querySelector('input[name="stop_loss"]');
    const tpInput = document.querySelector('input[name="take_profit"]');
    const rrInput = document.querySelector('input[name="risk_reward_ratio"]');

    function calculateRR() {
        const entry = parseFloat(entryInput.value) || 0;
        const sl = parseFloat(slInput.value) || 0;
        const tp = parseFloat(tpInput.value) || 0;
        
        if (entry && sl && tp) {
            const risk = Math.abs(entry - sl);
            const reward = Math.abs(tp - entry);
            const rr = risk > 0 ? (reward / risk).toFixed(2) : 0;
            rrInput.value = rr;
        }
    }

    entryInput.addEventListener('change', calculateRR);
    slInput.addEventListener('change', calculateRR);
    tpInput.addEventListener('change', calculateRR);
</script>

@endsection
