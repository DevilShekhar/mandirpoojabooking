
@extends('frontend.layouts.app')

@section('title', 'Booking Payment Status')

@section('content')
<style>
    .booking-result-wrapper{min-height:65vh;padding:55px 15px;background:#f8fafc}
    .booking-result-card{max-width:760px;margin:0 auto;background:#fff;border-radius:18px;box-shadow:0 10px 35px rgba(15,23,42,.08);overflow:hidden}
    .booking-result-header{padding:38px 25px 25px;text-align:center}
    .booking-result-icon{width:88px;height:88px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:42px}
    .booking-result-icon.failed{background:#fee2e2;color:#b91c1c}
    .booking-result-icon.pending{background:#fef3c7;color:#b45309}
    .booking-result-header h2{font-weight:800;margin-bottom:12px}
    .booking-result-header h2.failed{color:#b91c1c}
    .booking-result-header h2.pending{color:#92400e}
    .booking-result-header p{color:#64748b;margin:0;line-height:1.7}
    .booking-result-details{margin:0 25px 25px;padding:24px;background:#f8fafc;border-radius:12px}
    .booking-result-details h5{font-weight:700;color:#0f172a;margin-bottom:18px}
    .booking-detail-row{display:flex;justify-content:space-between;gap:20px;padding:12px 0;border-bottom:1px solid #e2e8f0}
    .booking-detail-row:last-child{border-bottom:0}
    .booking-detail-label{color:#64748b}
    .booking-detail-value{color:#0f172a;font-weight:600;text-align:right;overflow-wrap:anywhere}
    .booking-result-actions{display:flex;justify-content:center;flex-wrap:wrap;gap:12px;padding:0 25px 35px}
    .booking-result-actions a{padding:12px 22px;border-radius:9px;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;justify-content:center;gap:8px}
    .booking-btn-primary{background:#15803d;color:#fff}
    .booking-btn-primary:hover{background:#166534;color:#fff}
    .booking-btn-secondary{border:1px solid #cbd5e1;color:#334155;background:#fff}
    .booking-btn-secondary:hover{background:#f1f5f9;color:#0f172a}
    .booking-status-badge{display:inline-block;padding:5px 11px;border-radius:30px;font-size:13px;font-weight:700}
    .booking-status-badge.failed{background:#fee2e2;color:#b91c1c}
    .booking-status-badge.pending{background:#fef3c7;color:#92400e}
    .booking-warning{margin:0 25px 25px;padding:15px 17px;border-radius:10px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;line-height:1.7}
    @media(max-width:576px){.booking-result-wrapper{padding:25px 12px}.booking-result-header{padding:30px 18px 22px}.booking-result-details{margin:0 15px 20px;padding:17px}.booking-detail-row{gap:12px;font-size:14px}.booking-result-actions{padding:0 15px 25px}.booking-result-actions a{width:100%}.booking-warning{margin:0 15px 22px}}
</style>

@php
    $isPending = ($status ?? 'failed') === 'pending'
        || (isset($booking) && $booking && ($booking->payment_status ?? '') === 'pending');

    $displayStatus = $isPending ? 'Pending' : 'Failed';
@endphp

<div class="booking-result-wrapper">
    <div class="booking-result-card">
        <div class="booking-result-header">
            <div class="booking-result-icon {{ $isPending ? 'pending' : 'failed' }}">
                <i class="fas {{ $isPending ? 'fa-clock' : 'fa-times-circle' }}" aria-hidden="true"></i>
            </div>

            <h2 class="{{ $isPending ? 'pending' : 'failed' }}">
                {{ $isPending ? 'Payment Pending' : 'Booking Payment Failed' }}
            </h2>

            <p>
                {{ $message ?? ($isPending
                    ? 'Your payment is still being processed. Please check its status before trying again.'
                    : 'We could not confirm your payment. Please review the details below.') }}
            </p>
        </div>

        @if(isset($booking) && $booking)
            <div class="booking-result-details">
                <h5><i class="fas fa-receipt me-2"></i>Booking Details</h5>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Booking ID</span>
                    <span class="booking-detail-value">#{{ $booking->id }}</span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Event Name</span>
                    <span class="booking-detail-value">{{ $booking->event_name ?? 'Sanatan Event' }}</span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Event Date</span>
                    <span class="booking-detail-value">
                        {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                    </span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Event Time</span>
                    <span class="booking-detail-value">
                        {{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}
                    </span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Amount</span>
                    <span class="booking-detail-value">
                        ₹{{ number_format((float) $booking->amount, 2) }}
                    </span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Payment Status</span>
                    <span class="booking-detail-value">
                        <span class="booking-status-badge {{ $isPending ? 'pending' : 'failed' }}">
                            {{ ucfirst($booking->payment_status ?? $displayStatus) }}
                        </span>
                    </span>
                </div>

                <div class="booking-detail-row">
                    <span class="booking-detail-label">Booking Status</span>
                    <span class="booking-detail-value">
                        {{ ucfirst($booking->booking_status ?? 'pending') }}
                    </span>
                </div>
            </div>
        @endif

        <div class="booking-warning">
            <strong><i class="fas fa-info-circle me-1" aria-hidden="true"></i> Important:</strong>
            If money was deducted from your account, do not immediately make another payment. Your bank or Razorpay may still be processing the transaction. Check the payment status or contact support.
        </div>

        <div class="booking-result-actions">
            @if(isset($booking) && $booking)
                <a href="{{ url()->previous() }}" class="booking-btn-primary">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Event
                </a>
            @else
                <a href="{{ url('/') }}" class="booking-btn-primary">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    Browse Events
                </a>
            @endif

            <a href="{{ url('/') }}" class="booking-btn-secondary">
                <i class="fas fa-home" aria-hidden="true"></i>
                Go to Home
            </a>
        </div>
    </div>
</div>
@endsection