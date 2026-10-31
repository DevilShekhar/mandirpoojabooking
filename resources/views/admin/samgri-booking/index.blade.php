@can('samgri-booking.index')
    @extends('admin.layouts.app')
    @section('title', 'Samagri Bookings')
    @section('content')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Samagri Bookings</h4>
                                <div class="card-header-action">
                                    <span class="badge badge-primary">
                                        Total Bookings: {{ $samgriBookings->count() }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Booking Number</th>
                                                <th>Booking Date</th>
                                                <th>Temple</th>
                                                <th>Customer Name</th>                                                                                     
                                                <th>Samagri Items</th>
                                                <th>Booking Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($samgriBookings as $booking)
                                                @php
                                                    $products = $booking->selected_products ?? [];

                                                    if (is_string($products)) {
                                                        $products = json_decode($products, true) ?? [];
                                                    }

                                                    $products = is_array($products) ? $products : [];

                                                    $bookingStatus = strtolower(
                                                        $booking->booking_status ?? 'pending'
                                                    );
                                                @endphp
                                                <tr>                                                   
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>
                                                            {{ $booking->booking_number ?? '-' }}
                                                        </strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            Puja:   {{ $booking->puja->name ?? '-' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            <i class="fas fa-calendar-alt text-primary"></i>
                                                            {{ $booking->schedule_date
                                                                ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y')
                                                                : '-' }}
                                                        </strong>
                                                        @if($booking->schedule_time)
                                                           
                                                            <small class="text-muted">
                                                                <i class="far fa-clock"></i>
                                                                {{ \Carbon\Carbon::parse($booking->schedule_time)->format('h:i A') }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $booking->temple->name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            <i class="fas fa-user text-primary"></i>
                                                            {{ $booking->customer_name ?? '-' }}
                                                        </strong>
                                                    </td>                                                   
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ count($products) }} Items
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if(in_array($bookingStatus, ['confirmed', 'completed']))
                                                            <span class="badge badge-success">
                                                                {{ ucfirst($bookingStatus) }}
                                                            </span>
                                                        @elseif($bookingStatus === 'cancelled')
                                                            <span class="badge badge-danger">
                                                                Cancelled
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning">
                                                                {{ ucfirst($bookingStatus) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('samgri-booking.show', $booking->id) }}"
                                                            class="btn btn-sm btn-primary"
                                                            title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <i class="fas fa-box-open text-muted"></i>
                                                        No upcoming Samagri bookings found.
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
            $(document).ready(function() {
                $('#table-1').DataTable({
                    order: []
                });
            });
        </script>
    @endpush
@else
    @php
        abort(403);
    @endphp
@endcan