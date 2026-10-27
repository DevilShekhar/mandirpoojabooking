@can('all-tickit-booking.details')
@extends('admin.layouts.app')
@section('title', 'Booking Member Details')
@section('content')
<div class="container-fluid py-4">
    @php
        $firstBooking = $bookings->first();
        $totalMembers = $bookings->count();
        $totalAmount = $bookings->sum('price');
    @endphp
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Booking Member Details</h2>
            <p class="text-muted">
                View all members included in this ticket booking.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.tickit-booking-history') }}"
               class="btn btn-secondary">
                Back to Booking History
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Booking Information</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <strong>Booking Reference:</strong>
                    <div>{{ $firstBooking->booking_reference }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Customer Name:</strong>
                    <div>
                        {{ optional($firstBooking->user)->name
                            ?? optional($firstBooking->user)->email
                            ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Temple:</strong>
                    <div>
                        {{ optional($firstBooking->temple)->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Booking Date:</strong>
                    <div>
                        {{ $firstBooking->booking_date
                            ? $firstBooking->booking_date->format('d-m-Y')
                            : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Total Members:</strong>
                    <div>{{ $totalMembers }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Total Amount:</strong>
                    <div>₹{{ number_format((float) $totalAmount, 2) }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Payment Status:</strong>
                    <div>
                        <span class="badge bg-success">
                            {{ ucfirst($firstBooking->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Booking Status:</strong>
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
            <h5 class="mb-0">All Booking Members ({{ $totalMembers }})</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member Name</th>
                            <th>Ticket Type</th>
                            <th>Age</th>
                            <th>Ticket Price</th>
                            <th>Payment ID</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bookings as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $member->member_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $member->ticket_type ?? $member->type ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $member->age ?? 'N/A' }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $member->price, 2) }}
                                </td>

                                <td>
                                    {{ $member->razorpay_payment_id ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">
                                Total Amount
                            </th>
                            <th colspan="2">
                                ₹{{ number_format((float) $totalAmount, 2) }}
                            </th>
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