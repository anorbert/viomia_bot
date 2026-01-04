@extends('layouts.admin')

@section('content')

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <strong>Success:</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show">
        <strong>Fix the following issues:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
@endif


<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-plus-circle"></i> Add Trading Account</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <form method="POST" action="{{ route('admin.accounts.store') }}">
            @csrf

            <div class="row">

                <!-- CLIENT -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Client *</strong></label>
                        <select class="form-control @error('client_id') is-invalid @enderror" name="client_id">
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} — {{ $client->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- ACCOUNT NUMBER -->
                <div class="col-md-6">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Account Number *</strong></label>
                            <input type="text"
                                class="form-control @error('account_number') is-invalid @enderror"
                                name="account_number"
                                value="{{ old('account_number') }}">
                            @error('account_number')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label><strong>Account Type *</strong></label>
                            <select class="form-control @error('account_type') is-invalid @enderror" name="account_type">
                                <option value="">-- Select Client --</option>
                                <option value="Demo">-- Demo --</option>
                                <option value="Real">-- Real --</option>
                            </select>
                            @error('account_type')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- PLATFORM -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Platform *</strong></label>
                        <select class="form-control" name="platform">
                            <option value="mt4" {{ old('platform') == 'mt4' ? 'selected' : '' }}>MetaTrader 4</option>
                            <option value="mt5" {{ old('platform') == 'mt5' ? 'selected' : '' }}>MetaTrader 5</option>
                        </select>
                    </div>
                </div>

                <!-- SERVER -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Broker Server *</strong></label>
                        <input type="text"
                               class="form-control @error('broker_server') is-invalid @enderror"
                               name="broker_server"
                               value="{{ old('broker_server') }}">
                        @error('broker_server')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>API / Investor Password *</strong></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password">
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">This will be encrypted automatically.</small>
                    </div>
                </div>

            </div>

            <hr>

            <button class="btn btn-success btn-lg">
                <i class="fa fa-check"></i> Save Account
            </button>

            <a href="{{ route('admin.accounts.index') }}" class="btn btn-default btn-lg">
                <i class="fa fa-arrow-left"></i> Back
            </a>

        </form>

    </div>
</div>

@endsection
