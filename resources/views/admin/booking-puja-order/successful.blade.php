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
                                    <a href="{{ route('booking-puja-order.successful') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> All Booking Orders
                                    </a>
                                </div>
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
                                    <table class="table table-striped" id="successful-bookings-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Booking Number</th>
                                                <th>Customer</th>
                                                <th>Temple</th>
                                                <th>Puja</th>
                                                <th>Package</th>
                                                <th>Schedule</th>
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
                                                    <strong>{{ $booking->booking_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $booking->customer_name }}
                                                    @if($booking->customer_mobile)
                                                    <br>
                                                    <small>{{ $booking->customer_mobile }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $booking->temple?->name ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $booking->puja?->name ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $booking->package_name ?? $booking->pujaPackage?->name ?? '-' }}
                                                </td>
                                                <td>
                                                    @if($booking->pujaSchedule)
                                                    {{ $booking->pujaSchedule->puja_date }}
                                                    <br>
                                                    <small>
                                                        {{ $booking->pujaSchedule->start_time }}
                                                        -
                                                        {{ $booking->pujaSchedule->end_time }}
                                                    </small>
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>
                                                    ₹{{ number_format($booking->total_amount, 2) }}
                                                </td>
                                                <td>
                                                    <div class="badge badge-success badge-shadow">Successful / Paid</div>
                                                </td>
                                                <td>
                                                    @if($booking->booking_status == 'confirmed')
                                                    <div class="badge badge-success badge-shadow">Confirmed</div>
                                                    @elseif($booking->booking_status == 'cancelled')
                                                    <div class="badge badge-danger badge-shadow">Cancelled</div>
                                                    @elseif($booking->booking_status == 'completed')
                                                    <div class="badge badge-primary badge-shadow">Completed</div>
                                                    @else
                                                    <div class="badge badge-warning badge-shadow">
                                                        {{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <a href="{{ route('booking-puja-order.show', $booking) }}"
                                                            class="btn btn-info btn-sm mr-1" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>


                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    No successful booking orders found.
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
    $(document).ready(function() {
        $('#successful-bookings-table').DataTable({
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 10]
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