@extends('admin.layouts.app')
@section('content')
    @if(auth()->user()->hasRole('Customer'))
    <section class="section">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="font-weight-bold">My Dashboard</h4>
                <p class="text-muted">
                    Welcome back, {{ auth()->user()->name }}.
                    Manage your Puja bookings here.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-purple">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    {{ $bookingCount ?? 0 }}
                                </h3>
                                <span class="text-muted">
                                    <a href="{{ route('booking-puja-order.index') }}">
                                        My Total Bookings
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-green">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    {{ $sanatanEventBookingCount ?? 0 }}
                                </h3>
                                <span class="text-muted">
                                    <a href="{{ route('customer.sanatan-event-bookings.index') }}">
                                        My Sanatan Event Bookings
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-orange">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    {{ $cancellationCount ?? 0 }}
                                </h3>
                                <span class="text-muted">
                                    <a href="{{ route('customer-cancel-booking.index') }}">
                                        My Cancellation Requests
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-blue">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    {{ $ticketBookingCount ?? 0 }}
                                </h3>
                                <span class="text-muted">
                                    <a href="{{ route('admin.my-tickit-booking') }}">
                                        My Ticket Bookings
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-list-alt mr-2"></i>
                    My Recent Puja Bookings
                </h4>
                <a href="{{ route('booking-puja-order.index') }}" class="btn btn-primary btn-sm">
                    View All
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Number</th>
                                <th>Puja</th>
                                <th>Schedule Date</th>
                                <th>Amount</th>
                                <th>Booking Status</th>                         
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings ?? [] as $booking)
                                @php
                                    $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                                    $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                    $bookingBadge = 'badge-warning';
                                    if (in_array($bookingStatus, ['confirmed', 'completed'])) {
                                        $bookingBadge = 'badge-success';
                                    } elseif (in_array($bookingStatus, ['cancelled', 'canceled'])) {
                                        $bookingBadge = 'badge-danger';
                                    } elseif (in_array($bookingStatus, ['in progress', 'processing'])) {
                                        $bookingBadge = 'badge-primary';
                                    } elseif ($bookingStatus === 'scheduled') {
                                        $bookingBadge = 'badge-info';
                                    }
                                    $paymentBadge = 'badge-warning';
                                    if (in_array($paymentStatus, ['paid', 'success', 'captured', 'completed'])) {
                                        $paymentBadge = 'badge-success';
                                    } elseif ($paymentStatus === 'failed') {
                                        $paymentBadge = 'badge-danger';
                                    } elseif ($paymentStatus === 'refunded') {
                                        $paymentBadge = 'badge-info';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $booking->booking_number ?? $booking->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">
                                            <i class="fas fa-praying-hands text-primary mr-1"></i>
                                            {{ $booking->puja->name ?? '-' }}
                                        </div>                                   
                                    </td>
                                    <td>
                                        @if($booking->schedule_date)
                                            <i class="far fa-calendar-alt text-muted mr-1"></i>
                                            {{ \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            ₹{{ number_format((float) $booking->total_amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $bookingBadge }}">
                                            {{ ucfirst($booking->booking_status ?? 'Pending') }}
                                        </span>
                                    </td>                              
                                    <td>
                                        <a href="{{ route('booking-puja-order.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h6 class="font-weight-bold">No Puja Bookings Found</h6>
                                        <p class="text-muted">
                                            You have not booked any Puja yet.
                                        </p>
                                        <a href="{{ route('frontend.puja') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-praying-hands mr-1"></i>
                                            Explore Pujas
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    My Recent Sanatan Event Bookings
                </h4>
                <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-primary btn-sm">
                    View All
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Event Name</th>
                                <th>Amount</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sanatanEventBookings ?? [] as $eventBooking)
                                @php
                                    $bookingStatus = strtolower($eventBooking->booking_status ?? 'pending');
                                    $paymentStatus = strtolower($eventBooking->payment_status ?? 'pending');
                                    $bookingBadge = 'badge-warning';
                                    if (in_array($bookingStatus, ['confirmed', 'completed'])) {
                                        $bookingBadge = 'badge-success';
                                    } elseif (in_array($bookingStatus, ['cancelled', 'canceled'])) {
                                        $bookingBadge = 'badge-danger';
                                    } elseif (in_array($bookingStatus, ['processing', 'in progress'])) {
                                        $bookingBadge = 'badge-primary';
                                    }
                                    $paymentBadge = 'badge-warning';
                                    if (in_array($paymentStatus, ['paid', 'success', 'captured', 'completed'])) {
                                        $paymentBadge = 'badge-success';
                                    } elseif ($paymentStatus === 'failed') {
                                        $paymentBadge = 'badge-danger';
                                    } elseif ($paymentStatus === 'refunded') {
                                        $paymentBadge = 'badge-info';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>#{{ $eventBooking->id }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-check text-primary mr-1"></i>
                                        {{ $eventBooking->event_name ?? '-' }}
                                    </td>
                                    <td>
                                        <strong>
                                            ₹{{ number_format((float) $eventBooking->amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $bookingBadge }}">
                                            {{ ucfirst($eventBooking->booking_status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $paymentBadge }}">
                                            {{ ucfirst($eventBooking->payment_status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $eventBooking->created_at
                                            ? \Carbon\Carbon::parse($eventBooking->created_at)->format('d M Y')
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h6 class="font-weight-bold">No Sanatan Event Bookings Found</h6>
                                        <p class="text-muted">
                                            You have not booked any Sanatan event yet.
                                        </p>
                                        <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Explore Events
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-times mr-2"></i>
                    My Recent Cancellation Requests
                </h4>
                <a href="{{ route('customer-cancel-booking.index') }}" class="btn btn-primary btn-sm">
                    View All
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Number</th>
                                <th>Puja</th>
                                <th>Cancellation Reason</th>
                                <th>Request Date</th>
                                <th>Cancellation Status</th>
                                <th>Refund Amount</th>
                                <th>Refund Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cancellations ?? [] as $cancellation)
                                @php
                                    $status = strtolower($cancellation->status ?? 'pending');
                                    $refundStatus = strtolower($cancellation->refund_status ?? 'not_required');
                                    $statusBadge = 'badge-warning';
                                    if ($status === 'approved') {
                                        $statusBadge = 'badge-success';
                                    } elseif ($status === 'rejected') {
                                        $statusBadge = 'badge-danger';
                                    }
                                    $refundBadge = 'badge-warning';
                                    if (in_array($refundStatus, ['processed', 'completed'])) {
                                        $refundBadge = 'badge-success';
                                    } elseif ($refundStatus === 'failed') {
                                        $refundBadge = 'badge-danger';
                                    } elseif ($refundStatus === 'not_required') {
                                        $refundBadge = 'badge-secondary';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>
                                            {{ $cancellation->booking->booking_number ?? $cancellation->puja_booking_id }}
                                        </strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-praying-hands text-primary mr-1"></i>
                                        {{ $cancellation->booking->puja->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($cancellation->cancellation_reason, 45) }}
                                    </td>
                                    <td>
                                        {{ $cancellation->created_at
                                            ? \Carbon\Carbon::parse($cancellation->created_at)->format('d M Y')
                                            : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge }}">
                                            {{ ucfirst($cancellation->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>
                                            ₹{{ number_format((float) $cancellation->refund_amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $refundBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $cancellation->refund_status ?? 'Not Required')) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                        <h6 class="font-weight-bold">
                                            No Cancellation Requests Found
                                        </h6>
                                        <p class="text-muted mb-0">
                                            You have not submitted any booking cancellation requests.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    My Recent Ticket Bookings
                </h4>
                <a href="{{ route('admin.my-tickit-booking') }}" class="btn btn-primary btn-sm">
                    View All
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Reference</th>
                                <th>Temple</th>
                                <th>Booking Date</th>
                                <th>Total Members</th>
                                <th>Total Amount</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketBookings ?? [] as $ticketBooking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>
                                            {{ $ticketBooking->booking_reference ?? $ticketBooking->razorpay_order_id }}
                                        </strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-place-of-worship text-primary mr-1"></i>
                                        {{ $ticketBooking->temple_name ?? '-' }}
                                    </td>
                                    <td>
                                        @if($ticketBooking->booking_date)
                                            <i class="far fa-calendar-alt text-muted mr-1"></i>
                                            {{ \Carbon\Carbon::parse($ticketBooking->booking_date)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $ticketBooking->total_members }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>
                                            ₹{{ number_format((float) $ticketBooking->total_amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ ucfirst($ticketBooking->booking_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ ucfirst($ticketBooking->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                        <h6 class="font-weight-bold">
                                            No Ticket Bookings Found
                                        </h6>
                                        <p class="text-muted mb-0">
                                            You have not made any confirmed ticket bookings yet.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="section">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="font-weight-bold">Dashboard</h4>
                <p class="text-muted">
                    Welcome back, {{ auth()->user()->name }}.
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                <h5 class="font-weight-bold">Welcome to Your Dashboard</h5>
                <p class="text-muted mb-0">
                    You are logged in as {{ auth()->user()->getRoleNames()->implode(', ') }}.
                </p>
            </div>
        </div>
    </section>
    @endif
@endsection