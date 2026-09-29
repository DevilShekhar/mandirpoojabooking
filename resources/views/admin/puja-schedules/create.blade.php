@can('puja-schedules.create')
@extends('admin.layouts.app')

@section('title', 'Create Puja Schedule')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Create Puja Schedule</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('puja-schedules.store') }}">
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
                                Schedule Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>
                                        Temple <span class="text-danger">*</span>
                                    </label>

                                    <select name="temple_id"
                                            class="form-control @error('temple_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Temple</option>

                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Puja <span class="text-danger">*</span>
                                    </label>

                                    <select name="puja_id"
                                            class="form-control @error('puja_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Puja</option>

                                        @foreach($pujas as $puja)
                                            <option value="{{ $puja->id }}"
                                                {{ old('puja_id') == $puja->id ? 'selected' : '' }}>
                                                {{ $puja->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('puja_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Puja Date <span class="text-danger">*</span>
                                    </label>

                                    <input type="date"
                                           name="puja_date"
                                           class="form-control @error('puja_date') is-invalid @enderror"
                                           value="{{ old('puja_date') }}"
                                           required>

                                    @error('puja_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Start Time <span class="text-danger">*</span>
                                    </label>

                                    <input type="time"
                                           name="start_time"
                                           class="form-control @error('start_time') is-invalid @enderror"
                                           value="{{ old('start_time') }}"
                                           required>

                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        End Time <span class="text-danger">*</span>
                                    </label>

                                    <input type="time"
                                           name="end_time"
                                           class="form-control @error('end_time') is-invalid @enderror"
                                           value="{{ old('end_time') }}"
                                           required>

                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Capacity <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="capacity"
                                           id="capacity"
                                           class="form-control @error('capacity') is-invalid @enderror"
                                           value="{{ old('capacity', 1) }}"
                                           min="1"
                                           required>

                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Booked Capacity
                                    </label>

                                    <input type="number"
                                           name="booked_capacity"
                                           id="booked_capacity"
                                           class="form-control @error('booked_capacity') is-invalid @enderror"
                                           value="{{ old('booked_capacity', 0) }}"
                                           min="0">

                                    @error('booked_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Remaining Capacity
                                    </label>

                                    <input type="number"
                                           id="remaining_capacity"
                                           class="form-control"
                                           value="{{ old('capacity', 1) - old('booked_capacity', 0) }}"
                                           readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Booking Start Date</label>

                                    <input type="date"
                                           name="booking_start_date"
                                           class="form-control @error('booking_start_date') is-invalid @enderror"
                                           value="{{ old('booking_start_date') }}">

                                    @error('booking_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Booking End Date</label>

                                    <input type="date"
                                           name="booking_end_date"
                                           class="form-control @error('booking_end_date') is-invalid @enderror"
                                           value="{{ old('booking_end_date') }}">

                                    @error('booking_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('puja-schedules.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Schedule
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
    function calculateRemaining() {
        let capacity = parseInt($('#capacity').val()) || 0;
        let booked = parseInt($('#booked_capacity').val()) || 0;

        $('#remaining_capacity').val(Math.max(capacity - booked, 0));
    }

    $('#capacity, #booked_capacity').on('input', calculateRemaining);
</script>
@endpush
@else
    @php
        abort(404);
    @endphp
@endcan
