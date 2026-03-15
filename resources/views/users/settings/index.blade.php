@extends('layouts.user')

@section('title', 'Settings')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            {{-- Settings Navigation --}}
            <div class="settings-sidebar card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('user.settings.account') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('user.settings.account') ? 'active' : '' }}">
                            <i class="fa fa-user me-2"></i> Account
                        </a>
                        <a href="{{ route('user.settings.security') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('user.settings.security') ? 'active' : '' }}">
                            <i class="fa fa-lock me-2"></i> Security
                        </a>
                        <a href="{{ route('user.settings.notifications') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('user.settings.notifications') ? 'active' : '' }}">
                            <i class="fa fa-bell me-2"></i> Notifications
                        </a>
                        <a href="{{ route('user.settings.preferences') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('user.settings.preferences') ? 'active' : '' }}">
                            <i class="fa fa-sliders me-2"></i> Preferences
                        </a>
                        <a href="{{ route('user.settings.data-privacy') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('user.settings.data-privacy') ? 'active' : '' }}">
                            <i class="fa fa-shield me-2"></i> Data & Privacy
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            {{-- Account Settings --}}
            <div class="settings-card card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fa fa-user text-primary me-2"></i>Account Settings
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Manage your account information and profile details</p>
                    <a href="{{ route('user.settings.account') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-arrow-right me-1"></i> Manage Account
                    </a>
                </div>
            </div>

            {{-- Security Settings --}}
            <div class="settings-card card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fa fa-lock text-danger me-2"></i>Security
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Manage your password, sessions, and security preferences</p>
                    <a href="{{ route('user.settings.security') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-arrow-right me-1"></i> Manage Security
                    </a>
                </div>
            </div>

            {{-- Notification Settings --}}
            <div class="settings-card card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fa fa-bell text-warning me-2"></i>Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Control how and when you receive notifications</p>
                    <a href="{{ route('user.settings.notifications') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fa fa-arrow-right me-1"></i> Manage Notifications
                    </a>
                </div>
            </div>

            {{-- Preferences --}}
            <div class="settings-card card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fa fa-sliders text-info me-2"></i>Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Customize your experience and platform preferences</p>
                    <a href="{{ route('user.settings.preferences') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-arrow-right me-1"></i> Manage Preferences
                    </a>
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

    .settings-card {
        transition: all 0.2s;
    }

    .settings-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
    }
</style>
@endsection
