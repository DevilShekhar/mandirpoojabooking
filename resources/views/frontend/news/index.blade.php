@extends('frontend.layouts.app')

@section('title', 'Latest News')
@section('meta_keyword', 'Sri Mandir News, Temple News, Religious News')
@section('meta_description', 'Read the latest news, temple updates and religious announcements from Sri Mandir.')

@section('content')
<style>
.news-card {
    box-decoration-break: clone;
    -webkit-box-decoration-break: clone;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    transition: all 0.3s ease;
}
.news-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
}
.news-card .li-blog-banner img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}
.news-card .li-blog-content {
    padding: 15px !important;
}
.news-card .li-blog-heading {
    min-height: 55px;
}
.news-card .li-blog-details > p {
    min-height: 75px;
}
</style>

<div class="li-main-blog-page pt-60 pb-55">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="li-section-title capitalize mb-25">
                    <h2><span>Sri Mandir News</span></h2>
                    <p>Latest temple news, religious updates and announcements.</p>
                </div>

                <div class="row li-main-content">
                    @forelse($news as $item)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="li-blog-single-item pb-25 news-card">
                                <div class="li-blog-banner">
                                    <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}">
                                        @if($item->image)
                                            <img class="img-full" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}">
                                        @else
                                            <img class="img-full" src="{{ asset('assets/frontend/assets/images/news/news-1.jpg') }}" alt="{{ $item->title }}">
                                        @endif
                                    </a>
                                </div>

                                <div class="li-blog-content">
                                    <div class="li-blog-details">
                                        <h3 class="li-blog-heading pt-25">
                                            <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}">{{ $item->title }}</a>
                                        </h3>

                                        <div class="li-blog-meta">
                                            <a class="author" href="{{ !empty($item->temple?->slug) ? route('frontend.our-temple.show', $item->temple->slug) : 'javascript:void(0)' }}">
                                                <i class="fa fa-landmark"></i>
                                                {{ $item->temple->name ?? 'Sri Mandir' }}
                                            </a>
                                            <a class="post-time" href="javascript:void(0)">
                                                <i class="fa fa-calendar"></i>
                                                {{ $item->created_at->format('d M Y') }}
                                            </a>
                                        </div>

                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->short_description ?: $item->description), 150) }}</p>

                                        <a class="read-more" href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}">
                                            Read More...
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12">
                            <div class="text-center py-5">
                                <i class="fa fa-newspaper-o fa-3x mb-3"></i>
                                <h4>No News Available</h4>
                                <p>There are currently no news articles available.</p>
                            </div>
                        </div>
                    @endforelse

                    @if($news->hasPages())
                        <div class="col-lg-12">
                            <div class="li-paginatoin-area text-center pt-25">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul class="li-pagination-box">
                                            @if($news->onFirstPage())
                                                <li class="disabled">
                                                    <span class="Previous">Previous</span>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="Previous" href="{{ $news->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                                                <li class="{{ $news->currentPage() == $page ? 'active' : '' }}">
                                                    <a href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endforeach

                                            @if($news->hasMorePages())
                                                <li>
                                                    <a class="Next" href="{{ $news->nextPageUrl() }}">Next</a>
                                                </li>
                                            @else
                                                <li class="disabled">
                                                    <span class="Next">Next</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection