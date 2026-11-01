@extends('layouts.master')

@section('title', 'Alumni Login - Shwe Learn')

@push('styles')
<style>
    .alumni-hero {
        background: var(--dark);
        padding: 130px 0 70px;
        color: var(--white);
        position: relative;
    }

    .alumni-hero h1 {
        color: var(--white) !important;
        font-weight: 700;
        font-size: 2.5rem;
    }

    .alumni-hero p {
        color: var(--color-style-six) !important;
        font-size: 1.1rem;
    }

    .alumni-card {
        border: 1px solid var(--color-style-six);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        background: var(--white);
        margin-top: -35px;
        position: relative;
        z-index: 2;
    }

    .btn-alumni-submit {
        background: var(--bg-gradient) !important;
        color: var(--white) !important;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 30px;
        padding: 12px 36px;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(28, 176, 152, 0.3);
    }

    .btn-alumni-submit:hover {
        background: var(--color-primary) !important;
        color: var(--white) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 213, 179, 0.4);
    }

    .form-control:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 0.25rem rgba(28, 176, 152, 0.25) !important;
    }

    .form-label {
        color: var(--color-heading);
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<div class="alumni-hero text-center">
    <div class="container">
        <h1 class="mb-3">Welcome Back</h1>
        <p class="mx-auto mb-0" style="max-width: 650px;">
            Sign in to access your Shwe Learn Alumni account and network directory.
        </p>
    </div>
</div>

<div class="pb-5" style="background-color: var(--bg-gray-secondary);">
    <div class="container pb-4">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 mt-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 mt-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card alumni-card p-4 p-md-5">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--color-heading);">Alumni Portal Login</h3>
                    
                    <form action="{{ route('alumni.login.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 py-2" placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 py-2" placeholder="Enter your password" required>
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn-alumni-submit mb-3 w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                            <p class="mb-0 text-muted fs-6">
                                Don't have an alumni account? <a href="{{ route('alumni.join') }}" style="color: var(--color-primary); font-weight: 600;">Register Here</a>
                            </p>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection