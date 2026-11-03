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
    @elseif(auth()->user()->hasRole('Pandit'))
        <section class="section">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="font-weight-bold">Pandit Dashboard</h4>
                    <p class="text-muted">
                        Welcome back, {{ auth()->user()->name }}.
                        Manage your assigned Puja bookings here.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $successfulPujaBookingCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ url('booking-puja-order-successful') }}">
                                            Assigned Puja Bookings
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-red">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $failedPujaPaymentCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ url('booking-puja-order-failed') }}">
                                            Failed Puja Payments
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
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $cancelledPujaBookingCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ url('booking-puja-order-cancelled') }}">
                                            Cancelled Puja Bookings
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row mb-4">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green">
                            <i class="fas fa-clipboard-check"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $completedPujaBookingCount ?? 0 }}
                                    </h3>

                                    <span class="text-muted">
                                        <a href="{{ route('booking-puja-order-completed') }}">
                                            Completed Puja Bookings
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-blue">
                            <i class="fas fa-rupee-sign"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        ₹{{ number_format((float) ($completedPujaBookingAmount ?? 0), 2) }}
                                    </h3>

                                    <span class="text-muted">
                                        Completed Puja Booking Amount
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
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        Recent Successful Puja Bookings
                    </h4>
                    <a href="{{ url('booking-puja-order-successful') }}" class="btn btn-primary btn-sm">
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
                                    <th>Temple</th>
                                    <th>Schedule Date</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($successfulPujaBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>
                                                {{ $booking->booking_number ?? $booking->id }}
                                            </strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-user text-primary mr-1"></i>
                                            {{ $booking->customer_name ?? $booking->user?->name ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-praying-hands text-primary mr-1"></i>
                                            {{ $booking->puja?->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->pujaPackage?->name ?? $booking->package_name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->temple?->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->schedule_date
                                                ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y')
                                                : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Paid
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('booking-puja-order.show', $booking->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Successful Puja Bookings Found
                                            </h6>
                                            <p class="text-muted mb-0">
                                                No paid bookings are currently assigned to you.
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
                        <i class="fas fa-times-circle text-danger mr-2"></i>
                        Recent Failed Puja Payments
                    </h4>
                    <a href="{{ url('booking-puja-order-failed') }}"
                    class="btn btn-primary btn-sm">
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
                                    <th>Payment ID</th>
                                    <th>Booking Number</th>
                                    <th>Customer</th>
                                    <th>Puja</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($failedPujaPayments ?? [] as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $payment->id }}</strong>
                                        </td>
                                        <td>
                                            {{ $payment->booking?->booking_number ?? $payment->booking?->id ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-user text-primary mr-1"></i>
                                            {{ $payment->user?->name ?? $payment->booking?->customer_name ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-praying-hands text-primary mr-1"></i>
                                            {{ $payment->puja?->name ?? $payment->booking?->puja?->name ?? '-' }}
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format((float) ($payment->amount ?? 0), 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Failed
                                            </span>
                                        </td>
                                        <td>
                                            {{ $payment->created_at
                                                ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y')
                                                : '-' }}
                                        </td>
                                        <td>
                                            @if($payment->booking)
                                                <a href="{{ route('booking-puja-order.show', $payment->booking->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Failed Puja Payments Found
                                            </h6>
                                            <p class="text-muted mb-0">
                                                There are no failed payments for your assigned bookings.
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
                        <i class="fas fa-ban text-warning mr-2"></i>
                        Recent Cancelled Puja Bookings
                    </h4>
                    <a href="{{ url('booking-puja-order-cancelled') }}" class="btn btn-primary btn-sm">
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
                                    <th>Temple</th>
                                    <th>Schedule Date</th>
                                    <th>Booking Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cancelledPujaBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>
                                                {{ $booking->booking_number ?? $booking->id }}
                                            </strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-user text-primary mr-1"></i>
                                            {{ $booking->customer_name ?? $booking->user?->name ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-praying-hands text-primary mr-1"></i>
                                            {{ $booking->puja?->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->pujaPackage?->name ?? $booking->package_name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->temple?->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->schedule_date
                                                ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y')
                                                : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-ban mr-1"></i>
                                                Cancelled
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('booking-puja-order.show', $booking->id) }}"
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
                                                No Cancelled Puja Bookings Found
                                            </h6>
                                            <p class="text-muted mb-0">
                                                There are no cancelled bookings assigned to you.
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
    @elseif(auth()->user()->hasRole('Temple Manager'))
        <section class="section">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="font-weight-bold">Temple Manager Dashboard</h4>
                    <p class="text-muted">
                        Welcome back, {{ auth()->user()->name }}.
                        Manage your temple Pandits here.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        {{ $panditCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">
                                        <a href="{{ route('pandits.index') }}">
                                            Total Pandits
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-newspaper"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-weight-bold mb-2">
                                        {{ $newsCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">Total News</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-weight-bold mb-0">
                                        {{ $pujaCount ?? 0 }}
                                    </h3>
                                    <span class="text-muted">Total Pujas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green"><i class="fas fa-check-circle"></i></div>
                        <div class="card-wrap"><div class="padding-20"><div class="text-right">
                            <h3 class="font-light mb-0">{{ $successfulPujaBookingCount ?? 0 }}</h3>
                            <span class="text-muted"><a href="{{ route('booking-puja-order.successful') }}">Successful Puja Bookings</a></span>
                        </div></div></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-orange"><i class="fas fa-times-circle"></i></div>
                        <div class="card-wrap"><div class="padding-20"><div class="text-right">
                            <h3 class="font-light mb-0">{{ $failedPujaPaymentCount ?? 0 }}</h3>
                            <span class="text-muted"><a href="{{ route('booking-puja-order.failed') }}">Failed Puja Payments</a></span>
                        </div></div></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-red"><i class="fas fa-calendar-times"></i></div>
                        <div class="card-wrap"><div class="padding-20"><div class="text-right">
                            <h3 class="font-light mb-0">{{ $cancelledPujaBookingCount ?? 0 }}</h3>
                            <span class="text-muted"><a href="{{ route('booking-puja-order.cancelled') }}">Cancelled Puja Bookings</a></span>
                        </div></div></div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-orange"><i class="fas fa-calendar-times"></i></div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">{{ $customerCancellationCount ?? 0 }}</h3>
                                    <span class="text-muted">Customer Cancellation Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1">
                         <div class="card-icon l-bg-orange">
                            <i class="fa fa-ticket-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">{{ $managerTicketBookingCount ?? 0 }}</h3>
                                    <span class="text-muted">
                                        <a href="{{ route('admin.tickit-booking-history') }}">Ticket Bookings</a>
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
                        <i class="fas fa-user-tie text-primary mr-2"></i>
                        Recent Temple Pandits
                    </h4>
                    <a href="{{ route('pandits.index') }}" class="btn btn-primary btn-sm">
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
                                    <th>Pandit Details</th>
                                    <th>Phone</th>
                                    <th>Temple</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pandits ?? [] as $pandit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>
                                                <i class="fas fa-user text-primary mr-1"></i>
                                                {{ $pandit->name }}
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $pandit->email }}
                                            </small>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone-alt text-primary mr-1"></i>
                                            {{ $pandit->phone ?? '-' }}
                                        </td>
                                        <td>
                                            @forelse($pandit->temples as $temple)
                                                <span class="badge badge-info mb-1">
                                                    <i class="fas fa-place-of-worship mr-1"></i>
                                                    {{ $temple->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted">
                                                    Not Assigned
                                                </span>
                                            @endforelse
                                        </td>
                                        <td>
                                            @if($pandit->status)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>                            
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                No Pandits Found
                                            </h6>
                                            <p class="text-muted mb-3">
                                                No Pandits are currently associated with your approved temples.
                                            </p>
                                            <a href="{{ route('pandits.create') }}"
                                            class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus mr-1"></i>
                                                Add Pandit
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
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-newspaper mr-2"></i> Latest News
                        </h4>
                        <small class="text-muted">
                            News from your approved temples
                        </small>
                    </div>
                    <a href="{{ route('my-news.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list mr-1"></i> View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>News Details</th>
                                    <th>Temple</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newsList as $index => $news)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $news->title }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ Str::limit($news->short_description, 60) }}
                                            </small>
                                        </td>
                                        <td>
                                            <i class="fas fa-place-of-worship text-primary mr-1"></i>
                                            {{ $news->temple?->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @if($news->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $news->created_at?->format('d M Y') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('my-news.show', $news->id) }}"
                                            class="btn btn-outline-primary btn-sm" title="View News">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('my-news.edit', $news->id) }}"
                                            class="btn btn-outline-warning btn-sm" title="Edit News">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-newspaper fa-2x text-muted mb-2"></i>
                                            <h6 class="mb-1">No News Found</h6>
                                            <p class="text-muted mb-2">
                                                No news has been added to your approved temples yet.
                                            </p>
                                            <a href="{{ route('my-news.create') }}"  class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus mr-1"></i> Add News
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
                    <h4>
                        <i class="fas fa-praying-hands mr-2"></i>
                        Recent Pujas
                    </h4>
                    <a href="{{ route('pujas.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list mr-1"></i> View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Puja Name</th>
                                    <th>Temple</th>
                                    <th>Deity</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pujas ?? [] as $index => $puja)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $puja->name }}</strong>
                                            @if($puja->puja_code)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $puja->puja_code }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $puja->temple->name ?? 'N/A' }}</td>
                                        <td>{{ $puja->deity->name ?? 'N/A' }}</td>
                                        <td>{{ $puja->pujaCategory->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($puja->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $puja->created_at?->format('d M Y') ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-praying-hands fa-2x text-muted mb-2"></i>
                                            <p class="mb-0">No Pujas found.</p>
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
                    <h4 class="mb-0"><i class="fas fa-check-circle text-success mr-2"></i>Recent Successful Puja Bookings</h4>
                    <a href="{{ route('booking-puja-order.successful') }}" class="btn btn-primary btn-sm">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>#</th><th>Booking Number</th><th>Customer</th><th>Puja</th><th>Package</th><th>Temple</th><th>Schedule Date</th><th>Payment Status</th><th>Action</th></tr></thead>
                            <tbody>
                                @forelse($successfulPujaBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $booking->booking_number ?? $booking->id }}</strong></td>
                                        <td><i class="fas fa-user text-primary mr-1"></i>{{ $booking->customer_name ?? $booking->user?->name ?? '-' }}</td>
                                        <td><i class="fas fa-praying-hands text-primary mr-1"></i>{{ $booking->puja?->name ?? '-' }}</td>
                                        <td>{{ $booking->pujaPackage?->name ?? $booking->package_name ?? '-' }}</td>
                                        <td>{{ $booking->temple?->name ?? '-' }}</td>
                                        <td>{{ $booking->schedule_date ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y') : '-' }}</td>
                                        <td><span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Paid</span></td>
                                        <td><a href="{{ route('booking-puja-order.show', $booking->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-4"><i class="fas fa-calendar-check fa-2x text-muted mb-2"></i><h6>No Successful Puja Bookings Found</h6><p class="text-muted mb-0">No successful bookings found for your approved temples.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Recent Failed Puja Payments</h4>
                    <a href="{{ route('booking-puja-order.failed') }}" class="btn btn-primary btn-sm">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>#</th><th>Payment ID</th><th>Booking Number</th><th>Customer</th><th>Puja</th><th>Amount</th><th>Payment Status</th><th>Date</th><th>Action</th></tr></thead>
                            <tbody>
                                @forelse($failedPujaPayments ?? [] as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $payment->id }}</strong></td>
                                        <td>{{ $payment->booking?->booking_number ?? $payment->booking?->id ?? '-' }}</td>
                                        <td><i class="fas fa-user text-primary mr-1"></i>{{ $payment->user?->name ?? $payment->booking?->customer_name ?? '-' }}</td>
                                        <td><i class="fas fa-praying-hands text-primary mr-1"></i>{{ $payment->puja?->name ?? $payment->booking?->puja?->name ?? '-' }}</td>
                                        <td><strong>₹{{ number_format((float) ($payment->amount ?? 0), 2) }}</strong></td>
                                        <td><span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>Failed</span></td>
                                        <td>{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y') : '-' }}</td>
                                        <td>
                                            @if($payment->booking)
                                                <a href="{{ route('booking-puja-order.show', $payment->booking->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-4"><i class="fas fa-credit-card fa-2x text-muted mb-2"></i><h6>No Failed Payments Found</h6><p class="text-muted mb-0">No failed payments found for your approved temples.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-calendar-times text-danger mr-2"></i>Recent Cancelled Puja Bookings</h4>
                    <a href="{{ route('booking-puja-order.cancelled') }}" class="btn btn-primary btn-sm">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>#</th><th>Booking Number</th><th>Customer</th><th>Puja</th><th>Package</th><th>Temple</th><th>Schedule Date</th><th>Booking Status</th><th>Action</th></tr></thead>
                            <tbody>
                                @forelse($cancelledPujaBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $booking->booking_number ?? $booking->id }}</strong></td>
                                        <td><i class="fas fa-user text-primary mr-1"></i>{{ $booking->customer_name ?? $booking->user?->name ?? '-' }}</td>
                                        <td><i class="fas fa-praying-hands text-primary mr-1"></i>{{ $booking->puja?->name ?? '-' }}</td>
                                        <td>{{ $booking->pujaPackage?->name ?? $booking->package_name ?? '-' }}</td>
                                        <td>{{ $booking->temple?->name ?? '-' }}</td>
                                        <td>{{ $booking->schedule_date ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y') : '-' }}</td>
                                        <td><span class="badge badge-danger"><i class="fas fa-ban mr-1"></i>Cancelled</span></td>
                                        <td><a href="{{ route('booking-puja-order.show', $booking->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-4"><i class="fas fa-calendar-times fa-2x text-muted mb-2"></i><h6>No Cancelled Puja Bookings Found</h6><p class="text-muted mb-0">No cancelled bookings found for your approved temples.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-calendar-times text-danger mr-2"></i>Recent Customer Cancellation Requests</h4>
                    <span class="badge badge-warning">{{ $customerCancellationCount ?? 0 }} Requests</span>
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
                                    <th>Temple</th>
                                    <th>Cancellation Reason</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Refund Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customerCancellations ?? [] as $cancellation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $cancellation->booking?->booking_number ?? $cancellation->puja_booking_id }}</strong></td>
                                        <td><i class="fas fa-user text-primary mr-1"></i>{{ $cancellation->booking?->customer_name ?? $cancellation->customer?->name ?? $cancellation->booking?->user?->name ?? '-' }}</td>
                                        <td><i class="fas fa-praying-hands text-primary mr-1"></i>{{ $cancellation->booking?->puja?->name ?? '-' }}</td>
                                        <td>{{ $cancellation->booking?->temple?->name ?? '-' }}</td>
                                        <td>{{ $cancellation->cancellation_reason ?: '-' }}</td>
                                        <td>{{ $cancellation->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            @if($cancellation->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($cancellation->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cancellation->refund_status === 'processed')
                                                <span class="badge badge-success">Processed</span>
                                            @elseif($cancellation->refund_status === 'processing')
                                                <span class="badge badge-info">Processing</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $cancellation->refund_status ?? 'Not Required')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">No Customer Cancellation Requests Found</h6>
                                            <p class="text-muted mb-0">There are no cancellation requests for your approved temples.</p>
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
                        <i class="fas fa-ticket-alt text-primary mr-2"></i>
                        Recent Ticket Bookings
                    </h4>
                    <a href="{{ route('admin.tickit-booking-history') }}" class="btn btn-primary btn-sm">
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
                                    <th>Customer Details</th>
                                    <th>Temple</th>
                                    <th>Total Members</th>
                                    <th>Total Amount</th>
                                    <th>Booking Date</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($managerTicketBookings ?? [] as $ticketBooking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $ticketBooking->booking_reference ?? '-' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $ticketBooking->razorpay_order_id }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>
                                                <i class="fas fa-user text-primary mr-1"></i>
                                                {{ $ticketBooking->customer_name ?? '-' }}
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $ticketBooking->customer_email ?? '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            <i class="fas fa-place-of-worship text-primary mr-1"></i>
                                            {{ $ticketBooking->temple_name ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $ticketBooking->total_members }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>₹{{ number_format((float) $ticketBooking->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            {{ $ticketBooking->booking_date ? \Carbon\Carbon::parse($ticketBooking->booking_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ ucfirst($ticketBooking->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tickit-booking-history.show', $ticketBooking->razorpay_order_id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                            <h6 class="font-weight-bold">No Ticket Bookings Found</h6>
                                            <p class="text-muted mb-0">
                                                There are no confirmed ticket bookings for your approved temples yet.
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
   @elseif(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
        <section class="section dashboard-overview">
            <style>
                .dashboard-overview{--dash-primary:#5546a5;--dash-text:#263248;--dash-muted:#7b8495;--dash-border:#edf0f5}
                .dashboard-hero{position:relative;overflow:hidden;border-radius:16px;padding:28px 30px;margin-bottom:25px;background:linear-gradient(120deg,#302568,#6653b5);color:#fff;box-shadow:0 8px 25px rgba(65,49,135,.18)}
                .dashboard-hero:after{content:"ॐ";position:absolute;right:35px;top:-30px;font-size:150px;opacity:.09;font-weight:700}
                .dashboard-hero h3{color:#fff;font-size:25px;font-weight:700;margin-bottom:7px}
                .dashboard-hero p{color:rgba(255,255,255,.78);margin-bottom:0}
                .dashboard-hero-icon{width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;margin-right:16px}
                .dashboard-section-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:28px 0 16px}
                .dashboard-section-heading h4{font-size:17px;font-weight:700;color:var(--dash-text);margin:0}
                .dashboard-section-heading p{font-size:12px;color:var(--dash-muted);margin:4px 0 0}
                .dashboard-section-icon{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:16px;margin-right:12px}
                .dashboard-stat-card{height:100%;min-height:112px;background:#fff;border:1px solid var(--dash-border);border-radius:13px;padding:17px 18px;display:flex;align-items:center;gap:15px;transition:all .2s ease;box-shadow:0 3px 12px rgba(35,45,65,.035)}
                .dashboard-stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(35,45,65,.09)}
                .dashboard-stat-icon{width:46px;height:46px;min-width:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:19px}
                .dashboard-stat-info{min-width:0}
                .dashboard-stat-info h3{font-size:23px;font-weight:700;color:var(--dash-text);margin:0 0 4px;line-height:1.2}
                .dashboard-stat-info span{font-size:12px;color:var(--dash-muted);font-weight:600}
                .dashboard-stat-info a{color:inherit;text-decoration:none}
                .dashboard-stat-info a:hover{color:var(--dash-primary)}
                .dashboard-payment-section{border:1px solid var(--dash-border);border-radius:16px;background:#fff;margin-bottom:22px;overflow:hidden;box-shadow:0 4px 16px rgba(35,45,65,.045)}
                .dashboard-payment-header{padding:19px 22px;display:flex;align-items:center;justify-content:space-between;gap:12px}
                .dashboard-payment-header h4{font-size:17px;font-weight:700;margin:0;color:#fff}
                .dashboard-payment-header p{font-size:12px;margin:4px 0 0;color:rgba(255,255,255,.8)}
                .dashboard-payment-title{display:flex;align-items:center}
                .dashboard-payment-title-icon{width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,.17);display:flex;align-items:center;justify-content:center;font-size:19px;margin-right:13px;color:#fff}
                .dashboard-payment-total{font-size:12px;color:#fff;background:rgba(255,255,255,.15);padding:8px 12px;border-radius:20px;white-space:nowrap}
                .dashboard-payment-body{padding:20px}
                .dashboard-payment-card{height:100%;border:1px solid var(--dash-border);border-radius:13px;padding:17px;background:#fff;position:relative;overflow:hidden}
                .dashboard-payment-card:after{content:"";position:absolute;right:-23px;top:-28px;width:85px;height:85px;border-radius:50%;background:var(--payment-soft);opacity:.65}
                .dashboard-payment-card-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;position:relative;z-index:1}
                .dashboard-payment-card-icon{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;background:var(--payment-soft);color:var(--payment-color);font-size:16px}
                .dashboard-payment-label{font-size:12px;color:var(--dash-muted);font-weight:600}
                .dashboard-payment-count{font-size:25px;font-weight:700;color:var(--dash-text);line-height:1.2;margin-bottom:8px}
                .dashboard-payment-amount{font-size:19px;font-weight:700;color:var(--payment-color);margin-bottom:3px}
                .dashboard-payment-caption{font-size:11px;color:var(--dash-muted)}
                .dashboard-payment-card.paid{--payment-color:#15966a;--payment-soft:#e6f7ef}
                .dashboard-payment-card.cancelled{--payment-color:#dc4c55;--payment-soft:#fff0f0}
                .dashboard-payment-card.refunded{--payment-color:#d58a25;--payment-soft:#fff5e5}
                .dashboard-payment-note{font-size:12px;color:#8a6b32;background:#fff8e9;border:1px solid #f5e8c9;border-radius:9px;padding:10px 13px;margin-top:15px}
                @media(max-width:767px){.dashboard-hero{padding:22px 18px}.dashboard-hero h3{font-size:21px}.dashboard-hero:after{right:5px;font-size:100px}.dashboard-payment-header{padding:16px}.dashboard-payment-body{padding:14px}.dashboard-payment-total{display:none}.dashboard-stat-card{min-height:95px}}
            </style>

            @php
                $dashboardStats = [
                    ['title' => 'Home Banners', 'value' => $homeBannerCount ?? 0, 'icon' => 'fa-images', 'color' => 'purple', 'url' => route('home-banners.index')],
                    ['title' => 'Sanatan Events', 'value' => $sanatanEventCount ?? 0, 'icon' => 'fa-calendar-alt', 'color' => 'blue', 'url' => route('admin.sanatan-events.index')],
                    ['title' => 'Temple Registrations', 'value' => $templeRegistrationCount ?? 0, 'icon' => 'fa-place-of-worship', 'color' => 'orange', 'url' => route('all-templae-register')],
                    ['title' => 'Approved Temples', 'value' => $approvedTempleRegistrationCount ?? 0, 'icon' => 'fa-check-circle', 'color' => 'green', 'url' => route('all-templae-register')],
                    ['title' => 'Rejected Registrations', 'value' => $rejectedTempleRegistrationCount ?? 0, 'icon' => 'fa-times-circle', 'color' => 'red', 'url' => route('all-templae-register')],
                    ['title' => 'Total Users', 'value' => $totalUsersCount ?? 0, 'icon' => 'fa-users', 'color' => 'purple', 'url' => route('admin.users.index')],
                    ['title' => 'Total News', 'value' => $newsCount ?? 0, 'icon' => 'fa-newspaper', 'color' => 'blue', 'url' => route('my-news.index')],
                    ['title' => 'Samgri Bookings', 'value' => $samgriBookingCount ?? 0, 'icon' => 'fa-box-open', 'color' => 'orange', 'url' => route('samgri-booking.index')],
                    ['title' => 'Completed Samgri', 'value' => $completedSamgriBookingCount ?? 0, 'icon' => 'fa-check-circle', 'color' => 'green', 'url' => route('completed-samgri-booking.index')],
                    ['title' => 'Total Deities', 'value' => $deityCount ?? 0, 'icon' => 'fa-praying-hands', 'color' => 'purple', 'url' => route('deities.index')],
                    ['title' => 'Puja Categories', 'value' => $pujaCategoryCount ?? 0, 'icon' => 'fa-list-alt', 'color' => 'blue', 'url' => route('puja-categories.index')],
                    ['title' => 'Total Pujas', 'value' => $pujaCount ?? 0, 'icon' => 'fa-om', 'color' => 'orange', 'url' => route('pujas.index')],
                    ['title' => 'Sanatan Event Bookings', 'value' => $sanatanEventBookingCount ?? 0, 'icon' => 'fa-calendar-check', 'color' => 'purple', 'url' => route('customer.sanatan-event-bookings.index')],
                    ['title' => 'Successful Puja Bookings', 'value' => $successfulPujaBookingCount ?? 0, 'icon' => 'fa-check-circle', 'color' => 'green', 'url' => route('booking-puja-order.successful')],
                    ['title' => 'Failed Puja Payments', 'value' => $failedPujaPaymentCount ?? 0, 'icon' => 'fa-times-circle', 'color' => 'red', 'url' => route('booking-puja-order.failed')],
                    ['title' => 'Cancelled Puja Bookings', 'value' => $cancelledPujaBookingCount ?? 0, 'icon' => 'fa-ban', 'color' => 'orange', 'url' => route('booking-puja-order.cancelled')]
                ];
            @endphp

            <div class="dashboard-hero">
                <div class="d-flex align-items-center position-relative" style="z-index:1;">
                    <div class="dashboard-hero-icon"><i class="fas fa-chart-pie"></i></div>
                    <div>
                        <h3>Admin Dashboard</h3>
                        <p>Welcome back, {{ auth()->user()->name }}. Monitor bookings, payments and temple activities.</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-section-heading">
                <div class="d-flex align-items-center">
                    <div class="dashboard-section-icon" style="background:#eeeaff;color:#6653b5;"><i class="fas fa-th-large"></i></div>
                    <div>
                        <h4>Application Overview</h4>
                        <p>Quick summary of your application activities</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($dashboardStats as $stat)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
                        <div class="dashboard-stat-card">
                            <div class="dashboard-stat-icon" style="background:{{ ['purple' => '#eeeaff', 'blue' => '#eaf2ff', 'green' => '#e6f7ef', 'red' => '#fff0f0', 'orange' => '#fff4e5'][$stat['color']] }};color:{{ ['purple' => '#6653b5', 'blue' => '#3975c6', 'green' => '#15966a', 'red' => '#dc4c55', 'orange' => '#d58a25'][$stat['color']] }};">
                                <i class="fas {{ $stat['icon'] }}"></i>
                            </div>
                            <div class="dashboard-stat-info">
                                <h3><a href="{{ $stat['url'] }}">{{ number_format($stat['value']) }}</a></h3>
                                <span><a href="{{ $stat['url'] }}">{{ $stat['title'] }}</a></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="dashboard-section-heading">
                <div class="d-flex align-items-center">
                    <div class="dashboard-section-icon" style="background:#e7f5ee;color:#15966a;"><i class="fas fa-wallet"></i></div>
                    <div>
                        <h4>Booking & Payment Overview</h4>
                        <p>Paid collections, cancelled booking values and processed refunds</p>
                    </div>
                </div>
            </div>

            @php
                $paymentSections = [
                    [
                        'title' => 'Ticket Booking Payments',
                        'subtitle' => 'Ticket booking collection and cancellation summary',
                        'icon' => 'fa-ticket-alt',
                        'header' => 'linear-gradient(110deg,#315b9b,#4387c7)',
                        'paidCount' => $ticketPaidCount ?? 0,
                        'paidAmount' => $ticketPaidAmount ?? 0,
                        'cancelledCount' => $ticketCancelledCount ?? 0,
                        'cancelledAmount' => $ticketCancelledAmount ?? 0,
                        'refundCount' => null,
                        'refundAmount' => null
                    ],
                    [
                        'title' => 'Sanatan Event Booking Payments',
                        'subtitle' => 'Event booking collection, cancellations and refunds',
                        'icon' => 'fa-calendar-alt',
                        'header' => 'linear-gradient(110deg,#6945a5,#9568c9)',
                        'paidCount' => $eventPaidCount ?? 0,
                        'paidAmount' => $eventPaidAmount ?? 0,
                        'cancelledCount' => $eventCancelledCount ?? 0,
                        'cancelledAmount' => $eventCancelledAmount ?? 0,
                        'refundCount' => $eventRefundCount ?? 0,
                        'refundAmount' => $eventRefundAmount ?? 0
                    ],
                    [
                        'title' => 'Puja Booking Payments',
                        'subtitle' => 'Puja booking collection, cancellations and refunds',
                        'icon' => 'fa-praying-hands',
                        'header' => 'linear-gradient(110deg,#a85b26,#d58a3b)',
                        'paidCount' => $pujaPaidCount ?? 0,
                        'paidAmount' => $pujaPaidAmount ?? 0,
                        'cancelledCount' => $pujaCancelledCount ?? 0,
                        'cancelledAmount' => $pujaCancelledAmount ?? 0,
                        'refundCount' => $pujaRefundCount ?? 0,
                        'refundAmount' => $pujaRefundAmount ?? 0
                    ]
                ];
            @endphp

            @foreach($paymentSections as $payment)
                <div class="dashboard-payment-section">
                    <div class="dashboard-payment-header" style="background:{{ $payment['header'] }};">
                        <div class="dashboard-payment-title">
                            <div class="dashboard-payment-title-icon"><i class="fas {{ $payment['icon'] }}"></i></div>
                            <div>
                                <h4>{{ $payment['title'] }}</h4>
                                <p>{{ $payment['subtitle'] }}</p>
                            </div>
                        </div>
                        <div class="dashboard-payment-total"><i class="fas fa-chart-line mr-1"></i> Payment Summary</div>
                    </div>
                    <div class="dashboard-payment-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-3 mb-lg-0">
                                <div class="dashboard-payment-card paid">
                                    <div class="dashboard-payment-card-top">
                                        <span class="dashboard-payment-label">SUCCESSFUL PAYMENTS</span>
                                        <div class="dashboard-payment-card-icon"><i class="fas fa-check-circle"></i></div>
                                    </div>
                                    <div class="dashboard-payment-count">{{ number_format($payment['paidCount']) }}</div>
                                    <div class="dashboard-payment-amount">₹{{ number_format($payment['paidAmount'], 2) }}</div>
                                    <div class="dashboard-payment-caption">Total Paid Amount</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3 mb-lg-0">
                                <div class="dashboard-payment-card cancelled">
                                    <div class="dashboard-payment-card-top">
                                        <span class="dashboard-payment-label">CANCELLED BOOKINGS</span>
                                        <div class="dashboard-payment-card-icon"><i class="fas fa-times-circle"></i></div>
                                    </div>
                                    <div class="dashboard-payment-count">{{ number_format($payment['cancelledCount']) }}</div>
                                    <div class="dashboard-payment-amount">₹{{ number_format($payment['cancelledAmount'], 2) }}</div>
                                    <div class="dashboard-payment-caption">Total Cancelled Booking Amount</div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="dashboard-payment-card refunded">
                                    <div class="dashboard-payment-card-top">
                                        <span class="dashboard-payment-label">PROCESSED REFUNDS</span>
                                        <div class="dashboard-payment-card-icon"><i class="fas fa-undo-alt"></i></div>
                                    </div>
                                    @if($payment['refundCount'] !== null)
                                        <div class="dashboard-payment-count">{{ number_format($payment['refundCount']) }}</div>
                                        <div class="dashboard-payment-amount">₹{{ number_format($payment['refundAmount'], 2) }}</div>
                                        <div class="dashboard-payment-caption">Total Refunded Amount</div>
                                    @else
                                        <div class="dashboard-payment-count">Not Available</div>
                                        <div class="dashboard-payment-amount">—</div>
                                        <div class="dashboard-payment-caption">Ticket refund tracking is not configured</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($payment['refundCount'] === null)
                            <div class="dashboard-payment-note"><i class="fas fa-info-circle mr-1"></i> Ticket refund totals will appear here after refund amount and refund status are stored in the ticket booking records.</div>
                        @endif
                    </div>
                </div>
            @endforeach
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