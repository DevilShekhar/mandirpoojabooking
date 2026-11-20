
@can('all-tickit-booking.index')
@extends('admin.layouts.app')
@section('title', 'Completed Ticket Bookings')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Completed Ticket Bookings</h4>
                    </div>
                    <div class="card-body">
                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        {{-- Error Message --}}
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
                                        <th>Temple</th>
                                        <th>Booking Date</th>
                                        <th>Total Members</th>
                                        <th>Total Amount</th>
                                        <th>Payment Status</th>
                                        <th>Booking Status</th>
                                        <th>Completed At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td class="text-center">
                                                {{ $bookings->firstItem() + $loop->index }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $booking->booking_reference ?? 'N/A' }}
                                                </strong>
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
                                                <span class="badge badge-success">
                                                    {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    {{ ucfirst($booking->booking_status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $booking->completed_at
                                                    ? \Illuminate\Support\Carbon::parse($booking->completed_at)->format('d-m-Y h:i A')
                                                    : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.my-tickit-booking.show',['orderId' => $booking->razorpay_order_id]) }}"
                                                    class="btn btn-info btn-sm"  title="View Booking Members">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                No completed ticket bookings found.
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
                    targets: [0, 9]
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