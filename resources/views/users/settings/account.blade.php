@extends('layouts.user')

@section('title', 'Account Settings')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            {{-- Settings Navigation --}}
            <div class="settings-sidebar card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('user.settings.account') }}" 
                           class="list-group-item list-group-item-action active">
                            <i class="fa fa-user me-2"></i> Account
                        </a>
                        <a href="{{ route('user.settings.security') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-lock me-2"></i> Security
                        </a>
                        <a href="{{ route('user.settings.notifications') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-bell me-2"></i> Notifications
                        </a>
                        <a href="{{ route('user.settings.preferences') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-sliders me-2"></i> Preferences
                        </a>
                        <a href="{{ route('user.settings.data-privacy') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-shield me-2"></i> Data & Privacy
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">
                        <i class="fa fa-user text-primary me-2"></i>Account Settings
                    </h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Profile Section --}}
                    <div class="mb-5">
                        <h5 class="mb-4">Profile Information</h5>
                        
                        <form action="{{ route('user.settings.update-account') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $user->phone ?? '') }}" placeholder="+1 (555) 000-0000">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
                                           value="{{ old('country', $user->country ?? '') }}" placeholder="United States">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city', $user->city ?? '') }}" placeholder="New York">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                    <i class="fa fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <hr>

                    {{-- Account Status --}}
                    <div class="mb-4">
                        <h5 class="mb-4">Account Status</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info">
                                    <strong>Account Created:</strong><br>
                                    {{ $user->created_at->format('M d, Y \a\t h:i A') }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info">
                                    <strong>Last Updated:</strong><br>
                                    {{ $user->updated_at->format('M d, Y \a\t h:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                    <i class="fa fa-{{ $user->email_verified_at ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                                    <strong>Email Status:</strong>
                                    {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-{{ Auth::check() ? 'success' : 'warning' }}">
                                    <i class="fa fa-{{ Auth::check() ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                                    <strong>Account Status:</strong>
                                    {{ $user->deleted_at ? 'Suspended' : 'Active' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-sidebar .list-group-item {
        padding: 12px 16px;
        border: none;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .settings-sidebar .list-group-item:hover {
        background: #f8fafc;
        color: #0f172a;
        padding-left: 20px;
    }

    .settings-sidebar .list-group-item.active {
        background: #eff6ff;
        color: #2563eb;
        border-left: 3px solid #2563eb;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
    }
</style>
@endsection
