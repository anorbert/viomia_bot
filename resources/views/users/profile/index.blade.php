@extends('layouts.user')

@section('content')
<style>
    /* Profile Page Styling */
    .ln-card { 
        background: #fff; border-radius: 10px; border: 1px solid #e0e0e0; 
        margin-bottom: 25px; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.05); 
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #1ABB9C;
        box-shadow: 0 4px 12px rgba(26, 187, 156, 0.3);
        flex-shrink: 0;
    }
    
    .profile-name {
        color: #2A3F54;
        font-weight: 700;
        font-size: 18px;
        margin: 0;
    }
    
    .profile-subtext {
        color: #8a939f;
        font-size: 12px;
        margin: 2px 0 0;
    }
    
    /* Stats Cards */
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .stat-card h6 {
        font-size: 9px;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 3px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 0;
    }
    
    /* Info Grid */
    .info-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a939f;
        margin-bottom: 3px;
        display: block;
    }
    
    .info-value {
        font-size: 12px;
        color: #2A3F54;
        font-weight: 600;
        margin: 0;
    }
    
    .btn-action {
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 12px;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 187, 156, 0.2);
    }
</style>

<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
            <h3 style="font-weight: 700; color: #2A3F54; margin: 0; font-size: 20px;">
                <i class="fa fa-user text-primary mr-2"></i>My Profile
            </h3>
        </div>
        <div class="col-md-6 col-12 text-md-right mt-3 mt-md-0">
            <div class="d-flex gap-2 justify-content-md-end">
                <a href="{{ route('user.profile.edit', Auth::user()->id) }}" style="color: #1ABB9C; font-weight: 600; text-decoration: none;">
                    <i class="fa fa-edit mr-1"></i> Edit Profile
                </a>
                <a href="{{ route('user.profile.change-password', Auth::user()->id) }}" style="color: #1ABB9C; font-weight: 600; text-decoration: none;">
                    <i class="fa fa-lock mr-1"></i> Change Password
                </a>
            </div>
        </div>
    </div>

    {{-- Profile Header Card --}}
    <div class="ln-card">
        <div class="profile-header">
            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                 alt="Profile" class="profile-avatar">
            <div>
                <p class="profile-name">{{ Auth::user()->name }}</p>
                <p class="profile-subtext">
                    <i class="fa fa-envelope mr-1"></i>{{ Auth::user()->email }}
                </p>
                @if(Auth::user()->phone_number)
                    <p class="profile-subtext">
                        <i class="fa fa-phone mr-1"></i>{{ Auth::user()->phone_number }}
                    </p>
                @endif
                <div style="margin-top: 10px; display: flex; gap: 8px;">
                    @if(Auth::user()->email_verified_at)
                        <span class="badge badge-success" style="padding: 4px 10px; font-size: 10px;">
                            <i class="fa fa-check-circle mr-1"></i> Verified
                        </span>
                    @else
                        <span class="badge badge-warning" style="padding: 4px 10px; font-size: 10px;">
                            <i class="fa fa-exclamation-circle mr-1"></i> Verify Email
                        </span>
                    @endif
                    <span class="badge badge-primary" style="padding: 4px 10px; font-size: 10px;">
                        <i class="fa fa-check-circle mr-1"></i> Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); box-shadow: 0 4px 12px rgba(26, 187, 156, 0.3);">
                <h6><i class="fa fa-sun-o mr-1"></i>Member Since</h6>
                <div class="stat-value">{{ Auth::user()->created_at->format('M Y') }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);">
                <h6><i class="fa fa-briefcase mr-1"></i>Trading Accounts</h6>
                <div class="stat-value">{{ Auth::user()->accounts->count() ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                <h6><i class="fa fa-star mr-1"></i>Subscriptions</h6>
                <div class="stat-value">{{ Auth::user()->subscriptions->count() ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);">
                <h6><i class="fa fa-shield mr-1"></i>Account Status</h6>
                <div class="stat-value">Good</div>
            </div>
        </div>
    </div>

    {{-- Profile Details --}}
    <div class="row">
        <!-- Main Profile Info -->
        <div class="col-lg-8">
            <div class="ln-card">
                <div class="card-body" style="padding: 15px;">
                    <h5 style="color: #2A3F54; font-weight: 700; margin-bottom: 12px; font-size: 14px;">
                        <i class="fa fa-user-circle mr-2"></i>Account Information
                    </h5>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="info-label">Full Name</label>
                            <p class="info-value">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Email Address</label>
                            <p class="info-value">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Phone Number</label>
                            <p class="info-value">{{ Auth::user()->phone_number ?? 'Not Provided' }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Account Created</label>
                            <p class="info-value">{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Last Updated</label>
                            <p class="info-value">{{ Auth::user()->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Account Role</label>
                            <p class="info-value">
                                @if(Auth::user()->role)
                                    <span class="badge" style="background: #1ABB9C; color: #fff; padding: 4px 10px; font-size: 10px;">
                                        {{ ucfirst(Auth::user()->role->name) }}
                                    </span>
                                @else
                                    <span style="color: #8a939f;">Standard User</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr style="border-top: 1px solid #e0e0e0; margin: 12px 0;">

                    <h5 style="color: #2A3F54; font-weight: 700; margin-bottom: 12px; font-size: 14px;">
                        <i class="fa fa-package mr-2"></i>Subscription & Plans
                    </h5>

                    <div class="row">
                        @php $subscription = Auth::user()->currentSubscription; @endphp
                        <div class="col-sm-6 mb-3">
                            <label class="info-label">Current Plan</label>
                            <p class="info-value">
                                @if($subscription)
                                    <span class="badge" style="background: #f39c12; color: #fff; padding: 4px 10px; font-size: 10px;">
                                        {{ ucfirst($subscription->plan->name ?? 'Premium') }}
                                    </span>
                                @else
                                    <span style="color: #8a939f;">No Active Subscription</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="info-label">Email Verification</label>
                            <p class="info-value">
                                @if(Auth::user()->email_verified_at)
                                    <span class="badge badge-success" style="padding: 4px 10px; font-size: 10px;">
                                        <i class="fa fa-check-circle mr-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge badge-warning" style="padding: 4px 10px; font-size: 10px;">
                                        <i class="fa fa-exclamation-circle mr-1"></i>Pending
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="ln-card">
                <div style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); color: #fff; padding: 12px 15px; border-radius: 10px 10px 0 0;">
                    <h5 style="font-weight: 700; margin: 0; font-size: 13px;">
                        <i class="fa fa-lightning mr-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body" style="padding: 12px;">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-action btn-outline-primary w-100" style="margin-bottom: 8px; font-size: 11px; padding: 8px 12px;">
                        <i class="fa fa-tachometer mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.subscriptions.index') }}" class="btn btn-action btn-outline-primary w-100" style="margin-bottom: 8px; font-size: 11px; padding: 8px 12px;">
                        <i class="fa fa-star mr-2"></i>Subscriptions
                    </a>
                    <a href="{{ route('user.payments.index') }}" class="btn btn-action btn-outline-primary w-100" style="margin-bottom: 8px; font-size: 11px; padding: 8px 12px;">
                        <i class="fa fa-credit-card mr-2"></i>Payments
                    </a>
                    <a href="{{ route('user.accounts.index') }}" class="btn btn-action btn-outline-primary w-100" style="font-size: 11px; padding: 8px 12px;">
                        <i class="fa fa-briefcase mr-2"></i>Trading Accounts
                    </a>
                </div>
            </div>

            {{-- Account Status --}}
            <div class="ln-card">
                <div style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: #fff; padding: 12px 15px; border-radius: 10px 10px 0 0;">
                    <h5 style="font-weight: 700; margin: 0; font-size: 13px;">
                        <i class="fa fa-shield mr-2"></i>Account Status
                    </h5>
                </div>
                <div class="card-body" style="padding: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; background: #f8f9fa; border-radius: 6px; margin-bottom: 8px;">
                        <span style="font-size: 11px; color: #8a939f; font-weight: 600;">Status</span>
                        <span class="badge badge-success" style="font-size: 9px; padding: 3px 8px;">Active</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; background: #f8f9fa; border-radius: 6px; margin-bottom: 8px;">
                        <span style="font-size: 11px; color: #8a939f; font-weight: 600;">Security</span>
                        <span class="badge badge-success" style="font-size: 9px; padding: 3px 8px;">Good</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-size: 11px; color: #8a939f; font-weight: 600;">Verification</span>
                        <span class="badge {{ Auth::user()->email_verified_at ? 'badge-success' : 'badge-warning' }}" style="font-size: 9px; padding: 3px 8px;">
                            {{ Auth::user()->email_verified_at ? 'Verified' : 'Pending' }}
                        </span>
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

