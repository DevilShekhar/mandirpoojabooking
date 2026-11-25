@extends('frontend.layouts.app')
@section('title', 'About Us - All Sanatan')
@section('meta_keyword', 'About All Sanatan, Temple Darshan Booking, Pooja Booking, Pandit Booking, Online Pooja Services, Religious Services')
@section('meta_description', 'Learn about All Sanatan, a platform helping devotees access temple darshan, pooja ceremonies, pandit services and selected online religious services.')
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
                    <h2><span>About</span> All Sanatan</h2>
                    <h4>Who We Are</h4>
                    <p>
                        All Sanatan is a platform created to help devotees access religious and spiritual services more conveniently. Our aim is to make it easier for people to explore and arrange temple darshan, pooja ceremonies, pandit services and selected online religious services.
                    </p>
                    <p>
                        We aim to connect devotees with relevant temples, religious service providers and independent pandits, depending on the service offered.
                    </p>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 col-md-10">
                <div class="about-image-wrap">
                    <img class="img-full" src="{{ asset('assets/frontend/assets/images/product/large-size/aboutcompany.png') }}"  alt="About All Sanatan">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-50">
                <h2>Our Purpose</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 mb-30 mb-md-0">
                <div class="vision-img">
                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/vision.png') }}"
                         alt="Our Purpose" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded"
                     style="border-left: 4px solid #EA0505;">
                    <p>
                        Our purpose is to make religious services more accessible through a convenient online platform while respecting the traditions, practices and customs associated with Sanatan Dharma.
                    </p>
                    <p>We aim to help devotees:</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Explore available temple darshan and entry booking options.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Arrange pooja and religious ceremonies.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Find pandits for religious rituals.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Access online pooja services where available.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Participate in live pooja streaming where offered.
                        </li>
                        <li class="mb-0">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Explore festival and special darshan booking options where available.
                        </li>
                    </ul>
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
                    <h2>Our Services</h2>
                    <p>
                        Explore religious and spiritual services available
                        through the All Sanatan platform.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-university fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Temple Darshan & Entry Bookings</h4>
                    <p class="mb-0">
                        Explore available temple darshan and entry booking options
                        through our platform.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-fire fa-3x"  style="color: #EA0505;"></i>
                    </div>
                    <h4>Pooja Bookings</h4>
                    <p class="mb-0">
                        Arrange pooja ceremonies and religious rituals
                        according to available service options.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-users fa-3x"  style="color: #EA0505;"></i>
                    </div>
                    <h4>Pandit Bookings</h4>
                    <p class="mb-0">
                        Find independent pandits for religious ceremonies
                        and traditional rituals.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-praying-hands fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Online Pooja Services</h4>
                    <p class="mb-0">
                        Access selected online pooja services wherever
                        available.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-video-camera fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Live Pooja & Streaming</h4>
                    <p class="mb-0">
                        Participate in live pooja streaming where
                        the service is offered.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-calendar fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>Festival & Special Darshan</h4>
                    <p class="mb-0">
                        Explore festival bookings and special darshan
                        opportunities where available.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                <div class="single-choose-box text-center p-4 border rounded h-100">
                    <div class="choose-icon mb-3">
                        <i class="fa fa-star fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <h4>VIP Darshan</h4>
                    <p class="mb-0">
                        Explore VIP darshan options wherever officially
                        available through the relevant temple or provider.
                    </p>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <p class="text-center">
                    <small>
                        Service availability, terms, pricing and fulfilment
                        arrangements may vary depending on the temple,
                        service provider and location.
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-40">
                <h2> Our Role</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 mb-30 mb-md-0">
                <div class="mission-img">
                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/mission.png') }}"
                         alt="Our Role" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <p>
                        Depending on the service, All Sanatan may provide the service directly or facilitate bookings between devotees and independent temples, pandits or other service providers.
                    </p>
                    <p>
                        The applicable service page and booking details should identify the relevant provider and explain the role of All Sanatan wherever applicable.
                    </p>
                    <p class="mb-0">
                        All Sanatan does not claim to own, manage or officially represent any temple unless expressly stated and supported by appropriate authorisation.
                    </p>
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
                    <h2>Our Commitment</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="single-choose-box p-4 border rounded h-100">
                    <div class="choose-icon mb-3 text-center">
                        <i class="fa fa-handshake-o fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <p class="mb-0">
                        We aim to provide clear service information, transparent booking processes, accessible customer support and responsible handling of customer information.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="single-choose-box p-4 border rounded h-100">
                    <div class="choose-icon mb-3 text-center">
                        <i class="fa fa-file-text-o fa-3x" style="color: #EA0505;"></i>
                    </div>
                    <p class="mb-0">
                        We encourage devotees to review the applicable booking terms and service policies before making a payment.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-40">
                <h2>Contact Us</h2>
                <p>
                    For questions, booking assistance or general enquiries, contact us:
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="single-vision-box p-4 bg-white shadow-sm rounded text-center h-100" style="border-bottom: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-envelope fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <p class="mb-0">
                        <strong>Email:</strong>
                        <a href="mailto:allsanatan@gmail.com">allsanatan@gmail.com</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="single-vision-box p-4 bg-white shadow-sm rounded text-center h-100" style="border-bottom: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-phone fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <p class="mb-0">
                        <strong>Phone:</strong>
                        <a href="tel:9970711238">9970711238</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="single-vision-box p-4 bg-white shadow-sm rounded text-center h-100" style="border-bottom: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-globe fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <p class="mb-0">
                        <strong>Website:</strong>
                        <a href="https://allsanatan.com" target="_blank" rel="noopener noreferrer">https://allsanatan.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="why-choose-us-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <div class="icon mb-3">
                        <i class="fa fa-exclamation-circle fa-2x" style="color: #EA0505;"></i>
                    </div>
                    <h3 class="mb-3"> Important Information</h3>
                    <p>
                        All Sanatan is a brand name. The legal business identity and operating details will be provided in the relevant business disclosures and updated when applicable.
                    </p>
                    <p class="mb-0">
                        All services remain subject to availability, temple rules, provider confirmation and applicable policies.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection