@extends('admin.layouts.app')

@section('title', 'My Cancelled Orders')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>My Cancelled Orders</h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Number</th>
                                        <th>Puja</th>
                                        <th>Booking Amount</th>
                                        <th>Cancellation Reason</th>
                                        <th>Request Status</th>
                                        <th>Refund Amount</th>
                                        <th>Refund Status</th>
                                        <th>Requested On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cancellations as $cancellation)
                                        <tr>
                                            <td>{{ $cancellations->firstItem() + $loop->index }}</td>
                                            <td>{{ $cancellation->booking?->booking_number ?? 'N/A' }}</td>
                                            <td>{{ $cancellation->booking?->puja?->name ?? $cancellation->booking?->package_name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format((float) ($cancellation->booking?->total_amount ?? 0), 2) }}</td>
                                            <td>{{ $cancellation->cancellation_reason ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $status = strtolower($cancellation->status ?? 'pending');
                                                @endphp
                                                <span class="badge badge-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>₹{{ number_format((float) ($cancellation->refund_amount ?? 0), 2) }}</td>
                                            <td>
                                                @php
                                                    $refundStatus = strtolower($cancellation->refund_status ?? 'not_required');
                                                @endphp
                                                <span class="badge badge-{{ in_array($refundStatus, ['processed', 'completed', 'refunded']) ? 'success' : ($refundStatus === 'failed' ? 'danger' : 'warning') }}">
                                                    {{ ucwords(str_replace('_', ' ', $refundStatus)) }}
                                                </span>
                                            </td>
                                            <td>{{ $cancellation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No cancellation requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($cancellations->hasPages())
                            <div class="d-flex justify-content-end">
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