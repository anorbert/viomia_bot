@extends('layouts.admin')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-form-container { max-width: 700px; margin: 0 auto; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.07); }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
.vi-form-group { }
.vi-form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 8px; display: block; }
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; }
.vi-form-input::placeholder { color: #4b5563; }
.vi-form-input:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-input.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-textarea { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; transition: all 0.2s; font-family: inherit; resize: vertical; min-height: 100px; }
.vi-form-textarea::placeholder { color: #4b5563; }
.vi-form-textarea:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; background-color: rgba(26,187,156,0.02) !important; }
.vi-form-textarea.is-invalid { border-color: #ef4444 !important; }
.vi-form-textarea.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
.vi-form-select { width: 100%; background-color: #1a2235 !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #f1f5f9; padding: 10px 12px; border-radius: 6px; font-size: 13px; cursor: pointer; }
.vi-form-select:focus { border-color: #1ABB9C !important; outline: none !important; box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important; }
.vi-form-select option { background-color: #1a2235 !important; color: #f1f5f9 !important; padding: 8px; }
.vi-form-select option:checked { background: linear-gradient(#1ABB9C, #1ABB9C) !important; color: #fff !important; }
.vi-form-hint { font-size: 11px; color: #4b5563; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.vi-error i { font-size: 10px; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
.vi-info-box-title { font-size: 11px; font-weight: 700; color: #1ABB9C; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-info-box-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.vi-status-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-status-active { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-inactive { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-buttons { display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-btn { padding: 10px 18px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 14px rgba(26,187,156,0.32) !important; }
.vi-btn-secondary { background-color: rgba(107,114,128,0.13) !important; color: #9CA3AF !important; border: 1px solid rgba(107,114,128,0.25) !important; }
.vi-btn-secondary:hover { background-color: rgba(107,114,128,0.2) !important; }
.vi-metadata { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.07); }
.vi-metadata-item { }
.vi-metadata-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #4b5563; letter-spacing: 0.5px; }
.vi-metadata-value { font-size: 12px; color: #f1f5f9; margin-top: 4px; }
</style>
@endpush

@section('content')

<div class="vi-form-container">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">🤖 EA Bots Management</div>
            <div class="vi-header-title">Edit EA Bot Configuration</div>
            <div class="vi-header-sub">Manage bot settings and deployment options</div>
        </div>
        <a href="{{ route('admin.bots.index') }}" class="vi-btn vi-btn-secondary" style="margin-left:auto; width:auto;">
            <i class="fa fa-chevron-left"></i> Back to Bots
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($errors->all() as $error)
                <li style="padding: 3px 0;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-cog"></i> Bot Settings</div>

        <form id="editBotForm" method="POST" action="{{ route('admin.bots.update', $bot->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="vi-form-row">
                <div class="vi-form-group">
                    <label class="vi-form-label">Bot Name <span class="required">*</span></label>
                    <input type="text" name="name" class="vi-form-input @error('name') is-invalid @enderror"
                           placeholder="e.g., Advanced Scalper v3"
                           value="{{ old('name', $bot->name) }}" required>
                    @error('name')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Unique identifier for this bot</div>
                </div>

                <div class="vi-form-group">
                    <label class="vi-form-label">Version <span class="required">*</span></label>
                    <input type="text" name="version" class="vi-form-input @error('version') is-invalid @enderror"
                           placeholder="e.g., 3.2.1"
                           value="{{ old('version', $bot->version) }}" required>
                    @error('version')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Current release version</div>
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group" style="grid-column: 1 / -1;">
                    <label class="vi-form-label">Bot Status <span class="required">*</span></label>
                    <select name="status" class="vi-form-select @error('status') is-invalid @enderror" required>
                        <option value="Active" {{ old('status', $bot->status) === 'Active' ? 'selected' : '' }}>Active (Deployed)</option>
                        <option value="Inactive" {{ old('status', $bot->status) === 'Inactive' ? 'selected' : '' }}>Inactive (Testing)</option>
                    </select>
                    @error('status')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <div class="vi-form-row">
                <div class="vi-form-group" style="grid-column: 1 / -1;">
                    <label class="vi-form-label">EA Bot File</label>
                    <div style="display: grid; gap: 12px;">
                        @if($bot->address)
                            <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 6px; padding: 12px; display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-check-circle" style="color: #22C55E; font-size: 14px;"></i>
                                    <div>
                                        <div style="color: #22C55E; font-weight: 700; font-size: 12px;">File Uploaded</div>
                                        <div style="color: #94a3b8; font-size: 11px; margin-top: 2px;">Updated: {{ $bot->updated_at?->format('M d, Y H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.bots.download', $bot) }}" class="vi-btn" style="background-color:rgba(34,197,94,0.13); color:#22C55E; border:1px solid rgba(34,197,94,0.3); margin: 0;">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        @endif
                        <input type="file" name="address" id="botFile" class="vi-form-input @error('address') is-invalid @enderror"
                               accept=".exe,.zip,.dll" style="padding: 8px 12px; cursor: pointer;">
                        @error('address')<div class="vi-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Supported: .exe, .zip, .dll (Max 100MB) - Leave blank to keep current file</div>
                    </div>
                </div>
            </div>

            <!-- Metadata Display -->
            @if($bot->created_at || $bot->updated_at)
            <div class="vi-metadata">
                @if($bot->created_at)
                <div class="vi-metadata-item">
                    <div class="vi-metadata-label"><i class="fa fa-calendar"></i> Created</div>
                    <div class="vi-metadata-value">{{ $bot->created_at?->format('M d, Y H:i') ?? '—' }}</div>
                </div>
                @endif
                @if($bot->updated_at)
                <div class="vi-metadata-item">
                    <div class="vi-metadata-label"><i class="fa fa-refresh"></i> Last Updated</div>
                    <div class="vi-metadata-value">{{ $bot->updated_at?->format('M d, Y H:i') ?? '—' }}</div>
                </div>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.bots.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('.vi-form-input');
    if (firstInput) firstInput.focus();

    // File upload validation
    const botFile = document.getElementById('botFile');
    if (botFile) {
        botFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 102400000) { // 100MB
                    alert('File size must not exceed 100MB');
                    this.value = '';
                } else {
                    console.log('File selected:', file.name);
                }
            }
        });
    }
});
</script>

@endsection
