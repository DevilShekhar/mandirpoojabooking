
@extends('admin.layouts.app')

@section('title', 'Online Puja Customer Bookings')

@section('content')
<section class="section">

    {{-- Page Header --}}
    <div class="section-header">
        <h1>Customer Bookings</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('online-pujas.index') }}">Online Pujas</a>
            </div>
            <div class="breadcrumb-item active">
                Customer Bookings
            </div>
        </div>
    </div>

    <div class="section-body">

        {{-- Puja Information --}}
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fa fa-praying-hands text-primary"></i>
                    {{ $puja->title }}
                </h4>

                <div class="card-header-action">
                    <a href="{{ route('online-pujas.index') }}"
                       class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Online Pujas
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <div class="p-3 rounded bg-light">
                            <small class="text-muted">Temple Name</small>
                            <h6 class="mb-0 mt-1">
                                {{ $puja->temple_name ?? '-' }}
                            </h6>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-3 rounded bg-light">
                            <small class="text-muted">Puja Date</small>
                            <h6 class="mb-0 mt-1">
                                @if($puja->puja_datetime)
                                    {{ \Carbon\Carbon::parse($puja->puja_datetime)->format('d M Y, h:i A') }}
                                @else
                                    -
                                @endif
                            </h6>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-3 rounded bg-light">
                            <small class="text-muted">Puja Status</small>
                            <h6 class="mb-0 mt-1">
                                @if($puja->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- Summary Cards --}}
        @php
            $totalBookings = $bookings->count();

            $paidBookings = $bookings->filter(function ($booking) {
                return in_array(strtolower($booking->payment_status ?? ''), [
                    'paid', 'success', 'successful'
                ]);
            })->count();

            $pendingBookings = $bookings->filter(function ($booking) {
                return strtolower($booking->payment_status ?? '') === 'pending';
            })->count();

            $totalAmount = $bookings->sum('amount');
        @endphp

        <div class="row">

            {{-- Total Bookings --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalBookings }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paid Bookings --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Paid Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ $paidBookings }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Bookings --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fa fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending Payments</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingBookings }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Amount --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fa fa-rupee-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Booking Amount</h4>
                        </div>
                        <div class="card-body">
                            ₹{{ number_format((float) $totalAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Bookings Table --}}
        <div class="card">

            <div class="card-header">
                <h4>
                    <i class="fa fa-users text-primary"></i>
                    Customer Booking Details
                </h4>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered"
                           id="bookings-table">

                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Booking ID</th>
                                <th>Customer Details</th>
                                <th>Package</th>
                                <th>Devotees</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Booking Status</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($bookings as $key => $booking)

                                <tr>

                                    {{-- Serial Number --}}
                                    <td>{{ $key + 1 }}</td>

                                    {{-- Booking ID --}}
                                    <td>
                                        <strong>
                                            {{ $booking->order_number ?? ('#' . $booking->id) }}
                                        </strong>
                                    </td>

                                    {{-- Customer Details --}}
                                    <td>
                                        <strong>
                                            {{ $booking->customer_name ?? '-' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            <i class="fa fa-envelope"></i>
                                            {{ $booking->customer_email ?? '-' }}
                                        </small>

                                        <br>

                                        <small class="text-muted">
                                            <i class="fa fa-phone"></i>
                                            {{ $booking->customer_phone ?? '-' }}
                                        </small>
                                    </td>

                                    {{-- Package --}}
                                    <td>
                                        {{ $booking->package->package_name ?? $booking->package_name ?? '-' }}
                                    </td>

                                    {{-- Devotees Count --}}
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $booking->package->devotees_count ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Amount --}}
                                    <td>
                                        <strong>
                                            {{ $booking->currency ?? 'INR' }}
                                            {{ number_format((float) $booking->amount, 2) }}
                                        </strong>
                                    </td>

                                    {{-- Payment Status --}}
                                    <td>
                                        @php
                                            $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                        @endphp

                                        @if(in_array($paymentStatus, ['paid', 'success', 'successful']))
                                            <span class="badge badge-success">
                                                <i class="fa fa-check-circle"></i>
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>

                                        @elseif(in_array($paymentStatus, ['failed', 'failure']))
                                            <span class="badge badge-danger">
                                                <i class="fa fa-times-circle"></i>
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>

                                        @elseif($paymentStatus == 'refunded')
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>

                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fa fa-clock"></i>
                                                {{ ucfirst($booking->payment_status ?? 'Pending') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Booking Status --}}
                                    <td>
                                        @php
                                            $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                                        @endphp

                                        @if(in_array($bookingStatus, ['confirmed', 'completed']))
                                            <span class="badge badge-success">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>

                                        @elseif(in_array($bookingStatus, ['cancelled', 'rejected']))
                                            <span class="badge badge-danger">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>

                                        @else
                                            <span class="badge badge-warning">
                                                {{ ucfirst($booking->booking_status ?? 'Pending') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Booking Date --}}
                                    <td>
                                        @if($booking->created_at)
                                            {{ $booking->created_at->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $booking->created_at->format('h:i A') }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>

                                        <h6 class="text-muted">
                                            No Customer Bookings Found
                                        </h6>

                                        <p class="text-muted mb-0">
                                            No bookings have been received for this Online Puja yet.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection


@push('scripts')
<script>
    $(document).ready(function () {

        $('#bookings-table').DataTable({

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],

            ordering: true,

            searching: true,

            paging: true,

            info: true,

            responsive: true,

            autoWidth: false,

            order: [],

            columnDefs: [
                {
                    orderable: false,
                    targets: [0]
                }
            ],

            language: {
                search: "Search Customer:",
                emptyTable: "No customer bookings available",
                zeroRecords: "No matching bookings found"
            }

        });

    });
</script>
@endpush
