
@extends('frontend.layouts.app')

@section('title', 'Our Temples')
@section('meta_keyword', 'Temples, Hindu Temples, Famous Temples, India Temples')
@section('meta_description', 'Explore our temples and discover famous temples across India.')

@section('content')
<section class="team-area pt-60 pt-sm-44">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="li-section-title capitalize mb-25">
                    <h2><span>Our Temples</span></h2>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($temples as $temple)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="team-member temple-card">
                        <div class="team-thumb temple-card-image">
                            <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                <img
                                    src="{{ $temple->main_image ? asset('storage/' . $temple->main_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                                    alt="{{ $temple->name }}"
                                    loading="lazy">
                            </a>
                        </div>

                        <div class="team-content temple-card-content text-center">
                            <h3>
                                <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                    {{ $temple->name }}
                                </a>
                            </h3>

                            <p class="temple-location">
                                {{ $temple->city->name ?? '' }}
                                @if($temple->city && $temple->state)
                                    ,
                                @endif
                                {{ $temple->state->name ?? '' }}
                            </p>

                            @if($temple->is_verified)
                                <span class="badge bg-success temple-verified">
                                    Verified Temple
                                </span>
                            @endif

                            <a href="{{ route('frontend.our-temple.show', $temple->slug) }}"
                               class="btn btn-primary temple-view-button">
                                View Details
                            </a>
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

        @if($temples->total() > 0)
            <div class="temple-pagination-wrapper">
                <div class="temple-pagination-info">
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
                        <a
                            href="{{ $temples->previousPageUrl() ?? '#' }}"
                            class="temple-page-button temple-page-navigation {{ $temples->onFirstPage() ? 'is-disabled' : '' }}"
                            @if($temples->onFirstPage()) aria-disabled="true" tabindex="-1" @endif>
                            <span aria-hidden="true">&lsaquo;</span>
                            <span>Previous</span>
                        </a>

                        @if($startPage > 1)
                            <a href="{{ $temples->url(1) }}"
                               class="temple-page-button">1</a>

                            @if($startPage > 2)
                                <span class="temple-page-dots">...</span>
                            @endif
                        @endif

                        @for($page = $startPage; $page <= $endPage; $page++)
                            <a
                                href="{{ $temples->url($page) }}"
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

                        <a
                            href="{{ $temples->nextPageUrl() ?? '#' }}"
                            class="temple-page-button temple-page-navigation {{ !$temples->hasMorePages() ? 'is-disabled' : '' }}"
                            @if(!$temples->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>
                            <span>Next</span>
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    </nav>
                @endif
            </div>
        @endif
    </div>
</section>

<style>
.team-area .temple-card {
    height: 360px;
    display: flex;
    flex-direction: column;
    margin-bottom: 0;
    overflow: hidden;
}

.team-area .temple-card-image {
    height: 220px;
    flex: 0 0 220px;
    overflow: hidden;
}

.team-area .temple-card-image a {
    display: block;
    width: 100%;
    height: 100%;
}

.team-area .temple-card-image img {
    display: block;
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.team-area .temple-card-content {
    height: 140px;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px;
    overflow: hidden;
}

.team-area .temple-card-content h3 {
    max-width: 100%;
    margin: 0 0 8px;
    font-size: 18px;
    line-height: 1.4;
}

.team-area .temple-card-content h3 a {
    overflow-wrap: anywhere;
}

.team-area .temple-location {
    margin: 0 0 8px;
    font-size: 13px;
}

.team-area .temple-verified {
    display: inline-block;
    margin-bottom: 5px;
    font-size: 12px;
}

.team-area .temple-view-button {
    display: inline-block;
    margin-top: 4px;
    padding: 7px 14px;
    font-size: 13px;
}

.team-area .temple-pagination-wrapper {
    display: block;
    width: 100%;
    margin: 25px 0 35px;
    padding: 22px 15px;
    border: 1px solid #eeeeee;
    border-radius: 8px;
    background: #ffffff;
    box-sizing: border-box;
    clear: both;
}

.team-area .temple-pagination-info {
    display: block;
    margin-bottom: 18px;
    color: #777777;
    font-size: 14px;
    line-height: 1.6;
    text-align: center;
}

.team-area .temple-pagination-info strong {
    color: #222222;
    font-weight: 700;
}

.team-area nav.temple-pagination {
    position: static;
    display: flex !important;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: auto;
    min-height: 42px;
    margin: 0;
    padding: 0;
    overflow: visible;
    background: transparent;
    border: 0;
    box-shadow: none;
    float: none;
}

.team-area nav.temple-pagination .temple-page-button {
    position: static;
    display: inline-flex !important;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: auto;
    min-width: 42px;
    height: 42px;
    margin: 0;
    padding: 0 13px;
    border: 1px solid #dddddd;
    border-radius: 6px;
    background: #ffffff;
    color: #444444;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    text-decoration: none;
    box-shadow: none;
    outline: none;
    transform: none;
    cursor: pointer;
    transition: background-color .2s ease, color .2s ease, border-color .2s ease;
}

.team-area nav.temple-pagination .temple-page-button span {
    display: inline-block;
    position: static;
    width: auto;
    height: auto;
    margin: 0;
    padding: 0;
    color: inherit;
    font-size: inherit;
    line-height: 1;
    transform: none;
}

.team-area nav.temple-pagination .temple-page-button:hover {
    border-color: #ea0505;
    background: #fff0f0;
    color: #ea0505;
}

.team-area nav.temple-pagination .temple-page-button.is-active,
.team-area nav.temple-pagination .temple-page-button.is-active:hover {
    border-color: #ea0505;
    background: #ea0505;
    color: #ffffff;
}

.team-area nav.temple-pagination .temple-page-navigation {
    padding-right: 15px;
    padding-left: 15px;
}

.team-area nav.temple-pagination .temple-page-button.is-disabled,
.team-area nav.temple-pagination .temple-page-button.is-disabled:hover {
    border-color: #e5e5e5;
    background: #f5f5f5;
    color: #aaaaaa;
    cursor: not-allowed;
    pointer-events: none;
}

.team-area nav.temple-pagination .temple-page-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 42px;
    color: #777777;
    font-size: 14px;
}

@media (max-width: 767px) {
    .team-area .temple-pagination-wrapper {
        padding: 18px 10px;
    }

    .team-area nav.temple-pagination {
        gap: 6px;
    }

    .team-area nav.temple-pagination .temple-page-button {
        min-width: 36px;
        height: 38px;
        padding: 0 10px;
        font-size: 13px;
    }

    .team-area nav.temple-pagination .temple-page-navigation {
        padding-right: 10px;
        padding-left: 10px;
    }

    .team-area .temple-card-content h3 {
        font-size: 16px;
    }
}

@media (max-width: 380px) {
    .team-area nav.temple-pagination {
        gap: 4px;
    }

    .team-area nav.temple-pagination .temple-page-button {
        min-width: 32px;
        height: 36px;
        padding: 0 8px;
        font-size: 12px;
    }
}
</style>
@endsection