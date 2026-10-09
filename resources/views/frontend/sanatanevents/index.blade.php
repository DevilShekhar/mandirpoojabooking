@extends('frontend.layouts.app')

@section('title', 'Sanatan Events')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .sanatan-page{padding:50px 0;background:#f8f9fc;min-height:600px}
    .sanatan-heading{margin-bottom:30px}
    .sanatan-heading h2{font-size:30px;font-weight:700;color:#222;margin-bottom:8px}
    .sanatan-heading p{color:#6c757d;margin-bottom:0}
    .sanatan-filter-card{background:#fff;border-radius:12px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,.06);position:sticky;top:20px}
    .sanatan-filter-card h4{font-size:20px;font-weight:700;margin-bottom:22px;color:#222}
    .sanatan-filter-group{margin-bottom:22px}
    .sanatan-filter-group label{display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:8px}
    .sanatan-filter-group select{width:100%;height:46px;border:1px solid #e1e5eb;border-radius:8px;padding:0 12px;background:#fff;color:#444;outline:none}
    .sanatan-reset-btn{width:100%;border:0;background:#e85d04;color:#fff;padding:12px;border-radius:8px;font-weight:600;transition:.3s}
    .sanatan-reset-btn:hover{background:#c94d00;color:#fff}
    .sanatan-results-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:20px;flex-wrap:wrap}
    .sanatan-results-header h4{font-size:21px;font-weight:700;margin:0;color:#222}
    .sanatan-results-count{color:#777;font-size:14px}
    .sanatan-card{height:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 18px rgba(0,0,0,.06);transition:transform .3s,box-shadow .3s;display:flex;flex-direction:column}
    .sanatan-card:hover{transform:translateY(-5px);box-shadow:0 10px 28px rgba(0,0,0,.1)}
    .sanatan-card-image{height:220px;overflow:hidden;background:#f1f1f1}
    .sanatan-card-image img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .sanatan-card:hover .sanatan-card-image img{transform:scale(1.05)}
    .sanatan-card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fff0e6;color:#e85d04;font-size:48px}
    .sanatan-card-body{padding:20px;display:flex;flex-direction:column;flex:1}
    .sanatan-event-type{display:inline-block;align-self:flex-start;background:#fff0e6;color:#d94f00;font-size:12px;font-weight:700;padding:6px 10px;border-radius:20px;margin-bottom:12px}
    .sanatan-card-body h5{font-size:19px;font-weight:700;color:#222;margin-bottom:10px;line-height:1.4}
    .sanatan-card-description{color:#6c757d;font-size:14px;line-height:1.7;margin-bottom:15px;flex:1}
    .sanatan-meta{border-top:1px solid #eee;padding-top:14px;margin-top:auto}
    .sanatan-meta p{font-size:13px;color:#666;margin-bottom:8px;overflow-wrap:anywhere}
    .sanatan-meta p:last-child{margin-bottom:0}
    .sanatan-languages{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;margin-bottom:15px}
    .sanatan-language-badge{background:#f0f2f5;color:#555;font-size:11px;padding:5px 8px;border-radius:5px}
    .sanatan-price{color:#e85d04;font-size:20px;font-weight:700;margin-bottom:15px}
    .sanatan-view-btn{display:block;text-align:center;background:#e85d04;color:#fff;text-decoration:none;padding:11px 15px;border-radius:7px;font-size:14px;font-weight:600;transition:.3s}
    .sanatan-view-btn:hover{background:#c94d00;color:#fff}
    .sanatan-pagination{width:100%;display:flex;justify-content:center;margin-top:30px}
    .sanatan-pagination nav{width:100%;display:flex;justify-content:center}
    .sanatan-pagination .pagination{display:flex;flex-wrap:wrap;justify-content:center;gap:5px;margin-bottom:0}
    .sanatan-pagination .page-link{border-radius:6px;border:1px solid #e1e5eb;color:#444;padding:8px 13px}
    .sanatan-pagination .page-item.active .page-link{background:#e85d04;border-color:#e85d04;color:#fff}
    .sanatan-empty{background:#fff;text-align:center;padding:55px 20px;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.05)}
    .sanatan-empty h5{font-size:20px;font-weight:700;margin-bottom:8px}
    .sanatan-empty p{color:#777;margin-bottom:0}
    .sanatan-loading{opacity:.5;pointer-events:none}
    .sanatan-filter-card .select2-container{width:100%!important}
    .sanatan-filter-card .select2-container--default .select2-selection--single{height:46px;border:1px solid #e1e5eb;border-radius:8px;display:flex;align-items:center}
    .sanatan-filter-card .select2-container--default .select2-selection--single .select2-selection__rendered{padding-left:12px;padding-right:30px;color:#444;line-height:44px}
    .sanatan-filter-card .select2-container--default .select2-selection--single .select2-selection__arrow{height:44px;right:7px}
    .sanatan-filter-card .select2-dropdown{border:1px solid #e1e5eb;border-radius:8px;overflow:hidden}
    .sanatan-filter-card .select2-search--dropdown .select2-search__field{padding:8px;border:1px solid #ddd;border-radius:5px;outline:none}
    .sanatan-filter-card .select2-results__option{padding:9px 12px;font-size:14px}
    @media(max-width:991px){.sanatan-filter-card{position:static;margin-bottom:25px}.sanatan-page{padding:35px 0}}
    @media(max-width:575px){.sanatan-heading h2{font-size:25px}.sanatan-card-image{height:200px}}
</style>

<section class="sanatan-page">
    <div class="container">
        <div class="sanatan-heading">
            <h2>Sanatan Events</h2>
            <p>Explore spiritual programs, devotional gatherings and traditional religious events.</p>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="sanatan-filter-card">
                    <h4>Filter Events</h4>
                    <form id="sanatanFilterForm">
                        <div class="sanatan-filter-group">
                            <label for="program_type">Event Type</label>
                            <select name="program_type" id="program_type">
                                <option value="">All Event Types</option>
                                @foreach($programTypes as $programType)
                                    <option value="{{ $programType }}" {{ request('program_type') == $programType ? 'selected' : '' }}>{{ $programType }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sanatan-filter-group">
                            <label for="created_by">Created By</label>
                            <select name="created_by" id="created_by">
                                <option value="">Search or select user</option>
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}" {{ request('created_by') == $creator->id ? 'selected' : '' }}>{{ $creator->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" id="resetSanatanFilters" class="sanatan-reset-btn">Reset Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="sanatan-results-header">
                    <h4>Explore Events</h4>
                    <span class="sanatan-results-count" id="sanatanResultsCount">Showing {{ $sanatans->firstItem() ?? 0 }}–{{ $sanatans->lastItem() ?? 0 }} of {{ $sanatans->total() }} events</span>
                </div>

                <div id="sanatanResults">
                    <div class="row g-4" id="sanatanCards">
                        @forelse($sanatans as $sanatan)
                            <div class="col-md-6 col-xl-4">
                                <div class="sanatan-card">
                                    <div class="sanatan-card-image">
                                        @if($sanatan->banner_image || $sanatan->image)
                                            <img src="{{ asset('storage/' . ($sanatan->banner_image ?: $sanatan->image)) }}" alt="{{ $sanatan->name }}">
                                        @else
                                            <div class="sanatan-card-placeholder"><i class="fas fa-om"></i></div>
                                        @endif
                                    </div>
                                    <div class="sanatan-card-body">
                                        @if($sanatan->program_type)
                                            <span class="sanatan-event-type">{{ $sanatan->program_type }}</span>
                                        @endif
                                        <h5>{{ $sanatan->name }}</h5>
                                        <p class="sanatan-card-description">{{ $sanatan->short_description ?: \Illuminate\Support\Str::limit(strip_tags($sanatan->description ?? ''), 130) }}</p>
                                        <div class="sanatan-meta">
                                            @if($sanatan->duration)
                                                <p><i class="far fa-clock me-1"></i> {{ $sanatan->duration }}</p>
                                            @endif
                                            <p><i class="far fa-user me-1"></i> Created by: {{ $sanatan->creator_name ?? 'N/A' }}</p>
                                            @if(!empty($sanatan->languages))
                                                <div class="sanatan-languages">
                                                    @foreach($sanatan->languages as $language)
                                                        <span class="sanatan-language-badge">{{ $language }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="sanatan-price">₹{{ number_format((float) $sanatan->price, 2) }}</div>
                                        <a href="{{ route('frontend.sanatanevents.show', $sanatan->slug) }}" class="sanatan-view-btn">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="sanatan-empty">
                                    <h5>No Events Found</h5>
                                    <p>No Sanatan events are currently available for the selected filters.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="sanatan-pagination" id="sanatanPagination">{{ $sanatans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    const listingUrl = @json(route('frontend.sanatanevents'));
    const detailsBaseUrl = @json(url('/sanatanevents'));
    let currentRequest = null;
    let requestId = 0;

    $('#created_by').select2({
        width: '100%',
        placeholder: 'Search or select user',
        allowClear: true,
        minimumResultsForSearch: 0
    });

    $('#program_type').select2({
        width: '100%',
        placeholder: 'All Event Types',
        allowClear: true,
        minimumResultsForSearch: 0
    });

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function formatPrice(value) {
        return Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function renderLanguages(languages) {
        if (!Array.isArray(languages) || languages.length === 0) {
            return '';
        }

        return `<div class="sanatan-languages">${languages.map(language => `<span class="sanatan-language-badge">${escapeHtml(language)}</span>`).join('')}</div>`;
    }

    function renderEvents(events) {
        if (!events || events.length === 0) {
            return `<div class="col-12"><div class="sanatan-empty"><h5>No Events Found</h5><p>No Sanatan events are currently available for the selected filters.</p></div></div>`;
        }

        return events.map(event => {
            const image = event.image
                ? `<img src="${escapeHtml(event.image)}" alt="${escapeHtml(event.name)}">`
                : `<div class="sanatan-card-placeholder"><i class="fas fa-om"></i></div>`;

            const eventType = event.program_type
                ? `<span class="sanatan-event-type">${escapeHtml(event.program_type)}</span>`
                : '';

            const duration = event.duration
                ? `<p><i class="far fa-clock me-1"></i> ${escapeHtml(event.duration)}</p>`
                : '';

            const detailUrl = event.detail_url || `${detailsBaseUrl}/${encodeURIComponent(event.slug)}`;

            return `
                <div class="col-md-6 col-xl-4">
                    <div class="sanatan-card">
                        <div class="sanatan-card-image">${image}</div>
                        <div class="sanatan-card-body">
                            ${eventType}
                            <h5>${escapeHtml(event.name)}</h5>
                            <p class="sanatan-card-description">${escapeHtml(event.short_description || '')}</p>
                            <div class="sanatan-meta">
                                ${duration}
                                <p><i class="far fa-user me-1"></i> Created by: ${escapeHtml(event.creator_name || 'N/A')}</p>
                                ${renderLanguages(event.languages)}
                            </div>
                            <div class="sanatan-price">₹${formatPrice(event.price)}</div>
                            <a href="${escapeHtml(detailUrl)}" class="sanatan-view-btn">View Details</a>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderPagination(pagination) {
        if (!pagination || pagination.last_page <= 1) {
            $('#sanatanPagination').html('');
            return;
        }

        let html = '<nav aria-label="Sanatan events pagination"><ul class="pagination">';

        if (pagination.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a></li>`;
        }

        for (let page = 1; page <= pagination.last_page; page++) {
            if (page === 1 || page === pagination.last_page || Math.abs(page - pagination.current_page) <= 2) {
                html += `<li class="page-item ${page === pagination.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${page}">${page}</a></li>`;
            } else if (page === pagination.current_page - 3 || page === pagination.current_page + 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        if (pagination.current_page < pagination.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a></li>`;
        }

        html += '</ul></nav>';
        $('#sanatanPagination').html(html);
    }

    function loadEvents(page = 1) {
        const thisRequestId = ++requestId;

        if (currentRequest) {
            currentRequest.abort();
        }

        $('#sanatanResults').addClass('sanatan-loading');

        const request = $.ajax({
            url: listingUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                program_type: $('#program_type').val(),
                created_by: $('#created_by').val(),
                page: page
            },
            success: function (response) {
                if (thisRequestId !== requestId) {
                    return;
                }

                $('#sanatanCards').html(renderEvents(response.events));

                const pagination = response.pagination || {};
                renderPagination(pagination);

                $('#sanatanResultsCount').text(`Showing ${pagination.from || 0}–${pagination.to || 0} of ${pagination.total || 0} events`);
            },
            error: function (xhr, status) {
                if (status !== 'abort' && thisRequestId === requestId) {
                    $('#sanatanCards').html(`<div class="col-12"><div class="sanatan-empty"><h5>Unable to Load Events</h5><p>Please refresh the page and try again.</p></div></div>`);
                    $('#sanatanPagination').html('');
                }
            },
            complete: function () {
                if (thisRequestId === requestId) {
                    $('#sanatanResults').removeClass('sanatan-loading');
                    currentRequest = null;
                }
            }
        });

        currentRequest = request;
    }

    $('#program_type, #created_by').on('change', function () {
        loadEvents(1);
    });

    $('#resetSanatanFilters').on('click', function () {
        $('#program_type').val('').trigger('change.select2');
        $('#created_by').val('').trigger('change.select2');
        loadEvents(1);
    });

    $(document).on('click', '#sanatanPagination a[data-page]', function (event) {
        event.preventDefault();

        const page = parseInt($(this).attr('data-page'), 10);

        if (page > 0) {
            loadEvents(page);
        }
    });
});
</script>
@endpush