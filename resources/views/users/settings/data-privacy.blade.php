@extends('layouts.user')

@section('title', 'Data & Privacy')

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
                           class="list-group-item list-group-item-action">
                            <i class="fa fa-sliders me-2"></i> Preferences
                        </a>
                        <a href="{{ route('user.settings.data-privacy') }}" 
                           class="list-group-item list-group-item-action active">
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
                        <i class="fa fa-shield text-danger me-2"></i>Data & Privacy
                    </h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong>
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

                    {{-- Export Data Section --}}
                    <div class="mb-5">
                        <h5 class="mb-4">
                            <i class="fa fa-download me-2"></i>Download Your Data
                        </h5>

                        <div class="card border-0 p-4" style="background: #f8fafc;">
                            <p class="text-muted mb-3">
                                Get a copy of all your personal data including profile information, trading history, settings, and more. 
                                Your data will be exported as a ZIP file.
                            </p>
                            
                            <form action="{{ route('user.settings.download-data') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-download me-2"></i>Export My Data
                                </button>
                                <small class="ms-2 text-muted">This may take a minute depending on your data volume</small>
                            </form>
                        </div>
                    </div>

                    <hr class="my-5">

                    {{-- Delete Account Section --}}
                    <div class="mb-4">
                        <h5 class="mb-4">
                            <i class="fa fa-trash text-danger me-2"></i>Delete Account
                        </h5>

                        <div class="card border-0 p-4" style="background: #fef2f2; border-left: 4px solid #ef4444;">
                            <div class="alert alert-warning" role="alert">
                                <strong><i class="fa fa-exclamation-triangle me-2"></i>Warning:</strong> 
                                Deleting your account is permanent. Your account will be deactivated immediately and cannot be recovered.
                            </div>

                            <p class="text-muted mb-4">
                                When you delete your account:
                            </p>

                            <ul class="list-unstyled ms-3 mb-4">
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>Account deactivated instantly</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>Cannot be recovered</strong> - No way to reactivate or restore
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>All active trades</strong> will be closed automatically
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>Connected trading accounts</strong> will be disconnected
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>Subscriptions will be cancelled</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check text-danger me-2"></i>
                                    <strong>Future payments will not be processed</strong>
                                </li>
                            </ul>

                            <p class="text-muted mb-4">
                                <strong>Your data:</strong> While your account will be deleted, transaction records may be retained for compliance and legal requirements.
                            </p>

                            {{-- Delete Account Button with Confirmation Modal --}}
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fa fa-trash me-2"></i>Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Account Confirmation Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title">
                    <i class="fa fa-exclamation-circle me-2"></i>Delete Account - Final Confirmation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    <strong>This action cannot be undone.</strong> Once you delete your account, it will be permanently deactivated.
                </p>
                
                <p class="text-muted mb-4">
                    Please type your email address to confirm deletion:
                </p>

                <form id="deleteAccountForm" action="{{ route('user.settings.delete-account') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-lg" 
                               name="email"
                               id="confirmEmail" 
                               placeholder="Enter your email to confirm"
                               required>
                        <small class="text-muted d-block mt-2">
                            Please enter: <strong>{{ Auth::user()->email }}</strong>
                        </small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                            <label class="form-check-label" for="confirmCheck">
                                I understand that my account will be permanently deleted and cannot be recovered
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fa fa-trash me-2"></i>Yes, Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const confirmEmail = document.getElementById('confirmEmail');
    const confirmCheck = document.getElementById('confirmCheck');
    const deleteForm = document.getElementById('deleteAccountForm');
    const userEmail = '{{ Auth::user()->email }}';

    confirmDeleteBtn.addEventListener('click', function() {
        // Validate email matches
        if (confirmEmail.value !== userEmail) {
            alert('Email does not match. Please enter the correct email address.');
            return;
        }

        // Validate checkbox
        if (!confirmCheck.checked) {
            alert('Please confirm that you understand the consequences of deleting your account.');
            return;
        }

        // Submit form
        deleteForm.submit();
    });

    // Clear email on modal close
    document.getElementById('deleteAccountModal').addEventListener('hidden.bs.modal', function() {
        confirmEmail.value = '';
        confirmCheck.checked = false;
    });
});
</script>

<style>
.list-group-item.active {
    background-color: #eff6ff !important;
    border-color: #3b82f6 !important;
    color: #3b82f6 !important;
}
</style>
@endsection
