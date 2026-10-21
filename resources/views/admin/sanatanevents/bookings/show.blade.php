
@extends('admin.layouts.app')

@section('title', 'Booking Details')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Booking Details</h1>
        <div class="section-header-button ml-auto">
            <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Information</h4>
                        <div class="card-header-action">
                            @if($booking->booking_status === 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->booking_status === 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @elseif($booking->booking_status === 'cancelled')
                                <span class="badge badge-secondary">Cancelled</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($booking->booking_status ?? 'Pending') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">Event Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Booking ID</th>
                                        <td>{{ $booking->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Name</th>
                                        <td>{{ $booking->event_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Description</th>
                                        <td>
                                            @if(!empty($booking->event_description))
                                                {!! nl2br(e(strip_tags($booking->event_description))) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Event Date</th>
                                        <td>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Time</th>
                                        <td>{{ $booking->event_time ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mb-3 mt-4">Customer Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Customer Name</th>
                                        <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $booking->customer_email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $booking->customer_phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer ID</th>
                                        <td>{{ $booking->user_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>{{ $booking->created_by ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mb-3 mt-4">Payment Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Amount</th>
                                        <td>{{ $booking->currency ?? 'INR' }} {{ number_format((float) $booking->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td>
                                            @if($booking->payment_status === 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($booking->payment_status === 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-warning">{{ ucfirst($booking->payment_status ?? 'Pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Razorpay Order ID</th>
                                        <td>{{ $booking->razorpay_order_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Razorpay Payment ID</th>
                                        <td>{{ $booking->razorpay_payment_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Booking Created</th>
                                        <td>{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $booking->updated_at ? \Carbon\Carbon::parse($booking->updated_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
