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

                    @if($puja->description)
                        <p class="detail-description">
                            {{ $puja->description }}
                        </p>
                    @endif

                    <a href="{{ route('frontend.puja') }}" class="back-to-pujas-btn">
                        <i class="fa fa-arrow-left"></i>
                        Back to All Pujas
                    </a>
                </div>
            </div>
        </div>

        <div class="puja-detail-tabs-wrapper">
            <ul class="puja-detail-tabs nav" id="pujaDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="about-puja-tab" data-toggle="tab" href="#about-puja" role="tab" aria-controls="about-puja" aria-selected="true">
                        About Puja
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="temple-details-tab" data-toggle="tab" href="#temple-details" role="tab" aria-controls="temple-details" aria-selected="false">
                        Temple Details
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="benefits-tab" data-toggle="tab" href="#benefits" role="tab" aria-controls="benefits" aria-selected="false">
                        Benefits
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="process-tab" data-toggle="tab" href="#process" role="tab" aria-controls="process" aria-selected="false">
                        Process
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="packages-tab" data-toggle="tab" href="#packages" role="tab" aria-controls="packages" aria-selected="false">
                        Packages
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                        Reviews
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="faqs-tab" data-toggle="tab" href="#faqs" role="tab" aria-controls="faqs" aria-selected="false">
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
                            {!! nl2br(e($puja->description)) !!}
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
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div>
                            <h3>Puja Packages</h3>
                            <p>Select a package according to the number of devotees</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="package-card">
                                <div class="package-icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <h4>1 Person</h4>
                                <p>Individual puja participation</p>
                                <a href="#booking" class="package-btn">Select Package</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="package-card featured-package">
                                <span class="package-popular">Popular</span>
                                <div class="package-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <h4>2 Persons</h4>
                                <p>Perfect for couples or two devotees</p>
                                <a href="#booking" class="package-btn">Select Package</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="package-card">
                                <div class="package-icon">
                                    <i class="fa fa-group"></i>
                                </div>
                                <h4>4 Persons</h4>
                                <p>Ideal for family participation</p>
                                <a href="#booking" class="package-btn">Select Package</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="detail-tab-card">
                    <div class="detail-tab-heading">
                        <div class="detail-tab-icon">
                            <i class="fa fa-star-o"></i>
                        </div>
                        <div>
                            <h3>Devotee Reviews</h3>
                            <p>Experiences shared by devotees</p>
                        </div>
                    </div>

                    <div class="review-empty">
                        <div class="review-empty-icon">
                            <i class="fa fa-comments-o"></i>
                        </div>
                        <h4>No Reviews Yet</h4>
                        <p>Be the first devotee to share your experience after participating in this puja.</p>
                    </div>
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

                    <div class="puja-faq">
                        <div class="faq-item">
                            <button type="button" class="faq-question" data-toggle="collapse" data-target="#faqOne" aria-expanded="true" aria-controls="faqOne">
                                <span>Who can participate in this puja?</span>
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <div id="faqOne" class="collapse show">
                                <div class="faq-answer">
                                    Devotees can participate according to the puja guidelines and available package options.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button type="button" class="faq-question collapsed" data-toggle="collapse" data-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">
                                <span>Can I participate in the puja online?</span>
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <div id="faqTwo" class="collapse">
                                <div class="faq-answer">
                                    @if($puja->is_online)
                                        Yes, this puja supports online participation.
                                    @else
                                        Online participation is not currently available for this puja.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button type="button" class="faq-question collapsed" data-toggle="collapse" data-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                                <span>How long does the puja take?</span>
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <div id="faqThree" class="collapse">
                                <div class="faq-answer">
                                    @if($puja->duration)
                                        The approximate duration of this puja is {{ $puja->duration }}.
                                    @else
                                        The puja duration will be shared during booking.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button type="button" class="faq-question collapsed" data-toggle="collapse" data-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                                <span>What samagri is required for the puja?</span>
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <div id="faqFour" class="collapse">
                                <div class="faq-answer">
                                    @if($puja->samagri_details)
                                        {!! nl2br(e($puja->samagri_details)) !!}
                                    @else
                                        Samagri details will be shared by the temple or pandit.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
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
</style>
@endpush