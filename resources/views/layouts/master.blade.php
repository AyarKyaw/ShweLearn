<!DOCTYPE html>

<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shwe Learn">


<!-- ========== Page Title ========== -->
<title>@yield('title', 'Shwe Learn')</title>

<!-- ========== Favicon Icon ========== -->
<link rel="shortcut icon" href="{{ asset('assets/img/icon/Shwe Learns.png') }}" type="image/x-icon">

<!-- ========== Font Awesome CDN ========== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- ========== Stylesheets ========== -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/magnific-popup.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/validnavs.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/helper.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/unit-test.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

@stack('styles')

<style>
    a.alumni-btn {
        background-color: #05d5b3;
        color: #fff;
        border-radius: 20px;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    a.alumni-btn:hover {
        background-color: #04b89b !important;
        color: #ffffff !important;
    }

    .miffa-banner {
        position: relative;
        width: 100%;
        height: 240px;
        overflow: hidden;
    }

    .miffa-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.65) 0%,
            rgba(0, 0, 0, 0.30) 50%,
            rgba(0, 0, 0, 0.10) 100%
        );
        z-index: 1;
    }

    .miffa-banner img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

/* =========================================================
   MOBILE BOTTOM NAVIGATION
   FULL WIDTH
   ========================================================= */

.mobile-bottom-nav {
    position: fixed;

    left: 0;
    right: 0;
    bottom: 0;

    width: 100%;

    height: 68px;
    padding: 7px 8px;

    background: rgba(255, 255, 255, 0.98);

    border: none;
    border-top: 1px solid rgba(5, 213, 179, 0.12);

    border-radius: 0;

    display: flex;
    justify-content: space-around;
    align-items: center;

    box-shadow:
        0 -8px 25px rgba(0, 0, 0, 0.10),
        0 -2px 8px rgba(5, 213, 179, 0.06);

    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    z-index: 9999;

    transform: translateY(0);

    animation:
        mobileNavAppear 0.65s cubic-bezier(0.22, 1, 0.36, 1),
        mobileNavFloat 5s ease-in-out 1s infinite;
}


/* =========================================================
   TOP GLOWING LINE
========================================================= */

.mobile-bottom-nav::before {
    content: "";

    position: absolute;

    top: -1px;
    left: 20%;
    right: 20%;

    height: 2px;

    background: linear-gradient(
        90deg,
        transparent,
        #05d5b3,
        transparent
    );

    border-radius: 50%;

    opacity: 0.7;
}


/* =========================================================
   NAV ITEMS
========================================================= */

.mobile-bottom-nav a,
.mobile-bottom-nav button {
    position: relative;

    width: 20%;
    height: 54px;

    background: transparent;
    border: none;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    color: #7a8188;

    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.1px;

    text-decoration: none;
    outline: none;

    padding: 4px 2px;
    margin: 0;

    border-radius: 12px;

    transition:
        color 0.3s ease,
        transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
        background-color 0.3s ease;
}


/* =========================================================
   ICON
========================================================= */

.mobile-bottom-nav a i,
.mobile-bottom-nav button i {
    position: relative;

    font-size: 19px;
    margin-bottom: 4px;

    color: #444b52;

    transition:
        color 0.3s ease,
        transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
        filter 0.3s ease;
}


/* =========================================================
   ACTIVE ITEM
========================================================= */

.mobile-bottom-nav a.active {
    color: #05bfa1;

    background: rgba(5, 213, 179, 0.10);

    transform: translateY(-2px);
}


/* =========================================================
   ACTIVE ICON
========================================================= */

.mobile-bottom-nav a.active i {
    color: #05cdb0;

    transform: translateY(-2px) scale(1.12);

    filter:
        drop-shadow(
            0 4px 5px rgba(5, 213, 179, 0.25)
        );
}


/* =========================================================
   ACTIVE DOT
========================================================= */

.mobile-bottom-nav a.active::after {
    content: "";

    position: absolute;

    bottom: 2px;

    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: #05d5b3;

    box-shadow:
        0 0 0 3px rgba(5, 213, 179, 0.10),
        0 0 10px rgba(5, 213, 179, 0.45);

    animation:
        activeDot 0.45s cubic-bezier(
            0.34,
            1.56,
            0.64,
            1
        );
}


/* =========================================================
   HOVER
========================================================= */

.mobile-bottom-nav a:hover,
.mobile-bottom-nav button:hover {
    color: #05d5b3;
}


.mobile-bottom-nav a:hover i,
.mobile-bottom-nav button:hover i {
    color: #05d5b3;

    transform:
        translateY(-2px)
        scale(1.08);
}


/* =========================================================
   TOUCH
========================================================= */

.mobile-bottom-nav a:active,
.mobile-bottom-nav button:active {
    transform: scale(0.90);
}


/* =========================================================
   MENU BUTTON
========================================================= */

.mobile-bottom-nav button {
    cursor: pointer;
}


.mobile-bottom-nav button i {
    transition:
        transform 0.4s cubic-bezier(
            0.34,
            1.56,
            0.64,
            1
        ),
        color 0.3s ease;
}


.mobile-bottom-nav button:hover i {
    transform:
        rotate(8deg)
        scale(1.1);
}


/* =========================================================
   NAVIGATION ENTRANCE
========================================================= */

@keyframes mobileNavAppear {

    0% {
        opacity: 0;
        transform: translateY(35px);
    }

    70% {
        opacity: 1;
        transform: translateY(-3px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}


/* =========================================================
   ACTIVE DOT ENTRANCE
========================================================= */

@keyframes activeDot {

    0% {
        opacity: 0;
        transform: scale(0);
    }

    70% {
        opacity: 1;
        transform: scale(1.35);
    }

    100% {
        opacity: 1;
        transform: scale(1);
    }
}


/* =========================================================
   FLOATING ANIMATION
========================================================= */

@keyframes mobileNavFloat {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-1px);
    }
}


/* =========================================================
   CONTENT SPACE
========================================================= */

@media (max-width: 991px) {

    body {
        padding-bottom: 85px;
    }
}


/* =========================================================
   DESKTOP
========================================================= */

@media (min-width: 992px) {

    .mobile-bottom-nav {
        display: none !important;
    }
}


/* =========================================================
   SMALL PHONES
========================================================= */

@media (max-width: 380px) {

    .mobile-bottom-nav {

        height: 64px;

        padding: 5px 4px;

        border-radius: 0;
    }


    .mobile-bottom-nav a,
    .mobile-bottom-nav button {

        height: 50px;

        font-size: 9px;
    }


    .mobile-bottom-nav a i,
    .mobile-bottom-nav button i {

        font-size: 17px;
    }


    body {
        padding-bottom: 80px;
    }
}


/* =========================================================
   SAFE AREA - iPHONE
========================================================= */

@supports (padding-bottom: env(safe-area-inset-bottom)) {

    .mobile-bottom-nav {

        padding-bottom:
            calc(7px + env(safe-area-inset-bottom));

        height:
            calc(68px + env(safe-area-inset-bottom));
    }


    @media (max-width: 991px) {

        body {

            padding-bottom:
                calc(
                    85px +
                    env(safe-area-inset-bottom)
                );
        }
    }
}


