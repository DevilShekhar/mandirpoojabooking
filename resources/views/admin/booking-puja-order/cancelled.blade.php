@can('puja-bookings.index')
    @extends('admin.layouts.app')

    @section('title', 'Cancelled Booking Orders')

    @section('content')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Cancelled Booking Orders</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('booking-puja-order.index') }}" class="btn btn-primary">
                                        <i class="fas fa-list"></i> All Booking Orders
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
                                    <table class="table table-striped" id="cancelled-bookings-table">
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
                                                        {{ $booking->customer_name ?: ($booking->user?->name ?? '-') }}
                                                        @if($booking->customer_mobile)
                                                            <br>
                                                            <small>{{ $booking->customer_mobile }}</small>
                                                        @elseif($booking->user?->phone)
                                                            <br>
                                                            <small>{{ $booking->user->phone }}</small>
                                                        @endif
                                                    </td>

                                                    <td>{{ $booking->temple?->name ?? '-' }}</td>

                                                    <td>{{ $booking->puja?->name ?? '-' }}</td>

                                                    <td>
                                                        {{ $booking->package_name ?: ($booking->pujaPackage?->name ?? '-') }}
                                                    </td>

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

                                                    <td>
                                                        ₹{{ number_format((float) ($booking->total_amount ?? 0), 2) }}
                                                    </td>

                                                    <td>
                                                        @php
                                                            $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                                        @endphp

                                                        @if($paymentStatus === 'paid')
                                                            <span class="badge badge-success badge-shadow">Paid</span>
                                                        @elseif($paymentStatus === 'failed')
                                                            <span class="badge badge-danger badge-shadow">Failed</span>
                                                        @elseif($paymentStatus === 'refunded')
                                                            <span class="badge badge-warning badge-shadow">Refunded</span>
                                                        @else
                                                            <span class="badge badge-secondary badge-shadow">
                                                                {{ ucfirst($paymentStatus) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="badge badge-danger badge-shadow">Cancelled</span>
                                                    </td>

                                                    <td class="text-center">
                                                        <a href="{{ route('booking-puja-order.show', $booking->id) }}"
                                                           class="btn btn-info btn-sm"
                                                           title="View Booking">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center">
                                                        No cancelled booking orders found.
                                                    </td>
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
            $(document).ready(function () {
                if ($.fn.DataTable.isDataTable('#cancelled-bookings-table')) {
                    $('#cancelled-bookings-table').DataTable().destroy();
                }

                $('#cancelled-bookings-table').DataTable({
                    ordering: true,
                    searching: true,
                    paging: false,
                    info: false,
                    scrollX: true,
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 11]
                    }]
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