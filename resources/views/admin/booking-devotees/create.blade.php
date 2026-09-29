@can('booking-devotees.create')

@extends('admin.layouts.app')

@section('title', 'Add Booking Devotee')

@section('content')

<section class="section">

    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Add Booking Devotee</h4>

                        <div class="card-header-action">
                            <a href="{{ route('booking-devotees.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                        </div>

                    </div>

                    <form action="{{ route('booking-devotees.store') }}"
                          method="POST">

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
                                Booking Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-12">

                                    <label>
                                        Puja Booking
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="booking_id"
                                            class="form-control @error('booking_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select Booking
                                        </option>

                                        @foreach($bookings as $booking)

                                            <option value="{{ $booking->id }}"
                                                {{ old('booking_id') == $booking->id ? 'selected' : '' }}>

                                                {{ $booking->booking_number ?? 'Booking #' . $booking->id }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('booking_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                            <div class="section-title">
                                Devotee Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label>
                                        Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="Enter devotee name"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Relation</label>

                                    <select name="relation"
                                            class="form-control @error('relation') is-invalid @enderror">

                                        <option value="">
                                            Select Relation
                                        </option>

                                        <option value="Self" {{ old('relation') == 'Self' ? 'selected' : '' }}>
                                            Self
                                        </option>

                                        <option value="Father" {{ old('relation') == 'Father' ? 'selected' : '' }}>
                                            Father
                                        </option>

                                        <option value="Mother" {{ old('relation') == 'Mother' ? 'selected' : '' }}>
                                            Mother
                                        </option>

                                        <option value="Wife" {{ old('relation') == 'Wife' ? 'selected' : '' }}>
                                            Wife
                                        </option>

                                        <option value="Husband" {{ old('relation') == 'Husband' ? 'selected' : '' }}>
                                            Husband
                                        </option>

                                        <option value="Son" {{ old('relation') == 'Son' ? 'selected' : '' }}>
                                            Son
                                        </option>

                                        <option value="Daughter" {{ old('relation') == 'Daughter' ? 'selected' : '' }}>
                                            Daughter
                                        </option>

                                        <option value="Brother" {{ old('relation') == 'Brother' ? 'selected' : '' }}>
                                            Brother
                                        </option>

                                        <option value="Sister" {{ old('relation') == 'Sister' ? 'selected' : '' }}>
                                            Sister
                                        </option>

                                        <option value="Other" {{ old('relation') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                    @error('relation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Gender</label>

                                    <select name="gender"
                                            class="form-control @error('gender') is-invalid @enderror">

                                        <option value="">
                                            Select Gender
                                        </option>

                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                    @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Date of Birth</label>

                                    <input type="date"
                                           name="date_of_birth"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth') }}">

                                    @error('date_of_birth')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Mobile</label>

                                    <input type="text"
                                           name="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile') }}"
                                           placeholder="Enter mobile number">

                                    @error('mobile')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Gotra</label>

                                    <input type="text"
                                           name="gotra"
                                           class="form-control @error('gotra') is-invalid @enderror"
                                           value="{{ old('gotra') }}"
                                           placeholder="Enter Gotra">

                                    @error('gotra')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Rashi</label>

                                    <select name="rashi"
                                            class="form-control @error('rashi') is-invalid @enderror">

                                        <option value="">
                                            Select Rashi
                                        </option>

                                        @foreach([
                                            'Mesh',
                                            'Vrishabh',
                                            'Mithun',
                                            'Kark',
                                            'Singh',
                                            'Kanya',
                                            'Tula',
                                            'Vrishchik',
                                            'Dhanu',
                                            'Makar',
                                            'Kumbh',
                                            'Meen'
                                        ] as $rashi)

                                            <option value="{{ $rashi }}"
                                                {{ old('rashi') == $rashi ? 'selected' : '' }}>
                                                {{ $rashi }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('rashi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Nakshatra</label>

                                    <input type="text"
                                           name="nakshatra"
                                           class="form-control @error('nakshatra') is-invalid @enderror"
                                           value="{{ old('nakshatra') }}"
                                           placeholder="Enter Nakshatra">

                                    @error('nakshatra')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-12">

                                    <label>Address</label>

                                    <textarea name="address"
                                              rows="4"
                                              class="form-control @error('address') is-invalid @enderror"
                                              placeholder="Enter devotee address">{{ old('address') }}</textarea>

                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Primary Devotee</label>

                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox"
                                               name="is_primary"
                                               value="1"
                                               class="custom-control-input"
                                               id="is_primary"
                                               {{ old('is_primary') ? 'checked' : '' }}>

                                        <label class="custom-control-label"
                                               for="is_primary">

                                            This is the primary devotee

                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('booking-devotees.index') }}"
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
                                Save Devotee

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
