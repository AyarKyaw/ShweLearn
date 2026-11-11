@extends('layouts.master')

@section('title', ($course->title ?? 'Course Details') . ' - Shwe Learn')
@push('styles')
<style>
    .course-single-meta .teachers-item {
    width: 100%;
}

.course-single-meta .teachers-header {
    margin-bottom: 20px;
}

.course-single-meta .teachers-header h4 {
    margin: 0;
}

/* Teacher grid */
.course-single-meta .teachers-list {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px 30px;
}

/* Teacher */
.course-single-meta .author-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    text-decoration: none;
}

/* Profile image */
.course-single-meta .author-profile .thumb {
    width: 55px;
    height: 55px;
    min-width: 55px;
    border-radius: 50%;
    overflow: hidden;
}

.course-single-meta .author-profile .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Name */
.course-single-meta .author-profile .desc {
    min-width: 0;
}

.course-single-meta .author-profile .desc span {
    display: block;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Tablet */
@media (max-width: 991px) {
    .course-single-meta .teachers-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Mobile */
@media (max-width: 575px) {
    .course-single-meta .teachers-list {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@section('content')
    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area text-center bg-gray-gradient-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>{{ $course->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                            @if(isset($course->category))
                                <li><a href="#">{{ $course->category->name }}</a></li>
                            @endif
                            <li class="active">{{ $course->code ?? 'Course Single' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Course Details 
    ============================================= -->
    <div class="course-detils-area course-details-two default-padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                </div>
            </div>
        </div>
        <div class="course-single-meta-box" style="padding-top: 20px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
<div class="course-single-meta">

    <div class="item author teachers-item">

    <!-- <div class="teachers-header">
        <h4>
            {{ Str::plural('Teacher', $course->instructors->count()) }}
        </h4>
    </div> -->

    <div class="teachers-list">

        @forelse ($course->instructors as $instructor)

            <a href="{{ route('instructors.show', $instructor->id) }}"
               class="author-profile">

                <div class="thumb">
                    <img
                        src="{{ $instructor->image
                            ? asset('storage/' . $instructor->image)
                            : asset('assets/img/team/default.jpg') }}"
                        alt="{{ $instructor->name }}"
                    >
                </div>

                <div class="desc">
                    <span>{{ $instructor->name }}</span>
                </div>

            </a>

        @empty

            <div class="author-profile">

                <div class="thumb">
                    <img
                        src="{{ asset('assets/img/team/default.jpg') }}"
                        alt="Shwe Learn Teacher"
                    >
                </div>

                <div class="desc">
                    <span>Shwe Learn Teacher</span>
                </div>

            </div>

        @endforelse

    </div>

</div>

</div>


                            <!-- Category -->
                            <div class="item category">
                                <h4>Category</h4>
                                <a href="#">{{ $course->category->name ?? 'General' }}</a>
                            </div>

                            <!-- Rating / Code -->
                            <!-- <div class="item rating">
                                <h4>Course Code</h4>
                                <span class="fw-bold text-dark">{{ $course->code ?? 'N/A' }}</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="course-details-items">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 pr-40 pr-md-15 pr-xs-15">
                        <div class="course-details-left-info">
                            <div class="course-details-info">
                                <ul class="nav nav-tabs course-details-navs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tabs_1" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="tab_1" aria-selected="true">
                                            Course Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_2" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="tab_2" aria-selected="false">
                                            Curriculum
                                        </button>
                                    </li>
                                    <!-- <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_3" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="tab_3" aria-selected="false">
                                            Advisor
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_4" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="tab_4" aria-selected="false">
                                            Reviews
                                        </button>
                                    </li> -->
                                </ul>

                                <div class="tab-content course-details-tab-content" id="myTabContent">
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tabs_1">
                                        <h2>About this course</h2>
                                        <div>
                                            {!! $course->desc !!}
                                        </div>

                                        <h2>What you’ll learn</h2>
                                        <ul class="list-style-one">
                                            @forelse($units as $unit)
                                                <li>{{ $unit->title }}</li>
                                            @empty
                                                <li>No units available for this course yet.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tabs_2">
                                        <div class="faq-style-one-items curriculum-accordion">
                                            <div class="accordion" id="faqAccordion">
                                                @foreach($units as $unit)
                                                    <div class="accordion-item faq-style-one">
                                                        <!-- Unit Header -->
                                                        <h2 class="accordion-header" id="headingUnit{{ $unit->id }}">
                                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                                                    type="button" 
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#collapseUnit{{ $unit->id }}" 
                                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                                                    aria-controls="collapseUnit{{ $unit->id }}">
                                                                {{ $unit->title }}
                                                            </button>
                                                        </h2>

                                                        <!-- Unit Dropdown Content -->
                                                        <div id="collapseUnit{{ $unit->id }}" 
                                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                                            aria-labelledby="headingUnit{{ $unit->id }}" 
                                                            data-bs-parent="#faqAccordion">
                                                            <div class="accordion-body">
                                                                <ul class="curriculum-list">
                                                                    @foreach($unit->sections as $index => $section)
                                                                        <li>
                                                                            <div class="left-content">
                                                                                <span>{{ sprintf('%02d', $index + 1) }}</span>
                                                                                <h5>
                                                                                    <a href="{{ $section->url ?? '#' }}">
                                                                                        <i class="fas {{ ($section->type ?? 'video') === 'file' ? 'fa-file' : 'fa-play-circle' }}"></i>
                                                                                        {{ $section->title }}
                                                                                    </a>
                                                                                </h5>
                                                                            </div>
                                                                            <div class="right-content">
                                                                                @if($section->is_preview)
                                                                                    <a href="{{ $section->preview_url ?? '#' }}">Preview</a>
                                                                                @else
                                                                                    <i class="fas fa-lock"></i>
                                                                                @endif
                                                                                <span>{{ $section->duration }}</span>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tabs_3">
                                        <div class="curriculum-advisor">
                                            <div class="thumb">
                                                <img src="https://validthemes.net/site-template/learna/assets/img/advisor/2.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <div class="top">
                                                    <h4>James William</h4>
                                                    <span>Graphics Designer</span>
                                                    <div class="reviews">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                        <span>(4.7/ 3 Reviews)</span>
                                                    </div>
                                                </div>
                                                <ul>
                                                    <li><i class="fas fa-play"></i> 12 Courses</li>
                                                    <li><i class="fas fa-users"></i> 24k Students</li>
                                                </ul>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in.
                                                </p>
                                            </div>
                                            <ul class="social">
                                                <li class="facebook">
                                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="#"><img src="assets/img/icon/x.png" alt="Image Not Found"></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="curriculum-advisor">
                                            <div class="thumb">
                                                <img src="https://validthemes.net/site-template/learna/assets/img/advisor/3.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <div class="top">
                                                    <h4>Jones Mark</h4>
                                                    <span>UX Designer</span>
                                                    <div class="reviews">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                        <span>(4.7/ 55 Reviews)</span>
                                                    </div>
                                                </div>
                                                <ul>
                                                    <li><i class="fas fa-play"></i> 35 Courses</li>
                                                    <li><i class="fas fa-users"></i> 56k Students</li>
                                                </ul>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in.
                                                </p>
                                            </div>
                                            <ul class="social">
                                                <li class="facebook">
                                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="#"><img src="assets/img/icon/x.png" alt="Image Not Found"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tabs_4">
                                        <div class="curriculum-review">
                                            <div class="curriculum-review-item">
                                                <div class="total-review">
                                                    <h2>4.9</h2>
                                                    <h4>Wonderful</h4>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                    </div>
                                                    <span>(2.5k Reviews)</span>
                                                </div>
                                                <div class="review-count">
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-five">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-90"></div>
                                                        <span>90%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-four">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-80"></div>
                                                        <span>80%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-three">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-70"></div>
                                                        <span>70%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-two">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-60"></div>
                                                        <span>60%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-one">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-10"></div>
                                                        <span>10%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-review-section mt-80 mt-xs-50">
                                            <!-- Single Item -->
                                            <div class="course-review-item-one">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <div class="top">
                                                        <h4>Nigar Sultrua</h4>
                                                        <span>15 December, 2025</span>
                                                    </div>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                    </div>
                                                    <p>
                                                        Agreeable law unwilling sir deficient curiosity instantly. Easy mind life fact with see has bore ten. Parish any chatty can elinor direct for former. Up as meant widow equal an share least.
                                                    </p>
                                                    <div class="bottom">
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fas fa-thumbs-up"></i> 202</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fas fa-reply"></i> Reply</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Item -->
                                            <!-- Single Item -->
                                            <div class="course-review-item-one">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <div class="top">
                                                        <h4>Sarahub Albert</h4>
                                                        <span>18 October, 2025</span>
                                                    </div>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <p>
                                                        Agreeable law unwilling sir deficient curiosity instantly. Easy mind life fact with see has bore ten. Parish any chatty can elinor direct for former. Up as meant widow equal an share least.
                                                    </p>
                                                    <div class="bottom">
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fas fa-thumbs-up"></i> 658</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fas fa-reply"></i> Reply</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Item -->
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="course-details-category" style="top: -60px !important">
                            <!-- Single Item -->
                            <div class="course-cat-single">
                                <div class="course-preview-info style-two">
                                    <div class="thumb">
                                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('assets/img/courses/13.jpg') }}" alt="{{ $course->title }}" alt="{{ $course->title }}">
                                        @if(!empty($course->video_url))
                                            <a href="{{ $course->video_url }}" class="popup-youtube light video-play-button item-center">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="content">
                                        <div class="top">
                                            <div class="pricce">
                                                <h2>
                                                    @if(isset($course->price) && $course->price > 0)
                                                        {{ number_format($course->price, 0) }} MMK
                                                    @else
                                                        Free
                                                    @endif
                                                </h2>
                                            </div>
                                            @php
                                                $isEnrolled = in_array($course->id, $enrolledCourseIds ?? []);
                                            @endphp
                                            @if($isEnrolled)
                                                <a class="btn btn-gradient animation btn-sm" href="{{ route('courses.show', $course->id) }}">
                                                    <i class="fas fa-check-circle"></i> Already Enrolled
                                                </a>
                                            @else
                                                <a class="btn btn-gradient animation btn-sm" href="{{ route('enroll.index', $course->id) }}">
                                                    Buy Now
                                                </a>
                                            @endif
                                        </div>
                                        <div class="course-includes">
                                            <div class="info">
                                                <ul>
                                                    <!-- <li>
                                                        <i class="fas fa-code"></i> Course Code <span>{{ $course->code ?? 'N/A' }}</span>
                                                    </li> -->
                                                    <li>
                                                        <i class="fas fa-clock"></i> Duration <span>{{ $course->hour ?? '0' }} Hours</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-tag"></i> Category <span>{{ $course->category?->name ?? 'General' }}</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-sliders-h"></i> Skill level <span>All Levels</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-language"></i> Language <span>English</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Course Details -->

    <!-- Start Related Course 
    ============================================= -->
    <!-- <div class="related-course-area overflow-hidden default-padding-bottom">
        <div class="container">
            <div class="heading-left">
                <div class="row">
                    <div class="col-lg-7">
                        <h4 class="sub-title">Related Course</h4>
                        <h2 class="title">You might be interested in</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="related-course-carousel swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/1.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m1.jpg" alt="Image Not Found">
                                            <a href="#">Alsha Brown</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Development</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star-half-alt"></i>  
                                                <span>(2.7K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">WordPress and WooCommerce complete development.</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 78 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$42</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/2.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            <a href="#">Kevin Martin</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Programming</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i>  
                                                <span>(2.4K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">English grammar courses online with real certificates</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 128 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 2K Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$27</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/3.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Accounting</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star-half-alt"></i>  
                                                <span>(4.1K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">Complete React Front-end developer course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 69 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 246 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$38</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/4.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">WordPress</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i>  
                                                <span>(1.7K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">Basic to Advance UX &amp; UI Design and live Training Course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 38 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 154 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$26</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection