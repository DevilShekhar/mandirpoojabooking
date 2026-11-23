@can('my-online-pujabooking.index')
@extends('admin.layouts.app')
@section('title', 'My Online Puja Bookings')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>My Online Puja Bookings</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Order ID</th>
                                        <th>Puja Name</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Puja Start Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                    @php
                                        $statusKey = strtolower($booking->booking_status ?? 'pending');
                                        $badgeClass = in_array($statusKey, ['confirmed', 'completed']) 
                                            ? 'badge-success' 
                                            : (in_array($statusKey, ['cancelled', 'rejected']) ? 'badge-danger' : 'badge-warning');
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <code>{{ $booking->order_number ?? $booking->id }}</code>
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $booking->puja->title
                                                    ?? $booking->puja->name
                                                    ?? '-' }}
                                            </strong>
                                        </td>
                                        <td>
                                            {{ ucfirst($booking->package_name ?? '-') }}
                                        </td>
                                        <td>
                                            {{ $booking->currency ?? 'INR' }}
                                            {{ number_format((float) $booking->amount, 2) }}
                                        </td>
                                        <td>
                                            @if($booking->puja && $booking->puja->puja_datetime)
                                                {{ \Carbon\Carbon::parse(
                                                    $booking->puja->puja_datetime
                                                )->format('d M Y h:i A') }}
                                                @if($booking->puja->puja_day)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $booking->puja->puja_day }}
                                                    </small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}" style="font-size: 11px; padding: 5px 10px; border-radius: 20px;">
                                                {{ ucfirst($booking->booking_status ?? 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route(
                                                'admin.my-online-pujabooking.show',
                                                $booking->id
                                            ) }}"
                                                class="btn btn-primary btn-sm"
                                                title="View Details">
                                                <i class="fas fa-eye mr-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle mr-1"></i> No online puja bookings found.
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
                    targets: [0, 7]
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