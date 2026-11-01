<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Shwe Learn">


    <!-- ========== Page Title ========== -->

    <title>
        @yield('title', 'Student Dashboard | Shwe Learn')
    </title>


    <!-- ========== Favicon ========== -->

    <link
        rel="shortcut icon"
        href="{{ asset('assets/img/icon/miffas.png') }}"
        type="image/x-icon"
    >


    <!-- ========== Font Awesome ========== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    >


    <!-- ========== Bootstrap ========== -->

    <link
        href="{{ asset('assets/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >


    <!-- ========== Main Site CSS ========== -->

    <link
        href="{{ asset('assets/css/magnific-popup.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/swiper-bundle.min.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/animate.min.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/validnavs.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/helper.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/unit-test.css') }}"
        rel="stylesheet"
    >

    <link
        href="{{ asset('assets/css/style.css') }}"
        rel="stylesheet"
    >


    @stack('styles')


    <style>

        :root {
            --miffa-primary: #0b3281;
            --miffa-secondary: #ff7a00;
            --miffa-bg: #f4f7f9;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;

            padding: 0;

            min-height: 100%;
        }


        body {

            background-color: var(--miffa-bg);

            font-family:
                system-ui,
                -apple-system,
                "Segoe UI",
                Roboto,
                sans-serif;

            overflow-x: hidden;
        }


        /* =====================================================
           STUDENT DASHBOARD SIDEBAR
        ===================================================== */

        .sidebar {

            min-height: 100vh;

            background-color: #ffffff;

            border-right: 1px solid #e9ecef;
        }


        .sidebar-brand {

            color: var(--miffa-primary);

            font-weight: 800;

            font-size: 1.2rem;

            letter-spacing: 0.5px;
        }


        .sidebar-brand:hover {

            color: var(--miffa-primary);
        }


        .nav-link-custom {

            color: #6c757d;

            font-weight: 500;

            padding: 0.75rem 1rem;

            border-radius: 10px;

            display: flex;

            align-items: center;

            gap: 0.75rem;

            transition:
                color 0.2s ease,
                background-color 0.2s ease;

            text-decoration: none;
        }


        .nav-link-custom:hover {

            color: var(--miffa-primary);

            background-color: #eef3ff;
        }


        .nav-link-custom.active {

            color: var(--miffa-primary);

            background-color: #eef3ff;

            font-weight: 700;
        }


        .nav-link-custom i {

            width: 20px;

            text-align: center;
        }


        /* =====================================================
           USER AVATAR
        ===================================================== */

        .user-avatar {

            width: 38px;

            height: 38px;

            min-width: 38px;

            background-color: var(--miffa-primary);

            color: #ffffff;

            font-weight: 600;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        /* =====================================================
           MAIN CONTENT
        ===================================================== */

        .main-content {

            min-height: 100vh;
        }


        /* =====================================================
           TOP HEADER
        ===================================================== */

        .top-header {

            min-height: 64px;
        }


        .dashboard-title {

            white-space: nowrap;
        }


        /* =====================================================
           MAIN PAGE
        ===================================================== */

        .page-main {

            min-height: calc(100vh - 64px);
        }


        /* =====================================================
           MOBILE BACK TO MAIN SITE
        ===================================================== */

        .mobile-main-site-link {

            width: 32px;

            height: 32px;

            border-radius: 50%;

            background: #eef3ff;

            color: var(--miffa-primary);

            display: inline-flex;

            align-items: center;

            justify-content: center;

            text-decoration: none;

            font-size: 0.8rem;

            transition:
                background-color 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }


        .mobile-main-site-link:hover {

            background: var(--miffa-primary);

            color: #ffffff;

            transform: translateY(-1px);
        }


        /* =====================================================
           STUDENT MOBILE NAVIGATION
           DASHBOARD / COURSES / HOMEWORK / BACK TO MAIN SITE
        ===================================================== */

        .mobile-dashboard-nav {

            display: none;

            width: 100%;

            background: #ffffff;

            border-bottom: 1px solid #e9ecef;

            padding: 6px 8px;

            gap: 5px;

            align-items: center;

            justify-content: space-between;

            order: 3;
        }


        .mobile-dashboard-nav a {

            flex: 1;

            min-width: 0;
        }


        .mobile-dashboard-nav a {

            min-height: 52px;

            padding: 5px 3px;

            border: none;

            border-radius: 10px;

            background: transparent;

            color: #707780;

            text-decoration: none;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            gap: 3px;

            font-size: 10px;

            font-weight: 600;

            line-height: 1.1;

            transition:
                color 0.2s ease,
                background-color 0.2s ease,
                transform 0.2s ease;

            cursor: pointer;
        }


        .mobile-dashboard-nav a i {

            font-size: 17px;

            color: #555d66;

            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }


        .mobile-dashboard-nav a:hover {

            color: var(--miffa-primary);

            background: #eef3ff;
        }


        .mobile-dashboard-nav a:hover i {

            color: var(--miffa-primary);

            transform: translateY(-1px);
        }


        .mobile-dashboard-nav a.active {

            color: var(--miffa-primary);

            background: #eef3ff;

            font-weight: 700;
        }


        .mobile-dashboard-nav a.active i {

            color: var(--miffa-primary);

            transform: translateY(-1px);
        }


        /* =====================================================
           MAIN SITE MOBILE BOTTOM NAVIGATION
        ===================================================== */

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
                mobileNavAppear 0.65s cubic-bezier(
                    0.22,
                    1,
                    0.36,
                    1
                ),
                mobileNavFloat 5s ease-in-out 1s infinite;
        }


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
                transform 0.3s cubic-bezier(
                    0.22,
                    1,
                    0.36,
                    1
                ),
                background-color 0.3s ease;
        }


        .mobile-bottom-nav a i,
        .mobile-bottom-nav button i {

            position: relative;

            font-size: 19px;

            margin-bottom: 4px;

            color: #444b52;

            transition:
                color 0.3s ease,
                transform 0.35s cubic-bezier(
                    0.34,
                    1.56,
                    0.64,
                    1
                ),
                filter 0.3s ease;
        }


        .mobile-bottom-nav a.active {

            color: #05bfa1;

            background: rgba(5, 213, 179, 0.10);

            transform: translateY(-2px);
        }


        .mobile-bottom-nav a.active i {

            color: #05cdb0;

            transform:
                translateY(-2px)
                scale(1.12);

            filter:
                drop-shadow(
                    0 4px 5px rgba(5, 213, 179, 0.25)
                );
        }


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


        .mobile-bottom-nav a:active,
        .mobile-bottom-nav button:active {

            transform: scale(0.90);
        }


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


        /* =====================================================
           ANIMATIONS
        ===================================================== */

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


        @keyframes mobileNavFloat {

            0%,
            100% {

                transform: translateY(0);
            }

            50% {

                transform: translateY(-1px);
            }
        }


        /* =====================================================
           VALIDNAVS MENU OVERRIDES
           USE ORIGINAL MIFFA MOBILE MENU
        ===================================================== */

        .student-main-site-nav {

            position: relative;

            z-index: 10000;
        }


        .student-main-site-nav .navbar-collapse {

            z-index: 10001;
        }


        .student-main-site-nav .overlay-screen {

            z-index: 9998;
        }


        /* =====================================================
           ALUMNI BUTTON
        ===================================================== */

        .alumni-btn {

            background-color: #05d5b3 !important;

            color: #ffffff !important;

            border-radius: 20px;

            padding: 10px 18px !important;
        }


        .alumni-btn:hover {

            background-color: #04b89b !important;

            color: #ffffff !important;
        }


        /* =====================================================
           DESKTOP
        ===================================================== */

        @media (min-width: 992px) {

            .mobile-dashboard-nav {

                display: none !important;
            }


            .mobile-bottom-nav {

                display: none !important;
            }


            .student-main-site-nav {

                display: none !important;
            }

        }


        /* =====================================================
           TABLET / MOBILE
        ===================================================== */

        @media (max-width: 991px) {

            body {

                padding-bottom: 85px;
            }


            .mobile-bottom-nav {

                display: flex;
            }

        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 767.98px) {

            .desktop-sidebar {

                display: none !important;
            }


            .main-content {

                width: 100%;

                min-height: 100vh;
            }


            .top-header {

                padding: 0 !important;

                min-height: auto;

                display: flex;

                flex-direction: column;

                align-items: stretch !important;
            }


            .top-header > .header-main-row {

                min-height: 58px;

                padding: 0.75rem 1rem;

                display: flex;

                align-items: center;

                justify-content: space-between;
            }


            .dashboard-title {

                font-size: 1rem;
            }


            .desktop-user-info {

                display: none !important;
            }


            .mobile-dashboard-nav {

                display: flex;

                order: 2;
            }


            .page-main {

                padding: 1rem !important;

                padding-bottom: 95px !important;

                min-height: calc(100vh - 110px);
            }


            /*
             * The original MIFFA validnavs menu is only used
             * when the bottom Menu button is pressed.
             */

            .student-main-site-nav {

                display: block;
            }

        }


        /* =====================================================
           SMALL PHONES
        ===================================================== */

        @media (max-width: 575.98px) {

            .top-header > .header-main-row {

                padding: 0.65rem 0.75rem;

                min-height: 55px;
            }


            .dashboard-title {

                font-size: 0.95rem;
            }


            .page-main {

                padding: 0.75rem !important;

                padding-bottom: 95px !important;
            }


            .user-avatar {

                width: 35px;

                height: 35px;

                min-width: 35px;
            }


            .mobile-main-site-link {

                width: 30px;

                height: 30px;

                font-size: 0.75rem;
            }


            .mobile-dashboard-nav {

                padding: 5px 6px;
            }


            .mobile-dashboard-nav a {

                min-height: 49px;

                font-size: 9px;
            }


            .mobile-dashboard-nav a i {

                font-size: 16px;
            }


            .mobile-bottom-nav {

                height: 66px;
            }


            .mobile-bottom-nav a,
            .mobile-bottom-nav button {

                font-size: 9px;
            }


            .mobile-bottom-nav a i,
            .mobile-bottom-nav button i {

                font-size: 17px;
            }

        }


        /* =====================================================
           VERY SMALL PHONES
        ===================================================== */

        @media (max-width: 380px) {

            body {

                padding-bottom: 80px;
            }


            .dashboard-title {

                font-size: 0.9rem;
            }


            .top-header > .header-main-row {

                padding-left: 0.65rem;

                padding-right: 0.65rem;
            }


            .page-main {

                padding: 0.65rem !important;

                padding-bottom: 92px !important;
            }


            .mobile-dashboard-nav {

                padding: 4px;
            }


            .mobile-dashboard-nav a {

                min-height: 46px;

                font-size: 8px;
            }


            .mobile-dashboard-nav a i {

                font-size: 15px;
            }


            .mobile-bottom-nav {

                height: 64px;

                padding: 5px 4px;
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

        }


        /* =====================================================
           SAFE AREA - IPHONE
        ===================================================== */

        @supports (padding-bottom: env(safe-area-inset-bottom)) {

            .mobile-bottom-nav {

                padding-bottom:
                    calc(
                        7px +
                        env(safe-area-inset-bottom)
                    );

                height:
                    calc(
                        68px +
                        env(safe-area-inset-bottom)
                    );
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


        /* =====================================================
           ACCESSIBILITY
        ===================================================== */

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


<div class="container-fluid p-0">

    <div class="row g-0">


        <!-- =====================================================
             STUDENT DESKTOP SIDEBAR
        ===================================================== -->

        <aside
            class="col-md-3 col-xl-2 sidebar p-3 d-flex flex-column justify-content-between desktop-sidebar"
        >

            <div>


                <!-- Brand -->

                <div class="d-flex align-items-center justify-content-between mb-4 px-2">

                    <a
                        href="{{ url('/') }}"
                        class="sidebar-brand text-decoration-none"
                    >

                        🎓 Shwe Learn ACADEMY

                    </a>

                </div>


                <!-- Navigation -->

                <nav class="nav flex-column gap-1">


                    {{-- Dashboard --}}

                    <a
                        href="{{ route('student.dashboard') }}"
                        class="nav-link-custom {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                    >

                        <i class="fas fa-th-large"></i>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- My Courses --}}

                    <a
                        href="{{ route('student.dashboard.courses') }}"
                        class="nav-link-custom {{ request()->routeIs('student.dashboard.courses') || request()->routeIs('courses.learn') || request()->routeIs('courses.show') ? 'active' : '' }}"
                    >

                        <i class="fas fa-book"></i>

                        <span>
                            My Courses
                        </span>

                    </a>


                    {{-- My Homeworks --}}

                    <a
                        href="{{ route('student.dashboard.homework') }}"
                        class="nav-link-custom {{ request()->routeIs('student.dashboard.homework') ? 'active' : '' }}"
                    >

                        <i class="fas fa-folder-open"></i>

                        <span>
                            My Homeworks
                        </span>

                    </a>


                </nav>

            </div>


            <!-- =================================================
                 Sidebar Bottom
            ================================================= -->

            <div class="pt-3 border-top d-flex flex-column gap-1">


                {{-- Back to Main Site --}}

                <a
                    href="{{ url('/') }}"
                    class="nav-link-custom text-secondary"
                >

                    <i class="fas fa-globe"></i>

                    <span>
                        Back to Main Site
                    </span>

                </a>


            </div>

        </aside>


        <!-- =====================================================
             MAIN CONTENT
        ===================================================== -->

        <div class="col-12 col-md-9 col-xl-10 main-content">


            <!-- =================================================
                 TOP HEADER
            ================================================= -->

            <header class="bg-white border-bottom top-header">


                <!-- Header Main Row -->

                <div
                    class="header-main-row d-flex justify-content-between align-items-center px-4 py-3"
                >


                    <!-- Left -->

                    <div class="d-flex align-items-center gap-2">


                        <h5 class="fw-bold mb-0 text-dark dashboard-title">

                            Student Dashboard

                        </h5>
                    </div>


                    <!-- Right -->

                    <div class="d-flex align-items-center gap-3">


                        {{-- Desktop Exit Dashboard --}}

                        <a
                            href="{{ url('/') }}"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-2"
                        >

                            <i class="fas fa-arrow-left"></i>

                            <span>
                                Exit Dashboard
                            </span>

                        </a>


                        <div class="vr d-none d-md-block my-1"></div>


                        {{-- User Information --}}

                        <div class="text-end desktop-user-info d-none d-sm-block">

                            <div class="fw-bold text-dark fs-6">

                                {{ auth()->user()->name ?? 'Student Account' }}

                            </div>

                            <small class="text-muted">

                                {{ auth()->user()->email ?? 'student@Shwe Learn.com' }}

                            </small>

                        </div>


                        {{-- Avatar --}}

                        <div class="user-avatar">

                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}

                        </div>


                    </div>

                </div>


                <!-- =================================================
                     STUDENT MOBILE NAVIGATION
                     DASHBOARD / COURSES / HOMEWORK / BACK TO MAIN SITE
                ================================================= -->

                <nav class="mobile-dashboard-nav d-md-none">


                    {{-- Dashboard --}}

                    <a
                        href="{{ route('student.dashboard') }}"
                        class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                    >

                        <i class="fas fa-th-large"></i>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- Courses --}}

                    <a
                        href="{{ route('student.dashboard.courses') }}"
                        class="{{ request()->routeIs('student.dashboard.courses') || request()->routeIs('courses.learn') || request()->routeIs('courses.show') ? 'active' : '' }}"
                    >

                        <i class="fas fa-book"></i>

                        <span>
                            Courses
                        </span>

                    </a>


                    {{-- Homework --}}

                    <a
                        href="{{ route('student.dashboard.homework') }}"
                        class="{{ request()->routeIs('student.dashboard.homework') ? 'active' : '' }}"
                    >

                        <i class="fas fa-folder-open"></i>

                        <span>
                            Homework
                        </span>

                    </a>


                    {{-- Back to Main Site --}}

                    <a
                        href="{{ url('/') }}"
                    >

                        <i class="fas fa-globe"></i>

                        <span>
                            Main Site
                        </span>

                    </a>


                </nav>


            </header>


            <!-- =================================================
                 PAGE CONTENT
            ================================================= -->

            <main class="p-4 page-main">

                @yield('content')

            </main>


        </div>

    </div>

</div>


<!-- =====================================================
     ORIGINAL Shwe Learn VALIDNAVS MOBILE MENU
====================================================== -->

<nav
    class="navbar mobile-sidenav navbar-sticky navbar-default validnavs dark navbar-fixed no-background inc-topbar student-main-site-nav"
>


    <div class="container d-flex justify-content-between align-items-center">


        <!-- =================================================
             HEADER NAVIGATION
        ================================================= -->

        <div class="item-flex">

            <div class="navbar-header">


                <button
                    type="button"
                    class="navbar-toggle"
                    data-toggle="collapse"
                    data-target="#navbar-menu"
                >

                    <i
                        class="fas fa-bars"
                        aria-hidden="true"
                    ></i>

                </button>


                <a
                    class="navbar-brand"
                    href="{{ url('/') }}"
                >

                    <img
                        src="{{ asset('assets/img/new/logo-light.png') }}"
                        class="logo"
                        alt="Shwe Learn"
                    >

                </a>


            </div>

        </div>


        <!-- =================================================
             NAVIGATION MENU
        ================================================= -->

        <div class="nav-item-box d-flex justify-content-between align-items-center">


            <div
                class="collapse navbar-collapse"
                id="navbar-menu"
            >


                <!-- Logo inside slide-out -->

                <img
                    src="{{ asset('assets/img/new/logo-light.png') }}"
                    alt="Shwe Learn"
                >


                <!-- Close -->

                <button
                    type="button"
                    class="navbar-toggle"
                    data-toggle="collapse"
                    data-target="#navbar-menu"
                >

                    <i class="fas fa-times"></i>

                </button>


                <!-- Navigation -->

                <ul
                    class="nav navbar-nav navbar-right"
                    data-in="fadeInDown"
                    data-out="fadeOutUp"
                >


                    <!-- Home -->

                    <li>

                        <a href="{{ url('/') }}">
                            Home
                        </a>

                    </li>


                    <!-- Teachers -->

                    <li>

                        <a href="{{ url('/teachers') }}">
                            Teachers
                        </a>

                    </li>


                    <!-- Courses -->

                    <li
                        class="dropdown megamenu-fw megamenu-style-four"
                        style="right: 0 !important;"
                    >

                        <a
                            href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                        >

                            Courses

                        </a>


                        <ul
                            class="dropdown-menu megamenu-content"
                            role="menu"
                        >

                            <li>

                                <div class="col-menu-wrap">

                                    <div class="col-menu">


                                        <h6 class="title">
                                            Course Layout
                                        </h6>


                                        <div class="content">

                                            <ul class="menu-col">


                                                <li>

                                                    <a
                                                        href="{{ url('/course/categories') }}"
                                                    >

                                                        All Courses

                                                    </a>

                                                </li>


                                                @auth

                                                    <li>

                                                        <a
                                                            href="{{ url('/my-courses') }}"
                                                        >

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


                    <!-- Join Alumni -->

                    <li>

                        <a
                            class="alumni-btn"
                            href="{{ Route::has('alumni.join') ? route('alumni.join') : url('/alumni/join') }}"
                        >

                            Join Alumni

                        </a>

                    </li>


                    @auth


                        <!-- Profile -->

                        <li class="d-lg-none">

                            <a
                                href="{{ route('student.dashboard') }}"
                            >

                                <i class="fas fa-user-circle me-1"></i>

                                Profile
                                ({{ auth()->user()->name }})

                            </a>

                        </li>


                        <!-- Logout -->

                        <li class="d-lg-none">

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                class="px-3 py-2"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="bg-transparent border-0 text-danger p-0 m-0 w-100 text-start"
                                    style="box-shadow: none;"
                                >

                                    <i class="fas fa-sign-out-alt me-1"></i>

                                    Logout

                                </button>

                            </form>

                        </li>


                    @else


                        <!-- Login -->

                        <li class="d-lg-none">

                            <a
                                href="{{ url('/login') }}"
                            >

                                <i class="fas fa-sign-in-alt me-1"></i>

                                Login

                            </a>

                        </li>


                    @endauth


                </ul>

            </div>

        </div>

    </div>


    <!-- Overlay -->

    <div class="overlay-screen"></div>

