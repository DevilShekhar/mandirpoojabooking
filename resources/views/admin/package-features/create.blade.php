@extends('admin.layouts.app')

@section('title', 'Create Package Feature')

@section('content')

<section class="section">
    <div class="section-body">


    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4>Create Package Feature</h4>

                    <div class="card-header-action">
                        <a href="{{ route('package-features.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('package-features.store') }}">
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
                            Feature Information
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">

                                <label>
                                    Puja Package <span class="text-danger">*</span>
                                </label>

                                <select name="puja_package_id"
                                        class="form-control @error('puja_package_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Puja Package</option>

                                    @foreach($pujaPackages as $pujaPackage)
                                        <option value="{{ $pujaPackage->id }}"
                                            {{ old('puja_package_id') == $pujaPackage->id ? 'selected' : '' }}>
                                            {{ $pujaPackage->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('puja_package_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="form-group col-md-6">

                                <label>
                                    Feature Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="feature_name"
                                       class="form-control @error('feature_name') is-invalid @enderror"
                                       value="{{ old('feature_name') }}"
                                       placeholder="e.g. Prasad Delivery"
                                       required>

                                @error('feature_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="form-group col-md-12">

                                <label>Feature Value</label>

                                <textarea name="feature_value"
                                          rows="4"
                                          class="form-control @error('feature_value') is-invalid @enderror"
                                          placeholder="Enter feature details">{{ old('feature_value') }}</textarea>

                                @error('feature_value')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="form-group col-md-6">

                                <label>Sort Order</label>

                                <input type="number"
                                       name="sort_order"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', 0) }}"
                                       min="0">

                                @error('sort_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a href="{{ route('package-features.index') }}"
                           class="btn btn-secondary mr-1">
                            Cancel
                        </a>

                        <button type="reset" class="btn btn-light mr-1">
                            Reset
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Create Feature
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</div>


</section>

@endsection
