@can('news.show')
@extends('admin.layouts.app')

@section('title', 'News Details')

@section('content')
<div class="section">
    <div class="section-header news-page-header">
        <div class="news-heading">
            <div class="news-heading-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <h1>News Details</h1>
                <div class="news-breadcrumb">
                    <span>Dashboard</span>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ route('my-news.index') }}">News</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Details</span>
                </div>
            </div>
        </div>

        <div class="news-header-actions">
            <a href="{{ route('my-news.index') }}" class="btn btn-light news-back-btn">
                <i class="fas fa-arrow-left"></i> Back to News
            </a>
            <a href="{{ route('my-news.edit', $my_news->id) }}" class="btn btn-primary">
                <i class="fas fa-pen"></i> Edit News
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="news-main-card">

                    @if($my_news->image)
                        <div class="news-cover">
                            <img src="{{ asset('storage/' . $my_news->image) }}" alt="{{ $my_news->title }}">
                            <span class="image-label">
                                <i class="fas fa-image"></i> News Image
                            </span>
                        </div>
                    @else
                        <div class="news-cover news-cover-empty">
                            <i class="fas fa-newspaper"></i>
                            <span>No News Image Available</span>
                        </div>
                    @endif

                    <div class="news-content">
                        <div class="news-status-label {{ $my_news->status == 1 ? 'active' : 'inactive' }}">
                            <i class="fas fa-{{ $my_news->status == 1 ? 'check' : 'times' }}"></i>
                            {{ $my_news->status == 1 ? 'ACTIVE' : 'INACTIVE' }}
                        </div>

                        <h2 class="news-title">{{ $my_news->title }}</h2>

                        <div class="news-meta">
                            <div>
                                <i class="fas fa-landmark"></i>
                                <span>{{ $my_news->temple->name ?? 'N/A' }}</span>
                            </div>
                            <span class="meta-divider"></span>
                            <div>
                                <i class="far fa-calendar-alt"></i>
                                <span>{{ $my_news->created_at ? $my_news->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="news-divider"></div>

                        @if($my_news->short_description)
                            <div class="description-card short-description-card">
                                <div class="description-icon blue-icon">
                                    <i class="fas fa-align-left"></i>
                                </div>
                                <div class="description-content">
                                    <h4>Short Description</h4>
                                    <p>{{ $my_news->short_description }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="description-card full-description-card">
                            <div class="description-icon purple-icon">
                                <i class="far fa-file-alt"></i>
                            </div>
                            <div class="description-content">
                                <h4>Full Description</h4>

                                @if($my_news->description)
                                    <div class="news-description">
                                        {!! $my_news->description !!}
                                    </div>
                                @else
                                    <p class="text-muted">No full description available.</p>
                                @endif
                            </div>

                            @if($my_news->artical_img)
                                <div class="article-image-wrapper">
                                    <img src="{{ asset('storage/' . $my_news->artical_img) }}"
                                         alt="Article Image"
                                         class="article-image">
                                    <span class="image-label article-image-label">
                                        <i class="fas fa-images"></i> Article Image
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="news-sidebar">

                    <div class="sidebar-card status-card">
                        <h4><span class="sidebar-accent"></span> News Status</h4>
                        <div class="status-pill {{ $my_news->status == 1 ? 'status-active' : 'status-inactive' }}">
                            <span></span>
                            {{ $my_news->status == 1 ? 'Active' : 'Inactive' }}
                        </div>
                        <p>
                            {{ $my_news->status == 1
                                ? 'This news is currently published and visible to users.'
                                : 'This news is currently inactive and not published.' }}
                        </p>
                    </div>

                    <div class="sidebar-card information-card">
                        <div class="sidebar-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div>
                            <span class="sidebar-label">Temple Name</span>
                            <h5>{{ $my_news->temple->name ?? 'N/A' }}</h5>
                            @if(!empty($my_news->temple->address))
                                <p>{{ $my_news->temple->address }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-card information-card">
                        <div class="sidebar-icon">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <span class="sidebar-label">Created At</span>
                            <h5>{{ $my_news->created_at ? $my_news->created_at->format('d M Y, h:i A') : 'N/A' }}</h5>
                        </div>
                    </div>

                    <div class="sidebar-card information-card">
                        <div class="sidebar-icon">
                            <i class="far fa-calendar-check"></i>
                        </div>
                        <div>
                            <span class="sidebar-label">Last Updated</span>
                            <h5>{{ $my_news->updated_at ? $my_news->updated_at->format('d M Y, h:i A') : 'N/A' }}</h5>
                        </div>
                    </div>

                    <div class="sidebar-card information-card user-information-card">
                        <div class="sidebar-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <span class="sidebar-label">Created By</span>
                            <h5>{{ $my_news->createdBy->name ?? 'N/A' }}</h5>
                            <p>News Creator</p>
                        </div>
                    </div>

                    <div class="sidebar-card information-card user-information-card">
                        <div class="sidebar-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <span class="sidebar-label">Last Updated By</span>
                            <h5>{{ $my_news->updatedBy->name ?? 'N/A' }}</h5>
                            <p>Last Editor</p>
                        </div>
                    </div>

                    <div class="sidebar-card quick-actions-card">
                        <h4><i class="fas fa-bolt"></i> Quick Actions</h4>

                        <div class="quick-action-buttons">
                            <a href="{{ route('my-news.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>

                            <a href="{{ route('my-news.edit', $my_news->id) }}" class="btn btn-primary">
                                <i class="fas fa-pen"></i> Edit News
                            </a>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.news-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.news-heading {
    display: flex;
    align-items: center;
    gap: 15px;
}

.news-heading-icon {
    width: 58px;
    height: 58px;
    border: 3px solid #263f83;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #263f83;
    font-size: 30px;
}

.news-heading h1 {
    font-size: 25px;
    font-weight: 700;
    margin-bottom: 4px;
    color: #101b3c;
}

.news-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 13px;
    color: #7b8499;
}

.news-breadcrumb a {
    color: #52658d;
}

.news-breadcrumb i {
    font-size: 9px;
}

.news-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.news-header-actions .btn {
    padding: 10px 17px;
    border-radius: 7px;
    font-weight: 600;
}

.news-back-btn {
    border: 1px solid #dce2ec;
    color: #26334f;
}

.news-main-card,
.sidebar-card {
    background: #fff;
    border: 1px solid #e5eaf2;
    border-radius: 11px;
    overflow: hidden;
    margin-bottom: 15px;
}

.news-cover {
    height: 225px;
    position: relative;
    overflow: hidden;
    background: #edf1f7;
}

.news-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-cover-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #8792a8;
    gap: 10px;
    font-size: 15px;
}

.news-cover-empty i {
    font-size: 45px;
}

.image-label {
    position: absolute;
    top: 14px;
    left: 14px;
    background: rgba(20, 28, 49, .82);
    color: #fff;
    padding: 8px 13px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.news-content {
    padding: 22px;
}

.news-status-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 12px;
}

.news-status-label.active {
    background: #d7f7e6;
    color: #079653;
}

.news-status-label.inactive {
    background: #ffe1e5;
    color: #dc3545;
}

.news-title {
    color: #101a3a;
    font-size: 25px;
    line-height: 1.4;
    font-weight: 750;
    margin-bottom: 12px;
}

.news-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    color: #46536e;
    font-size: 14px;
}

.news-meta div {
    display: flex;
    align-items: center;
    gap: 9px;
}

.news-meta i {
    color: #294b91;
    font-size: 17px;
}

.meta-divider {
    height: 22px;
    width: 1px;
    background: #cbd3e0;
}

.news-divider {
    border-top: 1px solid #e8edf4;
    margin: 20px 0 15px;
}

.description-card {
    border: 1px solid #e4eaf2;
    border-radius: 9px;
    padding: 17px;
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.short-description-card {
    background: #f0f7ff;
    border-color: #d9eaff;
}

.description-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 19px;
}

.blue-icon {
    background: #2584ed;
}

.purple-icon {
    background: #bd32e9;
}

.description-content {
    flex: 1;
    min-width: 0;
}

.description-content h4 {
    color: #111d3d;
    font-size: 17px;
    font-weight: 700;
    margin: 3px 0 7px;
}

.description-content p {
    color: #536079;
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 0;
}

.news-description {
    color: #536079;
    font-size: 14px;
    line-height: 1.8;
    width: 100%;
    overflow-wrap: anywhere;
}

.news-description img {
    max-width: 100%;
    height: auto;
}

.news-description table {
    max-width: 100%;
}

.article-image-wrapper {
    position: relative;
    width: 100%;
    margin-top: 12px;
}

.article-image {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
    border-radius: 9px;
    display: block;
}

.article-image-label {
    top: 12px;
    left: 12px;
}

.news-sidebar {
    position: relative;
}

.sidebar-card {
    padding: 17px;
}

.status-card {
    background: #f0fbf5;
    border-color: #d3f2df;
}

.status-card h4,
.quick-actions-card h4 {
    color: #172341;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.sidebar-accent {
    width: 5px;
    height: 21px;
    border-radius: 5px;
    background: #12a65b;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 6px 13px;
    border-radius: 25px;
    font-weight: 700;
    margin: 0 0 8px 20px;
}

.status-pill span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.status-active {
    background: #d4f7e2;
    color: #087d3d;
}

.status-active span {
    background: #079c4d;
}

.status-inactive {
    background: #ffe0e4;
    color: #c82333;
}

.status-inactive span {
    background: #dc3545;
}

.status-card p {
    color: #647087;
    font-size: 13px;
    line-height: 1.6;
    margin: 0 0 0 20px;
}

.information-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.sidebar-icon {
    width: 45px;
    min-width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #294a91;
    font-size: 24px;
}

.sidebar-label {
    color: #69758c;
    font-size: 13px;
    display: block;
    margin-bottom: 4px;
}

.information-card h5 {
    color: #172342;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.5;
    margin-bottom: 3px;
    overflow-wrap: anywhere;
}

.information-card p {
    color: #8490a5;
    font-size: 12px;
    margin-bottom: 0;
}

.quick-actions-card {
    background: #fffbf2;
    border-color: #f7ebca;
}

.quick-actions-card h4 {
    color: #60431c;
}

.quick-actions-card h4 i {
    font-size: 18px;
}

.quick-action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.quick-action-buttons .btn {
    flex: 1;
    min-width: 85px;
    font-size: 12px;
    font-weight: 600;
    padding: 10px 8px;
}

.deactivate-form {
    margin-top: 10px;
}

.deactivate-form .btn {
    border-radius: 6px;
    font-weight: 600;
    padding: 10px;
}

@media (max-width: 767px) {
    .news-page-header {
        align-items: flex-start;
    }

    .news-heading h1 {
        font-size: 21px;
    }

    .news-header-actions {
        width: 100%;
    }

    .news-header-actions .btn {
        flex: 1;
    }

    .news-cover {
        height: 180px;
    }

    .news-content {
        padding: 15px;
    }

    .news-title {
        font-size: 20px;
    }

    .news-meta {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .meta-divider {
        display: none;
    }

    .description-card {
        padding: 13px;
    }
}
</style>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan