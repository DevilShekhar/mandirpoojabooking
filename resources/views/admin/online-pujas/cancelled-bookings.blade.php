@can('online-puja.cancelbooking')
@extends('admin.layouts.app')
@section('title', 'Cancelled Online Puja Bookings')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Cancelled Online Puja Bookings</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('online-pujas.index') }}">Online Pujas</a>
            </div>
            <div class="breadcrumb-item active">Cancelled Bookings</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Cancelled Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ $cancelledBookings->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Booking Amount</h4>
                        </div>
                        <div class="card-body">
                            ₹ {{ number_format($totalCancelledAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Refund Amount</h4>
                        </div>
                        <div class="card-body">
                            ₹ {{ number_format($totalRefundAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Cancelled Bookings List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="table-cancelled">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="8%">ID</th>
                                        <th>Puja Title</th>
                                        <th>Cancelled By User</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Refund Amount</th>
                                        <th>Cancellation Reason</th>
                                        <th>Cancelled At</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cancelledBookings as $key => $booking)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span class="badge badge-secondary">#{{ $booking->id }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $booking->puja->title ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-dark">
                                                    {{ $booking->cancelledBy->name ?? $booking->customer_name ?? 'N/A' }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    Email: {{ $booking->cancelledBy->email ?? $booking->customer_email ?? 'N/A' }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    Phone: {{ $booking->cancelledBy->phone ?? $booking->customer_phone ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                {{ $booking->package->name ?? $booking->package_name ?? '-' }}
                                            </td>
                                            <td>₹ {{ number_format($booking->amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="text-success font-weight-bold">
                                                    ₹ {{ number_format($booking->refund_amount ?? 0, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" title="{{ $booking->cancellation_reason }}">
                                                    {{ Str::limit($booking->cancellation_reason ?? 'No reason provided', 40) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $booking->cancelled_at ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d-m-Y h:i A') : '-' }}
                                            </td>                                         
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <div class="py-3">
                                                    <i class="fas fa-check-circle fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0">No Cancelled Bookings Found.</p>
                                                </div>
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
        $('#table-cancelled').DataTable({
            responsive: true,
            autoWidth: false
        });
    });
</script>
@endpush
@else
    @php abort(403); @endphp
@endcan