/* =========================================================
   ACCESSIBILITY
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .mobile-bottom-nav,
    .mobile-bottom-nav a,
    .mobile-bottom-nav button,
    .mobile-bottom-nav i,
    .mobile-bottom-nav a.active::after {

        animation: none !important;
        transition: none !important;
    }
}
</style>


</head>

<body>


<!-- Start Preloader -->
<div id="preloader">
    <div id="edufix-preloader" class="edufix-preloader">
        <div class="animation-preloader">
            <div class="spinner"></div>

            <div class="txt-loading">
                <span data-text-preloader="S" class="letters-loading"> S </span>
                <span data-text-preloader="H" class="letters-loading"> H </span>
                <span data-text-preloader="W" class="letters-loading"> W </span>
                <span data-text-preloader="E" class="letters-loading"> E </span>
                <span data-text-preloader="L" class="letters-loading"> L </span>
                <span data-text-preloader="E" class="letters-loading"> E </span>
                <span data-text-preloader="A" class="letters-loading"> A </span>
                <span data-text-preloader="R" class="letters-loading"> R </span>
                <span data-text-preloader="N" class="letters-loading"> N </span>
            </div>
        </div>

        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>

                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>

                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>

                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Preloader -->

<!-- Start Header Top -->
<div class="top-bar-area top-bar-style-one bg-dark text-light">
    <div class="container">
        <div class="row align-center">

            <div class="col-lg-7">
                <ul class="item-flex">

                    <li>
                        <a href="tel:+095056924">
                            <img src="{{ asset('assets/img/icon/2.png') }}" alt="Icon">
                            Phone: 095056924
                        </a>
                    </li>

                    <li>
                        <a href="mailto:info.itplusmm@gmail.com">
                            <img src="{{ asset('assets/img/icon/3.png') }}" alt="Icon">
                            Email: info.itplusmm@gmail.com
                        </a>
                    </li>

                </ul>
            </div>

            <div class="col-lg-5 text-end">
                <div class="item-flex">

                    <!-- <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <img src="{{ asset('assets/img/icon/flag.png') }}" alt="Image Not Found">

                            English
                            <i class="fas fa-angle-down"></i>

                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>
                                <a class="dropdown-item" href="#">
                                    Myanmar
                                </a>
                            </li>
                        </ul>
                    </div> -->

                    <div>

                        @auth

                            <div class="d-inline-flex align-items-center gap-2">

                                <a href="{{ route('student.dashboard') }}"
                                    class="text-light text-decoration-none d-inline-flex align-items-center gap-1">

                                    <img src="{{ asset('assets/img/icon/1.png') }}" alt="Icon">

                                    {{ auth()->user()->name }}

                                </a>

                                <span class="text-light opacity-50">|</span>

                                <form method="POST"
                                    action="{{ route('logout') }}"
                                    class="d-inline m-0 p-0">

                                    @csrf

                                    <button type="submit"
                                        class="bg-transparent border-0 text-light p-0 m-0 align-baseline"
                                        style="box-shadow: none; font-size: inherit;">

                                        <i class="fas fa-sign-out-alt ms-1"></i>
                                        Logout

                                    </button>

                                </form>

                            </div>

                        @else

                            <a href="{{ url('/login') }}"
                                class="text-light text-decoration-none">

                                <img src="{{ asset('assets/img/icon/1.png') }}" alt="Icon">
                                Login

                            </a>

                        @endauth

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Header Top -->

<!-- Header -->
<header>

    <!-- Start Navigation -->
    <nav class="navbar mobile-sidenav navbar-sticky navbar-default validnavs dark navbar-fixed no-background inc-topbar">

        <div class="container d-flex justify-content-between align-items-center">

            <!-- Start Header Navigation -->
            <div class="item-flex">

                <div class="navbar-header">

                    <button type="button"
                        class="navbar-toggle"
                        data-toggle="collapse"
                        data-target="#navbar-menu">

                        <i class="fas fa-bars" aria-hidden="true"></i>

                    </button>

                    <a class="navbar-brand" href="{{ url('/') }}">

                        <!-- <img src="{{ asset('assets/img/new/logo-light.png') }}"
                            class="logo"
                            alt="Logo"> -->

                    </a>

                </div>

            </div>
            <!-- End Header Navigation -->

            <div class="nav-item-box d-flex justify-content-between align-items-center">

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                    <!-- <img src="{{ asset('assets/img/new/logo-light.png') }}"
                        alt="Logo"> -->

                    <button type="button"
                        class="navbar-toggle"
                        data-toggle="collapse"
                        data-target="#navbar-menu">

                        <i class="fas fa-times"></i>

                    </button>

                    <ul class="nav navbar-nav navbar-right"
                        data-in="fadeInDown"
                        data-out="fadeOutUp">

                        <li>
                            <a href="{{ url('/') }}">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/teachers') }}">
                                Teachers
                            </a>
                        </li>

                        <li class="dropdown megamenu-fw megamenu-style-four"
                            style="right: 0 !important;">

                            <a href="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown">

                                Courses

                            </a>

                            <ul class="dropdown-menu megamenu-content"
                                role="menu">

                                <li>

                                    <div class="col-menu-wrap">

                                        <div class="col-menu">

                                            <h6 class="title">
                                                Course Layout
                                            </h6>

                                            <div class="content">

                                                <ul class="menu-col">

                                                    <li>
                                                        <a href="{{ url('/course/categories') }}">
                                                            All Courses
                                                        </a>
                                                    </li>

                                                    @auth
                                                    <li>
                                                        <a href="{{ url('/my-courses') }}">
                                                            Enrolled Courses
                                                        </a>
                                                    </li>
                                                    @endauth
                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                </li>

                            </ul>

                        </li>

                        <!-- Join Alumni Navigation Link -->
                        <li>
                            @if (Auth::guard('alumni')->check())
                                <a class="alumni-btn" href="{{ route('alumni.dashboard') }}">
                                    Dashboard
                                </a>
                            @else
                                <a class="alumni-btn"
                                href="{{ Route::has('alumni.join') ? route('alumni.join') : url('/alumni/join') }}">
                                    Join Alumni
                                </a>
                            @endif
                        </li>

                        <!-- Mobile-only view items inside the slide-out menu -->

                        @auth

                            <li class="d-lg-none">

                                <a href="{{ route('student.dashboard') }}">

                                    <i class="fas fa-user-circle me-1"></i>

                                    Profile ({{ auth()->user()->name }})

                                </a>

                            </li>

                            <li class="d-lg-none">

                                <form method="POST"
                                    action="{{ route('logout') }}"
                                    class="px-3 py-2">

                                    @csrf

                                    <button type="submit"
                                        class="bg-transparent border-0 text-danger p-0 m-0 w-100 text-start"
                                        style="box-shadow: none;">

                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        Logout

                                    </button>

                                </form>

                            </li>

                        @else

                            <li class="d-lg-none">

                                <a href="{{ url('/login') }}">

                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    Login

                                </a>

                            </li>

                        @endauth

                    </ul>

                </div>

            </div>

        </div>

        <!-- Overlay screen for menu -->
        <div class="overlay-screen"></div>

    </nav>
    <!-- End Navigation -->

</header>
<!-- End Header -->

<!-- Start Banner -->
<!--
<div class="miffa-banner">
    <img src="{{ asset('assets/img/courses/hero_banner.jpg') }}" alt="MIFFA Banner">
</div>
-->
<!-- End Banner -->

{{-- Main View Content --}}
<main>
    @yield('content')
</main>

<!-- Start Footer -->
<footer class="bg-dark footer-style-one text-light">

    <div class="footer-shape-style-one">

        <img src="{{ asset('assets/img/shape/2-light.png') }}"
            alt="Image Not Found">

    </div>

    <div class="container">

        <div class="f-items default-padding">

            <div class="row">

                <div class="col-lg-4 col-md-6 footer-item pr-30 pr-md-15 pr-xs-15">

                    <div class="f-item about">

                        <div class="footer-logo">

                            <!-- <img src="{{ asset('assets/img/new/logo-light.png') }}"
                                alt="Image Not Found"> -->

                        </div>

                        <p>
                           SHWE LEARN is an online learning platform dedicated to providing accessible, practical, and high-quality education. We empower learners to develop new skills, expand their knowledge, and achieve their personal and professional goals.
                        </p>

                    </div>

                </div>

                <div class="col-lg-2 col-md-6 footer-item">

                    <div class="f-item link">

                        <h4 class="widget-title">
                            About
                        </h4>

                        <ul>

                            <li>
                                <a href="{{ url('/about') }}">
                                    About Us
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/course/categories') }}">
                                    Courses
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/teachers') }}">
                                    Teachers
                                </a>
                            </li>

                            <li>
                                @if (Auth::guard('alumni')->check())
                                <a href="{{ route('alumni.dashboard') }}">
                                    Dashboard
                                </a>
                                @else
                                    <a
                                    href="{{ Route::has('alumni.join') ? route('alumni.join') : url('/alumni/join') }}">
                                        Join Alumni
                                    </a>
                                @endif
                            </li>

                        </ul>

                    </div>

                </div>

                <div class="col-lg-4 col-md-6 footer-item">

                    <div class="f-item newsletter">

                        <h4 class="widget-title">
                            Contact Info
                        </h4>

                        <ul class="contact-list-two">

                            <li>

                                <div class="icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>

                                <div class="info">
                                    <h5>
                                        <a href="tel:+095056924">
                                            095056924
                                        </a>
                                    </h5>
                                </div>

                            </li>

                            <li>

                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>

                                <div class="info">
                                    <h5>
                                        <a href="mailto:info.itplusmm@gmail.com">
                                            info.itplusmm@gmail.com
                                        </a>
                                    </h5>
                                </div>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Start Footer Bottom -->
    <div class="footer-bottom style-one">

        <div class="container">

            <div class="row">

                <div class="col-lg-6">

                    <p>
                        &copy; {{ date('Y') }} Shwe Learn. All Rights Reserved.
                    </p>

                </div>

                <div class="col-lg-6 text-end">

                    <!-- <ul>

                        <li>
                            <a href="#">
                                Terms of Use
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Privacy Policy
                            </a>
                        </li>

                    </ul> -->

                </div>

            </div>

        </div>

    </div>
    <!-- End Footer Bottom -->

</footer>
<!-- End Footer -->


<!-- ========== Mobile Bottom Navigation Bar ========== -->

<div class="mobile-bottom-nav">

    {{-- Home --}}
    <a href="{{ url('/') }}"
       class="{{ request()->is('/') ? 'active' : '' }}">

        <i class="fas fa-home"></i>

        <span>Home</span>

    </a>


    {{-- Courses --}}
    <a href="{{ url('/course/categories') }}"
       class="{{ request()->is('course/categories*') ? 'active' : '' }}">

        <i class="fas fa-graduation-cap"></i>

        <span>Courses</span>

    </a>


    @auth

        {{-- Enrolled Courses --}}
        <a href="{{ url('/my-courses') }}"
           class="{{ request()->is('my-courses*') ? 'active' : '' }}">

            <i class="fas fa-book-reader"></i>

            <span>Enrolled</span>

        </a>


        {{-- Student Profile / Dashboard --}}
        <a href="{{ route('student.dashboard') }}"
           class="{{ request()->routeIs('student.dashboard') || request()->routeIs('student.dashboard.*') ? 'active' : '' }}">

            <i class="fas fa-user-circle"></i>

            <span>Profile</span>

        </a>

    @else

        {{-- Login for guests --}}
        <a href="{{ url('/login') }}"
           class="{{ request()->is('login') ? 'active' : '' }}">

            <i class="fas fa-user-circle"></i>

            <span>Login</span>

        </a>

    @endauth


    {{-- Teachers --}}
    <a href="{{ url('/teachers') }}"
       class="{{ request()->is('teachers*') ? 'active' : '' }}">

        <i class="fas fa-chalkboard-teacher"></i>

        <span>Teachers</span>

    </a>


    {{-- Menu --}}
    <button type="button"
            class="navbar-toggle"
            data-toggle="collapse"
            data-target="#navbar-menu">

        <i class="fas fa-bars"></i>

        <span>Menu</span>

    </button>

</div>

<!-- ========== End Mobile Bottom Navigation Bar ========== -->


<!-- ========== Scripts ========== -->

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
<script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/progress-bar.min.js') }}"></script>
<script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/count-to.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/YTPlayer.min.js') }}"></script>
<script src="{{ asset('assets/js/loopcounter.js') }}"></script>
<script src="{{ asset('assets/js/validnavs.js') }}"></script>
<script src="{{ asset('assets/js/gsap.js') }}"></script>
<script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('assets/js/SplitText.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

@stack('scripts')


</body>
</html>
