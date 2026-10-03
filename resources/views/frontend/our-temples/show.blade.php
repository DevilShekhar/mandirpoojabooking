@extends('frontend.layouts.app')
@section('title', $temple->name)
@section('meta_keyword', $temple->name . ', Temple, Hindu Temple, India Temple')
@section('meta_description', 'Explore ' . $temple->name . ' temple details, location and information.')
@section('content')

<style>
.temple-detail-page {
    --temple-red: #a80f17;
    --temple-dark-red: #7e0b11;
    --temple-gold: #c47c20;
    --temple-light-gold: #f4d49d;
    --temple-cream: #faf6ed;
    --temple-border: #eee2d2;
    --temple-text: #30251f;
    --temple-muted: #8c8177;
    background: #fbf8f2;
    color: var(--temple-text);
    overflow: hidden;
}

.temple-hero {
    position: relative;
    min-height: 510px;
    display: flex;
    align-items: center;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}

.temple-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg,
            rgba(20, 9, 5, .88) 0%,
            rgba(20, 9, 5, .62) 38%,
            rgba(20, 9, 5, .18) 72%,
            rgba(20, 9, 5, .05) 100%),
        linear-gradient(0deg,
            rgba(20, 9, 5, .45),
            transparent 60%);
}

.temple-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    opacity: .16;
    background-image:
        radial-gradient(circle at 7% 28%,
            transparent 0,
            transparent 58px,
            rgba(236, 184, 88, .75) 59px,
            rgba(236, 184, 88, .75) 60px,
            transparent 61px),
        radial-gradient(circle at 94% 72%,
            transparent 0,
            transparent 95px,
            rgba(236, 184, 88, .5) 96px,
            rgba(236, 184, 88, .5) 97px,
            transparent 98px);
    pointer-events: none;
}

.temple-hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 80px 0 90px;
}

.temple-om-symbol {
    color: #f2bd5d;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 34px;
    line-height: 1;
    margin-bottom: 12px;
}

.temple-hero-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #f0c16b;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.temple-hero-eyebrow::before {
    content: "";
    width: 36px;
    height: 1px;
    background: #e4ad4d;
}

.temple-hero-title {
    max-width: 800px;
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(45px, 6vw, 76px);
    line-height: .98;
    letter-spacing: -2px;
    font-weight: 700;
    margin: 0 0 18px;
}

.temple-hero-location {
    display: flex;
    align-items: center;
    gap: 9px;
    color: rgba(255, 255, 255, .9);
    font-size: 15px;
}

.temple-hero-location i {
    color: #f1b94f;
    font-size: 18px;
}

.temple-main-section {
    position: relative;
    z-index: 5;
    margin-top: -35px;
    padding-bottom: 55px;
}

.temple-main-image-card {
    background: #fff;
    padding: 9px;
    border-radius: 17px;
    box-shadow: 0 20px 55px rgba(50, 29, 16, .12);
}

.temple-main-image {
    display: block;
    width: 100%;
    height: 515px;
    object-fit: cover;
    border-radius: 11px;
}

.temple-details-card {
    position: relative;
    height: 100%;
    background: #fff;
    border-radius: 17px;
    padding: 30px;
    box-shadow: 0 20px 55px rgba(50, 29, 16, .1);
    overflow: hidden;
}

.temple-details-card::after {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    right: -95px;
    top: 55px;
    border: 1px solid rgba(196, 124, 32, .16);
    border-radius: 50%;
}

.temple-details-card::before {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    right: -65px;
    top: 85px;
    border: 1px solid rgba(196, 124, 32, .1);
    border-radius: 50%;
}

.temple-details-header {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 17px;
    border-bottom: 1px solid var(--temple-border);
}

.temple-details-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.temple-heading-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 11px;
    background: #fff6e6;
    color: var(--temple-gold);
    font-size: 21px;
}

.temple-details-heading h2 {
    color: #25201c;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    line-height: 1;
    margin: 0;
    font-weight: 700;
}

.temple-details-heading h2 span {
    color: var(--temple-red);
}

.temple-verified {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    background: #e9f7ed;
    color: #16803a;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.temple-detail-list {
    position: relative;
    z-index: 2;
}

.temple-detail-item {
    display: flex;
    gap: 14px;
    padding: 15px 0;
    border-bottom: 1px solid #eee7de;
}

.temple-detail-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff7e9;
    color: var(--temple-gold);
    font-size: 15px;
}

.temple-detail-content {
    width: 100%;
    min-width: 0;
}

.temple-detail-label {
    display: block;
    color: #999089;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.temple-detail-value {
    display: block;
    color: #2e2722;
    font-size: 14px;
    line-height: 1.55;
    font-weight: 500;
}

.temple-coordinate-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.temple-coordinate {
    padding: 9px 11px;
    border: 1px solid #eee5d9;
    background: #faf8f4;
    border-radius: 8px;
}

.temple-coordinate small {
    display: block;
    color: #a29a92;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 3px;
}

.temple-coordinate strong {
    display: block;
    color: #332923;
    font-size: 12px;
}

.temple-status-active {
    color: #198754;
}

.temple-status-inactive {
    color: #dc3545;
}

.temple-status-icon {
    font-size: 7px;
    vertical-align: middle;
}

.temple-map-button {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    margin-top: 17px;
    padding: 13px 16px;
    background: var(--temple-red);
    border-radius: 7px;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: all .3s ease;
}

.temple-map-button:hover {
    color: #fff;
    background: var(--temple-dark-red);
    transform: translateY(-2px);
}

.temple-heritage-section {
    position: relative;
    overflow: hidden;
    padding: 35px 0;
    background:
        linear-gradient(90deg,
            #8d0c13 0%,
            #ae151c 60%,
            #8b0d13 100%);
}

.temple-heritage-section::before {
    content: "";
    position: absolute;
    inset: 0;
    opacity: .13;
    background-image:
        radial-gradient(circle at 8% 50%,
            transparent 0,
            transparent 65px,
            #f4cf86 66px,
            #f4cf86 67px,
            transparent 68px),
        radial-gradient(circle at 18% 50%,
            transparent 0,
            transparent 45px,
            #f4cf86 46px,
            #f4cf86 47px,
            transparent 48px),
        radial-gradient(circle at 92% 50%,
            transparent 0,
            transparent 80px,
            #f4cf86 81px,
            #f4cf86 82px,
            transparent 83px);
}

.temple-heritage-content {
    position: relative;
    z-index: 2;
}

.temple-heritage-eyebrow {
    color: #f1c45e;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.temple-heritage-title {
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    margin: 0 0 6px;
}

.temple-heritage-text {
    max-width: 620px;
    color: rgba(255, 255, 255, .83);
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

.temple-heritage-features {
    display: flex;
    justify-content: flex-end;
    height: 100%;
}

.temple-heritage-feature {
    min-width: 115px;
    padding: 3px 20px;
    text-align: center;
    border-left: 1px solid rgba(255, 255, 255, .2);
}

.temple-heritage-feature-icon {
    display: block;
    color: #f2c45f;
    font-size: 23px;
    margin-bottom: 7px;
}

.temple-heritage-feature-text {
    display: block;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.temple-location-section {
    padding: 55px 0 65px;
    background: #fffdf9;
}

.temple-location-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 18px;
}

.temple-location-heading-icon {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 9px;
    background: #fff6e6;
    color: var(--temple-gold);
    font-size: 20px;
}

.temple-location-heading h2 {
    color: #28211c;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    line-height: 1;
    margin: 0;
}

.temple-location-heading p {
    color: #91877f;
    font-size: 12px;
    margin: 5px 0 0;
}

.temple-map-wrapper {
    height: 340px;
    overflow: hidden;
    border: 1px solid #e8ded1;
    border-radius: 14px;
    box-shadow: 0 15px 45px rgba(47, 31, 20, .08);
}

.temple-map-wrapper iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

.temple-no-map {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #faf7f2;
    text-align: center;
}

.temple-no-map-icon {
    color: var(--temple-gold);
    font-size: 35px;
    margin-bottom: 12px;
}

.temple-no-map h4 {
    color: #332923;
    margin-bottom: 5px;
}

.temple-no-map p {
    color: #91877f;
    font-size: 13px;
    margin: 0;
}

.temple-footer {
    padding: 28px 0;
    background: #fff;
    border-top: 1px solid #eee4d8;
}

.temple-footer-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.temple-back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    border: 1px solid #d9cec2;
    border-radius: 7px;
    background: #fff;
    color: #302823;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all .3s ease;
}

.temple-back-button:hover {
    color: var(--temple-red);
    border-color: var(--temple-red);
}

.temple-created {
    display: flex;
    align-items: center;
    gap: 11px;
}

.temple-created-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #fff6e5;
    color: var(--temple-gold);
}

.temple-created-label {
    display: block;
    color: #968d85;
    font-size: 10px;
    margin-bottom: 2px;
}

.temple-created-date {
    display: block;
    color: #332923;
    font-size: 12px;
    font-weight: 700;
}

@media (max-width: 991px) {
    .temple-hero {
        min-height: 470px;
    }

    .temple-hero-content {
        padding: 70px 0 70px;
    }

    .temple-main-section {
        margin-top: -20px;
    }

    .temple-details-card {
        margin-top: 25px;
    }

    .temple-heritage-features {
        justify-content: flex-start;
        margin-top: 25px;
    }

    .temple-heritage-feature:first-child {
        padding-left: 0;
        border-left: 0;
    }
}

@media (max-width: 767px) {
    .temple-hero {
        min-height: 410px;
    }

    .temple-hero-content {
        padding: 60px 0 55px;
    }

    .temple-hero-title {
        font-size: 43px;
        letter-spacing: -1px;
    }

    .temple-hero-location {
        font-size: 13px;
    }

    .temple-main-image {
        height: 360px;
    }

    .temple-details-card {
        padding: 24px 20px;
    }

    .temple-details-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .temple-details-heading h2 {
        font-size: 27px;
    }

    .temple-coordinate-grid {
        grid-template-columns: 1fr;
    }

    .temple-heritage-section {
        padding: 35px 0;
    }

    .temple-heritage-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;
    }

    .temple-heritage-feature {
        min-width: 0;
        padding: 5px 8px;
    }

    .temple-location-section {
        padding: 45px 0 50px;
    }

    .temple-map-wrapper {
        height: 290px;
    }

    .temple-footer-content {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .temple-hero-title {
        font-size: 37px;
    }

    .temple-main-image {
        height: 300px;
    }

    .temple-heritage-feature-icon {
        font-size: 19px;
    }

    .temple-heritage-feature-text {
        font-size: 9px;
    }

    .temple-location-heading h2 {
        font-size: 25px;
    }
}
</style>

<div class="temple-detail-page">
    <section class="temple-hero"  style="background-image:url('{{ $temple->banner_image ? asset('storage/' . $temple->banner_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}');">
        <div class="container">
            <div class="temple-hero-content">
                <div class="temple-hero-eyebrow">
                    A Divine Destination
                </div>
                <h1 class="temple-hero-title">
                    {{ $temple->name }}
                </h1>
                <div class="temple-hero-location">
                    <i class="fa fa-map-marker"></i>
                    <span>
                        {{ $temple->city->name ?? '' }}
                        @if($temple->district)
                        , {{ $temple->district->name }}
                        @endif
                        @if($temple->state)
                        , {{ $temple->state->name }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="temple-main-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12">
                    <div class="temple-main-image-card">
                        <img src="{{ $temple->main_image ? asset('storage/' . $temple->main_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                            alt="{{ $temple->name }}" class="temple-main-image">
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="temple-details-card">
                        <div class="temple-details-header">
                            <div class="temple-details-heading">
                                <div class="temple-heading-icon">
                                    <i class="fa fa-university"></i>
                                </div>
                                <h2>
                                    Temple <span>Details</span>
                                </h2>
                            </div>
                            @if($temple->is_verified)
                            <span class="temple-verified">
                                <i class="fa fa-check-circle"></i>
                                Verified Temple
                            </span>
                            @endif
                        </div>
                        <div class="temple-detail-list">
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-qrcode"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Temple Code
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->temple_code ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Location
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->city->name ?? 'N/A' }}
                                        @if($temple->district)
                                        , {{ $temple->district->name }}
                                        @endif
                                        @if($temple->state)
                                        , {{ $temple->state->name }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Address
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->address ?? 'Address not available' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($temple->latitude && $temple->longitude)
                        <a href="https://www.google.com/maps?q={{ $temple->latitude }},{{ $temple->longitude }}"
                            target="_blank" class="temple-map-button">
                            <i class="fa fa-map"></i>
                            <span>
                                View Location on Google Maps
                            </span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="temple-heritage-section">
        <div class="container">
            <div class="temple-heritage-content">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="temple-heritage-eyebrow">
                            A Sacred Landmark
                        </div>
                        <h2 class="temple-heritage-title">
                            {{ $temple->name }}
                        </h2>
                        <p class="temple-heritage-text">
                            Explore the temple location and essential temple information
                            through our devotional platform.
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="temple-heritage-features">
                            <div class="temple-heritage-feature">
                                <i class="fa fa-university temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Spiritual
                                    <br>
                                    Heritage
                                </span>
                            </div>
                            <div class="temple-heritage-feature">
                                <i class="fa fa-star temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Divine
                                    <br>
                                    Destination
                                </span>
                            </div>
                            <div class="temple-heritage-feature">
                                <i class="fa fa-map-marker temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Pilgrimage
                                    <br>
                                    Destination
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="temple-location-section">
        <div class="container">
            <div class="temple-location-heading">
                <div class="temple-location-heading-icon">
                   <i class="fa fa-university"></i>
                </div>
                <div>
                    <h2>
                        Temple Location
                    </h2>
                    <p>
                        Find the exact location of {{ $temple->name }} on the map.
                    </p>
                </div>
            </div>
            @if($temple->latitude && $temple->longitude)
            <div class="temple-map-wrapper">
                <iframe
                    src="https://www.google.com/maps?q={{ $temple->latitude }},{{ $temple->longitude }}&output=embed"
                    loading="lazy" allowfullscreen>
                </iframe>
            </div>
            @else
            <div class="temple-map-wrapper">
                <div class="temple-no-map">
                    <div>
                        <div class="temple-no-map-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h4>
                            Location Not Available
                        </h4>
                        <p>
                            Temple coordinates have not been added yet.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <footer class="temple-footer">
        <div class="container">
            <div class="temple-footer-content">
                <a href="{{ route('frontend.our-temples') }}" class="temple-back-button">
                    <i class="fa fa-arrow-left"></i>
                    Back to Our Temples
                </a>
                <div class="temple-created">
                    <div class="temple-created-icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <span class="temple-created-label">
                            Temple registered on
                        </span>
                        <span class="temple-created-date">
                            {{ $temple->created_at ? $temple->created_at->format('d M Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection