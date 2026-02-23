@extends('app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <!-- Profile Card -->
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <!-- Header Background -->
                <div class="bg-gradient-primary" style="height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>

                <div class="card-body position-relative" style="padding-top: 0;">
                    <!-- Profile Photo Section -->
                    <div class="text-center" style="margin-top: -60px; position: relative; z-index: 10;">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                             alt="Profile Photo" 
                             class="rounded-circle border-4 border-white shadow-lg"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <!-- User Info -->
                    <div class="text-center mt-3">
                        <h3 class="mb-1 font-weight-bold">{{ Auth::user()->name }}</h3>
                        <p class="text-muted mb-2">
                            <i class="fa fa-envelope mr-2"></i>{{ Auth::user()->email }}
                        </p>
                        @if(Auth::user()->phone_number)
                            <p class="text-muted mb-3">
                                <i class="fa fa-phone mr-2"></i>{{ Auth::user()->phone_number }}
                            </p>
                        @endif
                        <div class="badge badge-success" style="font-size: 12px;">
                            <i class="fa fa-check-circle mr-1"></i> Active Account
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Profile Details -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="profile-info-card p-3 bg-light rounded">
                                <p class="text-muted small mb-1">Account Status</p>
                                <p class="font-weight-bold">
                                    <i class="fa fa-check-circle text-success mr-2"></i>Verified
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-card p-3 bg-light rounded">
                                <p class="text-muted small mb-1">Member Since</p>
                                <p class="font-weight-bold">
                                    <i class="fa fa-calendar mr-2"></i>{{ Auth::user()->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="profile-info-card p-3 bg-light rounded">
                                <p class="text-muted small mb-1">Last Updated</p>
                                <p class="font-weight-bold">
                                    <i class="fa fa-clock-o mr-2"></i>{{ Auth::user()->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-card p-3 bg-light rounded">
                                <p class="text-muted small mb-1">Subscription</p>
                                <p class="font-weight-bold">
                                    <i class="fa fa-star text-warning mr-2"></i>Premium
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-6">
                            <a href="{{ route('user.profile.edit', Auth::user()->id) }}" class="btn btn-primary btn-block rounded-lg">
                                <i class="fa fa-edit mr-2"></i> Edit Profile
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.profile.change-password', Auth::user()->id) }}" class="btn btn-outline-primary btn-block rounded-lg">
                                <i class="fa fa-lock mr-2"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h5 class="text-muted small">Trading Accounts</h5>
                        <p class="display-4 font-weight-bold text-primary mb-0">5</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h5 class="text-muted small">Active Trades</h5>
                        <p class="display-4 font-weight-bold text-success mb-0">12</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center p-3">
                        <h5 class="text-muted small">Total Profit</h5>
                        <p class="display-4 font-weight-bold text-info mb-0">$4.2K</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .profile-info-card {
        transition: all 0.3s ease;
    }
    
    .profile-info-card:hover {
        background-color: #e8eaf6 !important;
        transform: translateY(-2px);
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    .rounded-lg {
        border-radius: 8px;
    }
</style>
@endsection
