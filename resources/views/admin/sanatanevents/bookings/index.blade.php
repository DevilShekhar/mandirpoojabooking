
@extends('admin.layouts.app')

@section('title', 'Sanatan Event Bookings')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Sanatan Event Bookings</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>My Bookings</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if($bookings->isEmpty())
                            <div class="alert alert-info mb-0">
                                No Sanatan event bookings found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Event Name</th>
                                            <th>Event Date</th>
                                            <th>Event Time</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Booking Status</th>
                                            <th>Booked On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr>
                                                <td>{{ $bookings->firstItem() + $loop->index }}</td>
                                                <td>
                                                    <strong>{{ $booking->customer_name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $booking->customer_email ?? '-' }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $booking->customer_phone ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $booking->event_name ?? 'N/A' }}</strong>
                                                    @if(!empty($booking->event_description))
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ \Illuminate\Support\Str::limit(strip_tags($booking->event_description), 80) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $booking->event_time ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $booking->currency ?? 'INR' }}
                                                    {{ number_format((float) $booking->amount, 2) }}
                                                </td>
                                                <td>
                                                    @if($booking->payment_status === 'paid')
                                                        <span class="badge badge-success">Paid</span>
                                                    @elseif($booking->payment_status === 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @else
                                                        <span class="badge badge-warning">
                                                            {{ ucfirst($booking->payment_status ?? 'Pending') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($booking->booking_status === 'confirmed')
                                                        <span class="badge badge-success">Confirmed</span>
                                                    @elseif($booking->booking_status === 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @elseif($booking->booking_status === 'cancelled')
                                                        <span class="badge badge-secondary">Cancelled</span>
                                                    @else
                                                        <span class="badge badge-warning">
                                                            {{ ucfirst($booking->booking_status ?? 'Pending') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y h:i A') : '-' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.sanatan-event-bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#table-1')) {
        $('#table-1').DataTable({
            ordering: true,
            order: [],
            paging: false,
            info: false,
            searching: true
        });
    }
});
</script>
@endpush
