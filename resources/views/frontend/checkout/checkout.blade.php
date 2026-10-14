@extends('frontend.layouts.app')
@section('title', 'Puja Checkout')
@section('content')
    @php
        $temple = $puja->temple;
        $location = collect([
            $temple?->city?->name,
            $temple?->district?->name,
            $temple?->state?->name,
        ])->filter()->unique()->implode(', ');
        $image = $puja->banner_image ?: $puja->image;
        $imageUrl = null;
        if ($image) {
            $image = ltrim($image, '/');
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                $imageUrl = $image;
            } else {
                $image = preg_replace('#^(storage/|public/)#', '', $image);
                $imageUrl = asset('storage/' . $image);
            }
        }
        $hasDiscount =
            $package->discount_price !== null &&
            $package->discount_price !== '' &&
            $package->discount_price < $package->price;
        $finalPrice = $hasDiscount ? $package->discount_price : $package->price;
        $personCount = (int) ($package->person_count ?? 1);
    @endphp
    <section class="checkout-section">
        <div class="container">
            <div class="checkout-header">
                <div>
                    <span class="checkout-subtitle">BOOK YOUR PUJA</span>
                    <h1>Puja Checkout</h1>
                    <p>Review your puja and package details before proceeding.</p>
                </div>
                <a href="{{ url()->previous() }}" class="back-button">
                    <i class="fa fa-arrow-left"></i>
                    Back
                </a>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-om"></i>
                            </div>
                            <div>
                                <h3>Puja Details</h3>
                                <p>Information about your selected puja</p>
                            </div>
                        </div>
                        <div class="puja-information">
                            @if($imageUrl)
                                <div class="puja-image-wrapper">
                                    <img src="{{ $imageUrl }}" alt="{{ $puja->name }}" class="puja-image">
                                </div>
                            @endif
                            <div class="puja-information-content">
                                @if($puja->pujaCategory)
                                    <span class="category-badge">
                                        {{ $puja->pujaCategory->name }}
                                    </span>
                                @endif
                                <h2>{{ $puja->name }}</h2>
                                @if($puja->puja_code)
                                    <p>
                                        <i class="fa fa-hashtag"></i>
                                        <strong>Puja Code:</strong>
                                        {{ $puja->puja_code }}
                                    </p>
                                @endif
                                @if($puja->deity)
                                    <p>
                                        <i class="fa fa-om"></i>
                                        <strong>Deity:</strong>
                                        {{ $puja->deity->name }}
                                    </p>
                                @endif
                                @if($puja->duration)
                                    <p>
                                        <i class="fa fa-clock-o"></i>
                                        <strong>Duration:</strong>
                                        {{ $puja->duration }}
                                    </p>
                                @endif
                                @if($puja->is_online)
                                    <span class="mode-badge">
                                        <i class="fa fa-video-camera"></i>
                                        Online Puja
                                    </span>
                                @endif
                                @if($puja->is_offline)
                                    <span class="mode-badge">
                                        <i class="fa fa-home"></i>
                                        Offline Puja
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($puja->description)
                            <div class="checkout-detail-section">
                                <h4>About Puja</h4>
                                <div class="description-content">
                                    {!! $puja->description !!}
                                </div>
                            </div>
                        @endif
                        @if($puja->benefits)
                            <div class="checkout-detail-section">
                                <h4>Benefits of Puja</h4>
                                <div class="description-content">
                                    {!! nl2br(e($puja->benefits)) !!}
                                </div>
                            </div>
                        @endif
                        @if($puja->ritual_details)
                            <div class="checkout-detail-section">
                                <h4>Puja Process</h4>
                                <div class="description-content">
                                    {!! nl2br(e($puja->ritual_details)) !!}
                                </div>
                            </div>
                        @endif
                        @if($puja->samagri_details)
                            <div class="checkout-detail-section">
                                <h4>Puja Samagri</h4>
                                <div class="description-content">
                                    {!! nl2br(e($puja->samagri_details)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-university"></i>
                            </div>
                            <div>
                                <h3>Temple Details</h3>
                                <p>Temple information and location</p>
                            </div>
                        </div>
                        @if($temple)
                            <div class="temple-information-grid">
                                <div class="temple-information-item">
                                    <span>Temple Name</span>
                                    <strong>{{ $temple->name }}</strong>
                                </div>
                                <div class="temple-information-item">
                                    <span>City</span>
                                    <strong>{{ $temple->city?->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="temple-information-item">
                                    <span>District</span>
                                    <strong>{{ $temple->district?->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="temple-information-item">
                                    <span>State</span>
                                    <strong>{{ $temple->state?->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="temple-information-item full-width">
                                    <span>Location</span>
                                    <strong>{{ $location ?: 'N/A' }}</strong>
                                </div>
                                @if($temple->address)
                                    <div class="temple-information-item full-width">
                                        <span>Full Address</span>
                                        <strong>{{ $temple->address }}</strong>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="empty-message">
                                Temple details are not available.
                            </div>
                        @endif
                    </div>
                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <h3>Devotee Information</h3>
                                <p>Logged-in account information</p>
                            </div>
                        </div>
                        <div class="devotee-information">
                            <div class="devotee-information-item">
                                <span>Name</span>
                                <strong>{{ auth()->user()->name }}</strong>
                            </div>
                            <div class="devotee-information-item">
                                <span>Email</span>
                                <strong>{{ auth()->user()->email }}</strong>
                            </div>
                            @if(auth()->user()->phone)
                                <div class="devotee-information-item">
                                    <span>Mobile Number</span>
                                    <strong>{{ auth()->user()->phone }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="package-summary-card">
                        <div class="package-summary-header">
                            <i class="fa fa-shopping-bag"></i>
                            <h3>Booking Summary</h3>
                        </div>
                        <div class="package-summary-body">
                            <span class="summary-label">Selected Puja</span>
                            <h4>{{ $puja->name }}</h4>
                            <div class="summary-divider"></div>
                            <span class="summary-label">Selected Package</span>
                            <h4>{{ $package->name }}</h4>
                            @if($package->short_description)
                                <p class="package-description">
                                    {{ $package->short_description }}
                                </p>
                            @endif
                            <div class="summary-detail">
                                <span>
                                    <i class="fa fa-users"></i>
                                    Devotees
                                </span>
                                <strong>
                                    {{ $personCount }}
                                    {{ $personCount === 1 ? 'Person' : 'Persons' }}
                                </strong>
                            </div>               
                            <div class="summary-divider"></div>
                            @if($hasDiscount)
                                <div class="summary-detail">
                                    <span>Original Price</span>
                                    <span class="original-price">
                                        ₹{{ number_format($package->price, 2) }}
                                    </span>
                                </div>
                                <div class="summary-detail">
                                    <span>Discount</span>
                                    <strong class="discount-text">
                                        -₹{{ number_format(
                                            $package->price - $finalPrice,
                                            2
                                        ) }}
                                    </strong>
                                </div>
                            @endif
                            <div class="summary-total">
                                <span>Total Amount</span>
                                <strong>
                                    ₹{{ number_format($finalPrice, 2) }}
                                </strong>
                            </div>
                            <p class="price-note">
                                Final package price before any additional charges.
                            </p>
                            <button type="button"  class="checkout-payment-button" disabled>
                                Continue to Payment
                                <i class="fa fa-arrow-right"></i>
                            </button>
                            <p class="payment-note">
                                <i class="fa fa-lock"></i>
                                Secure payment will be available after payment
                                gateway integration.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
<style>
    .checkout-section {
        padding: 45px 0 70px;
        background: #fffaf5;
        color: #29211c;
    }
    .checkout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }
    .checkout-subtitle {
        color: #d71968;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }
    .checkout-header h1 {
        margin: 8px 0;
        font-size: 32px;
        font-weight: 700;
    }
    .checkout-header p {
        margin: 0;
        color: #777;
    }
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid #eadfd5;
        border-radius: 8px;
        color: #29211c;
        background: #fff;
        text-decoration: none;
        white-space: nowrap;
    }
    .back-button:hover {
        color: #d71968;
        border-color: #d71968;
        text-decoration: none;
    }
    .checkout-card,
    .package-summary-card {
        margin-bottom: 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f0e6dd;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
    }
    /* Keep only the right-side Booking Summary sticky on desktop. */
    .package-summary-card {
        position: sticky;
        top: 100px;
        z-index: 10;
    }
    .checkout-card {
        padding: 25px;
    }
    .checkout-card-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #eee;
    }
    .heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        color: #d71968;
        background: #fff0f5;
        font-size: 20px;
    }
    .checkout-card-heading h3 {
        margin: 0 0 5px;
        font-size: 21px;
        font-weight: 700;
    }
    .checkout-card-heading p {
        margin: 0;
        color: #888;
        font-size: 13px;
    }
    .puja-information {
        display: flex;
        align-items: flex-start;
        gap: 22px;
    }
    .puja-image-wrapper {
        flex: 0 0 42%;
        max-width: 42%;
    }
    .puja-image {
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 10px;
    }
    .puja-information-content {
        flex: 1;
        min-width: 0;
    }
    .puja-information-content h2 {
        margin: 12px 0 18px;
        font-size: 25px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }
    .puja-information-content p {
        margin-bottom: 12px;
        color: #666;
        font-size: 14px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }
    .puja-information-content p i {
        width: 20px;
        color: #d71968;
    }
    .category-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        color: #d71968;
        background: #fff0f5;
        font-size: 12px;
        font-weight: 700;
    }
    .mode-badge {
        display: inline-block;
        margin: 4px 5px 0 0;
        padding: 6px 10px;
        border-radius: 20px;
        color: #34743b;
        background: #f0f8ed;
        font-size: 12px;
        font-weight: 600;
    }
    .checkout-detail-section {
        margin-top: 25px;
        padding-top: 22px;
        border-top: 1px solid #eee;
    }
    .checkout-detail-section h4 {
        margin-bottom: 12px;
        font-size: 17px;
        font-weight: 700; 
    }
    .description-content {
        color: #666;
        font-size: 14px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }
    .description-content img {
        max-width: 100%;
        height: auto;
    }
    .temple-information-grid,
    .devotee-information {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    .temple-information-item,
    .devotee-information-item {
        min-width: 0;
        padding: 15px;
        border: 1px solid #f0e6dd;
        border-radius: 9px;
        background: #fffaf5;
    }
    .temple-information-item span,
    .devotee-information-item span {
        display: block;
        margin-bottom: 7px;
        color: #888;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .temple-information-item strong,
    .devotee-information-item strong {
        display: block;
        color: #29211c;
        font-size: 14px;
        overflow-wrap: anywhere;
    }
    .full-width {
        grid-column: 1 / -1;
    }
    .package-summary-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 22px;
        color: #fff;
        background: #d71968;
    }
    .package-summary-header i {
        font-size: 22px;
    }
    .package-summary-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }
    .package-summary-body {
        padding: 24px;
    }
    .summary-label {
        display: block;
        margin-bottom: 7px;
        color: #888;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .package-summary-body h4 {
        margin-bottom: 10px;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }
    .package-description {
        margin: 8px 0 15px;
        color: #777;
        font-size: 13px;
        line-height: 1.7;
    }
    .summary-divider {
        margin: 20px 0;
        border-top: 1px solid #eee;
    }
    .summary-detail {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 13px;
    }
    .summary-detail > span:first-child {
        color: #777;
    }
    .summary-detail strong {
        text-align: right;
        overflow-wrap: anywhere;
    }
    .summary-detail i {
        margin-right: 5px;
        color: #d71968;
    }
    .original-price {
        color: #999;
        text-decoration: line-through;
    }
    .discount-text {
        color: #198754;
    }
    .summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid #eee;
    }
    .summary-total span {
        font-size: 15px;
        font-weight: 600;
    }
    .summary-total strong {
        color: #d71968;
        font-size: 25px;
        font-weight: 700;
        white-space: nowrap;
    }
    .price-note,
    .payment-note {
        margin-top: 12px;
        color: #888;
        font-size: 12px;
        line-height: 1.6;
    }
    .checkout-payment-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        margin-top: 22px;
        padding: 14px 16px;
        border: 0;
        border-radius: 8px;
        color: #fff;
        background: #198754;
        font-size: 15px;
        font-weight: 700;
    }
    .checkout-payment-button:disabled {
        cursor: not-allowed;
        opacity: .65;
    }
    .payment-note {
        text-align: center;
    }
    .payment-note i {
        margin-right: 5px;
    }
    .empty-message {
        color: #777;
        font-size: 14px;
    }
    @media (max-width: 991px) {
        .checkout-section {
            padding: 30px 0 45px;
        }
        .puja-image {
            height: 200px;
        }
  
        .package-summary-card {
            position: static;
        }
    }
    @media (max-width: 575px) {
        .checkout-header {
            align-items: flex-start;
        }
        .checkout-header h1 {
            font-size: 26px;
        }
        .checkout-header p {
            font-size: 13px;
        }
        .back-button {
            padding: 9px 11px;
            font-size: 12px;
        }
        .checkout-card {
            padding: 18px;
        }
        .puja-information {
            flex-direction: column;
        }
        .puja-image-wrapper {
            flex: auto;
            max-width: 100%;
            width: 100%;
        }
        .puja-image {
            height: 220px;
        }
        .puja-information-content h2 {
            font-size: 22px;
        }
        .temple-information-grid,
        .devotee-information {
            grid-template-columns: 1fr;
        }
        .full-width {
            grid-column: auto;
        }
        .package-summary-body {
            padding: 20px;
        }
    }
</style>
@endpush