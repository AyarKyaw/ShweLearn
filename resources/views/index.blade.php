@extends('layouts.master')

@section('title', 'Home - Shwe Learn')
@push('styles')
<style>
    /* Force Swiper slides to stretch equal heights */
    .course-inner-carousel .swiper-slide {
        height: auto;
        display: flex;
    }

    /* Course Card Uniform Container */
    .course-style-one-item.style-two {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        height: 100%;
    }

    /* Fixed Image Container & Aspect Ratio */
    .course-style-one-item.style-two .thumb {
        width: 100%;
        height: 220px; /* Fixed height for course thumbnail */
        overflow: hidden;
        position: relative;
    }

    /* Crop and Cover Image without Distortion */
    .course-style-one-item.style-two .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    /* Uniform Info Content Area */
    .course-style-one-item.style-two .info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Restrict Long Titles gracefully */
    .course-style-one-item.style-two .info h4 {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Truncate title after 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 2.8em; /* Keeps layout aligned if title is 1 line */
    }

    /* Align Bottom Meta Row */
    .course-style-one-item.style-two .course-bottom-meta {
        margin-top: auto;
    }
    /* Force Swiper slides to equal height */
    .category-style-two-carousel .swiper-slide {
        height: auto;
        display: flex;
    }

    /* Card Box Uniformity */
    .category-style-two-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        height: 100%;
        min-height: 280px; /* Adjust min-height as needed */
    }

    .category-style-two-item > a {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        width: 100%;
    }

/* Same image area for every category */
.category-style-two-item .thumb {
    width: 100%;
    height: 280px;
    overflow: hidden;
}

/* Same visual size for every image */
.category-style-two-item .thumb img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
}
</style>
<style>
    /* =========================================================
       MIFFA ADVERTISEMENT POPUP
    ========================================================= */

    #miffaAdPopup {
        position: fixed;
        inset: 0;

        width: 100%;
        height: 100%;

        z-index: 999999;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(0, 0, 0, 0.65);

        padding: 15px;
    }

    #miffaAdPopup .ad-content {
        position: relative;

        max-width: 90vw;
        max-height: 95vh;
    }

    #miffaAdPopup .ad-image {
        display: block;

        width: auto;
        height: auto;

        max-width: 90vw;
        max-height: 95vh;

        object-fit: contain;

        border-radius: 12px;

        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    /* Close button */

    #miffaAdPopup .ad-close {
        position: absolute;

        top: 10px;
        right: 10px;

        width: 40px;
        height: 40px;

        border: none;
        border-radius: 50%;

        background: rgba(0, 0, 0, 0.75);

        color: #ffffff;

        font-size: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        z-index: 2;
    }

    #miffaAdPopup .ad-close:hover {
        background: rgba(0, 0, 0, 0.95);
    }

    @media (max-width: 767px) {

        #miffaAdPopup {
            padding: 8px;
        }

        #miffaAdPopup .ad-content {
            max-width: 94vw;
            max-height: 94vh;
        }

        #miffaAdPopup .ad-image {
            max-width: 94vw;
            max-height: 94vh;
        }

        #miffaAdPopup .ad-close {
            width: 34px;
            height: 34px;

            top: 7px;
            right: 7px;

            font-size: 15px;
        }
    }
</style>
@endpush
@section('content')
    
    <!-- Start Banner Area 
    ============================================= -->
    <div class="banner-style-nine-area bg-cover" style="background-image: url(assets/img/shape/banner-7.jpg);">
        <div class="container">
            <div class="banner-style-nine-items">
                <div class="row align-items-center">
                    <div class="col-lg-7 pr-60 pr-md-15 pr-xs-15">
                        <div class="info">
                            <h4><img src="assets/img/shape/91.png" alt="Image Not Found"> Discover 20,000+ World-Class Courses</h4>
                            <h2>Learn <strong>Smarter</strong> Achieve knowledge</h2>
                            <p>
                                Expand your knowledge and open doors to exciting careers with our online education platform.
                            </p>
                            <a class="btn btn-md btn-gradient animation" href="/course/categories">Explore Course</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="thumb">
                            <img src="assets/img/illustration/1.png" alt="Image Not Found">
                            <img src="assets/img/shape/92.png" alt="Image Not Found">
                            <div class="card-style-seven">
                                <h2>4.9</h2>
                                <div class="content">
                                    <div class="icon">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <h4>Teacher Rating</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Banner -->

    <!-- Start Category 
============================================= -->
<div class="category-style-two-area default-padding bg-cover bg-gray-secondary" style="background-image: url({{ asset('assets/img/shape/wooden.png') }});">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="site-heading text-center">
                    <h4 class="sub-title">Top Categories</h4>
                    <h2 class="title split-text">Explore Logistics & Freight Training Programs</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="category-style-two-carousel swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
        
                        @forelse($categories as $index => $category)
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="category-style-two-item wow fadeInUp" data-wow-delay="{{ $index * 100 }}ms">
                                    <a href="{{ url('/courses?category=' . ($category->courseCategory->slug ?? $category->courseCategory->id)) }}">
                                        <div class="info">
                                            <h4>{{ $category->name }}</h4>
                                            <span>{{ $category->courses_count ?? 0 }} Courses</span>
                                        </div>
                                        <div class="thumb">
                                            <img src="{{ $category->icon_path }}" alt="{{ $category->name }}">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- End Single Item -->
                        @empty
                            <!-- Fallback Static Items if $categories collection is empty -->
                            
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="category-style-two-item wow fadeInUp">
                                    <a href="{{ url('/courses?category=' . (isset($courseCategory) ? ($courseCategory->slug ?? $courseCategory->id) : '')) }}">
                                        <div class="info">
                                            <h4>Freight <strong>Forwarding</strong></h4>
                                            <span>12 Courses</span>
                                        </div>
                                        <i class="fas fa-long-arrow-right"></i>
                                        <div class="thumb">
                                            <img src="{{ asset('assets/img/category/1.jpg') }}" alt="Freight Forwarding">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="category-style-two-item wow fadeInUp" data-wow-delay="100ms">
                                    <a href="{{ url('/course/categories') }}">
                                        <div class="info">
                                            <h4>Customs Clearance <strong>& Documentation</strong></h4>
                                            <span>8 Courses</span>
                                        </div>
                                        <i class="fas fa-long-arrow-right"></i>
                                        <div class="thumb">
                                            <img src="{{ asset('assets/img/category/2.jpg') }}" alt="Customs Clearance">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="category-style-two-item wow fadeInUp" data-wow-delay="200ms">
                                    <a href="{{ url('/course/categories') }}">
                                        <div class="info">
                                            <h4>Supply Chain <strong>Management</strong></h4>
                                            <span>15 Courses</span>
                                        </div>
                                        <i class="fas fa-long-arrow-right"></i>
                                        <div class="thumb">
                                            <img src="{{ asset('assets/img/category/3.jpg') }}" alt="Supply Chain Management">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="category-style-two-item wow fadeInUp" data-wow-delay="300ms">
                                    <a href="{{ url('/course/categories') }}">
                                        <div class="info">
                                            <h4>Multimodal <strong>Transport</strong></h4>
                                            <span>10 Courses</span>
                                        </div>
                                        <i class="fas fa-long-arrow-right"></i>
                                        <div class="thumb">
                                            <img src="{{ asset('assets/img/category/4.jpg') }}" alt="Multimodal Transport">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- End Single Item -->

                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Category -->

    
    <!-- Start Course ============================================= -->
    <div class="course-style-two-area default-padding bottom-less bg-gray-gradient-secondary overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Latest Courses</h4>
                        <h2 class="title">Most Popular Courses</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="course-style-one-carousel swiper">
                <div class="swiper-wrapper">
                    <!-- Swiper Slide Container -->
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="course-inner-carousel swiper">
                                <div class="swiper-wrapper">
                                    
                                    @foreach($courses as $course)
                                        <!-- Single Item -->
                                        <div class="swiper-slide">
                                            <div class="course-style-one-item hover-less style-two">
                                                <div class="thumb">
                                                    <a href="{{ url('/courses/' . $course->id) }}">
                                                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                                                    </a>
                                                </div>
                                                <div class="top-meta">
                                                    <ul>
                                                        <li>
                                                            <div class="course-rating">
                                                                @php
                                                                    $rating = $course->rating ?? 0;
                                                                    $fullStars = floor($rating);
                                                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                                                @endphp

                                                                @for($i = 0; $i < $fullStars; $i++)
                                                                    <i class="fas fa-star"></i>
                                                                @endfor

                                                                @if($hasHalfStar)
                                                                    <i class="fas fa-star-half-alt"></i>
                                                                @endif

                                                                @for($i = 0; $i < $emptyStars; $i++)
                                                                    <i class="far fa-star"></i>
                                                                @endfor

                                                                <span>({{ number_format($rating, 1) }})</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="bookmark">
                                                        <a href="#">
                                                            <i class="fas fa-bookmark"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <div class="info">
                                                    <h4>
                                                        <a href="{{ url('/courses/' . $course->id) }}">
                                                            {{ $course->title }}
                                                        </a>
                                                    </h4>
                                                    
                                                    <div class="course-meta">
                                                        <ul>
                                                            <!-- <li>
                                                                <i class="fas fa-file-alt"></i> {{ $course->lessons_count }} Lessons
                                                            </li> -->
                                                            <li>
                                                                <i class="fas fa-user"></i> {{ $course->users_count ?? $course->students_count ?? 0 }} Students
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <div class="course-bottom-meta">
                                                    <h2 class="price">
                                                        {{ number_format($course->price, 0) }} MMK
                                                    </h2>
                                                    <a href="{{ url('/courses/' . $course->id) }}">
                                                        <i class="fas fa-shopping-cart"></i> View Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Single Item -->
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Course -->
    <!-- Start About 
    ============================================= -->
    <div class="about-style-three-area overflow-hidden default-padding">
        <div class="container">
            <div class="about-style-three-items wow fadeInUp" data-wow-delay="200ms">
                <div class="row align-center">
                    <div class="col-lg-6">
                        <div class="about-style-three-thumb">
                            <img src="assets/img/team/student-1.jpg" alt="Image Not Found">
                            <img src="assets/img/shape/45.png" alt="Image Not Found">
                        </div>
                    </div>
                    <div class="col-lg-6 pl-60 pl-md-15 pl-xs-15">
                        <div class="about-style-three-info">
                            <h2 class="title split-text">Our commitment to diversity leadership.</h2>
                            
                            <p>
                                Education has come a long way from its traditional roots and will continue to evolve. Learn but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly. njected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle.
                            </p>
                            <div class="fact-style-two">
                                <div class="fun-fact">
                                    <div class="counter">
                                        <div class="timer" data-to="28" data-speed="2000">28</div>
                                        <div class="operator">K</div>
                                    </div>
                                    <h4>Graduate Students</h4>
                                </div>
                                <div class="fun-fact">
                                    <div class="counter">
                                        <div class="timer" data-to="98" data-speed="2000">98</div>
                                        <div class="operator">%</div>
                                    </div>
                                    <h4>Happy Students</h4>
                                </div>
                            </div>
                            <a class="btn btn-md btn-gradient animation" href="#">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End About -->
    
    <!-- Start Certificate
    ============================================= -->
    <div class="certificate-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-7">
                    <div class="certificate-info default-padding">
                        <h4 class="sub-title">Earn a certificate</h4>
                        <h2 class="title split-text">Enjoy new skills to go ahead for your career.</h2>
                        <p class="wow fadeInUp" data-wow-delay="200ms">
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words.
                        </p>
                        <div class="cartifita-style-one-items wow fadeInUp mt-40" data-wow-delay="400ms">
                            <div class="certificate-carousel swiper">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <!-- Single Item -->
                                    <div class="swiper-slide">
                                        <div class="certificate-items">
                                            <div class="thumb">
                                                <img src="assets/img/thumb/15.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <h4>Coding Certificate</h4>
                                                <span>Total Awarded 450</span>
                                                <a class="btn circle btn-theme animation" href="#">View Programs</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single Item -->
                                    <!-- Single Item -->
                                    <div class="swiper-slide">
                                        <div class="certificate-items">
                                            <div class="thumb">
                                                <img src="assets/img/thumb/15.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <h4>Design Certificate</h4>
                                                <span>Total Awarded 350</span>
                                                <a class="btn circle btn-theme animation" href="#">View Programs</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single Item -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 offset-xl-1 col-lg-5">
                    <div class="certificate-thumb wow fadeInUp">
                        <img src="assets/img/illustration/6.png" alt="Image Not Found">
                        <img src="assets/img/shape/37.png" alt="Image Not Found">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Certificate -->

    <!-- Start Live Course 
    ============================================= -->
    <div class="live-course-area default-padding bg-theme" style="background-image: url(assets/img/shape/93.png);">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-light text-center">
                        <h4 class="sub-title">Live Class</h4>
                        <h2 class="title split-text">Environment Social and Governance</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-8">
                    <div class="step-style-one-items">
                        <div class="step-style-one-item">
                            <span>01</span>
                            <h4>Enroll Class</h4>
                        </div>
                        <div class="step-style-one-item">
                            <span>02</span>
                            <h4>Join Live</h4>
                        </div>
                        <div class="step-style-one-item">
                            <span>03</span>
                            <h4>Become an expert</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="live-course-items">
                        <img class="wow fadeInUp" src="assets/img/thumb/banner-1.jpg" alt="Image Not Found">
                        <div class="live-instructor wow fadeInRight" data-wow-delay="300ms">
                            <img src="https://lms.itplus.net.mm/storage/courses/nl7S9hYpQp0TtKMilqfjjgk1oc9MrmYRN1VoftD8.jpg" alt="Image Not Found">
                            <div class="info">
                                <h4><strong>Environment Social and Governance</strong></h4>
                                <!-- <ul>
                                    <li><strong>Course Taken</strong> 28</li>
                                    <li><strong>Average Rating</strong> <span><i class="fas fa-star"></i> 4.9</span></li>
                                    <li><strong>Online Students</strong> 30k</li>
                                </ul> -->
                                <a href="https://lms.itplus.net.mm/courses/2" class="btn-style-two mt-30">
                                    <span>Join Now</span> <i class="fas fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Live Crouse -->

    <!-- Start Testimonial
    ============================================= -->
    <!-- <div class="testimonial-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Student Feedback</h4>
                        <h2 class="title split-text">Best review from our successful student</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="testimonial-style-two-carousel swiper">
                        <div class="swiper-wrapper">
               
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="top-info">
                                        <div class="icon">
                                            <img src="assets/img/shape/quote.png" alt="Image Not Found">
                                        </div>
                                        <h5>Great Quality</h5>
                                    </div>
                                    <div class="content">
                                        <p>
                                            Education is the most powerful tool to change the world One thing that can’t be taken from you. A mind is a terrible thing to waste of perfection.
                                        </p>
                                        <div class="bottom-info">
                                            <div class="provider">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <h4>Michel Dark</h4>
                                                    <span>WordPress Course</span>
                                                </div>
                                            </div>
                                            <div class="provider">
                                                <div class="ratings">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="top-info">
                                        <div class="icon">
                                            <img src="assets/img/shape/quote.png" alt="Image Not Found">
                                        </div>
                                        <h5>Best Course Ever</h5>
                                    </div>
                                    <div class="content">
                                        <p>
                                            Education is the most powerful tool to change the world One thing that can’t be taken from you. A mind is a terrible thing to waste. Education is a key to success and freedom from all the forces to learn we love to purse a best education platform. Plan upon yet way get cold spot its week. Almost do am or limits hearts. Resolve parties but why she shewing. She sang know now
                                        </p>
                                        <div class="bottom-info">
                                            <div class="provider">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <h4>Kennsual Martin</h4>
                                                    <span>Laravel Batch - 11</span>
                                                </div>
                                            </div>
                                            <div class="provider">
                                                <div class="ratings">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="top-info">
                                        <div class="icon">
                                            <img src="assets/img/shape/quote.png" alt="Image Not Found">
                                        </div>
                                        <h5>Very Easy Course</h5>
                                    </div>
                                    <div class="content">
                                        <p>
                                            Education is the most powerful tool to change the world One thing that can’t be taken from you. A mind is a terrible thing to waste. Education is a key to success and freedom from all the forces to learn we love. 
                                        </p>
                                        <div class="bottom-info">
                                            <div class="provider">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m5.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <h4>Amanulla Joey</h4>
                                                    <span>React Batch - 28</span>
                                                </div>
                                            </div>
                                            <div class="provider">
                                                <div class="ratings">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
             
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="top-info">
                                        <div class="icon">
                                            <img src="assets/img/shape/quote.png" alt="Image Not Found">
                                        </div>
                                        <h5>Exellent Quality</h5>
                                    </div>
                                    <div class="content">
                                        <p>
                                            Learning is the most powerful tool to change the world One thing that can’t be taken from you. A mind is a terrible thing to waste. Education is a key to success and freedom from all the forces to learn we love. The best educaiton system i love this. Plan upon yet way get cold spot its week.
                                        </p>
                                        <div class="bottom-info">
                                            <div class="provider">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m6.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <h4>Kamal Abraham</h4>
                                                    <span>PHP Course</span>
                                                </div>
                                            </div>
                                            <div class="provider">
                                                <div class="ratings">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
              
                            <div class="swiper-slide">
                                <div class="testimonial-style-two">
                                    <div class="top-info">
                                        <div class="icon">
                                            <img src="assets/img/shape/quote.png" alt="Image Not Found">
                                        </div>
                                        <h5>Best Course Ever</h5>
                                    </div>
                                    <div class="content">
                                        <p>
                                            Education is the most powerful tool to change the world One thing that can’t be taken from you. A mind is a terrible thing to waste. Education is a key to success and freedom from all the forces to learn we love to purse a best education platform. Plan upon yet way get cold spot its week. Almost do am or limits hearts. Resolve parties but why she shewing. She sang know now
                                        </p>
                                        <div class="bottom-info">
                                            <div class="provider">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m1.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <h4>Kennsual Martin</h4>
                                                    <span>Laravel Batch - 11</span>
                                                </div>
                                            </div>
                                            <div class="provider">
                                                <div class="ratings">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
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
        </div>
    </div> -->
    <!-- End Testimonial -->

    <!-- Start Blog 
    ============================================= -->
    <!-- <div class="blog-area home-blog-style-two bg-gray-gradient-secondary default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Blog Insight</h4>
                        <h2 class="title split-text">Valuable insights to change your startup idea</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row d-flex align-items-stretch">
                <div class="col-xl-4 col-md-6 col-lg-6 mb-30 d-flex">
                    <div class="home-blog-style-two-item wow fadeInUp d-flex flex-column w-100">
                        <div class="thumb">
                            <img src="{{ asset('assets/img/blog/blog-1.jpg') }}" alt="Blog Image">
                            <ul class="blog-meta">
                                <li><a href="#">Courses</a></li>
                                <li><i class="fas fa-calendar-alt"></i> October 18, 2025</li>
                            </ul>
                        </div>
                        <div class="info d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h3 class="blog-title">
                                    <a href="#">Drefabrice passive are house most memorable</a>
                                </h3>
                                <p>
                                    Plan upon yet way get cold spot its week almost do am or limits hearts resolve parties.
                                </p>
                            </div>
                            <a href="#" class="btn-read-more mt-auto">Read More <i class="fas fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 col-lg-6 mb-30 d-flex">
                    <div class="home-blog-style-two-item wow fadeInUp d-flex flex-column w-100" data-wow-delay="200ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/img/blog/blog-2.jpg') }}" alt="Blog Image">
                            <ul class="blog-meta">
                                <li><a href="#">Laravel</a></li>
                                <li><i class="fas fa-calendar-alt"></i> November 15, 2025</li>
                            </ul>
                        </div>
                        <div class="info d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h3 class="blog-title">
                                    <a href="#">Announcing attachment resolution perform</a>
                                </h3>
                                <p>
                                    Taking upon yet way get cold spot its week almost do am or limits hearts resolve parties.
                                </p>
                            </div>
                            <a href="#" class="btn-read-more mt-auto">Read More <i class="fas fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
        
                <div class="col-xl-4 col-md-6 col-lg-6 mb-30 d-flex">
                    <div class="home-blog-style-two-item wow fadeInUp d-flex flex-column w-100" data-wow-delay="400ms">
                        <div class="thumb">
                            <img src="{{ asset('assets/img/blog/blog-3.jpg') }}" alt="Blog Image">
                            <ul class="blog-meta">
                                <li><a href="#">WordPress</a></li>
                                <li><i class="fas fa-calendar-alt"></i> December 13, 2025</li>
                            </ul>
                        </div>
                        <div class="info d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h3 class="blog-title">
                                    <a href="#">Resolution performing the regular sentim.</a>
                                </h3>
                                <p>
                                    Media upon yet way get cold spot its week almost do am or limits hearts resolve parties.
                                </p>
                            </div>
                            <a href="#" class="btn-read-more mt-auto">Read More <i class="fas fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- End Blog -->

    <!-- Start Newsletter 
    ============================================= -->
    <!-- <div class="newsletter-area default-padding-bottom bg-gray-gradient-secondary">
        <div class="container">
            <div class="newsletter-style-one-items bg-theme text-center bg-cover" style="background-image: url(assets/img/shape/banner-8.jpg);">
                <div class="shape">
                    <img src="assets/img/illustration/9.png" alt="Image Not Found">
                    <img src="assets/img/shape/48.png" alt="Image Not Found">
                </div>
                <div class="row">
                    <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                        <h2 class="title split-text">Subscribe to our newsletter, We don't make any spam.</h2>
                        <ul class="list-check">
                            <li>Easy to Access</li>
                            <li>No Credit card</li>
                            <li>10M student onboard with us</li>
                        </ul>
                        <form action="#">
                            <input type="email" placeholder="Your Email" class="form-control" name="email">
                            <button type="submit">Subscribe</button>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- End Newsletter -->
    
