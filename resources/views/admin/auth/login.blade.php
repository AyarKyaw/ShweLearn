<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ========== Page Title ========== -->
    <title>Shwe Learn - Admin Login</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ asset('assets/img/new/logo-light.png') }}" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/unit-test.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->
</head>

<body>

    <!-- Start Admin Login ============================================= -->
    <div class="login-register-area bg-gray-gradient-secondary">
        <div class="login-style-one-items">
            <div class="shape">
                <img src="{{ asset('assets/img/shape/banner-5.jpg') }}" alt="Shape Background">
            </div>
            <div class="thumb">
                <img src="{{ asset('assets/img/illustration/14.png') }}" alt="Admin Illustration">
            </div>
            <div class="container">
                <div class="row align-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="login-register-items text-light">
                            <h2>Admin Portal</h2>
                            <p>Shwe Learn Administration Access</p>

                            <!-- Success Alert -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Error Alert -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.login.perform') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input id="email" 
                                                   name="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="Admin Email*" 
                                                   type="email" 
                                                   required 
                                                   autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input id="password" 
                                                   name="password" 
                                                   class="form-control" 
                                                   placeholder="Password*" 
                                                   type="password" 
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="remember-pass">
                                            <div class="check-box">
                                                <input type="checkbox" id="remember" name="remember" value="1">
                                                <label for="remember">Remember device</label>
                                            </div>
                                            <a href="{{ route('home') }}" class="text-white-50">Back to Website</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-sm circle btn-theme animation w-100 py-2" type="submit" style="height: 42px; line-height: 1;">
                                            Log In to Dashboard
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Admin Login -->

    <!-- jQuery Frameworks -->
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

</body>
</html>