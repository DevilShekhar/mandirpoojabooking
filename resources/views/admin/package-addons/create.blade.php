@can('package-addons.create')
@extends('admin.layouts.app')

@section('title', 'Create Package Addon')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Create Package Addon</h4>

                        <div class="card-header-action">
                            <a href="{{ route('package-addons.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('package-addons.store') }}"
                          enctype="multipart/form-data">

                        @csrf

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
                                                {{ old('puja_package_id') == $package->id ? 'selected' : '' }}>
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
                                           value="{{ old('name') }}"
                                           placeholder="Enter addon name"
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
                                           value="{{ old('price', 0) }}"
                                           placeholder="Enter price"
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
                                           value="{{ old('max_quantity', 1) }}"
                                           required>

                                    @error('max_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Image</label>

                                    <input type="file"
                                           name="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">

                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Description</label>

                                    <textarea name="description"
                                              rows="5"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Enter addon description">{{ old('description') }}</textarea>

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

                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Addon
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
