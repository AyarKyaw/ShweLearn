@extends('dashboard.layouts.master')

@section('title', 'Edit Teacher - Shwe Learn')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .avatar-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--theme-color, #de2b2b);
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }

    .avatar-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .img-cropper-container {
        max-height: 600px;
        min-height: 380px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #0d0f11;
    }
</style>
@endpush

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <section class="m-card">
                    <header class="m-card__header">
                        <h2 class="m-card__title">Edit Teacher</h2>
                        <p class="m-card__subtitle">Update teacher details and profile image</p>
                    </header>

                    <div class="card-body">
                        <form action="{{ route('admin.instructors.update', $instructor->id) }}" method="POST" id="instructorEditForm">
                            @csrf
                            @method('PUT')

                            <!-- Hidden Base64 Input for Profile Avatar -->
                            <input type="hidden" name="cropped_image" id="cropped_image">

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Teacher Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $instructor->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">
                                    Email <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('name', $instructor->email) }}"
                                    placeholder="Enter teacher email"
                                    required
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">
                                    Password <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter teacher password"
                                    required
                                >

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label fw-bold">Bio / Biography</label>
                                <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" placeholder="Enter teacher background, qualification, or summary...">{{ old('bio', $instructor->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Profile Image Section -->
                            <div class="mb-4 text-center col-md-6 mx-auto">
                                <label class="form-label d-block text-start fw-bold">Profile Image (1:1 Aspect Ratio)</label>
                                
                                <div class="avatar-preview-container">
                                    <img id="avatarPreview" src="{{ $instructor->image ? asset('storage/' . $instructor->image) : asset('assets/images/default-avatar.png') }}" alt="{{ $instructor->name }}">
                                </div>

                                <input type="file" id="imageInput" class="form-control rounded-3" accept="image/*">
                                <div class="form-text text-start mt-1">Leave blank to keep current profile image.</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green text-decoration-none">Update Teacher</button>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>
</main>

<!-- Profile Image Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="cropperModalTitle">Adjust Profile Picture (1:1 Ratio)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="img-cropper-container">
                    <img id="cropperImage" src="" style="max-width: 100%; max-height: 580px; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="cropAndSaveBtn">Save & Apply</button>
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
    
    const cropperModalElement = document.getElementById('cropperModal');
    const cropperModal = new bootstrap.Modal(cropperModalElement);

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

    cropperModalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropperImage, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            responsive: true,
            restore: false,
            center: true,
            highlight: false,
            background: true,
        });
    });

    cropperModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        imageInput.value = '';
    });

    document.getElementById('cropAndSaveBtn').addEventListener('click', function () {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const base64Image = canvas.toDataURL('image/jpeg', 0.92);

        croppedImageInput.value = base64Image;
        avatarPreview.src = base64Image;

        cropperModal.hide();
    });
});
</script>
@endpush