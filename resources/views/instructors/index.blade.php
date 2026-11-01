@extends('layouts.master')

@section('title', 'Meet Our Expert Teachers - Shwe Learn')

@section('content')
<!-- Hero Section with Animated Soft Mesh & Grid Pattern -->
<section class="position-relative py-5 overflow-hidden hero-teachers-bg border-bottom">
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 hero-gradient-overlay pointer-events-none"></div>

    <div class="container position-relative z-1 py-4">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-3 py-2 rounded-pill mb-3 shadow-xs">
                    <i class="fas fa-graduation-cap me-1"></i> Shwe Learn Faculty & Experts
                </span>
                <h1 class="display-4 fw-black text-dark mb-3 tracking-tight">
                    Learn from Industry Leaders
                </h1>
                <p class="lead text-secondary mb-4 mx-auto style-lead">
                    Gain practical insights from experienced logistics professionals and accredited teachers dedicated to advancing your freight forwarding career.
                </p>

                <!-- Premium Floating Search Bar -->
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{ route('instructors.index') }}" method="GET">
                            <div class="p-2 bg-white rounded-pill shadow-xl border d-flex align-items-center search-wrapper">
                                <span class="ps-3 text-primary">
                                    <i class="fas fa-search fs-5"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control border-0 shadow-none bg-transparent ps-3 py-2 text-dark fs-6" 
                                       placeholder="Search teachers by name..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm d-flex align-items-center gap-2 btn-hover-slide" type="submit">
                                    <span>Search</span>
                                    <i class="fas fa-arrow-right small"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Directory Section -->
<section class="py-5 bg-light-subtle">
    <div class="container py-3">
        @if($instructors->count() > 0)

            <!-- Teachers Grid -->
            <div class="row g-4">
                @foreach($instructors as $instructor)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden teacher-card position-relative bg-white">
                            
                            <!-- Mini Hero Banner Canvas inside Card - Always using default banner image -->
                            <div class="card-banner-bg" style="background-image: url('{{ asset('assets/img/courses/teacher.jfif') }}');">
                                <div class="card-banner-overlay"></div>
                            </div>

                            <div class="card-body p-4 text-center d-flex flex-column pt-0 position-relative z-1">
                                <!-- Avatar Container overlapping the Mini Banner -->
                                <div class="position-relative mx-auto mb-3 avatar-wrapper">
                                    <img src="{{ $instructor->image ? Storage::url($instructor->image) : asset('assets/images/default-avatar.png') }}" 
                                         alt="{{ $instructor->name }}" 
                                         class="rounded-circle border border-4 border-white shadow-md object-fit-cover teacher-avatar" 
                                         width="110" 
                                         height="110">
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2" title="Active Faculty"></span>
                                </div>

                                <!-- Teacher Identity -->
                                <h4 class="fw-bold text-dark mb-1 teacher-name">{{ $instructor->name }}</h4>
                                <p class="text-primary fw-semibold small mb-3 text-uppercase tracking-wider">
                                    {{ $instructor->title ?? $instructor->designation ?? 'Teacher' }}
                                </p>

                                <!-- Specialization Badges -->
                                @if(!empty($instructor->specializations))
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                                        @foreach(array_slice($instructor->specializations, 0, 3) as $spec)
                                            <span class="badge bg-light-subtle text-secondary border extra-small fw-medium rounded-pill px-2 py-1">
                                                {{ $spec }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Biography Snippet -->
                                <p class="text-muted small mb-4 line-clamp-3 lh-relaxed px-2">
                                    {{ Str::limit($instructor->bio ?? 'Dedicated faculty member bringing decades of practical logistics experience to modern training programs.', 110) }}
                                </p>

                                <!-- Stats Container with Clean Divider -->
                                <div class="mt-auto">
                                    <div class="row g-0 py-2.5 mb-4 stats-container rounded-3 border">
                                        <div class="col-6 border-end">
                                            <span class="d-block text-muted extra-small fw-bold text-uppercase tracking-wider">Courses</span>
                                            <strong class="text-dark fs-6 fw-extrabold">
                                                {{ $instructor->courses_count ?? $instructor->courses->count() }}
                                            </strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="d-block text-muted extra-small fw-bold text-uppercase tracking-wider">Students</span>
                                            <strong class="text-dark fs-6 fw-extrabold">
                                                {{ number_format($instructor->students_count ?? 0) }}
                                            </strong>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('instructors.show', $instructor->id) }}" class="btn btn-outline-primary w-100 rounded-pill fw-semibold py-2.5 d-flex align-items-center justify-content-center gap-2 profile-btn">
                                        <span>View Full Profile</span>
                                        <i class="fas fa-arrow-right extra-small btn-arrow"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Previous / Next Controls -->
            <div class="mt-5 d-flex justify-content-center align-items-center gap-3">
                @if($instructors->onFirstPage())
                    <button class="btn btn-outline-secondary rounded-pill px-4 fw-semibold opacity-50" disabled>
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </button>
                @else
                    <a href="{{ $instructors->appends(request()->query())->previousPageUrl() }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </a>
                @endif

                <span class="text-muted small px-2">
                    Page <strong class="text-dark">{{ $instructors->currentPage() }}</strong> of <strong class="text-dark">{{ $instructors->lastPage() }}</strong>
                </span>

                @if($instructors->hasMorePages())
                    <a href="{{ $instructors->appends(request()->query())->nextPageUrl() }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                @else
                    <button class="btn btn-outline-secondary rounded-pill px-4 fw-semibold opacity-50" disabled>
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                @endif
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-5 my-4 bg-white rounded-4 border shadow-sm p-5 col-md-8 mx-auto">
                <div class="avatar-empty-state mx-auto mb-3 text-primary bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-user-slash fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark">No Teachers Found</h4>
                <p class="text-muted col-md-8 mx-auto mb-4">We couldn't find any teachers matching your search criteria. Try refining your keywords.</p>
                @if(request('search'))
                    <a href="{{ route('instructors.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="fas fa-undo me-1"></i> Clear Search
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Custom Styling Enhancements -->
<style>
    /* Typography Extra Weight */
    .fw-black { font-weight: 800; }
    .fw-extrabold { font-weight: 700; }

    /* Hero section styling */
    .hero-teachers-bg {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    }
    .hero-gradient-overlay {
        background: radial-gradient(circle at 50% 20%, rgba(13, 110, 253, 0.08) 0%, transparent 70%);
    }
    .style-lead {
        max-width: 680px;
    }
    .tracking-tight {
        letter-spacing: -0.03em;
    }
    .tracking-wider {
        letter-spacing: 0.06em;
    }

    /* Floating Search Bar */
    .shadow-xl {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.075) !important;
    }
    .search-wrapper {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .search-wrapper:focus-within {
        border-color: #0d6efd !important;
        box-shadow: 0 1rem 2.5rem rgba(13, 110, 253, 0.18) !important;
        transform: translateY(-2px);
    }

    /* Card Mini Hero Banner */
    .card-banner-bg {
        height: 90px;
        width: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
        transition: transform 0.5s ease;
    }
    .card-banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.35) 100%);
    }

    /* Avatar Positioning */
    .avatar-wrapper {
        margin-top: -55px;
    }
    .teacher-avatar {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Teacher Card Hover Animations */
    .teacher-card {
        transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .teacher-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1.25rem 2.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .teacher-card:hover .card-banner-bg {
        transform: scale(1.05);
    }
    .teacher-card:hover .teacher-avatar {
        transform: scale(1.04);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Stats Section Container */
    .stats-container {
        background-color: #f8f9fa;
        border-color: #f1f3f5 !important;
    }

    /* Button Micro-Interactions */
    .profile-btn {
        transition: all 0.25s ease;
    }
    .btn-arrow {
        transition: transform 0.25s ease;
    }
    .teacher-card:hover .profile-btn {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .teacher-card:hover .btn-arrow {
        transform: translateX(4px);
    }

    /* Typography Helpers */
    .extra-small {
        font-size: 0.725rem;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .lh-relaxed {
        line-height: 1.6;
    }
</style>
@endsection