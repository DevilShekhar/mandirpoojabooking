@extends('admin.layouts.app')

@section('title', 'Customer Cancellation Requests')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Customer Cancellation Requests</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">Cancellation Requests</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Cancellation Requests</h4>
                            <div class="card-header-action">
                                <span class="badge badge-primary">
                                    Total: {{ $cancellations->total() }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Booking Number</th>
                                            <th>Customer</th>
                                            <th>Puja</th>
                                            <th>Cancellation Reason</th>
                                            <th>Request Status</th>
                                            <th>Refund Status</th>
                                            <th>Refund Amount</th>
                                            <th>Requested On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cancellations as $cancellation)
                                            @php
                                                $booking = $cancellation->booking;
                                                $customer = $cancellation->customer ?? $booking?->user;

                                                $statusClass = match (strtolower((string) $cancellation->status)) {
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    default => 'secondary',
                                                };

                                                $refundClass = match (strtolower((string) $cancellation->refund_status)) {
                                                    'processed', 'completed' => 'success',
                                                    'processing', 'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    'not_required' => 'secondary',
                                                    default => 'light',
                                                };
                                            @endphp

                                            <tr>
                                                <td>{{ $cancellations->firstItem() + $loop->index }}</td>
                                                <td>
                                                    @if ($booking)
                                                        <strong>{{ $booking->booking_number ?? 'N/A' }}</strong>
                                                    @else
                                                        <span class="text-muted">Booking unavailable</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $customer?->name ?? $booking?->customer_name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $customer?->email ?? $booking?->customer_email ?? 'N/A' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    {{ $booking?->puja?->name ?? $booking?->package_name ?? 'N/A' }}
                                                    @if ($booking?->temple?->name)
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $booking->temple->name }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($cancellation->cancellation_reason, 50) }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $statusClass }}">
                                                        {{ ucfirst($cancellation->status ?? 'unknown') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $refundClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $cancellation->refund_status ?? 'unknown')) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    ₹{{ number_format((float) ($cancellation->refund_amount ?? 0), 2) }}
                                                </td>
                                                <td>
                                                    {{ $cancellation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.customer-cancel-booking.show', $cancellation->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="py-4">
                                                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                                        <p class="mb-0">No cancellation requests found.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($cancellations->hasPages())
                                <div class="card-footer text-right">
                                    {{ $cancellations->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection