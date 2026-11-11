@extends('dashboard.layouts.master')

@section('title', 'Create Unit - MIFFA')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="create-unit-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="create-unit-title">Add New Unit</h2>
                            <p class="m-card__subtitle">Create a structural module for a course</p>
                        </div>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Units
                        </a>
                    </header>

                    <div class="card-body mt-3">
                        <form action="{{ route('admin.units.store') }}" method="POST">
                            @csrf

                            <!-- Parent Course Selection -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label fw-semibold">Parent Course <span class="text-danger">*</span></label>
                                <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('course_id') ? '' : 'selected' }}>Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->code ? '[' . $course->code . '] ' : '' }}{{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Unit Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Unit 1: Introduction to Freight Forwarding" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Display Order -->
                            <div class="mb-3">
                                <label for="order" class="form-label fw-semibold">Sort Order</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 1) }}" min="1">
                                <small class="text-muted">Determines the sequence position of this unit in the course structure.</small>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Optional unit overview or summary...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.units.index') }}" class="btn btn-light border">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green au-btn--small text-decoration-none">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Unit
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection