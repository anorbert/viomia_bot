@extends('layouts.user')

@section('title', 'Security Settings')

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
                           class="list-group-item list-group-item-action active">
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">
                        <i class="fa fa-lock text-danger me-2"></i>Security Settings
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

                    {{-- Change Password Section --}}
                    <div class="mb-5">
                        <h5 class="mb-4">
                            <i class="fa fa-key me-2"></i>Change Password
                        </h5>
                        
                        <form action="{{ route('user.settings.update-password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       required minlength="8">
                                <small class="form-text text-muted">Must be at least 8 characters long</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       required minlength="8">
                            </div>

                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-refresh me-1"></i> Change Password
                            </button>
                        </form>
                    </div>

                    <hr>

                    {{-- Active Sessions --}}
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="fa fa-globe me-2"></i>Active Sessions
                            </h5>
                            <form action="{{ route('user.settings.logout-other-sessions') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure? You will be logged out from all other devices.')">
                                    <i class="fa fa-sign-out me-1"></i> Logout All Other Sessions
                                </button>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Session</th>
                                        <th>IP Address</th>
                                        <th>Last Activity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loginSessions as $session)
                                        <tr>
                                            <td>
                                                <i class="fa fa-laptop me-2 text-primary"></i>
                                                Browser Session
                                            </td>
                                            <td>
                                                <code class="text-muted">{{ request()->ip() }}</code>
                                            </td>
                                            <td>
                                                <small class="text-muted">Just now</small>
                                            </td>
                                            <td>
                                                @if(session()->getId() === $session->id)
                                                    <span class="badge bg-success">Current</span>
                                                @else
                                                    <span class="badge bg-secondary">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No active sessions found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Two-Factor Authentication --}}
                    <div class="mb-5">
                        <h5 class="mb-4">
                            <i class="fa fa-shield me-2"></i>Two-Factor Authentication
                        </h5>
                        
                        <div class="alert alert-info">
                            <strong>Coming Soon:</strong> Two-factor authentication will be available in the next update.
                        </div>
                    </div>

                    {{-- Login Activity --}}
                    <hr>

                    <div>
                        <h5 class="mb-4">
                            <i class="fa fa-history me-2"></i>Login Activity
                        </h5>
                        
                        <div class="alert alert-info">
                            <p class="mb-0">Last login: <strong>{{ Auth::user()->last_login_at ?? 'Never' }}</strong></p>
                            <p class="mb-0">Last IP: <strong>{{ Auth::user()->last_login_ip ?? 'N/A' }}</strong></p>
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
