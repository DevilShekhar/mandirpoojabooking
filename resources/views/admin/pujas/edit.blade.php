@can('pujas.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Puja')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Edit Puja</h4>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('pujas.update', $puja->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label>Temple <span class="text-danger">*</span></label>
                                    <select name="temple_id" class="form-control @error('temple_id') is-invalid @enderror">
                                        <option value="">Select Temple</option>

                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id', $puja->temple_id) == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Deity <span class="text-danger">*</span></label>
                                    <select name="deity_id" class="form-control @error('deity_id') is-invalid @enderror">
                                        <option value="">Select Deity</option>

                                        @foreach($deities as $deity)
                                            <option value="{{ $deity->id }}"
                                                {{ old('deity_id', $puja->deity_id) == $deity->id ? 'selected' : '' }}>
                                                {{ $deity->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('deity_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Puja Category <span class="text-danger">*</span></label>
                                    <select name="puja_category_id" class="form-control @error('puja_category_id') is-invalid @enderror">
                                        <option value="">Select Puja Category</option>

                                        @foreach($pujaCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('puja_category_id', $puja->puja_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('puja_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Puja Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           value="{{ old('name', $puja->name) }}"
                                           class="form-control @error('name') is-invalid @enderror">

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Puja Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="puja_code"
                                           value="{{ old('puja_code', $puja->puja_code) }}"
                                           class="form-control @error('puja_code') is-invalid @enderror">

                                    @error('puja_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Duration</label>
                                    <input type="text"
                                           name="duration"
                                           value="{{ old('duration', $puja->duration) }}"
                                           class="form-control @error('duration') is-invalid @enderror"
                                           placeholder="Example: 60 Minutes">

                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Change Image</label>
                                    <input type="file"
                                           name="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept="image/*">

                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if($puja->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $puja->image) }}"
                                                 width="100"
                                                 height="100"
                                                 style="object-fit: cover; border-radius: 5px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Change Banner Image</label>
                                    <input type="file"
                                           name="banner_image"
                                           class="form-control @error('banner_image') is-invalid @enderror"
                                           accept="image/*">

                                    @error('banner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if($puja->banner_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $puja->banner_image) }}"
                                                 width="200"
                                                 height="80"
                                                 style="object-fit: cover; border-radius: 5px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Description</label>
                                    <textarea name="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="4">{{ old('description', $puja->description) }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Benefits</label>
                                    <textarea name="benefits"
                                              class="form-control @error('benefits') is-invalid @enderror"
                                              rows="4">{{ old('benefits', $puja->benefits) }}</textarea>

                                    @error('benefits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Ritual Details</label>
                                    <textarea name="ritual_details"
                                              class="form-control @error('ritual_details') is-invalid @enderror"
                                              rows="4">{{ old('ritual_details', $puja->ritual_details) }}</textarea>

                                    @error('ritual_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Samagri Details</label>
                                    <textarea name="samagri_details"
                                              class="form-control @error('samagri_details') is-invalid @enderror"
                                              rows="4">{{ old('samagri_details', $puja->samagri_details) }}</textarea>

                                    @error('samagri_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Online Puja</label>
                                    <select name="is_online" class="form-control">
                                        <option value="1" {{ old('is_online', $puja->is_online) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('is_online', $puja->is_online) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Offline Puja</label>
                                    <select name="is_offline" class="form-control">
                                        <option value="1" {{ old('is_offline', $puja->is_offline) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('is_offline', $puja->is_offline) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', $puja->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $puja->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                            </div>

                            <div class="text-right">
                                <a href="{{ route('pujas.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Update Puja
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
