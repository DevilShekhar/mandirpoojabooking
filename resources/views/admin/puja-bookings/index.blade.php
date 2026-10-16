@can('puja-bookings.index')
@extends('admin.layouts.app')

@section('title', 'Booking Puja Order')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Puja Order</h4>
                        <div class="card-header-action">
                            <a href="{{ route('booking-puja-order.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Booking
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Booking Number</th>
                                        <th>Customer</th>
                                        <th>Temple</th>
                                        <th>Puja</th>
                                        <th>Package</th>
                                        <th>Schedule Date</th>
                                        <th>Schedule Time</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                                            </td>
                                            <td>
                                                <strong>{{ $booking->booking_number ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                {{ $booking->customer_name ?? $booking->user?->name ?? '-' }}
                                                @if($booking->customer_mobile)
                                                    <br>
                                                    <small>{{ $booking->customer_mobile }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $booking->temple?->name ?? '-' }}</td>
                                            <td>{{ $booking->puja?->name ?? '-' }}</td>
                                            <td>{{ $booking->package_name ?? $booking->pujaPackage?->name ?? '-' }}</td>
                                            <td>
                                                @if($booking->schedule_date)
                                                    {{ \Illuminate\Support\Carbon::parse($booking->schedule_date)->format('d M Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->schedule_time)
                                                    {{ \Illuminate\Support\Carbon::parse($booking->schedule_time)->format('h:i A') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>₹{{ number_format((float) $booking->total_amount, 2) }}</td>
                                            <td>
                                                @if($booking->payment_status === 'paid')
                                                    <span class="badge badge-success badge-shadow">Paid</span>
                                                @elseif($booking->payment_status === 'failed')
                                                    <span class="badge badge-danger badge-shadow">Failed</span>
                                                @elseif($booking->payment_status === 'refunded')
                                                    <span class="badge badge-warning badge-shadow">Refunded</span>
                                                @else
                                                    <span class="badge badge-secondary badge-shadow">
                                                        {{ ucwords(str_replace('_', ' ', $booking->payment_status ?? 'pending')) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->booking_status === 'confirmed')
                                                    <span class="badge badge-success badge-shadow">Confirmed</span>
                                                @elseif($booking->booking_status === 'cancelled')
                                                    <span class="badge badge-danger badge-shadow">Cancelled</span>
                                                @elseif($booking->booking_status === 'completed')
                                                    <span class="badge badge-primary badge-shadow">Completed</span>
                                                @else
                                                    <span class="badge badge-warning badge-shadow">
                                                        {{ ucwords(str_replace('_', ' ', $booking->booking_status ?? 'pending')) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('booking-puja-order.show', $booking->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">No booking orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $bookings->links() }}
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
    $(document).ready(function() {
        $('#table-1').DataTable({
            ordering: true,
            searching: true,
            paging: false,
            info: false,
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 11]
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