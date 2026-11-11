@extends('layouts.master')

@section('title', 'My Enrolled Courses - Shwe Learn')

@section('content')
<div class="container py-5 my-5 position-relative z-1">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1">My Enrolled Courses</h3>
            <p class="text-muted mb-0">Access and track all your active learning programs.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Browse More Courses
            </a>
        </div>
    </div>

    <!-- Enrolled Courses Grid -->
    @if($courses->count() > 0)
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                        <!-- Enrollment Badge -->
                        <span class="position-absolute top-0 end-0 m-3 badge bg-success rounded-pill px-3 py-2 shadow-sm fs-7">
                            <i class="fas fa-check-circle me-1"></i> Enrolled
                        </span>

                        <!-- Course Image -->
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-primary bg-gradient text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-book-open fa-3x opacity-50"></i>
                            </div>
                        @endif

                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Category Badge -->
                            @if($course->category)
                                <span class="text-uppercase text-primary fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    {{ $course->category->name }}
                                </span>
                            @endif

                            <h5 class="card-title fw-bold text-dark mb-3">{{ $course->title }}</h5>

                            <!-- Course Enrolled Info -->
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $course->pivot->created_at ? $course->pivot->created_at->format('M d, Y') : 'Enrolled' }}
                                </small>
                                <a href="{{ route('courses.units', $course->id) }}" class="btn-primary btn-sm rounded-3 px-3">
                                    View Class
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 text-center p-5">
            <div class="card-body">
                <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                    <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">No Enrolled Courses Found</h5>
                <p class="text-muted mb-4">You haven't enrolled in any courses yet.</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                    Explore Available Courses
                </a>
            </div>
        </div>
    @endif
</div>
@endsection