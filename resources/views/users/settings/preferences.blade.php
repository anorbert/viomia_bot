@extends('layouts.user')

@section('title', 'Preferences')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            {{-- Settings Navigation --}}
            <div class="settings-sidebar card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('user.settings.account') }}" 
                           class="list-group-item list-group-item-action">
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
                           class="list-group-item list-group-item-action active">
                            <i class="fa fa-sliders me-2"></i> Preferences
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">
                        <i class="fa fa-sliders text-info me-2"></i>Preferences
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

                    <form action="{{ route('user.settings.update-preferences') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Display Preferences --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-eye me-2"></i>Display Preferences
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Theme</label>
                                    <select name="theme" class="form-select">
                                        <option value="light" {{ $settings->theme === 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ $settings->theme === 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="auto" {{ $settings->theme === 'auto' ? 'selected' : '' }}>Auto (System)</option>
                                    </select>
                                    <small class="form-text text-muted">Choose your preferred color scheme</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Language</label>
                                    <select name="language" class="form-select">
                                        <option value="en" {{ $settings->language === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fr" {{ $settings->language === 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="es" {{ $settings->language === 'es' ? 'selected' : '' }}>Español</option>
                                        <option value="de" {{ $settings->language === 'de' ? 'selected' : '' }}>Deutsch</option>
                                    </select>
                                    <small class="form-text text-muted">Select your preferred language</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Privacy Settings --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-shield me-2"></i>Privacy
                            </h5>

                            <div class="card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <label class="form-label">Profile Visibility</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="profile_visibility" 
                                           id="visPublic" value="public" {{ $settings->profile_visibility === 'public' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visPublic">
                                        <strong>Public</strong>
                                        <br>
                                        <small class="text-muted">Your profile can be viewed by anyone</small>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" name="profile_visibility" 
                                           id="visPrivate" value="private" {{ $settings->profile_visibility === 'private' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visPrivate">
                                        <strong>Private</strong>
                                        <br>
                                        <small class="text-muted">Only you can view your profile</small>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" name="profile_visibility" 
                                           id="visFriends" value="friends" {{ $settings->profile_visibility === 'friends' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visFriends">
                                        <strong>Friends Only</strong>
                                        <br>
                                        <small class="text-muted">Only your friends can view your profile</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Security Preferences --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-lock me-2"></i>Security Features
                            </h5>

                            <div class="card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="two_factor_enabled" 
                                           id="twoFactorEnabled" {{ $settings->two_factor_enabled ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="twoFactorEnabled">
                                        <strong>Two-Factor Authentication</strong>
                                        <br>
                                        <small class="text-muted">Coming soon - Add an extra layer of security to your account</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Trading Preferences --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-line-chart me-2"></i>Trading Preferences
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Default Chart Type</label>
                                    <select name="chart_type" class="form-select">
                                        <option value="candlestick" {{ $settings->chart_type === 'candlestick' ? 'selected' : '' }}>Candlestick</option>
                                        <option value="line" {{ $settings->chart_type === 'line' ? 'selected' : '' }}>Line Chart</option>
                                        <option value="bar" {{ $settings->chart_type === 'bar' ? 'selected' : '' }}>Bar Chart</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Default Time Frame</label>
                                    <select name="timeframe" class="form-select">
                                        <option value="1m" {{ $settings->timeframe === '1m' ? 'selected' : '' }}>1 Minute</option>
                                        <option value="5m" {{ $settings->timeframe === '5m' ? 'selected' : '' }}>5 Minutes</option>
                                        <option value="15m" {{ $settings->timeframe === '15m' ? 'selected' : '' }}>15 Minutes</option>
                                        <option value="1h" {{ $settings->timeframe === '1h' ? 'selected' : '' }}>1 Hour</option>
                                        <option value="4h" {{ $settings->timeframe === '4h' ? 'selected' : '' }}>4 Hours</option>
                                        <option value="1d" {{ $settings->timeframe === '1d' ? 'selected' : '' }}>1 Day</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Email Preferences --}}
                        <div class="mb-4">
                            <h5 class="mb-4">
                                <i class="fa fa-envelope me-2"></i>Email Preferences
                            </h5>

                            <div class="card border-0 p-3" style="background: #f8fafc;">
                                <div class="form-check">
                                    <input type="hidden" name="email_marketing" value="0">
                                    <input class="form-check-input" type="checkbox" name="email_marketing" value="1"
                                           id="emailMarketing" {{ $settings->email_marketing ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailMarketing">
                                        <strong>Marketing Emails</strong>
                                        <br>
                                        <small class="text-muted">Receive promotional content and special offers</small>
                                    </label>
                                </div>
                                <hr>
                                <div class="form-check">
                                    <input type="hidden" name="email_newsletter" value="0">
                                    <input class="form-check-input" type="checkbox" name="email_newsletter" value="1"
                                           id="emailNewsletter" {{ $settings->email_newsletter ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailNewsletter">
                                        <strong>Newsletter</strong>
                                        <br>
                                        <small class="text-muted">Weekly market insights and analysis</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-4">
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-save me-1"></i> Save Preferences
                            </button>
                            <a href="{{ route('user.settings.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Data & Privacy Section --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fa fa-database me-2"></i>Data & Privacy
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Download Your Data</h6>
                            <p class="small text-muted mb-3">Get a copy of all your personal data</p>
                            <button type="button" class="btn btn-outline-info btn-sm">
                                <i class="fa fa-download me-1"></i> Export Data
                            </button>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6>Delete Account</h6>
                            <p class="small text-muted mb-3">Permanently delete your account and all data</p>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="if(confirm('This action cannot be undone. Are you sure?')) { alert('Contact support to delete your account'); }">
                                <i class="fa fa-trash me-1"></i> Delete Account
                            </button>
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
