@extends('frontend.layouts.app')

@section('title', $sanatan->name)

@section('content')
<section class="sanatan-event-page">
    <div class="container">
        <div class="sanatan-breadcrumb">
            <a href="{{ route('frontend.sanatanevents') }}">
                <i class="fa fa-arrow-left"></i>
                Back to Sanatan Events
            </a>
        </div>

        <div class="sanatan-hero-card">
            @if($sanatan->banner_image)
                <div class="sanatan-hero-image">
                    <img src="{{ asset('storage/' . $sanatan->banner_image) }}" alt="{{ $sanatan->name }}">
                    <div class="sanatan-hero-overlay"></div>
                    <div class="sanatan-hero-content">
                        <span class="sanatan-program-badge">{{ $sanatan->program_type ?: 'Sanatan Event' }}</span>
                        <h1>{{ $sanatan->name }}</h1>
                        @if($sanatan->short_description)
                            <p>{{ $sanatan->short_description }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="sanatan-hero-no-image">
                    <div class="sanatan-hero-icon">
                        <i class="fa fa-om"></i>
                    </div>
                    <div>
                        <span class="sanatan-program-badge">{{ $sanatan->program_type ?: 'Sanatan Event' }}</span>
                        <h1>{{ $sanatan->name }}</h1>
                        @if($sanatan->short_description)
                            <p>{{ $sanatan->short_description }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="sanatan-event-grid">
            <div class="sanatan-main-column">
                <div class="sanatan-card">
                    <div class="sanatan-card-header">
                        <div>
                            <span class="sanatan-small-label">EVENT DETAILS</span>
                            <h2>About This Event</h2>
                        </div>
                        <div class="sanatan-header-icon">
                            <i class="fa fa-info-circle"></i>
                        </div>
                    </div>
                    <div class="sanatan-description">
                        @if($sanatan->description)
                            {!! $sanatan->description !!}
                        @else
                            <p class="sanatan-empty">Event description is not available.</p>
                        @endif
                    </div>
                </div>

                @if($sanatan->image)
                    <div class="sanatan-card">
                        <div class="sanatan-card-header">
                            <div>
                                <span class="sanatan-small-label">EVENT GALLERY</span>
                                <h2>Event Image</h2>
                            </div>
                            <div class="sanatan-header-icon">
                                <i class="fa fa-picture-o"></i>
                            </div>
                        </div>
                        <div class="sanatan-event-image">
                            <img src="{{ asset('storage/' . $sanatan->image) }}" alt="{{ $sanatan->name }}">
                        </div>
                    </div>
                @endif

                @if(!empty($sanatan->languages))
                    <div class="sanatan-card">
                        <div class="sanatan-card-header">
                            <div>
                                <span class="sanatan-small-label">LANGUAGE OPTIONS</span>
                                <h2>Available Languages</h2>
                            </div>
                            <div class="sanatan-header-icon">
                                <i class="fa fa-language"></i>
                            </div>
                        </div>
                        <div class="sanatan-language-list">
                            @foreach($sanatan->languages as $language)
                                <span class="sanatan-language">
                                    <i class="fa fa-check-circle-o"></i>
                                    {{ $language }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($relatedSanatans->count())
                    <div class="sanatan-related-section">
                        <div class="sanatan-related-heading">
                            <div>
                                <span class="sanatan-small-label">EXPLORE MORE</span>
                                <h2><i class="fa fa-heart-o"></i> Related Sanatan Events</h2>
                            </div>
                            <a href="{{ route('frontend.sanatanevents') }}">
                                View All
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>

                        <div class="row">
                            @foreach($relatedSanatans as $relatedSanatan)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="sanatan-related-card">
                                        <a href="{{ route('frontend.sanatanevents.show', $relatedSanatan->slug) }}" class="sanatan-related-image">
                                            @if($relatedSanatan->image)
                                                <img src="{{ asset('storage/' . $relatedSanatan->image) }}" alt="{{ $relatedSanatan->name }}">
                                            @elseif($relatedSanatan->banner_image)
                                                <img src="{{ asset('storage/' . $relatedSanatan->banner_image) }}" alt="{{ $relatedSanatan->name }}">
                                            @else
                                                <div class="sanatan-related-placeholder">
                                                    <i class="fa fa-om"></i>
                                                </div>
                                            @endif
                                        </a>

                                        <div class="sanatan-related-body">
                                            <span class="sanatan-related-type">{{ $relatedSanatan->program_type ?: 'Sanatan Event' }}</span>
                                            <h3>
                                                <a href="{{ route('frontend.sanatanevents.show', $relatedSanatan->slug) }}">
                                                    {{ $relatedSanatan->name }}
                                                </a>
                                            </h3>
                                            <p>{{ $relatedSanatan->short_description ?: \Illuminate\Support\Str::limit(strip_tags($relatedSanatan->description ?? ''), 90) }}</p>
                                            <div class="sanatan-related-footer">
                                                <strong>₹{{ number_format((float) $relatedSanatan->price, 2) }}</strong>
                                                <a href="{{ route('frontend.sanatanevents.show', $relatedSanatan->slug) }}">
                                                    View Details
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="sanatan-sidebar">
                <div class="sanatan-booking-card">
                    <div class="sanatan-price-box">
                        <span>Starting Price</span>
                        <strong><i class="fa fa-inr"></i> {{ number_format((float) $sanatan->price, 2) }}</strong>
                    </div>

                    <div class="sanatan-booking-title">
                        <i class="fa fa-calendar-check-o"></i>
                        Event Information
                    </div>

                    <div class="sanatan-info-list">
                        <div class="sanatan-info-item">
                            <div class="sanatan-info-icon">
                                <i class="fa fa-list-alt"></i>
                            </div>
                            <div>
                                <span>Program Type</span>
                                <strong>{{ $sanatan->program_type ?: 'N/A' }}</strong>
                            </div>
                        </div>

                        @if($sanatan->duration)
                            <div class="sanatan-info-item">
                                <div class="sanatan-info-icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div>
                                    <span>Duration</span>
                                    <strong>{{ $sanatan->duration }}</strong>
                                </div>
                            </div>
                        @endif

                        <div class="sanatan-info-item">
                            <div class="sanatan-info-icon">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div>
                                <span>Languages</span>
                                <strong>{{ count($sanatan->languages ?? []) }} {{ count($sanatan->languages ?? []) === 1 ? 'Language' : 'Languages' }}</strong>
                            </div>
                        </div>

                        <div class="sanatan-info-item">
                            <div class="sanatan-info-icon">
                                <i class="fa fa-laptop"></i>
                            </div>
                            <div>
                                <span>Online Booking</span>
                                <strong class="{{ $sanatan->is_online ? 'available' : 'not-available' }}">
                                    <i class="fa {{ $sanatan->is_online ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $sanatan->is_online ? 'Available' : 'Not Available' }}
                                </strong>
                            </div>
                        </div>

                        <div class="sanatan-info-item">
                            <div class="sanatan-info-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div>
                                <span>Offline Booking</span>
                                <strong class="{{ $sanatan->is_offline ? 'available' : 'not-available' }}">
                                    <i class="fa {{ $sanatan->is_offline ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $sanatan->is_offline ? 'Available' : 'Not Available' }}
                                </strong>
                            </div>
                        </div>

                        @if($sanatan->created_at)
                            <div class="sanatan-info-item">
                                <div class="sanatan-info-icon">
                                    <i class="fa fa-calendar-plus-o"></i>
                                </div>
                                <div>
                                    <span>Added On</span>
                                    <strong>{{ $sanatan->created_at->format('d M Y') }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($sanatan->updated_at)
                            <div class="sanatan-info-item">
                                <div class="sanatan-info-icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div>
                                    <span>Last Updated</span>
                                    <strong>{{ $sanatan->updated_at->format('d M Y') }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($sanatan->is_online || $sanatan->is_offline)
                        <a href="{{ route('frontend.sanatanevents') }}" class="sanatan-primary-btn">
                            <i class="fa fa-calendar-check-o"></i>
                            Explore Events
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    @else
                        <button type="button" class="sanatan-primary-btn disabled" disabled>
                            <i class="fa fa-ban"></i>
                            Booking Unavailable
                        </button>
                    @endif
                </div>

                <div class="sanatan-creator-card">
                    <div class="sanatan-creator-heading">
                        <i class="fa fa-user-circle-o"></i>
                        <span>Event Added By</span>
                    </div>

                    <div class="sanatan-creator-profile">
                        <div class="sanatan-creator-avatar">
                            @if(isset($sanatan->creator) && $sanatan->creator)
                                {{ strtoupper(substr($sanatan->creator->name, 0, 1)) }}
                            @else
                                <i class="fa fa-user-o"></i>
                            @endif
                        </div>
                        <div class="sanatan-creator-info">
                            <strong>{{ $sanatan->creator->name ?? 'Admin' }}</strong>
                            <span>
                                <i class="fa fa-check-circle-o"></i>
                                Event Creator
                            </span>
                        </div>
                    </div>

                    @if(isset($sanatan->creator) && $sanatan->creator && $sanatan->creator->email)
                        <div class="sanatan-creator-detail">
                            <i class="fa fa-envelope-o"></i>
                            <span>{{ $sanatan->creator->email }}</span>
                        </div>
                    @endif

                    @if(isset($sanatan->creator) && $sanatan->creator && $sanatan->creator->created_at)
                        <div class="sanatan-creator-detail">
                            <i class="fa fa-calendar"></i>
                            <span>Member since {{ $sanatan->creator->created_at->format('M Y') }}</span>
                        </div>
                    @endif
                </div>

                <div class="sanatan-help-card">
                    <div class="sanatan-help-icon">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <h4>Discover Spiritual Events</h4>
                    <p>Explore Sanatan events and discover spiritual programs suitable for you and your family.</p>
                    <a href="{{ route('frontend.sanatanevents') }}">
                        Browse Sanatan Events
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.sanatan-event-page{background:#fffaf4;padding:35px 0 70px}
.sanatan-breadcrumb{margin-bottom:20px}
.sanatan-breadcrumb a{display:inline-flex;align-items:center;gap:8px;color:#a9430b;font-size:14px;font-weight:600;text-decoration:none}
.sanatan-breadcrumb a:hover{color:#702d0b}
.sanatan-hero-card{overflow:hidden;border-radius:18px;background:#fff;box-shadow:0 10px 35px rgba(80,38,10,.08);margin-bottom:30px}
.sanatan-hero-image{position:relative;height:390px;overflow:hidden}
.sanatan-hero-image>img{width:100%;height:100%;object-fit:cover}
.sanatan-hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(38,18,7,.82),rgba(38,18,7,.42) 48%,rgba(38,18,7,.08))}
.sanatan-hero-content{position:absolute;left:55px;right:35px;bottom:45px;max-width:720px;color:#fff}
.sanatan-hero-content h1,.sanatan-hero-no-image h1{margin:15px 0 12px;font-size:42px;line-height:1.2;font-weight:800;color:#fff;overflow-wrap:anywhere}
.sanatan-hero-content p,.sanatan-hero-no-image p{margin:0;color:rgba(255,255,255,.9);font-size:16px;line-height:1.7}
.sanatan-program-badge{display:inline-flex;align-items:center;padding:7px 14px;border-radius:30px;background:#fff0df;color:#a9430b;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
.sanatan-hero-no-image{min-height:300px;display:flex;align-items:center;gap:30px;padding:50px;background:linear-gradient(135deg,#8d3209,#d65b16)}
.sanatan-hero-icon{width:110px;height:110px;flex:0 0 110px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:rgba(255,255,255,.15);color:#fff;font-size:55px}
.sanatan-event-grid{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:30px;align-items:start}
.sanatan-main-column{min-width:0}
.sanatan-card,.sanatan-booking-card,.sanatan-creator-card,.sanatan-help-card{background:#fff;border:1px solid #f0e2d3;border-radius:16px;box-shadow:0 7px 25px rgba(85,42,15,.055)}
.sanatan-card{padding:30px;margin-bottom:25px}
.sanatan-card-header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding-bottom:18px;margin-bottom:25px;border-bottom:1px solid #f0e7df}
.sanatan-small-label{display:block;margin-bottom:5px;color:#c65a18;font-size:11px;font-weight:800;letter-spacing:1.2px}
.sanatan-card-header h2,.sanatan-related-heading h2{margin:0;color:#4f2818;font-size:24px;font-weight:750}
.sanatan-header-icon{width:42px;height:42px;display:flex;align-items:center;justify-content:center;flex:0 0 42px;border-radius:50%;background:#fff1e3;color:#c94d0b;font-size:16px}
.sanatan-description{color:#575757;font-size:15px;line-height:1.9;overflow-wrap:anywhere}
.sanatan-description p{margin-bottom:16px}
.sanatan-description h1,.sanatan-description h2,.sanatan-description h3,.sanatan-description h4{color:#542916;margin-top:25px;margin-bottom:12px}
.sanatan-description ul,.sanatan-description ol{padding-left:25px;margin-bottom:18px}
.sanatan-description img,.sanatan-description video,.sanatan-description iframe{max-width:100%;border-radius:10px}
.sanatan-description table{width:100%;max-width:100%;border-collapse:collapse;margin:20px 0}
.sanatan-description table td,.sanatan-description table th{padding:10px;border:1px solid #eadfd5}
.sanatan-empty{color:#999}
.sanatan-event-image{overflow:hidden;border-radius:12px;background:#faf6f1;text-align:center}
.sanatan-event-image img{display:block;width:100%;max-height:500px;object-fit:contain}
.sanatan-language-list{display:flex;flex-wrap:wrap;gap:10px}
.sanatan-language{display:inline-flex;align-items:center;gap:7px;padding:9px 14px;border-radius:30px;background:#fff5e9;border:1px solid #f4ddc7;color:#70401f;font-size:13px;font-weight:600}
.sanatan-language i{color:#c94d0b;font-size:11px}
.sanatan-sidebar{position:sticky;top:20px;min-width:0}
.sanatan-booking-card{padding:25px;margin-bottom:20px}
.sanatan-price-box{padding:20px;margin-bottom:22px;border-radius:12px;background:linear-gradient(135deg,#fff5e9,#fffaf5);border:1px solid #f4dfcb}
.sanatan-price-box span{display:block;color:#777;font-size:12px;margin-bottom:5px}
.sanatan-price-box strong{display:block;color:#248343;font-size:31px;font-weight:800}
.sanatan-price-box strong i{font-size:23px}
.sanatan-booking-title{display:flex;align-items:center;gap:9px;padding-bottom:17px;border-bottom:1px solid #eee5dd;color:#572a17;font-size:17px;font-weight:750}
.sanatan-booking-title i{color:#c94d0b}
.sanatan-info-list{margin-top:5px}
.sanatan-info-item{display:flex;align-items:center;gap:12px;padding:15px 0;border-bottom:1px solid #f1ebe5}
.sanatan-info-icon{width:36px;height:36px;display:flex;align-items:center;justify-content:center;flex:0 0 36px;border-radius:9px;background:#fff4e8;color:#c94d0b;font-size:13px}
.sanatan-info-item>div:last-child{min-width:0}
.sanatan-info-item span{display:block;color:#888;font-size:11px;margin-bottom:3px}
.sanatan-info-item strong{display:block;color:#4d4d4d;font-size:13px;overflow-wrap:anywhere}
.sanatan-info-item strong i{margin-right:3px}
.sanatan-info-item strong.available{color:#258044}
.sanatan-info-item strong.not-available{color:#999}
.sanatan-primary-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:22px;padding:14px 18px;border:0;border-radius:9px;background:#c94d0b;color:#fff!important;font-size:14px;font-weight:700;text-align:center;text-decoration:none!important;transition:all .25s ease}
.sanatan-primary-btn:hover{background:#9f3908;transform:translateY(-1px)}
.sanatan-primary-btn.disabled{background:#999;cursor:not-allowed}
.sanatan-creator-card{padding:22px;margin-bottom:20px}
.sanatan-creator-heading{display:flex;align-items:center;gap:9px;padding-bottom:15px;margin-bottom:17px;border-bottom:1px solid #eee6df;color:#542916;font-size:15px;font-weight:750}
.sanatan-creator-heading i{color:#c94d0b;font-size:19px}
.sanatan-creator-profile{display:flex;align-items:center;gap:13px}
.sanatan-creator-avatar{width:52px;height:52px;flex:0 0 52px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:linear-gradient(135deg,#c94d0b,#e47a30);color:#fff;font-size:19px;font-weight:800}
.sanatan-creator-info{min-width:0}
.sanatan-creator-info strong{display:block;color:#4c291b;font-size:15px;overflow-wrap:anywhere}
.sanatan-creator-info span{display:flex;align-items:center;gap:5px;margin-top:4px;color:#888;font-size:11px}
.sanatan-creator-info span i{color:#2d934f}
.sanatan-creator-detail{display:flex;align-items:flex-start;gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid #f1ebe5;color:#777;font-size:12px;overflow-wrap:anywhere}
.sanatan-creator-detail i{color:#c94d0b;margin-top:3px}
.sanatan-creator-detail span{min-width:0;overflow-wrap:anywhere}
.sanatan-help-card{padding:25px;text-align:center;background:linear-gradient(145deg,#fff7ed,#fff)}
.sanatan-help-icon{width:55px;height:55px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border-radius:50%;background:#fff0df;color:#c94d0b;font-size:23px}
.sanatan-help-card h4{margin:0 0 9px;color:#512b1b;font-size:17px;font-weight:750}
.sanatan-help-card p{margin:0 0 17px;color:#777;font-size:13px;line-height:1.7}
.sanatan-help-card a{color:#c94d0b;font-size:13px;font-weight:700;text-decoration:none}
.sanatan-help-card a i{margin-left:5px}
.sanatan-related-section{margin-top:45px}
.sanatan-related-heading{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:22px}
.sanatan-related-heading h2 i{color:#c94d0b;font-size:21px;margin-right:5px}
.sanatan-related-heading>a{color:#c94d0b;font-size:13px;font-weight:700;text-decoration:none}
.sanatan-related-heading>a i{margin-left:5px}
.sanatan-related-card{height:100%;overflow:hidden;background:#fff;border:1px solid #f0e2d3;border-radius:14px;box-shadow:0 5px 20px rgba(85,42,15,.05);transition:all .25s ease}
.sanatan-related-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(85,42,15,.1)}
.sanatan-related-image{display:block;height:190px;overflow:hidden;background:#fff3e6}
.sanatan-related-image img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
.sanatan-related-card:hover .sanatan-related-image img{transform:scale(1.04)}
.sanatan-related-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#c45a16;font-size:45px}
.sanatan-related-body{padding:18px}
.sanatan-related-type{display:inline-block;padding:5px 9px;border-radius:20px;background:#fff2e4;color:#b04a0e;font-size:10px;font-weight:750;text-transform:uppercase}
.sanatan-related-body h3{margin:12px 0 8px;font-size:17px;line-height:1.4}
.sanatan-related-body h3 a{color:#512b1b;text-decoration:none}
.sanatan-related-body h3 a:hover{color:#c94d0b}
.sanatan-related-body p{min-height:48px;margin-bottom:15px;color:#777;font-size:12px;line-height:1.7}
.sanatan-related-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:13px;border-top:1px solid #eee7df}
.sanatan-related-footer strong{color:#248343;font-size:16px}
.sanatan-related-footer a{color:#c94d0b;font-size:12px;font-weight:700;text-decoration:none}
.sanatan-related-footer a i{margin-left:4px}
@media(max-width:991px){.sanatan-event-grid{grid-template-columns:1fr}.sanatan-sidebar{position:static}.sanatan-booking-card{margin-top:5px}}
@media(max-width:767px){.sanatan-event-page{padding:25px 0 50px}.sanatan-hero-image{height:330px}.sanatan-hero-content{left:25px;right:25px;bottom:30px}.sanatan-hero-content h1,.sanatan-hero-no-image h1{font-size:29px}.sanatan-hero-content p,.sanatan-hero-no-image p{font-size:14px}.sanatan-hero-no-image{min-height:260px;padding:30px;gap:20px;align-items:flex-start;flex-direction:column}.sanatan-hero-icon{width:65px;height:65px;flex-basis:65px;font-size:30px}.sanatan-card{padding:20px}.sanatan-card-header h2,.sanatan-related-heading h2{font-size:21px}.sanatan-related-heading{align-items:flex-start;flex-direction:column}.sanatan-related-section{margin-top:30px}.sanatan-related-body p{min-height:0}}
</style>
@endpush