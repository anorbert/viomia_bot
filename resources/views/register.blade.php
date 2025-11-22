@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container" style="margin-top: 100px; max-width: 500px;">
    <div class="card">
        <div class="card-header text-center">
            <h3>Create an Account</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user_register.store') }}">
                @csrf

                {{-- Full Name --}}
                <div class="form-group mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="form-group mb-3">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           value="{{ old('phone_number') }}" required
                           placeholder="e.g. 078xxxxxxx">
                    @error('phone_number')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 4-digit PIN --}}
                <div class="form-group mb-3">
                    <label for="pin">PIN (4 digits)</label>
                    <input type="password" id="pin" name="pin"
                           class="form-control @error('pin') is-invalid @enderror"
                           required maxlength="4" pattern="\d{4}" title="Enter exactly 4 digits">
                    @error('pin')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm PIN --}}
                <div class="form-group mb-3">
                    <label for="pin_confirmation">Confirm PIN</label>
                    <input type="password" id="pin_confirmation" name="pin_confirmation"
                           class="form-control" required maxlength="4" pattern="\d{4}" title="Enter exactly 4 digits">
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success w-100">Register</button>

                <a class="d-block text-center mt-3" href="{{ route('login') }}">
                    Already have an account? Login here.
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
