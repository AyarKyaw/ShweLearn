@extends('dashboard.layouts.master')

@section('title', 'Edit Alumni - Shwe Learn')

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
        max-height: 400px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="edit-alumni-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="edit-alumni-title">Edit Alumni: {{ $alumni->name }}</h2>
                            <p class="m-card__subtitle">Update profile details and account approval status</p>
                        </div>
                        <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                    </header>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 mt-3" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.alumni.update', $alumni->id) }}" method="POST" class="mt-4" id="alumniUpdateForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="image_base64" id="image_base64">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $alumni->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $alumni->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $alumni->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $alumni->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $alumni->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">Select Course</option>
                                    @foreach(\App\Models\Course::all() as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $alumni->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4 text-center">
                                <label class="form-label d-block text-start fw-semibold">Profile Image</label>
                                
                                <div class="avatar-preview-container">
                                    <img id="avatarPreview" src="{{ $alumni->image ? asset($alumni->image) : asset('assets/images/default-avatar.png') }}" alt="Alumni Preview">
                                </div>

                                <input type="file" id="imageInput" class="form-control rounded-3 @error('image_base64') is-invalid @enderror" accept="image/*">
                                <div class="form-text text-start mt-1">Select a new image to crop and replace the current one (optional).</div>
                                @error('image_base64')
                                    <div class="invalid-feedback text-start">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="au-btn au-btn--green au-btn--small border-0 text-decoration-none">Update Alumni</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</main>

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
    const croppedImageInput = document.getElementById('image_base64');
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
            autoCropArea: 0.8,
            responsive: true,
            restore: false,
        });
    });

    cropperModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (!croppedImageInput.value) {
            imageInput.value = '';
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
});
</script>
@endpush