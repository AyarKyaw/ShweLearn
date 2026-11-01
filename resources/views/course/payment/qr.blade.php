@extends('layouts.master')

@section('title', 'Payment - ' . $course->title . ' - Shwe Learn')

@section('content')
<!-- Outer Wrapper to push content below fixed header -->
<div class="container py-5 my-5 position-relative z-1">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="bg-primary bg-gradient text-white text-center p-4 position-relative">
                    <h4 class="fw-bold mb-0">Scan & Pay</h4>
                    <p class="text-white-50 small mb-0 mt-1">Complete your course enrollment</p>
                </div>

                <div class="card-body p-4 p-md-5 text-center bg-white">
                    <!-- Course Details Summary -->
                    <div class="p-3 bg-light rounded-3 mb-4 text-start border border-light-subtle">
                        <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Selected Course</small>
                        <h6 class="fw-bold text-dark mb-2">{{ $course->title }}</h6>
                        
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <div>
                                <span class="text-muted small d-block">Total Amount</span>
                                @php
                                    // Fetch status from explicit variable, studentProfile relationship, user relation, or session
                                    $status = $membershipStatus 
                                        ?? $studentProfile->membership_status 
                                        ?? auth()->user()?->studentProfile?->membership_status 
                                        ?? session('membership_status');
                                @endphp
                                @if($status)
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill small" style="font-size: 0.7rem;">
                                        {{ ucfirst($status) }} Rate
                                    </span>
                                @endif
                            </div>
                            <span class="fs-5 fw-bold text-primary">
                                @php
                                    if ($status === 'member') {
                                        $finalPrice = $course->member_price ?? $course->price ?? 0;
                                    } elseif ($status === 'non-member') {
                                        $finalPrice = $course->non_member_price ?? $course->price ?? 0;
                                    } else {
                                        $finalPrice = $course->price ?? 0;
                                    }
                                @endphp

                                @if($finalPrice > 0)
                                    {{ number_format($finalPrice) }} <small class="fs-6 fw-normal text-muted">MMK</small>
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- QR Code Display Container -->
                    <div class="position-relative d-inline-block p-3 bg-white rounded-4 shadow-sm border mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=PAYMENT-{{ $course->id }}-{{ $orderRef }}" 
                             alt="Payment QR Code" 
                             class="img-fluid rounded-3" 
                             style="width: 210px; height: 210px; object-fit: contain;">
                    </div>

                    <!-- Supported Payment Methods Badge Strip -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                        <span class="badge bg-light text-secondary border px-2 py-1 fs-7">KBZPay</span>
                        <span class="badge bg-light text-secondary border px-2 py-1 fs-7">WavePay</span>
                        <span class="badge bg-light text-secondary border px-2 py-1 fs-7">CBPay</span>
                        <span class="badge bg-light text-secondary border px-2 py-1 fs-7">AYA Pay</span>
                    </div>

                    <!-- Polling Status Container -->
                    <div id="payment-status" class="alert alert-primary border-0 bg-primary-subtle text-primary-emphasis d-flex align-items-center justify-content-center gap-2 rounded-3 py-3 mb-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="fw-medium small">Awaiting payment verification...</span>
                    </div>

                    <!-- Demo Simulation Action -->
                    <div class="pt-2 border-top">
                        <button id="simulate-pay-btn" class="btn btn-outline-success w-100 rounded-3 py-2 btn-sm fw-semibold d-flex align-items-center justify-content-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                              <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                            </svg>
                            <span>Simulate Successful Payment</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmUrl = "{{ route('payment.confirm', $course->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        const statusBox = document.getElementById('payment-status');
        const simulateBtn = document.getElementById('simulate-pay-btn');

        function completePayment() {
            statusBox.className = 'alert alert-warning border-0 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center gap-2 rounded-3 py-3 mb-4';
            statusBox.innerHTML = `
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <span class="fw-medium small">Processing transaction...</span>
            `;

            fetch(confirmUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    statusBox.className = 'alert alert-success border-0 bg-success-subtle text-success-emphasis d-flex align-items-center justify-content-center gap-2 rounded-3 py-3 mb-4';
                    statusBox.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <span class="fw-bold small">Payment Received! Redirecting...</span>
                    `;

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1200);
                }
            })
            .catch(() => {
                statusBox.className = 'alert alert-danger border-0 bg-danger-subtle text-danger-emphasis d-flex align-items-center justify-content-center gap-2 rounded-3 py-3 mb-4';
                statusBox.innerHTML = '<span class="fw-medium small">Verification failed. Please try again.</span>';
                simulateBtn.disabled = false;
            });
        }

        simulateBtn.addEventListener('click', function () {
            this.disabled = true;
            completePayment();
        });
    });
</script>
@endsection