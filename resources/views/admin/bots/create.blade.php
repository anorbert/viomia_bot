@extends('layouts.admin')

@section('title', 'Create Bot — ' . config('app.name'))

@push('styles')
<style>
/* ── LAYOUT ── */
.right_col { background-color: #0a0e17 !important; }
.form-container { max-width: 700px; margin: 0 auto; }

/* ── HEADER ── */
.vi-header { 
    display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; 
    background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; 
    border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; 
    border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; 
}
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-breadcrumb { font-size: 11px; color: #4b5563; display: flex; align-items: center; gap: 6px; }
.vi-breadcrumb a { color: #1ABB9C; text-decoration: none; transition: color 0.2s; }
.vi-breadcrumb a:hover { color: #15a085; }

/* ── PANELS ── */
.vi-panel { 
    background: linear-gradient(135deg, #1a2235 0%, #111827 100%); 
    border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; 
    padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; 
}
.vi-panel-head {
    padding: 0 0 16px 0; margin-bottom: 20px;
    border-bottom: 1px solid rgba(26,187,156,0.2);
}
.vi-panel-title { 
    font-size: 14px; font-weight: 700; color: #f1f5f9; 
    display: flex; align-items: center; gap: 8px; margin: 0; 
}
.vi-panel-title i { color: #1ABB9C; font-size: 18px; }

/* ── FORM ELEMENTS ── */
.vi-form-group { margin-bottom: 20px; }
.vi-form-label { 
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; 
    color: #4b5563; margin-bottom: 8px; display: block; 
}
.vi-form-label .required { color: #ef4444; margin-left: 2px; }
.vi-form-input, .vi-form-textarea {
    width: 100%;
    background-color: #1a2235 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: #f1f5f9;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 13px;
    transition: all 0.2s ease;
    font-family: inherit;
    box-sizing: border-box;
}
.vi-form-input::placeholder, .vi-form-textarea::placeholder { color: #4b5563; }
.vi-form-input:focus, .vi-form-textarea:focus {
    border-color: #1ABB9C !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(26,187,156,0.1) !important;
    background-color: rgba(26,187,156,0.02) !important;
}
.vi-form-input.is-invalid, .vi-form-textarea.is-invalid { border-color: #ef4444 !important; }
.vi-form-input.is-invalid:focus, .vi-form-textarea.is-invalid:focus { 
    box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; 
}
.vi-form-textarea { min-height: 100px; resize: vertical; }
.vi-form-hint { 
    font-size: 11px; color: #4b5563; margin-top: 6px; 
    display: flex; align-items: center; gap: 4px; 
}
.vi-form-hint i { color: #1ABB9C; opacity: 0.7; }
.vi-form-error { 
    font-size: 11px; color: #ef4444; margin-top: 4px; 
    display: flex; align-items: center; gap: 4px; 
}
.vi-form-error i { font-size: 10px; }

/* ── GRID ── */
.vi-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
    gap: 20px; 
}

/* ── BUTTONS ── */
.vi-buttons { 
    display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; 
    border-top: 1px solid rgba(255,255,255,0.07); 
}
.vi-btn { 
    padding: 12px 24px; border-radius: 6px; font-weight: 700; 
    border: none; cursor: pointer; font-size: 12px; 
    display: inline-flex; align-items: center; gap: 6px; 
    text-decoration: none; transition: all 0.2s; 
    text-transform: uppercase; letter-spacing: 0.5px; 
}
.vi-btn-primary { 
    background: linear-gradient(135deg, #1ABB9C 0%, #15a085 100%); 
    color: #fff; box-shadow: 0 4px 12px rgba(26,187,156,0.25); 
}
.vi-btn-primary:hover { box-shadow: 0 6px 20px rgba(26,187,156,0.35); }
.vi-btn-secondary { 
    background-color: rgba(139,92,246,0.13); 
    color: #A78BFA; border: 1px solid rgba(139,92,246,0.25); 
}
.vi-btn-secondary:hover { background-color: rgba(139,92,246,0.2); }

/* ── ALERTS ── */
.vi-error-alert { 
    background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); 
    border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; 
    color: #ef4444; font-size: 12px; 
}
.vi-error-alert strong { display: block; margin-bottom: 8px; }
.vi-error-alert ul { 
    list-style: none; padding: 0; margin: 0; 
}
.vi-error-alert li { padding: 3px 0; }

/* ── INFO BOX ── */
.vi-info-box { 
    background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); 
    border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 16px; 
    margin-top: 24px; 
}
.vi-info-box-title { 
    font-size: 11px; font-weight: 700; color: #1ABB9C; 
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; 
}
.vi-info-box-text { font-size: 12px; color: #94a3b8; line-height: 1.6; margin: 0; }
</style>
@endpush

@section('content')

<div class="form-container">
    <!-- Header -->
    <div class="vi-header">
        <div>
            <div class="vi-breadcrumb">
                <a href="{{ route('admin.bots.index') }}">Bots</a>
                <span>/</span>
                <span>Create New</span>
            </div>
            <h1 class="vi-header-title">Create New Bot</h1>
            <p class="vi-header-sub">Add a new EA bot configuration to the system</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="vi-error-alert">
        <strong><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Bot Configuration Form -->
    <div class="vi-panel">
        <div class="vi-panel-head">
            <div class="vi-panel-title"><i class="fa fa-robot"></i> Bot Details</div>
        </div>

        <form method="POST" action="{{ route('admin.bots.store') }}" novalidate>
            @csrf

            <div class="vi-grid">
                <!-- Bot Name -->
                <div class="vi-form-group">
                    <label class="vi-form-label">Bot Name <span class="required">*</span></label>
                    <input type="text" name="name" class="vi-form-input @error('name') is-invalid @enderror"
                           placeholder="e.g., Viomia AI Bot" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="vi-form-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Unique name for this bot version</div>
                </div>

                <!-- Bot Version -->
                <div class="vi-form-group">
                    <label class="vi-form-label">Version <span class="required">*</span></label>
                    <input type="text" name="version" class="vi-form-input @error('version') is-invalid @enderror"
                           placeholder="e.g., 2.5.1" value="{{ old('version') }}" required>
                    @error('version')
                        <div class="vi-form-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Semantic version (major.minor.patch)</div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="vi-form-group">
                <label class="vi-form-label">Bot File <span class="required">*</span></label>
                <input type="file" name="address" class="vi-form-input @error('address') is-invalid @enderror"
                       accept=".ex4,.mql4,.mql5,.zip" required>
                @error('address')
                    <div class="vi-form-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
                <div class="vi-form-hint"><i class="fa fa-info-circle"></i> Accepted formats: .ex4, .mql4, .mql5, .zip</div>
            </div>

            <!-- Description -->
            <div class="vi-form-group">
                <label class="vi-form-label">Description</label>
                <textarea name="description" class="vi-form-textarea @error('description') is-invalid @enderror"
                          placeholder="Describe what this bot does, its features, and configuration...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="vi-form-error"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="vi-buttons">
                <button type="submit" class="vi-btn vi-btn-primary">
                    <i class="fa fa-save"></i> Create Bot
                </button>
                <a href="{{ route('admin.bots.index') }}" class="vi-btn vi-btn-secondary" style="text-decoration: none;">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Help Information -->
    <div class="vi-info-box">
        <div class="vi-info-box-title">💡 Bot Configuration Tips</div>
        <p class="vi-info-box-text">
            • Use clear, descriptive names for easy identification<br>
            • Follow semantic versioning (e.g., 1.0.0, 2.1.5)<br>
            • Provide detailed descriptions for your trading team<br>
            • Test the bot in demo mode before live deployment<br>
            • Keep backups of all bot source files
        </p>
    </div>
</div>

@endsection
