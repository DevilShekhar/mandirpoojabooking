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
    @elseif(auth()->user()->hasRole('Store Manager'))
        <section class="section">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="font-weight-bold">Store Manager Dashboard</h4>
                    <p class="text-muted">
                        Welcome back, {{ auth()->user()->name }}.
                        Manage your temple products here.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $templeProductCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('admin.temple-products.index') }}">
                                            My Total Products
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
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $samgriBookingCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('samgri-booking.index') }}">
                                            Pending Samagri Bookings
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
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $completedSamgriBookingCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('completed-samgri-booking.index') }}">
                                            Completed Samagri Bookings
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
                        <i class="fas fa-box-open mr-2"></i>
                        My Recent Temple Products
                    </h4>
                    <a href="{{ route('admin.temple-products.index') }}" class="btn btn-primary btn-sm">
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
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templeProducts ?? [] as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $product->sku ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-box text-primary mr-1"></i>
                                            {{ $product->name }}
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format((float) $product->price, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->qty > 0 ? 'badge-info' : 'badge-danger' }}">
                                                {{ $product->qty }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ ucfirst(str_replace('_', ' ', $product->unit ?? '-')) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.temple-products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">No Temple Products Found</h6>
                                            <p class="text-muted">
                                                You have not added any temple products yet.
                                            </p>
                                            <a href="{{ route('admin.temple-products.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus mr-1"></i>
                                                Add Product
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
                        <i class="fas fa-box-open mr-2"></i>
                        Recent Samagri Bookings
                    </h4>
                    <a href="{{ route('samgri-booking.index') }}" class="btn btn-primary btn-sm">
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
                                    <th>Customer</th>
                                    <th>Puja</th>
                                    <th>Package</th>
                                    <th>Schedule Date</th>
                                    <th>Schedule Time</th>
                                    <th>Samagri Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($samgriBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>
                                                {{ $booking->booking_number ?? $booking->id }}
                                            </strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-user text-primary mr-1"></i>
                                            {{ $booking->customer_name ?? $booking->user->name ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-praying-hands text-primary mr-1"></i>
                                            {{ $booking->puja->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->pujaPackage->name ?? $booking->package_name ?? '-' }}
                                        </td>
                                        <td>
                                            @if($booking->schedule_date)
                                                {{ \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $booking->schedule_time ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i>
                                                OTP Pending
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Pending Samagri Bookings Found
                                            </h6>
                                            <p class="text-muted mb-0">
                                                There are no upcoming Samagri bookings awaiting OTP verification.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        Recent Completed Samagri Bookings
                    </h6>

                    <a href="{{ route('completed-samgri-booking.index') }}"
                    class="btn btn-sm btn-success">
                        View All
                    </a>
                </div>

                <div class="card-body">
                    @if($completedSamgriBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Details</th>
                                        <th>Customer Details</th>
                                        <th>Puja Details</th>
                                        <th>Schedule</th>
                                        <th>Verified By</th>
                                        <th>Verified At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($completedSamgriBookings as $key => $booking)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                <strong>
                                                    {{ $booking->booking_number ?? 'N/A' }}
                                                </strong>
                                            </td>

                                            <td>
                                                <div>
                                                    <i class="fas fa-user text-primary mr-1"></i>
                                                    {{ $booking->customer_name ?? $booking->user?->name ?? 'N/A' }}
                                                </div>

                                                @if(!empty($booking->customer_email))
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        {{ $booking->customer_email }}
                                                    </small>
                                                @endif

                                                @if(!empty($booking->customer_mobile))
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone mr-1"></i>
                                                        {{ $booking->customer_mobile }}
                                                    </small>
                                                @endif
                                            </td>

                                            <td>
                                                <div>
                                                    <i class="fas fa-praying-hands text-warning mr-1"></i>
                                                    {{ $booking->puja?->name ?? 'N/A' }}
                                                </div>

                                                <small class="text-muted">
                                                    <i class="fas fa-box mr-1"></i>
                                                    {{ $booking->pujaPackage?->name ?? $booking->package_name ?? 'N/A' }}
                                                </small>
                                            </td>

                                            <td>
                                                <div>
                                                    <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                                    {{ $booking->schedule_date
                                                        ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y')
                                                        : 'N/A' }}
                                                </div>

                                                <small class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $booking->schedule_time ?? 'N/A' }}
                                                </small>
                                            </td>

                                            <td>
                                                {{ $booking->samgriVerification?->verifiedBy?->name ?? 'Not Available' }}
                                            </td>

                                            <td>
                                                {{ $booking->samgriVerification?->verified_at
                                                    ? \Carbon\Carbon::parse($booking->samgriVerification->verified_at)->format('d M Y h:i A')
                                                    : 'Not Available' }}
                                            </td>

                                            <td>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No completed Samagri bookings found.</h6>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @elseif(auth()->user()->hasRole('Event Creator'))
        <section class="section">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="font-weight-bold">Event Creator Dashboard</h4>
                    <p class="text-muted">
                        Welcome back, {{ auth()->user()->name }}.
                        Manage your Sanatan events and bookings here.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $sanatanEventCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('admin.sanatan-events.index') }}">
                                            My Total Events
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $activeSanatanEventCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        Active Events
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-orange">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $inactiveSanatanEventCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        Inactive Events
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-blue">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $sanatanEventBookingCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('customer.sanatan-event-bookings.index') }}">
                                            Event Bookings
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
                        <i class="fas fa-calendar-alt mr-2"></i>
                        My Recent Sanatan Events
                    </h4>
                    <div>
                        <a href="{{ route('admin.sanatan-events.create') }}"  class="btn btn-success btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Add New Event
                        </a>
                        <a href="{{ route('admin.sanatan-events.index') }}" class="btn btn-primary btn-sm">
                            View All
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Details</th>
                                    <th>Program Type</th>
                                    <th>Language</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sanatanEvents ?? [] as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($event->image)
                                                    <img src="{{ asset('storage/' . $event->image) }}"
                                                        alt="{{ $event->name }}"
                                                        width="45"
                                                        height="45"
                                                        class="rounded mr-2"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center mr-2"
                                                        style="width:45px;height:45px;">
                                                        <i class="fas fa-calendar text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $event->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $event->slug }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $event->program_type ?? '-' }}</td>
                                        <td>
                                            @php
                                                $languages = is_array($event->language)
                                                    ? $event->language
                                                    : json_decode($event->language ?? '[]', true);
                                            @endphp
                                            {{ is_array($languages) ? implode(', ', $languages) : '-' }}
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format((float) $event->price, 2) }}
                                            </strong>
                                        </td>
                                        <td>{{ $event->duration ?? '-' }}</td>
                                        <td>
                                            @if($event->is_online && $event->is_offline)
                                                <span class="badge badge-info">Online & Offline</span>
                                            @elseif($event->is_online)
                                                <span class="badge badge-primary">Online</span>
                                            @elseif($event->is_offline)
                                                <span class="badge badge-warning">Offline</span>
                                            @else
                                                <span class="badge badge-secondary">Not Set</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $event->status ? 'badge-success' : 'badge-danger' }}">
                                                {{ $event->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $event->created_at ? $event->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sanatan-events.show', $event->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Sanatan Events Found
                                            </h6>
                                            <p class="text-muted">
                                                You have not created any Sanatan events yet.
                                            </p>
                                            <a href="{{ route('admin.sanatan-events.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus mr-1"></i>
                                                Create Event
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
                        <i class="fas fa-calendar-check mr-2"></i>
                        Recent Sanatan Event Bookings
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
                                    <th>Booking Number</th>
                                    <th>Event Name</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Booking Status</th>
                                    <th>Booking Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sanatanEventBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>
                                                {{ $booking->booking_number ?? $booking->id }}
                                            </strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                            {{ $booking->event_name ?? '-' }}
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fas fa-user text-primary mr-1"></i>
                                                {{ $booking->customer_name ?? '-' }}
                                            </div>
                                            @if(!empty($booking->customer_email))
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $booking->customer_email }}
                                                </small>
                                            @endif
                                            @if(!empty($booking->customer_phone))
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone mr-1"></i>
                                                    {{ $booking->customer_phone }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format((float) ($booking->amount ?? 0), 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @php
                                                $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                            @endphp
                                            <span class="badge {{ in_array($paymentStatus, ['paid', 'success', 'captured', 'completed']) ? 'badge-success' : ($paymentStatus === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                                {{ ucfirst($paymentStatus) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                                            @endphp
                                            <span class="badge {{ $bookingStatus === 'confirmed' ? 'badge-success' : ($bookingStatus === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                                                {{ ucfirst($bookingStatus) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $booking->created_at
                                                ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y')
                                                : '-' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.sanatan-event-bookings.show', $booking->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Sanatan Event Bookings Found
                                            </h6>
                                            <p class="text-muted mb-0">
                                                There are no bookings for your Sanatan events yet.
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