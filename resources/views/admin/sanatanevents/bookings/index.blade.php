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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>My Bookings</h4>
                        <span class="badge badge-primary">Total: {{ $bookings->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($bookings->isEmpty())
                            <div class="alert alert-info mb-0">
                                No Sanatan event bookings found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
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
                                            @php
                                                $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                                $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $booking->customer_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $booking->customer_email ?? '-' }}</small><br>
                                                    <small class="text-muted">{{ $booking->customer_phone ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $booking->event_name ?? 'N/A' }}</strong>
                                                    @if(!empty($booking->event_description))
                                                        <br><small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($booking->event_description), 80) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $booking->event_time ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A') : '-' }}</td>
                                                <td>{{ $booking->currency ?? 'INR' }} {{ number_format((float) $booking->amount, 2) }}</td>
                                                <td>
                                                    @if(in_array($paymentStatus, ['paid', 'success', 'captured', 'completed']))
                                                        <span class="badge badge-success">Paid</span>
                                                    @elseif($paymentStatus === 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @elseif($paymentStatus === 'refunded')
                                                        <span class="badge badge-warning">Refunded</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ ucwords(str_replace('_', ' ', $paymentStatus)) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bookingStatus === 'confirmed')
                                                        <span class="badge badge-success">Confirmed</span>
                                                    @elseif($bookingStatus === 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @elseif($bookingStatus === 'cancelled')
                                                        <span class="badge badge-secondary">Cancelled</span>
                                                    @elseif($bookingStatus === 'completed')
                                                        <span class="badge badge-primary">Completed</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ ucwords(str_replace('_', ' ', $bookingStatus)) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y h:i A') : '-' }}</td>
                                                <td>
                                                    <a href="{{ route('customer.sanatan-event-bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            paging: true,
            searching: true,
            info: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 9]
                }
            ],
            language: {
                search: "Search:",
                searchPlaceholder: "Search event bookings...",
                emptyTable: "No event bookings found.",
                zeroRecords: "No matching bookings found."
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: @json(session('success')),
            icon: 'success',
            confirmButtonColor: '#6777ef',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: @json(session('error')),
            icon: 'error',
            confirmButtonColor: '#6777ef',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endpush