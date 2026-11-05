@extends('admin.layouts.app')

@section('content')
@can('view-event-bookings')
<section class="section">
    <div class="section-header">
        <h1>Cancelled Event Bookings</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('customer.sanatan-event-bookings.index') }}">Event Bookings</a></div>
            <div class="breadcrumb-item active">Cancelled Bookings</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Cancelled Bookings</h4>
                        <span class="badge badge-danger">{{ $bookings->total() }} Cancelled</span>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Reference</th>
                                        <th>Event</th>
                                        <th>Customer</th>
                                        <th>Event Date</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Refund Status</th>
                                        <th>Refund Amount</th>
                                        <th>Cancelled At</th>
                                        <th>Cancelled By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $key => $booking)
                                        <tr>
                                            <td>{{ $bookings->firstItem() + $key }}</td>
                                            <td>{{ $booking->booking_reference ?? ('#' . $booking->id) }}</td>
                                            <td>
                                                <strong>{{ $booking->event_name ?? 'Event' }}</strong>
                                                @if(!empty($booking->event_description))
                                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($booking->event_description), 60) }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $booking->customer_name ?? 'N/A' }}
                                                <div class="text-muted small">{{ $booking->customer_email ?? '' }}</div>
                                            </td>
                                            <td>
                                                @if(!empty($booking->event_date))
                                                    {{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}
                                                @else
                                                    -
                                                @endif
                                                @if(!empty($booking->event_time))
                                                    <div class="text-muted small">{{ $booking->event_time }}</div>
                                                @endif
                                            </td>
                                            <td>₹{{ number_format((float) ($booking->amount ?? 0), 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ strtolower($booking->payment_status ?? '') === 'paid' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php($refundStatus = strtolower($booking->refund_status ?? 'not_requested'))
                                                @php($refundBadge = in_array($refundStatus, ['processed', 'refunded', 'success'], true) ? 'success' : (in_array($refundStatus, ['failed'], true) ? 'danger' : (in_array($refundStatus, ['pending', 'processing', 'created'], true) ? 'warning' : 'secondary')))
                                                <span class="badge badge-{{ $refundBadge }}">{{ ucfirst(str_replace('_', ' ', $refundStatus)) }}</span>
                                            </td>
                                            <td>
                                                @if($booking->refund_amount !== null)
                                                    ₹{{ number_format((float) $booking->refund_amount, 2) }}
                                                    @if($booking->refund_percentage !== null)
                                                        <div class="text-muted small">{{ number_format((float) $booking->refund_percentage, 2) }}%</div>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ !empty($booking->cancelled_at) ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, h:i A') : '-' }}
                                            </td>
                                            <td>{{ $booking->cancelled_by_name ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('customer.sanatan-event-bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <h6 class="mb-1">No cancelled bookings found</h6>
                                                <p class="text-muted mb-0">Cancelled event bookings will appear here.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $bookings->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endcan
@endsection
