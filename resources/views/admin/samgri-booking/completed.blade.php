@can('samgri-booking-completed.index')
    @extends('admin.layouts.app')
    @section('title', 'Completed Samagri Bookings')
    @section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Completed Samagri Bookings</h4>
                            <div class="card-header-action">
                                <span class="badge badge-success">
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
                                            <th>Customer Name</th>                                           
                                            <th>Temple</th>
                                            <th>Samagri Items</th>
                                            <th>Booking Status</th>
                                            <th>OTP Verification</th>
                                            <th>Verified By</th>
                                            <th>Verified Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($samgriBookings as $booking)
                                        @php
                                        $products = $booking->selected_products ?? [];
                                        if (is_string($products)) {
                                        $products = json_decode($products, true) ?? [];
                                        }
                                        $products = is_array($products) ? $products : [];
                                        $bookingStatus = strtolower(
                                        $booking->booking_status ?? 'pending'
                                        );
                                        $verification = $booking->samgriVerification;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>
                                                    {{ $booking->booking_number }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    Puja : {{ $booking->puja->name ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                <strong>
                                                    <i class="fas fa-user text-primary"></i>
                                                    {{ $booking->customer_name ?? '-' }}
                                                </strong>
                                            </td>                                           
                                            <td>
                                                {{ $booking->temple->name ?? '-' }}
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
                                                @elseif($bookingStatus == 'cancelled')
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
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i>
                                                    Verified
                                                </span>
                                            </td>
                                            <td>
                                                {{ $verification?->verifiedBy?->name ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $verification?->verified_at?->format('d M Y h:i A') ?? '-' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('completed-samgri-booking.show', $booking->id) }}"
                                                    class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
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
            </div>
        </div>
    </section>
    @endsection
    @push('scripts')
        <script>
        $(document).ready(function() {
            $('#table-1').DataTable();
        });
    </script>
    @endpush
@else
    @php 
        abort(403); 
    @endphp
@endcan