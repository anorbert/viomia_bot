@extends('layouts.general')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 40px 20px;">
    <div class="container" style="max-width: 900px;">
        <a href="{{ route('user.profile.index') }}" class="btn btn-light btn-sm mb-4 shadow-sm" style="border-radius: 20px;">
            <i class="fa fa-arrow-left mr-2"></i> Back to Profile
        </a>

        <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h3 class="mb-0 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-edit mr-3" style="font-size: 24px;"></i> Edit Your Profile
                </h3>
                <p class="mt-2 mb-0" style="opacity: 0.9; font-size: 14px;">Update your personal information and profile details</p>
            </div>

            <div class="card-body p-5">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border: none;">
                        <h5 class="alert-heading mb-3"><i class="fa fa-exclamation-circle mr-2"></i> Validation Errors</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li class="mb-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="top: 12px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border: none;">
                        <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('user.profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-5 pb-3 border-bottom">
                        <div class="col-lg-4 text-center mb-4 mb-lg-0">
                            <label class="font-weight-bold d-block mb-3" style="color: #2c3e50;">Profile Photo</label>
                            <div style="position: relative; width: fit-content; margin: 0 auto;">
                                <img id="preview" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                                     alt="Preview" 
                                     class="rounded-circle border-white shadow"
                                     style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white; transition: all 0.3s ease;">
                                <label for="profile_photo" style="position: absolute; bottom: 0; right: 0; width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; color: white; font-size: 18px;">
                                    <i class="fa fa-camera"></i>
                                </label>
                                <input type="file" id="profile_photo" name="profile_photo" class="@error('profile_photo') is-invalid @enderror" accept="image/*" onchange="previewImage(event)" style="display: none;">
                            </div>
                            <small class="form-text text-muted d-block mt-3">JPEG, PNG (Max: 2MB)</small>
                            @error('profile_photo')
                                <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-8">
                            <div class="form-group mb-4">
                                <label for="name" class="font-weight-bold" style="color: #2c3e50;"><i class="fa fa-user mr-2"></i>Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                       name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e5e5;">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="email" class="font-weight-bold" style="color: #2c3e50;"><i class="fa fa-envelope mr-2"></i>Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                                       name="email" value="{{ old('email', Auth::user()->email) }}" required
                                       style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e5e5;">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="phone_number" class="font-weight-bold" style="color: #2c3e50;"><i class="fa fa-phone mr-2"></i>Phone Number</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" 
                               name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" placeholder="+1 (555) 123-4567"
                               style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e5e5;">
                        @error('phone_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-5">
                        <label for="bio" class="font-weight-bold" style="color: #2c3e50;"><i class="fa fa-quote-left mr-2"></i>Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" 
                                  name="bio" rows="4" placeholder="Tell us about yourself..." maxlength="500"
                                  style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e5e5; resize: vertical;" onkeyup="updateBioCount()">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="form-text text-muted">Share a bit about yourself</small>
                            <small class="form-text text-muted"><span id="bio-count">{{ strlen(Auth::user()->bio ?? '') }}</span>/500</small>
                        </div>
                        @error('bio')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row" style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #e5e5e5;">
                        <div class="col-sm-6 mb-2">
                            <button type="submit" class="btn btn-block font-weight-bold" 
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px; border-radius: 8px; border: none;">
                                <i class="fa fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
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

<style>
    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15) !important;
    }
    
    .form-control {
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        border-color: #667eea;
        color: #667eea
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .custom-file-upload {
        position: relative;
    }
</style>
@endsection
