@can('home-banners.view')
@extends('admin.layouts.app')

@section('title', 'View Home Banner')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>View Home Banner</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('home-banners.index') }}">Home Banners</a>
            </div>
            <div class="breadcrumb-item active">View</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Home Banner Details</h4>
                <div class="card-header-action">
                    <a href="{{ route('home-banners.edit', $homeBanner->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Title</label>
                        <div class="form-control">
                            {{ $homeBanner->title }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Button Name</label>
                        <div class="form-control">
                            {{ $homeBanner->btn_name ?: '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Button URL</label>
                        <div class="form-control">
                            {{ $homeBanner->btn_url ?: '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Created By</label>
                        <div class="form-control">
                            {{ $homeBanner->createdBy->name ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Updated By</label>
                        <div class="form-control">
                            {{ $homeBanner->updatedBy->name ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Created At</label>
                        <div class="form-control">
                            {{ $homeBanner->created_at ? $homeBanner->created_at->format('d M Y h:i A') : '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Updated At</label>
                        <div class="form-control">
                            {{ $homeBanner->updated_at ? $homeBanner->updated_at->format('d M Y h:i A') : '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Short Description</label>
                        <div class="form-control" style="height: auto; min-height: 100px;">
                            {{ $homeBanner->short_description ?: '-' }}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Banner Image</label>

                        @if($homeBanner->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $homeBanner->image) }}"
                                    alt="{{ $homeBanner->title }}"
                                    style="max-width: 500px; width: 100%; height: 250px; object-fit: cover; border-radius: 6px;">
                            </div>
                        @else
                            <div class="form-control">
                                No image available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('home-banners.index') }}" class="btn btn-secondary">
                    Back
                </a>
                <a href="{{ route('home-banners.edit', $homeBanner->id) }}" class="btn btn-primary">
                    Edit Home Banner
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@else
    @php
        abort(403);
    @endphp
@endcan