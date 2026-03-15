@extends('layouts.admin')

@section('title', 'Edit Payment Processor: ' . $bank->payment_owner . ' — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; padding: 24px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; margin-bottom: 16px !important; }
.vi-form-group { margin-bottom: 18px; }
.vi-form-group label { display: block; font-size: 11px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8 !important; margin-bottom: 8px; }
.vi-form-control { width: 100% !important; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9 !important; padding: 10px 12px !important; border-radius: 6px !important; font-size: 12px !important; transition: all 0.2s; }
.vi-form-control:focus { border-color: #1ABB9C !important; background-color: rgba(26,187,156,0.05) !important; outline: none; }
.vi-form-control::placeholder { color: #4b5563; }
.vi-btn { padding: 10px 20px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-error { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; }
.vi-error ul { margin: 8px 0 0 0; padding-left: 20px; }
.vi-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 768px) { .vi-form-row { grid-template-columns: 1fr; } }
.vi-section { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-section:last-child { border-bottom: none; }
.vi-section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.logo-current { background: rgba(26,187,156,0.1); border: 1px solid rgba(26,187,156,0.2); border-radius: 8px; padding: 10px; margin-top: 8px; }
.logo-current img { border-radius: 6px; }
.vi-form-help { font-size: 11px; color: #4b5563; margin-top: 4px; }
.success-message { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏦 Payment Systems</div>
        <div class="vi-header-title">Edit Processor: {{ $bank->payment_owner }}</div>
    </div>
    <a href="{{ route('admin.banks.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto;">
        <i class="fa fa-chevron-left"></i> Back
    </a>
</div>

@if($errors->any())
<div class="vi-error">
    <i class="fa fa-exclamation-circle"></i> <strong>Validation errors:</strong>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="success-message">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="vi-error">
    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<form action="{{ route('admin.banks.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="vi-panel">
        <div class="vi-section-title">
            <i class="fa fa-info-circle"></i> Basic Information
        </div>

        <div class="vi-form-row">
            <div class="vi-form-group">
                <label>Processor Name</label>
                <input type="text" class="vi-form-control" name="name" value="{{ old('name', $bank->payment_owner) }}" placeholder="e.g., MTN MoMo">
            </div>

            <div class="vi-form-group">
                <label>Support Phone</label>
                <input type="text" class="vi-form-control" name="phone" value="{{ old('phone', $bank->phone_number) }}" placeholder="078xxxxxxx">
            </div>
        </div>

        <div class="vi-form-group">
            <label>Charges (%)</label>
            <input type="number" step="0.01" class="vi-form-control" name="charges" value="{{ old('charges', $bank->charges) }}" placeholder="0.00">
            <div class="vi-form-help">Transaction fee percentage</div>
        </div>
    </div>

    <div class="vi-panel">
        <div class="vi-section-title">
            <i class="fa fa-image"></i> Logo
        </div>

        @if($bank->logo)
        <div class="logo-current">
            <div style="font-size: 11px; color: #94a3b8; margin-bottom: 8px; font-weight: 600;"><i class="fa fa-check-circle"></i> Current Logo</div>
            <img src="{{ asset('storage/'.$bank->logo) }}" width="100" alt="{{ $bank->payment_owner }}" style="border-radius: 6px;">
        </div>
        @endif

        <div class="vi-form-group" style="margin-top: 16px;">
            <label>Replace Logo (optional)</label>
            <input type="file" class="vi-form-control" name="logo" accept="image/*">
            <div class="vi-form-help">Recommended: 200x200px, PNG or JPG. Leave empty to keep current.</div>
        </div>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button type="submit" class="vi-btn vi-btn-primary">
            <i class="fa fa-save"></i> Save Changes
        </button>
        <a href="{{ route('admin.banks.index') }}" class="vi-btn vi-btn-secondary">
            <i class="fa fa-times"></i> Cancel
        </a>
    </div>

</form>

@endsection
