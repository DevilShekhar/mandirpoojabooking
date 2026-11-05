@extends('admin.layouts.app')

@section('title', 'Cancelled Ticket Bookings')

@section('content')
@can('my-tickit-booking.index')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Cancelled Ticket Bookings</h4>
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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
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
                                                    {{ $booking->total_members }}
                                                </span>
                                            </td>

                                            <td>
                                                <strong>
                                                    ₹{{ number_format((float) $booking->total_amount, 2) }}
                                                </strong>
                                            </td>

                                            <td>
                                                @php
                                                    $paymentStatus = strtolower($booking->payment_status ?? '');
                                                @endphp

                                                @if($paymentStatus === 'refunded')
                                                    <span class="badge badge-success">Refunded</span>
                                                @elseif($paymentStatus === 'partially_refunded')
                                                    <span class="badge badge-warning">Partially Refunded</span>
                                                @elseif($paymentStatus === 'paid')
                                                    <span class="badge badge-info">Paid</span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge badge-danger">
                                                    Cancelled
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.my-tickit-booking.show', ['orderId' => $booking->razorpay_order_id]) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="View Cancelled Booking Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No cancelled ticket bookings found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($bookings->hasPages())
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
@else
    @php abort(403); @endphp
@endcan
@endsection