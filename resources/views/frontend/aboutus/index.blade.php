@extends('frontend.layouts.app')
@section('title', 'About Us - Online Puja & Vedic Services')
@section('meta_keyword', 'Online Puja, Book Pandit Online, Vedic Rituals, Havan, Puja Samagri, Astrology')
@section('meta_description', 'Book experienced Vedic Pandits for authentic online pujas, havans, and sacred rituals performed according to traditional scriptures.')

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="active">About Us</li>
            </ul>
        </div>
    </div>
</div>
<div class="about-us-wrapper pt-60 pb-40">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-last order-lg-first">
                <div class="about-text-wrap">
                    <h2><span>Sacred Vedic</span> Rituals For You</h2>
                    <p>We connect devotees across the globe with verified, learned Vedic Pandits to perform authentic online pujas, havans, and spiritual ceremonies. Whether you are seeking blessings for a new beginning, peace at home, or planetary remedies, every ritual is conducted with strict adherence to ancient scriptures.</p>
                    <p>Devotees trust us for complete transparency, personalized Sankalpa (name and gotra recitation), and live video streaming so you and your family can participate from anywhere in the world.</p>
                    <p>From Griha Pravesh and Satyanarayan Puja to Rudrabhishek, Navagraha Shanti, and complete Puja Samagri delivery, we bring divine blessings directly to your doorstep.</p>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 col-md-10">
                <div class="about-image-wrap">
                    <img class="img-full" src="{{ asset('assets/frontend/assets/images/product/large-size/aboutcompany.png') }}" alt="About Online Puja Services" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-50">
                <h2>Our Vision &amp; Mission</h2>
                <p>Preserving timeless Sanatan traditions while making sacred rituals accessible to every household.</p>
            </div>
        </div>
        <div class="row align-items-center mb-50">
            <div class="col-lg-6 col-md-6 mb-30 mb-md-0">
                <div class="vision-img">
                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/vision.png') }}" alt="Our Vision" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-eye fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <h3 class="mb-3">Our Vision</h3>
                    <p class="mb-0">To become the most trusted global spiritual platform that bridges devotees with authentic Vedic traditions, ensuring distance never stands in the way of faith, devotion, and divine blessings.</p>
                </div>
            </div>
        </div>
        <div class="row align-items-center">         
            <div class="col-lg-6 col-md-6">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-bullseye fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <h3 class="mb-3">Our Mission</h3>
                    <p class="mb-0">To organize pure, scripture-compliant pujas led by experienced Acharyas, provide complete guidance on muhurat and samagri, and deliver sacred Prasad to devotees with utmost devotion and care.</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-30 mb-md-0">
                <div class="mission-img">
                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/mission.png') }}" alt="Our Mission" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="why-choose-us-wrapper pt-60 pb-40">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-40">
                <div class="section-title">
                    <h2>Why Choose Us</h2>
                    <p>Why thousands of families trust us for their sacred pujas and spiritual ceremonies.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-certificate fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Verified Vedic Pandits</h4>
                    <p class="mb-0">Experienced and knowledgeable Acharyas well-versed in Vedic mantras and traditional vidhi.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-video-camera fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Live Puja Streaming</h4>
                    <p class="mb-0">Join your personalized Sankalpa and watch the entire puja live via high-definition video call.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-gift fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Prasad &amp; Samagri Delivery</h4>
                    <p class="mb-0">100% pure puja essentials arranged on your behalf, with blessed Prasad delivered safely to your home.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-calendar-check-o fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Auspicious Muhurat</h4>
                    <p class="mb-0">Free consultation to choose the most auspicious date and time based on your horoscope and panchang.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection