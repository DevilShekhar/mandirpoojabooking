@extends('frontend.layouts.app')

@section('content')

@php
$temple = $puja->temple;

$location = collect([
$temple?->city?->name,
$temple?->district?->name,
$temple?->state?->name,
])->filter()->unique()->implode(', ');

$image = $puja->banner_image ?: $puja->image;

$imageUrl = $image
? asset('storage/' . ltrim($image, '/'))
: null;
@endphp

<section class="puja-detail-section">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.puja') }}">Our Pujas</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $puja->name }}
                </li>
            </ol>
        </nav>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="puja-detail-image-wrapper">
                    @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $puja->name }}" class="puja-detail-image">
                    @else
                    <div class="puja-detail-placeholder">
                        <i class="fa fa-om"></i>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="puja-overview">
                    @if($puja->pujaCategory)
                    <span class="detail-category">
                        {{ $puja->pujaCategory->name }}
                    </span>
                    @endif

                    <h1 class="detail-title mt-3">
                        {{ $puja->name }}
                    </h1>

                    @if($puja->puja_code)
                    <p class="detail-meta">
                        <strong>Puja Code:</strong>
                        {{ $puja->puja_code }}
                    </p>
                    @endif

                    @if($puja->deity)
                    <p class="detail-meta">
                        <i class="fa fa-om"></i>
                        <strong>Deity:</strong>
                        {{ $puja->deity->name }}
                    </p>
                    @endif

                    @if($temple)
                    <p class="detail-meta">
                        <i class="fa fa-university"></i>
                        <strong>Temple:</strong>
                        {{ $temple->name }}
                    </p>
                    @endif

                    @if($location)
                    <p class="detail-meta">
                        <i class="fa fa-map-marker"></i>
                        {{ $location }}
                    </p>
                    @endif

                    @if($puja->duration)
                    <p class="detail-meta">
                        <i class="fa fa-clock-o"></i>
                        <strong>Duration:</strong>
                        {{ $puja->duration }}
                    </p>
                    @endif

                    <div class="detail-modes mb-4">
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
            </div>
        </div>

        <div class="puja-detail-tabs-wrapper">
            <ul class="puja-detail-tabs nav" id="pujaDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="about-puja-tab" data-toggle="tab" href="#about-puja" role="tab"
                        aria-controls="about-puja" aria-selected="true">
                        About Puja
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="temple-details-tab" data-toggle="tab" href="#temple-details" role="tab"
                        aria-controls="temple-details" aria-selected="false">
                        Temple Details
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="benefits-tab" data-toggle="tab" href="#benefits" role="tab"
                        aria-controls="benefits" aria-selected="false">
                        Benefits
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="process-tab" data-toggle="tab" href="#process" role="tab"
                        aria-controls="process" aria-selected="false">
                        Process
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="packages-tab" data-toggle="tab" href="#packages" role="tab"
                        aria-controls="packages" aria-selected="false">
                        Packages
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
                        aria-controls="reviews" aria-selected="false">
                        Reviews
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="faqs-tab" data-toggle="tab" href="#faqs" role="tab" aria-controls="faqs"
                        aria-selected="false">
                        FAQs
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content puja-tab-content" id="pujaDetailTabsContent">
            <div class="tab-pane fade show active" id="about-puja" role="tabpanel" aria-labelledby="about-puja-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-om"></i>
                        </div>
                        <div>
                            <h3>About {{ $puja->name }}</h3>
                            <p>Know more about this sacred puja</p>
                        </div>
                    </div>

                    @if($puja->description)
                    <div class="detail-tab-content">
                        {!! $puja->description !!}
                    </div>
                    @else
                    <div class="empty-tab-content">
                        <i class="fa fa-info-circle"></i>
                        <p>Details about this puja will be available soon.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="temple-details" role="tabpanel" aria-labelledby="temple-details-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-university"></i>
                        </div>
                        <div>
                            <h3>Temple Details</h3>
                            <p>Information about the temple</p>
                        </div>
                    </div>

                    @if($temple)
                    <div class="temple-detail-grid">
                        <div class="temple-detail-item">
                            <span class="temple-detail-label">Temple Name</span>
                            <strong>{{ $temple->name }}</strong>
                        </div>

                        @if($location)
                        <div class="temple-detail-item">
                            <span class="temple-detail-label">Location</span>
                            <strong>{{ $location }}</strong>
                        </div>
                        @endif

                        @if($temple->address)
                        <div class="temple-detail-item temple-detail-full">
                            <span class="temple-detail-label">Address</span>
                            <strong>{{ $temple->address }}</strong>
                        </div>
                        @endif

                        @if($temple->pincode ?? false)
                        <div class="temple-detail-item">
                            <span class="temple-detail-label">PIN Code</span>
                            <strong>{{ $temple->pincode }}</strong>
                        </div>
                        @endif

                        @if($puja->deity)
                        <div class="temple-detail-item">
                            <span class="temple-detail-label">Deity</span>
                            <strong>{{ $puja->deity->name }}</strong>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="empty-tab-content">
                        <i class="fa fa-university"></i>
                        <p>Temple information is not available.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-star-o"></i>
                        </div>
                        <div>
                            <h3>Benefits of the Puja</h3>
                            <p>Spiritual significance and benefits</p>
                        </div>
                    </div>

                    @if($puja->benefits)
                    <div class="detail-tab-content">
                        {!! nl2br(e($puja->benefits)) !!}
                    </div>
                    @else
                    <div class="empty-tab-content">
                        <i class="fa fa-star-o"></i>
                        <p>Benefits information will be available soon.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="process" role="tabpanel" aria-labelledby="process-tab">
                <div class="row">
                    @if($puja->ritual_details)
                    <div class="col-lg-6 mb-4">
                        <div class="process-card">
                            <div class="process-card-icon">
                                <i class="fa fa-heart-o"></i>
                            </div>
                            <h3>Puja Process</h3>
                            <div class="process-card-content">
                                {!! nl2br(e($puja->ritual_details)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($puja->samagri_details)
                    <div class="col-lg-6 mb-4">
                        <div class="process-card">
                            <div class="process-card-icon">
                                <i class="fa fa-shopping-basket"></i>
                            </div>
                            <h3>Puja Samagri</h3>
                            <div class="process-card-content">
                                {!! nl2br(e($puja->samagri_details)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$puja->ritual_details && !$puja->samagri_details)
                    <div class="col-12">
                        <div class="empty-tab-content">
                            <i class="fa fa-list-alt"></i>
                            <p>Puja process information will be available soon.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="packages" role="tabpanel" aria-labelledby="packages-tab">

                @php
                /*
                |--------------------------------------------------------------------------
                | Packages
                |--------------------------------------------------------------------------
                */
                $packages = $puja->packages;

                /*
                |--------------------------------------------------------------------------
                | Default Package
                |--------------------------------------------------------------------------
                | Featured package first.
                | If no featured package exists, first package will be selected.
                */
                $defaultPackage = $packages->firstWhere('is_featured', 1);

                if (!$defaultPackage) {
                $defaultPackage = $packages->first();
                }

                /*
                |--------------------------------------------------------------------------
                | Default Price
                |--------------------------------------------------------------------------
                */
                $defaultPrice = null;

                if ($defaultPackage) {

                $hasDefaultDiscount =
                $defaultPackage->discount_price !== null &&
                $defaultPackage->discount_price !== '' &&
                $defaultPackage->discount_price < $defaultPackage->price;

                    $defaultPrice = $hasDefaultDiscount
                    ? $defaultPackage->discount_price
                    : $defaultPackage->price;
                    }
                    @endphp


                    <div class="detail-tab-card puja-package-section">

                        {{-- ==========================================================
             HEADER
        =========================================================== --}}
                        <div class="detail-tab-heading">

                            <div class="detail-tab-icon">
                                <i class="fa fa-users"></i>
                            </div>

                            <div>
                                <h3>Select your puja package</h3>

                                <p>
                                    Select a package according to the number of devotees
                                </p>
                            </div>

                        </div>


                        {{-- ==========================================================
             PACKAGES AVAILABLE
        =========================================================== --}}
                        @if($packages->count())

                        <div class="puja-package-grid">

                            @foreach($packages as $package)

                            @php

                            /*
                            |--------------------------------------------------------------------------
                            | Person Count
                            |--------------------------------------------------------------------------
                            */
                            $personCount = (int) ($package->person_count ?? 1);

                            $personText = $personCount === 1
                            ? 'Person'
                            : 'Persons';


                            /*
                            |--------------------------------------------------------------------------
                            | Featured
                            |--------------------------------------------------------------------------
                            */
                            $isFeatured = (int) $package->is_featured === 1;


                            /*
                            |--------------------------------------------------------------------------
                            | Discount
                            |--------------------------------------------------------------------------
                            */
                            $hasDiscount =
                            $package->discount_price !== null &&
                            $package->discount_price !== '' &&
                            $package->discount_price < $package->price;


                                /*
                                |--------------------------------------------------------------------------
                                | Final Price
                                |--------------------------------------------------------------------------
                                */
                                $finalPrice = $hasDiscount
                                ? $package->discount_price
                                : $package->price;


                                /*
                                |--------------------------------------------------------------------------
                                | Selected Package
                                |--------------------------------------------------------------------------
                                */
                                $isSelected =
                                $defaultPackage &&
                                $defaultPackage->id == $package->id;


                                /*
                                |--------------------------------------------------------------------------
                                | Package Image
                                |--------------------------------------------------------------------------
                                |
                                | Controller stores:
                                |
                                | puja_packages/filename.jpg
                                |
                                | Therefore frontend URL:
                                |
                                | /storage/puja_packages/filename.jpg
                                |
                                |--------------------------------------------------------------------------
                                */
                                $packageImageUrl = null;

                                if (!empty($package->package_image)) {

                                $packageImage = trim($package->package_image);

                                /*
                                | If DB already contains complete URL
                                */
                                if (
                                str_starts_with($packageImage, 'http://') ||
                                str_starts_with($packageImage, 'https://')
                                ) {

                                $packageImageUrl = $packageImage;

                                } else {

                                /*
                                | Remove starting slash
                                */
                                $packageImage = ltrim($packageImage, '/');

                                /*
                                | Prevent:
                                |
                                | storage/storage/...
                                |
                                */
                                $packageImage = preg_replace(
                                '#^(storage/|public/)#',
                                '',
                                $packageImage
                                );

                                /*
                                | Generate storage URL
                                */
                                $packageImageUrl = asset(
                                'storage/' . $packageImage
                                );
                                }
                                }

                                @endphp


                                {{-- ==================================================
                         PACKAGE CARD
                    =================================================== --}}
                                <div class="puja-package-card
                        {{ $isSelected ? 'is-selected' : '' }}
                        {{ $isFeatured ? 'is-featured' : '' }}" data-package-id="{{ $package->id }}"
                                    data-package-name="{{ e($package->name) }}" data-package-price="{{ $finalPrice }}">


                                    {{-- Featured Badge --}}
                                    @if($isFeatured)

                                    <div class="package-featured-badge">

                                        <i class="fa fa-star"></i>

                                        Popular

                                    </div>

                                    @endif


                                    {{-- Person Badge --}}
                                    <div class="package-person-badge">

                                        <i class="fa fa-user"></i>

                                        <span>
                                            {{ $personCount }}
                                            {{ $personText }}
                                        </span>

                                    </div>


                                    {{-- Selected Check --}}
                                    <div class="package-check-icon">

                                        @if($isSelected)

                                        <i class="fa fa-check"></i>

                                        @endif

                                    </div>


                                    {{-- Package Information --}}
                                    <div class="package-card-content">


                                        {{-- Package Name --}}
                                        <h4 class="package-title">

                                            {{ $package->name }}

                                        </h4>


                                        {{-- Short Description --}}
                                        @if($package->short_description)

                                        <p class="package-short-description">

                                            {{ $package->short_description }}

                                        </p>

                                        @endif


                                        {{-- ==================================================
                                 PACKAGE IMAGE
                            =================================================== --}}
                                        @if($packageImageUrl)

                                        <div class="package-image">

                                            <img src="{{ $packageImageUrl }}" alt="{{ $package->name }}"
                                                class="package-image-img" loading="lazy">

                                        </div>

                                        @endif


                                        {{-- ==================================================
                                 PRICE
                            =================================================== --}}
                                        <div class="package-price-box">

                                            @if($hasDiscount)

                                            <span class="package-old-price">

                                                ₹{{ number_format($package->price, 0) }}

                                            </span>

                                            @endif


                                            <span class="package-final-price">

                                                ₹{{ number_format($finalPrice, 0) }}

                                            </span>

                                        </div>


                                        {{-- Discount --}}
                                        @if($hasDiscount)

                                        @php
                                        $discountPercent = $package->price > 0
                                        ? round(
                                        (($package->price - $package->discount_price)
                                        / $package->price) * 100
                                        )
                                        : 0;
                                        @endphp

                                        <span class="package-discount">

                                            {{ $discountPercent }}% OFF

                                        </span>

                                        @endif


                                    </div>

                                </div>

                                @endforeach

                        </div>


                        {{-- ==========================================================
                 BOTTOM SECTION
            =========================================================== --}}
                        <div class="package-bottom-wrapper">


                            {{-- ======================================================
                     TRUST BOX
                ======================================================= --}}
                            <div class="package-trust-box">

                                <div class="package-trust-item">

                                    <i class="fa fa-certificate"></i>

                                    <span>
                                        ISO 27001 Certified Company
                                    </span>

                                </div>


                                <div class="package-trust-item">

                                    <i class="fa fa-check-circle"></i>

                                    <span>
                                        Official Temple Partner
                                    </span>

                                </div>


                                <div class="package-trust-item">

                                    <i class="fa fa-headphones"></i>

                                    <span>
                                        Customer Support
                                    </span>

                                </div>

                            </div>


                            {{-- ======================================================
                     SELECTED PACKAGE
                ======================================================= --}}
                            <div class="package-selected-box">


                                <div class="selected-package-details">

                                    <strong id="selectedPackagePrice">

                                        @if($defaultPrice !== null)

                                        ₹{{ number_format($defaultPrice, 0) }}

                                        @else

                                        ₹0

                                        @endif

                                    </strong>


                                    <span id="selectedPackageName">

                                        {{ $defaultPackage->name ?? 'Select Package' }}

                                    </span>

                                </div>


                                <button type="button" id="proceedPackageBtn" class="package-proceed-button">

                                    <span>
                                        Proceed
                                    </span>

                                    <i class="fa fa-arrow-right"></i>

                                </button>

                            </div>

                        </div>


                        {{-- ==========================================================
                 SELECTED PACKAGE ID
            =========================================================== --}}
                        <input type="hidden" id="selected_package_id" name="package_id"
                            value="{{ $defaultPackage->id ?? '' }}">


                        @else

                        {{-- ==========================================================
                 NO PACKAGES
            =========================================================== --}}
                        <div class="package-empty">

                            <i class="fa fa-inbox"></i>

                            <h4>
                                No Packages Available
                            </h4>

                            <p>
                                Packages are currently not available for this puja.
                            </p>

                        </div>

                        @endif

                    </div>

            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-star-o"></i>
                        </div>
                        <div>
                            <h3>Puja Reviews & Ratings</h3>
                            <p>See what devotees say about this puja</p>
                        </div>
                    </div>
                    <div class="text-right mb-4">
                        @auth
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addReviewModal">
                            <i class="fa fa-star"></i> Add Review
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fa fa-sign-in"></i> Login to Add Review
                        </a>
                        @endauth
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($reviews->count() > 0)
                    <div class="review-list">
                        @foreach($reviews as $review)
                        <div class="review-item mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        {{ $review->user->name ?? 'Devotee' }}
                                    </h5>
                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                                            <i class="fa fa-star"></i>
                                            @else
                                            <i class="fa fa-star-o"></i>
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $review->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <p class="mt-2 mb-0">
                                {{ $review->review }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="review-empty">
                        <div class="review-empty-icon">
                            <i class="fa fa-comments-o"></i>
                        </div>
                        <h4>No Reviews Yet</h4>
                        <p>
                            Be the first devotee to share your experience
                            after participating in this puja.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-question-circle-o"></i>
                        </div>
                        <div>
                            <h3>Frequently Asked Questions</h3>
                            <p>Common questions about this puja</p>
                        </div>
                    </div>

                    @if($puja->faqs && $puja->faqs->count())
                    <div class="puja-faq">
                        @foreach($puja->faqs as $index => $faq)
                        <div class="faq-item">
                            <button type="button" class="faq-question {{ $index === 0 ? '' : 'collapsed' }}"
                                data-toggle="collapse" data-target="#faq{{ $faq->id }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="faq{{ $faq->id }}">
                                <span>{{ $faq->question }}</span>
                                <i class="fa fa-angle-down"></i>
                            </button>

                            <div id="faq{{ $faq->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}">
                                <div class="faq-answer">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-tab-content">
                        <i class="fa fa-question-circle-o"></i>
                        <p>Frequently asked questions will be available soon.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@auth
<div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">
                    <i class="fa fa-star"></i>
                    Add Your Review
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('our-puja.review.store', $puja->id) }}" method="POST">

                @csrf

                <div class="modal-body">

                    {{-- Rating --}}
                    <div class="form-group">
                        <label>
                            Rating <span class="text-danger">*</span>
                        </label>

                        <div class="review-rating-input">

                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="rating{{ $i }}" name="rating" value="{{ $i }}"
                                {{ old('rating') == $i ? 'checked' : '' }}>

                            <label for="rating{{ $i }}">
                                <i class="fa fa-star"></i>
                            </label>
                            @endfor

                        </div>
                    </div>

                    {{-- Review --}}
                    <div class="form-group">
                        <label for="review">
                            Your Review <span class="text-danger">*</span>
                        </label>

                        <textarea name="review" id="review" class="form-control" rows="5" maxlength="2000"
                            placeholder="Share your experience about this puja..."
                            required>{{ old('review') }}</textarea>

                        <small class="text-muted">
                            Maximum 2000 characters.
                        </small>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i>
                        Submit Review
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@endauth
@push('scripts')

<script>
$(document).ready(function() {

    /*
    |--------------------------------------------------------------------------
    | Select Package
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.puja-package-card', function() {

        const $card = $(this);

        /*
        | Remove previous selection
        */
        $('.puja-package-card')
            .removeClass('is-selected');

        $('.package-check-icon')
            .empty();


        /*
        | Select clicked package
        */
        $card.addClass('is-selected');

        $card.find('.package-check-icon').html(
            '<i class="fa fa-check"></i>'
        );


        /*
        | Package data
        */
        const packageId =
            $card.attr('data-package-id');

        const packageName =
            $card.attr('data-package-name');

        const packagePrice =
            parseFloat(
                $card.attr('data-package-price')
            ) || 0;


        /*
        | Update hidden package ID
        */
        $('#selected_package_id')
            .val(packageId);


        /*
        | Update selected package name
        */
        $('#selectedPackageName')
            .text(packageName);


        /*
        | Update selected package price
        */
        $('#selectedPackagePrice')
            .text(
                '₹' +
                packagePrice.toLocaleString('en-IN')
            );

    });


    /*
    |--------------------------------------------------------------------------
    | Proceed Button
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '#proceedPackageBtn', function() {

        const packageId =
            $('#selected_package_id').val();


        if (!packageId) {

            alert('Please select a package.');

            return;

        }


        /*
        | Your booking URL
        |
        | Change this according to your booking route.
        */
        window.location.href =
            '#booking?package_id=' + packageId;

    });

});
</script>

@endpush
@endsection
@push('styles')
<style>
.review-rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 4px;
}

.review-rating-input input {
    display: none;
}

.review-rating-input label {
    cursor: pointer;
    font-size: 28px;
    color: #ccc;
    margin: 0;
    transition: color .2s ease;
}

.review-rating-input label:hover,
.review-rating-input label:hover~label,
.review-rating-input input:checked~label {
    color: #ffc107;
}

.review-rating-input label i {
    cursor: pointer;
}

.puja-detail-section {
    background: #fffaf5;
    color: #29211c;
    padding: 50px 0 70px;
}

.puja-detail-section .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.puja-detail-section .breadcrumb a {
    color: #ea0505;
    text-decoration: none;
}

.puja-detail-section .breadcrumb-item.active {
    color: #777;
}

.puja-detail-image-wrapper {
    height: 390px;
    overflow: hidden;
    border-radius: 14px;
    background: #fff0df;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .07);
}

.puja-detail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.puja-detail-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #ea0505;
    font-size: 80px;
}

.puja-overview {
    height: 100%;
    padding: 30px;
    background: #fff;
    border: 1px solid #f0e6dd;
    border-radius: 14px;
}

.detail-category {
    display: inline-block;
    padding: 7px 13px;
    color: #ea0505;
    background: #fff0f0;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.detail-title {
    color: #29211c;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 20px;
}

.detail-meta {
    color: #666;
    font-size: 14px;
    line-height: 1.8;
    overflow-wrap: anywhere;
    margin-bottom: 8px;
}

.detail-meta i {
    width: 20px;
    color: #ea0505;
    margin-right: 6px;
}

.detail-description {
    color: #666;
    font-size: 15px;
    line-height: 1.9;
    margin-top: 20px;
}

.detail-modes {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.mode-badge {
    padding: 7px 13px;
    color: #34743b;
    background: #f0f8ed;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.mode-badge i {
    margin-right: 5px;
}

.back-to-pujas-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    padding: 11px 16px;
    background: #ea0505;
    color: #fff;
    border-radius: 7px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.back-to-pujas-btn:hover {
    background: #c90000;
    color: #fff;
}

.puja-detail-tabs-wrapper {
    position: sticky;
    top: 0;
    z-index: 20;
    background: #fff;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #ddd;
}

.puja-detail-tabs {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
    width: 100%;
    margin: 0;
    padding: 0;
    border: 0;
}

.puja-detail-tabs .nav-item {
    flex: 1;
    text-align: center;
}

.puja-detail-tabs .nav-link {
    position: relative;
    display: block;
    padding: 20px 12px;
    color: #555;
    border: 0;
    border-radius: 0;
    background: transparent;
    font-size: 15px;
    font-weight: 500;
    white-space: nowrap;
}

.puja-detail-tabs .nav-link:after {
    position: absolute;
    right: 0;
    bottom: -1px;
    left: 0;
    height: 2px;
    background: transparent;
    content: "";
}

.puja-detail-tabs .nav-link:hover {
    color: #ea0505;
}

.puja-detail-tabs .nav-link.active {
    color: #ea0505;
    background: transparent;
    font-weight: 600;
}

.puja-detail-tabs .nav-link.active:after {
    background: #ea0505;
}

.puja-tab-content {
    padding-top: 35px;
}

.detail-tab-card {
    padding: 30px;
    background: #fff;
    border: 1px solid #f0e6dd;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
}

.detail-tab-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-bottom: 20px;
    margin-bottom: 22px;
    border-bottom: 1px solid #eee;
}

.detail-tab-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    color: #ea0505;
    background: #fff0f0;
    border-radius: 50%;
    font-size: 20px;
}

.detail-tab-heading h3 {
    margin: 0 0 5px;
    color: #29211c;
    font-size: 22px;
    font-weight: 700;
}

.detail-tab-heading p {
    margin: 0;
    color: #888;
    font-size: 13px;
}

.detail-tab-content {
    color: #666;
    font-size: 15px;
    line-height: 2;
    overflow-wrap: anywhere;
}

.temple-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.temple-detail-item {
    padding: 18px;
    background: #fffaf5;
    border: 1px solid #f0e6dd;
    border-radius: 10px;
}

.temple-detail-full {
    grid-column: 1 / -1;
}

.temple-detail-label {
    display: block;
    margin-bottom: 7px;
    color: #999;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.temple-detail-item strong {
    color: #29211c;
    font-size: 14px;
    line-height: 1.7;
}

.process-card {
    height: 100%;
    padding: 28px;
    background: #fff;
    border: 1px solid #f0e6dd;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
}

.process-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    margin-bottom: 18px;
    color: #ea0505;
    background: #fff0f0;
    border-radius: 50%;
    font-size: 20px;
}

.process-card h3 {
    margin-bottom: 15px;
    color: #29211c;
    font-size: 20px;
    font-weight: 700;
}

