@extends('dashboard.layouts.master')

@section('title', 'Create Course Category - Shwe Learn')

@section('content')
<main id="main-content" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Create Course Category</h1>
                    <p class="subtitle">Add a new category or classification for courses.</p>
                </div>
                <div>
                    <a href="{{ route('admin.course-categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Categories
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <strong>Add New</strong> Category
                        </div>
                        <div class="card-body card-block">
                            <form action="{{ route('admin.course-categories.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Category Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label font-weight-bold">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="e.g. Diploma, Certificate, Short Course" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug (Optional / Auto-generated) -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug <small class="text-muted">(Optional - generated automatically if left blank)</small></label>
                                    <input type="text" 
                                           id="slug" 
                                           name="slug" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           placeholder="e.g. diploma-courses" 
                                           value="{{ old('slug') }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" 
                                           id="image" 
                                           name="image" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*">
                                    <small class="form-text text-muted">Upload an optional cover image for this category (PNG, JPG, WEBP up to 2MB).</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              placeholder="Brief summary of what this category entails..." 
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-1"></i> Save Category
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection