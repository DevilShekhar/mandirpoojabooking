@can('home-banners.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Home Banner')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Edit Home Banner</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('home-banners.index') }}">Home Banners</a>
            </div>
            <div class="breadcrumb-item active">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Home Banner Details</h4>
            </div>

            <form action="{{ route('home-banners.update', $homeBanner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $homeBanner->title) }}"
                                placeholder="Enter banner title">

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Button Name</label>
                            <input type="text"
                                name="btn_name"
                                class="form-control @error('btn_name') is-invalid @enderror"
                                value="{{ old('btn_name', $homeBanner->btn_name) }}"
                                placeholder="e.g. Book Now">

                            @error('btn_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Button URL</label>
                            <input type="text"
                                name="btn_url"
                                class="form-control @error('btn_url') is-invalid @enderror"
                                value="{{ old('btn_url', $homeBanner->btn_url) }}"
                                placeholder="e.g. /pujas">

                            @error('btn_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', $homeBanner->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $homeBanner->status) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Banner Image</label>
                            <input type="file"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp">

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Allowed: JPG, JPEG, PNG, WEBP. Maximum size: 2MB.
                            </small>

                            @if($homeBanner->image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $homeBanner->image) }}"
                                        alt="{{ $homeBanner->title }}"
                                        style="width: 180px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </div>
                            @endif
                        </div>

                        <div class="form-group col-md-12">
                            <label>Short Description</label>
                            <textarea name="short_description"
                                rows="4"
                                class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Enter short description">{{ old('short_description', $homeBanner->short_description) }}</textarea>

                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('home-banners.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Home Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@else
    @php
        abort(403);
    @endphp
@endcan