.process-card-content {
    color: #666;
    font-size: 14px;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

.package-card {
    position: relative;
    height: 100%;
    padding: 30px 20px;
    text-align: center;
    background: #fffaf5;
    border: 1px solid #f0e6dd;
    border-radius: 14px;
    transition: all .3s ease;
}

.package-card:hover {
    transform: translateY(-4px);
    border-color: #ea0505;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .07);
}

.featured-package {
    border-color: #ea0505;
}

.package-popular {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 5px 9px;
    color: #fff;
    background: #ea0505;
    border-radius: 15px;
    font-size: 10px;
    font-weight: 700;
}

.package-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    margin: 0 auto 18px;
    color: #ea0505;
    background: #fff0f0;
    border-radius: 50%;
    font-size: 24px;
}

.package-card h4 {
    margin-bottom: 10px;
    color: #29211c;
    font-size: 19px;
    font-weight: 700;
}

.package-card p {
    min-height: 45px;
    margin-bottom: 20px;
    color: #777;
    font-size: 13px;
    line-height: 1.7;
}

.package-btn {
    display: inline-block;
    padding: 9px 18px;
    color: #fff;
    background: #ea0505;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

.package-btn:hover {
    color: #fff;
    background: #c90000;
}

.review-empty {
    padding: 45px 20px;
    text-align: center;
}

.review-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    color: #ea0505;
    background: #fff0f0;
    border-radius: 50%;
    font-size: 28px;
}

.review-empty h4 {
    margin-bottom: 8px;
    color: #29211c;
    font-size: 20px;
    font-weight: 700;
}

.review-empty p {
    margin: 0;
    color: #777;
    font-size: 14px;
}

.puja-faq {
    border: 1px solid #f0e6dd;
    border-radius: 10px;
    overflow: hidden;
}

.faq-item {
    border-bottom: 1px solid #eee;
}

.faq-item:last-child {
    border-bottom: 0;
}

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 18px 20px;
    border: 0;
    background: #fff;
    color: #29211c;
    text-align: left;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
}

.faq-question:hover {
    color: #ea0505;
}

.faq-question i {
    color: #ea0505;
    transition: transform .3s ease;
}

.faq-question:not(.collapsed) i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 20px 20px;
    color: #666;
    font-size: 14px;
    line-height: 1.9;
}

.empty-tab-content {
    padding: 45px 20px;
    text-align: center;
}

.empty-tab-content i {
    display: block;
    margin-bottom: 12px;
    color: #ea0505;
    font-size: 35px;
}

.empty-tab-content p {
    margin: 0;
    color: #777;
    font-size: 14px;
}

@media (max-width: 991px) {
    .puja-detail-tabs-wrapper {
        position: relative;
    }

    .puja-detail-tabs {
        justify-content: flex-start;
        overflow-x: auto;
    }

    .puja-detail-tabs .nav-item {
        flex: 0 0 auto;
    }

    .puja-detail-tabs .nav-link {
        padding: 17px 18px;
    }
}

