@extends('admin.layouts.app')

@section('title', 'Create City')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create City</h4>

                        <div class="card-header-action">
                            <a href="{{ route('cities.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('cities.store') }}">
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
                                City Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="country_id">
                                        Country <span class="text-danger">*</span>
                                    </label>

                                    <select name="country_id"
                                        id="country_id"
                                        class="form-control @error('country_id') is-invalid @enderror"
                                        required>

                                        <option value="">Select Country</option>

                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="state_id">
                                        State <span class="text-danger">*</span>
                                    </label>

                                    <select name="state_id"
                                        id="state_id"
                                        class="form-control @error('state_id') is-invalid @enderror"
                                        required
                                        disabled>

                                        <option value="">Select Country First</option>

                                    </select>

                                    @error('state_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="district_id">
                                        District <span class="text-danger">*</span>
                                    </label>

                                    <select name="district_id"
                                        id="district_id"
                                        class="form-control @error('district_id') is-invalid @enderror"
                                        required
                                        disabled>

                                        <option value="">Select State First</option>

                                    </select>

                                    @error('district_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name">
                                        City Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Enter City Name"
                                        required>

                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="pincode">
                                        Pincode
                                    </label>

                                    <input type="text"
                                        name="pincode"
                                        id="pincode"
                                        class="form-control @error('pincode') is-invalid @enderror"
                                        value="{{ old('pincode') }}"
                                        maxlength="10"
                                        placeholder="Enter Pincode">

                                    @error('pincode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="latitude">
                                        Latitude
                                    </label>

                                    <input type="text"
                                        name="latitude"
                                        id="latitude"
                                        class="form-control @error('latitude') is-invalid @enderror"
                                        value="{{ old('latitude') }}"
                                        placeholder="e.g. 18.5204">

                                    @error('latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="longitude">
                                        Longitude
                                    </label>

                                    <input type="text"
                                        name="longitude"
                                        id="longitude"
                                        class="form-control @error('longitude') is-invalid @enderror"
                                        value="{{ old('longitude') }}"
                                        placeholder="e.g. 73.8567">

                                    @error('longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                        id="status"
                                        class="form-control @error('status') is-invalid @enderror"
                                        required>

                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('cities.index') }}"
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
                                Create City
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

        $('#country_id').on('change', function() {

            let countryId = $(this).val();
            let stateSelect = $('#state_id');
            let districtSelect = $('#district_id');

            stateSelect.empty();
            stateSelect.append('<option value="">Select State</option>');
            stateSelect.prop('disabled', true);

            districtSelect.empty();
            districtSelect.append('<option value="">Select State First</option>');
            districtSelect.prop('disabled', true);

            if (!countryId) {
                stateSelect.empty();
                stateSelect.append('<option value="">Select Country First</option>');
                return;
            }

            $.ajax({
                url: "{{ url('cities/get-states') }}/" + countryId,
                type: "GET",
                dataType: "json",

                success: function(states) {

                    stateSelect.empty();
                    stateSelect.append('<option value="">Select State</option>');

                    if (states.length > 0) {

                        $.each(states, function(index, state) {
                            stateSelect.append(
                                '<option value="' + state.id + '">' +
                                state.name +
                                '</option>'
                            );
                        });

                        stateSelect.prop('disabled', false);

                    } else {

                        stateSelect.empty();
                        stateSelect.append(
                            '<option value="">No states found</option>'
                        );

                    }
                },

                error: function() {

                    stateSelect.empty();
                    stateSelect.append(
                        '<option value="">Unable to load states</option>'
                    );

                }
            });
        });

        $('#state_id').on('change', function() {

            let stateId = $(this).val();
            let districtSelect = $('#district_id');

            districtSelect.empty();
            districtSelect.append('<option value="">Select District</option>');
            districtSelect.prop('disabled', true);

            if (!stateId) {
                districtSelect.empty();
                districtSelect.append('<option value="">Select State First</option>');
                return;
            }

            $.ajax({
                url: "{{ url('cities/get-districts') }}/" + stateId,
                type: "GET",
                dataType: "json",

                success: function(districts) {

                    districtSelect.empty();
                    districtSelect.append(
                        '<option value="">Select District</option>'
                    );

                    if (districts.length > 0) {

                        $.each(districts, function(index, district) {
                            districtSelect.append(
                                '<option value="' + district.id + '">' +
                                district.name +
                                '</option>'
                            );
                        });

                        districtSelect.prop('disabled', false);

                    } else {

                        districtSelect.empty();
                        districtSelect.append(
                            '<option value="">No districts found</option>'
                        );

                    }
                },

                error: function() {

                    districtSelect.empty();
                    districtSelect.append(
                        '<option value="">Unable to load districts</option>'
                    );

                }
            });
        });

        @if(old('country_id'))
            $('#country_id').trigger('change');
        @endif

    });
</script>
@endpush
