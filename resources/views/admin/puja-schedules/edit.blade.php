@can('puja-schedules.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Puja Schedule')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Edit Puja Schedule</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('puja-schedules.update', $pujaSchedule->id) }}">
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
                                Schedule Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>
                                        Temple <span class="text-danger">*</span>
                                    </label>

                                    <select name="temple_id"
                                            class="form-control"
                                            required>
                                        <option value="">Select Temple</option>

                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id', $pujaSchedule->temple_id) == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Puja <span class="text-danger">*</span>
                                    </label>

                                    <select name="puja_id"
                                            class="form-control"
                                            required>
                                        <option value="">Select Puja</option>

                                        @foreach($pujas as $puja)
                                            <option value="{{ $puja->id }}"
                                                {{ old('puja_id', $pujaSchedule->puja_id) == $puja->id ? 'selected' : '' }}>
                                                {{ $puja->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Puja Date <span class="text-danger">*</span>
                                    </label>

                                    <input type="date"
                                           name="puja_date"
                                           class="form-control"
                                           value="{{ old('puja_date', $pujaSchedule->puja_date?->format('Y-m-d')) }}"
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Start Time <span class="text-danger">*</span>
                                    </label>

                                    <input type="time"
                                           name="start_time"
                                           class="form-control"
                                           value="{{ old('start_time', \Carbon\Carbon::parse($pujaSchedule->start_time)->format('H:i')) }}"
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        End Time <span class="text-danger">*</span>
                                    </label>

                                    <input type="time"
                                           name="end_time"
                                           class="form-control"
                                           value="{{ old('end_time', \Carbon\Carbon::parse($pujaSchedule->end_time)->format('H:i')) }}"
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>
                                        Capacity <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="capacity"
                                           id="capacity"
                                           class="form-control"
                                           value="{{ old('capacity', $pujaSchedule->capacity) }}"
                                           min="1"
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Booked Capacity</label>

                                    <input type="number"
                                           name="booked_capacity"
                                           id="booked_capacity"
                                           class="form-control"
                                           value="{{ old('booked_capacity', $pujaSchedule->booked_capacity) }}"
                                           min="0">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Remaining Capacity</label>

                                    <input type="number"
                                           id="remaining_capacity"
                                           class="form-control"
                                           value="{{ old('capacity', $pujaSchedule->capacity) - old('booked_capacity', $pujaSchedule->booked_capacity) }}"
                                           readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Booking Start Date</label>

                                    <input type="date"
                                           name="booking_start_date"
                                           class="form-control"
                                           value="{{ old('booking_start_date', $pujaSchedule->booking_start_date?->format('Y-m-d')) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Booking End Date</label>

                                    <input type="date"
                                           name="booking_end_date"
                                           class="form-control"
                                           value="{{ old('booking_end_date', $pujaSchedule->booking_end_date?->format('Y-m-d')) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-control"
                                            required>
                                        <option value="1"
                                            {{ old('status', $pujaSchedule->status) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status', $pujaSchedule->status) == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('puja-schedules.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Schedule
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
        abort(403);
    @endphp
@endcan
