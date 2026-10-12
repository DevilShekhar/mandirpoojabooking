
@extends('frontend.layouts.app')

@section('title', 'Sanatan Events | Spiritual Programs')

@section('content')
<style>
.sanatan-page{padding:55px 0 65px;background:#f7f8fc;min-height:600px}
.sanatan-heading{margin-bottom:32px}
.sanatan-heading h2{font-size:32px;font-weight:800;color:#242424;margin-bottom:10px;letter-spacing:-.5px}
.sanatan-heading p{font-size:15px;color:#777;margin-bottom:0;line-height:1.8}
.sanatan-heading-line{width:65px;height:4px;background:#e85d04;border-radius:5px;margin:15px 0}
.sanatan-results-header{display:flex;align-items:center;justify-content:space-between;gap:15px;flex-wrap:wrap;margin-bottom:25px;padding:19px 22px;background:#fff;border:1px solid #eee;border-radius:12px}
.sanatan-results-header h4{font-size:20px;font-weight:750;margin:0 0 5px;color:#252525}
.sanatan-results-count{font-size:13px;color:#777}
.sanatan-results-label{display:inline-flex;align-items:center;gap:8px;color:#e85d04;font-size:12px;font-weight:700;background:#fff0e6;border-radius:20px;padding:9px 13px}
.sanatan-card{height:100%;background:#fff;border:1px solid #eee;border-radius:13px;overflow:hidden;box-shadow:0 4px 18px rgba(0,0,0,.045);transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column}
.sanatan-card:hover{transform:translateY(-5px);box-shadow:0 12px 28px rgba(0,0,0,.09)}
.sanatan-card-image{height:225px;overflow:hidden;background:#fff0e6;position:relative}
.sanatan-card-image img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.sanatan-card:hover .sanatan-card-image img{transform:scale(1.05)}
.sanatan-card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#fff0e6,#ffe0c2);color:#e85d04;font-size:48px}
.sanatan-card-body{padding:21px;display:flex;flex-direction:column;flex:1}
.sanatan-event-type{display:inline-block;align-self:flex-start;background:#fff0e6;color:#d94f00;font-size:11px;font-weight:750;letter-spacing:.2px;padding:7px 11px;border-radius:20px;margin-bottom:12px;max-width:100%;overflow-wrap:anywhere}
.sanatan-card-body h5{font-size:18px;font-weight:750;color:#252525;margin-bottom:10px;line-height:1.5;overflow-wrap:anywhere}
.sanatan-card-body h5 a{color:inherit;text-decoration:none}
.sanatan-card-body h5 a:hover{color:#e85d04}
.sanatan-card-description{color:#737373;font-size:13px;line-height:1.8;margin-bottom:16px;flex:1;overflow-wrap:anywhere}
.sanatan-meta{border-top:1px solid #f0f0f0;padding-top:14px;margin-top:auto}
.sanatan-meta p{font-size:12px;color:#666;margin-bottom:9px;line-height:1.7;overflow-wrap:anywhere}
.sanatan-meta p:last-child{margin-bottom:0}
.sanatan-meta i{width:18px;color:#e85d04}
.sanatan-languages{display:flex;flex-wrap:wrap;gap:6px;margin-top:11px;margin-bottom:14px}
.sanatan-language-badge{background:#f2f3f6;color:#555;font-size:11px;font-weight:600;padding:6px 9px;border-radius:5px}
.sanatan-price-row{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:17px;margin-bottom:14px}
.sanatan-price-label{display:block;color:#888;font-size:11px;margin-bottom:3px}
.sanatan-price{color:#e85d04;font-size:20px;font-weight:800;overflow-wrap:anywhere}
.sanatan-view-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;background:#e85d04;color:#fff;text-decoration:none;padding:12px 15px;border-radius:8px;font-size:13px;font-weight:700;transition:background .25s}
.sanatan-view-btn:hover{background:#c94d00;color:#fff;text-decoration:none}
.sanatan-pagination{width:100%;display:flex;justify-content:center;margin-top:32px}
.sanatan-pagination nav{display:flex;justify-content:center;max-width:100%}
.sanatan-pagination .pagination{display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin-bottom:0}
.sanatan-pagination .page-link{border-radius:7px!important;border:1px solid #e3e5eb;color:#444;padding:9px 14px;font-size:13px;background:#fff}
.sanatan-pagination .page-item.active .page-link{background:#e85d04;border-color:#e85d04;color:#fff}
.sanatan-pagination .page-item.disabled .page-link{color:#aaa;background:#f8f8f8}
.sanatan-empty{background:#fff;text-align:center;padding:60px 20px;border-radius:13px;border:1px solid #eee;box-shadow:0 4px 18px rgba(0,0,0,.035)}
.sanatan-empty-icon{width:70px;height:70px;display:flex;align-items:center;justify-content:center;background:#fff0e6;color:#e85d04;font-size:28px;border-radius:50%;margin:0 auto 18px}
.sanatan-empty h5{font-size:20px;font-weight:750;margin-bottom:9px;color:#252525}
.sanatan-empty p{color:#777;font-size:14px;margin-bottom:0;line-height:1.7}
.sanatan-card-link{display:block;text-decoration:none}
@media(max-width:991px){.sanatan-page{padding:35px 0 45px}.sanatan-heading h2{font-size:28px}}
@media(max-width:575px){.sanatan-heading h2{font-size:25px}.sanatan-card-image{height:210px}.sanatan-card-body{padding:17px}.sanatan-results-header{padding:15px}.sanatan-pagination .page-link{padding:8px 11px}}
</style>

<section class="sanatan-page">
    <div class="container">
        <div class="sanatan-heading">
            <h2>Explore Sanatan Events</h2>
            <div class="sanatan-heading-line"></div>
            <p>Discover spiritual programs, devotional gatherings and traditional religious events. Find an experience that connects you with your faith and traditions.</p>
        </div>

        <div class="sanatan-results-header">
            <div>
                <h4>All Sanatan Events</h4>
                <span class="sanatan-results-count">Showing {{ $sanatans->firstItem() ?? 0 }}–{{ $sanatans->lastItem() ?? 0 }} of {{ $sanatans->total() }} events</span>
            </div>
            <span class="sanatan-results-label"><i class="fa fa-om"></i> Spiritual Experiences</span>
        </div>

        <div class="row">
            @forelse($sanatans as $sanatan)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="sanatan-card">
                        <a class="sanatan-card-link" href="{{ route('frontend.sanatanevents.show', $sanatan->slug) }}">
                            <div class="sanatan-card-image">
                                @if($sanatan->banner_image || $sanatan->image)
                                    <img src="{{ asset('storage/' . ($sanatan->banner_image ?: $sanatan->image)) }}" alt="{{ $sanatan->name }}" loading="lazy">
                                @else
                                    <div class="sanatan-card-placeholder"><i class="fa fa-om"></i></div>
                                @endif
                            </div>
                        </a>

                        <div class="sanatan-card-body">
                            @if($sanatan->program_type)
                                <span class="sanatan-event-type">{{ $sanatan->program_type }}</span>
                            @endif

                            <h5>
                                <a href="{{ route('frontend.sanatanevents.show', $sanatan->slug) }}">{{ $sanatan->name }}</a>
                            </h5>

                            <p class="sanatan-card-description">{{ $sanatan->short_description ?: \Illuminate\Support\Str::limit(strip_tags($sanatan->description ?? ''), 130) }}</p>

                            <div class="sanatan-meta">
                                @if($sanatan->duration)
                                    <p><i class="fa fa-clock-o"></i> {{ $sanatan->duration }}</p>
                                @endif

                                <p><i class="fa fa-user"></i> Created by: {{ $sanatan->creator_name ?? $sanatan->creator?->name ?? 'N/A' }}</p>

                                @php
                                    $languages = $sanatan->languages ?? (is_array($sanatan->language) ? $sanatan->language : (is_string($sanatan->language) ? json_decode($sanatan->language, true) : []));
                                    $languages = is_array($languages) ? $languages : [];
                                @endphp

                                @if(count($languages))
                                    <div class="sanatan-languages">
                                        @foreach($languages as $language)
                                            <span class="sanatan-language-badge">{{ $language }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="sanatan-price-row">
                                <div>
                                    <span class="sanatan-price-label">Starting from</span>
                                    <div class="sanatan-price">₹{{ number_format((float) $sanatan->price, 2) }}</div>
                                </div>
                            </div>

                            <a href="{{ route('frontend.sanatanevents.show', $sanatan->slug) }}" class="sanatan-view-btn">
                                View Details <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="sanatan-empty">
                        <div class="sanatan-empty-icon"><i class="fa fa-calendar"></i></div>
                        <h5>No Events Available</h5>
                        <p>There are currently no Sanatan events available. Please check again later.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($sanatans->hasPages())
            <div class="sanatan-pagination">
                {{ $sanatans->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
