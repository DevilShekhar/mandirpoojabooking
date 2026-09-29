@can('cities.edit')
@extends('admin.layouts.app')

@section('title', 'Edit City')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit City</h4>


                    <div class="card-header-action">
                        <a href="{{ route('cities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form id="city-edit-form"
                    method="POST"
                    action="{{ route('cities.update', $city->id) }}">

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

                        <div class="section-title mt-0">City Information</div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
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
                                        {{ old('country_id', $city->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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
                                        {{ old('state_id', $city->state_id) == $state->id ? 'selected' : '' }}>
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
                                    required>

                                    <option value="">Select District</option>

                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id', $city->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('district_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="name">
                                    City Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $city->name) }}"
                                    placeholder="Enter City Name"
                                    required>

                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pincode">Pincode</label>

                                <input type="text"
                                    name="pincode"
                                    id="pincode"
                                    class="form-control @error('pincode') is-invalid @enderror"
                                    value="{{ old('pincode', $city->pincode) }}"
                                    placeholder="Enter Pincode">

                                @error('pincode')
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
                                    value="{{ old('latitude', $city->latitude) }}"
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
                                    value="{{ old('longitude', $city->longitude) }}"
                                    placeholder="Enter Longitude">

                                @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="status">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status"
                                    id="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>

                                    <option value="1"
                                        {{ old('status', $city->status) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0"
                                        {{ old('status', $city->status) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            Update City
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

        let selectedState = "{{ old('state_id', $city->state_id) }}";
        let selectedDistrict = "{{ old('district_id', $city->district_id) }}";

        $('#country_id').on('change', function() {

            let countryId = $(this).val();

            $('#state_id').html('<option value="">Select State</option>');
            $('#district_id').html('<option value="">Select District</option>');

            if (!countryId) {
                $('#state_id').prop('disabled', true);
                $('#district_id').prop('disabled', true);
                return;
            }

            $('#state_id').prop('disabled', false);

            $.ajax({
                url: "{{ url('cities/get-states') }}/" + countryId,
                type: "GET",
                success: function(states) {

                    $.each(states, function(key, state) {
                        $('#state_id').append(
                            '<option value="' + state.id + '">' +
                            state.name +
                            '</option>'
                        );
                    });

                    if (selectedState) {
                        $('#state_id').val(selectedState).trigger('change');
                        selectedState = '';
                    }
                }
            });
        });

        $('#state_id').on('change', function() {

            let stateId = $(this).val();

            $('#district_id').html('<option value="">Select District</option>');

            if (!stateId) {
                $('#district_id').prop('disabled', true);
                return;
            }

            $('#district_id').prop('disabled', false);

            $.ajax({
                url: "{{ url('cities/get-districts') }}/" + stateId,
                type: "GET",
                success: function(districts) {

                    $.each(districts, function(key, district) {
                        $('#district_id').append(
                            '<option value="' + district.id + '">' +
                            district.name +
                            '</option>'
                        );
                    });

                    if (selectedDistrict) {
                        $('#district_id').val(selectedDistrict);
                        selectedDistrict = '';
                    }
                }
            });
        });

        $('#country_id').trigger('change');

    });
</script>

@if(session('success'))

<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>

@endif

@if(session('error'))

<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
</script>

@endif
@endpush
@else
    @php
        abort(404);
    @endphp
@endcan
