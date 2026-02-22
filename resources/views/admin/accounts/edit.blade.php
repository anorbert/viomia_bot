@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- Nav --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-success font-weight-bold">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}" class="text-success font-weight-bold">Trading Accounts</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Modify Account</li>
        </ol>
    </nav>

    {{-- Compact Alerts --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-3 small d-flex align-items-center text-white" style="background: #1a7e33; border-radius: 6px;">
            <i class="fa fa-check-circle mr-2"></i> 
            <span class="font-weight-bold">{{ session('success') }}</span>
            <button type="button" class="close ml-auto text-white" data-dismiss="alert" style="line-height: 1; opacity: 1;">&times;</button>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h5 class="font-weight-bold text-dark mb-0">Edit Trading Account</h5>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Updating ID: <span class="text-success font-weight-bold">{{ $account->account_number }}</span></p>
        </div>
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-light border text-muted px-3 font-weight-bold shadow-sm">
            <i class="fa fa-chevron-left mr-1"></i> Back
        </a>
    </div>

    {{-- Bolder Green Themed Card --}}
    <div class="card shadow-sm border-0" style="border-radius: 8px; border-left: 4px solid #1a7e33 !important;">
        <div class="card-body p-3">
            <form method="POST" action="{{ route('admin.accounts.update', $account) }}">
                @csrf 
                @method('PUT')

                <div class="row no-gutters mx-n2">
                    
                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Account Number *</label>
                        <input type="text" name="account_number" class="form-control form-control-sm font-weight-bold" 
                               value="{{ old('account_number', $account->account_number) }}" style="font-family: monospace;">
                        @error('account_number') <span class="text-danger font-weight-bold" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Trading Platform *</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-4 mt-1">
                                <input type="radio" id="mt4" name="platform" value="mt4" class="custom-control-input" {{ old('platform', $account->platform) == 'mt4' ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-info" for="mt4"><i class="fa fa-windows mr-1"></i> MT4</label>
                            </div>
                            <div class="custom-control custom-radio mt-1">
                                <input type="radio" id="mt5" name="platform" value="mt5" class="custom-control-input" {{ old('platform', $account->platform) == 'mt5' ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-primary" for="mt5"><i class="fa fa-windows mr-1"></i> MT5</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Operational Status *</label>
                        <select class="form-control form-control-sm font-weight-bold" name="status" style="background-color: #f8f9fa;">
                            <option value="active" {{ old('status', $account->status) == 'active' ? 'selected' : '' }}>Active (Live Sync)</option>
                            <option value="inactive" {{ old('status', $account->status) == 'inactive' ? 'selected' : '' }}>Inactive (Paused)</option>
                        </select>
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Broker Server *</label>
                        <input type="text" name="server" class="form-control form-control-sm" 
                               value="{{ old('server', $account->server) }}" placeholder="e.g. IC-Markets-Live">
                    </div>

                    <div class="col-md-8 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Investor / Master Password *</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="password" class="form-control form-control-sm" 
                                   value="{{ old('password', $account->password) }}">
                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white border-0 px-2 small"><i class="fa fa-key" style="font-size: 10px;"></i></span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="border-top mt-3 pt-3 d-flex justify-content-end">
                    <button type="submit" class="btn text-white px-5 shadow border-0" 
                            style="background: linear-gradient(45deg, #0f5132, #1a7e33); font-weight: 800; font-size: 0.85rem; border-radius: 4px; letter-spacing: 0.5px;">
                        <i class="fa fa-refresh mr-1"></i> COMMIT CHANGES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control-sm { height: 32px; padding: 4px 8px; font-size: 0.85rem; border-color: #cbd5e0; }
    .form-control-sm:focus { border-color: #1a7e33; box-shadow: 0 0 0 0.2rem rgba(26, 126, 51, 0.15); background-color: #fafffa; }
    label { letter-spacing: 0.2px; margin-bottom: 2px !important; }
    .custom-control-label::before { border-color: #cbd5e0; }
    .custom-control-input:checked ~ .custom-control-label::before { border-color: #1a7e33; background-color: #1a7e33; }
</style>
@endsection