@media (max-width: 767px) {
    .puja-detail-section {
        padding: 30px 0 50px;
    }

    .puja-detail-image-wrapper {
        height: 260px;
    }

    .puja-overview {
        padding: 20px;
    }

    .detail-title {
        font-size: 26px;
    }

    .detail-tab-card {
        padding: 20px;
    }

    .detail-tab-heading h3 {
        font-size: 19px;
    }

    .temple-detail-grid {
        grid-template-columns: 1fr;
    }

    .temple-detail-full {
        grid-column: auto;
    }

    .puja-detail-tabs .nav-link {
        padding: 15px 14px;
        font-size: 13px;
    }
}

/* =========================================================
   PUJA PACKAGE SECTION
========================================================= */

.puja-package-section {
    width: 100%;
}


/* =========================================================
   PACKAGE GRID
========================================================= */

.puja-package-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin-top: 25px;
}


/* =========================================================
   PACKAGE CARD
========================================================= */

.puja-package-card {
    position: relative;

    height: 290px;

    overflow: hidden;

    background: #ffffff;

    border: 1px solid #e1e5eb;

    border-radius: 12px;

    cursor: pointer;

    transition:
        border-color .25s ease,
        box-shadow .25s ease,
        transform .25s ease;
}


.puja-package-card:hover {
    transform: translateY(-2px);

    border-color: #d71968;

    box-shadow:
        0 6px 18px rgba(0, 0, 0, .08);
}


/* =========================================================
   SELECTED PACKAGE
========================================================= */

.puja-package-card.is-selected {
    border: 2px solid #d71968;

    box-shadow:
        0 5px 18px rgba(215, 25, 104, .12);
}


/* =========================================================
   PERSON BADGE
========================================================= */

.package-person-badge {

    position: absolute;

    top: 15px;
    left: 14px;

    z-index: 5;

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 8px 14px;

    color: #f05a28;

    background: #fff2eb;

    border-radius: 7px;

    font-size: 14px;

    font-weight: 600;

    line-height: 1;
}


/*
|--------------------------------------------------------------------------
| Selected badge
|--------------------------------------------------------------------------
*/

.puja-package-card.is-selected .package-person-badge {

    color: #ffffff;

    background: #d71968;
}


/* =========================================================
   CHECK ICON
========================================================= */

.package-check-icon {

    position: absolute;

    top: 14px;
    right: 14px;

    z-index: 6;

    width: 36px;
    height: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 2px solid #e2e6ed;

    border-radius: 50%;

    background: #ffffff;

    color: #ffffff;

    transition: all .25s ease;
}


/*
|--------------------------------------------------------------------------
| Selected check
|--------------------------------------------------------------------------
*/

.puja-package-card.is-selected .package-check-icon {

    background: #d71968;

    border-color: #d71968;

    color: #ffffff;
}


.package-check-icon i {
    font-size: 17px;
}


/* =========================================================
   PACKAGE TITLE
========================================================= */