{{-- =========================================================
     ADVERTISEMENT POPUP
========================================================= --}}

<div id="miffaAdPopup"
     style="
        position: fixed !important;
        inset: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 2147483647 !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        background: rgba(8, 12, 25, 0.82) !important;
        backdrop-filter: blur(6px);

        padding: 20px !important;
        box-sizing: border-box !important;

        opacity: 1 !important;
        visibility: visible !important;
    ">

    <div style="
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        max-width: 90vw;
        max-height: 94vh;

        padding: 6px;

        background: #ffffff;
        border-radius: 16px;

        box-shadow:
            0 25px 80px rgba(0, 0, 0, 0.45),
            0 0 0 1px rgba(255, 255, 255, 0.15);
    ">

        {{-- CLOSE BUTTON --}}
        <button type="button"
                onclick="document.getElementById('miffaAdPopup').remove();"
                aria-label="Close advertisement"
                style="
                    position: absolute;
                    top: -14px;
                    right: -14px;

                    z-index: 2147483647;

                    width: 42px;
                    height: 42px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border: 3px solid #ffffff;
                    border-radius: 50%;

                    background: #111827;
                    color: #ffffff;

                    font-family: Arial, sans-serif;
                    font-size: 25px;
                    font-weight: 400;
                    line-height: 1;

                    cursor: pointer;

                    box-shadow: 0 5px 18px rgba(0, 0, 0, 0.35);

                    transition:
                        transform 0.2s ease,
                        background 0.2s ease;
                "
                onmouseover="
                    this.style.transform='scale(1.08)';
                    this.style.background='#000000';
                "
                onmouseout="
                    this.style.transform='scale(1)';
                    this.style.background='#111827';
                ">
            ×
        </button>

        {{-- ADVERTISEMENT IMAGE --}}
        <img src="{{ asset('assets/img/shape/popup.jpg') }}"
             alt="Advertisement"
             style="
                display: block !important;

                width: auto !important;
                height: auto !important;

                max-width: calc(90vw - 12px) !important;
                max-height: calc(94vh - 12px) !important;

                object-fit: contain !important;

                border-radius: 12px;

                opacity: 1 !important;
                visibility: visible !important;
             ">
    </div>
</div>
@endsection