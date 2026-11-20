
@can('all-tickit-not-reached.index')
@extends('admin.layouts.app')
@section('title', 'Not Reached Ticket Bookings')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Not Reached Ticket Bookings</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Booking Reference</th>
                                        <th>Order ID</th>
                                        <th>Temple</th>
                                        <th>Booking Date</th>
                                        <th>Total Members</th>
                                        <th>Total Amount</th>
                                        <th>Payment Status</th>
                                        <th>Booking Status</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $booking->booking_reference ?? 'N/A' }}
                                                </strong>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $booking->razorpay_order_id ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                {{ $booking->temple_name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $booking->booking_date
                                                    ? \Illuminate\Support\Carbon::parse($booking->booking_date)->format('d-m-Y')
                                                    : 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $booking->total_members ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>
                                                    ₹{{ number_format((float) $booking->total_amount, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                @if($booking->payment_status == 'paid')
                                                    <span class="badge badge-success">
                                                        {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->booking_status ?? 'N/A')) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $booking->updated_at
                                                    ? \Illuminate\Support\Carbon::parse($booking->updated_at)->format('d-m-Y h:i A')
                                                    : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.my-tickit-booking.show', ['orderId' => $booking->razorpay_order_id]) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="View Booking Members">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">
                                                No not reached ticket bookings found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
        $('#table-1').DataTable({
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 10]
                }
            ]
        });
    });
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan