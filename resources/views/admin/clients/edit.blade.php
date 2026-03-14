@extends('layouts.admin')

@section('title', 'Edit Client — ' . config('app.name'))

@push('styles')
<style>
    /* ════════════════════════════════════════
       VIOMIA · EDIT CLIENT FORM
       Dark theme with teal accents
       ════════════════════════════════════════ */

    .right_col {
        background-color: #1e2a3a !important;
        padding: 20px 24px !important;
        min-height: 100vh;
    }

    /* ── PAGE HEADER ── */
    .frm-header {
        display: flex !important; align-items: center !important;
        gap: 14px; margin-bottom: 24px;
    }
    .frm-back-btn {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background-color: rgba(26,187,156,0.12) !important;
        border: 1px solid rgba(26,187,156,0.25) !important;
        color: #1ABB9C !important; text-decoration: none !important;
        transition: all .2s;
        font-size: 16px;
    }
    .frm-back-btn:hover {
        background-color: rgba(26,187,156,0.20) !important;
        border-color: rgba(26,187,156,0.40) !important;
        transform: translateX(-3px);
    }
    .frm-header-content h1 {
        font-size: 22px; font-weight: 900; color: #ffffff;
        margin: 0; line-height: 1.1;
    }
    .frm-header-sub {
        font-size: 12px; color: #8ab0c8; margin-top: 2px;
    }

    /* ── FORM CARD ── */
    .frm-card {
        background-color: #253347 !important;
        border: 1px solid rgba(255,255,255,0.13) !important;
        border-radius: 12px !important;
        padding: 28px !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.20) !important;
        max-width: 600px;
    }

    /* ── FORM GROUP ── */
    .frm-group {
        margin-bottom: 20px;
    }
    .frm-group:last-child { margin-bottom: 0; }
    .frm-label {
        display: block;
        font-size: 12.5px; font-weight: 700;
        color: #c5d8e8 !important;
        margin-bottom: 8px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .frm-label .frm-req { color: #ef4444; margin-left: 3px; }

    .frm-input {
        width: 100% !important;
        background-color: #1e2a3a !important;
        border: 1.5px solid rgba(255,255,255,0.15) !important;
        border-radius: 10px !important;
        color: #e8f4ff !important;
        font-size: 13px !important;
        padding: 10px 14px !important;
        font-family: 'DM Sans', system-ui, sans-serif !important;
        transition: all .2s !important;
    }
    .frm-input::placeholder { color: #6a8aA0 !important; }
    .frm-input:focus {
        outline: none !important;
        border-color: rgba(26,187,156,0.60) !important;
        box-shadow: 0 0 0 3px rgba(26,187,156,0.12) !important;
        background-color: rgba(26,187,156,0.04) !important;
    }
    .frm-input:disabled {
        opacity: 0.6 !important;
        cursor: not-allowed !important;
    }

    /* ── VALIDATION ── */
    .frm-input.is-invalid {
        border-color: rgba(239,68,68,0.55) !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08) !important;
    }
    .frm-error {
        font-size: 11px;
        color: #fca5a5 !important;
        margin-top: 5px;
        display: flex; align-items: center; gap: 4px;
    }
    .frm-error i { font-size: 9px; }

    /* ── FORM ROW (For 2 columns) ── */
    .frm-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    }
    @media (max-width: 640px) {
        .frm-row { grid-template-columns: 1fr; }
    }

    /* ── BUTTONS ── */
    .frm-actions {
        display: flex; gap: 10px; margin-top: 28px;
        justify-content: flex-start;
    }
    .frm-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 6px; padding: 10px 20px;
        border-radius: 10px; font-size: 12.5px; font-weight: 700;
        border: none; cursor: pointer;
        transition: all .2s;
        text-decoration: none !important;
    }
    .frm-btn-submit {
        background-color: #1ABB9C !important; color: #fff !important;
    }
    .frm-btn-submit:hover {
        background-color: #15a085 !important;
        box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important;
        transform: translateY(-1px);
    }
    .frm-btn-cancel {
        background-color: rgba(255,255,255,0.10) !important; color: #a8c0d4 !important;
        border: 1px solid rgba(255,255,255,0.15) !important;
    }
    .frm-btn-cancel:hover {
        background-color: rgba(255,255,255,0.15) !important;
        border-color: rgba(255,255,255,0.25) !important;
    }

    /* ── INFO BOX ── */
    .frm-info-box {
        background-color: rgba(26,187,156,0.08) !important;
        border: 1px solid rgba(26,187,156,0.25) !important;
        border-radius: 10px !important;
        padding: 12px 14px !important;
        font-size: 11.5px !important;
        color: #8ab0c8 !important;
        margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
    }
    .frm-info-box i {
        font-size: 13px; color: #1ABB9C; flex-shrink: 0; margin-top: 1px;
    }

    .frm-anim { animation: frmIn .4s ease both; }
    @keyframes frmIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
