@extends('frontend.layouts.app')
@section('title', 'Latest News | Sri Mandir')
@section('meta_keyword', 'Sri Mandir News, Temple News, Religious News, Spiritual Updates')
@section('meta_description', 'Stay updated with the latest temple news, religious events, spiritual updates and announcements from Sri Mandir.')
@section('content')
<div class="news-page">
    <div class="container">
        <div class="news-section-header">
            <span class="section-subtitle">
                <i class="fa fa-book-open"></i> News & Updates
            </span>
            <h2>Sri Mandir News</h2>
            <p>
                Stay connected with the latest temple news, spiritual updates
                and religious announcements.
            </p>
        </div>
        <div class="row">
            @forelse($news as $item)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="news-card">
                        <div class="news-image">
                            <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                    <img src="{{ asset('assets/frontend/assets/images/news/news-1.jpg') }}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                            </a>
                            <div class="news-date-badge">
                                <i class="fa fa-calendar"></i>
                                {{ $item->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <a class="news-temple" href="{{ !empty($item->temple?->slug) ? route('frontend.our-temple.show', $item->temple->slug) : 'javascript:void(0)' }}">
                                    <i class="fa fa-landmark"></i>
                                    {{ $item->temple->name ?? 'Sri Mandir' }}
                                </a>
                                <span class="news-category">
                                    <i class="fa fa-clock-o"></i>
                                    News Update
                                </span>
                            </div>
                            <h3 class="news-title">
                                <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <p class="news-description">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->short_description ?: $item->description), 140) }}
                            </p>
                            <a href="{{ route('frontend.news.show', ['slug' => $item->slug]) }}" class="news-read-more">
                                <span>Read Full Story</span>
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-lg-12">
                    <div class="news-empty-state">
                        <div class="news-empty-icon">
                            <i class="fa fa-newspaper-o"></i>
                        </div>
                        <h4>No News Available</h4>
                        <p>
                            There are currently no news articles available.
                            Please check back later for the latest updates.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
        @if($news->hasPages())
            <div class="news-pagination">
                <ul class="li-pagination-box">
                    @if($news->onFirstPage())
                        <li class="disabled">
                            <span>
                                <i class="fa fa-angle-left"></i> Previous
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $news->previousPageUrl() }}">
                                <i class="fa fa-angle-left"></i> Previous
                            </a>
                        </li>
                    @endif
                    @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                        <li class="{{ $news->currentPage() == $page ? 'active' : '' }}">
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    @if($news->hasMorePages())
                        <li>
                            <a href="{{ $news->nextPageUrl() }}">
                                Next <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="disabled">
                            <span>
                                Next <i class="fa fa-angle-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection