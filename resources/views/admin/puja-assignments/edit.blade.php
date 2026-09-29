@can('puja-assignments.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Puja Assignment')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Edit Puja Assignment</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-assignments.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('puja-assignments.update', $pujaAssignment->id) }}">

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
                                Puja Assignment Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label>
                                        Booking <span class="text-danger">*</span>
                                    </label>

                                    <select name="booking_id"
                                            class="form-control"
                                            required>

                                        <option value="">
                                            Select Booking
                                        </option>

                                        @foreach($bookings as $booking)

                                            <option value="{{ $booking->id }}"
                                                {{ old('booking_id', $pujaAssignment->booking_id) == $booking->id ? 'selected' : '' }}>

                                                Booking #{{ $booking->id }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Pandit <span class="text-danger">*</span>
                                    </label>

                                    <select name="pandit_id"
                                            class="form-control"
                                            required>

                                        <option value="">
                                            Select Pandit
                                        </option>

                                        @foreach($pandits as $pandit)

                                            <option value="{{ $pandit->id }}"
                                                {{ old('pandit_id', $pujaAssignment->pandit_id) == $pandit->id ? 'selected' : '' }}>

                                                {{ $pandit->name }}

                                                @if($pandit->temple)
                                                    - {{ $pandit->temple->name }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Assigned At
                                    </label>

                                    <input type="datetime-local"
                                           name="assigned_at"
                                           class="form-control"
                                           value="{{ old('assigned_at', $pujaAssignment->assigned_at ? $pujaAssignment->assigned_at->format('Y-m-d\TH:i') : '') }}">

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-control"
                                            required>

                                        <option value="assigned"
                                            {{ old('status', $pujaAssignment->status) == 'assigned' ? 'selected' : '' }}>
                                            Assigned
                                        </option>

                                        <option value="accepted"
                                            {{ old('status', $pujaAssignment->status) == 'accepted' ? 'selected' : '' }}>
                                            Accepted
                                        </option>

                                        <option value="started"
                                            {{ old('status', $pujaAssignment->status) == 'started' ? 'selected' : '' }}>
                                            Started
                                        </option>

                                        <option value="completed"
                                            {{ old('status', $pujaAssignment->status) == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                        <option value="cancelled"
                                            {{ old('status', $pujaAssignment->status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>

                                    </select>

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Started At
                                    </label>

                                    <input type="datetime-local"
                                           name="started_at"
                                           class="form-control"
                                           value="{{ old('started_at', $pujaAssignment->started_at ? $pujaAssignment->started_at->format('Y-m-d\TH:i') : '') }}">

                                </div>


                                <div class="form-group col-md-6">

                                    <label>
                                        Completed At
                                    </label>

                                    <input type="datetime-local"
                                           name="completed_at"
                                           class="form-control"
                                           value="{{ old('completed_at', $pujaAssignment->completed_at ? $pujaAssignment->completed_at->format('Y-m-d\TH:i') : '') }}">

                                </div>


                                <div class="form-group col-md-12">

                                    <label>
                                        Remarks
                                    </label>

                                    <textarea name="remarks"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Enter remarks">{{ old('remarks', $pujaAssignment->remarks) }}</textarea>

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('puja-assignments.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Assignment
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
        abort(404);
    @endphp
@endcan