.package-title {

    position: absolute;

    top: 68px;

    left: 14px;

    right: 14px;

    z-index: 4;

    margin: 0;

    color: #292929;

    font-size: 23px;

    font-weight: 600;

    line-height: 1.25;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   SHORT DESCRIPTION
========================================================= */

.package-short-description {

    position: absolute;

    top: 108px;

    left: 14px;

    right: 14px;

    z-index: 4;

    margin: 0;

    color: #777;

    font-size: 13px;

    line-height: 1.5;

    max-width: 70%;

}


/* =========================================================
   PACKAGE IMAGE
========================================================= */

.package-image {

    position: absolute;

    right: 0;

    bottom: 0;

    z-index: 2;

    width: 62%;

    height: 185px;

    display: flex;

    align-items: flex-end;

    justify-content: flex-end;

    pointer-events: none;
}


.package-image img {

    display: block;

    width: auto;

    max-width: 100%;

    height: auto;

    max-height: 185px;

    object-fit: contain;
}


/* =========================================================
   PRICE
========================================================= */

.package-price-box {

    position: absolute;

    left: 0;

    bottom: 0;

    z-index: 4;

    min-width: 43%;

    padding: 11px 15px;

    background: rgba(255, 248, 244, .96);
}


.package-final-price {

    color: #f05a28;

    font-size: 25px;

    font-weight: 700;
}


/*
|--------------------------------------------------------------------------
| Selected price
|--------------------------------------------------------------------------
*/

.puja-package-card.is-selected .package-final-price {

    color: #d71968;
}


.package-old-price {

    margin-right: 6px;

    color: #999;

    font-size: 13px;

    text-decoration: line-through;
}


/* =========================================================
   BOTTOM SECTION
========================================================= */

.package-bottom-wrapper {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 25px;

    margin-top: 15px;
}


/* =========================================================
   TRUST BOX
========================================================= */

.package-trust-box {

    min-height: 95px;

    display: flex;

    align-items: center;

    justify-content: space-around;

    gap: 15px;

    padding: 15px;

    background: #f1f1f1;
}


.package-trust-item {

    display: flex;

    align-items: center;

    gap: 9px;

    color: #555;

    font-size: 15px;

    font-weight: 600;

    white-space: nowrap;
}


.package-trust-item i {

    color: #555;

    font-size: 22px;
}


/* =========================================================
   SELECTED PACKAGE / PROCEED BOX
========================================================= */

.package-selected-box {

    min-height: 95px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 15px 25px;

    background: #079669;

    border-radius: 9px;

    color: #ffffff;
}


/* =========================================================
   SELECTED PACKAGE DETAILS
========================================================= */

.selected-package-details {

    display: flex;

    flex-direction: column;

    gap: 5px;
}


.selected-package-details strong {

    color: #ffffff;

    font-size: 25px;

    font-weight: 700;

    line-height: 1.2;
}


.selected-package-details span {

    color: #ffffff;

    font-size: 17px;

    font-weight: 600;

    line-height: 1.3;
}


/* =========================================================
   PROCEED BUTTON
========================================================= */

.package-proceed-button {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 0;

    border: 0;

    background: transparent;

    color: #ffffff;

    font-size: 21px;

    font-weight: 700;

    cursor: pointer;

    white-space: nowrap;
}


.package-proceed-button:hover {

    color: #ffffff;

    text-decoration: none;

}


.package-proceed-button i {

    font-size: 34px;

    transition: transform .2s ease;
}


.package-proceed-button:hover i {

    transform: translateX(5px);
}


/* =========================================================
   EMPTY PACKAGE
========================================================= */

.package-empty {

    padding: 50px 20px;

    text-align: center;

    border: 1px dashed #ddd;

    border-radius: 10px;

    background: #fafafa;
}


.package-empty i {

    display: block;

    margin-bottom: 15px;

    color: #d71968;

    font-size: 40px;
}


.package-empty h4 {

    margin-bottom: 8px;

    color: #333;

    font-size: 20px;
}


.package-empty p {

    margin: 0;

    color: #777;

    font-size: 14px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1200px) {

    .puja-package-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }


    .package-bottom-wrapper {

        grid-template-columns: 1fr;

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .puja-package-grid {

        grid-template-columns: 1fr;

        gap: 15px;

    }


    .puja-package-card {

        height: 280px;

    }


    .package-title {

        font-size: 21px;

    }


    .package-short-description {

        max-width: 75%;

    }


    .package-trust-box {

        flex-direction: column;

        align-items: flex-start;

        justify-content: center;

        padding: 20px;

    }


    .package-trust-item {

        font-size: 14px;

    }


    .package-selected-box {

        padding: 15px;

    }


    .selected-package-details strong {

        font-size: 22px;

    }


    .selected-package-details span {

        font-size: 15px;

    }


    .package-proceed-button {

        font-size: 18px;

    }


    .package-proceed-button i {

        font-size: 28px;

    }

}
</style>

@endpush