</nav>


<!-- =====================================================
     MAIN SITE MOBILE BOTTOM NAVIGATION
====================================================== -->

<div class="mobile-bottom-nav">


    {{-- Home --}}

    <a
        href="{{ url('/') }}"
        class="{{ request()->is('/') ? 'active' : '' }}"
    >

        <i class="fas fa-home"></i>

        <span>
            Home
        </span>

    </a>


    {{-- Courses --}}

    <a
        href="{{ url('/course/categories') }}"
        class="{{ request()->is('course/categories*') ? 'active' : '' }}"
    >

        <i class="fas fa-graduation-cap"></i>

        <span>
            Courses
        </span>

    </a>


    @auth


        {{-- Enrolled --}}

        <a
            href="{{ url('/my-courses') }}"
            class="{{ request()->is('my-courses*') ? 'active' : '' }}"
        >

            <i class="fas fa-book-reader"></i>

            <span>
                Enrolled
            </span>

        </a>


        {{-- Profile --}}

        <a
            href="{{ route('student.dashboard') }}"
            class="{{ request()->routeIs('student.dashboard') || request()->routeIs('student.dashboard.*') ? 'active' : '' }}"
        >

            <i class="fas fa-user-circle"></i>

            <span>
                Profile
            </span>

        </a>


    @else


        {{-- Login --}}

        <a
            href="{{ url('/login') }}"
            class="{{ request()->is('login') ? 'active' : '' }}"
        >

            <i class="fas fa-user-circle"></i>

            <span>
                Login
            </span>

        </a>


    @endauth


    {{-- Teachers --}}

    <a
        href="{{ url('/teachers') }}"
        class="{{ request()->is('teachers*') ? 'active' : '' }}"
    >

        <i class="fas fa-chalkboard-teacher"></i>

        <span>
            Teachers
        </span>

    </a>


    {{-- Menu --}}

    <button
        type="button"
        class="navbar-toggle"
        data-toggle="collapse"
        data-target="#navbar-menu"
    >

        <i class="fas fa-bars"></i>

        <span>
            Menu
        </span>

    </button>


</div>


<!-- =====================================================
     SCRIPTS
====================================================== -->

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