@extends('layouts.user')

@section('content')
<style>
    .ln-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a939f;
        margin-bottom: 6px;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1ABB9C;
        box-shadow: 0 0 0 3px rgba(26, 187, 156, 0.1);
    }

    .priority-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
    }

    .priority-low { background: #dbeafe; color: #1e40af; }
    .priority-medium { background: #fef08a; color: #b45309; }
    .priority-high { background: #fee2e2; color: #b91c1c; }

    .textarea-counter {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #8a939f;
        margin-top: 4px;
    }

    .category-info {
        font-size: 11px;
        color: #8a939f;
        margin-top: 6px;
    }
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 style="font-weight: 700; color: #2A3F54; margin: 0; font-size: 20px;">
                <i class="fa fa-envelope text-primary mr-2"></i>Support Request
            </h3>
            <p style="color: #8a939f; font-size: 12px; margin: 6px 0 0;">Submit a support ticket and our team will respond within 24 hours</p>
        </div>
        <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0">
            <a href="https://wa.me/0787373722?text=I%20need%20help%20with%20Viomia" target="_blank" class="btn btn-sm" style="background: #25d366; color: #fff; border: none; border-radius: 6px; padding: 8px 12px; font-size: 11px; font-weight: 600;">
                <i class="fa fa-whatsapp mr-1"></i>Chat on WhatsApp
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="ln-card" style="padding: 12px 15px; background: #fee2e2; border-left: 3px solid #dc3545;">
            <div style="color: #b91c1c; font-size: 12px; font-weight: 600;">
                <i class="fa fa-exclamation-circle mr-2"></i>Form Errors
            </div>
            <ul style="margin: 6px 0 0 20px; padding: 0; color: #7f1d1d; font-size: 11px;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 3px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="ln-card" style="padding: 12px 15px; background: #dcfce7; border-left: 3px solid #28a745;">
            <div style="color: #15803d; font-size: 12px; font-weight: 600;">
                <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" class="ln-card">
        @csrf

        <div style="padding: 20px;">
            <!-- Subject -->
            <div style="margin-bottom: 15px;">
                <label class="form-label">Subject<span style="color: #dc3545;">*</span></label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                       name="subject" placeholder="Brief summary of your issue"
                       value="{{ old('subject') }}" required maxlength="255">
                <small style="color: #8a939f; font-size: 11px; margin-top: 3px; display: block;">
                    Be specific so we can help you faster
                </small>
                @error('subject')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <!-- Category -->
                <div class="col-md-6">
                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Category<span style="color: #dc3545;">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" name="category" required onchange="updateCategoryInfo()">
                            <option value="">-- Select Category --</option>
                            <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>🔧 Technical Issue</option>
                            <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>💳 Billing & Payment</option>
                            <option value="account" {{ old('category') === 'account' ? 'selected' : '' }}>👤 Account & Security</option>
                            <option value="trading" {{ old('category') === 'trading' ? 'selected' : '' }}>📈 Trading & Bots</option>
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>💬 General Inquiry</option>
                        </select>
                        <div id="category-info" class="category-info"></div>
                        @error('category')
                            <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Priority -->
                <div class="col-md-6">
                    <div style="margin-bottom: 15px;">
                        <label class="form-label">Priority<span style="color: #dc3545;">*</span></label>
                        <select class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                            <option value="">-- Select Priority --</option>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low - General question</option>
                            <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium - Something is not working</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High - Urgent/Blocking issue</option>
                        </select>
                        @error('priority')
                            <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Message -->
            <div style="margin-bottom: 15px;">
                <label class="form-label">Message<span style="color: #dc3545;">*</span></label>
                <textarea class="form-control @error('message') is-invalid @enderror"
                          name="message" rows="5" placeholder="Describe your issue in detail..."
                          onkeyup="updateMessageCounter()" maxlength="2000" required>{{ old('message') }}</textarea>
                <div class="textarea-counter">
                    <span>Provide as much detail as possible to help us assist you better</span>
                    <span><span id="msg-count">{{ strlen(old('message', '')) }}</span>/2000</span>
                </div>
                @error('message')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Attachment -->
            <div style="margin-bottom: 15px;">
                <label class="form-label">Attachment (Optional)</label>
                <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                       name="attachment" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.gif,.zip">
                <small style="color: #8a939f; font-size: 11px; margin-top: 3px; display: block;">
                    Max 5MB. Accepted: PDF, DOC, DOCX, PNG, JPG, GIF, ZIP
                </small>
                @error('attachment')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #e0e0e0; border-radius: 0 0 10px 10px;">
            <div class="row">
                <div class="col-sm-6 mb-2">
                    <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px;">
                        <i class="fa fa-paper-plane mr-2"></i>Submit Support Request
                    </button>
                </div>
                <div class="col-sm-6">
                    <a href="{{ auth()->check() ? route('user.profile.index') : '/' }}" class="btn btn-outline-secondary w-100" style="color: #8a939f; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px;">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Info Box --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="ln-card" style="padding: 15px 20px;">
                <h5 style="font-weight: 700; color: #2A3F54; margin: 0 0 10px; font-size: 13px;">
                    <i class="fa fa-clock text-primary mr-2"></i>Response Times
                </h5>
                <ul style="margin: 0; padding: 0; list-style: none; font-size: 12px;">
                    <li style="padding: 4px 0; color: #8a939f;"><strong style="color: #28a745;">High Priority:</strong> Within 4 hours</li>
                    <li style="padding: 4px 0; color: #8a939f;"><strong style="color: #f39c12;">Medium Priority:</strong> Within 12 hours</li>
                    <li style="padding: 4px 0; color: #8a939f;"><strong style="color: #1e40af;">Low Priority:</strong> Within 24 hours</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6 mt-3 mt-md-0">
            <div class="ln-card" style="padding: 15px 20px; border-left: 3px solid #25d366;">
                <h5 style="font-weight: 700; color: #2A3F54; margin: 0 0 10px; font-size: 13px;">
                    <i class="fa fa-whatsapp" style="color: #25d366; margin-right: 6px;"></i>Urgent Support
                </h5>
                <p style="margin: 0; font-size: 12px; color: #8a939f;">
                    Need immediate help? Chat with us on WhatsApp for faster response
                </p>
                <a href="https://wa.me/0787373722?text=I%20need%20urgent%20help" target="_blank" style="color: #25d366; font-weight: 600; text-decoration: none; font-size: 12px;">
                    Open WhatsApp →
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateMessageCounter() {
    const message = document.querySelector('textarea[name="message"]').value;
    document.getElementById('msg-count').textContent = message.length;
}

function updateCategoryInfo() {
    const category = document.querySelector('select[name="category"]').value;
    const categoryInfo = document.getElementById('category-info');
    
    const info = {
        technical: '🔧 Browser errors, login issues, bot not starting, data sync problems',
        billing: '💳 Invoice questions, payment issues, subscription problems',
        account: '👤 Password reset, 2FA issues, security concerns, account access',
        trading: '📈 Bot configuration, signal questions, trading strategy help',
        general: '💬 Any other questions or feedback about Viomia'
    };
    
    categoryInfo.textContent = info[category] || '';
}
</script>

@endsection
