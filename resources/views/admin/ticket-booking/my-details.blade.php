@can('my-tickit-booking.details')
@extends('admin.layouts.app')
@section('title', 'My Booking Details')
@section('content')
<div class="container-fluid py-4">
    @php
        $firstBooking = $bookings->first();
        $totalAmount = $bookings->sum(fn ($booking) => (float) $booking->price);
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Booking Details</h2>
            <p class="text-muted mb-0">
                View all members included in your ticket booking.
            </p>
        </div>
        <a
            href="{{ route('admin.my-tickit-booking') }}"
            class="btn btn-secondary">
            Back to My Bookings
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Booking Summary</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Booking Reference</strong>
                    <div>{{ $firstBooking->booking_reference }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Temple</strong>
                    <div>{{ optional($firstBooking->temple)->name ?? 'N/A' }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Visit Date</strong>
                    <div>
                        {{ $firstBooking->booking_date
                            ? \Illuminate\Support\Carbon::parse($firstBooking->booking_date)->format('d-m-Y')
                            : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Total Members</strong>
                    <div>{{ $bookings->count() }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Total Amount</strong>
                    <div>
                        ₹{{ number_format($totalAmount, 2) }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Payment Status</strong>
                    <div>
                        <span class="badge bg-success">
                            {{ ucfirst($firstBooking->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Booking Status</strong>
                    <div>
                        <span class="badge bg-success">
                            {{ ucfirst($firstBooking->booking_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Member Details</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member Name</th>
                            <th>Ticket Type</th>
                            <th>Visitor Category</th>
                            <th>Age</th>
                            <th>Visit Date</th>
                            <th>Price</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $booking->member_name }}</td>

                                <td>
                                    {{ strtoupper($booking->ticket_type) }}
                                </td>

                                <td>{{ $booking->type }}</td>

                                <td>{{ $booking->age }}</td>

                                <td>
                                    {{ $booking->booking_date
                                        ? \Illuminate\Support\Carbon::parse($booking->booking_date)->format('d-m-Y')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $booking->price, 2) }}
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ ucfirst($booking->booking_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">
                                Total Amount
                            </th>
                            <th>
                                ₹{{ number_format($totalAmount, 2) }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan