@can('puja-bookings.index')
@extends('admin.layouts.app')
@section('title', 'Successful Booking Orders')
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Successful Booking Orders</h4>
                            <div class="card-header-action">
                                <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> All Booking Orders
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
                                            <th>Total Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td><strong>{{ $booking->booking_number ?? '-' }}</strong></td>
                                                <td>
                                                    {{ $booking->customer_name ?: ($booking->user?->name ?? '-') }}
                                                    @if($booking->customer_mobile ?: $booking->user?->phone)
                                                        <br>
                                                        <small>{{ $booking->customer_mobile ?: $booking->user?->phone }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $booking->temple?->name ?? '-' }}</td>
                                                <td>{{ $booking->puja?->name ?? '-' }}</td>
                                                <td>{{ $booking->package_name ?: ($booking->pujaPackage?->name ?? '-') }}</td>
                                                <td>
                                                    {{ $booking->schedule_date ? \Illuminate\Support\Carbon::parse($booking->schedule_date)->format('d-m-Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $booking->schedule_time ? \Illuminate\Support\Carbon::parse($booking->schedule_time)->format('h:i A') : '-' }}
                                                </td>
                                                <td>₹{{ number_format((float) ($booking->total_amount ?? 0), 2) }}</td>
                                                <td>
                                                    <span class="badge badge-success badge-shadow">Paid</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $status = strtolower($booking->booking_status ?? 'pending');
                                                    @endphp
                                                    @if($status === 'confirmed')
                                                        <span class="badge badge-success badge-shadow">Confirmed</span>
                                                    @elseif($status === 'completed')
                                                        <span class="badge badge-primary badge-shadow">Completed</span>
                                                    @elseif($status === 'cancelled')
                                                        <span class="badge badge-danger badge-shadow">Cancelled</span>
                                                    @else
                                                        <span class="badge badge-warning badge-shadow">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('booking-puja-order.show', $booking->id) }}" class="btn btn-info btn-sm mr-1" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">No successful booking orders found.</td>
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
                        targets: [0, 11]
                    }
                ]
            });
        });
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
    </script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan