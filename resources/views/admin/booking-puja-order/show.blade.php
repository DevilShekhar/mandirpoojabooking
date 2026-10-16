
@extends('admin.layouts.app')

@section('title', 'Puja Booking Details')

@section('content')
@php
    $currentUser = auth()->user();
    $isCustomer = $currentUser && $currentUser->hasRole('Customer') && (int) $currentUser->id === (int) $pujaBooking->user_id;
    $bookingStatus = strtolower($pujaBooking->booking_status ?? 'pending');
    $paymentStatus = strtolower($pujaBooking->payment_status ?? 'pending');

    $cancellationRequest = $isCustomer
        ? \App\Models\CustomerBookingCancellation::where('puja_booking_id', $pujaBooking->id)->where('customer_id', $currentUser->id)->first()
        : null;

    $canRequestCancellation = $isCustomer && !in_array($bookingStatus, ['cancelled', 'completed']) && !$cancellationRequest;

    $bookingBadge = match ($bookingStatus) {
        'confirmed' => 'success',
        'cancelled', 'failed' => 'danger',
        'completed' => 'primary',
        default => 'warning',
    };

    $paymentBadge = match ($paymentStatus) {
        'paid', 'success', 'captured' => 'success',
        'failed', 'refunded' => 'danger',
        default => 'warning',
    };
@endphp

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Puja Booking Details</h4>
            <p class="text-muted mb-0">Booking: {{ $pujaBooking->booking_number ?: '#' . $pujaBooking->id }}</p>
        </div>
        <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary mt-2 mt-md-0">
            <i class="fas fa-arrow-left"></i> Back to Bookings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($isCustomer)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Booking Cancellation</h5>
            </div>
            <div class="card-body">
                @if($cancellationRequest)
                    @php
                        $cancellationStatus = strtolower($cancellationRequest->status ?? 'pending');
                        $refundStatus = strtolower($cancellationRequest->refund_status ?? 'not_required');

                        $cancellationBadge = match ($cancellationStatus) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'warning',
                        };

                        $refundBadge = match ($refundStatus) {
                            'processed' => 'success',
                            'failed' => 'danger',
                            default => 'warning',
                        };
                    @endphp

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Request Status</strong>
                            <div>
                                <span class="badge badge-{{ $cancellationBadge }}">
                                    {{ ucfirst($cancellationStatus) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Refund Status</strong>
                            <div>
                                <span class="badge badge-{{ $refundBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $refundStatus)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Cancellation Reason</strong>
                        <p class="mb-0">{{ $cancellationRequest->cancellation_reason }}</p>
                    </div>

                    @if($cancellationRequest->review_notes)
                        <div class="mb-3">
                            <strong>Admin Review Notes</strong>
                            <p class="mb-0">{{ $cancellationRequest->review_notes }}</p>
                        </div>
                    @endif

                    @if((float) $cancellationRequest->refund_amount > 0)
                        <div class="mb-3">
                            <strong>Refund Amount</strong>
                            <div>₹{{ number_format((float) $cancellationRequest->refund_amount, 2) }}</div>
                        </div>
                    @endif

                    @if($cancellationRequest->razorpay_refund_id)
                        <div class="mb-3">
                            <strong>Razorpay Refund ID</strong>
                            <div>{{ $cancellationRequest->razorpay_refund_id }}</div>
                        </div>
                    @endif

                    @if($cancellationStatus === 'pending')
                        <div class="alert alert-warning mb-0">
                            Your cancellation request is awaiting admin review.
                        </div>
                    @elseif($cancellationStatus === 'rejected')
                        <div class="alert alert-danger mb-0">
                            Your cancellation request was rejected. Please contact support if you need assistance.
                        </div>
                    @elseif($refundStatus === 'processing')
                        <div class="alert alert-info mb-0">
                            Your refund is being processed. Please wait for confirmation.
                        </div>
                    @elseif($refundStatus === 'failed')
                        <div class="alert alert-danger mb-0">
                            The refund could not be confirmed. Please contact support for assistance.
                        </div>
                    @elseif($refundStatus === 'processed')
                        <div class="alert alert-success mb-0">
                            Your refund has been processed.
                        </div>
                    @endif
                @elseif(in_array($bookingStatus, ['cancelled', 'completed']))
                    <p class="text-muted mb-0">
                        This booking cannot be cancelled because it is {{ $bookingStatus }}.
                    </p>
                @else
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <p class="text-muted mb-2 mb-md-0">
                            Submit a cancellation request with your reason. Your booking will not be cancelled until the request is reviewed and any applicable refund is confirmed.
                        </p>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelBookingModal">
                            <i class="fas fa-times-circle"></i> Cancel Booking
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Booking Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Booking Number</strong>
                    <div>{{ $pujaBooking->booking_number ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Booking Status</strong>
                    <div><span class="badge badge-{{ $bookingBadge }}">{{ ucfirst($bookingStatus) }}</span></div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Payment Status</strong>
                    <div><span class="badge badge-{{ $paymentBadge }}">{{ ucfirst($paymentStatus) }}</span></div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Created</strong>
                    <div>{{ $pujaBooking->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Last Updated</strong>
                    <div>{{ $pujaBooking->updated_at?->format('d M Y, h:i A') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Customer Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Name</strong>
                    <div>{{ $pujaBooking->customer_name ?: ($pujaBooking->user?->name ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Email</strong>
                    <div>{{ $pujaBooking->customer_email ?: ($pujaBooking->user?->email ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Mobile</strong>
                    <div>{{ $pujaBooking->customer_mobile ?: ($pujaBooking->user?->phone ?? $pujaBooking->user?->mobile ?? '-') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Puja Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Temple</strong>
                    <div>{{ $pujaBooking->temple?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Deity</strong>
                    <div>{{ $pujaBooking->deity?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Puja</strong>
                    <div>{{ $pujaBooking->puja?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Package</strong>
                    <div>{{ $pujaBooking->package_name ?: ($pujaBooking->pujaPackage?->name ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Schedule Date</strong>
                    <div>{{ $pujaBooking->schedule_date ? \Illuminate\Support\Carbon::parse($pujaBooking->schedule_date)->format('d M Y') : '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Schedule Time</strong>
                    <div>{{ $pujaBooking->schedule_time ? \Illuminate\Support\Carbon::parse($pujaBooking->schedule_time)->format('h:i A') : '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Person Count</strong>
                    <div>{{ $pujaBooking->person_count ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Amount Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach([
                    'Base Amount' => $pujaBooking->base_amount,
                    'Discount Amount' => $pujaBooking->discount_amount,
                    'Coupon Discount' => $pujaBooking->coupon_discount,
                    'Add-on Amount' => $pujaBooking->addon_amount,
                    'Shipping Amount' => $pujaBooking->shipping_amount,
                    'Tax Amount' => $pujaBooking->tax_amount,
                    'Total Amount' => $pujaBooking->total_amount,
                ] as $label => $amount)
                    <div class="col-md-3 col-sm-6 mb-3">
                        <strong>{{ $label }}</strong>
                        <div>₹{{ number_format((float) ($amount ?? 0), 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Payment Details</h5>
        </div>
        <div class="card-body table-responsive">
            @if(isset($bookingPayments) && $bookingPayments->isNotEmpty())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Failure Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingPayments as $index => $payment)
                            @php
                                $status = strtolower($payment->status ?? 'pending');
                                $paymentRowBadge = in_array($status, ['paid', 'success', 'captured', 'completed'])
                                    ? 'success'
                                    : ($status === 'refunded' ? 'info' : ($status === 'failed' ? 'danger' : 'warning'));
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment->razorpay_order_id ?? '-' }}</td>
                                <td>{{ $payment->razorpay_payment_id ?? '-' }}</td>
                                <td>₹{{ number_format(((float) ($payment->amount_paise ?? 0)) / 100, 2) }}</td>
                                <td>{{ $payment->currency ?? 'INR' }}</td>
                                <td><span class="badge badge-{{ $paymentRowBadge }}">{{ ucfirst($status) }}</span></td>
                                <td>{{ $payment->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                                <td>{{ $payment->error_description ?? $payment->error_reason ?? $payment->error_code ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">No Razorpay payments are linked to this booking.</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Assigned Pandit Details</h5>
        </div>
        <div class="card-body">
            @if($pujaBooking->pandit)
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Pandit Name</strong>
                        <div>{{ $pujaBooking->pandit->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Email</strong>
                        <div>{{ $pujaBooking->pandit->email ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Mobile</strong>
                        <div>{{ $pujaBooking->pandit->phone ?? $pujaBooking->pandit->mobile ?? '-' }}</div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No Pandit assigned to this booking.</p>
            @endif
        </div>
    </div>

    <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>
</div>

@if($canRequestCancellation)
    <div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('customer-cancel-booking.store', $pujaBooking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelBookingModalLabel">Request Booking Cancellation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            Your booking will not be cancelled immediately. The administration must review your request.
                        </div>
                        <div class="form-group">
                            <label for="cancellation_reason">Cancellation Reason <span class="text-danger">*</span></label>
                            <textarea name="cancellation_reason" id="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="4" minlength="5" maxlength="2000" placeholder="Please explain why you want to cancel this booking..." required>{{ old('cancellation_reason') }}</textarea>
                            @error('cancellation_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Submit Cancellation Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        @if($errors->has('cancellation_reason') && $canRequestCancellation)
            $('#cancelBookingModal').modal('show');
        @endif
    });
</script>
@endpush
