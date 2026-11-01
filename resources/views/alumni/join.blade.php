@extends('layouts.master')

@section('title', 'Join Alumni - Shwe Learn')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .alumni-hero {
        background: var(--dark);
        padding: 130px 0 70px;
        color: var(--white);
        position: relative;
    }

    .alumni-hero h1 {
        color: var(--white) !important;
        font-weight: 700;
        font-size: 2.5rem;
    }

    .alumni-hero p {
        color: var(--color-style-six) !important;
        font-size: 1.1rem;
    }

    .alumni-card {
        border: 1px solid var(--color-style-six);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        background: var(--white);
        margin-top: -35px;
        position: relative;
        z-index: 2;
    }

    .btn-alumni-submit {
        background: var(--bg-gradient) !important;
        color: var(--white) !important;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 30px;
        padding: 12px 36px;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(28, 176, 152, 0.3);
    }

    .btn-alumni-submit:hover {
        background: var(--color-primary) !important;
        color: var(--white) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 213, 179, 0.4);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 0.25rem rgba(28, 176, 152, 0.25) !important;
    }

    .form-label {
        color: var(--color-heading);
        font-weight: 600;
    }

    .avatar-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--color-primary);
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-gray);
    }

    .avatar-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .img-cropper-container {
        max-height: 400px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

<div class="alumni-hero text-center">
    <div class="container">
        <h1 class="mb-3">Join the Shwe Learn Alumni Network</h1>
        <p class="mx-auto mb-0" style="max-width: 650px;">
            Create your alumni account to connect with peers and access exclusive member resources.
        </p>
    </div>
</div>

<div class="pb-5" style="background-color: var(--bg-gray-secondary);">
    <div class="container pb-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 mt-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 mt-4" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card alumni-card p-4 p-md-5">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--color-heading);">Alumni Registration</h3>
                    
                    <form action="{{ route('alumni.store') }}" method="POST" id="alumniRegisterForm">
                        @csrf

                        <input type="hidden" name="cropped_image" id="cropped_image" required>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 py-2" placeholder="Enter your full name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                                placeholder="name@example.com"
                                value="{{ old('email') }}"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course_id" class="form-label">Course Completed <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="form-select rounded-3 py-2" required>
                                <option value="" disabled selected>Select your course</option>
                                @if(isset($courses) && $courses->count() > 0)
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title ?? $course->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="1">Diploma in International Freight Forwarding</option>
                                    <option value="2">Supply Chain Management Course</option>
                                    <option value="3">Logistics Operations Certificate</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 py-2" placeholder="Create a password" required>
                        </div>

                        <div class="mb-4 text-center">
                            <label class="form-label d-block text-start">Profile Image <span class="text-danger">*</span></label>
                            
                            <div class="avatar-preview-container">
                                <img id="avatarPreview">
                            </div>

                            <input type="file" id="imageInput" class="form-control rounded-3" accept="image/*">
                            <div class="form-text text-start">Upload and crop your photo so your face fits perfectly.</div>
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn-alumni-submit mb-3 w-100">
                                <i class="fas fa-user-plus me-2"></i> Register as Alumni
                            </button>
                            <p class="mb-0 text-muted fs-6">
                                Already have an account? <a href="{{ route('alumni.login') }}" style="color: var(--color-primary); font-weight: 600;">Sign In</a>
                            </p>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Adjust Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-cropper-container">
                    <img id="cropperImage" src="" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-alumni-submit" id="cropAndSaveBtn">Save & Apply</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let cropper;
    const imageInput = document.getElementById('imageInput');
    const cropperImage = document.getElementById('cropperImage');
    const avatarPreview = document.getElementById('avatarPreview');
    const croppedImageInput = document.getElementById('cropped_image');
    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));

    imageInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                cropperImage.src = event.target.result;
                cropperModal.show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropperImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 0.8,
            responsive: true,
            restore: false,
        });
    });

    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    document.getElementById('cropAndSaveBtn').addEventListener('click', function () {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        const base64Image = canvas.toDataURL('image/jpeg', 0.9);
        croppedImageInput.value = base64Image;
        avatarPreview.src = base64Image;
        cropperModal.hide();
    });

    document.getElementById('alumniRegisterForm').addEventListener('submit', function (e) {
        if (!croppedImageInput.value) {
            e.preventDefault();
            alert('Please select and crop your profile image before submitting.');
        }
    });
});
</script>
@endpush