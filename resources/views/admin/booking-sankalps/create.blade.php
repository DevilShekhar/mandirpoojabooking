@extends('admin.layouts.app')

@section('title', 'Add Booking Sankalp')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Sankalp Information</h4>
                    </div>
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

                        <form action="{{ route('booking-sankalps.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Booking <span class="text-danger">*</span>
                                        </label>
                                        <select name="booking_id"
                                                class="form-control @error('booking_id') is-invalid @enderror"
                                                required>
                                            <option value="">Select Booking</option>
                                            @foreach($bookings as $booking)
                                                <option value="{{ $booking->id }}"
                                                    {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                                    {{ $booking->booking_number ?? 'Booking #'.$booking->id }}
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pandit</label>
                                        <select name="pandit_id"
                                                class="form-control @error('pandit_id') is-invalid @enderror">
                                            <option value="">Select Pandit</option>
                                            @foreach($pandits as $pandit)
                                                <option value="{{ $pandit->id }}"
                                                    {{ old('pandit_id') == $pandit->id ? 'selected' : '' }}>
                                                    {{ $pandit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pandit_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sankalp Date</label>
                                        <input type="date"
                                               name="sankalp_date"
                                               class="form-control @error('sankalp_date') is-invalid @enderror"
                                               value="{{ old('sankalp_date') }}">
                                        @error('sankalp_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required>
                                            <option value="pending"
                                                {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="in_progress"
                                                {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                                In Progress
                                            </option>
                                            <option value="completed"
                                                {{ old('status') == 'completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sankalp Text</label>
                                        <textarea name="sankalp_text"
                                                  rows="5"
                                                  class="form-control @error('sankalp_text') is-invalid @enderror"
                                                  placeholder="Enter sankalp details">{{ old('sankalp_text') }}</textarea>
                                        @error('sankalp_text')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Completed At</label>
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
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Video URL</label>
                                        <input type="url"
                                               name="video_url"
                                               class="form-control @error('video_url') is-invalid @enderror"
                                               value="{{ old('video_url') }}"
                                               placeholder="https://...">
                                        @error('video_url')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <textarea name="remarks"
                                                  rows="4"
                                                  class="form-control @error('remarks') is-invalid @enderror"
                                                  placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Sankalp
                                </button>
                                <a href="{{ route('booking-sankalps.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
