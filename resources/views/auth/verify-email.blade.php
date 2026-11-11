<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Your Email - Shwe Learn</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-gradient-secondary d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg text-center p-4">
                    <div class="mb-3">
                        <i class="fa fa-envelope-open-text fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Check Your Email</h3>
                    <p class="text-muted" style="font-size: 14px;">
                        We sent a confirmation link to: <br>
                        <strong class="text-dark">{{ $email ?? session('pending_registration.email') }}</strong>
                    </p>
                    <p class="text-muted" style="font-size: 13px;">
                        Your account will <strong>not be created</strong> until you click the confirmation link in your email inbox.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success py-2" style="font-size: 12px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-3">
                        <form action="{{ route('account.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm circle w-100 mb-2">
                                Resend Confirmation Email
                            </button>
                        </form>
                        <a href="{{ route('register') }}" class="text-decoration-none text-muted" style="font-size: 12px;">
                            Entered wrong email? Register again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-redirect script when confirmed -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let isRedirecting = false;

            const checkStatus = function () {
                if (isRedirecting) return;

                fetch("{{ route('account.check-status') }}", {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('CHECK STATUS RESPONSE:', data);

                    if (data.confirmed && !isRedirecting) {
                        isRedirecting = true;

                        console.log('EMAIL VERIFIED - REDIRECTING TO:', data.redirect || '/');

                        window.location.replace(data.redirect || '/');
                    }
                })
                .catch(error => console.error('Status check error:', error));
            };

            // Check immediately, then poll every 2 seconds
            checkStatus();
            setInterval(checkStatus, 2000);
        });
    </script>
</body>
</html>