@extends('app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fa fa-edit mr-2"></i> Edit Profile
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fa fa-exclamation-circle mr-2"></i> Validation Errors</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Photo -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Profile Photo</label>
                            <div class="custom-file-upload mt-2">
                                <img id="preview" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/pfr_logo2.png') }}" 
                                     alt="Preview" 
                                     class="img-thumbnail d-block mb-3"
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                                
                                <input type="file" id="profile_photo" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                                <small class="form-text text-muted">Allowed: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-group mb-3">
                            <label for="name" class="font-weight-bold">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                   name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="font-weight-bold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                                   name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group mb-3">
                            <label for="phone_number" class="font-weight-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" 
                                   name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}" placeholder="+1 (555) 123-4567">
                            @error('phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="form-group mb-4">
                            <label for="bio" class="font-weight-bold">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" 
                                      name="bio" rows="4" placeholder="Tell us about yourself..." maxlength="500">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                            <small class="form-text text-muted">Max 500 characters</small>
                            @error('bio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block rounded-lg font-weight-bold">
                                    <i class="fa fa-save mr-2"></i> Save Changes
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('user.profile.index') }}" class="btn btn-outline-secondary btn-block rounded-lg font-weight-bold">
                                    <i class="fa fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
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
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .rounded-lg {
        border-radius: 8px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .custom-file-upload {
        position: relative;
    }
</style>
@endsection
