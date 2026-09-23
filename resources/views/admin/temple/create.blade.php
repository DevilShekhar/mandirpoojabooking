@extends('admin.layouts.app')

@section('title', 'Create Temple')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Temple</h4>


                    <div class="card-header-action">
                        <a href="{{ route('temples.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form method="POST"
                    action="{{ route('temples.store') }}"
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

                        <div class="section-title mt-0">Temple Location</div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="state_id">
                                    State <span class="text-danger">*</span>
                                </label>

                                <select name="state_id"
                                    id="state_id"
                                    class="form-control @error('state_id') is-invalid @enderror"
                                    required>

                                    <option value="">Select State</option>

                                    @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('state_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="district_id">
                                    District <span class="text-danger">*</span>
                                </label>

                                <select name="district_id"
                                    id="district_id"
                                    class="form-control @error('district_id') is-invalid @enderror"
                                    disabled
                                    required>

                                    <option value="">Select District</option>
                                </select>

                                @error('district_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="city_id">
                                    City <span class="text-danger">*</span>
                                </label>

                                <select name="city_id"
                                    id="city_id"
                                    class="form-control @error('city_id') is-invalid @enderror"
                                    disabled
                                    required>

                                    <option value="">Select City</option>
                                </select>

                                @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="section-title">Temple Information</div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="name">
                                    Temple Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Enter Temple Name"
                                    required>

                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="temple_code">
                                    Temple Code <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="temple_code"
                                    id="temple_code"
                                    class="form-control @error('temple_code') is-invalid @enderror"
                                    value="{{ old('temple_code') }}"
                                    placeholder="Enter Temple Code"
                                    required>

                                @error('temple_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-12">
                                <label for="address">
                                    Address <span class="text-danger">*</span>
                                </label>

                                <textarea name="address"
                                    id="address"
                                    rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Enter Temple Address"
                                    required>{{ old('address') }}</textarea>

                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="latitude">Latitude</label>

                                <input type="text"
                                    name="latitude"
                                    id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}"
                                    placeholder="Enter Latitude">

                                @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="longitude">Longitude</label>

                                <input type="text"
                                    name="longitude"
                                    id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}"
                                    placeholder="Enter Longitude">

                                @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="section-title">Temple Images</div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="main_image">Main Image</label>

                                <input type="file"
                                    name="main_image"
                                    id="main_image"
                                    class="form-control @error('main_image') is-invalid @enderror"
                                    accept="image/*">

                                @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="banner_image">Banner Image</label>

                                <input type="file"
                                    name="banner_image"
                                    id="banner_image"
                                    class="form-control @error('banner_image') is-invalid @enderror"
                                    accept="image/*">

                                @error('banner_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="section-title">Temple Status</div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="is_verified">
                                    Verification <span class="text-danger">*</span>
                                </label>

                                <select name="is_verified"
                                    id="is_verified"
                                    class="form-control"
                                    required>

                                    <option value="0"
                                        {{ old('is_verified', 0) == 0 ? 'selected' : '' }}>
                                        Not Verified
                                    </option>

                                    <option value="1"
                                        {{ old('is_verified') == 1 ? 'selected' : '' }}>
                                        Verified
                                    </option>

                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status"
                                    id="status"
                                    class="form-control"
                                    required>

                                    <option value="1"
                                        {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0"
                                        {{ old('status') == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('temples.index') }}"
                            class="btn btn-secondary mr-1">
                            Cancel
                        </a>

                        <button type="reset"
                            class="btn btn-light mr-1">
                            Reset
                        </button>

                        <button type="submit"
                            class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Create Temple
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


</section>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {

        $('#state_id').on('change', function() {

            let stateId = $(this).val();

            $('#district_id').html('<option value="">Select District</option>');
            $('#city_id').html('<option value="">Select City</option>');

            $('#district_id').prop('disabled', true);
            $('#city_id').prop('disabled', true);

            if (!stateId) {
                return;
            }

            $.ajax({
                url: "{{ url('temples/get-districts') }}/" + stateId,
                type: "GET",
                success: function(districts) {

                    $.each(districts, function(key, district) {
                        $('#district_id').append(
                            '<option value="' + district.id + '">' +
                            district.name +
                            '</option>'
                        );
                    });

                    $('#district_id').prop('disabled', false);
                }
            });
        });

        $('#district_id').on('change', function() {

            let districtId = $(this).val();

            $('#city_id').html('<option value="">Select City</option>');
            $('#city_id').prop('disabled', true);

            if (!districtId) {
                return;
            }

            $.ajax({
                url: "{{ url('temples/get-cities') }}/" + districtId,
                type: "GET",
                success: function(cities) {

                    $.each(cities, function(key, city) {
                        $('#city_id').append(
                            '<option value="' + city.id + '">' +
                            city.name +
                            '</option>'
                        );
                    });

                    $('#city_id').prop('disabled', false);
                }
            });
        });

        @if(old('state_id'))
        $('#state_id').trigger('change');

        setTimeout(function() {
            $('#district_id').val("{{ old('district_id') }}").trigger('change');

            setTimeout(function() {
                $('#city_id').val("{{ old('city_id') }}");
            }, 500);

        }, 500);
        @endif

    });
</script>

@endpush
