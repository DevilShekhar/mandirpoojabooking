@extends('admin.layouts.app')
@section('title', 'Booking Details')
@section('content')
<style>
    .seb-page {
        --seb-primary: #5145cd;
        --seb-success: #169b62;
        --seb-danger: #dc3545;
        --seb-info: #1686b9;
        --seb-dark: #263247;
        --seb-muted: #718096;
        --seb-border: #e9edf4;
        color: var(--seb-dark);
    }
    .seb-page .seb-hero {
        background: linear-gradient(120deg, #4338a9, #7165df);
        border-radius: 14px;
        padding: 25px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 8px 24px rgba(81, 69, 205, .15);
    }
    .seb-hero h3 {
        color: #fff;
        font-weight: 700;
        margin-bottom: 7px;
    }
    .seb-hero p {
        color: rgba(255,255,255,.8);
        margin-bottom: 0;
    }
    .seb-hero .seb-booking-id {
        font-size: 13px;
        color: rgba(255,255,255,.8);
        letter-spacing: .5px;
    }
    .seb-page .seb-card {
        border: 1px solid var(--seb-border);
        border-radius: 12px;
        background: #fff;
        margin-bottom: 22px;
        box-shadow: 0 3px 15px rgba(30, 45, 75, .045);
        overflow: hidden;
    }
    .seb-page .seb-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid var(--seb-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        background: #fff;
    }
    .seb-page .seb-card-header h5 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: var(--seb-dark);
    }
    .seb-page .seb-card-header .seb-heading-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        margin-right: 9px;
        font-size: 15px;
    }

    .seb-page .seb-card-body {
        padding: 20px;
    }

    .seb-icon-purple {
        background: #efedff;
        color: #6254d8;
    }

    .seb-icon-blue {
        background: #e8f5ff;
        color: #1686b9;
    }

    .seb-icon-green {
        background: #e6f8ef;
        color: #169b62;
    }

    .seb-icon-orange {
        background: #fff2e5;
        color: #e38a2f;
    }

    .seb-icon-red {
        background: #ffebed;
        color: #dc3545;
    }

    .seb-icon-cyan {
        background: #e4f8fa;
        color: #1595a5;
    }

    .seb-page .seb-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 24px;
    }

    .seb-page .seb-info-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f2f6;
        min-width: 0;
    }

    .seb-page .seb-info-item:last-child,
    .seb-page .seb-info-item:nth-last-child(2) {
        border-bottom: 0;
    }

    .seb-page .seb-info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        color: var(--seb-muted);
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .seb-page .seb-info-label i {
        width: 16px;
        text-align: center;
    }

    .seb-page .seb-info-value {
        color: #29354b;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .seb-page .seb-info-value.normal {
        font-weight: 400;
    }

    .seb-page .seb-amount-box {
        background: linear-gradient(135deg, #edfbf4, #f5fffa);
        border: 1px solid #d6f3e3;
        border-radius: 11px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .seb-page .seb-amount-box small {
        color: #55856c;
        font-weight: 600;
    }

    .seb-page .seb-amount {
        font-size: 27px;
        font-weight: 800;
        color: #15945a;
        margin-top: 5px;
    }

    .seb-page .seb-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .seb-status-success {
        background: #e6f8ef;
        color: #168b57;
    }

    .seb-status-warning {
        background: #fff4df;
        color: #bc7b18;
    }

    .seb-status-danger {
        background: #ffebed;
        color: #d63848;
    }

    .seb-status-secondary {
        background: #edf0f5;
        color: #687487;
    }

    .seb-status-info {
        background: #e6f5ff;
        color: #1686b9;
    }

    .seb-page .seb-completed-card {
        border: 1px solid #bcebd1;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(22, 155, 98, .08);
    }

    .seb-page .seb-completed-header {
        background: linear-gradient(110deg, #138b58, #25ad73);
        padding: 16px 20px;
        color: #fff;
    }

    .seb-page .seb-completed-header h5 {
        margin: 0;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
    }

    .seb-page .seb-completed-body {
        padding: 22px;
    }

    .seb-page .seb-completed-detail {
        display: flex;
        align-items: center;
        gap: 13px;
        background: #f3fbf6;
        border: 1px solid #e1f3e8;
        padding: 16px;
        border-radius: 10px;
        height: 100%;
    }

    .seb-page .seb-completed-detail .detail-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 10px;
        background: #dff6e9;
        color: #169b62;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .seb-page .seb-completed-detail small {
        color: #648574;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .seb-page .seb-completed-detail strong {
        font-size: 14px;
        color: #176a45;
        overflow-wrap: anywhere;
    }

    .seb-page .seb-action-card {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 22px;
        background: #fff;
    }

    .seb-page .seb-complete-action {
        border: 1px solid #bcebd1;
        background: linear-gradient(135deg, #f3fcf7, #fff);
    }

    .seb-page .seb-cancel-action {
        border: 1px solid #f3c9cd;
        background: linear-gradient(135deg, #fff7f7, #fff);
    }

    .seb-page .seb-action-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .seb-page .seb-action-description {
        font-size: 13px;
        color: #718096;
        line-height: 1.7;
        margin-bottom: 17px;
    }

    .seb-page .seb-btn-complete {
        background: #169b62;
        color: #fff;
        border: 0;
        border-radius: 7px;
        padding: 10px 19px;
        font-weight: 600;
    }

    .seb-page .seb-btn-complete:hover {
        background: #117c4e;
        color: #fff;
    }

    .seb-page .seb-btn-cancel {
        background: #dc3545;
        color: #fff;
        border: 0;
        border-radius: 7px;
        padding: 10px 19px;
        font-weight: 600;
    }

    .seb-page .seb-btn-cancel:hover {
        background: #bd2635;
        color: #fff;
    }

    .seb-page .seb-bottom-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .seb-page .seb-id-value {
        font-size: 12px;
        word-break: break-all;
    }

    .seb-page .form-control {
        border-radius: 8px;
        min-height: 42px;
    }

    .seb-page textarea.form-control {
        min-height: 100px;
    }

    @media(max-width: 767px) {
        .seb-page .seb-info-grid {
            grid-template-columns: 1fr;
        }

        .seb-page .seb-info-item:nth-last-child(2) {
            border-bottom: 1px solid #f0f2f6;
        }

        .seb-page .seb-hero {
            padding: 19px;
        }

        .seb-page .seb-amount {
            font-size: 23px;
        }

        .seb-page .seb-card-body {
            padding: 15px;
        }

        .seb-page .seb-completed-body {
            padding: 15px;
        }
    }
</style>

<section class="section seb-page">
    @php
        $currentUser = auth()->user();
        $bookingStatus = strtolower($booking->booking_status ?? 'pending');
        $paymentStatus = strtolower($booking->payment_status ?? 'pending');
        $refundStatus = $booking->refund_status ?? 'not_requested';
        $canCancelBooking = $currentUser && (
            (
                $currentUser->hasRole('Customer') &&
                (int) $currentUser->id === (int) $booking->user_id
            ) ||
            (
                $currentUser->hasRole('Event Creator') &&
                (int) $currentUser->id === (int) $booking->event_created_by
            )
        );
        $canShowCancelForm = $canCancelBooking
            && $bookingStatus === 'confirmed'
            && $paymentStatus === 'paid'
            && empty($booking->cancelled_at)
            && $refundStatus === 'not_requested'
            && empty($booking->razorpay_refund_id)
            && empty($booking->completed_at);
        $canCompleteBooking = $currentUser && (
            $currentUser->hasAnyRole(['Admin', 'Super Admin']) ||
            (
                $currentUser->hasRole('Event Creator') &&
                (int) $currentUser->id === (int) $booking->event_created_by
            )
        );
        $canShowCompleteButton = $canCompleteBooking
            && $bookingStatus === 'confirmed'
            && $paymentStatus === 'paid'
            && empty($booking->completed_at)
            && empty($booking->cancelled_at);
        $statusClass = match($bookingStatus) {
            'completed', 'confirmed' => 'seb-status-success',
            'cancelled', 'failed' => 'seb-status-danger',
            'pending' => 'seb-status-warning',
            default => 'seb-status-secondary',
        };
        $paymentClass = match($paymentStatus) {
            'paid', 'success', 'captured' => 'seb-status-success',
            'failed' => 'seb-status-danger',
            default => 'seb-status-warning',
        };
        $refundClass = match($refundStatus) {
            'processed' => 'seb-status-success',
            'failed' => 'seb-status-danger',
            'pending', 'processing', 'created' => 'seb-status-warning',
            default => 'seb-status-secondary',
        };
    @endphp
    <div class="section-header">
        <h1>
            <i class="fas fa-calendar-check text-primary mr-2"></i>
            Booking Details
        </h1>
        <div class="section-header-button ml-auto">
            <a href="{{ route('customer.sanatan-event-bookings.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
    </div>
    <div class="section-body">
        <div class="seb-hero">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="seb-booking-id mb-2">
                        BOOKING REFERENCE #{{ $booking->id }}
                    </div>
                    <h3>
                        {{ $booking->event_name ?? 'Sanatan Event Booking' }}
                    </h3>
                    <p>
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $booking->event_date
                            ? \Carbon\Carbon::parse($booking->event_date)->format('d M Y')
                            : 'Date not available' }}
                        <span class="mx-2">|</span>
                        <i class="far fa-clock mr-1"></i>
                        {{ $booking->event_time
                            ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A')
                            : 'Time not available' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <span class="seb-status {{ $statusClass }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($bookingStatus) }}
                    </span>
                    <div class="mt-3">
                        <div class="seb-booking-id">TOTAL BOOKING AMOUNT</div>
                        <div class="h3 text-white font-weight-bold mb-0">
                            {{ $booking->currency ?? 'INR' }}
                            {{ number_format((float) $booking->amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="seb-card">
                    <div class="seb-card-header">
                        <h5>
                            <span class="seb-heading-icon seb-icon-purple">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            Event Information
                        </h5>
                    </div>
                    <div class="seb-card-body">
                        <div class="seb-info-grid">
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-hashtag"></i> Booking ID
                                </div>
                                <div class="seb-info-value">
                                    #{{ $booking->id }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-praying-hands"></i> Event Name
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->event_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-calendar-day"></i> Event Date
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->event_date
                                        ? \Carbon\Carbon::parse($booking->event_date)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-clock"></i> Event Time
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->event_time
                                        ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A')
                                        : '-' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-user-tie"></i> Event Created By
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->event_creator_name
                                        ?? ($booking->event_created_by ?? '-') }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-align-left"></i> Description
                                </div>
                                <div class="seb-info-value normal">
                                    @if(!empty($booking->event_description))
                                        {!! nl2br(e(strip_tags($booking->event_description))) !!}
                                    @else
                                        Not available
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="seb-card">
                    <div class="seb-card-header">
                        <h5>
                            <span class="seb-heading-icon seb-icon-blue">
                                <i class="fas fa-user"></i>
                            </span>
                            Customer Information
                        </h5>
                    </div>
                    <div class="seb-card-body">
                        <div class="seb-info-grid">
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-user-circle"></i> Customer Name
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->customer_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-envelope"></i> Email Address
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->customer_email ?? '-' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-phone-alt"></i> Phone Number
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->customer_phone ?? '-' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-id-card"></i> Customer ID
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->user_id ?? '-' }}
                                </div>
                            </div>
                            <div class="seb-info-item">
                                <div class="seb-info-label">
                                    <i class="fas fa-user-plus"></i> Booking Created By ID
                                </div>
                                <div class="seb-info-value">
                                    {{ $booking->created_by ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($bookingStatus === 'completed')
                    <div class="seb-completed-card">
                        <div class="seb-completed-header">
                            <h5>
                                <i class="fas fa-check-circle mr-2"></i>
                                Booking Completion Information
                            </h5>
                        </div>
                        <div class="seb-completed-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="seb-completed-detail">
                                        <div class="detail-icon">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div>
                                            <small>Completed By</small>
                                            <strong>
                                                {{ $booking->completed_by_name
                                                    ?? ($booking->completed_by
                                                        ? 'User ID: '.$booking->completed_by
                                                        : 'N/A') }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="seb-completed-detail">
                                        <div class="detail-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <small>Completed Date & Time</small>
                                            <strong>
                                                {{ $booking->completed_at
                                                    ? \Carbon\Carbon::parse($booking->completed_at)->format('d M Y, h:i:s A')
                                                    : 'N/A' }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="fas fa-check-circle mr-1"></i>
                                This event booking has been successfully completed.
                            </div>
                        </div>
                    </div>
                @endif
                @if($canShowCompleteButton)
                    <div class="seb-action-card seb-complete-action">
                        <div class="seb-action-title text-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            Complete Event Booking
                        </div>
                        <p class="seb-action-description">
                            Confirm that this event booking has been successfully completed.
                            The system will automatically record the completed user's name
                            and completion date and time.
                        </p>
                        <form id="completeBookingForm" action="{{ route('customer.sanatan-event-bookings.complete', $booking->id) }}"
                            method="POST">
                            @csrf
                            <button type="submit" id="completeBookingButton" class="btn seb-btn-complete" >
                                <i class="fas fa-check-circle mr-1"></i>
                                Mark as Completed
                            </button>
                        </form>
                    </div>
                @endif
                @if($canShowCancelForm)
                    <div class="seb-action-card seb-cancel-action">
                        <div class="seb-action-title text-danger">
                            <i class="fas fa-times-circle mr-2"></i>
                            Cancel Booking
                        </div>
                        <p class="seb-action-description">
                            Enter the reason for cancellation. A refund request
                            will be submitted to Razorpay.
                        </p>
                        <form id="cancelBookingForm"  action="{{ route('customer.sanatan-event-bookings.cancel', $booking->id) }}"
                            method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="cancellation_reason">
                                    Cancellation Reason
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    name="cancellation_reason"
                                    id="cancellation_reason"
                                    class="form-control @error('cancellation_reason') is-invalid @enderror"
                                    rows="4"
                                    minlength="5"
                                    maxlength="1000"
                                    placeholder="Please enter your reason for cancelling this booking"
                                    required
                                >{{ old('cancellation_reason') }}</textarea>
                                @error('cancellation_reason')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button  type="submit"  id="cancelBookingButton" class="btn seb-btn-cancel" >
                                <i class="fas fa-times-circle mr-1"></i>
                                Cancel Booking & Request Refund
                            </button>
                        </form>
                    </div>
                @endif
                @if($bookingStatus === 'cancelled')
                    <div class="seb-card">
                        <div class="seb-card-header">
                            <h5 class="text-danger">
                                <i class="fas fa-ban mr-2"></i>
                                Cancellation Information
                            </h5>
                        </div>
                        <div class="seb-card-body">
                            <div class="seb-info-grid">
                                <div class="seb-info-item">
                                    <div class="seb-info-label">
                                        <i class="fas fa-user-times"></i> Cancelled By
                                    </div>
                                    <div class="seb-info-value">
                                        {{ $booking->cancelled_by_name
                                            ?? ($booking->cancelled_by
                                                ? 'User ID: '.$booking->cancelled_by
                                                : '-') }}
                                    </div>
                                </div>
                                <div class="seb-info-item">
                                    <div class="seb-info-label">
                                        <i class="fas fa-calendar-times"></i> Cancelled At
                                    </div>
                                    <div class="seb-info-value">
                                        {{ $booking->cancelled_at
                                            ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, h:i A')
                                            : '-' }}
                                    </div>
                                </div>
                                <div class="seb-info-item">
                                    <div class="seb-info-label">
                                        <i class="fas fa-comment-alt"></i> Cancellation Reason
                                    </div>
                                    <div class="seb-info-value normal">
                                        {{ $booking->cancellation_reason ?? '-' }}
                                    </div>
                                </div>
                                <div class="seb-info-item">
                                    <div class="seb-info-label">
                                        <i class="fas fa-money-bill-wave"></i> Refund Status
                                    </div>
                                    <div class="seb-info-value">
                                        <span class="seb-status {{ $refundClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $refundStatus)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="seb-card">
                    <div class="seb-card-header">
                        <h5>
                            <span class="seb-heading-icon seb-icon-green">
                                <i class="fas fa-wallet"></i>
                            </span>
                            Payment Summary
                        </h5>
                    </div>
                    <div class="seb-card-body">
                        <div class="seb-amount-box">
                            <small>
                                <i class="fas fa-rupee-sign mr-1"></i>
                                TOTAL AMOUNT
                            </small>
                            <div class="seb-amount">
                                {{ $booking->currency ?? 'INR' }}
                                {{ number_format((float) $booking->amount, 2) }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="seb-info-label">
                                <i class="fas fa-credit-card"></i>
                                Payment Status
                            </div>
                            <span class="seb-status {{ $paymentClass }}">
                                <i class="fas fa-circle"></i>
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="seb-info-label">
                                <i class="fas fa-receipt"></i>
                                Refund Status
                            </div>
                            <span class="seb-status {{ $refundClass }}">
                                {{ ucfirst(str_replace('_', ' ', $refundStatus)) }}
                            </span>
                        </div>
                        <hr>
                        <div class="seb-info-item">
                            <div class="seb-info-label">
                                <i class="fas fa-money-bill-wave"></i>
                                Refund Amount
                            </div>
                            <div class="seb-info-value">
                                @if($booking->refund_amount !== null)
                                    {{ $booking->currency ?? 'INR' }}
                                    {{ number_format((float) $booking->refund_amount, 2) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="seb-card">
                    <div class="seb-card-header">
                        <h5>
                            <span class="seb-heading-icon seb-icon-cyan">
                                <i class="fas fa-shield-alt"></i>
                            </span>
                            Razorpay Details
                        </h5>
                    </div>
                    <div class="seb-card-body">
                        <div class="mb-4">
                            <div class="seb-info-label">
                                <i class="fas fa-shopping-cart"></i>
                                Order ID
                            </div>
                            <div class="seb-info-value seb-id-value">
                                {{ $booking->razorpay_order_id ?? '-' }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="seb-info-label">
                                <i class="fas fa-credit-card"></i>
                                Payment ID
                            </div>
                            <div class="seb-info-value seb-id-value">
                                {{ $booking->razorpay_payment_id ?? '-' }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="seb-info-label">
                                <i class="fas fa-undo-alt"></i>
                                Refund ID
                            </div>
                            <div class="seb-info-value seb-id-value">
                                {{ $booking->razorpay_refund_id ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="seb-info-label">
                                <i class="fas fa-calendar-check"></i>
                                Refund Processed At
                            </div>
                            <div class="seb-info-value">
                                {{ $booking->refunded_at
                                    ? \Carbon\Carbon::parse($booking->refunded_at)->format('d M Y, h:i A')
                                    : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="seb-card">
                    <div class="seb-card-header">
                        <h5>
                            <span class="seb-heading-icon seb-icon-orange">
                                <i class="fas fa-history"></i>
                            </span>
                            Booking Timeline
                        </h5>
                    </div>
                    <div class="seb-card-body">
                        <div class="mb-4">
                            <div class="seb-info-label">
                                <i class="fas fa-plus-circle"></i>
                                Booking Created
                            </div>
                            <div class="seb-info-value">
                                {{ $booking->created_at
                                    ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y, h:i A')
                                    : '-' }}
                            </div>
                        </div>
                        @if(!empty($booking->completed_at))
                            <div class="mb-4">
                                <div class="seb-info-label text-success">
                                    <i class="fas fa-check-circle"></i>
                                    Booking Completed
                                </div>
                                <div class="seb-info-value text-success">
                                    {{ \Carbon\Carbon::parse($booking->completed_at)->format('d M Y, h:i A') }}
                                </div>
                            </div>
                        @endif
                        @if(!empty($booking->cancelled_at))
                            <div class="mb-4">
                                <div class="seb-info-label text-danger">
                                    <i class="fas fa-times-circle"></i>
                                    Booking Cancelled
                                </div>
                                <div class="seb-info-value text-danger">
                                    {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, h:i A') }}
                                </div>
                            </div>
                        @endif
                        <div>
                            <div class="seb-info-label">
                                <i class="fas fa-sync-alt"></i>
                                Last Updated
                            </div>
                            <div class="seb-info-value">
                                {{ $booking->updated_at
                                    ? \Carbon\Carbon::parse($booking->updated_at)->format('d M Y, h:i A')
                                    : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="seb-bottom-actions mb-4">
            <a href="{{ route('customer.sanatan-event-bookings.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to All Bookings
            </a>
            @if($bookingStatus === 'completed')
                <a href="{{ route('customer.sanatan-event-bookings.completed') }}"
                   class="btn btn-success">
                    <i class="fas fa-check-circle mr-1"></i>
                    View Completed Bookings
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const completeForm = document.getElementById('completeBookingForm');
    const cancelForm = document.getElementById('cancelBookingForm');
    // SUCCESS MESSAGE
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            confirmButtonColor: '#169b62',
            confirmButtonText: 'OK'
        });
    @endif
    // BOOKING ERROR
    @if($errors->has('booking'))
        Swal.fire({
            icon: 'error',
            title: 'Unable to Process',
            text: @json($errors->first('booking')),
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
    @endif
    // CANCELLATION VALIDATION ERROR
    @if($errors->has('cancellation_reason'))
        Swal.fire({
            icon: 'error',
            title: 'Invalid Cancellation Reason',
            text: @json($errors->first('cancellation_reason')),
            confirmButtonText: 'OK'
        });
    @endif
    // MARK AS COMPLETED
    if (completeForm) {
        completeForm.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Complete This Booking?',
                html: `
                    <p>Are you sure you want to mark this booking as completed?</p>
                    <small class="text-muted">
                        The completion date and your user details will be saved.
                    </small>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, Complete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#169b62',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    const button = document.getElementById('completeBookingButton');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    }
                    completeForm.submit();
                }
            });
        });
    }
    // CANCEL BOOKING
    if (cancelForm) {
        cancelForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const reasonField = document.getElementById('cancellation_reason');
            const reason = reasonField.value.trim();
            if (reason.length < 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cancellation Reason Required',
                    text: 'Please enter a cancellation reason containing at least 5 characters.',
                    confirmButtonText: 'OK'
                });
                reasonField.focus();
                return;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Cancel This Booking?',
                html: `
                    <p>Are you sure you want to cancel this booking?</p>
                    <p>
                        Refund Amount:
                        <strong>
                            {{ $booking->currency ?? 'INR' }}
                            {{ number_format((float) $booking->amount, 2) }}
                        </strong>
                    </p>
                    <small class="text-muted">
                        A refund request will be submitted to Razorpay.
                    </small>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-times-circle"></i> Yes, Cancel',
                cancelButtonText: 'Keep Booking',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    const button = document.getElementById('cancelBookingButton');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    }
                    cancelForm.submit();
                }

            });
        });
    }
});
</script>
@endpush