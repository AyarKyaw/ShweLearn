{{-- resources/views/dashboard/alumni-payments/show.blade.php --}}
@extends('dashboard.layouts.master')

@section('title', 'Payment Details - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="payment-detail-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="payment-detail-title">Payment Transaction Details</h2>
                            <p class="m-card__subtitle">Viewing transaction record for <span class="font-monospace text-dark fw-bold">{{ $payment->transaction_id }}</span></p>
                        </div>
                        <div>
                            <a href="{{ route('admin.alumni-payments.index') }}" class="au-btn au-btn--blue au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Payments
                            </a>
                        </div>
                    </header>

                    <div class="card-body px-4 py-3">
                        <div class="row g-4">
                            <!-- Transaction & Payment Summary -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light fw-bold text-dark">
                                        <i class="fa-solid fa-receipt me-1"></i> Transaction Information
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted fw-semibold" style="width: 40%;">Transaction ID:</td>
                                                <td><span class="font-monospace fw-bold text-dark">{{ $payment->transaction_id }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Gateway:</td>
                                                <td><span class="badge bg-secondary">{{ $payment->gateway }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Amount:</td>
                                                <td><span class="fw-bold text-success fs-5">{{ number_format($payment->amount, 2) }}</span> <small class="text-muted">{{ $payment->currency ?? 'MMK' }}</small></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Status:</td>
                                                <td>
                                                    @php
                                                        $badgeColors = [
                                                            'completed' => 'success',
                                                            'pending' => 'warning text-dark',
                                                            'failed' => 'danger',
                                                            'refunded' => 'info'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $badgeColors[$payment->status] ?? 'secondary' }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Date & Time:</td>
                                                <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Alumni Profile Summary -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light fw-bold text-dark">
                                        <i class="fa-solid fa-user-graduate me-1"></i> Alumni Profile
                                    </div>
                                    <div class="card-body">
                                        @if($payment->alumni)
                                            <div class="d-flex align-items-center mb-3">
                                                @if($payment->alumni->image)
                                                    <img src="{{ asset('storage/' . $payment->alumni->image) }}"alt="" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px; font-size: 18px;">
                                                        {{ strtoupper(substr($payment->alumni->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-0">{{ $payment->alumni->name }}</h5>
                                                    <span class="badge bg-secondary font-monospace">{{ $payment->alumni->register_no }}</span>
                                                </div>
                                            </div>
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="text-muted fw-semibold" style="width: 35%;">Email:</td>
                                                    <td>{{ $payment->alumni->email ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Account Status:</td>
                                                    <td>
                                                        <span class="badge bg-{{ $payment->alumni->status === 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($payment->alumni->status ?? 'N/A') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        @else
                                            <div class="text-muted text-center py-4">
                                                <i class="fa-solid fa-triangle-exclamation fa-2x mb-2 text-warning"></i>
                                                <p class="mb-0">Associated alumni record not found or has been removed.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Raw Payload Data (Optional Debug/Audit block) -->
                            @if(!empty($payment->payload))
                                <div class="col-md-12">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-light fw-bold text-dark">
                                            <i class="fa-solid fa-code me-1"></i> Gateway Payload Response
                                        </div>
                                        <div class="card-body bg-dark text-light rounded-bottom">
                                            <pre class="mb-0 text-light font-monospace small" style="max-height: 250px; overflow-y: auto;"><code>{{ json_encode($payment->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection