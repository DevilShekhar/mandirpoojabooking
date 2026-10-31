@extends('frontend.layouts.app')
@section('title', 'Find Your Temple & Puja')
@section('content')
<div class="temple-search-page">
    <section class="temple-hero">
        <div class="hero-overlay"></div>
        <div class="container hero-container">
            <div class="row align-items-center">
                <div class="col-lg-9 col-md-9">
                    <div class="hero-content">
                        <div class="hero-eyebrow">
                            <span></span>
                            DISCOVER DIVINE EXPERIENCES
                        </div>
                        <h1>Find Your Temple & Puja</h1>
                        <p class="hero-description">
                            Search your favourite temple or puja and explore available packages for a divine experience.
                        </p>
                        <div class="hero-search-box">
                            <form id="templeSearchForm">
                                <div class="search-field">
                                    <i class="fa fa-search search-icon"></i>
                                    <input type="text" id="pujaSearch" placeholder="Search temple name or puja name..."
                                        autocomplete="off">
                                    <button type="button" id="clearSearch" class="clear-search d-none">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button type="submit" class="search-submit">
                                        <i class="fa fa-search"></i>
                                        <span>Search</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="popular-searches">
                            <span class="popular-label">Try searching:</span>
                            <button type="button" class="search-chip">Kashi Vishwanath</button>
                            <button type="button" class="search-chip">Rudrabhishek</button>
                            <button type="button" class="search-chip">Tirupati</button>
                            <button type="button" class="search-chip">Satyanarayan Puja</button>
                            <button type="button" class="search-chip">Shirdi Sai Baba</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 d-none d-md-block">
                    <div class="hero-feature-card">
                        <div class="hero-feature-item">
                            <div class="feature-icon">
                                <i class="fa fa-university"></i>
                            </div>
                            <div>
                                <h6>Sacred Temples</h6>
                                <p>Explore famous and spiritual temples</p>
                            </div>
                        </div>
                        <div class="hero-feature-item">
                            <div class="feature-icon">
                                <i class="fa fa-sun-o"></i>
                            </div>
                            <div>
                                <h6>Authentic Pujas</h6>
                                <p>Book traditional puja services</p>
                            </div>
                        </div>
                        <div class="hero-feature-item">
                            <div class="feature-icon">
                                <i class="fa fa-shield"></i>
                            </div>
                            <div>
                                <h6>Safe & Secure Booking</h6>
                                <p>Your devotion, our responsibility</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="search-results-section">
        <div class="container">
            <div class="results-header">
                <div>
                    <h3>Search Results</h3>
                    <p id="resultSummary">
                        Search for your favourite temple or puja.
                    </p>
                </div>
                <div class="sort-wrapper">
                    <label for="sortResults">Sort by:</label>
                    <select id="sortResults">
                        <option value="relevance">Relevance</option>
                        <option value="name">Name A-Z</option>
                    </select>
                </div>
            </div>
            <div id="searchLoading" class="search-loading d-none">
                <div class="spinner-border text-warning"></div>
                <p>Finding temples and pujas...</p>
            </div>
            <div id="searchResults"></div>
            <div id="initialMessage" class="initial-search-state">
                <div class="initial-search-icon">
                    <i class="fa fa-search"></i>
                </div>
                <h5>Discover Your Divine Experience</h5>
                <p>
                    Search by temple name or puja name to explore available temples and rituals.
                </p>
            </div>
            <div id="noResults" class="no-results d-none">
                <div class="initial-search-icon">
                    <i class="fa fa-search-minus"></i>
                </div>
                <h5>No Matching Results Found</h5>
                <p>Try searching with another temple or puja name.</p>
            </div>
        </div>
    </section>
</div>
@endsection
@push('styles')
<style>
.hero-content h1,.puja-item-name,.results-header h3,.temple-name{font-family:Georgia,'Times New Roman',serif}.temple-search-page{background:#f6f7f9;min-height:100vh;color:#182033}.temple-search-page \*{box-sizing:border-box}.temple-hero{position:relative;min-height:360px;display:flex;align-items:center;overflow:hidden;background-image:url("{{ asset('assets/frontend/assets/images/banner/GoldenTemple.png') }}");background-size:cover;background-position:center 48%;background-repeat:no-repeat}.hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(255,247,235,.92) 0,rgba(255,241,221,.82) 35%,rgba(255,229,190,.2) 68%,rgba(0,0,0,.08) 100%)}.hero-container{position:relative;z-index:2;padding-top:35px;padding-bottom:35px}.hero-content{max-width:760px}.hero-eyebrow{font-size:11px;letter-spacing:4px;font-weight:700;color:#76685c;margin-bottom:13px}.hero-eyebrow span{display:inline-block;width:23px;height:2px;background:#dc751c;vertical-align:middle;margin-right:7px}.hero-content h1{font-size:clamp(32px, 4vw, 49px);line-height:1.13;font-weight:700;color:#161616;margin-bottom:12px}.hero-description{max-width:570px;font-size:16px;line-height:1.55;color:#4c4d50;margin-bottom:20px}.hero-search-box{max-width:750px}.search-field{position:relative;display:flex;align-items:center;background:#fff;border:3px solid rgba(255,255,255,.8);border-radius:14px;min-height:60px;box-shadow:0 7px 25px rgba(0,0,0,.1);overflow:hidden}.search-icon{position:absolute;left:18px;color:#858b96;font-size:17px}.search-field input{height:55px;width:100%;min-width:0;border:none;outline:0;padding:0 90px 0 48px;font-size:14px;color:#303747;background:0 0}.search-field input::placeholder{color:#969ba5}.clear-search{position:absolute;right:155px;border:none;background:0 0;color:#777;cursor:pointer}.search-submit{height:50px;min-width:150px;margin-right:2px;border:none;border-radius:10px;background:#d95f00;color:#fff;font-weight:700;font-size:14px;transition:.2s}.puja-view-btn:hover,.search-submit:hover{background:#b94d00}.search-submit i{margin-right:7px}.popular-searches{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-top:14px}.popular-label{color:#454545;font-size:11px;font-weight:600;margin-right:3px}.search-chip{background:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.8);color:#3e4149;border-radius:20px;padding:6px 13px;font-size:11px;font-weight:500;transition:.2s}.search-chip:hover{background:#d95f00;color:#fff;border-color:#d95f00}.hero-feature-card{background:rgba(255,255,255,.9);backdrop-filter:blur(8px);border-radius:12px;padding:20px 17px;box-shadow:0 8px 30px rgba(0,0,0,.12)}.hero-feature-item{display:flex;align-items:center;gap:12px;margin-bottom:20px}.feature-icon,.results-header{align-items:center;display:flex}.hero-feature-item:last-child{margin-bottom:0}.feature-icon{min-width:34px;height:34px;justify-content:center;color:#d65e08;font-size:23px}.temple-image-placeholder,.temple-image-wrapper,.temple-main-image{height:100%;min-height:290px;width:100%}.hero-feature-item h6{font-size:12px;font-weight:700;color:#252525;margin-bottom:3px}.hero-feature-item p{font-size:10px;color:#656565;margin-bottom:0;line-height:1.4}.search-results-section{padding:25px 0 55px;background:#f6f7f9}.results-header{justify-content:space-between;margin-bottom:15px;gap:15px}.results-header h3{font-size:25px;font-weight:700;color:#171e32;margin-bottom:5px}.results-header p{color:#646b77;font-size:13px;margin-bottom:0}.results-header p strong{color:#d95f00}.sort-wrapper{display:flex;align-items:center;gap:10px}.sort-wrapper label{font-size:12px;color:#626775;margin-bottom:0}.sort-wrapper select{height:40px;min-width:130px;padding:0 12px;background:#fff;border:1px solid #e1e3e8;border-radius:9px;color:#303747;font-size:12px;outline:0}.temple-result-card{background:#fff;border-radius:13px;padding:15px;margin-bottom:17px;display:grid;grid-template-columns:275px minmax(0,1fr);gap:20px;box-shadow:0 4px 20px rgba(25,35,50,.055);border:1px solid #f0f0f0;transition:.2s}.temple-result-card:hover{box-shadow:0 7px 25px rgba(25,35,50,.09)}.temple-image-wrapper{position:relative;border-radius:9px;overflow:hidden;background:#eee7dd}.temple-main-image{object-fit:cover;display:block}.temple-image-placeholder{display:flex;justify-content:center;align-items:center;color:#c87535;font-size:55px;background:linear-gradient(135deg,#f6e4cc,#f8f1e8)}.popular-badge{position:absolute;top:12px;left:10px;background:#d95f00;color:#fff;font-size:11px;font-weight:700;padding:6px 11px;border-radius:20px;z-index:2}.popular-badge i{margin-right:4px}.temple-content{min-width:0;display:flex;flex-direction:column}.temple-information{display:flex;justify-content:space-between;gap:12px;margin-bottom:12px}.temple-name{font-size:21px;font-weight:700;color:#172036;margin-bottom:5px}.temple-verified{color:#2196f3;font-size:15px;margin-left:4px}.temple-location{font-size:12px;color:#737987;margin-bottom:7px}.temple-location i{color:#d95f00;margin-right:5px}.temple-description{color:#666d78;font-size:13px;line-height:1.45;margin-bottom:12px}.temple-details-btn{display:inline-flex;align-items:center;justify-content:center;gap:12px;border:1px solid #e66c20;border-radius:25px;color:#cf590d!important;font-size:11px;font-weight:600;padding:9px 15px;white-space:nowrap;text-decoration:none!important;height:37px}.temple-details-btn:hover{background:#d95f00;color:#fff!important}.available-puja-heading{font-size:14px;font-weight:700;color:#252b3a;margin-bottom:10px}.puja-item-name,.puja-price{margin-bottom:5px;font-weight:700}.puja-cards-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px}.puja-item-card{background:#fff;border:1px solid #e9ebef;border-radius:9px;overflow:hidden;text-align:center;min-width:0;box-shadow:0 3px 10px rgba(0,0,0,.045);display:flex;flex-direction:column;transition:.2s}.puja-item-card:hover{transform:translateY(-3px);box-shadow:0 6px 15px rgba(0,0,0,.09)}.puja-image-wrapper{width:100%;height:65px;overflow:hidden;background:#f3e9dc}.puja-item-image{width:100%;height:100%;object-fit:cover}.puja-placeholder{height:100%;display:flex;align-items:center;justify-content:center;font-size:25px;color:#c47b40}.puja-item-body{padding:7px 8px 8px;flex:1;display:flex;flex-direction:column;align-items:center}.puja-item-name{color:#252b3b;font-size:12px;line-height:1.3;min-height:31px;width:100%}.puja-price{font-size:13px;color:#d95f00}.puja-duration{font-size:10px;color:#858b96;margin-bottom:8px}.puja-duration i{margin-right:3px}.puja-view-btn{width:100%;margin-top:auto;padding:7px 4px;background:#d95f00;color:#fff!important;border-radius:6px;text-decoration:none!important;font-size:10px;font-weight:700;transition:.2s}.puja-view-btn i{margin-left:4px}.initial-search-state,.no-results{text-align:center;background:#fff;border-radius:12px;padding:55px 20px}.initial-search-icon{width:65px;height:65px;border-radius:50%;background:#fff0e5;color:#d95f00;display:flex;align-items:center;justify-content:center;margin:auto auto 15px;font-size:26px}.initial-search-state h5,.no-results h5{font-weight:700;color:#20283b}.initial-search-state p,.no-results p{color:#858b96;font-size:13px;margin-bottom:0}.search-loading{text-align:center;padding:45px 20px;color:#d95f00}.search-loading p{margin-top:12px;font-size:13px;color:#737987}@media(max-width:1199px){.temple-result-card{grid-template-columns:230px minmax(0,1fr)}.puja-cards-grid{grid-template-columns:repeat(4,minmax(0,1fr))}}@media(max-width:991px){.temple-hero{min-height:340px}.temple-result-card{grid-template-columns:190px minmax(0,1fr);gap:15px}.temple-image-wrapper,.temple-main-image{min-height:260px}.puja-cards-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:767px){.temple-hero{min-height:370px;background-position:60% center}.hero-overlay{background:rgba(255,245,230,.88)}.hero-container{padding-top:35px;padding-bottom:35px}.hero-content h1{font-size:34px}.hero-description{font-size:14px}.search-field{min-height:54px}.search-field input{height:50px;padding-right:75px;font-size:12px}.search-submit{min-width:48px;height:45px}.search-submit span{display:none}.search-submit i{margin:0}.clear-search{right:65px}.popular-searches{gap:6px}.popular-label{width:100%}.search-chip{padding:6px 10px;font-size:10px}.results-header{align-items:flex-start;flex-direction:column}.temple-result-card{grid-template-columns:1fr;gap:15px}.temple-image-wrapper,.temple-main-image{height:220px;min-height:220px}.temple-information{flex-wrap:wrap}.puja-cards-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:480px){.hero-content h1{font-size:29px}.puja-cards-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.temple-name{font-size:18px}.temple-result-card{padding:11px}.temple-details-btn{font-size:10px}}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function() {
    let searchTimer = null;
    let currentRequest = null;
    let currentResults = [];
    const searchInput = $('#pujaSearch');
    const searchResults = $('#searchResults');
    const searchLoading = $('#searchLoading');
    const initialMessage = $('#initialMessage');
    const noResults = $('#noResults');
    const clearButton = $('#clearSearch');
    const resultSummary = $('#resultSummary');
    const searchUrl = @json(route('tickit-booking.search'));
    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }
    function imageUrl(path) {
        if (!path) {
            return '';
        }
        path = String(path).trim();
        if (/^https?:\/\//i.test(path)) {
            return path;
        }
        if (path.startsWith('/')) {
            return path;
        }
        if (path.startsWith('storage/')) {
            return '/' + path;
        }
        if (path.startsWith('assets/')) {
            return '/' + path;
        }
        return "{{ asset('storage') }}/" + path.replace(/^\/+/, '');
    }
    function renderTempleImage(temple) {
        if (temple.temple_image) {
            return `
                <img src="${escapeHtml(imageUrl(temple.temple_image))}" class="temple-main-image" alt="${escapeHtml(temple.temple_name)}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="temple-image-placeholder" style="display:none;">
                    <i class="fa fa-university"></i>
                </div>
            `;
        }
        return `
            <div class="temple-image-placeholder">
                <i class="fa fa-university"></i>
            </div>
        `;
    }
    function renderPujaImage(puja) {
        if (puja.image) {
            return `
                <img src="${escapeHtml(imageUrl(puja.image))}" class="puja-item-image" alt="${escapeHtml(puja.name)}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="puja-placeholder" style="display:none;">
                    <i class="fa fa-sun-o"></i>
                </div>
            `;
        }
        return `
            <div class="puja-placeholder">
                <i class="fa fa-sun-o"></i>
            </div>
        `;
    }
    function renderPuja(puja) {
        let priceHtml = '';
        if (puja.price !== null &&
            puja.price !== undefined &&
            puja.price !== '') {
            priceHtml = `
                <div class="puja-price">
                    ₹ ${escapeHtml(puja.price)}
                </div>
            `;
        }
        let durationHtml = puja.duration
            ?
            `<div class="puja-duration">
                    <i class="fa fa-clock-o"></i>
                    ${escapeHtml(puja.duration)}
               </div>`
            :
            '';
        return `
            <div class="puja-item-card">
                <div class="puja-image-wrapper">
                    ${renderPujaImage(puja)}
                </div>
                <div class="puja-item-body">
                    <div class="puja-item-name">
                        ${escapeHtml(puja.name)}
                    </div>
                    ${priceHtml}
                    ${durationHtml}
                    <a href="${escapeHtml(puja.url)}"
                       class="puja-view-btn">
                        View Details
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        `;
    }
    function renderTemple(temple, index) {
        let pujaCards = '';
        $.each(temple.pujas || [], function(i, puja) {
            pujaCards += renderPuja(puja);
        });
        let locationHtml = temple.location
            ?
            `<div class="temple-location">
                    <i class="fa fa-map-marker"></i>
                    ${escapeHtml(temple.location)}
               </div>`
            :
            '';
        let description = temple.description ||
            'Explore available puja services and traditional rituals at this temple.';
        let templeDetailsHtml = temple.temple_url
            ?
            `<a href="${escapeHtml(temple.temple_url)}"
                  class="temple-details-btn">
                    View Temple Details
                    <i class="fa fa-arrow-right"></i>
               </a>`
            :
            '';
        return `
            <div class="temple-result-card">
                <div class="temple-image-wrapper">
                    ${renderTempleImage(temple)}
                    ${index === 0 ? `
                        <span class="popular-badge">
                            <i class="fa fa-star"></i>
                            Most Popular
                        </span>
                    ` : ''}
                </div>
                <div class="temple-content">
                    <div class="temple-information">
                        <div>
                            <h4 class="temple-name">
                                ${escapeHtml(temple.temple_name)}
                                ${temple.is_verified ? '<i class="fa fa-check-circle temple-verified"></i>' : ''}
                            </h4>
                            ${locationHtml}
                            <p class="temple-description">
                                ${escapeHtml(description)}
                            </p>
                        </div>
                        ${templeDetailsHtml}
                    </div>
                    <div class="available-puja-heading">
                        Available Pujas (${(temple.pujas || []).length})
                    </div>
                    <div class="puja-cards-grid">
                        ${pujaCards}
                    </div>
                </div>
            </div>
        `;
    }
    function displayResults(results) {
        searchResults.empty();
        currentResults = results || [];
        initialMessage.addClass('d-none');
        if (!currentResults.length) {
            noResults.removeClass('d-none');
            resultSummary.text('No matching temples or pujas found.');
            return;
        }
        noResults.addClass('d-none');
        let totalPujas = 0;
        $.each(currentResults, function(i, temple) {
            totalPujas += (temple.pujas || []).length;
        });
        resultSummary.html(
            'Found <strong>' + currentResults.length +
            ' temples</strong> and <strong>' + totalPujas +
            ' pujas</strong> matching your search.'
        );
        $.each(currentResults, function(index, temple) {
            searchResults.append(renderTemple(temple, index));
        });
    }
    function performSearch(keyword) {
        if (keyword.length < 2) {
            searchResults.empty();
            noResults.addClass('d-none');
            searchLoading.addClass('d-none');
            initialMessage.removeClass('d-none');
            resultSummary.text(
                'Search for your favourite temple or puja.'
            );
            currentResults = [];
            return;
        }
        initialMessage.addClass('d-none');
        noResults.addClass('d-none');
        searchResults.empty();
        searchLoading.removeClass('d-none');
        if (currentRequest) {
            currentRequest.abort();
        }
        currentRequest = $.ajax({
            url: searchUrl,
            type: 'GET',
            data: {
                search: keyword
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    displayResults(response.results);
                } else {
                    displayResults([]);
                }
            },
            error: function(xhr, status) {
                if (status !== 'abort') {
                    searchResults.html(`
                        <div class="alert alert-danger">
                            Unable to load search results. Please try again.
                        </div>
                    `);
                }
            },
            complete: function() {
                searchLoading.addClass('d-none');
            }
        });
    }
    searchInput.on('input', function() {
        const keyword = $(this).val().trim();
        clearButton.toggleClass(
            'd-none',
            keyword.length === 0
        );
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            performSearch(keyword);
        }, 400);
    });
    $('#templeSearchForm').on('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimer);
        performSearch(searchInput.val().trim());
    });
    $('.search-chip').on('click', function() {
        const keyword = $(this).text().trim();
        searchInput.val(keyword);
        clearButton.removeClass('d-none');
        clearTimeout(searchTimer);
        performSearch(keyword);
    });
    clearButton.on('click', function() {
        searchInput.val('');
        clearButton.addClass('d-none');
        clearTimeout(searchTimer);
        if (currentRequest) {
            currentRequest.abort();
        }
        searchResults.empty();
        noResults.addClass('d-none');
        searchLoading.addClass('d-none');
        initialMessage.removeClass('d-none');
        resultSummary.text(
            'Search for your favourite temple or puja.'
        );
        currentResults = [];
        searchInput.focus();
    });
    $('#sortResults').on('change', function() {
        if (!currentResults.length) {
            return;
        }
        let sortedResults = [...currentResults];
        if ($(this).val() === 'name') {
            sortedResults.sort(function(a, b) {
                return (a.temple_name || '').localeCompare(
                    b.temple_name || ''
                );
            });
        }
        searchResults.empty();
        $.each(sortedResults, function(index, temple) {
            searchResults.append(renderTemple(temple, index));
        });
    });
});
</script>
@endpush