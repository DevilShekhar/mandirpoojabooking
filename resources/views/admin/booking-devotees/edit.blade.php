@extends('admin.layouts.app')

@section('title', 'Edit Booking Devotee')

@section('content')

<section class="section">

    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Edit Booking Devotee</h4>

                        <div class="card-header-action">

                            <a href="{{ route('booking-devotees.index') }}"
                               class="btn btn-secondary">

                                <i class="fas fa-arrow-left"></i>
                                Back

                            </a>

                        </div>

                    </div>

                    <form action="{{ route('booking-devotees.update', $bookingDevotee) }}"
                          method="POST">

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
                                                {{ old('booking_id', $bookingDevotee->booking_id) == $booking->id ? 'selected' : '' }}>

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
                                           value="{{ old('name', $bookingDevotee->name) }}"
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
                                            class="form-control">

                                        <option value="">Select Relation</option>

                                        @foreach([
                                            'Self',
                                            'Father',
                                            'Mother',
                                            'Wife',
                                            'Husband',
                                            'Son',
                                            'Daughter',
                                            'Brother',
                                            'Sister',
                                            'Other'
                                        ] as $relation)

                                            <option value="{{ $relation }}"
                                                {{ old('relation', $bookingDevotee->relation) == $relation ? 'selected' : '' }}>

                                                {{ $relation }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Gender</label>

                                    <select name="gender"
                                            class="form-control">

                                        <option value="">Select Gender</option>

                                        <option value="Male"
                                            {{ old('gender', $bookingDevotee->gender) == 'Male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="Female"
                                            {{ old('gender', $bookingDevotee->gender) == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="Other"
                                            {{ old('gender', $bookingDevotee->gender) == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Date of Birth</label>

                                    <input type="date"
                                           name="date_of_birth"
                                           class="form-control"
                                           value="{{ old('date_of_birth', optional($bookingDevotee->date_of_birth)->format('Y-m-d')) }}">

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Mobile</label>

                                    <input type="text"
                                           name="mobile"
                                           class="form-control"
                                           value="{{ old('mobile', $bookingDevotee->mobile) }}">

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Gotra</label>

                                    <input type="text"
                                           name="gotra"
                                           class="form-control"
                                           value="{{ old('gotra', $bookingDevotee->gotra) }}">

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Rashi</label>

                                    <select name="rashi"
                                            class="form-control">

                                        <option value="">Select Rashi</option>

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
                                                {{ old('rashi', $bookingDevotee->rashi) == $rashi ? 'selected' : '' }}>

                                                {{ $rashi }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Nakshatra</label>

                                    <input type="text"
                                           name="nakshatra"
                                           class="form-control"
                                           value="{{ old('nakshatra', $bookingDevotee->nakshatra) }}">

                                </div>

                                <div class="form-group col-md-12">

                                    <label>Address</label>

                                    <textarea name="address"
                                              rows="4"
                                              class="form-control">{{ old('address', $bookingDevotee->address) }}</textarea>

                                </div>

                                <div class="form-group col-md-6">

                                    <label>Primary Devotee</label>

                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox"
                                               name="is_primary"
                                               value="1"
                                               class="custom-control-input"
                                               id="is_primary"
                                               {{ old('is_primary', $bookingDevotee->is_primary) ? 'checked' : '' }}>

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

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save"></i>
                                Update Devotee

                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

</section>

@endsection
