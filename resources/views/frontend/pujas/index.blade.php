@extends('frontend.layouts.app')

@section('content')

<section class="our-puja-section py-5">
    <div class="container">

        {{-- Page Heading --}}
        <div class="text-center mb-5">
            <span class="puja-subtitle">DIVINE EXPERIENCES</span>

            <h2 class="puja-heading mt-2">Our Sacred Pujas</h2>

            <p class="puja-intro">
                Explore sacred rituals, discover their benefits,
                and learn more about traditional puja ceremonies.
            </p>
        </div>

        {{-- Puja Listing --}}
        <div class="row g-4">

            @forelse($pujas as $puja)

                @php
                    $temple = $puja->temple;

                    $location = collect([
                        $temple?->city?->name,
                        $temple?->district?->name,
                        $temple?->state?->name,
                    ])->filter()->unique()->implode(', ');

                    $image = $puja->image;

                    $imageUrl = $image
                        ? asset('storage/' . ltrim($image, '/'))
                        : null;
                @endphp

                <div class="col-xl-4 col-lg-4 col-md-6">

                    <div class="puja-card h-100">

                        {{-- Image --}}
                        <div class="puja-image-wrapper">

                            @if($imageUrl)
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $puja->name }}"
                                    class="puja-image"
                                    loading="lazy"
                                >
                            @else
                                <div class="puja-placeholder">
                                    <span>🪔</span>
                                </div>
                            @endif

                            {{-- Category --}}
                            @if($puja->pujaCategory)
                                <span class="puja-category-badge">
                                    {{ $puja->pujaCategory->name }}
                                </span>
                            @endif

                        </div>

                        {{-- Card Content --}}
                        <div class="puja-card-body">

                            <h4 class="puja-title">
                                {{ $puja->name }}
                            </h4>

                            {{-- Puja Code --}}
                            @if($puja->puja_code)
                                <p class="puja-info">
                                    <i class="bi bi-upc-scan"></i>
                                    <strong>Puja Code:</strong>
                                    {{ $puja->puja_code }}
                                </p>
                            @endif

                            {{-- Deity --}}
                            @if($puja->deity)
                                <p class="puja-info">
                                    <i class="bi bi-flower1"></i>
                                    <strong>Deity:</strong>
                                    {{ $puja->deity->name }}
                                </p>
                            @endif

                            {{-- Temple --}}
                            @if($temple)
                                <p class="puja-info">
                                    <i class="bi bi-building"></i>
                                    <strong>Temple:</strong>
                                    {{ $temple->name }}
                                </p>
                            @endif

                            {{-- Location --}}
                            @if($location)
                                <p class="puja-info">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $location }}
                                </p>
                            @endif

                            {{-- Address --}}
                            @if($temple?->address)
                                <p class="puja-info">
                                    <i class="bi bi-geo"></i>
                                    <strong>Address:</strong>
                                    {{ \Illuminate\Support\Str::limit(
                                        $temple->address,
                                        90
                                    ) }}
                                </p>
                            @endif

                            {{-- Duration --}}
                            @if($puja->duration)
                                <p class="puja-info">
                                    <i class="bi bi-clock"></i>
                                    <strong>Duration:</strong>
                                    {{ $puja->duration }}
                                </p>
                            @endif

                            {{-- Description --}}
                            <p class="puja-description">
                                {{ \Illuminate\Support\Str::limit(
                                    strip_tags($puja->description ?? ''),
                                    120
                                ) }}
                            </p>

                            {{-- Booking Modes --}}
                            <div class="puja-modes mb-3">

                                @if($puja->is_online)
                                    <span class="mode-badge">
                                        <i class="bi bi-camera-video"></i>
                                        Online
                                    </span>
                                @endif

                                @if($puja->is_offline)
                                    <span class="mode-badge">
                                        <i class="bi bi-geo-alt"></i>
                                        Offline
                                    </span>
                                @endif

                            </div>

                            {{-- Details Button: Uses Slug --}}
                            @if($puja->slug)
                                <a
                                    href="{{ route('frontend.puja.show', ['slug' => $puja->slug]) }}"
                                    class="puja-details-btn"
                                >
                                    <span>View Full Details</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <span class="puja-details-btn puja-details-disabled">
                                    Details Unavailable
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            @empty

                {{-- Empty State --}}
                <div class="col-12">
                    <div class="empty-puja text-center py-5">

                        <div class="empty-icon">🪔</div>

                        <h4>No Pujas Available</h4>

                        <p class="text-muted mb-0">
                            Please check back later for available pujas.
                        </p>

                    </div>
                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if($pujas->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $pujas->links() }}
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
    .our-puja-section {
        background: #fffaf5;
        padding-top: 60px;
        padding-bottom: 60px;
    }

    .puja-subtitle {
        display: inline-block;
        color: #ea0505;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .puja-heading {
        color: #29211c;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .puja-intro {
        max-width: 650px;
        margin: 0 auto;
        color: #777;
        font-size: 15px;
        line-height: 1.8;
    }

    .puja-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f0e6dd;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        transition: transform .3s ease, box-shadow .3s ease;
    }

    .puja-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .12);
    }

    .puja-image-wrapper {
        height: 230px;
        position: relative;
        overflow: hidden;
        background: #fff0df;
        flex-shrink: 0;
    }

    .puja-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }

    .puja-card:hover .puja-image {
        transform: scale(1.04);
    }

    .puja-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 65px;
        background: linear-gradient(135deg, #fff0df, #ffe0bf);
    }

    .puja-category-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        max-width: calc(100% - 24px);
        padding: 7px 12px;
        color: #ea0505;
        background: #fff;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    .puja-card-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 22px;
    }

    .puja-title {
        color: #29211c;
        font-size: 21px;
        font-weight: 700;
        line-height: 1.45;
        margin-bottom: 14px;
        overflow-wrap: anywhere;
    }

    .puja-info {
        color: #666;
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
        margin-bottom: 8px;
    }

    .puja-info i {
        color: #ea0505;
        margin-right: 5px;
    }

    .puja-info strong {
        color: #444;
        font-weight: 600;
    }

    .puja-description {
        color: #777;
        font-size: 14px;
        line-height: 1.8;
        margin-top: 8px;
        margin-bottom: 16px;
    }

    .puja-modes {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: auto;
    }

    .mode-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        color: #34743b;
        background: #f0f8ed;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .puja-details-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        width: 100%;
        margin-top: 8px;
        padding: 12px 16px;
        color: #fff;
        background: #ea0505;
        border: 1px solid #ea0505;
        border-radius: 7px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background .3s ease, border-color .3s ease;
    }

    .puja-details-btn:hover {
        color: #fff;
        background: #c90000;
        border-color: #c90000;
    }

    .puja-details-btn i {
        font-size: 17px;
        transition: transform .3s ease;
    }

    .puja-details-btn:hover i {
        transform: translateX(4px);
    }

    .puja-details-disabled {
        background: #999;
        border-color: #999;
        cursor: not-allowed;
    }

    .puja-details-disabled:hover {
        color: #fff;
        background: #999;
        border-color: #999;
    }

    .empty-puja {
        padding: 50px 20px;
        background: #fff;
        border: 1px solid #f0e6dd;
        border-radius: 14px;
    }

    .empty-icon {
        font-size: 55px;
        margin-bottom: 15px;
    }

    .empty-puja h4 {
        color: #29211c;
        font-weight: 700;
        margin-bottom: 10px;
    }

    /* Pagination */
    .our-puja-section .pagination {
        gap: 5px;
        flex-wrap: wrap;
    }

    .our-puja-section .page-link {
        color: #ea0505;
        border-color: #f0e6dd;
        border-radius: 5px;
    }

    .our-puja-section .page-item.active .page-link {
        color: #fff;
        background: #ea0505;
        border-color: #ea0505;
    }

    @media (max-width: 767px) {
        .puja-image-wrapper {
            height: 220px;
        }

        .puja-card-body {
            padding: 18px;
        }
    }

    @media (max-width: 576px) {
        .our-puja-section {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .puja-heading {
            font-size: 28px;
        }

        .puja-intro {
            font-size: 14px;
        }

        .puja-image-wrapper {
            height: 210px;
        }

        .puja-title {
            font-size: 19px;
        }
    }
</style>
@endpush