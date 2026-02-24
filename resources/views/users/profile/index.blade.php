@extends('layouts.general')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 40px 20px;">
    <div class="container" style="max-width: 1200px;">
        <!-- Back Button -->
        <a href="javascript:history.back()" class="btn btn-sm btn-light mb-4 shadow-sm" style="border-radius: 20px;">
            <i class="fa fa-arrow-left mr-2"></i> Back
        </a>

        <div class="row">
            <!-- Main Profile Card -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 15px;">
                    <!-- Gradient Header -->
                    <div style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative;">
                        <!-- Role Badge -->
                        <div style="position: absolute; top: 15px; right: 20px;">
                            <span class="badge badge-pill" style="background: rgba(255,255,255,0.9); color: #764ba2; font-weight: 600; padding: 8px 16px; font-size: 11px;">
                                <i class="fa fa-shield mr-1"></i> 
                                @if(Auth::user()->role)
                                    {{ strtoupper(Auth::user()->role->name) }}
                                @else
                                    USER
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="card-body" style="padding-top: 0;">
                        <!-- Profile Photo -->
                        <div style="text-align: center; margin-top: -70px; position: relative; z-index: 10; margin-bottom: 20px;">
                            <div style="position: relative; width: fit-content; margin: 0 auto;">
                                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                                     alt="Profile Photo" 
                                     class="rounded-circle border-white shadow-lg"
                                     style="width: 140px; height: 140px; object-fit: cover; border: 5px solid white;">
                                
                                <!-- Status Indicator -->
                                <span class="badge badge-success" style="position: absolute; bottom: 10px; right: 10px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border: 3px solid white; border-radius: 50%;">
                                    <i class="fa fa-check" style="font-size: 16px;"></i>
                                </span>
                            </div>
                        </div>

                        <!-- User Name & Contact -->
                        <div style="text-align: center; margin-bottom: 30px;">
                            <h2 class="font-weight-bold mb-2" style="color: #2c3e50; font-size: 28px;">{{ Auth::user()->name }}</h2>
                            
                            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-bottom: 15px;">
                                <a href="mailto:{{ Auth::user()->email }}" class="text-decoration-none" style="color: #667eea; font-size: 14px;">
                                    <i class="fa fa-envelope mr-1"></i>{{ Auth::user()->email }}
                                </a>
                                @if(Auth::user()->phone_number)
                                    <a href="tel:{{ Auth::user()->phone_number }}" class="text-decoration-none" style="color: #667eea; font-size: 14px;">
                                        <i class="fa fa-phone mr-1"></i>{{ Auth::user()->phone_number }}
                                    </a>
                                @endif
                            </div>

                            <!-- Status Badges -->
                            <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                                    <i class="fa fa-check-circle mr-1"></i> Active Account
                                </span>
                                @if(Auth::user()->email_verified_at)
                                    <span class="badge badge-primary" style="padding: 6px 12px; font-size: 11px;">
                                        <i class="fa fa-shield mr-1"></i> Verified Email
                                    </span>
                                @else
                                    <span class="badge badge-warning" style="padding: 6px 12px; font-size: 11px;">
                                        <i class="fa fa-exclamation-circle mr-1"></i> Verify Email
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr style="border-top: 2px solid #e5e5e5; margin: 30px 0;">

                        <!-- Profile Information Grid -->
                        <div class="row" style="margin-bottom: 30px;">
                            <!-- Account Status -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #667eea;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">Account Status</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        <i class="fa fa-check-circle text-success mr-2"></i>Active & Verified
                                    </p>
                                </div>
                            </div>

                            <!-- Member Since -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #28a745;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">Member Since</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        <i class="fa fa-calendar text-success mr-2"></i>{{ Auth::user()->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #ffc107;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">Last Updated</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        <i class="fa fa-clock-o text-warning mr-2"></i>{{ Auth::user()->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <!-- User Role -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #17a2b8;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">User Role</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        <i class="fa fa-user-shield text-info mr-2"></i>
                                        @if(Auth::user()->role)
                                            {{ ucfirst(Auth::user()->role->name) }}
                                        @else
                                            <span class="text-muted">Standard User</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Subscription Status -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #fd7e14;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">Subscription</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        @php
                                            $subscription = Auth::user()->currentSubscription;
                                        @endphp
                                        @if($subscription)
                                            <i class="fa fa-star text-warning mr-2"></i>{{ ucfirst($subscription->plan->name ?? 'Premium') }}
                                        @else
                                            <i class="fa fa-info-circle text-secondary mr-2"></i><span class="text-muted">No Active Plan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Account Type -->
                            <div class="col-md-6 mb-3">
                                <div class="profile-info-card p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); border-radius: 12px; border-left: 4px solid #6f42c1;">
                                    <p class="text-muted small mb-1" style="font-weight: 500;">Account Type</p>
                                    <p class="font-weight-bold mb-0" style="font-size: 16px; color: #2c3e50;">
                                        <i class="fa fa-briefcase text-danger mr-2"></i>{{ Auth::user()->role_id ? 'Professional' : 'Standard' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr style="border-top: 2px solid #e5e5e5; margin: 30px 0;">

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <a href="{{ route('user.profile.edit', Auth::user()->id) }}" 
                                   class="btn btn-primary btn-block" 
                                   style="border-radius: 10px; padding: 12px; font-weight: 600; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fa fa-edit mr-2"></i> Edit Profile
                                </a>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <a href="{{ route('user.profile.change-password', Auth::user()->id) }}" 
                                   class="btn btn-outline-primary btn-block" 
                                   style="border-radius: 10px; padding: 12px; font-weight: 600; border: 2px solid #667eea;">
                                    <i class="fa fa-lock mr-2"></i> Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Role & Account Info -->
            <div class="col-lg-4">
                <!-- Role Information Card -->
                <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); padding: 20px; color: white;">
                        <h5 class="font-weight-bold mb-0">
                            <i class="fa fa-shield mr-2"></i> Role Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->role)
                            <div class="mb-3">
                                <small class="text-muted">Role Name</small>
                                <p class="font-weight-bold" style="color: #2c3e50;">{{ ucfirst(Auth::user()->role->name) }}</p>
                            </div>
                            @if(Auth::user()->role->description)
                                <div class="mb-3">
                                    <small class="text-muted">Description</small>
                                    <p style="color: #555; font-size: 14px;">{{ Auth::user()->role->description }}</p>
                                </div>
                            @endif
                            <div>
                                <small class="text-muted">Status</small>
                                <p class="font-weight-bold mb-0">
                                    @if(Auth::user()->role->active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <p class="text-muted">
                                <i class="fa fa-info-circle mr-2"></i> No role assigned
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Account Statistics -->
                <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); padding: 20px; color: white;">
                        <h5 class="font-weight-bold mb-0">
                            <i class="fa fa-chart-bar mr-2"></i> Account Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <small class="text-muted">Trading Accounts</small>
                                <span class="badge badge-primary">{{ Auth::user()->accounts->count() }}</span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min((Auth::user()->accounts->count() / 10) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <small class="text-muted">Subscriptions</small>
                                <span class="badge badge-success">{{ Auth::user()->subscriptions->count() }}</span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min((Auth::user()->subscriptions->count() / 5) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-2">Member Status</small>
                            <div style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%); padding: 12px; border-radius: 8px; text-align: center;">
                                <p class="mb-0" style="color: #667eea; font-weight: 600; font-size: 14px;">
                                    <i class="fa fa-check-circle mr-1"></i> Good Standing
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #fd7e14 0%, #dc6912 100%); padding: 20px; color: white;">
                        <h5 class="font-weight-bold mb-0">
                            <i class="fa fa-lightning mr-2"></i> Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-block btn-outline-secondary mb-2" style="border-radius: 8px;">
                            <i class="fa fa-tachometer mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('user.subscriptions.index') }}" class="btn btn-sm btn-block btn-outline-secondary mb-2" style="border-radius: 8px;">
                            <i class="fa fa-star mr-2"></i> Subscriptions
                        </a>
                        <a href="{{ route('help') }}" class="btn btn-sm btn-block btn-outline-secondary" style="border-radius: 8px;">
                            <i class="fa fa-question-circle mr-2"></i> Help Center
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-info-card {
        transition: all 0.3s ease;
        cursor: default;
    }
    
    .profile-info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    a.btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }
</style>
@endsection