</style>
@endpush

@section('content')

<div class="frm-header frm-anim">
    <a href="{{ route('admin.clients.index') }}" class="frm-back-btn">
        <i class="fa fa-chevron-left"></i>
    </a>
    <div class="frm-header-content">
        <h1>Edit Client</h1>
        <div class="frm-header-sub">Update client account details</div>
    </div>
</div>

<div class="frm-card frm-anim">
    <div class="frm-info-box">
        <i class="fa fa-info-circle"></i>
        <span><strong>UUID:</strong> {{ $client->uuid }}</span>
    </div>

    <form action="{{ route('admin.clients.update', $client->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="frm-group">
            <label class="frm-label">
                Full Name
                <span class="frm-req">*</span>
            </label>
            <input type="text"
                   name="name"
                   class="frm-input @error('name') is-invalid @enderror"
                   placeholder="Enter client's full name"
                   value="{{ old('name', $client->name) }}"
                   required>
            @error('name')
                <div class="frm-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- Email & Phone Row -->
        <div class="frm-row">
            <div class="frm-group">
                <label class="frm-label">
                    Email Address
                    <span class="frm-req">*</span>
                </label>
                <input type="email"
                       name="email"
                       class="frm-input @error('email') is-invalid @enderror"
                       placeholder="client@example.com"
                       value="{{ old('email', $client->email) }}"
                       required>
                @error('email')
                    <div class="frm-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="frm-group">
                <label class="frm-label">Phone Number</label>
                <input type="tel"
                       name="phone_number"
                       class="frm-input"
                       placeholder="+234 (Optional)"
                       value="{{ old('phone_number', $client->phone_number) }}">
                @error('phone_number')
                    <div class="frm-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Account Status -->
        <div class="frm-group">
            <label class="frm-label">Account Status</label>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;background-color:#1e2a3a;border-radius:10px;border:1.5px solid rgba(255,255,255,0.15);">
                <span style="width:8px;height:8px;border-radius:50%;background-color:{{ $client->trashed() ? '#ef4444' : '#22C55E' }};display:inline-block;"></span>
                <span style="color:#e8f4ff;font-weight:700;">{{ $client->trashed() ? 'INACTIVE (Soft-Deleted)' : 'ACTIVE' }}</span>
            </div>
            <div style="font-size:11px;color:#8ab0c8;margin-top:6px;">
                <i class="fa fa-info-circle" style="margin-right:4px;opacity:.7;"></i>
                Toggle status from the clients list page
            </div>
        </div>

        <!-- Joined Date -->
        <div class="frm-group">
            <label class="frm-label">Member Since</label>
            <div style="padding:10px 12px;background-color:rgba(26,187,156,0.08);border-radius:10px;border:1.5px solid rgba(26,187,156,0.25);color:#8ab0c8;font-size:13px;font-weight:600;">
                <i class="fa fa-calendar-o" style="margin-right:6px;color:#1ABB9C;"></i>
                {{ $client->created_at->format('F j, Y') }} at {{ $client->created_at->format('g:i A') }}
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="frm-actions">
            <button type="submit" class="frm-btn frm-btn-submit">
                <i class="fa fa-check-circle"></i> Save Changes
            </button>
            <a href="{{ route('admin.clients.index') }}" class="frm-btn frm-btn-cancel">
                <i class="fa fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Auto-focus first input on page load
    document.addEventListener('DOMContentLoaded', function() {
        const firstInput = document.querySelector('.frm-input');
        if (firstInput) firstInput.focus();
    });
</script>
@endpush
