@extends('frontend.layouts.app')
@section('title', $onlinePuja->title . ' | Online Puja')
@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $getImageUrl = function ($image) {
        if (empty($image)) {
            return null;
        }
        if (is_array($image)) {
            $image = $image['image'] ?? $image['path'] ?? $image['url'] ?? null;
        }
        if (!$image) {
            return null;
        }
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return asset('storage/' . ltrim($image, '/'));
    };
    $galleryImages = $onlinePuja->gallery_images ?? [];
    if (is_string($galleryImages)) {
        $galleryImages = json_decode($galleryImages, true) ?? [];
    }
    if (!is_array($galleryImages)) {
        $galleryImages = [];
    }
    $galleryImages = collect($galleryImages)
        ->map(fn($image) => $getImageUrl($image))
        ->filter()
        ->values();
    if ($galleryImages->isEmpty()) {
        $fallbackImage = $getImageUrl(
            $onlinePuja->banner_image ?: $onlinePuja->thumbnail_image
        );
        if ($fallbackImage) {
            $galleryImages->push($fallbackImage);
        }
    }
    $packages = $onlinePuja->packages
        ->where('status', 1)
        ->sortBy('sort_order')
        ->values();
    $totalSeats = (int) ($onlinePuja->total_seat ?? 0);
    $bookedSeats = (int) ($onlinePuja->booked_seat ?? 0);
    $hasSeatLimit = $totalSeats > 0;
    $availableSeats = $hasSeatLimit
        ? max(0, $totalSeats - $bookedSeats)
        : null;
    $firstPackage = $packages->first(function ($package) use ($hasSeatLimit, $availableSeats) {
        return !$hasSeatLimit || (int) $package->devotees_count <= $availableSeats;
    });
    $faqs = $onlinePuja->faqs
        ->where('status', 1)
        ->values();
    $pujaDate = $onlinePuja->puja_datetime
        ? \Carbon\Carbon::parse($onlinePuja->puja_datetime)
        : null;
@endphp
<section class="puja-breadcrumb">
    <div class="container">
        <div class="puja-breadcrumb-inner">
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-chevron-right"></i>
            <a href="{{ route('frontend.puja') }}">Online Puja</a>
            <i class="fa fa-chevron-right"></i>
            <span>{{ $onlinePuja->title }}</span>
        </div>
    </div>
</section>
<section class="puja-detail-hero">
    <div class="container">
        <div class="puja-hero-grid">
            <div class="puja-gallery-column">
                <div class="puja-gallery-card">
                    <div class="puja-gallery-main">
                        @if($galleryImages->isNotEmpty())
                            @foreach($galleryImages as $index => $image)
                                <div class="puja-gallery-slide {{ $index === 0 ? 'active' : '' }}"
                                     data-slide="{{ $index }}">
                                    <img src="{{ $image }}"
                                         alt="{{ $onlinePuja->title }} - Gallery {{ $index + 1 }}"
                                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                </div>
                            @endforeach
                        @else
                            <div class="puja-gallery-empty">
                                <i class="fa fa-place-of-worship"></i>
                                <span>Puja Image</span>
                            </div>
                        @endif
                        @if($onlinePuja->is_featured)
                            <div class="puja-featured-label">
                                <i class="fa fa-star"></i>
                                Featured Puja
                            </div>
                        @endif
                        @if($galleryImages->count() > 1)
                            <button type="button"
                                    class="gallery-arrow gallery-prev"
                                    aria-label="Previous image">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button type="button"
                                    class="gallery-arrow gallery-next"
                                    aria-label="Next image">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        @endif
                        <div class="gallery-counter">
                            <i class="far fa-images"></i>
                            <span id="galleryCurrent">1</span>
                            /
                            <span>{{ max(1, $galleryImages->count()) }}</span>
                        </div>
                    </div>
                    @if($galleryImages->count() > 1)
                        <div class="puja-gallery-thumbnails">
                            @foreach($galleryImages as $index => $image)
                                <button type="button"
                                        class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                        data-thumb="{{ $index }}"
                                        aria-label="View image {{ $index + 1 }}">
                                    <img src="{{ $image }}"
                                         alt="Gallery thumbnail {{ $index + 1 }}"
                                         loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="gallery-caption">
                    <span class="gallery-caption-line"></span>
                    <span>Divine moments, sacred rituals</span>
                </div>
            </div>
            <div class="puja-info-column">
                <div class="puja-info-top">
                    <div class="puja-category-label">
                        <span class="category-dot"></span>
                        {{ $onlinePuja->category->name ?? 'Sacred Ritual' }}
                    </div>
                    <h1>{{ $onlinePuja->title }}</h1>
                    <div class="puja-title-decoration">
                        <span></span>
                        <i class="fa fa-om"></i>
                        <span></span>
                    </div>
                    @if($onlinePuja->short_description)
                        <p class="puja-short-description">
                            {{ $onlinePuja->short_description }}
                        </p>
                    @endif
                </div>
                <div class="puja-quick-details">
                    @if($onlinePuja->temple_name)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                                <i class="fa fa-building-o" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Temple</span>
                                <strong>{{ $onlinePuja->temple_name }}</strong>
                            </div>
                        </div>
                    @endif
                    @if($pujaDate)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Puja Date</span>
                                <strong>{{ $pujaDate->format('d M Y') }}</strong>
                            </div>
                        </div>
                    @endif
                    @if($onlinePuja->day)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                                <i class="far fa-sun" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Day</span>
                                <strong>{{ $onlinePuja->day }}</strong>
                            </div>
                        </div>
                    @endif
                    @if($onlinePuja->tithi)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                                <i class="fa fa-moon-o" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Tithi</span>
                                <strong>{{ $onlinePuja->tithi }}</strong>
                            </div>
                        </div>
                    @endif
                    @if($onlinePuja->muhurat)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                               <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Muhurat</span>
                                <strong>{{ $onlinePuja->muhurat }}</strong>
                            </div>
                        </div>
                    @endif
                    @if($onlinePuja->temple_city || $onlinePuja->temple_state)
                        <div class="quick-detail-item">
                            <div class="quick-detail-icon">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                            </div>
                            <div>
                                <span class="quick-detail-label">Location</span>
                                <strong>
                                    {{ collect([
                                        $onlinePuja->temple_city,
                                        $onlinePuja->temple_state
                                    ])->filter()->implode(', ') }}
                                </strong>
                            </div>
                        </div>
                    @endif
                </div>
                @if($hasSeatLimit)
                    @php
                        $seatPercentage = min(
                            100,
                            ($bookedSeats / max(1, $totalSeats)) * 100
                        );
                    @endphp
                    <div class="puja-seat-status">
                        <div class="seat-status-top">
                            <span>
                                <i class="fa fa-users"></i>
                                Participation Availability
                            </span>
                            <strong>
                                {{ number_format($availableSeats) }} / {{ number_format($totalSeats) }} Seats Available
                            </strong>
                        </div>
                        <div class="seat-progress">
                            <span style="width: {{ $seatPercentage }}%"></span>
                        </div>
                        <div class="seat-status-bottom">
                            <span>{{ number_format($bookedSeats) }} booked</span>
                            <span>{{ number_format($totalSeats) }} total seats</span>
                        </div>
                    </div>
                @else
                    <div class="puja-seat-status">
                        <div class="seat-status-top">
                            <span>
                                <i class="fa fa-users"></i>
                                Participation Availability
                            </span>
                            <strong>Unlimited Seats</strong>
                        </div>
                    </div>
                @endif
                <div class="puja-booking-card">               
                    <button type="button"
                            class="participate-main-btn"
                            id="openPackageModal"
                            {{ $packages->isEmpty() || $availableSeats <= 0 && $onlinePuja->total_seat ? 'disabled' : '' }}>
                        <span>Participate in This Puja</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <div class="booking-trust-note">
                        <i class="fa fa-check-circle"></i>
                        Choose your preferred participation package
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="puja-trust-strip">
    <div class="container">
        <div class="trust-strip-grid">
            <div class="trust-item">
                <div class="trust-icon">
                    <i class="fa fa-asterisk" aria-hidden="true"></i>
                </div>
                <div>
                    <strong>Authentic Rituals</strong>
                    <span>Traditional Vedic Practices</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                </div>
                <div>
                    <strong>Experienced Priests</strong>
                    <span>Performed with Devotion</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                </div>
                <div>
                    <strong>Online Participation</strong>
                    <span>From Your Home</span>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <i class="fa fa-heart"></i>
                </div>
                <div>
                    <strong>Divine Blessings</strong>
                    <span>Peace, Positivity and Prosperity</span>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="puja-section-navigation" id="pujaSectionNavigation">
    <div class="container">
        <div class="puja-section-nav-inner">
            @if($onlinePuja->puja_significance)
                <a href="#significance" class="puja-nav-link active">
                    Significance
                </a>
            @endif
            @if($onlinePuja->puja_benefits)
                <a href="#benefits" class="puja-nav-link">
                    Benefits
                </a>
            @endif
            @if($onlinePuja->puja_procedure)
                <a href="#procedure" class="puja-nav-link">
                    Procedure
                </a>
            @endif
            @if($onlinePuja->what_you_will_receive)
                <a href="#receive" class="puja-nav-link">
                    What You Receive
                </a>
            @endif
            @if($onlinePuja->temple_name)
                <a href="#temple" class="puja-nav-link">
                    Temple
                </a>
            @endif
            @if($faqs->isNotEmpty())
                <a href="#faqs" class="puja-nav-link">
                    FAQs
                </a>
            @endif
        </div>
    </div>
</div>
<section class="puja-content-section">
    <div class="container">
        <div class="puja-content-layout">
            <div class="puja-content-main">
                @if($onlinePuja->puja_significance)
                    <section class="puja-content-block" id="significance">
                        <div class="section-heading">
                            <span class="section-eyebrow">UNDERSTANDING THE RITUAL</span>
                            <h2>Puja Significance</h2>
                            <p>Discover the spiritual meaning behind this sacred ceremony.</p>
                        </div>
                        <div class="puja-rich-content">
                            {!! $onlinePuja->puja_significance !!}
                        </div>
                    </section>
                @endif
                @if($onlinePuja->puja_benefits)
                    <section class="puja-content-block" id="benefits">
                        <div class="section-heading">
                            <span class="section-eyebrow">DIVINE POSSIBILITIES</span>
                            <h2>Benefits of This Puja</h2>
                            <p>Understand the traditional spiritual benefits of performing this puja.</p>
                        </div>
                        <div class="puja-rich-content">
                            {!! $onlinePuja->puja_benefits !!}
                        </div>
                    </section>
                @endif
                @if($onlinePuja->puja_procedure)
                    <section class="puja-content-block" id="procedure">
                        <div class="section-heading">
                            <span class="section-eyebrow">THE SACRED PROCESS</span>
                            <h2>Puja Procedure</h2>
                            <p>Learn how the ceremony is traditionally performed.</p>
                        </div>
                        <div class="puja-rich-content">
                            {!! $onlinePuja->puja_procedure !!}
                        </div>
                    </section>
                @endif
                @if($onlinePuja->what_you_will_receive)
                    <section class="puja-content-block" id="receive">
                        <div class="section-heading">
                            <span class="section-eyebrow">YOUR PARTICIPATION</span>
                            <h2>What You Will Receive</h2>
                            <p>Know what is included when you participate in this puja.</p>
                        </div>
                        <div class="puja-rich-content">
                            {!! $onlinePuja->what_you_will_receive !!}
                        </div>
                    </section>
                @endif
                @if($onlinePuja->temple_name)
                    <section class="puja-content-block" id="temple">
                        <div class="section-heading">
                            <span class="section-eyebrow">SACRED DESTINATION</span>
                            <h2>About the Temple</h2>
                        </div>
                        <div class="temple-information-card">
                            <div class="temple-information-icon">
                                <i class="fa fa-building-o" aria-hidden="true"></i>
                            </div>
                            <div class="temple-information-content">
                                <h3>{{ $onlinePuja->temple_name }}</h3>
                                @if($onlinePuja->temple_location || $onlinePuja->temple_city || $onlinePuja->temple_state)
                                    <p class="temple-location">
                                        <i class="fa fa-map-marker-alt"></i>
                                        {{ collect([
                                            $onlinePuja->temple_location,
                                            $onlinePuja->temple_city,
                                            $onlinePuja->temple_state
                                        ])->filter()->implode(', ') }}
                                    </p>
                                @endif
                                @if($onlinePuja->temple_description)
                                    <div class="puja-rich-content">
                                        {!! $onlinePuja->temple_description !!}
                                    </div>
                                @endif
                                @if($onlinePuja->temple_address)
                                    <p>{{ $onlinePuja->temple_address }}</p>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif
                @if($faqs->isNotEmpty())
                    <section class="puja-content-block" id="faqs">
                        <div class="section-heading">
                            <span class="section-eyebrow">NEED MORE INFORMATION?</span>
                            <h2>Frequently Asked Questions</h2>
                            <p>Find answers to common questions about this puja.</p>
                        </div>
                        <div class="puja-faq-list">
                            @foreach($faqs as $index => $faq)
                                <div class="puja-faq-item {{ $index === 0 ? 'open' : '' }}">
                                    <button type="button" class="puja-faq-question">
                                        <span>{{ $faq->question }}</span>
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <div class="puja-faq-answer">
                                        <div>
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
            <aside class="puja-content-sidebar">
                <div class="sidebar-booking-card">
                    <div class="sidebar-card-top">
                        <span class="sidebar-eyebrow">YOUR DIVINE JOURNEY</span>
                        <h3>Be a Part of This Sacred Puja</h3>
                        <p>Choose a participation package and receive divine blessings.</p>
                    </div>                 
                    <button type="button"
                            class="sidebar-participate-btn"
                            id="sidebarParticipateBtn"
                            {{ $packages->isEmpty() || $availableSeats <= 0 && $onlinePuja->total_seat ? 'disabled' : '' }}>
                        Choose Your Package
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <div class="sidebar-assurance">
                        <i class="fa fa-lock"></i>
                        Safe and Secure Participation
                    </div>
                </div>
                <div class="sidebar-highlights-card">
                    <h4>Participation Highlights</h4>
                    <div class="sidebar-highlight">
                        <i class="fa fa-check-circle"></i>
                        <span>Traditional Vedic rituals</span>
                    </div>
                    <div class="sidebar-highlight">
                        <i class="fa fa-check-circle"></i>
                        <span>Experienced priests</span>
                    </div>
                    <div class="sidebar-highlight">
                        <i class="fa fa-check-circle"></i>
                        <span>Online participation</span>
                    </div>
                    <div class="sidebar-highlight">
                        <i class="fa fa-check-circle"></i>
                        <span>Participation confirmation</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@if(isset($relatedPujas) && $relatedPujas->isNotEmpty())
<section class="related-pujas-section">
    <div class="container">
        <div class="related-section-heading">
            <div>
                <span class="section-eyebrow">EXPLORE MORE</span>
                <h2>Related Pujas</h2>
                <p>Discover more sacred ceremonies and spiritual experiences.</p>
            </div>
        </div>
        <div class="related-puja-grid">
            @foreach($relatedPujas as $relatedPuja)
                @php
                    $relatedImage = $getImageUrl(
                        $relatedPuja->thumbnail_image ?: $relatedPuja->banner_image
                    );
                    $relatedPackage = $relatedPuja->packages
                        ->where('status', 1)
                        ->sortBy('sort_order')
                        ->first();
                @endphp
                <a href="{{ route('frontend.online-puja.show', $relatedPuja->slug) }}"
                   class="related-puja-card">
                    <div class="related-puja-image">
                        @if($relatedImage)
                            <img src="{{ $relatedImage }}"
                                 alt="{{ $relatedPuja->title }}"
                                 loading="lazy">
                        @else
                            <div class="related-image-placeholder">
                                <i class="fa fa-place-of-worship"></i>
                            </div>
                        @endif
                        <span class="related-image-arrow">
                            <i class="fa fa-arrow-up"></i>
                        </span>
                    </div>
                    <div class="related-puja-body">
                        <span class="related-category">
                            {{ $relatedPuja->category->name ?? 'Sacred Puja' }}
                        </span>
                        <h3>{{ $relatedPuja->title }}</h3>
                        @if($relatedPuja->short_description)
                            <p>{{ Str::limit($relatedPuja->short_description, 90) }}</p>
                        @endif
                        <div class="related-puja-footer">
                            <span>
                                @if($relatedPackage)
                                    Starting ₹{{ number_format($relatedPackage->price, 0) }}
                                @else
                                    Explore Puja
                                @endif
                            </span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
<div class="package-modal-overlay" id="packageModal" aria-hidden="true">
    <div class="package-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="packageModalTitle">
        <div class="package-modal-header">
            <div>
                <span class="modal-eyebrow">PERSONALISE YOUR PARTICIPATION</span>
                <h2 id="packageModalTitle">Choose Your Package</h2>
                <p>Select the participation option that suits you best.</p>
            </div>
            <button type="button"
                    class="package-modal-close"
                    id="closePackageModal"
                    aria-label="Close package selection">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="package-modal-puja-info">
            <div class="modal-puja-icon">
                <i class="fa fa-om"></i>
            </div>
            <div>
                <span>Selected Puja</span>
                <strong>{{ $onlinePuja->title }}</strong>
            </div>
        </div>
        <div class="package-modal-body">
            <div class="package-selection-heading">
                <div>
                    <h3>Available Packages</h3>
                    <span>{{ $packages->count() }} participation options</span>
                </div>
                <span class="package-select-hint">
                    Select one package
                </span>
            </div>
            @if($packages->isNotEmpty())
                <div class="package-options-list" id="packageOptionsList">
                    @foreach($packages as $index => $package)
                        @php
                            $packageSeatUnavailable = $hasSeatLimit &&
                                (int) $package->devotees_count > $availableSeats;
                            $packageSelected = $firstPackage && $firstPackage->id === $package->id;
                        @endphp
                        <label class="package-option-card {{ $packageSelected ? 'recommended-package' : '' }} {{ $packageSeatUnavailable ? 'package-unavailable' : '' }}"
                               data-package-card="{{ $package->id }}">
                            <input type="radio"
                                   class="package-radio"
                                   name="selected_package"
                                   value="{{ $package->id }}"
                                   data-price="{{ $package->price }}"
                                   data-name="{{ $package->package_name }}"
                                   data-devotees="{{ $package->devotees_count }}"
                                   {{ $packageSelected ? 'checked' : '' }}
                                   {{ $packageSeatUnavailable ? 'disabled' : '' }}>
                            <span class="custom-radio-indicator">
                               <i class="fa fa-check"></i>
                            </span>
                            <span class="package-option-content">
                                <span class="package-option-top">
                                    <strong>{{ $package->package_name }}</strong>
                                    @if($package->is_recommended)
                                        <span class="recommended-badge">
                                            <i class="fa fa-star"></i>
                                            Recommended
                                        </span>
                                    @endif
                                </span>
                                @if($package->description)
                                    <span class="package-description">
                                        {{ $package->description }}
                                    </span>
                                @endif
                                <span class="package-meta">
                                    @if($packageSeatUnavailable)
                                        <span class="package-seat-unavailable">
                                            <i class="fa fa-exclamation-circle"></i>
                                            Not enough seats available
                                        </span>
                                    @elseif($package->devotees_count)
                                        <span>
                                            <i class="fa fa-users"></i>
                                            {{ $package->devotees_count }}
                                            {{ (int) $package->devotees_count === 1 ? 'Devotee' : 'Devotees' }}
                                        </span>
                                    @endif
                                    <span>
                                        <i class="fa fa-check-circle"></i>
                                        Puja Participation
                                    </span>
                                </span>
                            </span>
                            <span class="package-option-price">
                                <small>Amount</small>
                                <strong>₹{{ number_format($package->price, 0) }}</strong>
                            </span>
                        </label>
                    @endforeach
                </div>
                <div class="package-selection-error" id="packageSelectionError">
                    Please select a package to continue.
                </div>
                <div class="selected-package-summary" id="selectedPackageSummary">
                    <div class="selected-summary-info">
                        <span>Selected Package</span>
                        <strong id="selectedPackageName">
                            {{ $firstPackage->package_name ?? '' }}
                        </strong>
                    </div>
                    <div class="selected-summary-price">
                        <span>Total Amount</span>
                        <strong id="selectedPackagePrice">
                            ₹{{ number_format($firstPackage->price ?? 0, 0) }}
                        </strong>
                    </div>
                </div>
            @else
                <div class="no-packages-message">
                    <i class="fa fa-box-open"></i>
                    <h3>No Packages Available</h3>
                    <p>Participation packages are not available at the moment.</p>
                </div>
            @endif
        </div>
        <form id="onlinePujaPackageForm" method="POST" action="{{ route('frontend.online-puja.select-package') }}">
            @csrf
            <input type="hidden" name="puja_id" value="{{ $onlinePuja->id }}">
            <input type="hidden" name="package_id" id="selectedPackageId">
        </form>
        <div class="package-modal-footer">
            <button type="button"
                    class="modal-cancel-btn"
                    id="cancelPackageSelection">
                Cancel
            </button>
            <button type="button"
                    class="modal-continue-btn"
                    id="continuePackageSelection"
                    {{ $packages->isEmpty() ? 'disabled' : '' }}>
                <span>Continue</span>
                <i class="fa fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
:root {
    --puja-primary: #a84e20;
    --puja-primary-dark: #783514;
    --puja-gold: #d9a441;
    --puja-gold-light: #f5e7c5;
    --puja-cream: #fffaf2;
    --puja-background: #fbf8f3;
    --puja-text: #28251f;
    --puja-muted: #817b70;
    --puja-border: #eee6d9;
    --puja-green: #38835c;
    --puja-shadow: 0 18px 50px rgba(65, 42, 18, .07);

    /* Sticky navigation settings */
    --puja-header-height: 0px;
    --puja-sticky-gap: 12px;
    --puja-nav-height: 58px;
}

/* GENERAL */

.puja-detail-hero,
.puja-content-section,
.related-pujas-section {
    font-family: inherit;
}

.puja-breadcrumb {
    background: #fbf8f3;
    border-bottom: 1px solid #eee8dc;
    padding: 15px 0;
}

.puja-breadcrumb-inner {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 11px;
    font-size: 13px;
    color: var(--puja-muted);
}

.puja-breadcrumb-inner a {
    color: #8a6b45;
    text-decoration: none;
    transition: .2s;
}

.puja-breadcrumb-inner a:hover {
    color: var(--puja-primary);
}

.puja-breadcrumb-inner i {
    font-size: 9px;
    color: #c6b9a6;
}

.puja-breadcrumb-inner span {
    color: var(--puja-primary);
    font-weight: 600;
}

/* HERO */

.puja-detail-hero {
    position: relative;
    padding: 48px 0 55px;
    background:
        radial-gradient(circle at 10% 10%, rgba(230, 191, 119, .13), transparent 27%),
        linear-gradient(120deg, #fffaf2 0%, #fff 58%, #fcf5e9 100%);
}

.puja-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr);
    gap: 52px;
    align-items: center;
}

/* GALLERY */

.puja-gallery-card {
    background: #fff;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid rgba(220, 194, 151, .45);
    box-shadow: var(--puja-shadow);
}

.puja-gallery-main {
    height: 470px;
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    background: #f1e6d5;
}

.puja-gallery-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transform: scale(1.035);
    transition: opacity .55s ease, transform .8s ease, visibility .55s;
}

.puja-gallery-slide.active {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
    z-index: 1;
}

.puja-gallery-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.puja-gallery-empty {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 15px;
    color: #b58c54;
    font-size: 16px;
}

.puja-gallery-empty i {
    font-size: 55px;
}

.puja-featured-label {
    position: absolute;
    top: 18px;
    left: 18px;
    z-index: 3;
    background: rgba(255, 250, 239, .96);
    color: var(--puja-primary);
    padding: 9px 15px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(0,0,0,.08);
}

.puja-featured-label i {
    color: var(--puja-gold);
    margin-right: 5px;
}

.gallery-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 4;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.5);
    background: rgba(255,255,255,.91);
    color: var(--puja-primary);
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: .25s;
}

.gallery-arrow:hover {
    background: var(--puja-primary);
    color: #fff;
}

.gallery-prev {
    left: 16px;
}

.gallery-next {
    right: 16px;
}

.gallery-counter {
    position: absolute;
    z-index: 3;
    right: 17px;
    bottom: 17px;
    padding: 8px 13px;
    border-radius: 25px;
    background: rgba(30, 25, 19, .66);
    color: #fff;
    font-size: 12px;
    backdrop-filter: blur(6px);
}

.gallery-counter i {
    margin-right: 5px;
}

.puja-gallery-thumbnails {
    display: flex;
    gap: 10px;
    padding: 12px 2px 2px;
    overflow-x: auto;
    scrollbar-width: thin;
}

.gallery-thumb {
    flex: 0 0 76px;
    width: 76px;
    height: 63px;
    padding: 3px;
    border-radius: 9px;
    border: 2px solid transparent;
    background: #f4eee5;
    cursor: pointer;
    overflow: hidden;
    transition: .25s;
}

.gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 5px;
}

.gallery-thumb.active {
    border-color: var(--puja-primary);
    background: #fff;
}

.gallery-caption {
    margin-top: 19px;
    display: flex;
    align-items: center;
    gap: 11px;
    color: #a18d70;
    font-size: 12px;
    letter-spacing: 1.1px;
    text-transform: uppercase;
}

.gallery-caption-line {
    width: 33px;
    height: 1px;
    background: var(--puja-gold);
}

/* RIGHT SIDE INFORMATION */

.puja-info-column {
    padding: 5px 0;
}

.puja-category-label {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 8px 13px;
    background: #f6ead6;
    color: #92552e;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.category-dot {
    width: 7px;
    height: 7px;
    background: var(--puja-gold);
    border-radius: 50%;
}

.puja-info-top h1 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(30px, 3.1vw, 43px);
    line-height: 1.19;
    color: #32291f;
    font-weight: 700;
    margin: 19px 0 14px;
}

.puja-title-decoration {
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--puja-gold);
    margin-bottom: 17px;
}

.puja-title-decoration span {
    width: 33px;
    height: 1px;
    background: #d9b87e;
}

.puja-title-decoration i {
    font-size: 15px;
}

.puja-short-description {
    color: #71685e;
    font-size: 14px;
    line-height: 1.9;
    margin-bottom: 24px;
}

/* QUICK DETAILS */

.puja-quick-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 17px 13px;
    padding: 21px;
    border: 1px solid #eee5d7;
    border-radius: 14px;
    background: rgba(255,255,255,.76);
}

.quick-detail-item {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.quick-detail-icon {
    width: 39px;
    height: 39px;
    flex: 0 0 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #fbf1df;
    color: var(--puja-primary);
    font-size: 15px;
}

.quick-detail-item > div:last-child {
    min-width: 0;
}

.quick-detail-label {
    display: block;
    color: #999084;
    font-size: 10px;
    letter-spacing: .6px;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.quick-detail-item strong {
    display: block;
    color: #40382f;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.45;
    overflow-wrap: anywhere;
}

/* SEATS */

.puja-seat-status {
    margin-top: 18px;
}

.seat-status-top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 9px;
    color: #726a5f;
    font-size: 12px;
}

.seat-status-top i {
    color: var(--puja-primary);
    margin-right: 5px;
}

.seat-status-top strong {
    color: var(--puja-green);
    font-size: 12px;
}

.seat-status-bottom {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
    color: var(--puja-muted);
    font-size: 11px;
}

.seat-progress {
    height: 6px;
    background: #eee8dd;
    border-radius: 20px;
    overflow: hidden;
}

.seat-progress span {
    display: block;
    height: 100%;
    background: linear-gradient(90deg, #d9a441, #a84e20);
    border-radius: 20px;
}

/* BOOKING */

.puja-booking-card {
    margin-top: 22px;
    padding: 21px;
    background: #fff;
    border: 1px solid #eadbc2;
    border-radius: 15px;
    box-shadow: 0 12px 30px rgba(107, 73, 31, .06);
}

.booking-price-area {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 18px;
}

.booking-price-label {
    display: block;
    font-size: 11px;
    color: #8e8376;
    margin-bottom: 4px;
}

.booking-price {
    color: var(--puja-primary);
    font-size: 30px;
    font-weight: 800;
    line-height: 1;
}

.currency-symbol {
    font-size: 22px;
}

.secure-booking {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--puja-green);
    font-size: 10px;
    font-weight: 700;
}

.participate-main-btn,
.sidebar-participate-btn {
    width: 100%;
    min-height: 52px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 0 20px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(110deg, #a84e20, #783514);
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: .3px;
    cursor: pointer;
    transition: .3s;
}

.participate-main-btn:hover,
.sidebar-participate-btn:hover {
    background: linear-gradient(110deg, #783514, #a84e20);
    box-shadow: 0 8px 20px rgba(120, 53, 20, .2);
    transform: translateY(-2px);
}

.participate-main-btn:disabled,
.sidebar-participate-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none;
}

.booking-trust-note {
    margin-top: 13px;
    color: #8c8276;
    text-align: center;
    font-size: 10px;
}

.booking-trust-note i {
    color: var(--puja-green);
    margin-right: 4px;
}

/* TRUST STRIP */

.puja-trust-strip {
    background: #fff;
    border-top: 1px solid #f0e8dc;
    border-bottom: 1px solid #f0e8dc;
    padding: 23px 0;
}

.trust-strip-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.trust-icon {
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fbf1e1;
    color: var(--puja-primary);
    border-radius: 13px;
    font-size: 17px;
}

.trust-item strong {
    display: block;
    color: #3b332a;
    font-size: 12px;
    margin-bottom: 4px;
}

.trust-item span {
    display: block;
    color: #93897d;
    font-size: 10px;
}

/* STICKY SECTION NAVIGATION */

.puja-section-navigation {
    position: sticky;
    top: calc(var(--puja-header-height) + var(--puja-sticky-gap));
    z-index: 900;
    width: 100%;
    min-height: var(--puja-nav-height);
    background: rgba(255, 255, 255, .97);
    border: 1px solid #eee7dc;
    border-radius: 0;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 5px 18px rgba(50, 35, 20, .06);
    transition: box-shadow .25s ease;
}

.puja-section-navigation.is-sticky {
    box-shadow: 0 7px 22px rgba(50, 35, 20, .10);
}

.puja-section-nav-inner {
    display: flex;
    align-items: center;
    gap: 30px;
    min-height: var(--puja-nav-height);
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: none;
    scroll-behavior: smooth;
    margin-top:30px;
}

.puja-section-nav-inner::-webkit-scrollbar {
    display: none;
}

.puja-nav-link {
    position: relative;
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    height: var(--puja-nav-height);
    padding: 0;
    white-space: nowrap;
    text-decoration: none !important;
    color: #746a5e;
    font-size: 12px;
    font-weight: 700;
    transition: color .25s ease;
}

.puja-nav-link:hover,
.puja-nav-link.active {
    color: var(--puja-primary);
}

.puja-nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    width: 0;
    height: 3px;
    margin: auto;
    background: var(--puja-primary);
    border-radius: 4px 4px 0 0;
    transition: width .25s ease;
}

.puja-nav-link.active::after {
    width: 100%;
}

/* CONTENT */

.puja-content-section {
    padding: 65px 0;
    background: #fff;
}

.puja-content-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 310px;
    gap: 50px;
    align-items: start;
}

.puja-content-main {
    min-width: 0;
}

.puja-content-block {
    scroll-margin-top: calc(
        var(--puja-header-height) +
        var(--puja-sticky-gap) +
        var(--puja-nav-height) +
        25px
    );
    padding-bottom: 48px;
    margin-bottom: 48px;
    border-bottom: 1px solid #f0ebe4;
}

.puja-content-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-heading {
    margin-bottom: 25px;
}

.section-eyebrow {
    display: block;
    color: #b27a3b;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1.7px;
    margin-bottom: 10px;
}

.section-heading h2,
.related-section-heading h2 {
    font-family: Georgia, "Times New Roman", serif;
    color: #332b23;
    font-size: 30px;
    font-weight: 700;
    margin: 0 0 10px;
}

.section-heading p,
.related-section-heading p {
    color: #8b8277;
    font-size: 13px;
    margin: 0;
}

.puja-rich-content {
    color: #625a50;
    font-size: 14px;
    line-height: 1.95;
    overflow-wrap: anywhere;
}

.puja-rich-content h1,
.puja-rich-content h2,
.puja-rich-content h3,
.puja-rich-content h4 {
    color: #393027;
    margin: 20px 0 10px;
    font-weight: 700;
}

.puja-rich-content p {
    margin-bottom: 15px;
}

.puja-rich-content ul,
.puja-rich-content ol {
    padding-left: 22px;
    margin-bottom: 18px;
}

.puja-rich-content li {
    padding-left: 5px;
    margin-bottom: 9px;
}

.puja-rich-content li::marker {
    color: var(--puja-gold);
}

.puja-rich-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

/* SIDEBAR */

.puja-content-sidebar {
    position: sticky;
    top: 85px;
}

.sidebar-booking-card {
    padding: 25px;
    background: linear-gradient(145deg, #fffaf0, #f8edda);
    border: 1px solid #ebd9b8;
    border-radius: 16px;
    box-shadow: 0 12px 35px rgba(90, 60, 25, .07);
}

.sidebar-eyebrow {
    color: #b27a3b;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.sidebar-card-top h3 {
    font-family: Georgia, "Times New Roman", serif;
    color: #392d21;
    font-size: 22px;
    line-height: 1.35;
    margin: 12px 0;
}

.sidebar-card-top p {
    color: #837568;
    font-size: 12px;
    line-height: 1.8;
}

.sidebar-price {
    border-top: 1px solid #ead9bd;
    border-bottom: 1px solid #ead9bd;
    padding: 15px 0;
    margin: 17px 0;
}

.sidebar-price span {
    display: block;
    color: #8c806e;
    font-size: 11px;
    margin-bottom: 4px;
}

.sidebar-price strong {
    color: var(--puja-primary);
    font-size: 27px;
}

.sidebar-assurance {
    margin-top: 14px;
    color: #8d816f;
    font-size: 10px;
    text-align: center;
}

.sidebar-assurance i {
    color: var(--puja-green);
    margin-right: 5px;
}

.sidebar-highlights-card {
    margin-top: 20px;
    padding: 22px;
    background: #fff;
    border: 1px solid #eee8df;
    border-radius: 14px;
}

.sidebar-highlights-card h4 {
    color: #3b332a;
    font-size: 14px;
    font-weight: 800;
    margin: 0 0 17px;
}

.sidebar-highlight {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 13px;
    color: #71695f;
    font-size: 11px;
}

.sidebar-highlight:last-child {
    margin-bottom: 0;
}

.sidebar-highlight i {
    color: var(--puja-green);
}

/* TEMPLE */

.temple-information-card {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    padding: 24px;
    background: #fffaf2;
    border: 1px solid #eee3d1;
    border-radius: 15px;
}

.temple-information-icon {
    width: 55px;
    height: 55px;
    flex: 0 0 55px;
    border-radius: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f5e7ce;
    color: var(--puja-primary);
    font-size: 23px;
}

.temple-information-content h3 {
    font-family: Georgia, "Times New Roman", serif;
    color: #392e23;
    font-size: 20px;
    margin: 0 0 9px;
}

.temple-location {
    color: #a06c38;
    font-size: 12px;
}

.temple-location i {
    margin-right: 5px;
}

/* FAQ */

.puja-faq-list {
    border-top: 1px solid #eee7dd;
}

.puja-faq-item {
    border-bottom: 1px solid #eee7dd;
}

.puja-faq-question {
    width: 100%;
    padding: 19px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    background: none;
    border: none;
    color: #41372d;
    text-align: left;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

.puja-faq-question i {
    color: var(--puja-primary);
    transition: transform .25s;
}

.puja-faq-item.open .puja-faq-question i {
    transform: rotate(45deg);
}

.puja-faq-answer {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows .3s ease;
}

.puja-faq-answer > div {
    overflow: hidden;
    color: #766d62;
    font-size: 13px;
    line-height: 1.8;
}

.puja-faq-item.open .puja-faq-answer {
    grid-template-rows: 1fr;
}

.puja-faq-item.open .puja-faq-answer > div {
    padding-bottom: 18px;
}

/* RELATED PUJAS */

.related-pujas-section {
    padding: 65px 0;
    background: #fbf8f2;
    border-top: 1px solid #eee8dd;
}

.related-section-heading {
    margin-bottom: 30px;
}

.related-puja-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}

.related-puja-card {
    display: block;
    text-decoration: none !important;
    background: #fff;
    border: 1px solid #eee6d9;
    border-radius: 15px;
    overflow: hidden;
    transition: .3s;
}

.related-puja-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(74, 51, 25, .1);
}

.related-puja-image {
    height: 210px;
    position: relative;
    overflow: hidden;
    background: #f2e8d9;
}

.related-puja-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s;
}

.related-puja-card:hover .related-puja-image img {
    transform: scale(1.06);
}

.related-image-placeholder {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #b68b51;
    font-size: 40px;
}

.related-image-arrow {
    position: absolute;
    right: 14px;
    bottom: 14px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
    color: var(--puja-primary);
    transform: rotate(45deg);
}

.related-puja-body {
    padding: 19px;
}

.related-category {
    color: #b17a3e;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.related-puja-body h3 {
    color: #392f25;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 18px;
    line-height: 1.4;
    margin: 9px 0;
}

.related-puja-body p {
    color: #898075;
    font-size: 11px;
    line-height: 1.7;
    margin-bottom: 15px;
}

.related-puja-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 13px;
    border-top: 1px solid #f0eae0;
    color: var(--puja-primary);
    font-size: 12px;
    font-weight: 800;
}

/* PACKAGE MODAL */

.package-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(31, 24, 17, .68);
    backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: .3s;
}

.package-modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.package-modal-dialog {
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 30px 100px rgba(0,0,0,.25);
    transform: translateY(25px) scale(.98);
    transition: .3s;
}

.package-modal-overlay.show .package-modal-dialog {
    transform: translateY(0) scale(1);
}

.package-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    padding: 26px 28px 20px;
    background: linear-gradient(110deg, #fffaf1, #f8edd9);
    border-bottom: 1px solid #eee2ce;
}

.modal-eyebrow {
    display: block;
    color: #ad753a;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
}

.package-modal-header h2 {
    color: #34291f;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 25px;
    font-weight: 700;
    margin: 0 0 7px;
}

.package-modal-header p {
    color: #8a7e70;
    font-size: 12px;
    margin: 0;
}

.package-modal-close {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #eadcc6;
    border-radius: 50%;
    background: #fff;
    color: #725c43;
    cursor: pointer;
    transition: .2s;
}

.package-modal-close:hover {
    background: var(--puja-primary);
    color: #fff;
    border-color: var(--puja-primary);
}

.package-modal-puja-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 28px;
    background: #fff;
    border-bottom: 1px solid #f1ece4;
}

.modal-puja-icon {
    width: 39px;
    height: 39px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 11px;
    background: #f9eedb;
    color: var(--puja-primary);
    font-size: 17px;
}

.package-modal-puja-info span {
    display: block;
    color: #9a8c7b;
    font-size: 10px;
    margin-bottom: 4px;
}

.package-modal-puja-info strong {
    display: block;
    color: #42372b;
    font-size: 13px;
}

.package-modal-body {
    padding: 22px 28px;
    overflow-y: auto;
}

.package-selection-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.package-selection-heading h3 {
    color: #382f26;
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 4px;
}

.package-selection-heading span {
    color: #938879;
    font-size: 10px;
}

.package-selection-heading .package-select-hint {
    padding: 7px 10px;
    background: #f8f1e6;
    border-radius: 20px;
    color: #987044;
    font-weight: 700;
}

.package-options-list {
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.package-option-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 16px;
    border: 1px solid #ece5da;
    border-radius: 12px;
    background: #fff;
    cursor: pointer;
    transition: .25s;
}

.package-option-card:hover {
    border-color: #d5b47d;
    background: #fffdf9;
}

.package-option-card.selected {
    border-color: var(--puja-primary);
    background: #fffaf2;
    box-shadow: 0 5px 18px rgba(168, 78, 32, .07);
}

.package-option-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.custom-radio-indicator {
    width: 21px;
    height: 21px;
    flex: 0 0 21px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #d5c9b7;
    border-radius: 50%;
    color: transparent;
    font-size: 9px;
    transition: .2s;
}

.package-option-card.selected .custom-radio-indicator {
    background: var(--puja-primary);
    border-color: var(--puja-primary);
    color: #fff;
}

.package-option-content {
    display: block;
    flex: 1;
    min-width: 0;
}

.package-option-top {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.package-option-top strong {
    color: #3c3228;
    font-size: 13px;
    font-weight: 800;
}

.recommended-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 7px;
    border-radius: 20px;
    background: #f8e9c9;
    color: #946523;
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
}

.package-description {
    display: block;
    color: #8c8174;
    font-size: 10px;
    line-height: 1.6;
    margin-top: 6px;
}

.package-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 9px;
}

.package-meta span {
    color: #8d806f;
    font-size: 9px;
}

.package-meta i {
    color: #b27a3b;
    margin-right: 4px;
}

.package-option-price {
    text-align: right;
    flex: 0 0 auto;
}

.package-option-price small {
    display: block;
    color: #a19587;
    font-size: 9px;
    margin-bottom: 4px;
}

.package-option-price strong {
    color: var(--puja-primary);
    font-size: 17px;
    font-weight: 800;
}

.package-selection-error {
    display: none;
    color: #b63e32;
    font-size: 11px;
    margin-top: 12px;
}

.package-selection-error.show {
    display: block;
}

.selected-package-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 15px 17px;
    margin-top: 18px;
    background: #f8f4ed;
    border: 1px solid #eee4d4;
    border-radius: 11px;
}

.selected-summary-info span,
.selected-summary-price span {
    display: block;
    color: #918577;
    font-size: 9px;
    margin-bottom: 5px;
}

.selected-summary-info strong {
    display: block;
    color: #44382d;
    font-size: 12px;
}

.selected-summary-price {
    text-align: right;
}

.selected-summary-price strong {
    display: block;
    color: var(--puja-primary);
    font-size: 20px;
    font-weight: 800;
}

.no-packages-message {
    padding: 40px 15px;
    text-align: center;
    color: #958779;
}

.no-packages-message i {
    font-size: 35px;
    color: #c4a16b;
    margin-bottom: 12px;
}

.no-packages-message h3 {
    color: #463b30;
    font-size: 17px;
}

.no-packages-message p {
    font-size: 12px;
}

.package-modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    padding: 17px 28px;
    background: #fff;
    border-top: 1px solid #eee8df;
}

.modal-cancel-btn {
    min-height: 45px;
    padding: 0 19px;
    border: 1px solid #e5ddd1;
    border-radius: 9px;
    background: #fff;
    color: #776c5e;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.modal-continue-btn {
    min-height: 45px;
    min-width: 155px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    padding: 0 20px;
    border: none;
    border-radius: 9px;
    background: linear-gradient(110deg, #a84e20, #783514);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s;
}

.modal-continue-btn:hover {
    box-shadow: 0 7px 17px rgba(120, 53, 20, .2);
}

.modal-continue-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
}

/* RESPONSIVE */

@media (max-width: 1199px) {
    .puja-hero-grid {
        gap: 30px;
    }

    .puja-gallery-main {
        height: 420px;
    }

    .puja-content-layout {
        gap: 30px;
    }
}

@media (max-width: 991px) {
    .puja-hero-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .puja-gallery-main {
        height: 430px;
    }

    .puja-info-column {
        max-width: 100%;
    }

    .trust-strip-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .puja-content-layout {
        grid-template-columns: 1fr;
    }

    .puja-content-sidebar {
        position: static;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .sidebar-highlights-card {
        margin-top: 0;
    }
}

@media (max-width: 767px) {
    :root {
        --puja-sticky-gap: 7px;
        --puja-nav-height: 50px;
    }

    .puja-detail-hero {
        padding: 25px 0 35px;
    }

    .puja-gallery-main {
        height: 340px;
    }

    .puja-info-top h1 {
        font-size: 31px;
    }

    .puja-quick-details {
        padding: 15px;
        gap: 16px 10px;
    }

    .quick-detail-item {
        gap: 8px;
    }

    .quick-detail-icon {
        width: 34px;
        height: 34px;
        flex-basis: 34px;
        font-size: 13px;
    }

    .trust-strip-grid {
        gap: 18px 12px;
    }

    .trust-item {
        align-items: flex-start;
        gap: 8px;
    }

    .trust-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
        font-size: 14px;
    }

    .trust-item strong {
        font-size: 10px;
    }

    .trust-item span {
        font-size: 9px;
        line-height: 1.5;
    }

    .puja-section-nav-inner {
        gap: 23px;
    }

    .puja-nav-link {
        font-size: 11px;
    }

    .puja-content-section,
    .related-pujas-section {
        padding: 42px 0;
    }

    .section-heading h2,
    .related-section-heading h2 {
        font-size: 25px;
    }

    .puja-content-sidebar {
        grid-template-columns: 1fr;
    }

    .related-puja-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .related-puja-image {
        height: 150px;
    }

    .related-puja-body {
        padding: 14px;
    }

    .related-puja-body h3 {
        font-size: 15px;
    }

    .package-modal-overlay {
        padding: 10px;
    }

    .package-modal-dialog {
        max-height: 94vh;
        border-radius: 15px;
    }

    .package-modal-header {
        padding: 20px 18px 16px;
    }

    .package-modal-header h2 {
        font-size: 21px;
    }

    .package-modal-puja-info {
        padding: 13px 18px;
    }

    .package-modal-body {
        padding: 18px;
    }

    .package-modal-footer {
        padding: 13px 18px;
    }
}

@media (max-width: 480px) {
    .puja-gallery-main {
        height: 270px;
    }

    .gallery-thumb {
        flex-basis: 62px;
        width: 62px;
        height: 53px;
    }

    .puja-quick-details {
        grid-template-columns: 1fr 1fr;
    }

    .booking-price-area {
        align-items: flex-start;
    }

    .secure-booking {
        font-size: 9px;
    }

    .related-puja-grid {
        grid-template-columns: 1fr;
    }

    .related-puja-image {
        height: 195px;
    }

    .package-option-card {
        gap: 9px;
        padding: 12px 10px;
    }

    .package-option-top strong {
        font-size: 11px;
    }

    .package-option-price strong {
        font-size: 14px;
    }

    .package-meta {
        gap: 7px;
    }

    .package-meta span {
        font-size: 8px;
    }

    .package-selection-heading {
        align-items: flex-start;
    }

    .package-selection-heading .package-select-hint {
        font-size: 9px;
    }

    .modal-continue-btn {
        min-width: 130px;
    }
}

/* ACCESSIBILITY: REDUCE MOTION */

@media (prefers-reduced-motion: reduce) {
    .puja-section-nav-inner {
        scroll-behavior: auto;
    }

    .puja-nav-link,
    .puja-nav-link::after {
        transition: none;
    }
}

</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const navigation = document.getElementById('pujaSectionNavigation');
    function updateHeaderOffset() {
        const headerSelectors = [
            '#header',
            '#site-header',
            '.site-header',
            '.main-header',
            '.header-area',
            '.main-navbar',
            '.navbar',
            'header'
        ];
        let header = null;
        for (const selector of headerSelectors) {
            const elements = document.querySelectorAll(selector);
            for (const element of elements) {
                if (
                    element === navigation ||
                    navigation?.contains(element)
                ) {
                    continue;
                }
                const style = window.getComputedStyle(element);
                if (
                    style.position === 'fixed' ||
                    style.position === 'sticky'
                ) {
                    header = element;
                    break;
                }
            }
            if (header) break;
        }
        let headerHeight = 0;
        if (header) {
            const rect = header.getBoundingClientRect();
            const style = window.getComputedStyle(header);
            if (
                rect.height > 0 &&
                rect.bottom > 0 &&
                (style.position === 'fixed' ||
                 style.position === 'sticky')
            ) {
                headerHeight = rect.bottom;
            }
        }
        document.documentElement.style.setProperty(
            '--puja-header-height',
            headerHeight + 'px'
        );
    }
    updateHeaderOffset();
    window.addEventListener('resize', updateHeaderOffset);
    window.addEventListener('scroll', updateHeaderOffset, {
        passive: true
    });
    if ('ResizeObserver' in window) {
        const headerElement = document.querySelector(
            '#header, #site-header, .site-header, .main-header, .header-area, .main-navbar, .navbar, header'
        );
        if (headerElement) {
            const headerObserver = new ResizeObserver(function () {
                updateHeaderOffset();
            });
            headerObserver.observe(headerElement);
        }
    }
    const slides = document.querySelectorAll('.puja-gallery-slide');
    const thumbnails = document.querySelectorAll('.gallery-thumb');
    const previousButton = document.querySelector('.gallery-prev');
    const nextButton = document.querySelector('.gallery-next');
    const galleryCurrent = document.getElementById('galleryCurrent');
    const galleryMain = document.querySelector('.puja-gallery-main');
    let currentSlide = 0;
    let galleryTimer = null;
    function showGallerySlide(index) {
        if (!slides.length) return;
        currentSlide = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle(
                'active',
                i === currentSlide
            );
        });
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle(
                'active',
                i === currentSlide
            );
        });
        if (galleryCurrent) {
            galleryCurrent.textContent = currentSlide + 1;
        }
    }
    function startGalleryAutoplay() {
        if (slides.length <= 1) return;
        clearInterval(galleryTimer);
        galleryTimer = setInterval(function () {
            showGallerySlide(currentSlide + 1);
        }, 5000);
    }
    function stopGalleryAutoplay() {
        clearInterval(galleryTimer);
    }
    if (previousButton) {
        previousButton.addEventListener('click', function () {
            showGallerySlide(currentSlide - 1);
            startGalleryAutoplay();
        });
    }
    if (nextButton) {
        nextButton.addEventListener('click', function () {
            showGallerySlide(currentSlide + 1);
            startGalleryAutoplay();
        });
    }
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', function () {
            showGallerySlide(index);
            startGalleryAutoplay();
        });
    });
    if (galleryMain) {
        galleryMain.addEventListener('mouseenter', stopGalleryAutoplay);
        galleryMain.addEventListener('mouseleave', startGalleryAutoplay);
    }
    startGalleryAutoplay();
    const packageModal = document.getElementById('packageModal');
    const openModalButtons = [
        document.getElementById('openPackageModal'),
        document.getElementById('sidebarParticipateBtn')
    ];
    const closeModalButton = document.getElementById('closePackageModal');
    const cancelModalButton = document.getElementById('cancelPackageSelection');
    const packageCards = document.querySelectorAll('.package-option-card');
    const packageRadios = document.querySelectorAll('.package-radio');
    const selectedPackageName = document.getElementById('selectedPackageName');
    const selectedPackagePrice = document.getElementById('selectedPackagePrice');
    const selectionError = document.getElementById('packageSelectionError');
    const continueButton = document.getElementById('continuePackageSelection');
    function openPackageModal() {
        if (!packageModal) return;
        packageModal.classList.add('show');
        packageModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        const selected = packageModal.querySelector('.package-radio:checked');
        if (selected) {
            selected.focus();
        }
    }
    function closePackageModal() {
        if (!packageModal) return;
        packageModal.classList.remove('show');
        packageModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    openModalButtons.forEach(button => {
        if (button && !button.disabled) {
            button.addEventListener('click', openPackageModal);
        }
    });
    if (closeModalButton) {
        closeModalButton.addEventListener('click', closePackageModal);
    }
    if (cancelModalButton) {
        cancelModalButton.addEventListener('click', closePackageModal);
    }
    if (packageModal) {
        packageModal.addEventListener('click', function (event) {
            if (event.target === packageModal) {
                closePackageModal();
            }
        });
    }
    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            packageModal?.classList.contains('show')
        ) {
            closePackageModal();
        }
    });

    function updatePackageSelection() {
        packageCards.forEach(card => {
            const radio = card.querySelector('.package-radio');
            card.classList.toggle(
                'selected',
                radio && radio.checked
            );
        });
        const selectedPackage = document.querySelector(
            '.package-radio:checked'
        );
        if (selectedPackage) {
            const price = Number(selectedPackage.dataset.price || 0);
            const name = selectedPackage.dataset.name || 'Selected Package';
            if (selectedPackageName) {
                selectedPackageName.textContent = name;
            }
            if (selectedPackagePrice) {
                selectedPackagePrice.textContent = '₹' + price.toLocaleString('en-IN');
            }
            if (selectionError) {
                selectionError.classList.remove('show');
            }
        } else {
            if (selectedPackageName) {
                selectedPackageName.textContent = 'No package selected';
            }
            if (selectedPackagePrice) {
                selectedPackagePrice.textContent = '₹0';
            }
        }
    }
    packageRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            updatePackageSelection();
        });
    });
    packageCards.forEach(card => {
        card.addEventListener('click', function (event) {
            if (event.target.closest('input')) return;
            const radio = card.querySelector('.package-radio');
            if (!radio) return;
            radio.checked = true;
            radio.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        });
    });
    updatePackageSelection();
    if (continueButton) {
        continueButton.addEventListener('click', function () {
            const selected = document.querySelector('.package-radio:checked');
            if (!selected || selected.disabled) {
                if (selectionError) selectionError.classList.add('show');
                return;
            }
            const packageForm = document.getElementById('onlinePujaPackageForm');
            const packageIdInput = document.getElementById('selectedPackageId');
            if (!packageForm || !packageIdInput) {
                console.error('Package selection form not found.');
                return;
            }
            packageIdInput.value = selected.value;
            continueButton.disabled = true;
            const continueText = continueButton.querySelector('span');
            if (continueText) continueText.textContent = 'Please wait...';
            packageForm.submit();
        });
    }
 
    document.querySelectorAll('.puja-faq-question').forEach(button => {
        button.addEventListener('click', function () {
            const currentItem = this.closest('.puja-faq-item');
            if (!currentItem) return;
            document.querySelectorAll('.puja-faq-item').forEach(item => {
                if (item !== currentItem) {
                    item.classList.remove('open');
                }
            });
            currentItem.classList.toggle('open');
        });
    });
    const navLinks = document.querySelectorAll('.puja-nav-link');
    const sections = Array.from(navLinks)
        .map(link => {
            const id = link.getAttribute('href');
            return document.querySelector(id);
        })
        .filter(Boolean);
    function setActiveNavigation(sectionId) {
        navLinks.forEach(link => {
            const isActive =
                link.getAttribute('href') === '#' + sectionId;
            link.classList.toggle('active', isActive);
            if (isActive) {
                link.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'nearest'
                });
            }
        });
    }
    if ('IntersectionObserver' in window && sections.length) {
        let observer;
        function createSectionObserver() {
            if (observer) {
                observer.disconnect();
            }
            const headerHeight = parseFloat(
                getComputedStyle(document.documentElement)
                    .getPropertyValue('--puja-header-height')
            ) || 0;
            const navHeight = navigation
                ? navigation.offsetHeight
                : 58;
            const topOffset = Math.round(
                headerHeight + navHeight + 35
            );
            observer = new IntersectionObserver(function (entries) {
                const visibleSections = entries
                    .filter(entry => entry.isIntersecting)
                    .sort((a, b) => {
                        return a.boundingClientRect.top -
                              b.boundingClientRect.top;
                    });
                if (visibleSections.length) {
                    setActiveNavigation(
                       visibleSections[0].target.id
                    );
                }
            }, {
                root: null,
                rootMargin:
                   '-' + topOffset + 'px 0px -60% 0px',
                threshold: 0
            });
            sections.forEach(section => {
                observer.observe(section);
            });
        }
        createSectionObserver();
        window.addEventListener('resize', createSectionObserver);
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (!target) return;
            event.preventDefault();
            const headerHeight = parseFloat(
               getComputedStyle(document.documentElement)
                   .getPropertyValue('--puja-header-height')
            ) || 0;
            const navHeight = navigation
                ? navigation.offsetHeight
                : 58;
            const gap = window.innerWidth <= 767 ? 7 : 12;
            const extraSpace = 25;
            const totalOffset =
                headerHeight +
                gap +
                navHeight +
                extraSpace;
            const targetPosition =
                target.getBoundingClientRect().top +
                window.scrollY -
                totalOffset;
            navLinks.forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');
            window.scrollTo({
                top: Math.max(0, targetPosition),
                behavior: 'smooth'
            });
            history.replaceState(
                null,
                '',
                targetId
            );
        });
    });
    function updateStickyNavigation() {
        if (!navigation) return;
        const rect = navigation.getBoundingClientRect();
        const headerHeight = parseFloat(
            getComputedStyle(document.documentElement)
                .getPropertyValue('--puja-header-height')
        ) || 0;
        if (rect.top <= headerHeight + 15) {
            navigation.classList.add('is-sticky');
        } else {
            navigation.classList.remove('is-sticky');
        }
    }
    window.addEventListener(
        'scroll',
        updateStickyNavigation,
        { passive: true }
    );
    updateStickyNavigation();
});
</script>
@endpush