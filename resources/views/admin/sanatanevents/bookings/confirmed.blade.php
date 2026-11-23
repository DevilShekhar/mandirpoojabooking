@extends('admin.layouts.app')
@section('title', 'Confirmed Event Bookings')
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
                            {{ $bookings->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Confirmed Event Booking List</h4>
                <div class="card-header-action">
                    <a href="{{ route('customer.sanatan-event-bookings.index') }}"
                       class="btn btn-primary">
                        <i class="fas fa-list"></i> All Bookings
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover"  id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
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
                                @php
                                    $paymentStatus = strtolower( $booking->payment_status ?? 'pending');
                                    $paymentBadge = match ($paymentStatus) {
                                        'paid', 'success', 'captured', 'completed' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'info',
                                        default => 'warning',
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>
                                            {{ $booking->event_name ?? 'N/A' }}
                                        </strong>
                                        @if(!empty($booking->event_creator_name))
                                            <br>
                                            <small class="text-muted">
                                                Creator: {{ $booking->event_creator_name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $booking->customer_name ?? 'N/A' }}
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $booking->customer_email ?? '' }}
                                        </small>
                                        @if(!empty($booking->customer_phone))
                                            <br>
                                            <small class="text-muted">
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
                                            <small class="text-muted">
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
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
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
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#table-1').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            order: [[7, 'desc']],
            language: {
                emptyTable: "No confirmed event bookings found.",
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ bookings",
                infoEmpty: "Showing 0 to 0 of 0 bookings",
                zeroRecords: "No matching confirmed bookings found."
            }
        });
    });
</script>
@endpush