@extends('layouts.master')

@section('title', ($courseCategory->name ?? 'Courses') . ' - Shwe Learn')

@section('content')
<!-- Start Breadcrumb -->
<div class="breadcrumb-area text-center bg-gray-gradient-secondary">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $courseCategory->name ?? 'Courses' }}</h1>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ url('/') }}">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>

                        <li class="active">
                            {{ $courseCategory->name ?? 'Course' }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->


<!-- Start Course -->
<div class="course-tabs-area default-padding">
    <div class="container">

        <div class="course-tab-style-one">

            <div class="row">

                <!-- ==========================================
                     CATEGORY SIDEBAR
                =========================================== -->
                <div class="col-xl-4 col-lg-5">

                    <ul class="nav nav-tabs category-tabs wow fadeInLeft"
                        id="myTab"
                        role="tablist">

                        @forelse($categories as $category)

                            <li class="nav-item category-sidebar-item"
                                role="presentation">

                                @php
                                    $bgImage = $category->icon_path
                                        ?? $category->image
                                        ?? $category->icon
                                        ?? null;

                                    $bgUrl = $bgImage
                                        ? asset($bgImage)
                                        : asset('assets/img/icon/29.png');
                                @endphp

                                <button
                                    class="nav-link category-sidebar-card {{ $loop->first ? 'active' : '' }}"
                                    id="tabs_{{ $category->id }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab_{{ $category->id }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab_{{ $category->id }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    style="background-image: url('{{ $bgUrl }}');"
                                >

                                    <!-- Brightness layer -->
                                    <span class="category-brightness"></span>

                                    <!-- Soft readability overlay -->
                                    <span class="category-image-overlay"></span>

                                    <!-- Selected indicator -->
                                    <span class="category-selected">
                                        <i class="fas fa-check"></i>
                                    </span>

                                    <!-- Category content -->
                                    <span class="category-card-content">

                                        <strong>
                                            {{ $category->name }}
                                        </strong>

                                        <span class="category-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>

                                    </span>

                                </button>

                            </li>

                        @empty

                            <li class="nav-item">
                                <span class="nav-link">
                                    No categories available
                                </span>
                            </li>

                        @endforelse

                    </ul>

                </div>
                <!-- End Category Sidebar -->


                <!-- ==========================================
                     CATEGORY CONTENT
                =========================================== -->
                <div class="col-xl-7 offset-xl-1 col-lg-7">

                    <div class="tab-content category-tab-content wow fadeInUp"
                         data-wow-delay="400ms"
                         id="myTabContent">

                        @forelse($categories as $category)

                            <div
                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="tab_{{ $category->id }}"
                                role="tabpanel"
                                aria-labelledby="tabs_{{ $category->id }}"
                            >

                                @forelse($category->courses as $course)

                                    @php
                                        $isEnrolled = in_array(
                                            $course->id,
                                            $enrolledCourseIds ?? []
                                        );
                                    @endphp


                                    <!-- Single Course Item -->
                                    <div class="course-style-one-item hover-less list-layout mb-4">

                                        <div class="thumb">
                                            <img
                                                src="{{ asset('storage/' . $course->image) }}"
                                                alt="{{ $course->title }}"
                                            >
                                        </div>

                                        <div class="info">

                                            <h4>
                                                <a href="{{ route('courses.show', $course->id) }}">
                                                    {{ $course->title }}
                                                </a>
                                            </h4>

                                            <div class="course-meta">
                                                <ul>

                                                    <li>
                                                        <div class="course-rating">

                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>

                                                            <span>
                                                                ({{ $course->rating_count ?? '0' }})
                                                            </span>

                                                        </div>
                                                    </li>

                                                    <li>
                                                        <i class="fas fa-user"></i>

                                                        {{ $course->users_count
                                                            ?? $course->students_count
                                                            ?? 0 }}

                                                        Students
                                                    </li>

                                                </ul>
                                            </div>

                                            <div class="bottom-meta">

                                                @if($isEnrolled)

                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        Enrolled
                                                        <i class="fas fa-long-arrow-right"></i>
                                                    </a>

                                                @else

                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        Enroll Now
                                                        <i class="fas fa-long-arrow-right"></i>
                                                    </a>

                                                @endif

                                                <h2 class="price">

                                                    @if($course->old_price)

                                                        <del>
                                                            {{ number_format($course->old_price, 0) }}
                                                            MMK
                                                        </del>

                                                    @endif

                                                    {{ number_format($course->price, 0) }}
                                                    MMK

                                                </h2>

                                            </div>

                                        </div>

                                    </div>
                                    <!-- End Single Course Item -->


                                @empty

                                    <div class="alert alert-info text-center">
                                        No courses found in
                                        <strong>{{ $category->name }}</strong>.
                                    </div>

                                @endforelse

                            </div>

                        @empty

                            <div class="alert alert-warning text-center">
                                No categories found for this program.
                            </div>

                        @endforelse

                    </div>

                </div>
                <!-- End Category Content -->

            </div>

        </div>

    </div>
</div>
<!-- End Course -->
```

@endsection

@push('styles')

<style>

/* ==========================================================
   CATEGORY SIDEBAR
========================================================== */

.category-tabs {
    border: none !important;

    display: flex;
    flex-direction: column;

    gap: 18px;

    padding: 0;
}


/* ==========================================================
   SIDEBAR ITEM ANIMATION
========================================================== */

.category-sidebar-item {
    width: 100%;

    border: none !important;

    margin: 0 !important;

    opacity: 0;

    transform: translateX(-45px);

    animation: categorySlideIn 0.65s ease forwards;
}


.category-sidebar-item:nth-child(1) {
    animation-delay: .05s;
}

.category-sidebar-item:nth-child(2) {
    animation-delay: .12s;
}

.category-sidebar-item:nth-child(3) {
    animation-delay: .19s;
}

.category-sidebar-item:nth-child(4) {
    animation-delay: .26s;
}

.category-sidebar-item:nth-child(5) {
    animation-delay: .33s;
}

.category-sidebar-item:nth-child(6) {
    animation-delay: .40s;
}

.category-sidebar-item:nth-child(7) {
    animation-delay: .47s;
}

.category-sidebar-item:nth-child(8) {
    animation-delay: .54s;
}


/* ==========================================================
   CATEGORY CARD
========================================================== */

.category-sidebar-card {
    position: relative !important;

    width: 100% !important;

    min-height: 110px;

    padding: 0 !important;

    display: flex !important;

    align-items: center !important;

    overflow: hidden;

    border-radius: 15px !important;

    border: 3px solid transparent !important;

    background-size: cover !important;

    background-position: center !important;

    background-repeat: no-repeat !important;

    color: #ffffff !important;

    cursor: pointer;

    box-shadow:
        0 7px 20px rgba(0, 0, 0, 0.12);

    transition:
        transform .35s ease,
        box-shadow .35s ease,
        border-color .35s ease,
        filter .35s ease;
}


/* ==========================================================
   MAKE IMAGE BRIGHTER
========================================================== */

.category-brightness {
    position: absolute;

    inset: 0;

    z-index: 0;

    background: rgba(255, 255, 255, 0.20);

    transition:
        background .35s ease;
}


/* ==========================================================
   TEXT READABILITY
========================================================== */

.category-image-overlay {
    position: absolute;

    inset: 0;

    z-index: 1;

    background:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.20) 0%,
            rgba(0, 0, 0, 0.08) 55%,
            rgba(0, 0, 0, 0.02) 100%
        );

    transition:
        background .35s ease;
}


/* ==========================================================
   CONTENT
========================================================== */

.category-card-content {
    position: relative;

    z-index: 3;

    width: 100%;

    min-height: 110px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 24px 27px;
}


.category-card-content strong {
    color: #ffffff !important;

    font-size: 19px;

    font-weight: 700;

    line-height: 1.3;

    text-align: left;

    text-shadow:
        0 2px 5px rgba(0, 0, 0, .55);

    transition:
        transform .3s ease,
        font-size .3s ease;
}


/* ==========================================================
   ARROW
========================================================== */

.category-arrow {
    width: 42px;

    height: 42px;

    min-width: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: rgba(255, 255, 255, .95);

    color: #05d5b3;

    opacity: 0;

    transform: translateX(-15px);

    box-shadow:
        0 4px 12px rgba(0, 0, 0, .15);

    transition:
        opacity .3s ease,
        transform .3s ease;
}


/* ==========================================================
   SELECTED CHECK
========================================================== */

.category-selected {
    position: absolute;

    top: 12px;

    right: 12px;

    z-index: 5;

    width: 30px;

    height: 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #05d5b3;

    color: #ffffff;

    font-size: 13px;

    box-shadow:
        0 3px 10px rgba(0, 0, 0, .25);

    opacity: 0;

    transform: scale(.5);

    transition:
        opacity .3s ease,
        transform .3s ease;
}


/* ==========================================================
   HOVER
========================================================== */

.category-sidebar-card:hover {

    transform: translateX(8px) scale(1.015);

    box-shadow:
        0 13px 30px rgba(0, 0, 0, .22);

    filter: brightness(1.10);
}


.category-sidebar-card:hover .category-brightness {

    background: rgba(255, 255, 255, .30);
}


.category-sidebar-card:hover .category-card-content strong {

    transform: translateX(4px);
}


.category-sidebar-card:hover .category-arrow {

    opacity: 1;

    transform: translateX(0);
}


/* ==========================================================
   ACTIVE / SELECTED
========================================================== */

.category-sidebar-card.active {

    border-color: #05d5b3 !important;

    transform: translateX(8px) scale(1.025);

    filter: brightness(1.15);

    box-shadow:
        0 0 0 3px rgba(5, 213, 179, .20),
        0 14px 35px rgba(5, 213, 179, .32);
}


/* Stronger brightness for selected */

.category-sidebar-card.active .category-brightness {

    background: rgba(255, 255, 255, .32);
}


/* Selected overlay */

.category-sidebar-card.active .category-image-overlay {

    background:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, .08),
            rgba(0, 0, 0, .02)
        );
}


/* Selected text */

.category-sidebar-card.active .category-card-content strong {

    transform: translateX(5px);

    font-size: 20px;

    text-shadow:
        0 2px 6px rgba(0, 0, 0, .65);
}


/* Selected arrow */

.category-sidebar-card.active .category-arrow {

    opacity: 1;

    transform: translateX(0);

    background: #05d5b3;

    color: #ffffff;
}


/* Selected check */

.category-sidebar-card.active .category-selected {

    opacity: 1;

    transform: scale(1);
}


/* ==========================================================
   CLICK FEEDBACK
========================================================== */

.category-sidebar-card:active {

    transform: translateX(5px) scale(.99);

}


/* ==========================================================
   ANIMATION
========================================================== */

@keyframes categorySlideIn {

    0% {
        opacity: 0;
        transform: translateX(-45px);
    }

    65% {
        opacity: 1;
        transform: translateX(7px);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }

}


/* ==========================================================
   MOBILE
========================================================== */

@media (max-width: 991px) {

    .category-tabs {
        margin-bottom: 35px;
    }

    .category-sidebar-card {
        min-height: 95px;
    }

    .category-card-content {
        min-height: 95px;

        padding: 20px 23px;
    }

    .category-card-content strong {
        font-size: 17px;
    }

    .category-sidebar-card.active .category-card-content strong {
        font-size: 18px;
    }

}


@media (max-width: 575px) {

    .category-sidebar-card {
        min-height: 82px;
    }

    .category-card-content {
        min-height: 82px;

        padding: 17px 20px;
    }

    .category-card-content strong {
        font-size: 16px;
    }

    .category-sidebar-card.active .category-card-content strong {
        font-size: 17px;
    }

    .category-selected {
        width: 26px;
        height: 26px;

        top: 8px;
        right: 8px;
    }

}


/* ==========================================================
   REDUCE MOTION
========================================================== */

@media (prefers-reduced-motion: reduce) {

    .category-sidebar-item {
        animation: none;

        opacity: 1;

        transform: none;
    }

    .category-sidebar-card,
    .category-arrow,
    .category-selected,
    .category-card-content strong {
        transition: none;
    }

}

</style>

@endpush
