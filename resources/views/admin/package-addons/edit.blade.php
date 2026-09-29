@can('package-addons.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Package Addon')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Edit Package Addon</h4>

                        <div class="card-header-action">
                            <a href="{{ route('package-addons.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('package-addons.update', $packageAddon->id) }}"
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
                                Package Addon Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>
                                        Puja Package <span class="text-danger">*</span>
                                    </label>

                                    <select name="puja_package_id"
                                            class="form-control @error('puja_package_id') is-invalid @enderror"
                                            required>

                                        <option value="">Select Package</option>

                                        @foreach($pujaPackages as $package)

                                            <option value="{{ $package->id }}"
                                                {{ old('puja_package_id', $packageAddon->puja_package_id) == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('puja_package_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Addon Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $packageAddon->name) }}"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Price <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="price"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $packageAddon->price) }}"
                                           required>

                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Maximum Quantity <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="max_quantity"
                                           min="1"
                                           class="form-control @error('max_quantity') is-invalid @enderror"
                                           value="{{ old('max_quantity', $packageAddon->max_quantity) }}"
                                           required>

                                    @error('max_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">

                                    <label>Current Image</label>

                                    <div class="mb-2">

                                        @if($packageAddon->image)

                                            <img src="{{ asset('storage/' . $packageAddon->image) }}"
                                                 width="100"
                                                 height="100"
                                                 style="object-fit: cover; border-radius: 5px;">

                                        @else

                                            <span>No image</span>

                                        @endif

                                    </div>

                                    <label>Change Image</label>

                                    <input type="file"
                                           name="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">

                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required>

                                        <option value="1"
                                            {{ old('status', $packageAddon->status) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status', $packageAddon->status) == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-12">

                                    <label>Description</label>

                                    <textarea name="description"
                                              rows="5"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Enter addon description">{{ old('description', $packageAddon->description) }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('package-addons.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Addon
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
