
@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Confirmed Event Bookings</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('customer.sanatan-event-bookings.index') }}">
                    Event Bookings
                </a>
            </div>
            <div class="breadcrumb-item active">
                Confirmed Bookings
            </div>
        </div>
    </div>

    <div class="section-body">

        {{-- Summary --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Confirmed Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ $bookings->total() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="card">
            <div class="card-header">
                <h4>Confirmed Event Booking List</h4>

                <div class="card-header-action">
                    <a href="{{ route('customer.sanatan-event-bookings.index') }}"
                       class="btn btn-primary">
                        All Bookings
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Reference</th>
                                <th>Event Details</th>
                                <th>Customer Details</th>
                                <th>Event Date</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Booking Status</th>
                                <th>Booking Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bookings as $key => $booking)
                                <tr>
                                    <td>
                                        {{ $bookings->firstItem() + $key }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $booking->booking_reference ?? 'N/A' }}
                                        </strong>
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $booking->event_name ?? 'N/A' }}
                                        </strong>

                                        @if(!empty($booking->event_creator_name))
                                            <br>
                                            <small class="text-muted">
                                                Creator:
                                                {{ $booking->event_creator_name }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $booking->customer_name ?? 'N/A' }}
                                        </strong>
                                        <br>
                                        <small>
                                            {{ $booking->customer_email ?? '' }}
                                        </small>
                                        @if(!empty($booking->customer_phone))
                                            <br>
                                            <small>
                                                {{ $booking->customer_phone }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $booking->event_date
                                            ? \Carbon\Carbon::parse($booking->event_date)->format('d M Y')
                                            : 'N/A' }}

                                        @if(!empty($booking->event_time))
                                            <br>
                                            <small>
                                                {{ $booking->event_time }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $booking->currency ?? 'INR' }}
                                            {{ number_format((float) $booking->amount, 2) }}
                                        </strong>
                                    </td>

                                    <td>
                                        @php
                                            $paymentStatus = strtolower(
                                                $booking->payment_status ?? 'pending'
                                            );

                                            $paymentBadge = match ($paymentStatus) {
                                                'paid', 'success', 'captured', 'completed' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'info',
                                                default => 'warning',
                                            };
                                        @endphp

                                        <span class="badge badge-{{ $paymentBadge }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-success">
                                            Confirmed
                                        </span>
                                    </td>

                                    <td>
                                        {{ $booking->created_at
                                            ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y, h:i A')
                                            : 'N/A' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('customer.sanatan-event-bookings.show', $booking->id) }}"
                                           class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <h6>No confirmed event bookings found.</h6>
                                        <p class="text-muted mb-0">
                                            Confirmed bookings will appear here.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($bookings->hasPages())
                    <div class="mt-3">
                        {{ $bookings->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endsection
