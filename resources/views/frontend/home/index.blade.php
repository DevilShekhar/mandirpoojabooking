@extends('frontend.layouts.app')

@section('title', 'Home')

@section('meta_keyword', 'Computer Hardware, Gaming PC, PC Components')

@section('meta_description', 'Premium computer hardware and gaming PC products.')

@section('content')

<!-- ================================
     HERO SECTION
================================ -->
<div class="slider-with-banner full-screen-slider">

    <div class="slider-area">

        <div class="slider-active owl-carousel">

            <div class="single-slide align-center-left animation-style-01"
                style="background-image: url('{{ asset('assets/frontend/assets/images/slider/1.jpg') }}');">

                <div class="slider-progress"></div>

                <div class="slider-content">

                    <h5>Power Your Performance</h5>

                    <h2>Build Your Dream PC</h2>

                    <div class="default-btn slide-btn">
                        <span class="links">Build Your PC</span>
                    </div>

                </div>

            </div>


            <div class="single-slide align-center-left animation-style-01"
                style="background-image: url('{{ asset('assets/frontend/assets/images/slider/2.jpg') }}');">

                <div class="slider-progress"></div>

                <div class="slider-content">

                    <h5>Premium Computer Components</h5>

                    <h2>Upgrade Your Setup</h2>

                    <div class="default-btn slide-btn">
                        <span class="links">Shop Now</span>
                    </div>

                </div>

            </div>


            <div class="single-slide align-center-left animation-style-01"
                style="background-image: url('{{ asset('assets/frontend/assets/images/slider/3.jpg') }}');">

                <div class="slider-progress"></div>

                <div class="slider-content">

                    <h5>Genuine Products</h5>

                    <h2>Performance. Reliability. Quality.</h2>

                    <div class="default-btn slide-btn">
                        <span class="links">Explore Products</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ================================
     STATIC BANNERS
================================ -->
<div class="li-static-banner li-static-banner-4 text-center pt-20">

    <div class="container">

        <div class="row">

            <div class="col-lg-6">

                <div class="single-banner pb-sm-30 pb-xs-30">

                    <img src="{{ asset('assets/frontend/assets/images/banner/2_3.jpg') }}" alt="Computer Hardware">

                </div>

            </div>


            <div class="col-lg-6">

                <div class="single-banner">

                    <img src="{{ asset('assets/frontend/assets/images/banner/2_4.jpg') }}" alt="Gaming Products">

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ================================
     OUR BRANDS
================================ -->
<section class="team-area pt-60 pt-sm-44">

    <div class="container">

        <div class="row">

            <div class="col-12">

                <div class="li-section-title capitalize mb-25">

                    <h2>
                        <span>Our Brands</span>
                    </h2>

                </div>

            </div>

        </div>


        <div class="row">

            <div class="product-active owl-carousel">


                <!-- Intel -->
                <div class="col-lg-12">

                    <div class="team-member mb-60">

                        <div class="team-thumb">

                            <img src="{{ asset('assets/frontend/assets/images/brand/intel.png') }}" alt="Intel"
                                style="width:100%;height:220px;object-fit:contain;">

                        </div>

                        <div class="team-content text-center">

                            <h3>Intel</h3>

                            <p>Computer Processors</p>

                        </div>

                    </div>

                </div>


                <!-- AMD -->
                <div class="col-lg-12">

                    <div class="team-member mb-60">

                        <div class="team-thumb">

                            <img src="{{ asset('assets/frontend/assets/images/brand/amd.png') }}" alt="AMD"
                                style="width:100%;height:220px;object-fit:contain;">

                        </div>

                        <div class="team-content text-center">

                            <h3>AMD</h3>

                            <p>Processors & Graphics</p>

                        </div>

                    </div>

                </div>


                <!-- NVIDIA -->
                <div class="col-lg-12">

                    <div class="team-member mb-60">

                        <div class="team-thumb">

                            <img src="{{ asset('assets/frontend/assets/images/brand/nvidia.png') }}" alt="NVIDIA"
                                style="width:100%;height:220px;object-fit:contain;">

                        </div>

                        <div class="team-content text-center">

                            <h3>NVIDIA</h3>

                            <p>Graphics Cards</p>

                        </div>

                    </div>

                </div>


                <!-- ASUS -->
                <div class="col-lg-12">

                    <div class="team-member mb-60">

                        <div class="team-thumb">

                            <img src="{{ asset('assets/frontend/assets/images/brand/asus.png') }}" alt="ASUS"
                                style="width:100%;height:220px;object-fit:contain;">

                        </div>

                        <div class="team-content text-center">

                            <h3>ASUS</h3>

                            <p>Motherboards & Components</p>

                        </div>

                    </div>

                </div>


                <!-- MSI -->
                <div class="col-lg-12">

                    <div class="team-member mb-60">

                        <div class="team-thumb">

                            <img src="{{ asset('assets/frontend/assets/images/brand/msi.png') }}" alt="MSI"
                                style="width:100%;height:220px;object-fit:contain;">

                        </div>

                        <div class="team-content text-center">

                            <h3>MSI</h3>

                            <p>Gaming Components</p>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</section>


<!-- ================================
     PC BUILDER
================================ -->
<section class="pc-builder-banner">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6 col-md-6">

                <div class="pc-builder-content">

                    <h1>
                        Build Your <span>Dream PC</span>
                    </h1>

                    <h4>
                        Performance. Reliability. Built for You.
                    </h4>

                    <div class="pc-builder-line"></div>

                    <p class="pc-builder-description">

                        Build your perfect PC with genuine components,
                        expert guidance and unbeatable prices.

                    </p>


                    <div class="pc-builder-button">

                        <span>
                            Click Here to Build Your PC
                        </span>

                    </div>


                    <div class="pc-builder-features">


                        <div class="pc-feature">

                            <div class="pc-feature-icon">
                                <i class="fa fa-shield"></i>
                            </div>

                            <div class="pc-feature-content">

                                <span>Free</span>
                                <strong>Delivery</strong>

                            </div>

                        </div>


                        <div class="pc-feature-divider"></div>


                        <div class="pc-feature">

                            <div class="pc-feature-icon">
                                <i class="fa fa-lightbulb-o"></i>
                            </div>

                            <div class="pc-feature-content">

                                <span>Secure</span>
                                <strong>Payments</strong>

                            </div>

                        </div>


                        <div class="pc-feature-divider"></div>


                        <div class="pc-feature">

                            <div class="pc-feature-icon">
                                <i class="fa fa-headphones"></i>
                            </div>

                            <div class="pc-feature-content">

                                <span>Expert</span>
                                <strong>Support</strong>

                            </div>

                        </div>


                        <div class="pc-feature-divider"></div>


                        <div class="pc-feature">

                            <div class="pc-feature-icon">
                                <i class="fa fa-thumbs-up"></i>
                            </div>

                            <div class="pc-feature-content">

                                <span>Trusted by</span>
                                <strong>Thousands</strong>

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            <div class="col-lg-6 col-md-6">

                <div class="pc-builder-image">

                    <img src="{{ asset('assets/frontend/assets/images/pc-builder/pc-builder.png') }}"
                        alt="Build Your Dream PC">

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ================================
     OUR PRODUCTS
================================ -->
<section class="product-area li-laptop-product pt-60 pb-45">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="li-section-title">

                    <h2>
                        <span>Our Products</span>
                    </h2>

                </div>


                <div class="row">

                    <div class="product-active owl-carousel">


                        <!-- PRODUCT 1 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/1.jpg') }}"
                                        alt="Intel Core i7 Processor">

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <div class="product-review">

                                            <h5 class="manufacturer">
                                                Intel
                                            </h5>

                                            <div class="rating-box">

                                                <ul class="rating">

                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>

                                                </ul>

                                            </div>

                                        </div>


                                        <h4 class="product_name">
                                            Intel Core i7 Processor
                                        </h4>


                                        <div class="price-box">

                                            <span class="new-price">
                                                ₹32,999.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- PRODUCT 2 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/2.jpg') }}"
                                        alt="NVIDIA Graphics Card">

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <div class="product-review">

                                            <h5 class="manufacturer">
                                                NVIDIA
                                            </h5>

                                            <div class="rating-box">

                                                <ul class="rating">

                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>

                                                </ul>

                                            </div>

                                        </div>


                                        <h4 class="product_name">
                                            NVIDIA Gaming Graphics Card
                                        </h4>


                                        <div class="price-box">

                                            <span class="new-price">
                                                ₹54,999.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- PRODUCT 3 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/3.jpg') }}"
                                        alt="ASUS Gaming Motherboard">

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <div class="product-review">

                                            <h5 class="manufacturer">
                                                ASUS
                                            </h5>

                                            <div class="rating-box">

                                                <ul class="rating">

                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>

                                                </ul>

                                            </div>

                                        </div>


                                        <h4 class="product_name">
                                            ASUS Gaming Motherboard
                                        </h4>


                                        <div class="price-box">

                                            <span class="new-price">
                                                ₹18,499.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- PRODUCT 4 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/4.jpg') }}"
                                        alt="Corsair Gaming RAM">

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <div class="product-review">

                                            <h5 class="manufacturer">
                                                Corsair
                                            </h5>

                                            <div class="rating-box">

                                                <ul class="rating">

                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>

                                                </ul>

                                            </div>

                                        </div>


                                        <h4 class="product_name">
                                            Corsair 16GB Gaming RAM
                                        </h4>


                                        <div class="price-box">

                                            <span class="new-price">
                                                ₹6,999.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

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

</section>


<!-- ================================
     WHY BUY FROM US
================================ -->
<section class="why-buy-section">

    <div class="container">

        <div class="why-buy-heading text-center">

            <h2>
                Why Buy From Us?
            </h2>

            <p>
                Your trusted destination for genuine PC products,
                custom builds and computer accessories.
            </p>

        </div>


        <div class="row why-buy-row">


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-shield"></i>
                    </div>

                    <h3>100% Genuine Products</h3>

                    <p>
                        Only authentic products guaranteed.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-truck"></i>
                    </div>

                    <h3>Free Shipping</h3>

                    <p>
                        Free shipping on selected products across India.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-bolt"></i>
                    </div>

                    <h3>Fast Delivery</h3>

                    <p>
                        Fast and reliable delivery to your doorstep.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-file-text"></i>
                    </div>

                    <h3>GST Invoice</h3>

                    <p>
                        GST invoice available with eligible orders.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-credit-card"></i>
                    </div>

                    <h3>Secure Payments</h3>

                    <p>
                        Safe and secure payment options.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-video-camera"></i>
                    </div>

                    <h3>Live Product Demo</h3>

                    <p>
                        Experience the product before you buy.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-dropbox"></i>
                    </div>

                    <h3>Secure Packaging</h3>

                    <p>
                        Products packed carefully before dispatch.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">

                <div class="why-buy-card">

                    <div class="why-buy-icon">
                        <i class="fa fa-undo"></i>
                    </div>

                    <h3>Easy Returns</h3>

                    <p>
                        Hassle-free returns on eligible products.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- ================================
     DISCOUNTED PRODUCTS
================================ -->
<section class="product-area li-laptop-product pt-60 pb-45">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="li-section-title">

                    <h2>
                        <span>Discounted Products</span>
                    </h2>

                </div>


                <div class="row">

                    <div class="product-active owl-carousel">


                        <!-- Product 1 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/5.jpg') }}"
                                        alt="Gaming Keyboard">

                                    <span class="sticker">
                                        -15%
                                    </span>

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <h5 class="manufacturer">
                                            Logitech
                                        </h5>

                                        <h4 class="product_name">
                                            Logitech Gaming Keyboard
                                        </h4>

                                        <div class="price-box">

                                            <span class="new-price new-price-2">
                                                ₹2,999.00
                                            </span>

                                            <span class="old-price">
                                                ₹3,499.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Product 2 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/6.jpg') }}"
                                        alt="Gaming Mouse">

                                    <span class="sticker">
                                        -20%
                                    </span>

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <h5 class="manufacturer">
                                            Logitech
                                        </h5>

                                        <h4 class="product_name">
                                            Logitech Gaming Mouse
                                        </h4>

                                        <div class="price-box">

                                            <span class="new-price new-price-2">
                                                ₹1,599.00
                                            </span>

                                            <span class="old-price">
                                                ₹1,999.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Product 3 -->
                        <div class="col-lg-12">

                            <div class="single-product-wrap">

                                <div class="product-image">

                                    <img src="{{ asset('assets/frontend/assets/images/product/large-size/7.jpg') }}"
                                        alt="Gaming Headset">

                                    <span class="sticker">
                                        -10%
                                    </span>

                                </div>


                                <div class="product_desc">

                                    <div class="product_desc_info">

                                        <h5 class="manufacturer">
                                            HyperX
                                        </h5>

                                        <h4 class="product_name">
                                            HyperX Gaming Headset
                                        </h4>

                                        <div class="price-box">

                                            <span class="new-price new-price-2">
                                                ₹4,499.00
                                            </span>

                                            <span class="old-price">
                                                ₹4,999.00
                                            </span>

                                        </div>

                                    </div>


                                    <div class="add-actions">

                                        <ul class="add-actions-link">

                                            <li class="add-cart active">
                                                <span>Add to Cart</span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-heart-o"></i>
                                                </span>
                                            </li>

                                            <li>
                                                <span>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </li>

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

</section>


<!-- ================================
     WHY CHOOSE US
================================ -->
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


<!-- ================================
     COUNTERS
================================ -->
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