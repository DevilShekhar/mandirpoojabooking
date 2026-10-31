@extends('frontend.layouts.app')

@section('title', $news->title)
@section('meta_keyword', $news->title . ', Sri Mandir News, Temple News')
@section('meta_description', $news->short_description ?: \Illuminate\Support\Str::limit(strip_tags($news->description ?? ''), 160))

@section('content')
<section class="news-detail-page">
    <div class="container">
        <div class="news-breadcrumb">
            <a href="{{ route('frontend.news') }}">
                <i class="fa fa-arrow-left"></i>
                Back to News
            </a>
        </div>

        <div class="news-hero-card">
            @if($news->image)
                <div class="news-hero-image">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}">
                    <div class="news-hero-overlay"></div>
                    <div class="news-hero-content">
                        <span class="news-category-badge">
                            <i class="fa fa-newspaper-o"></i>
                            Sri Mandir News
                        </span>
                        <h1>{{ $news->title }}</h1>
                        @if($news->short_description)
                            <p>{{ $news->short_description }}</p>
                        @endif
                        <div class="news-hero-meta">
                            <span><i class="fa fa-landmark"></i> {{ $news->temple->name ?? 'Sri Mandir' }}</span>
                            <span><i class="fa fa-calendar"></i> {{ $news->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="news-hero-no-image">
                    <div class="news-hero-icon">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                    <div>
                        <span class="news-category-badge">Sri Mandir News</span>
                        <h1>{{ $news->title }}</h1>
                        @if($news->short_description)
                            <p>{{ $news->short_description }}</p>
                        @endif
                        <div class="news-hero-meta">
                            <span><i class="fa fa-landmark"></i> {{ $news->temple->name ?? 'Sri Mandir' }}</span>
                            <span><i class="fa fa-calendar"></i> {{ $news->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="news-detail-grid">
            <div class="news-main-column">
                <div class="news-detail-card">
                    <div class="news-card-header">
                        <div>
                            <span class="news-small-label">NEWS ARTICLE</span>
                            <h2>News Details</h2>
                        </div>
                        <div class="news-header-icon">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                    </div>
                    <div class="news-description">
                        @if($news->description)
                            {!! $news->description !!}
                        @else
                            <p class="news-empty">News description is not available.</p>
                        @endif
                    </div>
                </div>

                @if($news->artical_img)
                    <div class="news-detail-card">
                        <div class="news-card-header">
                            <div>
                                <span class="news-small-label">ARTICLE IMAGE</span>
                                <h2>News Gallery</h2>
                            </div>
                            <div class="news-header-icon">
                                <i class="fa fa-picture-o"></i>
                            </div>
                        </div>
                        <div class="news-article-image">
                            <img src="{{ asset('storage/' . $news->artical_img) }}" alt="{{ $news->title }}">
                        </div>
                    </div>
                @endif
            </div>

            <div class="news-sidebar">
                <div class="news-info-card">
                    <div class="news-info-heading">
                        <i class="fa fa-info-circle"></i>
                        News Information
                    </div>
                    <div class="news-info-list">
                        <div class="news-info-item">
                            <div class="news-info-icon">
                                <i class="fa fa-landmark"></i>
                            </div>
                            <div>
                                <span>Temple</span>
                                <strong>{{ $news->temple->name ?? 'Sri Mandir' }}</strong>
                            </div>
                        </div>
                        <div class="news-info-item">
                            <div class="news-info-icon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div>
                                <span>Published On</span>
                                <strong>{{ $news->created_at->format('d M Y') }}</strong>
                            </div>
                        </div>
                        @if($news->updated_at && $news->updated_at->ne($news->created_at))
                            <div class="news-info-item">
                                <div class="news-info-icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div>
                                    <span>Last Updated</span>
                                    <strong>{{ $news->updated_at->format('d M Y') }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="news-help-card">
                    <div class="news-help-icon">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <h4>Stay Connected with Sri Mandir</h4>
                    <p>Explore the latest temple news, spiritual updates and announcements from Sri Mandir.</p>
                    <a href="{{ route('frontend.news') }}">
                        Browse All News
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        @if($relatedNews->count())
            <div class="news-related-section">
                <div class="news-related-heading">
                    <div>
                        <span class="news-small-label">EXPLORE MORE</span>
                        <h2><i class="fa fa-newspaper-o"></i> Related News</h2>
                    </div>
                    <a href="{{ route('frontend.news') }}">
                        View All
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <div class="news-related-slider owl-carousel">
                    @foreach($relatedNews as $related)
                        <div class="news-related-slide">
                            <div class="news-related-card">
                                <a href="{{ route('frontend.news.show', $related->id) }}" class="news-related-image">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}">
                                    @else
                                        <div class="news-related-placeholder">
                                            <i class="fa fa-newspaper-o"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="news-related-body">
                                    <span class="news-related-type">
                                        <i class="fa fa-calendar"></i>
                                        {{ $related->created_at->format('d M Y') }}
                                    </span>
                                    <h3>
                                        <a href="{{ route('frontend.news.show', $related->id) }}">{{ $related->title }}</a>
                                    </h3>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($related->short_description ?: $related->description), 100) }}</p>
                                    <div class="news-related-footer">
                                        <span><i class="fa fa-landmark"></i> {{ $related->temple->name ?? 'Sri Mandir' }}</span>
                                        <a href="{{ route('frontend.news.show', $related->id) }}">
                                            Read More
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
</section>
@endsection

@push('styles')
<style>
.news-detail-page{background:#fffaf4;padding:35px 0 70px}
.news-breadcrumb{margin-bottom:20px}
.news-breadcrumb a{display:inline-flex;align-items:center;gap:8px;color:#a9430b;font-size:14px;font-weight:600;text-decoration:none}
.news-breadcrumb a:hover{color:#702d0b}
.news-hero-card{overflow:hidden;border-radius:18px;background:#fff;box-shadow:0 10px 35px rgba(80,38,10,.08);margin-bottom:30px}
.news-hero-image{position:relative;height:390px;overflow:hidden}
.news-hero-image>img{width:100%;height:100%;object-fit:cover}
.news-hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(38,18,7,.85),rgba(38,18,7,.45) 55%,rgba(38,18,7,.08))}
.news-hero-content{position:absolute;left:55px;right:35px;bottom:40px;max-width:780px;color:#fff}
.news-hero-content h1,.news-hero-no-image h1{margin:15px 0 12px;color:#fff;font-size:38px;line-height:1.25;font-weight:800;overflow-wrap:anywhere}
.news-hero-content p,.news-hero-no-image p{margin:0;color:rgba(255,255,255,.9);font-size:15px;line-height:1.7}
.news-category-badge{display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:30px;background:#fff0df;color:#a9430b;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
.news-hero-meta{display:flex;flex-wrap:wrap;gap:20px;margin-top:18px}
.news-hero-meta span{display:inline-flex;align-items:center;gap:7px;color:#fff;font-size:13px;font-weight:600}
.news-hero-no-image{min-height:270px;display:flex;align-items:center;gap:30px;padding:45px;background:linear-gradient(135deg,#8d3209,#d65b16)}
.news-hero-icon{width:100px;height:100px;flex:0 0 100px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:rgba(255,255,255,.15);color:#fff;font-size:48px}
.news-detail-grid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:30px;align-items:start}
.news-main-column{min-width:0}
.news-detail-card,.news-info-card,.news-help-card{background:#fff;border:1px solid #f0e2d3;border-radius:16px;box-shadow:0 7px 25px rgba(85,42,15,.055)}
.news-detail-card{padding:30px;margin-bottom:25px}
.news-card-header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding-bottom:18px;margin-bottom:25px;border-bottom:1px solid #f0e7df}
.news-small-label{display:block;margin-bottom:5px;color:#c65a18;font-size:11px;font-weight:800;letter-spacing:1.2px}
.news-card-header h2,.news-related-heading h2{margin:0;color:#4f2818;font-size:24px;font-weight:750}
.news-header-icon{width:42px;height:42px;display:flex;align-items:center;justify-content:center;flex:0 0 42px;border-radius:50%;background:#fff1e3;color:#c94d0b;font-size:16px}
.news-description{color:#575757;font-size:15px;line-height:1.9;overflow-wrap:anywhere}
.news-description p{margin-bottom:16px}
.news-description h1,.news-description h2,.news-description h3,.news-description h4{color:#542916;margin-top:25px;margin-bottom:12px}
.news-description ul,.news-description ol{padding-left:25px;margin-bottom:18px}
.news-description img,.news-description video,.news-description iframe{max-width:100%;border-radius:10px}
.news-description table{width:100%;max-width:100%;border-collapse:collapse;margin:20px 0}
.news-description table td,.news-description table th{padding:10px;border:1px solid #eadfd5}
.news-empty{color:#999}
.news-article-image{overflow:hidden;border-radius:12px;background:#faf6f1;text-align:center}
.news-article-image img{display:block;width:100%;max-height:500px;object-fit:contain}
.news-sidebar{position:sticky;top:20px;min-width:0}
.news-info-card{padding:25px;margin-bottom:20px}
.news-info-heading{display:flex;align-items:center;gap:9px;padding-bottom:17px;border-bottom:1px solid #eee5dd;color:#572a17;font-size:17px;font-weight:750}
.news-info-heading i{color:#c94d0b}
.news-info-list{margin-top:5px}
.news-info-item{display:flex;align-items:center;gap:12px;padding:15px 0;border-bottom:1px solid #f1ebe5}
.news-info-icon{width:36px;height:36px;display:flex;align-items:center;justify-content:center;flex:0 0 36px;border-radius:9px;background:#fff4e8;color:#c94d0b;font-size:13px}
.news-info-item>div:last-child{min-width:0}
.news-info-item span{display:block;color:#888;font-size:11px;margin-bottom:3px}
.news-info-item strong{display:block;color:#4d4d4d;font-size:13px;overflow-wrap:anywhere}
.news-help-card{padding:25px;text-align:center;background:linear-gradient(145deg,#fff7ed,#fff)}
.news-help-icon{width:55px;height:55px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border-radius:50%;background:#fff0df;color:#c94d0b;font-size:23px}
.news-help-card h4{margin:0 0 9px;color:#512b1b;font-size:17px;font-weight:750}
.news-help-card p{margin:0 0 17px;color:#777;font-size:13px;line-height:1.7}
.news-help-card a{color:#c94d0b;font-size:13px;font-weight:700;text-decoration:none}
.news-help-card a i{margin-left:5px}
.news-related-section{margin-top:45px}
.news-related-heading{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:22px}
.news-related-heading h2 i{color:#c94d0b;font-size:21px;margin-right:5px}
.news-related-heading>a{color:#c94d0b;font-size:13px;font-weight:700;text-decoration:none}
.news-related-heading>a i{margin-left:5px}
.news-related-slide{padding:5px 10px 15px}
.news-related-card{height:100%;overflow:hidden;background:#fff;border:1px solid #f0e2d3;border-radius:14px;box-shadow:0 5px 20px rgba(85,42,15,.05);transition:all .25s ease}
.news-related-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(85,42,15,.1)}
.news-related-image{display:block;height:190px;overflow:hidden;background:#fff3e6}
.news-related-image img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
.news-related-card:hover .news-related-image img{transform:scale(1.04)}
.news-related-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#c45a16;font-size:45px}
.news-related-body{padding:18px}
.news-related-type{display:inline-block;padding:5px 9px;border-radius:20px;background:#fff2e4;color:#b04a0e;font-size:10px;font-weight:750;text-transform:uppercase}
.news-related-body h3{margin:12px 0 8px;font-size:17px;line-height:1.4}
.news-related-body h3 a{color:#512b1b;text-decoration:none}
.news-related-body h3 a:hover{color:#c94d0b}
.news-related-body p{min-height:48px;margin-bottom:15px;color:#777;font-size:12px;line-height:1.7}
.news-related-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:13px;border-top:1px solid #eee7df}
.news-related-footer span{color:#777;font-size:11px}
.news-related-footer a{color:#c94d0b;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap}
.news-related-footer a i{margin-left:4px}
.news-related-slider .owl-nav{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}
.news-related-slider .owl-nav button{width:38px;height:38px;border:1px solid #f0d6c2!important;border-radius:50%;background:#fff!important;color:#c94d0b!important;font-size:20px!important;line-height:1!important;transition:all .25s ease}
.news-related-slider .owl-nav button:hover{background:#c94d0b!important;color:#fff!important}
.news-related-slider .owl-dots{text-align:center;margin-top:12px}
.news-related-slider .owl-dots .owl-dot span{display:block;width:8px;height:8px;margin:5px;border-radius:50%;background:#e8cdb8}
.news-related-slider .owl-dots .owl-dot.active span{width:22px;border-radius:10px;background:#c94d0b}
@media(max-width:991px){.news-detail-grid{grid-template-columns:1fr}.news-sidebar{position:static}}
@media(max-width:767px){.news-detail-page{padding:25px 0 50px}.news-hero-image{height:330px}.news-hero-content{left:25px;right:25px;bottom:25px}.news-hero-content h1,.news-hero-no-image h1{font-size:27px}.news-hero-content p,.news-hero-no-image p{font-size:13px}.news-hero-no-image{min-height:250px;padding:30px;gap:20px;align-items:flex-start;flex-direction:column}.news-hero-icon{width:65px;height:65px;flex-basis:65px;font-size:30px}.news-detail-card{padding:20px}.news-card-header h2,.news-related-heading h2{font-size:21px}.news-related-heading{align-items:flex-start;flex-direction:column}.news-related-section{margin-top:30px}.news-related-body p{min-height:0}}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    var relatedSlider = $('.news-related-slider');

    if (relatedSlider.length && !relatedSlider.hasClass('owl-loaded')) {
        relatedSlider.owlCarousel({
            loop: {{ $relatedNews->count() > 3 ? 'true' : 'false' }},
            margin: 15,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplaySpeed: 800,
            smartSpeed: 800,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    }
});
</script>
@endpush