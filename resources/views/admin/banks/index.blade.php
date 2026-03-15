@extends('layouts.admin')

@section('title', 'Payment Processors — ' . config('app.name'))

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-panel { background-color: #111827 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-radius: 12px !important; overflow: hidden; margin-bottom: 16px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.3) !important; }
.vi-panel-head { display: flex !important; align-items: center !important; gap: 10px; padding: 14px 18px !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; background-color: #1a2235 !important; }
.vi-panel-title { font-size: 11.5px !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8 !important; flex: 1; }
.vi-form-group { margin-bottom: 16px; }
.vi-form-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 6px; display: block; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 8px 10px; border-radius: 6px; font-size: 12px; transition: all 0.2s; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-help { font-size: 11px; color: #4b5563; margin-top: 4px; }
.vi-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.vi-table thead th { padding: 10px 14px; font-size: 9.5px !important; font-weight: 800 !important; letter-spacing: 1.2px; text-transform: uppercase; color: #4b5563 !important; text-align: left; background-color: #1a2235 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.07) !important; }
.vi-table tbody tr:hover { background-color: #1a2235 !important; }
.vi-table tbody td { padding: 11px 14px; color: #94a3b8 !important; vertical-align: middle; background-color: transparent !important; }
.vi-table .td-sym { color: #f1f5f9 !important; font-weight: 600 !important; font-size: 12px; }
.vi-badge { padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; }
.vi-badge-active { background-color: rgba(34,197,94,0.13) !important; color: #22C55E !important; }
.vi-badge-inactive { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; }
.vi-btn { padding: 6px 11px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; transition: all 0.15s; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(139,92,246,0.13) !important; color: #A78BFA !important; border: 1px solid rgba(139,92,246,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2) !important; }
.vi-btn-danger { background-color: rgba(239,68,68,0.13) !important; color: #EF4444 !important; border: 1px solid rgba(239,68,68,0.25) !important; }
.vi-btn-danger:hover { background-color: rgba(239,68,68,0.2) !important; }
.logo-preview { width: 55px; height: 55px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(26,187,156,0.3); }
.bank-logo-placeholder { width: 55px; height: 55px; background-color: #1a2235; border: 1px dashed rgba(26,187,156,0.3); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #4b5563; font-size: 24px; }
.success-alert { background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #22C55E; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600; }
.error-alert { background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #EF4444; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 12px; }
.error-list { list-style: none; padding: 0; margin: 0; }
.error-list li { padding: 4px 0; }
</style>
@endpush

@section('content')

<div class="vi-header">
    <div>
        <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🏦 Payments</div>
        <div class="vi-header-title">Payment Processors</div>
        <div class="vi-header-sub">Register and manage payment processors for donations</div>
    </div>
    <a href="{{ route('admin.banks.create') }}" class="vi-btn vi-btn-primary" style="margin-left:auto;">
        <i class="fa fa-plus-circle"></i> Add Processor
    </a>
</div>

@if($errors->any())
<div class="error-alert">
    <strong>Fix the following errors:</strong>
    <ul class="error-list">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="success-alert">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="error-alert">
    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 20px;">

    {{-- FORM PANEL --}}
    <div class="vi-panel">
        <div class="vi-panel-head">
            <i class="fa fa-plus" style="color:#1ABB9C; font-size:14px;"></i>
            <div class="vi-panel-title">Register New Processor</div>
        </div>

        <div style="padding: 20px;">
            <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="vi-form-group">
                    <label class="vi-form-label">Processor Name *</label>
                    <input type="text" class="vi-form-input" name="name" value="{{ old('name') }}" placeholder="e.g., MTN MoMo" required>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Logo (optional)</label>
                    <input type="file" class="vi-form-input" name="logo" accept="image/*">
                    <div class="vi-form-help">Recommended: 200x200px, PNG or JPG</div>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Support Phone</label>
                    <input type="text" class="vi-form-input" name="phone" value="{{ old('phone') }}" placeholder="078xxxxxxx">
                    <div class="vi-form-help">Customer support contact number</div>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Confirm Password *</label>
                    <input type="password" class="vi-form-input" name="password" placeholder="Your admin password" required>
                    <div class="vi-form-help">For security verification</div>
                </div>

                <button type="submit" class="vi-btn vi-btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa fa-check"></i> Register Processor
                </button>
            </form>
        </div>
    </div>

    {{-- TABLE PANEL --}}
    <div class="vi-panel">
        <div class="vi-panel-head">
            <i class="fa fa-list" style="color:#1ABB9C; font-size:14px;"></i>
            <div class="vi-panel-title">Registered Processors ({{ $banks->count() }})</div>
        </div>

        <div style="overflow-x: auto;">
            <table class="vi-table">
                <thead>
                    <tr>
                        <th style="width:8%;">Logo</th>
                        <th style="width:18%;">Processor</th>
                        <th style="width:12%;">Phone</th>
                        <th style="width:10%;">Charges</th>
                        <th style="width:10%;">Balance</th>
                        <th style="width:12%;">Status</th>
                        <th style="text-align:right; width:22%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks as $bank)
                    @php
                        $isActive = strtoupper($bank->status ?? 'INACTIVE') === 'ACTIVE';
                    @endphp
                    <tr>
                        <td>
                            @if($bank->logo)
                                <img src="{{ asset('storage/'.$bank->logo) }}" class="logo-preview" alt="{{ $bank->payment_owner }}">
                            @else
                                <div class="bank-logo-placeholder">
                                    <i class="fa fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="td-sym">{{ $bank->payment_owner }}</td>
                        <td>{{ $bank->phone_number ?? '—' }}</td>
                        <td>
                            <span style="color:#22C55E; font-weight:700;">{{ number_format((float)$bank->charges, 2) }}%</span>
                        </td>
                        <td>
                            <code style="background:rgba(26,187,156,0.1); color:#1ABB9C; padding:3px 6px; border-radius:4px;font-size:10px;">
                                ${{ number_format((float)$bank->balance, 2) }}
                            </code>
                        </td>
                        <td>
                            @if($isActive)
                                <span class="vi-badge vi-badge-active">
                                    <i class="fa fa-check-circle"></i> ACTIVE
                                </span>
                            @else
                                <span class="vi-badge vi-badge-inactive">
                                    <i class="fa fa-times-circle"></i> INACTIVE
                                </span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                <a href="{{ route('admin.banks.edit', $bank->id) }}" class="vi-btn vi-btn-secondary" title="Edit">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>

                                <form action="{{ route('admin.banks.toggle', $bank->id) }}" method="POST" style="display:inline;" 
                                      onsubmit="return confirm('{{ $isActive ? 'Deactivate' : 'Activate' }} this processor?');">
                                    @csrf
                                    <button class="vi-btn {{ $isActive ? 'vi-btn-danger' : 'vi-btn-secondary' }}" title="Toggle Status">
                                        <i class="fa {{ $isActive ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" style="display:inline;" 
                                      onsubmit="return confirm('Delete this processor? Cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="vi-btn vi-btn-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa fa-inbox" style="font-size: 32px; opacity: 0.5; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0;">No payment processors registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
