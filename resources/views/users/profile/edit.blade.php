@extends('layouts.user')

@section('content')
<style>
    /* Edit Profile Page Styling */
    .ln-card { 
        background: #fff; border-radius: 10px; border: 1px solid #e0e0e0; 
        margin-bottom: 15px; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.05); 
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
    
    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #1ABB9C;
        box-shadow: 0 0 0 3px rgba(26, 187, 156, 0.1);
    }
    
    .photo-upload-wrapper {
        position: relative;
        width: fit-content;
        margin: 0 auto;
    }
    
    .profile-photo-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #1ABB9C;
        transition: all 0.3s ease;
    }
    
    .camera-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid #fff;
        color: #fff;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(26, 187, 156, 0.3);
    }
    
    .camera-btn:hover {
        transform: scale(1.05);
    }

    .form-group textarea {
        resize: vertical;
        line-height: 1.5;
    }
</style>

<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
            <h3 style="font-weight: 700; color: #2A3F54; margin: 0; font-size: 20px;">
                <i class="fa fa-edit text-primary mr-2"></i>Edit Profile
            </h3>
        </div>
        <div class="col-md-6 col-12 text-md-right mt-3 mt-md-0">
            <a href="{{ route('user.profile.index') }}" style="color: #1ABB9C; font-weight: 600; text-decoration: none;">
                <i class="fa fa-arrow-left mr-1"></i> Back to Profile
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="ln-card" style="padding: 12px 15px; background: #fee2e2; border-left: 3px solid #dc3545; margin-bottom: 15px;">
            <div style="color: #b91c1c; font-size: 12px; font-weight: 600;">
                <i class="fa fa-exclamation-circle mr-2"></i>Validation Errors
            </div>
            <ul style="margin: 6px 0 0 20px; padding: 0; color: #7f1d1d; font-size: 11px;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 3px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="ln-card" style="padding: 12px 15px; background: #dcfce7; border-left: 3px solid #28a745; margin-bottom: 15px;">
            <div style="color: #15803d; font-size: 12px; font-weight: 600;">
                <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    <form action="{{ route('user.profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data" class="ln-card">
        @csrf
        @method('PUT')

        <div style="padding: 20px;">
            {{-- Profile Photo Section --}}
            <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;">
                <label class="form-label">Profile Photo</label>
                <div class="photo-upload-wrapper">
                    <img id="preview" 
                         src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                         alt="Profile Preview" 
                         class="profile-photo-preview">
                    <label for="profile_photo" class="camera-btn">
                        <i class="fa fa-camera"></i>
                    </label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(event)" style="display: none;">
                </div>
                <small style="display: block; text-align: center; color: #8a939f; font-size: 11px; margin-top: 8px;">
                    JPEG, PNG (Max: 2MB)
                </small>
                @error('profile_photo')
                    <small style="color: #dc3545; font-size: 11px; text-align: center; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Full Name --}}
            <div style="margin-bottom: 15px;">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', Auth::user()->name) }}" required>
                @error('name')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div style="margin-bottom: 15px;">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email', Auth::user()->email) }}" required>
                <small style="color: #8a939f; font-size: 11px; margin-top: 3px; display: block;">Used for notifications and account recovery</small>
                @error('email')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Phone Number --}}
            <div style="margin-bottom: 15px;">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                       name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" 
                       placeholder="+1 (555) 123-4567">
                @error('phone_number')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Bio --}}
            <div style="margin-bottom: 15px;">
                <label class="form-label">Bio</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" 
                          name="bio" rows="3" placeholder="Tell us about yourself..." maxlength="500" onkeyup="updateBioCount()">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                    <small style="color: #8a939f; font-size: 11px;">Share a bit about yourself</small>
                    <small style="color: #8a939f; font-size: 11px;"><span id="bio-count">{{ strlen(Auth::user()->bio ?? '') }}</span>/500</small>
                </div>
                @error('bio')
                    <small style="color: #dc3545; font-size: 11px; margin-top: 3px; display: block;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #e0e0e0; border-radius: 0 0 10px 10px;">
            <div class="row">
                <div class="col-sm-6 mb-2">
                    <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #1ABB9C 0%, #16a085 100%); color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px;">
                        <i class="fa fa-save mr-2"></i>Save Changes
                    </button>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('user.profile.index') }}" class="btn btn-outline-secondary w-100" style="color: #8a939f; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 16px; font-weight: 600; font-size: 12px;">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Related Actions --}}
    <div class="ln-card" style="padding: 15px 20px;">
        <h5 style="font-weight: 700; color: #2A3F54; margin: 0; margin-bottom: 12px; font-size: 13px;">
            <i class="fa fa-shield text-primary mr-2"></i>Account Security
        </h5>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('user.profile.change-password', Auth::user()->id) }}" class="btn btn-sm" style="background: #f8f9fa; color: #1ABB9C; border: 1px solid #e0e0e0; border-radius: 6px; padding: 8px 12px; font-size: 11px; font-weight: 600; text-decoration: none;">
                <i class="fa fa-lock mr-1"></i>Change Password
            </a>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function updateBioCount() {
    const bioText = document.getElementById('bio').value;
    document.getElementById('bio-count').textContent = bioText.length;
}
</script>

@endsection
