@extends('frontend.layouts.app')
@section('title', 'Explore Temples')
@section('meta_keyword', 'Temples, Hindu Temples, Famous Temples, India Temples')
@section('meta_description', 'Discover sacred temples across India. Explore temple details, locations and book online pujas.')
@section('content')
<section class="temple-directory py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <div class="temple-page-header text-center">
                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 mb-3">
                        <i class="fa fa-om me-2"></i>
                        DIVINE DESTINATIONS
                    </span>
                    <h1 class="fw-bold text-dark mb-3">
                        Explore Sacred <span class="text-danger">Temples</span>
                    </h1>
                    <p class="text-muted mx-auto mb-0" style="max-width: 720px;">
                        Discover India's sacred temples, explore their spiritual
                        significance and connect with divine traditions.
                        Find your temple and experience the blessings of devotion.
                    </p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @forelse($temples as $temple)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="temple-directory-card h-100 bg-white rounded-4 shadow-sm overflow-hidden">
                        <div class="temple-directory-image position-relative overflow-hidden">
                            <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                <img src="{{ $temple->main_image
                                        ? asset('storage/' . $temple->main_image)
                                        : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                                    alt="{{ $temple->name }}"
                                    loading="lazy"
                                    class="w-100">
                            </a>
                            @if($temple->is_verified)
                                <span class="temple-verified-badge">
                                    <i class="fa fa-check-circle me-1"></i>
                                    Verified
                                </span>
                            @endif
                            <div class="temple-image-overlay"></div>
                        </div>
                        <div class="temple-directory-content p-3 p-lg-4">
                            <h3 class="temple-directory-title">
                                <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                    {{ $temple->name }}
                                </a>
                            </h3>
                            <div class="temple-directory-location mb-3">
                                <i class="fa fa-map-marker text-danger me-2 mr-2"></i>
                                <span> 
                                    {{ $temple->city->name ?? '' }}
                                    @if($temple->city && $temple->state)
                                       ,
                                    @endif
                                    {{ $temple->state->name ?? '' }}
                                </span>
                            </div>
                            <div class="border-top pt-3">
                                <a href="{{ route('frontend.our-temple.show', $temple->slug) }}"
                                   class="temple-directory-button d-flex align-items-center justify-content-between">
                                    <span>
                                        Explore Temple
                                    </span>
                                    <span class="temple-button-icon">
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="temple-empty-state text-center bg-white rounded-4 shadow-sm p-5">
                        <div class="temple-empty-icon mb-3">
                            <i class="fa fa-university"></i>
                        </div>
                        <h4 class="fw-bold text-dark">
                            No Temples Available
                        </h4>
                        <p class="text-muted mb-0">
                            We are currently updating our temple directory.
                            Please check back soon.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
        @if($temples->total() > 0)
            <div class="temple-pagination-wrapper mt-5">
                <div class="temple-pagination-info text-center mb-3">
                    Showing
                    <strong>{{ $temples->firstItem() }}</strong>
                    to
                    <strong>{{ $temples->lastItem() }}</strong>
                    of
                    <strong>{{ $temples->total() }}</strong>
                    temples
                </div>
                @if($temples->lastPage() > 1)
                    @php
                        $currentPage = $temples->currentPage();
                        $lastPage = $temples->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp
                    <nav class="temple-pagination" aria-label="Temple pagination">
                        <a href="{{ $temples->previousPageUrl() ?? '#' }}"
                           class="temple-page-button temple-page-navigation {{ $temples->onFirstPage() ? 'is-disabled' : '' }}"
                           @if($temples->onFirstPage()) aria-disabled="true" tabindex="-1" @endif>
                            <i class="fa fa-angle-left"></i>
                            <span>Previous</span>
                        </a>
                        @if($startPage > 1)
                            <a href="{{ $temples->url(1) }}"
                               class="temple-page-button">
                                1
                            </a>
                            @if($startPage > 2)
                                <span class="temple-page-dots">...</span>
                            @endif
                        @endif
                        @for($page = $startPage; $page <= $endPage; $page++)
                            <a href="{{ $temples->url($page) }}"
                               class="temple-page-button {{ $page === $currentPage ? 'is-active' : '' }}"
                               @if($page === $currentPage) aria-current="page" @endif>
                                {{ $page }}
                            </a>
                        @endfor
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="temple-page-dots">...</span>
                            @endif
                            <a href="{{ $temples->url($lastPage) }}"
                               class="temple-page-button">
                                {{ $lastPage }}
                            </a>
                        @endif
                        <!-- Next -->
                        <a href="{{ $temples->nextPageUrl() ?? '#' }}"
                           class="temple-page-button temple-page-navigation {{ !$temples->hasMorePages() ? 'is-disabled' : '' }}"
                           @if(!$temples->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>
                            <span>Next</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </nav>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection