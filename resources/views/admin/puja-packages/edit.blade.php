@can('puja-packages.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Puja Package')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                <div class="card-header">
                    <h4>Edit Puja Package</h4>

                    <div class="card-header-action">
                        <a href="{{ route('puja-packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('puja-packages.update', $pujaPackage->id) }}"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="section-title mt-0">
                            Package Information
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label>Puja <span class="text-danger">*</span></label>

                                <select name="puja_id"
                                        class="form-control @error('puja_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select Puja</option>

                                    @foreach($pujas as $puja)
                                        <option value="{{ $puja->id }}"
                                            {{ old('puja_id', $pujaPackage->puja_id) == $puja->id ? 'selected' : '' }}>
                                            {{ $puja->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('puja_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Package Name <span class="text-danger">*</span></label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $pujaPackage->name) }}"
                                       placeholder="e.g. Family Package"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Person Count <span class="text-danger">*</span></label>

                                <input type="number"
                                       name="person_count"
                                       class="form-control"
                                       value="{{ old('person_count', $pujaPackage->person_count) }}"
                                       min="1"
                                       required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Price <span class="text-danger">*</span></label>

                                <input type="number"
                                       name="price"
                                       step="0.01"
                                       class="form-control"
                                       value="{{ old('price', $pujaPackage->price) }}"
                                       required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Discount Price</label>

                                <input type="number"
                                       name="discount_price"
                                       step="0.01"
                                       class="form-control"
                                       value="{{ old('discount_price', $pujaPackage->discount_price) }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Package Image</label>

                                @if($pujaPackage->package_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $pujaPackage->package_image) }}"
                                             alt="Package Image"
                                             style="width: 100px; height: 80px; object-fit: cover; border-radius: 5px;">
                                    </div>
                                @endif

                                <input type="file"
                                       name="package_image"
                                       class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Sort Order</label>

                                <input type="number"
                                       name="sort_order"
                                       class="form-control"
                                       value="{{ old('sort_order', $pujaPackage->sort_order) }}"
                                       min="0">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Short Description</label>

                                <input type="text"
                                       name="short_description"
                                       class="form-control"
                                       value="{{ old('short_description', $pujaPackage->short_description) }}"
                                       placeholder="Short package description">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Description</label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Package description">{{ old('description', $pujaPackage->description) }}</textarea>
                            </div>

                        </div>

                        <div class="section-title">
                            Included Services
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           name="includes_sankalp"
                                           value="1"
                                           class="custom-control-input"
                                           id="includes_sankalp"
                                           {{ old('includes_sankalp', $pujaPackage->includes_sankalp) ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="includes_sankalp">
                                        Includes Sankalp
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           name="includes_prasad"
                                           value="1"
                                           class="custom-control-input"
                                           id="includes_prasad"
                                           {{ old('includes_prasad', $pujaPackage->includes_prasad) ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="includes_prasad">
                                        Includes Prasad
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           name="includes_video"
                                           value="1"
                                           class="custom-control-input"
                                           id="includes_video"
                                           {{ old('includes_video', $pujaPackage->includes_video) ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="includes_video">
                                        Includes Video
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           name="includes_certificate"
                                           value="1"
                                           class="custom-control-input"
                                           id="includes_certificate"
                                           {{ old('includes_certificate', $pujaPackage->includes_certificate) ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="includes_certificate">
                                        Includes Certificate
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="section-title">
                            Settings
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label>Status</label>

                                <select name="status" class="form-control">
                                    <option value="1"
                                        {{ old('status', $pujaPackage->status) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0"
                                        {{ old('status', $pujaPackage->status) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Featured</label>

                                <select name="is_featured" class="form-control">
                                    <option value="0"
                                        {{ old('is_featured', $pujaPackage->is_featured) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>

                                    <option value="1"
                                        {{ old('is_featured', $pujaPackage->is_featured) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a href="{{ route('puja-packages.index') }}"
                           class="btn btn-secondary mr-1">
                            Cancel
                        </a>

                        <button type="reset" class="btn btn-light mr-1">
                            Reset
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Package
                        </button>

                    </div>

                </form>

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
