@extends('admin.layouts.app')

@section('title', 'Assign Puja')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Assign Puja to Pandit</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-assignments.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('puja-assignments.store') }}">

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
                                Puja Assignment Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label>
                                        Booking <span class="text-danger">*</span>
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

                                                {{ $booking->booking_number }} - {{ $booking->customer_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('booking_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Pandit <span class="text-danger">*</span>
                                    </label>

                                    <select name="pandit_id"
                                            class="form-control @error('pandit_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select Pandit
                                        </option>

                                        @foreach($pandits as $pandit)

                                            <option value="{{ $pandit->id }}"
                                                {{ old('pandit_id') == $pandit->id ? 'selected' : '' }}>

                                                {{ $pandit->name }}

                                                @if($pandit->temple)
                                                    - {{ $pandit->temple->name }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('pandit_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Assigned At
                                    </label>

                                    <input type="datetime-local"
                                           name="assigned_at"
                                           class="form-control @error('assigned_at') is-invalid @enderror"
                                           value="{{ old('assigned_at', now()->format('Y-m-d\TH:i')) }}">

                                    @error('assigned_at')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required>

                                        <option value="assigned"
                                            {{ old('status', 'assigned') == 'assigned' ? 'selected' : '' }}>
                                            Assigned
                                        </option>

                                        <option value="accepted"
                                            {{ old('status') == 'accepted' ? 'selected' : '' }}>
                                            Accepted
                                        </option>

                                        <option value="started"
                                            {{ old('status') == 'started' ? 'selected' : '' }}>
                                            Started
                                        </option>

                                        <option value="completed"
                                            {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                        <option value="cancelled"
                                            {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>

                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Started At
                                    </label>

                                    <input type="datetime-local"
                                           name="started_at"
                                           class="form-control @error('started_at') is-invalid @enderror"
                                           value="{{ old('started_at') }}">

                                    @error('started_at')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Completed At
                                    </label>

                                    <input type="datetime-local"
                                           name="completed_at"
                                           class="form-control @error('completed_at') is-invalid @enderror"
                                           value="{{ old('completed_at') }}">

                                    @error('completed_at')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="form-group col-md-12">

                                    <label>
                                        Remarks
                                    </label>

                                    <textarea name="remarks"
                                              class="form-control @error('remarks') is-invalid @enderror"
                                              rows="4"
                                              placeholder="Enter remarks">{{ old('remarks') }}</textarea>

                                    @error('remarks')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('puja-assignments.index') }}"
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
                                Assign Puja
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

@endsection
