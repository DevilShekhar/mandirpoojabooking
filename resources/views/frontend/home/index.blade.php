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
<section class="team-area pt-60 pt-sm-44">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="li-section-title capitalize mb-25">
                    <h2>
                        <span>Our Temples</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="product-active owl-carousel">
                @forelse($temples as $temple)
                    <div class="col-lg-12">
                        <div class="team-member mb-60"
                            style="height: 360px; display: flex; flex-direction: column;">

                            <div class="team-thumb"
                                style="height: 220px; flex-shrink: 0; overflow: hidden;">
                                <img
                                    src="{{ $temple->main_image ? asset('storage/' . $temple->main_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                                    alt="{{ $temple->name }}"
                                    style="width:100%;height:220px;object-fit:cover;">
                            </div>

                            <div class="team-content text-center"
                                style="height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px;">

                                <h3 style="margin-bottom: 8px;">
                                    {{ $temple->name }}
                                </h3>

                                <p style="margin-bottom: 10px;">
                                    {{ $temple->city->name ?? '' }},
                                    {{ $temple->state->name ?? '' }}
                                </p>

                                @if($temple->is_verified)
                                    <span class="badge bg-success">
                                        Verified Temple
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No temples available.</h4>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
<section class="special-puja-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="special-puja-heading text-center mb-30">
                    <h2>
                        <span>Sri Mandir</span> Special Pujas
                    </h2>
                    <p>
                        Begin 2026 with faith - get special pujas performed in your name
                        at India's powerful temples to achieve peace and protection for your family.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="special-puja-slider owl-carousel">
                    @forelse($pujas as $puja)
                        <div class="col-lg-4">
                            <div class="special-puja-card">
                                <div class="puja-image">
                                    @if($puja->image)
                                        <img src="{{ asset('storage/' . $puja->image) }}" alt="{{ $puja->name }}">
                                    @else
                                        <img src="{{ asset('assets/frontend/assets/images/puja/puja-1.jpg') }}" alt="{{ $puja->name }}">
                                    @endif
                                </div>

                                <div class="puja-content">
                                    <div class="puja-category">
                                        {{ $puja->pujaCategory->name ?? 'SPECIAL PUJA' }}
                                    </div>

                                    <h3>
                                        {{ $puja->name }}
                                    </h3>

                                    @if($puja->description)
                                        <p class="puja-description">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($puja->description), 120) }}
                                        </p>
                                    @endif

                                    <div class="puja-info">
                                        @if($puja->temple)
                                            <div>
                                                <i class="fa fa-map-marker"></i>
                                                <span>
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
                                            <div>
                                                <i class="fa fa-clock-o"></i>
                                                <span>
                                                    {{ $puja->duration }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <a href="#" class="puja-btn">
                                        PARTICIPATE
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No pujas available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center mt-25">
                <a href="#" class="view-all-pujas">
                    View All Pujas
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
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
<section class="special-puja-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="special-puja-heading text-center mb-30">
                    <h2>Sri Mandir in News</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="special-puja-slider owl-carousel">
                    <div class="col-lg-4">
                        <div class="special-puja-card">
                            <div class="puja-content text-center">
                                <div class="puja-category">द्रिक पंचांग</div>
                                <h3>अयोध्या का विग्रहण: श्री मंदिर एवं बालाजी धाम से जुड़ी विशेष खबर</h3>
                                <p class="puja-description">श्री मंदिर से जुड़ी महत्वपूर्ण धार्मिक खबरें और विशेष समाचार पढ़ें।</p>
                            </div>
                            <div class="puja-image">
                                <img src="{{ asset('assets/frontend/assets/images/news/news-1.jpg') }}" alt="Sri Mandir News">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="special-puja-card">
                            <div class="puja-content text-center">
                                <div class="puja-category">प्रभात खबर</div>
                                <h3>पितृपक्ष में अनुष्ठान का बना बदलाव, श्री मंदिर ऐप से दुनिया भर के श्रद्धालु जुड़ रहे</h3>
                                <p class="puja-description">देश और विदेश के भक्तों के लिए विशेष पूजा और धार्मिक अनुष्ठानों की जानकारी।</p>
                            </div>
                            <div class="puja-image">
                                <img src="{{ asset('assets/frontend/assets/images/news/news-2.jpg') }}" alt="Sri Mandir News">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="special-puja-card">
                            <div class="puja-content text-center">
                                <div class="puja-category">नवभारत टाइम्स</div>
                                <h3>उदय के अतिथि देवों भवः की सेवा और भक्ति लोकपयोगी भी मंदिर सेवा पर</h3>
                                <p class="puja-description">भारत के प्रसिद्ध मंदिरों और धार्मिक आयोजनों से जुड़ी नवीनतम खबरें।</p>
                            </div>
                            <div class="puja-image">
                                <img src="{{ asset('assets/frontend/assets/images/news/news-3.jpg') }}" alt="Sri Mandir News">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="special-puja-card">
                            <div class="puja-content text-center">
                                <div class="puja-category">आज तक</div>
                                <h3>धार्मिक स्थलों और मंदिरों से जुड़ी महत्वपूर्ण खबरें और अपडेट</h3>
                                <p class="puja-description">मंदिरों, पूजा और धार्मिक आयोजनों की नवीनतम जानकारी।</p>
                            </div>
                            <div class="puja-image">
                                <img src="{{ asset('assets/frontend/assets/images/news/news-4.jpg') }}" alt="Sri Mandir News">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="special-puja-card">
                            <div class="puja-content text-center">
                                <div class="puja-category">दैनिक जागरण</div>
                                <h3>भारत के प्रसिद्ध मंदिरों में विशेष पूजा और धार्मिक कार्यक्रमों की जानकारी</h3>
                                <p class="puja-description">श्रद्धालुओं के लिए पूजा और धार्मिक कार्यक्रमों से जुड़ी विशेष जानकारी।</p>
                            </div>
                            <div class="puja-image">
                                <img src="{{ asset('assets/frontend/assets/images/news/news-5.jpg') }}" alt="Sri Mandir News">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-25">
                <a href="#" class="view-all-pujas">View All News <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>
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