 @extends('admin.layouts.app')

@section('title', 'My Cancelled Orders')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-purple"><i class="fas fa-calendar-times"></i></div>
                    <div class="card-wrap"><div class="padding-20"><div class="text-right">
                        <h3 class="font-light mb-0">{{ $cancellationCount ?? 0 }}</h3>
                        <span class="text-muted">Total Requests</span>
                    </div></div></div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-orange"><i class="fas fa-clock"></i></div>
                    <div class="card-wrap"><div class="padding-20"><div class="text-right">
                        <h3 class="font-light mb-0">{{ $pendingCancellationCount ?? 0 }}</h3>
                        <span class="text-muted">Pending Requests</span>
                    </div></div></div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-green"><i class="fas fa-check-circle"></i></div>
                    <div class="card-wrap"><div class="padding-20"><div class="text-right">
                        <h3 class="font-light mb-0">{{ $approvedCancellationCount ?? 0 }}</h3>
                        <span class="text-muted">Approved Requests</span>
                    </div></div></div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-red"><i class="fas fa-times-circle"></i></div>
                    <div class="card-wrap"><div class="padding-20"><div class="text-right">
                        <h3 class="font-light mb-0">{{ $rejectedCancellationCount ?? 0 }}</h3>
                        <span class="text-muted">Rejected Requests</span>
                    </div></div></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-calendar-times text-primary mr-2"></i>My Cancelled Orders</h4>
                        <span class="badge badge-primary">{{ $cancellationCount ?? 0 }} Requests</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Number</th>
                                        <th>Puja</th>
                                        <th>Temple</th>
                                        <th>Booking Amount</th>
                                        <th>Cancellation Reason</th>
                                        <th>Request Status</th>
                                        <th>Refund Amount</th>
                                        <th>Refund Status</th>
                                        <th>Requested On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cancellations as $cancellation)
                                        @php
                                            $status = strtolower($cancellation->status ?? 'pending');
                                            $refundStatus = strtolower($cancellation->refund_status ?? 'not_required');
                                        @endphp
                                        <tr>
                                            <td>{{ $cancellations->firstItem() + $loop->index }}</td>
                                            <td><strong>{{ $cancellation->booking?->booking_number ?? 'N/A' }}</strong></td>
                                            <td><i class="fas fa-praying-hands text-primary mr-1"></i>{{ $cancellation->booking?->puja?->name ?? $cancellation->booking?->pujaPackage?->name ?? $cancellation->booking?->package_name ?? 'N/A' }}</td>
                                            <td>{{ $cancellation->booking?->temple?->name ?? 'N/A' }}</td>
                                            <td><strong>₹{{ number_format((float) ($cancellation->booking?->total_amount ?? 0), 2) }}</strong></td>
                                            <td>{{ $cancellation->cancellation_reason ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($status) }}</span>
                                            </td>
                                            <td>₹{{ number_format((float) ($cancellation->refund_amount ?? 0), 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ in_array($refundStatus, ['processed', 'completed', 'refunded']) ? 'success' : ($refundStatus === 'failed' ? 'danger' : 'warning') }}">{{ ucwords(str_replace('_', ' ', $refundStatus)) }}</span>
                                            </td>
                                            <td>{{ $cancellation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <h6 class="font-weight-bold">No Cancellation Requests Found</h6>
                                                <p class="text-muted mb-0">You have not submitted any cancellation requests yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($cancellations->hasPages())
                            <div class="d-flex justify-content-end mt-3">
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