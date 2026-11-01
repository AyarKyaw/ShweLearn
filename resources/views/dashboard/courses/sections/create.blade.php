@extends('dashboard.layouts.master')

@section('title', 'Create Section - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="create-section-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="create-section-title">Add New Section</h2>
                            <p class="m-card__subtitle">Create content block under a course unit</p>
                        </div>
                        <a href="{{ route('admin.sections.index', ['unit_id' => $section->unit_id ?? request('unit_id')]) }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Sections
</a>
                    </header>

                    <div class="card-body mt-3">
                        <form action="{{ route('admin.sections.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="unit_id" class="form-label fw-semibold">Parent Unit <span class="text-danger">*</span></label>
                                <select name="unit_id" id="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('unit_id') ? '' : 'selected' }}>Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->title }} {{ $unit->course ? ' (' . $unit->course->title . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Section Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. 1.1 Overview of Bill of Lading" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="order" class="form-label fw-semibold">Sort Order</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 1) }}" min="1">
                                <small class="text-muted">Determines the sequence position inside the parent unit.</small>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label fw-semibold">Content</label>
                                <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" placeholder="Write section body content or instructions...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.sections.index') }}" class="btn btn-light border">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green au-btn--small text-decoration-none">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Section
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