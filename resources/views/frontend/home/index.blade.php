@extends('frontend.layouts.app')
@section('title', 'Home')
@section('meta_keyword', 'Computer Hardware, Gaming PC, PC Components')
@section('meta_description', 'Premium computer hardware and gaming PC products.')
@section('content')
<div class="slider-with-banner full-screen-slider">
    <div class="slider-area">
        <div class="slider-active owl-carousel">
            @forelse($homeBanners as $homeBanner)
                <div class="single-slide align-center-left animation-style-01"
                    style="background-image: url('{{ asset('storage/' . $homeBanner->image) }}');">
                    <div class="slider-progress"></div>
                    <div class="slider-content">
                        @if($homeBanner->title)
                            <h2>{{ $homeBanner->title }}</h2>
                        @endif
                        @if($homeBanner->short_description)
                            <h5>{{ $homeBanner->short_description }}</h5>
                        @endif
                        @if($homeBanner->btn_name)
                            <div class="default-btn slide-btn">
                                <a class="links" href="{{ $homeBanner->btn_url ?: '#' }}">
                                    {{ $homeBanner->btn_name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="single-slide align-center-left animation-style-01">
                    <div class="slider-progress"></div>
                    <div class="slider-content">
                        <h5>Welcome to Puja Pandit</h5>
                        <h2>Book Your Puja With Experienced Pandits</h2>
                        <div class="default-btn slide-btn">
                            <a class="links" href="#">
                                Explore Puja
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<section class="temples-section">
    <div class="container">
        <div class="temples-header">
            <div>
                <span class="section-subtitle">DIVINE DESTINATIONS</span>
                <h2>Explore Our Temples</h2>
                <p>Discover sacred temples and connect with divine blessings.</p>
            </div>
            <a href="{{ route('frontend.our-temples') }}" class="temple-main-btn">
                Our Temples <i class="fa fa-arrow-right"></i>
            </a>
        </div>
        <div class="row">
            <div class="product-active owl-carousel">
                @forelse($temples as $temple)
                    <div class="col-lg-12">
                        <div class="temple-card">
                            <div class="temple-image">
                                <img src="{{ $temple->main_image ? asset('storage/' . $temple->main_image): asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                                    alt="{{ $temple->name }}">
                                <div class="temple-image-overlay"></div>
                                @if($temple->is_verified)
                                    <span class="verified-badge">
                                        <i class="fa fa-check-circle"></i>
                                        Verified
                                    </span>
                                @endif
                            </div>
                            <div class="temple-content">
                                <span class="temple-location">
                                    <i class="fa fa-map-marker"></i>
                                    {{ $temple->city->name ?? '' }},
                                    {{ $temple->state->name ?? '' }}
                                </span>
                                <h3>{{ $temple->name }}</h3>
                                <a href="{{ route('frontend.our-temple.show', $temple->slug) }}" class="temple-footer">
                                    <span>Explore Temple</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="temple-empty">
                            <i class="fa fa-university"></i>
                            <h4>No Temples Available</h4>
                            <p>Our sacred destinations will be listed here soon.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="special-puja-area pt-60 pb-60 bg-light">
    <div class="container">

        <!-- Heading -->
        <div class="row">
            <div class="col-12">
                <div class="special-puja-heading text-center mb-30">

                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill mb-3">
                        DIVINE EXPERIENCES
                    </span>

                    <h2 class="fw-bold text-dark">
                        <span class="text-danger">All Sanatan</span> Special Pujas
                    </h2>

                    <p class="text-muted">
                        Begin your spiritual journey with sacred rituals.
                        Get special pujas performed in your name at India's
                        powerful temples to achieve peace and protection for your family.
                    </p>

                </div>
            </div>
        </div>

        <!-- Auto Slider - Original Structure Preserved -->
        <div class="row">
            <div class="col-12">
                <div class="special-puja-slider owl-carousel">

                    @forelse($pujas as $puja)

                        <div class="col-lg-4">
                            <div class="special-puja-card bg-white shadow rounded-4 border-0 overflow-hidden mb-4">

                                <!-- Image -->
                                <div class="puja-image position-relative overflow-hidden">

                                    @if($puja->image)
                                        <img src="{{ asset('storage/' . $puja->image) }}"
                                             alt="{{ $puja->name }}"
                                             class="w-100">
                                    @else
                                        <img src="{{ asset('assets/frontend/assets/images/puja/puja-1.jpg') }}"
                                             alt="{{ $puja->name }}"
                                             class="w-100">
                                    @endif

                                </div>

                                <!-- Content -->
                                <div class="puja-content p-4">

                                    <div class="puja-category badge bg-danger text-white rounded-pill px-3 py-2 mb-3">
                                        {{ $puja->pujaCategory->name ?? 'SPECIAL PUJA' }}
                                    </div>

                                    <h3 class="fw-bold text-dark mb-3">
                                        {{ $puja->name }}
                                    </h3>

                                    @if($puja->description)
                                        <p class="puja-description text-muted mb-3">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($puja->description), 120) }}
                                        </p>
                                    @endif

                                    <!-- Details -->
                                    <div class="puja-info border-top border-bottom py-3 mb-3">

                                        @if($puja->temple)
                                            <div class="d-flex align-items-start mb-3">

                                                <i class="fa fa-map-marker text-danger me-3 mt-1"></i>

                                                <span class="text-muted">
                                                    {{ $puja->temple->address ?? $puja->temple->name }}

                                                    @if($puja->temple->city)
                                                        , {{ $puja->temple->city->name }}
                                                    @endif

                                                    @if($puja->temple->state)
                                                        , {{ $puja->temple->state->name }}
                                                    @endif
                                                </span>

                                            </div>
                                        @endif

                                        @if($puja->duration)
                                            <div class="d-flex align-items-center">

                                                <i class="fa fa-clock-o text-danger me-3"></i>

                                                <span class="text-muted ">
                                                    {{ $puja->duration }}
                                                </span>

                                            </div>
                                        @endif

                                    </div>

                                    <!-- Participate -->
                                    <a href="{{ route('frontend.puja.show', ['slug' => $puja->slug]) }}" class="puja-btn btn btn-danger w-100 rounded-pill py-3 fw-semibold">
                                        PARTICIPATE
                                        <i class="fa fa-arrow-right ms-2"></i>
                                    </a>

                                </div>
                            </div>
                        </div>

                    @empty

                        <div class="col-12 text-center">
                            <div class="bg-white shadow-sm rounded-4 p-5">
                                <i class="fa fa-om fa-3x text-danger mb-3"></i>
                                <h4 class="fw-bold">No Pujas Available</h4>
                                <p class="text-muted mb-0">New pujas will be available soon.</p>
                            </div>
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        <!-- View All -->
        <div class="row">
            <div class="col-12 text-center mt-25">

                <a href="{{ route('frontend.puja') }}"
                   class="view-all-pujas btn btn-outline-danger rounded-pill px-4 py-3 fw-semibold">
                    View All Pujas
                    <i class="fa fa-arrow-right ms-2"></i>
                </a>

            </div>
        </div>

    </div>
</section>
<!-- ================= ONLINE PUJA SECTION ================= -->

<section class="special-puja-area pt-60 pb-60 bg-light">

    <div class="container">

        <!-- Heading -->
        <div class="row">
            <div class="col-12">
                <div class="special-puja-heading text-center mb-30">

                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill mb-3">
                        DIVINE EXPERIENCES
                    </span>

                    <h2 class="fw-bold text-dark">
                        <span class="text-danger">Online</span> Special Pujas
                    </h2>

                    <p class="text-muted">
                        Participate in sacred Vedic rituals from anywhere.
                        Book online pujas performed by experienced priests
                        at India's holy temples and receive divine blessings.
                    </p>

                </div>
            </div>
        </div>

        <!-- Online Puja Slider -->
        <div class="row">
            <div class="col-12">

                <div class="special-puja-slider owl-carousel">

                    @forelse($onlinePujas as $onlinePuja)

                        <div class="col-lg-4">

                            <div class="special-puja-card bg-white shadow rounded-4 border-0 overflow-hidden mb-4">

                                <!-- Image -->
                                <div class="puja-image position-relative overflow-hidden">

                                    @if($onlinePuja->thumbnail_image)

                                        <img src="{{ asset('storage/' . $onlinePuja->thumbnail_image) }}"
                                             alt="{{ $onlinePuja->title }}"
                                             class="w-100">

                                    @elseif($onlinePuja->banner_image)

                                        <img src="{{ asset('storage/' . $onlinePuja->banner_image) }}"
                                             alt="{{ $onlinePuja->title }}"
                                             class="w-100">

                                    @else

                                        <img src="{{ asset('assets/frontend/assets/images/puja/puja-1.jpg') }}"
                                             alt="{{ $onlinePuja->title }}"
                                             class="w-100">

                                    @endif

                                    <!-- Featured Badge -->
                                    @if($onlinePuja->is_featured)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill">
                                            Featured
                                        </span>
                                    @endif

                                    @if($onlinePuja->is_popular)
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">
                                            Popular
                                        </span>
                                    @endif

                                </div>

                                <!-- Content -->
                                <div class="puja-content p-4">

                                    <!-- Category -->
                                    <div class="puja-category badge bg-danger text-white rounded-pill px-3 py-2 mb-3">

                                        {{ $onlinePuja->category->name ?? 'ONLINE PUJA' }}

                                    </div>

                                    <!-- Title -->
                                    <h3 class="fw-bold text-dark mb-3">

                                        {{ $onlinePuja->title }}

                                    </h3>

                                    <!-- Description -->
                                    @if($onlinePuja->short_description)

                                        <p class="puja-description text-muted mb-3">

                                            {{ \Illuminate\Support\Str::limit(strip_tags($onlinePuja->short_description), 120) }}

                                        </p>

                                    @endif

                                    <!-- Details -->
                                    <div class="puja-info border-top border-bottom py-3 mb-3">

                                        <!-- Temple -->
                                        @if($onlinePuja->temple_name)

                                            <div class="d-flex align-items-start mb-3">

                                                <i class="fa fa-map-marker text-danger me-3 mt-1"></i>

                                                <span class="text-muted">

                                                    {{ $onlinePuja->temple_name }}

                                                    @if($onlinePuja->temple_city)
                                                        , {{ $onlinePuja->temple_city }}
                                                    @endif

                                                    @if($onlinePuja->temple_state)
                                                        , {{ $onlinePuja->temple_state }}
                                                    @endif

                                                </span>

                                            </div>

                                        @elseif($onlinePuja->temple_location)

                                            <div class="d-flex align-items-start mb-3">

                                                <i class="fa fa-map-marker text-danger me-3 mt-1"></i>

                                                <span class="text-muted">
                                                    {{ $onlinePuja->temple_location }}
                                                </span>

                                            </div>

                                        @endif

                                        <!-- Puja Date -->
                                        @if($onlinePuja->puja_datetime)

                                            <div class="d-flex align-items-center mb-3">

                                                <i class="fa fa-calendar text-danger me-3"></i>

                                                <span class="text-muted">
                                                    {{ $onlinePuja->puja_datetime->format('d M Y, h:i A') }}
                                                </span>

                                            </div>

                                        @endif

                                        <!-- Puja Day -->
                                        @if($onlinePuja->puja_day)

                                            <div class="d-flex align-items-center mb-3">

                                                <i class="fa fa-calendar-check-o text-danger me-3"></i>

                                                <span class="text-muted">
                                                    {{ $onlinePuja->puja_day }}
                                                </span>

                                            </div>

                                        @endif

                                        <!-- Tithi -->
                                        @if($onlinePuja->tithi)

                                            <div class="d-flex align-items-center mb-3">

                                                <i class="fa fa-star text-danger me-3"></i>

                                                <span class="text-muted">
                                                    {{ $onlinePuja->tithi }}
                                                </span>

                                            </div>

                                        @endif

                                        <!-- Booking Seats -->
                                        @if($onlinePuja->total_seat)

                                            <div class="d-flex align-items-center">

                                                <i class="fa fa-users text-danger me-3"></i>

                                                <span class="text-muted">

                                                    {{ max(0, $onlinePuja->total_seat - $onlinePuja->booked_seat) }}
                                                    Seats Available

                                                </span>

                                            </div>

                                        @endif

                                    </div>

                                    <!-- Participate Button -->
                                    <a href="{{ route('frontend.online-puja.show', ['slug' => $onlinePuja->slug]) }}"
                                       class="puja-btn btn btn-danger w-100 rounded-pill py-3 fw-semibold">

                                        PARTICIPATE NOW

                                        <i class="fa fa-arrow-right ms-2"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12 text-center">

                            <div class="bg-white shadow-sm rounded-4 p-5">

                                <i class="fa fa-om fa-3x text-danger mb-3"></i>

                                <h4 class="fw-bold">
                                    No Online Pujas Available
                                </h4>

                                <p class="text-muted mb-0">
                                    New online pujas will be available soon.
                                </p>

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>
        </div>

        <!-- View All -->
        <div class="row">

            <div class="col-12 text-center mt-25">

                <a href="{{ route('frontend.online-puja.index') }}"
                   class="view-all-pujas btn btn-outline-danger rounded-pill px-4 py-3 fw-semibold">

                    View All Online Pujas

                    <i class="fa fa-arrow-right ms-2"></i>

                </a>

            </div>

        </div>

    </div>

</section>

<!-- ================= END ONLINE PUJA SECTION ================= -->

<section class="why-buy-section">
    <div class="container">
        <div class="why-buy-heading text-center">
            <h2>
               One App for all your devotional needs
            </h2>

            <p>Sri Mandir brings these amazing features for you, get these features for free and start your devotional journey now.</p>
        </div>

        <div class="row why-buy-row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>

                    <h3>Authentic Puja Services</h3>

                    <p>
                        Perform traditional pujas with authentic Vedic rituals and proper procedures.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-user"></i>
                    </div>

                    <h3>Experienced Pandits</h3>

                    <p>
                        Connect with experienced and knowledgeable Pandits for your religious ceremonies.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-university"></i>
                    </div>

                    <h3>Renowned Temples</h3>

                    <p>
                        Book puja and devotional services at renowned temples across India.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-calendar"></i>
                    </div>

                    <h3>Easy Puja Booking</h3>

                    <p>
                        Book your preferred puja, package and schedule through a simple process.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-heart"></i>
                    </div>

                    <h3>Personalized Sankalp</h3>

                    <p>
                        Perform pujas with personalized Sankalp details for you and your family.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-video-camera"></i>
                    </div>

                    <h3>Live Puja Experience</h3>

                    <p>
                        Participate in your puja remotely and stay connected with the divine experience.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-gift"></i>
                    </div>

                    <h3>Prasad & Puja Materials</h3>

                    <p>
                        Get Prasad and required puja materials as part of selected puja services.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="why-buy-card">
                    <div class="why-buy-icon">
                        <i class="fa fa-shield"></i>
                    </div>

                    <h3>Secure Payments</h3>

                    <p>
                        Make your bookings with secure and convenient online payment options.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="trusted-devotees-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-12">
                <div class="trusted-devotees-content">
                    <span class="trusted-devotees-label">
                        Trusted by Over 30 Million Devotees
                    </span>
                    <h2>
                        India's Largest Devotional Platform
                    </h2>
                    <p>
                        We are committed to building the most trusted destination
                        that serves the devotional needs of millions of devotees in
                        India and abroad, providing them the access they always wanted.
                    </p>
                </div>
            </div>
            <div class="col-lg-7 col-md-12">
                <div class="trusted-devotees-stats">
                    <div class="trusted-stat-item">
                        <div class="trusted-stat-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <h3>30M+ Devotees</h3>
                        <p>
                            have trusted us in their devotional journey
                        </p>
                    </div>
                    <div class="trusted-stat-item">
                        <div class="trusted-stat-icon">
                            <i class="fa fa-star"></i>
                        </div>
                        <h3>4.5 star rating</h3>
                        <p>
                            Over 1 Lakh devotees express their love for us on playstore
                        </p>
                    </div>
                    <div class="trusted-stat-item">
                        <div class="trusted-stat-icon">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h3>30+ Countries</h3>
                        <p>
                            We help devotees globally reconnect with their devotional heritage
                        </p>
                    </div>
                    <div class="trusted-stat-item">
                        <div class="trusted-stat-icon">
                            <i class="fa fa-fire"></i>
                        </div>
                        <h3>3M+ Services</h3>
                        <p>
                            Millions of devotees have commenced Pooja and Chadhava at
                            famous temples of India with us to seek God's grace.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(isset($news) && $news->isNotEmpty())
<section class="special-puja-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="special-puja-heading text-center mb-30">
                    <h2>
                        <span>Sri Mandir</span> in News
                    </h2>
                    <p>
                        Read the latest news, updates and announcements from Sri Mandir.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="special-puja-slider owl-carousel">
                    @foreach($news as $item)
                        <div class="col-lg-4">
                            <div class="special-puja-card">
                                <div class="puja-image">
                                    @if($item->image)
                                        <img
                                            src="{{ asset('storage/' . $item->image) }}"
                                            alt="{{ $item->title }}"
                                        >
                                    @else
                                        <img
                                            src="{{ asset('assets/frontend/assets/images/news/news-1.jpg') }}"
                                            alt="{{ $item->title }}"
                                        >
                                    @endif
                                </div>

                                <div class="puja-content text-center">
                                    <h3>
                                        {{ $item->title }}
                                    </h3>
                                    @if($item->short_description)
                                        <p class="puja-description">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($item->short_description),
                                                120
                                            ) }}
                                        </p>
                                    @elseif($item->description)
                                        <p class="puja-description">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($item->description),
                                                120
                                            ) }}
                                        </p>
                                    @endif
                                    <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}"
                                       class="puja-btn">
                                        READ MORE
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-25">
                <a href="{{ route('frontend.news') }}" class="view-all-pujas">
                    View All News
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
<section class="why-choose-section">

    <div class="container">

        <div class="why-choose-heading">

            <h2>
                Why Choose Us
            </h2>

        </div>


        <div class="row why-choose-row">


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-choose-card">

                    <div class="why-choose-icon">
                        <i class="fa fa-exchange"></i>
                    </div>

                    <h3>Fast Delivery</h3>

                    <p>
                        Quick and reliable shipping to your doorstep.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-choose-card">

                    <div class="why-choose-icon">
                        <i class="fa fa-shield"></i>
                    </div>

                    <h3>Secure Payment</h3>

                    <p>
                        Your transactions are protected with secure technology.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-choose-card">

                    <div class="why-choose-icon">
                        <i class="fa fa-life-ring"></i>
                    </div>

                    <h3>24/7 Support</h3>

                    <p>
                        Our customer service team is always ready to help.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-choose-card">

                    <div class="why-choose-icon">
                        <i class="fa fa-certificate"></i>
                    </div>

                    <h3>100% New & Genuine</h3>

                    <p>
                        All products are brand new and genuine.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>
<div class="counterup-area">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-3 col-md-6">
                <div class="limupa-counter white-smoke-bg">
                    <div class="container">
                        <div class="counter-img">
                            <img src="{{ asset('assets/frontend/assets/images/about-us/icon/1.png') }}"
                                alt="Happy Customers">
                        </div>
                        <div class="counter-info">
                            <div class="counter-number">
                                <h3 class="counter">
                                    2169
                                </h3>
                            </div>
                            <div class="counter-text">
                                <span>
                                    HAPPY CUSTOMERS
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="limupa-counter gray-bg">
                    <div class="counter-img">
                        <img src="{{ asset('assets/frontend/assets/images/about-us/icon/2.png') }}" alt="Awards">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">
                                869
                            </h3>
                        </div>
                        <div class="counter-text">
                            <span>
                                AWARDS WON
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="limupa-counter white-smoke-bg">
                    <div class="counter-img">
                        <img src="{{ asset('assets/frontend/assets/images/about-us/icon/3.png') }}" alt="Hours Worked">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">
                               689
                            </h3>
                        </div>
                        <div class="counter-text">
                            <span>
                                HOURS WORKED
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="limupa-counter gray-bg">
                    <div class="counter-img">
                        <img src="{{ asset('assets/frontend/assets/images/about-us/icon/4.png') }}"
                            alt="Completed Projects">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">
                               2169
                            </h3>
                        </div>
                        <div class="counter-text">
                            <span>
                                COMPLETE PROJECTS
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection