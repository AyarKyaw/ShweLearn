@extends('dashboard.layouts.master')

@section('title', 'Edit Category - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="category-edit-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="m-card__title" id="category-edit-title">Edit Category</h2>
                            <p class="m-card__subtitle">Update the details for {{ $category->name }}</p>
                        </div>
                        <a href="{{ route('admin.categories.index') }}" class="au-btn au-btn--small text-decoration-none d-inline-flex align-items-center gap-1" style="background-color: #6c757d; color: #fff;">
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back
                        </a>
                    </header>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please check the form below for errors.</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Course Category (Parent) Dropdown -->
                                <div class="form-group mb-3">
                                    <label for="course_category_id" class="form-label fw-bold">Course Category <span class="text-danger">*</span></label>
                                    <select name="course_category_id" id="course_category_id" class="form-select @error('course_category_id') is-invalid @enderror" required>
                                        <option value="">-- Select Course Category --</option>
                                        @foreach($courseCategories as $courseCategory)
                                            <option value="{{ $courseCategory->id }}" {{ old('course_category_id', $category->course_category_id) == $courseCategory->id ? 'selected' : '' }}>
                                                {{ $courseCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category Name -->
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $category->name) }}" 
                                           placeholder="e.g. Web Development" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Icon / Image -->
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label fw-bold">Category Icon / Image</label>
                                    
                                    @if($category->icon || $category->icon_path)
                                        <div class="mb-2 d-flex align-items-center gap-2">
                                            <img src="{{ asset($category->icon_path ?? $category->icon) }}" alt="{{ $category->name }}" class="rounded img-thumbnail" style="height: 48px; width: 48px; object-fit: cover;">
                                            <span class="text-muted small">Current Image</span>
                                        </div>
                                    @endif

                                    <input type="file" 
                                           name="icon" 
                                           id="icon" 
                                           class="form-control @error('icon') is-invalid @enderror" 
                                           accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep current icon.</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status Switch -->
                                <div class="form-group mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">Active / Published</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="au-btn au-btn--green au-btn--small border-0">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Update Category
                            </button>
                        </div>
                    </form>

                </section>
            </div>
        </div>
    </div>
</main>
@endsection