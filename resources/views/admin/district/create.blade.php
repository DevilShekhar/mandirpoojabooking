@extends('admin.layouts.app')

@section('title', 'Create District')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create District</h4>

                        <div class="card-header-action">
                            <a href="{{ route('districts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('districts.store') }}">
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
                                District Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="country_id">
                                        Country <span class="text-danger">*</span>
                                    </label>

                                    <select name="country_id"
                                        id="country_id"
                                        class="form-control"
                                        required>

                                        <option value="">Select Country</option>

                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="state_id">
                                        State <span class="text-danger">*</span>
                                    </label>

                                    <select name="state_id"
                                        id="state_id"
                                        class="form-control"
                                        required
                                        disabled>

                                        <option value="">Select Country First</option>

                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name">
                                        District Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Enter District Name"
                                        required>

                                    @error('name')
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
                                        class="form-control"
                                        required>

                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('districts.index') }}"
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
                                Create District
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

            stateSelect.empty();
            stateSelect.append('<option value="">Select State</option>');
            stateSelect.prop('disabled', true);

            if (!countryId) {
                stateSelect.empty();
                stateSelect.append('<option value="">Select Country First</option>');
                return;
            }

            $.ajax({
                url: "{{ url('districts/get-states') }}/" + countryId,
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

        @if(old('country_id'))
            $('#country_id').trigger('change');
        @endif

    });
</script>
@endpush
