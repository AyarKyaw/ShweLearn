<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Shwe Learn Teacher Portal">


    {{-- Page Title --}}

    <title>
        @yield('title', 'Teacher Dashboard | Shwe Learn')
    </title>


    {{-- Favicon --}}

    <link
        rel="shortcut icon"
        href="{{ asset('assets/img/icon/miffas.png') }}"
        type="image/x-icon"
    >


    {{-- Font Awesome --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    >


    {{-- Bootstrap --}}

    <link
        href="{{ asset('assets/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >


    {{-- Main Site CSS --}}

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
           TEACHER SIDEBAR
        ===================================================== */

        .teacher-sidebar {

            min-height: 100vh;

            background-color: #ffffff;

            border-right: 1px solid #e9ecef;
        }


        .teacher-sidebar-brand {

            color: var(--miffa-primary);

            font-weight: 800;

            font-size: 1.2rem;

            letter-spacing: 0.5px;
        }


        .teacher-sidebar-brand:hover {

            color: var(--miffa-primary);
        }


        /* =====================================================
           SIDEBAR NAVIGATION
        ===================================================== */

        .teacher-nav-link {

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


        .teacher-nav-link:hover {

            color: var(--miffa-primary);

            background-color: #eef3ff;
        }


        .teacher-nav-link.active {

            color: var(--miffa-primary);

            background-color: #eef3ff;

            font-weight: 700;
        }


        .teacher-nav-link i {

            width: 20px;

            text-align: center;
        }


        /* =====================================================
           TEACHER AVATAR
        ===================================================== */

        .teacher-avatar {

            width: 38px;

            height: 38px;

            min-width: 38px;

            border-radius: 50%;

            overflow: hidden;

            background-color: var(--miffa-primary);

            color: #ffffff;

            font-weight: 600;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .teacher-avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;
        }


        /* =====================================================
           MAIN CONTENT
        ===================================================== */

        .teacher-main-content {

            min-height: 100vh;
        }


        /* =====================================================
           TOP HEADER
        ===================================================== */

        .teacher-top-header {

            min-height: 64px;
        }


        .teacher-dashboard-title {

            white-space: nowrap;
        }


        /* =====================================================
           PAGE MAIN
        ===================================================== */

        .teacher-page-main {

            min-height: calc(100vh - 64px);
        }


        /* =====================================================
           MOBILE NAVIGATION
        ===================================================== */

        .teacher-mobile-nav {

            display: none;

            width: 100%;

            background: #ffffff;

            border-bottom: 1px solid #e9ecef;

            padding: 6px 8px;

            gap: 5px;

            align-items: center;

            justify-content: space-between;
        }


        .teacher-mobile-nav a {

            flex: 1;

            min-width: 0;

            min-height: 52px;

            padding: 5px 3px;

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
                background-color 0.2s ease;
        }


        .teacher-mobile-nav a i {

            font-size: 17px;

            color: #555d66;

            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }


        .teacher-mobile-nav a:hover {

            color: var(--miffa-primary);

            background: #eef3ff;
        }


        .teacher-mobile-nav a.active {

            color: var(--miffa-primary);

            background: #eef3ff;

            font-weight: 700;
        }


        .teacher-mobile-nav a.active i {

            color: var(--miffa-primary);

            transform: translateY(-1px);
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 991px) {

            .teacher-mobile-nav {

                display: flex;
            }

        }


        @media (max-width: 767.98px) {

            body {

                padding-bottom: 20px;
            }


            .desktop-teacher-sidebar {

                display: none !important;
            }


            .teacher-main-content {

                width: 100%;

                min-height: 100vh;
            }


            .teacher-top-header {

                padding: 0 !important;

                min-height: auto;

                display: flex;

                flex-direction: column;

                align-items: stretch !important;
            }


            .teacher-header-main-row {

                min-height: 58px;

                padding: 0.75rem 1rem;

                display: flex;

                align-items: center;

                justify-content: space-between;
            }


            .teacher-dashboard-title {

                font-size: 1rem;
            }


            .teacher-desktop-user-info {

                display: none !important;
            }


            .teacher-mobile-nav {

                display: flex;

                order: 2;
            }


            .teacher-page-main {

                padding: 1rem !important;

                min-height: calc(100vh - 115px);
            }

        }


        @media (max-width: 575.98px) {

            .teacher-header-main-row {

                padding: 0.65rem 0.75rem;

                min-height: 55px;
            }


            .teacher-dashboard-title {

                font-size: 0.95rem;
            }


            .teacher-page-main {

                padding: 0.75rem !important;
            }


            .teacher-avatar {

                width: 35px;

                height: 35px;

                min-width: 35px;
            }


            .teacher-mobile-nav {

                padding: 5px 6px;
            }


            .teacher-mobile-nav a {

                min-height: 49px;

                font-size: 9px;
            }


            .teacher-mobile-nav a i {

                font-size: 16px;
            }

        }


        @media (max-width: 380px) {

            .teacher-dashboard-title {

                font-size: 0.9rem;
            }


            .teacher-header-main-row {

                padding-left: 0.65rem;

                padding-right: 0.65rem;
            }


            .teacher-page-main {

                padding: 0.65rem !important;
            }


            .teacher-mobile-nav {

                padding: 4px;
            }


            .teacher-mobile-nav a {

                min-height: 46px;

                font-size: 8px;
            }


            .teacher-mobile-nav a i {

                font-size: 15px;
            }

        }

    </style>

</head>


<body>


<div class="container-fluid p-0">

    <div class="row g-0">


        {{-- =====================================================
             DESKTOP SIDEBAR
        ====================================================== --}}

        <aside
            class="col-md-3 col-xl-2 teacher-sidebar p-3 d-flex flex-column justify-content-between desktop-teacher-sidebar"
        >


            <div>


                {{-- Brand --}}

                <div class="d-flex align-items-center justify-content-between mb-4 px-2">

                    <a
                        href="{{ url('/') }}"
                        class="teacher-sidebar-brand text-decoration-none"
                    >

                        🎓 Shwe Learn

                    </a>

                </div>


                {{-- Navigation --}}

                <nav class="nav flex-column gap-1">


                    {{-- Dashboard --}}

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="teacher-nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                    >

                        <i class="fas fa-th-large"></i>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- Homework --}}

                    <a
                        href="{{ route('teacher.homework') }}"
                        class="teacher-nav-link {{ request()->routeIs('teacher.homework*') ? 'active' : '' }}"
                    >

                        <i class="fas fa-folder-open"></i>

                        <span>
                            Homework
                        </span>

                    </a>


                </nav>

            </div>


            {{-- Sidebar Bottom --}}

            <div class="pt-3 border-top d-flex flex-column gap-1">


                {{-- Back to Main Site --}}

                <a
                    href="{{ url('/') }}"
                    class="teacher-nav-link"
                >

                    <i class="fas fa-globe"></i>

                    <span>
                        Back to Main Site
                    </span>

                </a>


                {{-- Logout --}}

                <form
                    action="{{ route('teacher.logout') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="teacher-nav-link border-0 bg-transparent w-100 text-start"
                    >

                        <i class="fas fa-sign-out-alt"></i>

                        <span>
                            Logout
                        </span>

                    </button>

                </form>


            </div>

        </aside>



        {{-- =====================================================
             MAIN CONTENT
        ====================================================== --}}

        <div class="col-12 col-md-9 col-xl-10 teacher-main-content">


            {{-- =================================================
                 TOP HEADER
            ================================================== --}}

            <header class="bg-white border-bottom teacher-top-header">


                {{-- Header Main Row --}}

                <div
                    class="teacher-header-main-row d-flex justify-content-between align-items-center px-4 py-3"
                >


                    {{-- Left --}}

                    <div class="d-flex align-items-center gap-2">

                        <h5 class="fw-bold mb-0 text-dark teacher-dashboard-title">

                            Teacher Portal

                        </h5>

                    </div>


                    {{-- Right --}}

                    <div class="d-flex align-items-center gap-3">


                        {{-- Exit Dashboard --}}

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


                        {{-- Teacher Information --}}

                        <div class="text-end teacher-desktop-user-info d-none d-sm-block">

                            <div class="fw-bold text-dark fs-6">

                                {{ auth('teacher')->user()->name ?? 'Teacher' }}

                            </div>

                            <small class="text-muted">

                                {{ auth('teacher')->user()->email ?? '' }}

                            </small>

                        </div>


                        {{-- Teacher Avatar --}}

                        @if(auth('teacher')->user()?->image)

                            <div class="teacher-avatar">

                                <img
                                    src="{{ asset('storage/' . auth('teacher')->user()->image) }}"
                                    alt="{{ auth('teacher')->user()->name }}"
                                >

                            </div>

                        @else

                            <div class="teacher-avatar">

                                {{ strtoupper(
                                    substr(
                                        auth('teacher')->user()->name ?? 'T',
                                        0,
                                        1
                                    )
                                ) }}

                            </div>

                        @endif


                    </div>

                </div>


                {{-- =================================================
                     MOBILE TEACHER NAVIGATION
                ================================================== --}}

                <nav class="teacher-mobile-nav d-md-none">


                    {{-- Dashboard --}}

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                    >

                        <i class="fas fa-th-large"></i>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- Homework --}}

                    <a
                        href="{{ route('teacher.homework') }}"
                        class="{{ request()->routeIs('teacher.homework*') ? 'active' : '' }}"
                    >

                        <i class="fas fa-folder-open"></i>

                        <span>
                            Homework
                        </span>

                    </a>


                    {{-- Main Site --}}

                    <a
                        href="{{ url('/') }}"
                    >

                        <i class="fas fa-globe"></i>

                        <span>
                            Main Site
                        </span>

                    </a>


                    {{-- Logout --}}

                    <a
                        href="#"
                        onclick="event.preventDefault(); document.getElementById('teacher-mobile-logout').submit();"
                    >

                        <i class="fas fa-sign-out-alt"></i>

                        <span>
                            Logout
                        </span>

                    </a>


                </nav>


                <form
                    id="teacher-mobile-logout"
                    action="{{ route('teacher.logout') }}"
                    method="POST"
                    class="d-none"
                >

                    @csrf

                </form>


            </header>


            {{-- =================================================
                 PAGE CONTENT
            ================================================== --}}

            <main class="p-4 teacher-page-main">

                @yield('content')

            </main>


        </div>

    </div>

</div>


{{-- =========================================================
     SCRIPTS
========================================================= --}}

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