{{-- resources/views/dashboard/alumni-payments/index.blade.php --}}
@extends('dashboard.layouts.master')

@section('title', 'Alumni Payments - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="alumni-payments-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="alumni-payments-title">Alumni Payment Transactions</h2>
                            <p class="m-card__subtitle">Monitor and track all registered alumni payment records</p>
                        </div>
                    </header>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.alumni-payments.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="status" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                </div>
                                <div class="select-wrapper">
                                    <select class="form-select" name="gateway" onchange="this.form.submit()">
                                        <option value="">All Gateways</option>
                                        <option value="KBZPay" {{ request('gateway') === 'KBZPay' ? 'selected' : '' }}>KBZPay</option>
                                        <option value="CB Bank MMQR" {{ request('gateway') === 'CB Bank MMQR' ? 'selected' : '' }}>CB Bank MMQR</option>
                                        <option value="Bank Transfer" {{ request('gateway') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Transaction ID</th>
                                    <th>Alumni</th>
                                    <th>Gateway</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $payment->transaction_id }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $payment->alumni->name ?? 'N/A' }}</span>
                                            @if($payment->alumni && $payment->alumni->register_no)
                                                <br><small class="text-muted">{{ $payment->alumni->register_no }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $payment->gateway }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($payment->amount, 2) }}</span> 
                                            <small class="text-muted">{{ $payment->currency ?? 'MMK' }}</small>
                                        </td>
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
                                        <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.alumni-payments.show', $payment->id) }}" class="item" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No alumni payment records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($payments, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection