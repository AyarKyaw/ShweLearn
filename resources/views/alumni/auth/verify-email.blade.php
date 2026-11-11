@extends('layouts.master')

@section('content')

<div class="container py-5 my-5">

<div class="row justify-content-center">

    <div class="col-md-6 col-lg-5">

        <div class="card shadow-lg border-0 rounded-4 text-center p-4">

            <div class="mb-3">
                <i class="fa fa-envelope-open-text fa-3x text-primary"></i>
            </div>

            <h3 class="fw-bold mb-2">
                Check Your Email
            </h3>

            <p class="text-muted" style="font-size: 14px;">
                We sent a verification link to:
                <br>

                <strong class="text-dark">
                    {{ $email ?? session('pending_registration.email') }}
                </strong>
            </p>

            <p class="text-muted" style="font-size: 13px;">
                Please check your inbox and click the verification
                link to confirm your email address.
            </p>

            {{-- Verification status --}}
            <div
                id="verificationStatus"
                class="alert alert-info py-2"
                style="font-size: 12px;"
            >
                <i class="fa fa-clock me-1"></i>
                Waiting for email verification...
            </div>

            @if (session('message'))

                <div
                    class="alert alert-success py-2"
                    style="font-size: 12px;"
                >
                    {{ session('message') }}
                </div>

            @endif

            @if (session('success'))

                <div
                    class="alert alert-success py-2"
                    style="font-size: 12px;"
                >
                    {{ session('success') }}
                </div>

            @endif

            <div class="mt-3">

                <form
                    method="POST"
                    action="{{ route('alumni.verification.send') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-primary btn-sm rounded-pill w-100 mb-2"
                    >
                        Resend Verification Email
                    </button>

                </form>

                <a
                    href="{{ route('register') }}"
                    class="text-decoration-none text-muted"
                    style="font-size: 12px;"
                >
                    Entered wrong email? Register again
                </a>

            </div>

        </div>

    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let isRedirecting = false;

    const statusBox =
        document.getElementById('verificationStatus');

    function checkVerificationStatus() {

        if (isRedirecting) {
            return;
        }

        fetch(
            "{{ route('alumni.verification.status') }}?email={{ urlencode($email ?? session('pending_registration.email')) }}",
            {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            }
        )
        .then(response => {

            if (!response.ok) {
                throw new Error(
                    'HTTP error: ' + response.status
                );
            }

            return response.json();
        })
        .then(data => {

            console.log(
                'EMAIL VERIFICATION STATUS:',
                data
            );

            if (data.verified && !isRedirecting) {

                isRedirecting = true;

                statusBox.className =
                    'alert alert-success py-2';

                statusBox.innerHTML =
                    '<i class="fa fa-check-circle me-1"></i>' +
                    'Email verified successfully!';

                console.log(
                    'EMAIL VERIFIED - REDIRECTING TO:',
                    data.redirect
                );

                setTimeout(function () {

                    window.location.replace(
                        data.redirect
                    );

                }, 800);
            }

        })
        .catch(error => {

            console.error(
                'Verification status error:',
                error
            );

        });
    }

    // Check immediately
    checkVerificationStatus();

    // Check every 2 seconds
    setInterval(
        checkVerificationStatus,
        2000
    );

});
</script>

@endsection
