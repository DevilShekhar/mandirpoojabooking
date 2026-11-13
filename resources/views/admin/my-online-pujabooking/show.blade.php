@can('my-online-pujabooking.show')
@extends('admin.layouts.app')

@section('content')

@php
    $puja = $booking->puja;
    $package = $booking->package;

    $currency = $booking->currency ?? 'INR';
    $bookingAmount = (float) ($booking->amount ?? 0);
    $packagePrice = $package ? (float) $package->price : null;

    $paymentStatus = $booking->payment_status ?? 'pending';
    $bookingStatus = $booking->booking_status ?? 'pending';

    $paymentKey = strtolower($paymentStatus);
    $bookingKey = strtolower($bookingStatus);

    $paymentBadge = in_array($paymentKey, ['paid', 'success', 'successful'])
        ? 'success'
        : (in_array($paymentKey, ['failed', 'failure']) ? 'danger' : 'warning');

    $bookingBadge = in_array($bookingKey, ['confirmed', 'completed'])
        ? 'success'
        : (in_array($bookingKey, ['cancelled', 'rejected']) ? 'danger' : 'info');

    $pujaDateTime = $puja && $puja->puja_datetime
        ? \Carbon\Carbon::parse($puja->puja_datetime)
        : null;
@endphp

<style>
    .booking-page {
        padding-bottom: 35px;
        background: #f5f7fb;
    }

    /* Main Header */
    .booking-hero {
        position: relative;
        overflow: hidden;
        padding: 30px;
        border-radius: 15px;
        color: #fff;
        background: linear-gradient(120deg, #29245c, #4936a5, #7355c7);
        box-shadow: 0 8px 25px rgba(65, 53, 150, .18);
        margin-bottom: 25px;
    }

    .booking-hero:after {
        content: "\f654";
        font-family: FontAwesome;
        position: absolute;
        right: 40px;
        top: -20px;
        font-size: 150px;
        color: rgba(255,255,255,.07);
        pointer-events: none;
    }

    .booking-hero h4 {
        font-size: 23px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .booking-hero p {
        color: rgba(255,255,255,.8);
        margin-bottom: 0;
        font-size: 13px;
    }

    .booking-hero .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 17px;
        position: relative;
        z-index: 2;
    }

    .booking-reference {
        display: inline-block;
        margin-top: 13px;
        padding: 7px 13px;
        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 7px;
        color: #fff;
        font-size: 12px;
    }

    /* Section Cards */
    .booking-section {
        background: #fff;
        border-radius: 13px;
        margin-bottom: 24px;
        overflow: hidden;
        border: 1px solid #e9edf5;
        box-shadow: 0 4px 18px rgba(30, 41, 59, .045);
    }

    .booking-section-header {
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #edf0f5;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .section-icon {
        height: 43px;
        width: 43px;
        min-width: 43px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 11px;
        font-size: 19px;
    }

    .section-heading h5 {
        margin: 0;
        color: #202b3c;
        font-size: 16px;
        font-weight: 700;
    }

    .section-heading p {
        margin: 4px 0 0;
        color: #929bad;
        font-size: 12px;
    }

    .booking-section-body {
        padding: 23px;
    }

    /* Section Color Themes */
    .theme-purple {
        border-top: 4px solid #7355c7;
    }

    .theme-purple .section-icon {
        color: #7355c7;
        background: #f0ebff;
    }

    .theme-blue {
        border-top: 4px solid #3283d9;
    }

    .theme-blue .section-icon {
        color: #3283d9;
        background: #eaf4ff;
    }

    .theme-orange {
        border-top: 4px solid #e89a32;
    }

    .theme-orange .section-icon {
        color: #dc8a20;
        background: #fff4e5;
    }

    .theme-green {
        border-top: 4px solid #27a879;
    }

    .theme-green .section-icon {
        color: #22996d;
        background: #e7f8f0;
    }

    .theme-pink {
        border-top: 4px solid #df6090;
    }

    .theme-pink .section-icon {
        color: #d94d81;
        background: #fff0f5;
    }

    /* Information Boxes */
    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 16px;
        border-radius: 10px;
        border: 1px solid #edf0f5;
        background: #fafbfe;
        height: 100%;
        transition: all .2s ease;
    }

    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30,41,59,.07);
    }

    .info-icon {
        width: 37px;
        height: 37px;
        min-width: 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 15px;
    }

    .info-content {
        min-width: 0;
    }

    .info-label {
        display: block;
        color: #8993a4;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .55px;
        margin-bottom: 6px;
    }

    .info-value {
        display: block;
        color: #263445;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
        margin: 0;
    }

    .info-value.small-value {
        font-size: 13px;
    }

    /* Individual Icon Colors */
    .icon-purple {
        background: #f0ebff;
        color: #7355c7;
    }

    .icon-blue {
        background: #eaf4ff;
        color: #3283d9;
    }

    .icon-orange {
        background: #fff4e5;
        color: #dc8a20;
    }

    .icon-green {
        background: #e7f8f0;
        color: #22996d;
    }

    .icon-pink {
        background: #fff0f5;
        color: #d94d81;
    }

    .icon-red {
        background: #fff0ee;
        color: #dd665b;
    }

    /* Status */
    .status-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .status-badge.badge-success {
        background: #e2f7ec;
        color: #16834d;
    }

    .status-badge.badge-warning {
        background: #fff4d9;
        color: #a36c00;
    }

    .status-badge.badge-danger {
        background: #ffe8e8;
        color: #c73737;
    }

    .status-badge.badge-info {
        background: #e6f3ff;
        color: #2478b8;
    }

    .status-badge.badge-secondary {
        background: #edf0f4;
        color: #687386;
    }

    /* Package */
    .package-highlight {
        background: linear-gradient(135deg, #fff8ec, #fff2dc);
        border: 1px solid #f9e3bb;
    }

    .package-description-box {
        background: #fffaf1;
        border: 1px solid #f6e8ce;
        border-radius: 10px;
        padding: 18px;
        color: #6c5b43;
        font-size: 13px;
        line-height: 1.8;
        white-space: pre-line;
    }

    .recommended-label {
        display: inline-block;
        padding: 7px 12px;
        background: #fff2d4;
        color: #a56a00;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
    }

    /* Temple Address */
    .temple-address-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
        background: #edfaf4;
        border: 1px solid #d4f0e2;
        border-radius: 10px;
    }

    .temple-address-box .address-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #22996d;
        background: #d8f3e5;
        font-size: 17px;
    }

    .temple-address-box p {
        color: #315b48;
        font-size: 13px;
        line-height: 1.8;
        margin: 0;
        font-weight: 600;
    }

    /* Payment */
    .payment-highlight {
        background: linear-gradient(135deg, #fff0f5, #fff7fa);
        border: 1px solid #f8dce7;
        border-radius: 11px;
        padding: 23px;
        height: 100%;
    }

    .payment-highlight .payment-label {
        color: #a16a82;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 9px;
    }

    .payment-highlight .payment-amount {
        font-size: 26px;
        font-weight: 800;
        color: #ca477b;
        margin: 0;
    }

    .payment-status-box {
        background: #f5f8ff;
        border: 1px solid #e2eafa;
        border-radius: 11px;
        padding: 23px;
        height: 100%;
    }

    .payment-status-box .payment-label {
        color: #7d8ba5;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .empty-state {
        padding: 20px;
        background: #f8f9fc;
        border-radius: 9px;
        color: #929bad;
        text-align: center;
        font-size: 13px;
    }

    @media(max-width: 767px) {
        .booking-hero {
            padding: 22px;
        }

        .booking-hero h4 {
            font-size: 19px;
        }

        .booking-hero .header-actions {
            margin-top: 18px;
            text-align: left !important;
        }

        .booking-section-body {
            padding: 16px;
        }

        .booking-section-header {
            padding: 15px;
        }

        .section-heading h5 {
            font-size: 14px;
        }

        .info-box {
            padding: 13px;
        }

        .payment-highlight .payment-amount {
            font-size: 22px;
        }
    }
</style>

<div class="container-fluid booking-page">

    {{-- PAGE HEADER --}}
    <div class="booking-hero">
        <div class="row align-items-center">

            <div class="col-md-8">
                <h4>
                    <i class="fa fa-om mr-2"></i>
                    Online Puja Booking Details
                </h4>

                <p>
                    Complete information about your online puja booking.
                </p>

                <div class="booking-reference">
                    <i class="fa fa-hashtag mr-1"></i>
                    Booking Reference:
                    <strong>{{ $booking->order_number ?? $booking->id }}</strong>
                </div>
            </div>

            <div class="col-md-4 text-md-right header-actions">
                <a href="{{ route('admin.my-online-pujabooking.index') }}"
                   class="btn btn-light">
                    <i class="fa fa-arrow-left mr-1"></i>
                    Back to Bookings
                </a>
            </div>

        </div>
    </div>


    {{-- 1. BOOKING SUMMARY --}}
    <div class="booking-section theme-purple">

        <div class="booking-section-header">
            <div class="section-heading">
                <div class="section-icon">
                    <i class="fa fa-clipboard"></i>
                </div>

                <div>
                    <h5>Booking Summary</h5>
                    <p>Booking reference and current booking status</p>
                </div>
            </div>
        </div>

        <div class="booking-section-body">
            <div class="row">

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-purple">
                            <i class="fa fa-hashtag"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Booking ID</span>
                            <p class="info-value">
                                {{ $booking->order_number ?? $booking->id }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-green">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Payment Status</span>
                            <p class="info-value">
                                <span class="status-badge badge-{{ $paymentBadge }}">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-blue">
                            <i class="fa fa-bookmark"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Booking Status</span>
                            <p class="info-value">
                                <span class="status-badge badge-{{ $bookingBadge }}">
                                    {{ ucfirst($bookingStatus) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-orange">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Booking Created On</span>
                            <p class="info-value small-value">
                                {{ $booking->created_at
                                    ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y, h:i A')
                                    : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- 2. PUJA DETAILS --}}
    <div class="booking-section theme-blue">

        <div class="booking-section-header">
            <div class="section-heading">
                <div class="section-icon">
                    <i class="fa fa-om"></i>
                </div>

                <div>
                    <h5>Puja Details</h5>
                    <p>Information about the selected puja</p>
                </div>
            </div>
        </div>

        <div class="booking-section-body">

            @if($puja)

                <div class="row">

                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-blue">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Puja Name</span>
                                <p class="info-value">
                                    {{ $puja->title ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-purple">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Puja Date</span>
                                <p class="info-value">
                                    {{ $pujaDateTime
                                        ? $pujaDateTime->format('d M Y')
                                        : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-orange">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Puja Time</span>
                                <p class="info-value">
                                    {{ $pujaDateTime
                                        ? $pujaDateTime->format('h:i A')
                                        : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-green">
                                <i class="fa fa-calendar-check-o"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Puja Day</span>
                                <p class="info-value">
                                    {{ $puja->puja_day ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-pink">
                                <i class="fa fa-moon-o"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Tithi</span>
                                <p class="info-value">
                                    {{ $puja->tithi ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-orange">
                                <i class="fa fa-sun-o"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Muhurat</span>
                                <p class="info-value">
                                    {{ $puja->muhurat ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            @else
                <div class="empty-state">
                    <i class="fa fa-info-circle mr-1"></i>
                    Puja details are not available.
                </div>
            @endif

        </div>
    </div>


    {{-- 3. PACKAGE DETAILS --}}
    <div class="booking-section theme-orange">

        <div class="booking-section-header">
            <div class="section-heading">
                <div class="section-icon">
                    <i class="fa fa-cubes"></i>
                </div>

                <div>
                    <h5>Package Details</h5>
                    <p>Selected package, price and devotee information</p>
                </div>
            </div>

            @if($package && $package->is_recommended)
                <span class="recommended-label">
                    <i class="fa fa-star mr-1"></i>
                    Recommended
                </span>
            @endif
        </div>

        <div class="booking-section-body">

            <div class="row">

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box package-highlight">
                        <div class="info-icon icon-orange">
                            <i class="fa fa-gift"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Package Name</span>
                            <p class="info-value">
                                {{ $package->package_name ?? $booking->package_name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box package-highlight">
                        <div class="info-icon icon-blue">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Devotees Count</span>
                            <p class="info-value">
                                @if($package && $package->devotees_count !== null)
                                    {{ $package->devotees_count }}
                                    {{ $package->devotees_count == 1 ? 'Devotee' : 'Devotees' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="info-box package-highlight">
                        <div class="info-icon icon-green">
                            <i class="fa fa-inr"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Package Price</span>
                            <p class="info-value">
                                @if($packagePrice !== null)
                                    {{ $currency }} {{ number_format($packagePrice, 2) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-green">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Package Status</span>
                            <p class="info-value">
                                @if($package)
                                    @if($package->status)
                                        <span class="status-badge badge-success">Active</span>
                                    @else
                                        <span class="status-badge badge-secondary">Inactive</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon icon-orange">
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Recommended Package</span>
                            <p class="info-value">
                                @if($package && $package->is_recommended)
                                    <span class="status-badge badge-warning">Yes</span>
                                @else
                                    <span class="status-badge badge-secondary">No</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <span class="info-label">
                        <i class="fa fa-align-left mr-1"></i>
                        Package Description
                    </span>

                    @if($package && $package->description)
                        <div class="package-description-box">
                            {{ $package->description }}
                        </div>
                    @else
                        <div class="empty-state">
                            No package description available.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    {{-- 4. TEMPLE DETAILS --}}
    <div class="booking-section theme-green">

        <div class="booking-section-header">
            <div class="section-heading">
                <div class="section-icon">
                    <i class="fa fa-university"></i>
                </div>

                <div>
                    <h5>Temple Details</h5>
                    <p>Temple location and address information</p>
                </div>
            </div>
        </div>

        <div class="booking-section-body">

            @if($puja)

                <div class="row">

                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-green">
                                <i class="fa fa-university"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Temple Name</span>
                                <p class="info-value">
                                    {{ $puja->temple_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-blue">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">City</span>
                                <p class="info-value">
                                    {{ $puja->temple_city ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="info-box">
                            <div class="info-icon icon-orange">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">State</span>
                                <p class="info-value">
                                    {{ $puja->temple_state ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="temple-address-box">
                            <div class="address-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>

                            <div>
                                <span class="info-label">Temple Full Address</span>
                                <p>
                                    {{ $puja->temple_address ?? 'Address not available' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            @else
                <div class="empty-state">
                    <i class="fa fa-info-circle mr-1"></i>
                    Temple details are not available.
                </div>
            @endif

        </div>
    </div>


    {{-- 5. PAYMENT DETAILS --}}
    <div class="booking-section theme-pink">

        <div class="booking-section-header">
            <div class="section-heading">
                <div class="section-icon">
                    <i class="fa fa-credit-card"></i>
                </div>

                <div>
                    <h5>Payment Details</h5>
                    <p>Booking amount and payment status</p>
                </div>
            </div>
        </div>

        <div class="booking-section-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <div class="payment-highlight">
                        <p class="payment-label">
                            <i class="fa fa-money mr-1"></i>
                            Total Booking Amount
                        </p>

                        <h3 class="payment-amount">
                            {{ $currency }} {{ number_format($bookingAmount, 2) }}
                        </h3>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="payment-status-box">
                        <p class="payment-label">
                            <i class="fa fa-shield mr-1"></i>
                            Payment Status
                        </p>

                        <h5 class="mb-0">
                            <span class="status-badge badge-{{ $paymentBadge }}">
                                <i class="fa fa-check-circle mr-1"></i>
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </h5>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan