
@extends('frontend.layouts.app')

@section('title', 'Online Puja')

@section('content')

<section class="online-puja-section">
    <div class="container">

        {{-- Page Header --}}
        <div class="puja-page-header">
            <span class="puja-eyebrow">
                <i class="fa fa-om"></i>
                DIVINE EXPERIENCES
            </span>

            <h1>Discover Online Pujas</h1>

            <p>
                Participate in sacred rituals performed by experienced priests
                and receive divine blessings from the comfort of your home.
            </p>
        </div>

        {{-- Category Section --}}
        <div class="puja-category-section">

            <div class="puja-section-label">
                <h5>Explore By Category</h5>
                <span>Choose a puja category</span>
            </div>

            <div class="online-puja-categories" id="onlinePujaCategories">

                <button type="button"
                    class="category-filter active"
                    data-category="">
                    <i class="fa fa-th-large"></i>
                    All Pujas
                </button>

                @foreach($categories as $category)
                    <button type="button"
                        class="category-filter"
                        data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach

            </div>
        </div>

        {{-- Results Header --}}
        <div class="puja-results-header">
            <div>
                <span class="puja-results-label">OUR COLLECTION</span>

                <h3 id="selectedCategoryName">All Online Pujas</h3>

                <p>
                    Showing
                    <strong id="pujaCount">{{ $onlinePujas->total() }}</strong>
                    upcoming pujas
                </p>
            </div>

            <div class="puja-results-icon">
                <i class="fa fa-om"></i>
            </div>
        </div>

        {{-- Loader --}}
        <div id="onlinePujaLoader"
            class="online-puja-loader"
            style="display:none;">

            <div class="puja-spinner"></div>
            <p>Finding sacred pujas for you...</p>

        </div>

        {{-- Puja Cards --}}
        <div class="row" id="onlinePujaResults">

            @forelse($onlinePujas as $puja)

                @php
                    $image = $puja->thumbnail_image ?: $puja->banner_image;

                    $totalSeats = (int) ($puja->total_seat ?? 0);
                    $bookedSeats = (int) ($puja->booked_seat ?? 0);
                    $hasSeatLimit = $totalSeats > 0;
                    $availableSeats = $hasSeatLimit
                        ? max(0, $totalSeats - $bookedSeats)
                        : null;

                    $detailUrl = route('frontend.online-puja.show', [
                        'slug' => $puja->slug
                    ]);
                @endphp

                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 puja-grid-item">

                    <div class="online-puja-card">

                        {{-- Image --}}
                        <div class="puja-card-image">

                            <a href="{{ $detailUrl }}">

                                @if($image)
                                    <img
                                        src="{{ asset('storage/' . $image) }}"
                                        alt="{{ $puja->title }}"
                                        loading="lazy">
                                @else
                                    <div class="puja-image-placeholder">
                                        <i class="fa fa-om"></i>
                                    </div>
                                @endif

                            </a>

                            @if($puja->category)
                                <span class="puja-image-category">
                                    {{ $puja->category->name }}
                                </span>
                            @endif

                        </div>

                        {{-- Card Body --}}
                        <div class="puja-card-body">

                            <h4>
                                <a href="{{ $detailUrl }}">
                                    {{ $puja->title }}
                                </a>
                            </h4>

                            <p class="puja-description">
                                {{ \Illuminate\Support\Str::limit(strip_tags($puja->short_description ?? ''), 120) }}
                            </p>

                            {{-- Information --}}
                            <div class="puja-card-information">

                                {{-- Puja Date --}}
                                <div class="puja-info-row">

                                    <span class="puja-info-icon">
                                        <i class="fa fa-calendar"></i>
                                    </span>

                                    <div class="puja-info-text">
                                        <small>Puja Date & Time</small>

                                        <strong>
                                            {{ $puja->puja_datetime
                                                ? \Carbon\Carbon::parse($puja->puja_datetime)->format('d M Y, h:i A')
                                                : 'Not scheduled' }}
                                        </strong>
                                    </div>

                                </div>

                                {{-- Booking Starts --}}
                                <div class="puja-info-row">

                                    <span class="puja-info-icon">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </span>

                                    <div class="puja-info-text">
                                        <small>Booking Starts</small>

                                        <strong>
                                            {{ $puja->booking_start_at
                                                ? \Carbon\Carbon::parse($puja->booking_start_at)->format('d M Y, h:i A')
                                                : 'Not specified' }}
                                        </strong>
                                    </div>

                                </div>

                                {{-- Available Seats --}}
                                <div class="puja-info-row">

                                    <span class="puja-info-icon">
                                        <i class="fa fa-users"></i>
                                    </span>

                                    <div class="puja-info-text">
                                        <small>Available / Total Seats</small>

                                        <strong class="{{ !$hasSeatLimit || $availableSeats > 0 ? 'seats-available' : 'seats-full' }}">
                                            @if($hasSeatLimit)
                                                {{ number_format($availableSeats) }} / {{ number_format($totalSeats) }}
                                            @else
                                                Unlimited
                                            @endif
                                        </strong>
                                        @if($hasSeatLimit)
                                            <small>{{ number_format($bookedSeats) }} already booked</small>
                                        @endif
                                    </div>

                                </div>

                                {{-- Organized By --}}
                                <div class="puja-info-row">

                                    <span class="puja-info-icon">
                                        <i class="fa fa-user"></i>
                                    </span>

                                    <div class="puja-info-text">
                                        <small>Organized By</small>

                                        <strong>
                                            {{ $puja->creator->name ?? 'Admin' }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                            {{-- Details Button --}}
                            <div class="puja-card-footer">

                                <a href="{{ $detailUrl }}" class="puja-details-btn">
                                    View Puja Details
                                    <i class="fa fa-arrow-right"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="puja-empty-state">

                        <div class="empty-icon">
                            <i class="fa fa-om"></i>
                        </div>

                        <h4>No Upcoming Online Pujas</h4>

                        <p>
                            There are currently no upcoming pujas.
                            Please check back soon.
                        </p>

                    </div>
                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div id="onlinePujaPagination">

            @if($onlinePujas->hasPages())

                <div class="online-puja-pagination">

                    <button type="button"
                        class="pagination-link"
                        data-page="{{ $onlinePujas->currentPage() - 1 }}"
                        {{ $onlinePujas->onFirstPage() ? 'disabled' : '' }}>
                        <i class="fa fa-chevron-left"></i>
                        Previous
                    </button>

                    @for($page = 1; $page <= $onlinePujas->lastPage(); $page++)

                        <button type="button"
                            class="pagination-link {{ $onlinePujas->currentPage() == $page ? 'active' : '' }}"
                            data-page="{{ $page }}">
                            {{ $page }}
                        </button>

                    @endfor

                    <button type="button"
                        class="pagination-link"
                        data-page="{{ $onlinePujas->currentPage() + 1 }}"
                        {{ !$onlinePujas->hasMorePages() ? 'disabled' : '' }}>
                        Next
                        <i class="fa fa-chevron-right"></i>
                    </button>

                </div>

            @endif

        </div>

    </div>
</section>

@endsection


@push('styles')

<style>
.online-puja-section {
    padding: 65px 0 75px;
    background: #fafafa;
}

.puja-page-header {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 45px;
}

.puja-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 17px;
    border-radius: 30px;
    background: #fff0e5;
    color: #e76d1b;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin-bottom: 17px;
}

.puja-page-header h1 {
    color: #252525;
    font-size: 38px;
    font-weight: 750;
    margin-bottom: 14px;
}

.puja-page-header p {
    color: #777;
    font-size: 15px;
    line-height: 1.8;
    margin: 0;
}

/* Categories */

.puja-category-section {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 22px 25px;
    margin-bottom: 40px;
}

.puja-section-label {
    margin-bottom: 17px;
}

.puja-section-label h5 {
    font-size: 15px;
    font-weight: 700;
    color: #292929;
    margin-bottom: 4px;
}

.puja-section-label span {
    color: #999;
    font-size: 12px;
}

.online-puja-categories {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.category-filter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 19px;
    border: 1px solid #e8e8e8;
    background: #fff;
    color: #555;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s ease;
}

.category-filter:hover {
    color: #ed741f;
    border-color: #ed741f;
    background: #fff8f2;
}

.category-filter.active {
    background: #ed741f;
    border-color: #ed741f;
    color: #fff;
    box-shadow: 0 4px 12px rgba(237,116,31,.2);
}

/* Results Header */

.puja-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
}

.puja-results-label {
    font-size: 10px;
    font-weight: 700;
    color: #ed741f;
    letter-spacing: 1.6px;
}

.puja-results-header h3 {
    font-size: 24px;
    font-weight: 750;
    color: #262626;
    margin: 5px 0;
}

.puja-results-header p {
    font-size: 13px;
    color: #888;
    margin: 0;
}

.puja-results-header p strong {
    color: #ed741f;
}

.puja-results-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    background: #fff0e5;
    color: #ed741f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

/* Cards */

.puja-grid-item {
    display: flex;
}

.online-puja-card {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    background: #fff;
    border: 1px solid #ededed;
    border-radius: 13px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,.035);
    transition: transform .3s ease, box-shadow .3s ease;
}

.online-puja-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,.09);
    border-color: #f2d3bd;
}

/* Image */

.puja-card-image {
    height: 225px;
    width: 100%;
    position: relative;
    overflow: hidden;
    background: #fff0e5;
    flex-shrink: 0;
}

.puja-card-image > a {
    display: block;
    width: 100%;
    height: 100%;
}

.puja-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s ease;
}

.online-puja-card:hover .puja-card-image img {
    transform: scale(1.06);
}

.puja-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ed741f;
    font-size: 65px;
}

.puja-image-category {
    position: absolute;
    bottom: 13px;
    left: 14px;
    padding: 7px 13px;
    border-radius: 30px;
    background: rgba(255,255,255,.95);
    color: #df691b;
    font-size: 11px;
    font-weight: 700;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
}

/* Body */

.puja-card-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 20px;
}

.puja-card-body h4 {
    font-size: 18px;
    font-weight: 750;
    line-height: 1.45;
    margin: 0 0 9px;
    min-height: 52px;
}

.puja-card-body h4 a {
    color: #292929;
    text-decoration: none;
    transition: color .2s ease;
}

.puja-card-body h4 a:hover {
    color: #ed741f;
}

.puja-description {
    font-size: 13px;
    color: #888;
    line-height: 1.7;
    margin-bottom: 17px;
    min-height: 44px;
}

/* Information */

.puja-card-information {
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 0;
    margin-bottom: 18px;
}

.puja-info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.puja-info-row:last-child {
    margin-bottom: 0;
}

.puja-info-icon {
    width: 36px;
    min-width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #fff2e8;
    color: #ed741f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    line-height: 1;
}

/* Keep Font Awesome glyph visible */
.puja-info-icon i {
    display: inline-block;
    font-family: FontAwesome !important;
    font-style: normal;
    font-weight: normal;
    line-height: 1;
}

.puja-info-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: 3px;
}

.puja-info-text small {
    color: #999;
    font-size: 11px;
}

.puja-info-text strong {
    color: #373737;
    font-size: 12px;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.puja-info-text strong.seats-available {
    color: #198754;
}

.puja-info-text strong.seats-full {
    color: #dc3545;
}

/* Footer */

.puja-card-footer {
    margin-top: auto;
}

.puja-details-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 12px 15px;
    background: #ed741f;
    color: #fff !important;
    border-radius: 7px;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 700;
    transition: all .25s ease;
}

.puja-details-btn:hover {
    background: #d45d12;
}

.puja-details-btn i {
    transition: transform .25s ease;
}

.puja-details-btn:hover i {
    transform: translateX(4px);
}

/* Loader */

.online-puja-loader {
    text-align: center;
    padding: 55px 0;
}

.puja-spinner {
    width: 42px;
    height: 42px;
    border: 4px solid #ffe6d4;
    border-top-color: #ed741f;
    border-radius: 50%;
    animation: pujaSpin .8s linear infinite;
    margin: 0 auto;
}

@keyframes pujaSpin {
    to {
        transform: rotate(360deg);
    }
}

.online-puja-loader p {
    margin-top: 13px;
    color: #888;
    font-size: 13px;
}

/* Empty State */

.puja-empty-state {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    text-align: center;
    padding: 65px 20px;
}

.empty-icon {
    width: 85px;
    height: 85px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff1e6;
    color: #ed741f;
    border-radius: 50%;
    font-size: 38px;
    margin: 0 auto 20px;
}

.puja-empty-state h4 {
    color: #292929;
    font-size: 21px;
    font-weight: 700;
}

.puja-empty-state p {
    color: #888;
    font-size: 14px;
    margin: 0;
}

/* Pagination */

.online-puja-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 25px;
}

.pagination-link {
    min-width: 40px;
    height: 40px;
    padding: 0 13px;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 7px;
    color: #555;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.pagination-link:hover:not(:disabled),
.pagination-link.active {
    background: #ed741f;
    border-color: #ed741f;
    color: #fff;
}

.pagination-link:disabled {
    opacity: .4;
    cursor: not-allowed;
}

/* Responsive */

@media(max-width: 991px) {
    .puja-card-image {
        height: 210px;
    }
}

@media(max-width: 767px) {
    .online-puja-section {
        padding: 40px 0 50px;
    }

    .puja-page-header h1 {
        font-size: 28px;
    }

    .puja-page-header p {
        font-size: 13px;
    }

    .puja-category-section {
        padding: 17px;
    }

    .online-puja-categories {
        justify-content: flex-start;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 7px;
    }

    .category-filter {
        flex-shrink: 0;
        padding: 9px 15px;
    }

    .puja-results-header h3 {
        font-size: 20px;
    }

    .puja-results-icon {
        width: 45px;
        height: 45px;
        font-size: 19px;
    }

    .puja-card-image {
        height: 220px;
    }

    .puja-card-body {
        padding: 17px;
    }
}
</style>

@endpush


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const categoryContainer = document.getElementById('onlinePujaCategories');
    const resultsContainer = document.getElementById('onlinePujaResults');
    const paginationContainer = document.getElementById('onlinePujaPagination');
    const loader = document.getElementById('onlinePujaLoader');

    const countElement = document.getElementById('pujaCount');
    const categoryTitle = document.getElementById('selectedCategoryName');

    const filterUrl = @json(route('frontend.online-puja.filter'));
    const detailBaseUrl = @json(url('/online-puja'));
    const csrfToken = @json(csrf_token());

    let selectedCategory = '';
    let currentPage = 1;
    let requestController = null;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function imageHtml(puja) {
        if (puja.image) {
            return `
                <img
                    src="${escapeHtml(puja.image)}"
                    alt="${escapeHtml(puja.title)}"
                    loading="lazy">
            `;
        }

        return `
            <div class="puja-image-placeholder">
                <i class="fa fa-om"></i>
            </div>
        `;
    }

    function renderPuja(puja) {

        const detailUrl = detailBaseUrl + '/' + encodeURIComponent(puja.slug);

        const totalSeats = Number(puja.total_seat || 0);
        const bookedSeats = Number(puja.booked_seat || 0);
        const hasSeatLimit = totalSeats > 0;
        const availableSeats = hasSeatLimit
            ? Math.max(0, Number(puja.available_seats ?? (totalSeats - bookedSeats)))
            : null;

        const seatClass = !hasSeatLimit || availableSeats > 0
            ? 'seats-available'
            : 'seats-full';

        const seatDisplay = hasSeatLimit
            ? `${availableSeats.toLocaleString('en-IN')} / ${totalSeats.toLocaleString('en-IN')}`
            : 'Unlimited';

        const bookedDisplay = hasSeatLimit
            ? `<small>${bookedSeats.toLocaleString('en-IN')} already booked</small>`
            : '';

        return `
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 puja-grid-item">

                <div class="online-puja-card">

                    <div class="puja-card-image">
                        <a href="${detailUrl}">
                            ${imageHtml(puja)}
                        </a>

                        ${puja.category_name ? `
                            <span class="puja-image-category">
                                ${escapeHtml(puja.category_name)}
                            </span>
                        ` : ''}
                    </div>

                    <div class="puja-card-body">

                        <h4>
                            <a href="${detailUrl}">
                                ${escapeHtml(puja.title)}
                            </a>
                        </h4>

                        <p class="puja-description">
                            ${escapeHtml(puja.short_description)}
                        </p>

                        <div class="puja-card-information">

                            <div class="puja-info-row">
                                <span class="puja-info-icon">
                                    <i class="fa fa-calendar"></i>
                                </span>

                                <div class="puja-info-text">
                                    <small>Puja Date & Time</small>
                                    <strong>${escapeHtml(puja.puja_datetime || 'Not scheduled')}</strong>
                                </div>
                            </div>

                            <div class="puja-info-row">
                                <span class="puja-info-icon">
                                    <i class="fa fa-calendar-check-o"></i>
                                </span>

                                <div class="puja-info-text">
                                    <small>Booking Starts</small>
                                    <strong>${escapeHtml(puja.booking_start_at || 'Not specified')}</strong>
                                </div>
                            </div>

                            <div class="puja-info-row">
                                <span class="puja-info-icon">
                                    <i class="fa fa-users"></i>
                                </span>

                                <div class="puja-info-text">
                                    <small>Available / Total Seats</small>
                                    <strong class="${seatClass}">
                                        ${seatDisplay}
                                    </strong>
                                    ${bookedDisplay}
                                </div>
                            </div>

                            <div class="puja-info-row">
                                <span class="puja-info-icon">
                                    <i class="fa fa-user"></i>
                                </span>

                                <div class="puja-info-text">
                                    <small>Organized By</small>
                                    <strong>${escapeHtml(puja.creator_name || 'Admin')}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="puja-card-footer">
                            <a href="${detailUrl}" class="puja-details-btn">
                                View Puja Details
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        `;
    }

    function renderEmptyState() {
        resultsContainer.innerHTML = `
            <div class="col-12">
                <div class="puja-empty-state">
                    <div class="empty-icon">
                        <i class="fa fa-om"></i>
                    </div>

                    <h4>No Upcoming Online Pujas</h4>

                    <p>
                        No pujas are currently available in this category.
                        Please explore another category or check back later.
                    </p>
                </div>
            </div>
        `;
    }

    function renderPagination(current, last) {

        if (last <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = `
            <div class="online-puja-pagination">

                <button type="button"
                    class="pagination-link"
                    data-page="${current - 1}"
                    ${current <= 1 ? 'disabled' : ''}>
                    <i class="fa fa-chevron-left"></i>
                    Previous
                </button>
        `;

        for (let i = 1; i <= last; i++) {
            html += `
                <button type="button"
                    class="pagination-link ${current === i ? 'active' : ''}"
                    data-page="${i}">
                    ${i}
                </button>
            `;
        }

        html += `
                <button type="button"
                    class="pagination-link"
                    data-page="${current + 1}"
                    ${current >= last ? 'disabled' : ''}>
                    Next
                    <i class="fa fa-chevron-right"></i>
                </button>

            </div>
        `;

        paginationContainer.innerHTML = html;
    }

    async function loadPujas(page = 1) {

        if (requestController) {
            requestController.abort();
        }

        requestController = new AbortController();

        loader.style.display = 'block';
        resultsContainer.style.display = 'none';
        paginationContainer.style.display = 'none';

        try {

            const response = await fetch(filterUrl, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    category: selectedCategory || null,
                    page: page
                }),

                signal: requestController.signal
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Unable to load pujas.');
            }

            currentPage = Number(result.current_page || 1);

            countElement.textContent = result.total ?? 0;

            categoryTitle.textContent = result.category_name || 'All Online Pujas';

            if (Array.isArray(result.data) && result.data.length > 0) {
                resultsContainer.innerHTML = result.data
                    .map(puja => renderPuja(puja))
                    .join('');
            } else {
                renderEmptyState();
            }

            renderPagination(
                Number(result.current_page || 1),
                Number(result.last_page || 1)
            );

        } catch (error) {

            if (error.name === 'AbortError') {
                return;
            }

            console.error('Online Puja AJAX Error:', error);

            resultsContainer.innerHTML = `
                <div class="col-12">
                    <div class="puja-empty-state">
                        <div class="empty-icon">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>

                        <h4>Something went wrong</h4>
                        <p>Please refresh the page and try again.</p>
                    </div>
                </div>
            `;

            paginationContainer.innerHTML = '';

        } finally {

            if (requestController && !requestController.signal.aborted) {
                loader.style.display = 'none';
                resultsContainer.style.display = '';
                paginationContainer.style.display = '';
            }
        }
    }

    // Category click
    categoryContainer.addEventListener('click', function (event) {

        const button = event.target.closest('.category-filter');

        if (!button) return;

        selectedCategory = button.dataset.category || '';

        currentPage = 1;

        categoryContainer.querySelectorAll('.category-filter')
            .forEach(item => item.classList.remove('active'));

        button.classList.add('active');

        loadPujas(1);
    });

    // Pagination click
    paginationContainer.addEventListener('click', function (event) {

        const button = event.target.closest('.pagination-link');

        if (!button || button.disabled) return;

        const page = parseInt(button.dataset.page, 10);

        if (!page || page < 1) return;

        loadPujas(page);

        categoryTitle.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });

});
</script>

@endpush