@extends('layouts.user')

@section('title', 'Notification Settings')

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
                           class="list-group-item list-group-item-action active">
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
                        <i class="fa fa-bell text-warning me-2"></i>Notification Settings
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('user.settings.update-notifications') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Email Notifications --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-envelope me-2"></i>Email Notifications
                            </h5>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="email_trade_alerts" value="0">
                                        <input class="form-check-input" type="checkbox" name="email_trade_alerts" value="1"
                                               id="emailTradeAlerts" 
                                               {{ $notificationSettings['email_trade_alerts'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="emailTradeAlerts">
                                            <strong>Trade Alerts</strong>
                                            <br>
                                            <small class="text-muted">Receive notifications when trades are opened or closed</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="email_weekly_report" value="0">
                                        <input class="form-check-input" type="checkbox" name="email_weekly_report" value="1"
                                               id="emailWeeklyReport" 
                                               {{ $notificationSettings['email_weekly_report'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="emailWeeklyReport">
                                            <strong>Weekly Reports</strong>
                                            <br>
                                            <small class="text-muted">Get your weekly trading performance summary</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="email_payment_reminders" value="0">
                                        <input class="form-check-input" type="checkbox" name="email_payment_reminders" value="1"
                                               id="emailPaymentReminders" 
                                               {{ $notificationSettings['email_payment_reminders'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="emailPaymentReminders">
                                            <strong>Payment Reminders</strong>
                                            <br>
                                            <small class="text-muted">Be reminded about upcoming subscription and weekly payments</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="email_system_updates" value="0">
                                        <input class="form-check-input" type="checkbox" name="email_system_updates" value="1"
                                               id="emailSystemUpdates" 
                                               {{ $notificationSettings['email_system_updates'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="emailSystemUpdates">
                                            <strong>System Updates</strong>
                                            <br>
                                            <small class="text-muted">Important platform updates and maintenance notifications</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Push Notifications --}}
                        <div class="mb-5">
                            <h5 class="mb-4">
                                <i class="fa fa-mobile me-2"></i>Push Notifications
                            </h5>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="push_trade_alerts" value="0">
                                        <input class="form-check-input" type="checkbox" name="push_trade_alerts" value="1"
                                               id="pushTradeAlerts" 
                                               {{ $notificationSettings['push_trade_alerts'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="pushTradeAlerts">
                                            <strong>Trade Alerts</strong>
                                            <br>
                                            <small class="text-muted">Instant push notifications for trade executions</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="notification-item card border-0 mb-3 p-3" style="background: #f8fafc;">
                                <div class="form-check d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="hidden" name="push_payment_reminders" value="0">
                                        <input class="form-check-input" type="checkbox" name="push_payment_reminders" value="1"
                                               id="pushPaymentReminders" 
                                               {{ $notificationSettings['push_payment_reminders'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="pushPaymentReminders">
                                            <strong>Payment Reminders</strong>
                                            <br>
                                            <small class="text-muted">Timely reminders for subscription and weekly payments</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Notification Frequency --}}
                        <div class="mb-4">
                            <h5 class="mb-4">
                                <i class="fa fa-clock-o me-2"></i>Notification Frequency
                            </h5>

                            <div class="card border-0 p-3" style="background: #f8fafc;">
                                <label class="form-label">How often would you like to receive notifications?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="freqImmediate" value="immediate" {{ $notificationSettings['notification_frequency'] === 'immediate' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="freqImmediate">
                                        Immediately
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="freqDaily" value="daily" {{ $notificationSettings['notification_frequency'] === 'daily' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="freqDaily">
                                        Daily Digest
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="freqWeekly" value="weekly" {{ $notificationSettings['notification_frequency'] === 'weekly' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="freqWeekly">
                                        Weekly Digest
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-save me-1"></i> Save Preferences
                            </button>
                            <a href="{{ route('user.settings.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
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

    .notification-item {
        transition: all 0.2s;
    }

    .notification-item:hover {
        background: #ffffff !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .form-check-input:checked {
        background-color: #fbbf24;
        border-color: #fbbf24;
    }
</style>
@endsection
