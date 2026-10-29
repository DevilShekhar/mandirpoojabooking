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
                $packages = $puja->packages;
                $defaultPackage = $packages->firstWhere('is_featured', 1) ?? $packages->first();
                $defaultProducts = collect($defaultPackage->frontend_products ?? []);
                $defaultProductsTotal = (float) $defaultProducts->sum(fn ($product) => (float) ($product['line_total']
                ?? 0));
                $defaultHasDiscount = $defaultPackage && $defaultPackage->discount_price !== null && (float)
                $defaultPackage->price > 0 && (float) $defaultPackage->discount_price < (float) $defaultPackage->price;
                    $defaultDiscountFactor = $defaultHasDiscount ? (float) $defaultPackage->discount_price / (float)
                    $defaultPackage->price : 1;
                    $defaultBasePrice = $defaultPackage ? (float) ($defaultPackage->pandit_price ?? max(0, (float)
                    $defaultPackage->price - $defaultProductsTotal)) : 0;
                    $defaultPrice = $defaultPackage ? round($defaultBasePrice * $defaultDiscountFactor, 2) : null;
                    @endphp
                    <div class="detail-tab-card puja-package-section">
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
                        @if($packages->count())
                        <div class="puja-package-grid">
                            @foreach($packages as $package)
                            @php
                            $personCount = (int) ($package->person_count ?? 1);
                            $personText = $personCount === 1 ? 'Person' : 'Persons';
                            $isFeatured = (int) $package->is_featured === 1;
                            $packageProducts = collect($package->frontend_products ?? []);
                            $productsTotal = (float) $packageProducts->sum(fn ($product) => (float)
                            ($product['line_total'] ?? 0));
                            $hasDiscount = $package->discount_price !== null && (float) $package->price > 0 && (float)
                            $package->discount_price < (float) $package->price;
                                $discountFactor = $hasDiscount ? (float) $package->discount_price / (float)
                                $package->price : 1;
                                $basePrice = (float) ($package->pandit_price ?? max(0, (float) $package->price -
                                $productsTotal));
                                $finalPrice = round($basePrice * $discountFactor, 2);
                                $isSelected = $defaultPackage && $defaultPackage->id == $package->id;
                                $packageImageUrl = null;
                                if (!empty($package->package_image)) {
                                $packageImage = trim($package->package_image);
                                if (
                                str_starts_with($packageImage, 'http://') ||
                                str_starts_with($packageImage, 'https://')
                                ) {
                                $packageImageUrl = $packageImage;
                                } else {
                                $packageImage = ltrim($packageImage, '/');
                                $packageImage = preg_replace(
                                '#^(storage/|public/)#',
                                '',
                                $packageImage
                                );
                                $packageImageUrl = asset(
                                'storage/' . $packageImage
                                );
                                }
                                }
                                @endphp
                                <div class="puja-package-card {{ $isSelected ? 'is-selected is-expanded' : '' }} {{ $isFeatured ? 'is-featured' : '' }}"
                                    data-package-id="{{ $package->id }}" data-package-name="{{ $package->name }}"
                                    data-base-price="{{ $basePrice }}" data-discount-factor="{{ $discountFactor }}">
                                    <div class="package-main-row">
                                        <div class="package-image-wrap">
                                            @if($packageImageUrl)
                                            <img src="{{ $packageImageUrl }}" alt="{{ $package->name }}"
                                                class="package-image-img" loading="lazy">
                                            @else
                                            <div class="package-image-placeholder"><i class="fa fa-om"></i><span>Puja
                                                    Package</span></div>
                                            @endif
                                            @if($isFeatured)
                                            <span class="package-featured-badge"><i class="fa fa-star"></i> Most
                                                Popular</span>
                                            @endif
                                        </div>
                                        <div class="package-main-info">
                                            <div class="package-title-line">
                                                <h4 class="package-title">{{ $package->name }}</h4>
                                                <span class="package-person-badge"><i class="fa fa-users"></i>
                                                    {{ $personCount }} {{ $personText }}</span>
                                            </div>
                                            @if($package->short_description)
                                            <p class="package-short-description">{{ $package->short_description }}</p>
                                            @endif
                                            <div class="package-benefits-row">
                                                <span><i class="fa fa-university"></i> Complete Rituals</span>
                                                <span><i class="fa fa-leaf"></i> Puja Samagri</span>
                                                <span><i class="fa fa-users"></i> Up to {{ $personCount }}
                                                    {{ $personText }}</span>
                                                <span><i class="fa fa-clock-o"></i> Flexible Timing</span>
                                            </div>
                                        </div>
                                        <div class="package-price-column">
                                            @if($hasDiscount)
                                            <span class="package-old-price">₹{{ number_format($basePrice, 0) }}</span>
                                            @endif
                                            <strong
                                                class="package-final-price package-card-current-price">₹{{ number_format($finalPrice, 0) }}</strong>
                                            @if($hasDiscount)
                                            <span class="package-discount">Save
                                                {{ round((1 - $discountFactor) * 100) }}%</span>
                                            @endif
                                        </div>
                                        <div class="package-action-column">
                                            <button type="button"
                                                class="package-select-button">{{ $isSelected ? 'Selected Package' : 'Select this Package' }}
                                                <i class="fa fa-arrow-right"></i></button>
                                        </div>
                                        <button type="button" class="package-expand-toggle"
                                            aria-label="Expand package details"><i
                                                class="fa {{ $isSelected ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i></button>
                                    </div>
                                    <div class="package-expanded-content" {{ $isSelected ? '' : 'hidden' }}>
                                        @if($packageProducts->isNotEmpty())
                                        <div class="package-products">
                                            <div class="package-products-heading">
                                                <div>
                                                    <h5 class="package-products-title"><i class="fa fa-gift"></i>
                                                        Included Puja Samagri <span>(Optional)</span></h5>
                                                    <p>Items are selected by default. Uncheck any item you do not need.
                                                    </p>
                                                </div>
                                                <label class="package-select-all"><input type="checkbox"
                                                        class="package-select-all-checkbox" checked> <span>Select /
                                                        Deselect All</span></label>
                                            </div>
                                            <div class="package-products-table-wrap">
                                                <table class="package-products-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-check-col"></th>
                                                            <th>Product</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th class="text-right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($packageProducts as $product)
                                                        @php
                                                        $productId = $product['product_id'] ?? $product['id'] ?? '';
                                                        $productQuantity = (float) ($product['quantity'] ?? 1);
                                                        $productPrice = (float) ($product['price'] ?? 0);
                                                        $productTotal = (float) ($product['line_total'] ??
                                                        ($productPrice * $productQuantity));
                                                        $productUnit = $product['unit'] ?? 'item(s)';
                                                        @endphp
                                                        <tr class="package-product-row">
                                                            <td class="product-check-col"><input type="checkbox"
                                                                    class="package-product-checkbox" checked
                                                                    data-product-id="{{ $productId }}"
                                                                    data-product-total="{{ $productTotal }}"
                                                                    aria-label="Include {{ $product['name'] ?? 'Puja Samagri' }}">
                                                            </td>
                                                            <td><strong>{{ $product['name'] ?? 'Puja Samagri' }}</strong><small
                                                                    class="product-unit">/ {{ $productUnit }}</small>
                                                            </td>
                                                            <td>₹{{ number_format($productPrice, 2) }}</td>
                                                            <td><input type="number" min="1" step="1"
                                                                    class="package-product-quantity"
                                                                    value="{{ (int) $productQuantity }}"
                                                                    data-unit-price="{{ $productPrice }}"
                                                                    aria-label="Quantity for {{ $product['name'] ?? 'Puja Samagri' }}">
                                                            </td>
                                                            <td class="text-right product-line-total">
                                                                ₹{{ number_format($productTotal, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @else
                                        <div class="package-no-products"><i class="fa fa-info-circle"></i> No additional
                                            samagri is included. You can book this puja package on its own.</div>
                                        @endif
                                        <div class="package-estimated-total">
                                            <div><strong>Estimated Total</strong><span
                                                    class="package-price-caption">Puja package + selected samagri</span>
                                            </div>
                                            <div class="package-total-values">
                                                <strong
                                                    class="package-card-expanded-total">₹{{ number_format($finalPrice, 0) }}</strong>
                                                @if($hasDiscount)<span
                                                    class="package-old-price">₹{{ number_format($basePrice, 0) }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach

                        </div>
                        <div class="package-bottom-wrapper">
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
                        <input type="hidden" id="selected_package_id" name="package_id"
                            value="{{ $defaultPackage->id ?? '' }}">
                        @else
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

    $(document).on('click', '.puja-package-card', function(event) {
        if ($(event.target).closest(
                '.package-product-checkbox, .package-select-all-checkbox, .package-select-all, .package-products-table, .package-select-button'
                ).length) return;
        const $card = $(this);
        $('.puja-package-card').not($card).removeClass('is-selected is-expanded').find(
            '.package-expanded-content').prop('hidden', true);
        $('.puja-package-card').not($card).find('.package-expand-toggle i').removeClass('fa-chevron-up')
            .addClass('fa-chevron-down');
        $card.addClass('is-selected is-expanded').find('.package-expanded-content').prop('hidden',
            false);
        $card.find('.package-expand-toggle i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        $('.package-select-button').text('Select this Package').append(
            ' <i class="fa fa-arrow-right"></i>');
        $card.find('.package-select-button').html('Selected Package <i class="fa fa-check"></i>');
        $('#selected_package_id').val($card.attr('data-package-id'));
        $('#selectedPackageName').text($card.attr('data-package-name'));
        updatePackagePrice($card);
    });

    $(document).on('click', '.package-select-button, .package-expand-toggle', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).closest('.puja-package-card').trigger('click');
    });

    function updatePackagePrice($card) {
        if (!$card || !$card.length) return;
        const basePrice = parseFloat($card.attr('data-base-price')) || 0;
        const discountFactor = parseFloat($card.attr('data-discount-factor')) || 1;
        let selectedProductsTotal = 0;
        $card.find('.package-product-row').each(function() {
            const $row = $(this);
            const $checkbox = $row.find('.package-product-checkbox');
            const $quantity = $row.find('.package-product-quantity');
            const unitPrice = parseFloat($quantity.attr('data-unit-price')) || 0;
            const quantity = Math.max(1, parseInt($quantity.val(), 10) || 1);
            $quantity.val(quantity);
            const lineTotal = unitPrice * quantity;
            $checkbox.attr('data-product-total', lineTotal.toFixed(2));
            $row.find('.product-line-total').text('₹' + lineTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            if ($checkbox.is(':checked')) selectedProductsTotal += lineTotal;
        });
        const currentPrice = Math.max(0, (basePrice * discountFactor) + selectedProductsTotal);
        $card.find('.package-card-current-price, .package-card-expanded-total').text('₹' + currentPrice
            .toLocaleString('en-IN', {
                maximumFractionDigits: 2
            }));
        $card.find('.package-product-checkbox').each(function() {
            $(this).closest('.package-product-row').toggleClass('is-unselected', !$(this).is(
                ':checked'));
        });
        const caption = selectedProductsTotal > 0 ? 'Puja package + selected samagri' : 'Puja package only';
        $card.find('.package-price-caption').text(caption);
        if ($card.hasClass('is-selected')) {
            $('#selectedPackagePrice').text('₹' + currentPrice.toLocaleString('en-IN', {
                maximumFractionDigits: 2
            }));
        }
    }

    $(document).on('click', '.package-product-option, .package-product-checkbox, .package-select-all', function(
        event) {
        event.stopPropagation();
    });

    $(document).on('input change', '.package-product-quantity', function(event) {
        event.stopPropagation();
        updatePackagePrice($(this).closest('.puja-package-card'));
    });

    $(document).on('change', '.package-product-checkbox', function() {
        const $card = $(this).closest('.puja-package-card');
        const total = $card.find('.package-product-checkbox').length;
        const checked = $card.find('.package-product-checkbox:checked').length;
        $card.find('.package-select-all-checkbox').prop('checked', total > 0 && total === checked);
        updatePackagePrice($card);
    });

    $(document).on('change', '.package-select-all-checkbox', function(event) {
        event.stopPropagation();
        const $card = $(this).closest('.puja-package-card');
        $card.find('.package-product-checkbox').prop('checked', $(this).is(':checked'));
        updatePackagePrice($card);
    });

    $(document).on('click', '#proceedPackageBtn', function() {
        const packageId = $('#selected_package_id').val();
        if (!packageId) {
            alert('Please select a package.');
            return;
        }
        const $selectedCard = $('.puja-package-card.is-selected');
        const productIds = [];
        $selectedCard.find('.package-product-checkbox:checked').each(function() {
            const productId = $(this).attr('data-product-id');
            if (productId) productIds.push(productId);
        });
        const params = new URLSearchParams();
        params.set('package_id', packageId);
        productIds.forEach(function(productId) {
            params.append('product_ids[]', productId);
        });
        window.location.href = "{{ route('frontend.checkout') }}" + '?' + params.toString();
    });

    $('.puja-package-card').each(function() {
        const $card = $(this);
        const total = $card.find('.package-product-checkbox').length;
        const checked = $card.find('.package-product-checkbox:checked').length;
        $card.find('.package-select-all-checkbox').prop('checked', total > 0 && total === checked);
        updatePackagePrice($card);
    });

    const $initialCard = $('.puja-package-card.is-selected').first();
    if ($initialCard.length) updatePackagePrice($initialCard);

});
</script>

@endpush

@endsection

@push('styles')
<style>
.package-products {
    padding: 14px;
    background: #fffaf5;
    border: 1px solid #f0e3d5;
    border-radius: 10px;
    margin-top: 14px;
}

.package-products-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
}

.package-products-heading p {
    margin: 5px 0 0;
    color: #76675d;
    font-size: 12px;
    line-height: 1.5;
}

.package-optional-label {
    flex-shrink: 0;
    padding: 5px 9px;
    color: #7a4b13;
    background: #fff0d5;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.package-product-list {
    display: grid;
    gap: 8px;
}

.package-product-option {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    padding: 11px 12px;
    background: #fff;
    border: 1px solid #eee3d9;
    border-radius: 9px;
    cursor: pointer;
    transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
}

.package-product-option:hover {
    border-color: #d8a17a;
}

.package-product-option:has(.package-product-checkbox:checked) {
    background: #fff7ef;
    border-color: #c7773c;
    box-shadow: 0 0 0 1px rgba(199, 119, 60, .08);
}

.package-product-checkbox {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.package-product-checkmark {
    display: inline-flex;
    flex: 0 0 20px;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    color: transparent;
    background: #fff;
    border: 1.5px solid #cbbcaf;
    border-radius: 5px;
    font-size: 11px;
}

.package-product-checkbox:checked+.package-product-checkmark {
    color: #fff;
    background: #a94f19;
    border-color: #a94f19;
}

.package-product-checkbox:focus-visible+.package-product-checkmark {
    outline: 3px solid rgba(169, 79, 25, .25);
    outline-offset: 2px;
}

.package-product-name {
    display: flex;
    flex: 1;
    flex-direction: column;
    min-width: 0;
    gap: 3px;
}

.package-product-name strong {
    color: #392b22;
    font-size: 13px;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.package-product-name small {
    color: #88796f;
    font-size: 11px;
    font-weight: 400;
}

.package-product-price {
    flex-shrink: 0;
    color: #713c1b;
    font-size: 13px;
    font-weight: 700;
}

.package-price-caption {
    display: block;
    margin-top: 5px;
    color: #88796f;
    font-size: 11px;
    font-weight: 500;
}

@media (max-width: 575px) {
    .package-products {
        padding: 11px;
    }

    .package-product-option {
        padding: 10px 9px;
    }

    .package-product-name strong {
        font-size: 12px;
    }

    .package-product-price {
        font-size: 12px;
    }
}

.package-products-title {
    margin-bottom: 12px;
    color: #4b3020;
    font-size: 15px;
    font-weight: 600;
}

.package-products-title i {
    margin-right: 6px;
}

.package-products-table {
    margin-bottom: 0;
    font-size: 13px;
}

.package-products-table th {
    font-weight: 600;
    white-space: nowrap;
}

.package-products-table td,
.package-products-table th {
    padding: 8px 5px;
    vertical-align: middle;
}

.package-products-table td:first-child {
    min-width: 110px;
    overflow-wrap: anywhere;
}
</style>

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

.puja-package-section {

    width: 100%;

}

.puja-package-grid {

    display: grid;

    grid-template-columns: repeat(4, minmax(0, 1fr));

    gap: 20px;

    margin-top: 25px;

}

.puja-package-card {

    position: relative;


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

.puja-package-card.is-selected {

    border: 2px solid #d71968;

    box-shadow:

        0 5px 18px rgba(215, 25, 104, .12);

}

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

.puja-package-card.is-selected .package-person-badge {

    color: #ffffff;

    background: #d71968;

}

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

.puja-package-card.is-selected .package-check-icon {

    background: #d71968;

    border-color: #d71968;

    color: #ffffff;

}

.package-check-icon i {

    font-size: 17px;

}

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

.puja-package-card.is-selected .package-final-price {

    color: #d71968;

}

.package-old-price {

    margin-right: 6px;

    color: #999;

    font-size: 13px;

    text-decoration: line-through;

}

.package-bottom-wrapper {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 25px;

    margin-top: 15px;

}

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

@media (max-width: 1200px) {

    .puja-package-grid {

        grid-template-columns:

            repeat(2, minmax(0, 1fr));

    }

    .package-bottom-wrapper {

        grid-template-columns: 1fr;

    }

}

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



.puja-package-grid {
    display: flex;
    flex-direction: column;
    gap: 14px;
    width: 100%
}

.puja-package-card {
    position: relative;
    display: block;
    width: 100%;
    min-width: 0;
    padding: 14px;
    background: #fff;
    border: 1px solid #f0e2d5;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(87, 50, 20, .04);
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s
}

.puja-package-card:hover {
    border-color: #f7a34a;
    box-shadow: 0 8px 24px rgba(87, 50, 20, .08)
}

.puja-package-card.is-selected {
    border: 1.5px solid #f07812;
    box-shadow: 0 7px 22px rgba(240, 120, 18, .10)
}

.package-main-row {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr) 170px 22px;
    align-items: center;
    gap: 18px;
    position: relative
}

.package-image-wrap {
    height: 126px;
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    background: #fff4e7
}

.package-image-img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover
}

.package-image-placeholder {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #d96a08;
    background: linear-gradient(135deg, #fff4e6, #fce2bd)
}

.package-image-placeholder i {
    font-size: 34px
}

.package-image-placeholder span {
    font-size: 12px;
    font-weight: 700
}

.package-featured-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #ed650b;
    color: #fff;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 11px;
    font-weight: 800
}

.package-main-info {
    min-width: 0
}

.package-title-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 5px
}

.package-title {
    font-size: 22px;
    font-weight: 800;
    color: #382415;
    margin: 0;
    line-height: 1.25
}

.package-person-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    background: #fff0dc;
    color: #9c4a08;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap
}

.package-short-description {
    font-size: 13px;
    line-height: 1.5;
    color: #6d625a;
    margin: 0 0 12px;
    max-width: 100%
}

.package-benefits-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 15px
}

.package-benefits-row span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #6c625b;
    font-size: 12px
}

.package-benefits-row i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: 1px solid #ffe0bb;
    border-radius: 50%;
    color: #e8750c;
    background: #fffaf4;
    font-size: 14px
}

.package-price-column {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
    min-width: 0
}

.package-final-price {
    font-size: 28px;
    font-weight: 900;
    line-height: 1.1;
    color: #e63d0b
}

.package-old-price {
    font-size: 14px;
    color: #89827d;
    text-decoration: line-through
}

.package-discount {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    background: #e0f5d9;
    color: #247c24;
    font-size: 11px;
    font-weight: 800
}

.package-select-button {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-top: 6px;
    padding: 11px 12px;
    border: 0;
    border-radius: 8px;
    background: #e94208;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer
}

.package-select-button:hover {
    background: #c93605
}

.puja-package-card.is-selected .package-select-button {
    background: #13865e
}

.package-expand-toggle {
    border: 0;
    background: transparent;
    color: #72513a;
    font-size: 17px;
    padding: 5px;
    align-self: start;
    cursor: pointer
}

.puja-package-card.is-expanded .package-expand-toggle {
    color: #e76b0b
}

.package-expanded-content {
    padding: 16px 0 0;
    margin-top: 14px;
    border-top: 1px solid #f1e8df
}

.package-expanded-content[hidden] {
    display: none !important
}

.package-products {
    padding: 0;
    border: 0;
    background: transparent
}

.package-products-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 12px
}

.package-products-title {
    font-size: 16px;
    font-weight: 800;
    color: #392719;
    margin: 0 0 4px
}

.package-products-title i {
    color: #e8750c;
    margin-right: 8px
}

.package-products-title span {
    font-weight: 600;
    color: #8c7664;
    font-size: 13px
}

.package-products-heading p {
    font-size: 12px;
    color: #7a7068;
    margin: 0
}

.package-select-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 8px 10px;
    color: #623d20;
    background: #fff5e9;
    border: 1px solid #f5d9b8;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap
}

.package-select-all-checkbox,
.package-product-checkbox {
    width: 16px;
    height: 16px;
    accent-color: #e94b08;
    cursor: pointer
}

.package-products-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #f0e6dc;
    border-radius: 9px
}

.package-products-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 520px;
    background: #fff
}

.package-products-table th {
    padding: 10px 12px;
    background: #fff6eb;
    color: #67401e;
    text-align: left;
    font-size: 12px;
    font-weight: 800
}

.package-products-table td {
    padding: 10px 12px;
    border-top: 1px solid #f2ebe4;
    color: #3e352e;
    font-size: 13px;
    vertical-align: middle
}

.package-products-table th:first-child,
.package-products-table td:first-child {
    width: 44px;
    text-align: center
}

.package-products-table td strong {
    font-size: 13px;
    font-weight: 700
}

.product-unit {
    display: block;
    color: #93877c;
    font-size: 11px;
    margin-top: 2px
}

.package-product-row.is-unselected td {
    color: #a69b91;
    background: #fcfaf8
}

.package-product-row.is-unselected td strong {
    text-decoration: line-through
}

.package-no-products {
    padding: 14px;
    border: 1px dashed #edcfac;
    background: #fffaf4;
    border-radius: 8px;
    color: #7c6854;
    font-size: 13px
}

.package-estimated-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 12px;
    padding: 13px 16px;
    background: #fff3e5;
    border-radius: 9px
}

.package-estimated-total>div:first-child {
    display: flex;
    flex-direction: column;
    gap: 4px
}

.package-estimated-total>div:first-child strong {
    font-family: Georgia, serif;
    font-size: 17px;
    color: #392719
}

.package-price-caption {
    font-size: 11px;
    color: #816a57
}

.package-total-values {
    display: flex;
    align-items: center;
    gap: 10px
}

.package-card-expanded-total {
    font-size: 25px;
    color: #e63d0b;
    font-weight: 900
}

.package-bottom-wrapper {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(280px, .7fr);
    align-items: stretch;
    gap: 16px;
    margin-top: 18px
}

.package-selected-box {
    border-radius: 10px;
    background: #078e67;
    padding: 16px 20px
}

.package-trust-box {
    border-radius: 10px;
    padding: 16px;
    background: #f5f2ee
}

@media(max-width:1100px) {
    .package-main-row {
        grid-template-columns: 190px minmax(0, 1fr) 150px 20px;
        gap: 12px
    }

    .package-benefits-row {
        gap: 7px
    }

    .package-benefits-row span {
        font-size: 11px
    }
}

@media(max-width:767px) {
    .puja-package-card {
        padding: 11px
    }

    .package-main-row {
        grid-template-columns: 100px minmax(0, 1fr) 20px;
        gap: 10px;
        align-items: start
    }

    .package-image-wrap {
        height: 100px;
        grid-row: 1
    }

    .package-main-info {
        grid-column: 2;
        grid-row: 1
    }

    .package-title {
        font-size: 17px
    }

    .package-title-line {
        gap: 6px
    }

    .package-person-badge {
        font-size: 10px;
        padding: 5px 7px
    }

    .package-short-description {
        font-size: 12px;
        margin-bottom: 8px
    }

    .package-benefits-row {
        display: none
    }

    .package-price-column {
        grid-column: 1/3;
        grid-row: 2;
        display: grid;
        grid-template-columns: auto auto 1fr;
        align-items: center;
        gap: 8px;
        margin-top: 5px
    }

    .package-final-price {
        font-size: 23px
    }

    .package-select-button {
        grid-column: 3;
        grid-row: 1/3;
        margin: 0;
        padding: 10px 8px;
        font-size: 11px
    }

    .package-expand-toggle {
        grid-column: 3;
        grid-row: 1
    }

    .package-featured-badge {
        font-size: 9px;
        top: 4px;
        left: 4px;
        padding: 4px 5px
    }

    .package-expanded-content {
        margin-top: 12px;
        padding-top: 12px
    }

    .package-products-heading {
        align-items: flex-start
    }

    .package-products-title {
        font-size: 14px
    }

    .package-products-heading p {
        max-width: 250px
    }

    .package-estimated-total {
        padding: 11px;
        align-items: flex-start
    }

    .package-card-expanded-total {
        font-size: 21px
    }

    .package-total-values {
        flex-wrap: wrap;
        justify-content: flex-end
    }

    .package-bottom-wrapper {
        grid-template-columns: 1fr
    }

    .package-trust-box {
        gap: 8px
    }

    .package-selected-box {
        padding: 14px
    }
}



.puja-package-section {
    width: 100%;
    background: #fff;
    border: 1px solid #f0e2d5;
    border-radius: 16px;
    padding: 28px 30px;
    box-shadow: 0 8px 28px rgba(91, 54, 24, .05)
}

.puja-package-section .detail-tab-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-bottom: 22px;
    margin-bottom: 24px;
    border-bottom: 1px solid #f0e9e2
}

.puja-package-section .detail-tab-heading h3 {
    margin: 0 0 5px;
    color: #342315;
    font-size: 23px;
    font-weight: 800
}

.puja-package-section .detail-tab-heading p {
    margin: 0;
    color: #7d7168;
    font-size: 13px
}

.puja-package-section .detail-tab-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 48px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #fff0e2;
    color: #e85a0a;
    font-size: 21px
}

.puja-package-grid {
    display: flex;
    flex-direction: column;
    gap: 13px;
    width: 100%;
    margin-top: 0
}

.puja-package-card {
    position: relative;
    display: block;
    width: 100%;
    min-width: 0;
    padding: 14px 16px;
    background: #fff;
    border: 1px solid #f1e4d7;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(80, 47, 22, .035);
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s
}

.puja-package-card:hover {
    border-color: #f7a34a;
    box-shadow: 0 7px 20px rgba(160, 87, 22, .08)
}

.puja-package-card.is-selected {
    border: 1.5px solid #ff850d;
    box-shadow: 0 5px 17px rgba(255, 133, 13, .10)
}

.package-main-row {
    display: grid;
    grid-template-columns: 230px minmax(240px, 1fr) 125px 190px 20px;
    align-items: center;
    gap: 18px;
    position: relative
}

.package-image-wrap {
    height: 126px;
    position: relative;
    overflow: hidden;
    border-radius: 9px;
    background: #fff3e4
}

.package-image-img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover
}

.package-image-placeholder {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #d96a08;
    background: linear-gradient(135deg, #fff4e6, #fce2bd)
}

.package-image-placeholder i {
    font-size: 34px
}

.package-image-placeholder span {
    font-size: 12px;
    font-weight: 700
}

.package-featured-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #ed650b;
    color: #fff;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 11px;
    font-weight: 800
}

.package-main-info {
    min-width: 0
}

.package-title-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 5px
}

.package-title {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 23px;
    font-weight: 700;
    color: #352315;
    margin: 0;
    line-height: 1.2
}

.package-person-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    background: #fff0dc;
    color: #9c4a08;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap
}

.package-short-description {
    font-size: 13px;
    line-height: 1.45;
    color: #6d625a;
    margin: 0 0 12px;
    max-width: 100%
}

.package-benefits-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 13px
}

.package-benefits-row span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #6c625b;
    font-size: 12px
}

.package-benefits-row i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    border: 1px solid #ffe0bb;
    border-radius: 50%;
    color: #e8750c;
    background: #fffaf4;
    font-size: 14px
}

.package-price-column {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px 8px;
    min-width: 0
}

.package-final-price {
    font-size: 27px;
    font-weight: 900;
    line-height: 1.1;
    color: #e63d0b;
    white-space: nowrap
}

.package-old-price {
    font-size: 13px;
    color: #89827d;
    text-decoration: line-through;
    white-space: nowrap
}

.package-discount {
    display: inline-block;
    padding: 4px 7px;
    border-radius: 6px;
    background: #e0f5d9;
    color: #247c24;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap
}

.package-action-column {
    min-width: 0
}

.package-select-button {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 15px 14px;
    border: 0;
    border-radius: 8px;
    background: #e94208;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap
}

.package-select-button:hover {
    background: #c93605;
    color: #fff
}

.puja-package-card.is-selected .package-select-button {
    background: #e94208;
    color: #fff
}

.package-expand-toggle {
    position: absolute;
    right: -4px;
    top: -8px;
    border: 0;
    background: transparent;
    color: #72513a;
    font-size: 17px;
    padding: 5px;
    cursor: pointer
}

.puja-package-card.is-expanded .package-expand-toggle {
    color: #e76b0b
}

.package-expanded-content {
    padding: 16px 0 0;
    margin-top: 14px;
    border-top: 1px solid #f1e8df
}

.package-expanded-content[hidden] {
    display: none !important
}

.package-products {
    padding: 0;
    border: 0;
    background: transparent;
    margin: 0
}

.package-products-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 12px
}

.package-products-title {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 18px;
    font-weight: 700;
    color: #392719;
    margin: 0 0 4px
}

.package-products-title i {
    color: #e8750c;
    margin-right: 8px
}

.package-products-title span {
    font-family: inherit;
    font-weight: 600;
    color: #8c7664;
    font-size: 13px
}

.package-products-heading p {
    font-size: 12px;
    color: #7a7068;
    margin: 0
}

.package-select-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 7px 9px;
    color: #2d281f;
    background: #fff;
    border: 0;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap
}

.package-select-all-checkbox,
.package-product-checkbox {
    position: static;
    width: 17px;
    height: 17px;
    opacity: 1;
    accent-color: #e94b08;
    cursor: pointer
}

.package-products-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #f0e6dc;
    border-radius: 9px
}

.package-products-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 520px;
    background: #fff;
    margin: 0
}

.package-products-table th {
    padding: 10px 12px;
    background: #fff6eb;
    color: #67401e;
    text-align: left;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap
}

.package-products-table td {
    padding: 9px 12px;
    border-top: 1px solid #f2ebe4;
    color: #3e352e;
    font-size: 13px;
    vertical-align: middle
}

.package-products-table th:first-child,
.package-products-table td:first-child {
    width: 42px;
    text-align: center
}

.package-products-table td strong {
    font-size: 13px;
    font-weight: 700
}

.product-unit {
    display: block;
    color: #93877c;
    font-size: 11px;
    margin-top: 2px
}

.package-product-quantity {
    width: 78px;
    max-width: 100%;
    height: 39px;
    padding: 6px 10px;
    border: 1px solid #dfe2e7;
    border-radius: 6px;
    text-align: center;
    color: #352f2a;
    background: #fff;
    font-size: 13px
}

.package-product-row.is-unselected td {
    color: #a69b91;
    background: #fcfaf8
}

.package-product-row.is-unselected td strong {
    text-decoration: line-through
}

.package-no-products {
    padding: 14px;
    border: 1px dashed #edcfac;
    background: #fffaf4;
    border-radius: 8px;
    color: #7c6854;
    font-size: 13px
}

.package-estimated-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 12px;
    padding: 14px 18px;
    background: #fff3e5;
    border-radius: 9px
}

.package-estimated-total>div:first-child {
    display: flex;
    flex-direction: column;
    gap: 4px
}

.package-estimated-total>div:first-child strong {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 18px;
    color: #392719
}

.package-price-caption {
    font-size: 11px;
    color: #816a57
}

.package-total-values {
    display: flex;
    align-items: center;
    gap: 10px
}

.package-card-expanded-total {
    font-size: 27px;
    color: #e63d0b;
    font-weight: 900
}

.package-bottom-wrapper {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(300px, .62fr);
    align-items: stretch;
    gap: 16px;
    margin-top: 18px
}

.package-trust-box {
    display: flex;
    align-items: center;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 14px;
    border-radius: 0;
    padding: 18px;
    background: #f4f1ed
}

.package-trust-item {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #554b42;
    font-size: 13px;
    font-weight: 700
}

.package-trust-item i {
    font-size: 17px;
    color: #776a5d
}

.package-selected-box {
    border-radius: 9px;
    background: #078e67;
    padding: 16px 20px;
    color: #fff
}

@media(max-width:1200px) {
    .package-main-row {
        grid-template-columns: 190px minmax(180px, 1fr) 110px 160px 18px;
        gap: 12px
    }

    .package-title {
        font-size: 20px
    }

    .package-benefits-row {
        gap: 7px
    }

    .package-benefits-row span {
        font-size: 11px
    }

    .package-select-button {
        padding: 12px 10px;
        font-size: 11px
    }
}

@media(max-width:900px) {
    .package-main-row {
        grid-template-columns: 160px minmax(0, 1fr) 110px 18px
    }

    .package-image-wrap {
        height: 112px
    }

    .package-action-column {
        grid-column: 2/4
    }

    .package-select-button {
        margin-top: 8px
    }

    .package-price-column {
        grid-column: 3;
        grid-row: 1
    }

    .package-main-info {
        grid-column: 2;
        grid-row: 1
    }

    .package-image-wrap {
        grid-column: 1;
        grid-row: 1
    }

    .package-benefits-row {
        display: none
    }
}

@media(max-width:600px) {
    .puja-package-section {
        padding: 18px 12px;
        border-radius: 12px
    }

    .puja-package-section .detail-tab-heading {
        align-items: flex-start;
        gap: 10px
    }

    .puja-package-section .detail-tab-heading h3 {
        font-size: 19px
    }

    .puja-package-grid {
        gap: 10px
    }

    .puja-package-card {
        padding: 10px
    }

    .package-main-row {
        grid-template-columns: 95px minmax(0, 1fr) 18px;
        gap: 10px;
        align-items: start
    }

    .package-image-wrap {
        grid-column: 1;
        grid-row: 1;
        height: 95px
    }

    .package-main-info {
        grid-column: 2;
        grid-row: 1
    }

    .package-title {
        font-size: 17px
    }

    .package-title-line {
        gap: 5px
    }

    .package-person-badge {
        font-size: 10px;
        padding: 5px 7px
    }

    .package-short-description {
        font-size: 11px;
        margin-bottom: 5px
    }

    .package-benefits-row {
        display: none
    }

    .package-price-column {
        grid-column: 1/3;
        grid-row: 2;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 9px
    }

    .package-final-price {
        font-size: 23px
    }

    .package-old-price {
        font-size: 11px
    }

    .package-discount {
        font-size: 9px
    }

    .package-action-column {
        grid-column: 1/4;
        grid-row: 3
    }

    .package-select-button {
        margin-top: 9px;
        padding: 11px 12px;
        font-size: 12px
    }

    .package-expand-toggle {
        right: -5px;
        top: -5px
    }

    .package-featured-badge {
        font-size: 9px;
        top: 4px;
        left: 4px;
        padding: 4px 5px
    }

    .package-expanded-content {
        margin-top: 12px;
        padding-top: 12px
    }

    .package-products-heading {
        align-items: flex-start
    }

    .package-products-title {
        font-size: 15px
    }

    .package-products-heading p {
        max-width: 250px
    }

    .package-estimated-total {
        padding: 11px;
        align-items: flex-start
    }

    .package-card-expanded-total {
        font-size: 21px
    }

    .package-total-values {
        flex-wrap: wrap;
        justify-content: flex-end
    }

    .package-bottom-wrapper {
        grid-template-columns: 1fr
    }

    .package-trust-box {
        justify-content: flex-start;
        gap: 10px;
        padding: 12px
    }

    .package-trust-item {
        font-size: 11px
    }

    .package-selected-box {
        padding: 14px
    }

    .package-product-quantity {
        width: 60px;
        height: 34px
    }
}


.puja-package-section .puja-package-grid {
    display: flex;
    flex-direction: column;
    gap: 14px;
    width: 100%;
}

.puja-package-section .puja-package-card {
    width: 100%;
    background: #fff;
    border: 1px solid #dcebe2;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(23, 91, 56, .05);
}

.puja-package-section .puja-package-card:hover {
    border-color: #7bb99a;
    box-shadow: 0 8px 22px rgba(23, 91, 56, .09);
}

.puja-package-section .puja-package-card.is-selected {
    border: 1.5px solid #e94208;
    box-shadow: 0 6px 20px rgba(22, 134, 83, .12);
}

.puja-package-section .package-main-row {
    display: grid;
    grid-template-columns: 220px minmax(0, 1fr) 130px 175px 20px;
    align-items: center;
    gap: 18px;
}

.puja-package-section .package-main-info {
    min-width: 0;
    align-self: center;
}

.puja-package-section .package-title {
    position: static;
    inset: auto;
    overflow: visible;
    white-space: normal;
    text-overflow: clip;
    color: #243b2e;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 22px;
    line-height: 1.25;
    font-weight: 800;
}

.puja-package-section .package-person-badge {
    position: static;
    padding: 6px 10px;
    color: #e94208;
    background: #e9f7ef;
}

.puja-package-section .package-short-description {
    position: static;
    max-width: none;
    margin: 0 0 10px;
    color: #65746b;
}

.puja-package-section .package-benefits-row i {
    color: #e94208;
    background: #f2fbf5;
    border-color: #d6eddf;
}

.puja-package-section .package-featured-badge {
    background: #e94208;
}

.puja-package-section .package-price-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-align: center;
}

.puja-package-section .package-final-price,
.puja-package-section .package-card-expanded-total {
    color: #e94208;
}

.puja-package-section .package-discount {
    background: #e2f6e8;
    color: #e94208;
}

.puja-package-section .package-action-column {
    min-width: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    align-self: stretch;
}

.puja-package-section .package-select-button {
    width: 100%;
    min-height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 0;
    padding: 12px 14px;
    border: 1px solid #e94208;
    border-radius: 8px;
    background: #e94208;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
    white-space: normal;
    box-shadow: 0 3px 8px rgba(22, 134, 83, .15);
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.puja-package-section .package-select-button:hover,
.puja-package-section .package-select-button:focus {
    background: #106b41;
    border-color: #106b41;
    color: #fff;
}

.puja-package-section .puja-package-card.is-selected .package-select-button {
    background: #106b41;
    border-color: #106b41;
    color: #fff;
}

.puja-package-section .package-expand-toggle,
.puja-package-section .puja-package-card.is-expanded .package-expand-toggle {
    color: #e94208;
}

.puja-package-section .package-select-all-checkbox,
.puja-package-section .package-product-checkbox {
    accent-color: #e94208;
}

.puja-package-section .package-select-all {
    color: #e94208;
    background: #f0faf4;
    border-color: #d8eddf;
}

.puja-package-section .package-products-title i {
    color: #e94208;
}

.puja-package-section .package-products-table th {
    background: #f0faf4;
    color: #24573d;
}

.puja-package-section .package-estimated-total {
    background: #f0faf4;
}

.puja-package-section .package-selected-box {
    background: #078653;
}

@media (max-width: 1100px) {
    .puja-package-section .package-main-row {
        grid-template-columns: 180px minmax(0, 1fr) 105px 155px 18px;
        gap: 12px;
    }

    .puja-package-section .package-title {
        font-size: 20px;
    }
}

@media (max-width: 900px) {
    .puja-package-section .package-main-row {
        grid-template-columns: 150px minmax(0, 1fr) 100px 18px;
        gap: 12px;
    }

    .puja-package-section .package-action-column {
        grid-column: 2 / 4;
        justify-content: flex-start;
    }

    .puja-package-section .package-select-button {
        max-width: 220px;
        margin-top: 8px;
    }

    .puja-package-section .package-price-column {
        grid-column: 3;
        grid-row: 1;
    }

    .puja-package-section .package-main-info {
        grid-column: 2;
        grid-row: 1;
    }

    .puja-package-section .package-image-wrap {
        grid-column: 1;
        grid-row: 1;
    }

    .puja-package-section .package-benefits-row {
        display: none;
    }
}

@media (max-width: 600px) {
    .puja-package-section {
        padding: 18px 12px;
    }

    .puja-package-section .package-main-row {
        grid-template-columns: 95px minmax(0, 1fr) 18px;
        gap: 10px;
        align-items: start;
    }

    .puja-package-section .package-image-wrap {
        grid-column: 1;
        grid-row: 1;
        height: 95px;
    }

    .puja-package-section .package-main-info {
        grid-column: 2;
        grid-row: 1;
    }

    .puja-package-section .package-title {
        font-size: 17px;
    }

    .puja-package-section .package-title-line {
        gap: 5px;
    }

    .puja-package-section .package-person-badge {
        font-size: 10px;
        padding: 5px 7px;
    }

    .puja-package-section .package-short-description {
        font-size: 11px;
        margin-bottom: 5px;
    }

    .puja-package-section .package-price-column {
        grid-column: 1 / 3;
        grid-row: 2;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        margin-top: 9px;
        text-align: left;
    }

    .puja-package-section .package-final-price {
        font-size: 23px;
    }

    .puja-package-section .package-action-column {
        grid-column: 1 / 4;
        grid-row: 3;
        justify-content: center;
    }

    .puja-package-section .package-select-button {
        max-width: none;
        width: 100%;
        margin-top: 9px;
    }

    .puja-package-section .package-expand-toggle {
        right: -5px;
        top: -5px;
    }
}

.puja-package-section .package-select-button {
    background: #e94208 !important;
    border-color: #e94208 !important;
    color: #fff !important;
}

.puja-package-section .package-select-button:hover,
.puja-package-section .package-select-button:focus {
    background: #c93605 !important;
    border-color: #c93605 !important;
    color: #fff !important;
}

.puja-package-section .puja-package-card.is-selected .package-select-button {
    background: #e94208 !important;
    border-color: #e94208 !important;
    color: #fff !important;
    box-shadow: 0 3px 8px rgba(22, 134, 83, .18);
}

.puja-package-section .puja-package-card.is-selected .package-select-button:hover,
.puja-package-section .puja-package-card.is-selected .package-select-button:focus {
    background: #106b41 !important;
    border-color: #106b41 !important;
}
</style>

@endpush