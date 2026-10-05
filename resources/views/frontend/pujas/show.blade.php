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

<section class="puja-detail-section py-5">
    <div class="container">

        {{-- Breadcrumb --}}
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

        {{-- Puja Overview --}}
        <div class="row g-4 mb-5">

            <div class="col-lg-6">
                <div class="puja-detail-image-wrapper">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $puja->name }}"
                            class="puja-detail-image"
                        >
                    @else
                        <div class="puja-detail-placeholder">
                            🪔
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
                            <i class="bi bi-flower1"></i>
                            <strong>Deity:</strong>
                            {{ $puja->deity->name }}
                        </p>
                    @endif

                    @if($temple)
                        <p class="detail-meta">
                            <i class="bi bi-building"></i>
                            <strong>Temple:</strong>
                            {{ $temple->name }}
                        </p>
                    @endif

                    @if($location)
                        <p class="detail-meta">
                            <i class="bi bi-geo-alt"></i>
                            {{ $location }}
                        </p>
                    @endif

                    @if($puja->duration)
                        <p class="detail-meta">
                            <i class="bi bi-clock"></i>
                            <strong>Duration:</strong>
                            {{ $puja->duration }}
                        </p>
                    @endif

                    <div class="detail-modes mb-4">
                        @if($puja->is_online)
                            <span class="mode-badge">Online Puja</span>
                        @endif

                        @if($puja->is_offline)
                            <span class="mode-badge">Offline Puja</span>
                        @endif
                    </div>

                    @if($puja->description)
                        <p class="detail-description">
                            {{ $puja->description }}
                        </p>
                    @endif

                    <a
                        href="{{ route('frontend.puja') }}"
                        class="back-to-pujas-btn"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back to All Pujas
                    </a>

                </div>
            </div>

        </div>

        {{-- Detailed Information --}}
        <div class="row g-4">

            <div class="col-lg-8">

                @if($puja->benefits)
                    <div class="detail-content-card mb-4">
                        <h3>
                            <i class="bi bi-stars"></i>
                            Benefits of the Puja
                        </h3>

                        <div class="detail-content">
                            {!! nl2br(e($puja->benefits)) !!}
                        </div>
                    </div>
                @endif

                @if($puja->ritual_details)
                    <div class="detail-content-card mb-4">
                        <h3>
                            <i class="bi bi-flower2"></i>
                            Ritual Details
                        </h3>

                        <div class="detail-content">
                            {!! nl2br(e($puja->ritual_details)) !!}
                        </div>
                    </div>
                @endif

                @if($puja->samagri_details)
                    <div class="detail-content-card mb-4">
                        <h3>
                            <i class="bi bi-basket"></i>
                            Puja Samagri
                        </h3>

                        <div class="detail-content">
                            {!! nl2br(e($puja->samagri_details)) !!}
                        </div>
                    </div>
                @endif

            </div>

            {{-- Temple Information --}}
            <div class="col-lg-4">
                @if($temple)
                    <div class="temple-info-card">
                        <h3>Temple Information</h3>

                        <h5>{{ $temple->name }}</h5>

                        @if($temple->address)
                            <p>
                                <strong>Address:</strong><br>
                                {{ $temple->address }}
                            </p>
                        @endif

                        @if($location)
                            <p>
                                <strong>Location:</strong><br>
                                {{ $location }}
                            </p>
                        @endif

                        @if($temple->pincode ?? false)
                            <p>
                                <strong>PIN Code:</strong>
                                {{ $temple->pincode }}
                            </p>
                        @endif
                    </div>
                @endif
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
    }

    .breadcrumb a {
        color: #ea0505;
        text-decoration: none;
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
        font-size: 85px;
    }

    .puja-overview {
        height: 100%;
        padding: 25px;
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
        font-size: 32px;
        font-weight: 700;
        line-height: 1.35;
    }

    .detail-meta {
        color: #666;
        font-size: 14px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .detail-meta i {
        color: #ea0505;
        margin-right: 6px;
    }

    .detail-description {
        color: #666;
        font-size: 15px;
        line-height: 1.9;
    }

    .detail-modes {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mode-badge {
        padding: 6px 12px;
        color: #34743b;
        background: #f0f8ed;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .back-to-pujas-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    .detail-content-card,
    .temple-info-card {
        padding: 25px;
        background: #fff;
        border: 1px solid #f0e6dd;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .04);
    }

    .detail-content-card h3,
    .temple-info-card h3 {
        margin-bottom: 18px;
        color: #29211c;
        font-size: 21px;
        font-weight: 700;
    }

    .detail-content-card h3 i {
        margin-right: 7px;
        color: #ea0505;
    }

    .detail-content {
        color: #666;
        font-size: 14px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }

    .temple-info-card h5 {
        margin-bottom: 15px;
        font-size: 17px;
        font-weight: 700;
    }

    .temple-info-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    @media (max-width: 767px) {
        .puja-detail-image-wrapper {
            height: 260px;
        }

        .detail-title {
            font-size: 26px;
        }

        .puja-overview,
        .detail-content-card,
        .temple-info-card {
            padding: 20px;
        }
    }
</style>
@endpush
