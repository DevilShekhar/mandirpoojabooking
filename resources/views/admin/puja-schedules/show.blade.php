@can('puja-schedules.show')
@extends('admin.layouts.app')

@section('title', 'Puja Schedule Details')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Puja Schedule Details</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-schedules.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>

                            <a href="{{ route('puja-schedules.edit', $pujaSchedule->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="section-title mt-0">
                            Schedule Information
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <strong>Temple</strong>
                                <p>{{ $pujaSchedule->temple->name ?? '-' }}</p>
                            </div>

                            <div class="col-md-6">
                                <strong>Puja</strong>
                                <p>{{ $pujaSchedule->puja->name ?? '-' }}</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Puja Date</strong>
                                <p>
                                    {{ $pujaSchedule->puja_date ? $pujaSchedule->puja_date->format('d M Y') : '-' }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <strong>Start Time</strong>
                                <p>
                                    {{ \Carbon\Carbon::parse($pujaSchedule->start_time)->format('h:i A') }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <strong>End Time</strong>
                                <p>
                                    {{ \Carbon\Carbon::parse($pujaSchedule->end_time)->format('h:i A') }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <strong>Total Capacity</strong>
                                <p>{{ $pujaSchedule->capacity }}</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Booked Capacity</strong>
                                <p>{{ $pujaSchedule->booked_capacity }}</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Remaining Capacity</strong>
                                <p>
                                    <span class="badge badge-info">
                                        {{ $pujaSchedule->remaining_capacity }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <strong>Booking Start Date</strong>
                                <p>
                                    {{ $pujaSchedule->booking_start_date
                                        ? $pujaSchedule->booking_start_date->format('d M Y')
                                        : '-' }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <strong>Booking End Date</strong>
                                <p>
                                    {{ $pujaSchedule->booking_end_date
                                        ? $pujaSchedule->booking_end_date->format('d M Y')
                                        : '-' }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <strong>Status</strong>
                                <p>
                                    @if($pujaSchedule->status)
                                        <span class="badge badge-success badge-shadow">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger badge-shadow">
                                            Inactive
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6">
                                <strong>Created At</strong>
                                <p>
                                    {{ $pujaSchedule->created_at
                                        ? $pujaSchedule->created_at->format('d M Y h:i A')
                                        : '-' }}
                                </p>
                            </div>

                        </div>

                    </div>
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
