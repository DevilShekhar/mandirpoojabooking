<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title', 'Computer Hardware')</title>
    <meta name="keywords" content="@yield('meta_keyword', 'computer hardware, computer parts')">
    <meta name="description" content="@yield('meta_description', 'Computer Hardware')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/frontend/assets/images/favicon.png') }}">
    <!-- Material Design Iconic Font -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/material-design-iconic-font.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/font-awesome.min.css') }}">
    <!-- Font Awesome Stars -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/fontawesome-stars.css') }}">
    <!-- Meanmenu CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/meanmenu.css') }}">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/owl.carousel.min.css') }}">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/slick.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/animate.css') }}">
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/jquery-ui.min.css') }}">
    <!-- Venobox CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/venobox.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/nice-select.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/magnific-popup.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/bootstrap.min.css') }}">
    <!-- Helper CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/helper.css') }}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/style-custom.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/responsive.css') }}">
    <!-- Modernizr JS -->
    <script src="{{ asset('assets/frontend/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    <style>
    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .product-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        z-index: 9999;
        display: none;
        max-height: 400px;
        overflow-y: auto;
    }

    .product-search-item {
        display: block;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #eee;
        background: #fff;
    }

    .product-search-item:hover {
        background: #f5f5f5;
        color: #333;
    }

    .product-search-item .product-name {
        font-size: 14px;
        font-weight: 500;
        display: block;
    }

    .product-search-item .product-sku {
        font-size: 12px;
        color: #999;
        display: block;
        margin-top: 3px;
    }

    .search-no-result {
        padding: 15px;
        color: #777;
        text-align: center;
    }

    /* WhatsApp Floating Button */
    .whatsapp-float {
        position: fixed;
        width: 58px;
        height: 58px;
        bottom: 90px;
        right: 25px;
        background: #25D366;
        color: #fff !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        text-decoration: none !important;
        z-index: 99999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover {
        background: #20b957;
        color: #fff !important;
        transform: scale(1.08);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.30);
    }

    .whatsapp-float i {
        line-height: 1;
    }

    @media (max-width: 767px) {
        .whatsapp-float {
            width: 52px;
            height: 52px;
            bottom: 18px;
            right: 18px;
            font-size: 27px;
        }
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .puja-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        z-index: 99999;
        display: none;
        max-height: 350px;
        overflow-y: auto;
    }

    .puja-search-item {
        display: block;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #eee;
    }

    .puja-search-item:hover {
        background: #f5f5f5;
        color: #ea0505;
        text-decoration: none;
    }

    .puja-search-item .puja-name {
        display: block;
        font-size: 14px;
        font-weight: 500;
    }
    </style>
</head>

<body>
    <div class="body-wrapper">
        <!-- Begin Header Area -->
        <header>
            <!-- Begin Header Top Area -->
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <!-- Begin Header Top Left Area -->
                        <div class="col-lg-3 col-md-4">
                            <div class="header-top-left">
                                <ul class="phone-wrap">
                                    <li><span>Telephone Enquiry:</span><a href="#">(+123) 123 321 345</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Header Top Left Area End Here -->
                        <!-- Begin Header Top Right Area -->
                        <div class="col-lg-9 col-md-8">
                            <div class="header-top-right">
                                <ul class="ht-menu">
                                    <!-- Begin Setting Area -->
                                    <li>
                                        <div class="ht-setting-trigger"><span>Setting</span></div>
                                        <div class="setting ht-setting">
                                            <ul class="ht-setting-list">
                                                <li><a href="login-register.html">My Account</a></li>
                                                <li><a href="checkout.html">Checkout</a></li>
                                                <li><a href="login-register.html">Sign In</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- Setting Area End Here -->
                                    <!-- Begin Currency Area -->
                                    <li>
                                        <span class="currency-selector-wrapper">Currency :</span>
                                        <div class="ht-currency-trigger"><span>USD $</span></div>
                                        <div class="currency ht-currency">
                                            <ul class="ht-setting-list">
                                                <li><a href="#">EUR €</a></li>
                                                <li class="active"><a href="#">USD $</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- Currency Area End Here -->
                                    <!-- Begin Language Area -->
                                    <li>
                                        <span class="language-selector-wrapper">Language :</span>
                                        <div class="ht-language-trigger"><span>English</span></div>
                                        <div class="language ht-language">
                                            <ul class="ht-setting-list">
                                                <li class="active"><a href="#"><img src="images/menu/flag-icon/1.jpg"
                                                            alt="">English</a></li>
                                                <li><a href="#"><img src="images/menu/flag-icon/2.jpg"
                                                            alt="">Français</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- Language Area End Here -->
                                </ul>
                            </div>
                        </div>
                        <!-- Header Top Right Area End Here -->
                    </div>
                </div>
            </div>
            <!-- Header Top Area End Here -->
            <!-- Begin Header Middle Area -->
            <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
                <div class="container">
                    <div class="row">
                        <!-- Begin Header Logo Area -->
                        <div class="col-lg-3">
                            <div class="logo pb-sm-30 pb-xs-30">
                                <a href="index.html">
                                    <img src="{{ asset('assets/frontend/assets/images/menu/logo/logo.png') }}"
                                        alt="Footer Logo" alt="">
                                </a>
                            </div>
                        </div>
                        <!-- Header Logo Area End Here -->
                        <!-- Begin Header Middle Right Area -->
                        <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15 p-4">
                            <form action="{{ route('frontend.puja') }}" method="GET" class="hm-searchbox"
                                id="pujaSearchForm">
                               <select name="temple_id" id="headerTempleSelect" class="nice-select select-search-category">
                                    <option value="">All Temples</option>
                                    @foreach($headerTemples as $temple)
                                        <option value="{{ $temple->slug }}">{{ $temple->name }}</option>
                                    @endforeach
                                </select>
                                <div class="search-input-wrapper">
                                    <input type="text" name="search" id="headerPujaSearch" placeholder="Search Puja..."
                                        autocomplete="off">
                                    <div id="headerPujaResults" class="product-search-results">
                                        @foreach($headerPujas as $puja)
                                        <a href="{{ route('frontend.puja.show', $puja->slug) }}"
                                            class="product-search-item header-puja-item"
                                            data-temple-id="{{ $puja->temple_id }}">
                                            <span class="product-name">{{ $puja->name }}</span>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="li-btn" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>

                            <div class="header-middle-right">
                                <ul class="hm-menu">
                                    <li class="hm-wishlist">
                                        <a href="">
                                            <span class="cart-item-count wishlist-item-count">0</span>
                                            <i class="fa fa-heart-o"></i>
                                        </a>
                                    </li>
                                    <li class="hm-minicart">
                                        <div class="hm-minicart-trigger">
                                            <span class="item-icon"></span>
                                            <span class="item-text">
                                                ₹<span id="minicart-header-total">0.00</span>
                                                <span class="cart-item-count" id="minicart-count">0</span>
                                            </span>
                                        </div>
                                        <span></span>
                                        <div class="minicart">
                                            <ul class="minicart-product-list" id="minicart-product-list"></ul>
                                            <p class="minicart-total">
                                                SUBTOTAL:
                                                <span id="minicart-subtotal">₹0.00</span>
                                            </p>
                                            <div class="minicart-button">
                                                <a href="" class="li-button li-button-fullwidth li-button-dark">View
                                                    Full Cart</a>
                                                <a href="" class="li-button li-button-fullwidth">Checkout</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Header Middle Right Area End Here -->
                    </div>
                </div>
            </div>
            <!-- Header Middle Area End Here -->
            <!-- Begin Header Bottom Area -->
            <div class="header-bottom mb-0 header-sticky stick d-none d-lg-block d-xl-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Header Bottom Menu Area -->
                            <div class="hb-menu">
                                <nav>
                                    <ul>
                                        <li>
                                            <a href="{{ route('frontend.home') }}">Home</a>
                                        </li>
                                        <li>
                                            <a href="">About Us</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.our-temples') }}">Our Temples</a>
                                        </li>
                                        <li>
                                            <a href="">Contact</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.puja') }}">Puja</a>
                                        </li>
                                        <li class="dropdown-holder">
                                            <a href="{{ route('frontend.sanatanevents') }}">Sanatan Events</a>
                                            <ul class="hb-dropdown">
                                                @forelse($headerSanatanEvents as $event)
                                                    <li>
                                                        <a href="{{ route('frontend.sanatanevents.type', ['typeSlug' => \Illuminate\Support\Str::slug($event->program_type)]) }}">
                                                            {{ $event->program_type }}
                                                        </a>
                                                    </li>
                                                @empty
                                                    <li>
                                                        <a href="{{ route('frontend.sanatanevents') }}">All Events</a>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="">My Bookings</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <!-- Header Bottom Menu Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Bottom Area End Here -->
            <!-- Begin Mobile Menu Area -->
            <div class="mobile-menu-area d-lg-none d-xl-none col-12">
                <div class="container">
                    <div class="row">
                        <div class="mobile-menu">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu Area End Here -->
        </header>
        <main>
            @yield('content')
        </main>

        <div class="footer">
            <!-- Begin Footer Static Top Area -->
            <div class="footer-static-top">
                <div class="container">
                    <!-- Begin Footer Shipping Area -->
                    <div class="footer-shipping pt-60 pb-55 pb-xs-25">
                        <div class="row">
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('assets/frontend/assets/images/shipping-icon/1.png') }}"
                                            alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Free Delivery</h2>
                                        <p>And free returns. See checkout for delivery dates.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('assets/frontend/assets/images/shipping-icon/2.png') }}"
                                            alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Safe Payment</h2>
                                        <p>Pay with the world's most popular and secure payment methods.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('assets/frontend/assets/images/shipping-icon/3.png') }}"
                                            alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Shop with Confidence</h2>
                                        <p>Our Buyer Protection covers your purchasefrom click to delivery.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('assets/frontend/assets/images/shipping-icon/4.png') }}"
                                            alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>24/7 Help Center</h2>
                                        <p>Have a question? Call a Specialist or chat online.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                        </div>
                    </div>
                    <!-- Footer Shipping Area End Here -->
                </div>
            </div>
            <!-- Footer Static Top Area End Here -->
            <!-- Begin Footer Static Middle Area -->
            <div class="footer-static-middle">
                <div class="container">
                    <div class="footer-logo-wrap pt-50 pb-35">
                        <div class="row">
                            <!-- Begin Footer Logo Area -->
                            <div class="col-lg-6 col-md-6">
                                <div class="footer-logo">
                                    <img src="{{ asset('assets/frontend/assets/images/menu/logo/logo.png') }}"
                                        alt="Footer Logo">
                                    <p class="info">
                                        computer hardwareis your trusted destination for quality products, competitive
                                        prices, and reliable service. Explore our products, discover leading brands, and
                                        build your perfect PC with our PC Builder.
                                    </p>
                                </div>
                                <ul class="des">
                                    <li>
                                        <span>Address: </span>
                                        6688Princess Road, London, Greater London BAS 23JK, UK
                                    </li>
                                    <li>
                                        <span>Phone: </span>
                                        <a href="#">(+123) 123 321 345</a>
                                    </li>
                                    <li>
                                        <span>Email: </span>
                                        <a href="mailto://info@yourdomain.com">info@yourdomain.com</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Footer Logo Area End Here -->
                            <!-- Begin Footer Quick Links Area -->
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Quick Links</h3>
                                    <ul>
                                        <li>
                                            <a href="">Home</a>
                                        </li>
                                        <li>
                                            <a href="">About Us</a>
                                        </li>
                                        <li>
                                            <a href="">Our Brand</a>
                                        </li>
                                        <li>
                                            <a href="">Our Products</a>
                                        </li>
                                        <li>
                                            <a href="">Discounted Products</a>
                                        </li>
                                        <li>
                                            <a href="">PC Builder</a>
                                        </li>
                                        <li>
                                            <a href="">Contact Us</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="footer-block mb-10">
                                    <h3 class="footer-block-title">Follow Us</h3>
                                    <ul class="social-link">
                                        <li class="twitter">
                                            <a href="https://twitter.com/" data-toggle="tooltip" target="_blank"
                                                title="Twitter">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="rss">
                                            <a href="https://rss.com/" data-toggle="tooltip" target="_blank"
                                                title="RSS">
                                                <i class="fa fa-rss"></i>
                                            </a>
                                        </li>
                                        <li class="google-plus">
                                            <a href="https://www.plus.google.com/discover" data-toggle="tooltip"
                                                target="_blank" title="Google Plus">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                        </li>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank"
                                                title="Facebook">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li class="youtube">
                                            <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank"
                                                title="Youtube">
                                                <i class="fa fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/" data-toggle="tooltip" target="_blank"
                                                title="Instagram">
                                                <i class="fa fa-instagram"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div><br>
                                <div class="footer-block mt-10">
                                    <h3 class="footer-block-title">We Accept</h3>
                                    <div class="footer-payment-methods">
                                        <img src="{{ asset('assets/frontend/assets/images/payment/1.png') }}"
                                            alt="Payment Method 1">
                                    </div>
                                </div>

                            </div>
                            <!-- Footer Block Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Middle Area End Here -->
            <!-- Begin Footer Static Bottom Area -->
            <div class="footer-static-bottom pt-15 pb-15">
                <div class="container">
                    <div class="row align-items-center vh-footer-row">
                        <div class="col-lg-7 col-md-7 vh-footer-copyright">
                            <div class="vh-footer-copy-content">
                                <p>
                                    © 2026 Computer Hardware. All Rights Reserved. Developed &amp; Marketed by
                                    <a href="https://eternalhightech.com/" target="_blank" rel="noopener noreferrer">
                                        <strong>Eternal HighTech</strong>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 vh-footer-policies">
                            <div class="vh-footer-policy-content">
                                <ul>
                                    <li>
                                        <a href="">Privacy Policy</a>
                                    </li>
                                    <li>
                                        <a href="">Terms of Use</a>
                                    </li>
                                    <li>
                                        <a href="">Disclaimer</a>
                                    </li>
                                    <li>
                                        <a href="">Sitemap</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Area End Here -->
        <!-- Begin Quick View | Modal Area -->
        <div class="modal fade modal-wrapper" id="exampleModalCenter">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-inner-area row">
                            <div class="col-lg-5 col-md-6 col-sm-6">
                                <!-- Product Details Left -->
                                <div class="product-details-left">
                                    <div class="product-details-images slider-navigation-1">
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/1.jpg') }}"
                                                alt="product image">
                                        </div>
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/2.jpg') }}"
                                                alt="product image">
                                        </div>
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/3.jpg') }}"
                                                alt="product image">
                                        </div>
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/4.jpg') }}"
                                                alt="product image">
                                        </div>
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/5.jpg') }}"
                                                alt="product image">
                                        </div>
                                        <div class="lg-image">
                                            <img src="{{ asset('assets/frontend/assets/images/product/large-size/6.jpg') }}"
                                                alt="product image">
                                        </div>
                                    </div>
                                    <div class="product-details-thumbs slider-thumbs-1">
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/1.jpg') }}"
                                                alt="product image thumb"></div>
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/2.jpg') }}"
                                                alt="product image thumb"></div>
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/3.jpg') }}"
                                                alt="product image thumb"></div>
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/4.jpg') }}"
                                                alt="product image thumb"></div>
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/5.jpg') }}"
                                                alt="product image thumb"></div>
                                        <div class="sm-image"><img
                                                src="{{ asset('assets/frontend/assets/images/product/small-size/6.jpg') }}"
                                                alt="product image thumb"></div>
                                    </div>
                                </div>
                                <!--// Product Details Left -->
                            </div>

                            <div class="col-lg-7 col-md-6 col-sm-6">
                                <div class="product-details-view-content pt-60">
                                    <div class="product-info">
                                        <h2>Today is a good day Framed poster</h2>
                                        <span class="product-details-ref">Reference: demo_15</span>
                                        <div class="rating-box pt-20">
                                            <ul class="rating rating-with-review-item">
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                <li class="review-item"><a href="#">Read Review</a></li>
                                                <li class="review-item"><a href="#">Write Review</a></li>
                                            </ul>
                                        </div>
                                        <div class="price-box pt-20">
                                            <span class="new-price new-price-2">$57.98</span>
                                        </div>
                                        <div class="product-desc">
                                            <p>
                                                <span>100% cotton double printed dress. Black and white striped top and
                                                    orange high waisted skater skirt bottom. Lorem ipsum dolor sit amet,
                                                    consectetur adipisicing elit. quibusdam corporis, earum facilis et
                                                    nostrum dolorum accusamus similique eveniet quia pariatur.
                                                </span>
                                            </p>
                                        </div>
                                        <div class="product-variants">
                                            <div class="produt-variants-size">
                                                <label>Dimension</label>
                                                <select class="nice-select">
                                                    <option value="1" title="S" selected="selected">40x60cm</option>
                                                    <option value="2" title="M">60x90cm</option>
                                                    <option value="3" title="L">80x120cm</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="single-add-to-cart">
                                            <form action="#" class="cart-quantity">
                                                <div class="quantity">
                                                    <label>Quantity</label>
                                                    <div class="cart-plus-minus">
                                                        <input class="cart-plus-minus-box" value="1" type="text">
                                                        <div class="dec qtybutton"><i class="fa fa-angle-down"></i>
                                                        </div>
                                                        <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                                    </div>
                                                </div>
                                                <button class="add-to-cart" type="submit">Add to cart</button>
                                            </form>
                                        </div>
                                        <div class="product-additional-info pt-25">
                                            <a class="wishlist-btn" href="wishlist.html"><i
                                                    class="fa fa-heart-o"></i>Add to wishlist</a>
                                            <div class="product-social-sharing pt-25">
                                                <ul>
                                                    <li class="facebook"><a href="#"><i
                                                                class="fa fa-facebook"></i>Facebook</a></li>
                                                    <li class="twitter"><a href="#"><i
                                                                class="fa fa-twitter"></i>Twitter</a></li>
                                                    <li class="google-plus"><a href="#"><i
                                                                class="fa fa-google-plus"></i>Google +</a></li>
                                                    <li class="instagram"><a href="#"><i
                                                                class="fa fa-instagram"></i>Instagram</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick View | Modal Area End Here -->
    </div>

    <!-- Body Wrapper End Here -->

    <script src="{{ asset('assets/frontend/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/ajax-mail.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/venobox.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/scrollUp.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/sweetalert/sweetalert-11.js') }}"></script>
    <!-- Main Theme JS -->
    <script src="{{ asset('assets/frontend/assets/js/main.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#headerTempleSelect').on('change', function () {
                const slug = $(this).val();

                if (slug) {
                    window.location.href = "{{ url('/our-temples') }}/" + encodeURIComponent(slug);
                } else {
                    window.location.href = "{{ route('frontend.our-temples') }}";
                }
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        var $pujaSlider = $('.special-puja-slider');
        if (!$pujaSlider.length) {
            return;
        }
        if (typeof $.fn.owlCarousel === 'undefined') {
            console.error('Owl Carousel is not loaded.');
            return;
        }
        if ($pujaSlider.hasClass('owl-loaded')) {
            $pujaSlider.trigger('destroy.owl.carousel');
        }
        $pujaSlider.owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            smartSpeed: 700,
            slideBy: 1,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $('#headerPujaSearch').on('keyup focus', function() {
            let search = $(this).val().toLowerCase().trim();
            let templeId = $('#headerTempleSelect').val();
            let found = false;

            $('#headerPujaResults .header-puja-item').each(function() {
                let pujaName = $(this).find('.product-name').text().toLowerCase();
                let pujaTempleId = $(this).data('temple-id');

                let templeMatch = templeId === '' || String(pujaTempleId) === String(templeId);
                let searchMatch = search === '' || pujaName.includes(search);

                if (templeMatch && searchMatch && search !== '') {
                    $(this).show();
                    found = true;
                } else {
                    $(this).hide();
                }
            });

            if (found) {
                $('#headerPujaResults').show();
            } else {
                $('#headerPujaResults').hide();
            }
        });

        $('#headerTempleSelect').on('change', function() {
            $('#headerPujaSearch').val('').focus();
            $('#headerPujaResults .header-puja-item').hide();
            $('#headerPujaResults').hide();
        });

        $('#pujaSearchForm').on('submit', function(e) {
            let search = $('#headerPujaSearch').val().trim();
            let firstResult = $('#headerPujaResults .header-puja-item:visible').first();

            if (search !== '' && firstResult.length) {
                e.preventDefault();
                window.location.href = firstResult.attr('href');
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-input-wrapper').length) {
                $('#headerPujaResults').hide();
            }
        });
    });
    </script>

    @if(session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#2878f0'
        });
    });
    </script>
    @endif
    @stack('scripts')
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/919767555737?text=Hello%20METAVERSE%20INFO,%20I%20would%20like%20to%20know%20more%20about%20your%20products."
        class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp"
        title="Chat with us on WhatsApp">
        <i class="fa fa-whatsapp"></i>
    </a>
</body>

</html>