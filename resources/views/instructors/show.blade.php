@extends('layouts.master')

@section('title', $instructor->name . ' - Teacher Profile')

@push('styles')
<style>
    /* Hero Banner Wrapper - Matched to 24:10 ratio */
    .instructor-banner {
        width: 100%;
        aspect-ratio: 24 / 10;      /* Matches the 1200x500 Cropper export ratio */
        background-color: #1a1d20;
        background-size: cover;     /* Fills the container edge-to-edge */
        background-repeat: no-repeat;
        background-position: center;
        position: relative;
    }
    
    .instructor-banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.45) 100%);
        pointer-events: none;
    }

    .instructor-profile-header {
        margin-top: -60px;
        position: relative;
        z-index: 2;
    }

    /* Avatar Container */
    .avatar-wrapper {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        overflow: hidden;
        background-color: #f8f9fa;
    }

    /* Avatar Image */
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 767.98px) {
        .instructor-banner {
            aspect-ratio: 16 / 9; /* Slightly taller aspect on mobile screens for better visibility */
        }
        .instructor-profile-header {
            margin-top: -40px;
        }
        .avatar-wrapper {
            width: 110px;
            height: 110px;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-light pb-5">
    <!-- Always using default banner image -->
    <div class="instructor-banner" 
         style="background-image: url('{{ asset('assets/img/courses/teacher.jfif') }}');">
        <div class="instructor-banner-overlay"></div>
    </div>

    <div class="container">
        <div class="card border-0 shadow-sm overflow-hidden mb-4 instructor-profile-header">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-auto">
                        <div class="avatar-wrapper">
                            @if($instructor->image)
                                <img src="{{ asset('storage/' . $instructor->image) }}" 
                                     alt="{{ $instructor->name }}">
                            @else
                                <div class="w-100 h-100 bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="font-size: 3rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                <h1 class="h2 fw-bold mb-1">{{ $instructor->name }}</h1>
                                <p class="text-primary fw-medium mb-2">
                                    {{ $instructor->title ?? 'Teacher' }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                @if(!empty($instructor->social_links['linkedin']))
                                    <a href="{{ $instructor->social_links['linkedin'] }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                                @if(!empty($instructor->social_links['twitter']))
                                    <a href="{{ $instructor->social_links['twitter'] }}" target="_blank" class="btn btn-outline-info btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="Twitter">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                @endif
                                @if(!empty($instructor->email))
                                    <a href="mailto:{{ $instructor->email }}" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-4 mt-3 pt-3 border-top">
                            <div>
                                <span class="d-block text-muted small">Courses</span>
                                <span class="h6 mb-0 fw-bold">{{ $instructor->courses_count ?? $instructor->courses->count() }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Total Students</span>
                                <span class="h6 mb-0 fw-bold">{{ number_format($instructor->students_count ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold border-bottom pb-3 mb-3">About the Teacher</h3>
                        <div class="text-secondary lh-base" style="white-space: pre-line;">
                            {{ $instructor->bio ?? 'No biography details available for this Teacher.' }}
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold border-bottom pb-3 mb-4">
                            Courses Taught by {{ $instructor->name }}
                        </h3>

                        @if(isset($instructor->courses) && $instructor->courses->count() > 0)
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                @foreach($instructor->courses as $course)
                                    <div class="col">
                                        <div class="card h-100 border border-light shadow-sm hover-shadow transition">
                                            @if($course->image)
                                                <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: contain; background-color: #f8f9fa;">
                                            @else
                                                <div class="bg-secondary text-white text-center py-5 card-img-top">
                                                    <i class="fas fa-book-open fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <span class="badge bg-primary-subtle text-primary align-self-start mb-2">
                                                    {{ $course->category->name ?? 'Logistics' }}
                                                </span>
                                                <h4 class="h6 card-title fw-bold text-dark mb-2">
                                                    <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" class="text-decoration-none text-dark">
                                                        {{ $course->title }}
                                                    </a>
                                                </h4>
                                                <p class="card-text text-muted small flex-grow-1">
                                                    {{ Str::limit($course->description, 80) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                                    <span class="text-muted small">
                                                        <i class="far fa-clock me-1"></i>{{ $course->duration ?? 'Self-paced' }}
                                                    </span>
                                                    <a href="{{ route('courses.show', $course->slug ?? $course->id) }}" class="btn btn-sm btn-outline-primary">
                                                        View Course
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No active courses available for this teacher at the moment.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection