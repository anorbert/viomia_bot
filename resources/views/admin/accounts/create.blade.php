@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- Nav --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-success font-weight-bold">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}" class="text-success font-weight-bold">Trading Accounts</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Add New</li>
        </ol>
    </nav>

    {{-- Bolder Compact Alerts --}}
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
            <h5 class="font-weight-bold text-dark mb-0">Add Trading Account</h5>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Link a new MT4/MT5 terminal to the dashboard.</p>
        </div>
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-light border text-muted px-3 font-weight-bold shadow-sm">
            <i class="fa fa-chevron-left mr-1"></i> Back
        </a>
    </div>

    {{-- Bolder Green Themed Card --}}
    <div class="card shadow-sm border-0" style="border-radius: 8px; border-left: 4px solid #1a7e33 !important;">
        <div class="card-body p-3">
            <form method="POST" action="{{ route('admin.accounts.store') }}">
                @csrf

                <div class="row no-gutters mx-n2">
                    
                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Client Owner *</label>
                        <select class="form-control form-control-sm @error('client_id') is-invalid @enderror" name="client_id">
                            <option value="">Select Client...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-danger font-weight-bold" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Account Category *</label>
                        <select class="form-control form-control-sm font-weight-bold text-success" name="account_type" style="background-color: #f0fff4;">
                            <option value="Real" {{ old('account_type') == 'Real' ? 'selected' : '' }}>Real (Live)</option>
                            <option value="Demo" {{ old('account_type') == 'Demo' ? 'selected' : '' }}>Trial (Demo)</option>
                        </select>
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Login ID / Account # *</label>
                        <input type="text" name="account_number" class="form-control form-control-sm @error('account_number') is-invalid @enderror" 
                               value="{{ old('account_number') }}" placeholder="102938..." style="font-family: monospace; font-weight: bold;">
                        @error('account_number') <span class="text-danger font-weight-bold" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Trading Platform *</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-4 mt-1">
                                <input type="radio" id="mt4" name="platform" value="mt4" class="custom-control-input" {{ old('platform', 'mt4') == 'mt4' ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-info" for="mt4"><i class="fa fa-windows mr-1"></i> MT4</label>
                            </div>
                            <div class="custom-control custom-radio mt-1">
                                <input type="radio" id="mt5" name="platform" value="mt5" class="custom-control-input" {{ old('platform') == 'mt5' ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-primary" for="mt5"><i class="fa fa-windows mr-1"></i> MT5</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Broker Server *</label>
                        <input type="text" name="broker_server" class="form-control form-control-sm @error('broker_server') is-invalid @enderror" 
                               value="{{ old('broker_server') }}" placeholder="e.g. IC-Markets-Live">
                    </div>

                    <div class="col-md-4 px-2 mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Investor Password *</label>
                        <div class="input-group input-group-sm shadow-xs">
                            <input type="password" name="password" class="form-control form-control-sm" placeholder="••••••••">
                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white border-0 px-2"><i class="fa fa-lock" style="font-size: 10px;"></i></span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="border-top mt-3 pt-3 d-flex justify-content-end">
                    <button type="submit" class="btn text-white px-5 shadow border-0" 
                            style="background: linear-gradient(45deg, #0f5132, #1a7e33); font-weight: 800; font-size: 0.85rem; border-radius: 4px; letter-spacing: 0.5px;">
                        <i class="fa fa-check-circle mr-1"></i> DEPLOY ACCOUNT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control-sm { height: 32px; padding: 4px 8px; font-size: 0.85rem; border-color: #cbd5e0; }
    .form-control-sm:focus { border-color: #1a7e33; box-shadow: 0 0 0 0.2rem rgba(26, 126, 51, 0.25); background-color: #fafffa; }
    label { letter-spacing: 0.2px; margin-bottom: 2px !important; }
    .custom-control-label::before { border-color: #cbd5e0; }
    .custom-control-input:checked ~ .custom-control-label::before { border-color: #1a7e33; background-color: #1a7e33; }
    .breadcrumb-item + .breadcrumb-item::before { color: #1a7e33; font-weight: bold; }
</style>
@endsection