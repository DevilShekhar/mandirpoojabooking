@extends('admin.layouts.app')

@section('title', 'Completed Puja Bookings')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h4 class="mb-1">Completed Puja Bookings</h4>
            <p class="mb-0">View all successfully completed Puja bookings.</p>
        </div>

        <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary mt-2">
            <i class="fas fa-arrow-left mr-1"></i> Back to Bookings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">
                <i class="fas fa-check-circle mr-2"></i>
                Completed Bookings
                <span class="badge badge-success ml-2">
                    {{ $bookings->count() }}
                </span>
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="completed-bookings-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking Number</th>
                            <th>Customer</th>
                            <th>Temple</th>
                            <th>Schedule Date</th>
                            <th>Amount</th>
                            <th>Completed By</th>
                            <th>Completed At</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>
                                        {{ $booking->booking_number ?: '#'.$booking->id }}
                                    </strong>
                                    <span class="d-block">
                                        {{ $booking->puja?->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        {{ $booking->customer_name ?: ($booking->user?->name ?? 'N/A') }}
                                    </strong>

                                    @if($booking->customer_mobile)
                                        <small class="d-block text-muted">
                                            {{ $booking->customer_mobile }}
                                        </small>
                                    @endif
                                </td>

                                <td>{{ $booking->temple?->name ?? 'N/A' }}</td>

                                <td>
                                    {{ $booking->schedule_date
                                        ? $booking->schedule_date->format('d M Y')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) ($booking->total_amount ?? 0), 2) }}
                                </td>

                                <td>{{ $booking->completedBy?->name ?? 'N/A' }}</td>

                                <td>
                                    {{ $booking->completed_at
                                        ? $booking->completed_at->format('d M Y, h:i A')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Completed
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('booking-puja-order.show', $booking->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#completed-bookings-table').DataTable({
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,
            autoWidth: false,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],

            order: [[7, 'desc']],

            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 9]
                }
            ],

            language: {
                search: 'Search:',
                searchPlaceholder: 'Search booking or customer...',
                lengthMenu: 'Show _MENU_ bookings',
                info: 'Showing _START_ to _END_ of _TOTAL_ bookings',
                infoEmpty: 'Showing 0 to 0 of 0 bookings',
                zeroRecords: 'No matching completed bookings found.',
                emptyTable: 'No completed Puja bookings found.',
                paginate: {
                    previous: 'Previous',
                    next: 'Next'
                }
            }
        });
    });
</script>
@endpush