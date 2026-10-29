@extends('admin.layouts.app')
@section('title', 'Puja Booking Details')
@section('content')
@php
    $currentUser = auth()->user();
    $isCustomer = $currentUser && $currentUser->hasRole('Customer') && (int) $currentUser->id === (int) $pujaBooking->user_id;
    $bookingStatus = strtolower($pujaBooking->booking_status ?? 'pending');
    $paymentStatus = strtolower($pujaBooking->payment_status ?? 'pending');
    $cancellationRequest = $isCustomer
        ? \App\Models\CustomerBookingCancellation::where('puja_booking_id', $pujaBooking->id)
            ->where('customer_id', $currentUser->id)->first()
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
    $selectedProducts = $selectedProducts ?? collect();
    if (is_array($selectedProducts)) {
        $selectedProducts = collect($selectedProducts);
    }
@endphp
<div class="container-fluid puja-details-page">
    <div class="puja-page-heading d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <div class="puja-heading-title">
                <span class="puja-heading-icon"><i class="fas fa-om"></i></span>
                <div><h4 class="mb-1">Puja Booking Details</h4>
                    <p class="mb-0">
                        Booking: {{ $pujaBooking->booking_number ?: '#' . $pujaBooking->id }}
                    </p>
                </div>
            </div>
        </div>
        <a href="{{ route('booking-puja-order.index') }}" class="btn puja-back-btn mt-2 mt-md-0">
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
        <div class="card mb-4 puja-cancellation-card">
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
                        <p class="mb-0">
                            {{ $cancellationRequest->cancellation_reason }}
                        </p>
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
                            <div>
                                ₹{{ number_format((float) $cancellationRequest->refund_amount, 2) }}
                            </div>
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
                            Your cancellation request was rejected.
                            Please contact support if you need assistance.
                        </div>
                    @elseif($refundStatus === 'processing')
                        <div class="alert alert-info mb-0">
                            Your refund is being processed. Please wait for confirmation.
                        </div>
                    @elseif($refundStatus === 'failed')
                        <div class="alert alert-danger mb-0">
                            The refund could not be confirmed.
                            Please contact support for assistance.
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
                            Submit a cancellation request with your reason.
                            Your booking will not be cancelled until the request is reviewed
                            and any applicable refund is confirmed.
                        </p>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelBookingModal">
                            <i class="fas fa-times-circle"></i> Cancel Booking
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="card mb-4 puja-booking-card">
        <div class="card-header">
            <h5 class="mb-0">Booking Information</h5>
        </div>
        <div class="card-body">
            <div class="puja-booking-summary">
                <div class="puja-booking-reference"><span class="puja-field-label">Booking Reference</span><strong>{{ $pujaBooking->booking_number ?: ('#' . $pujaBooking->id) }}</strong><small><i class="far fa-calendar-alt"></i> Booked {{ $pujaBooking->created_at?->format('d M Y, h:i A') ?? '-' }}</small></div>
                <div class="puja-status-panel"><div><span class="puja-status-dot"></span><div><small>Booking Status</small><strong>{{ ucfirst($bookingStatus) }}</strong></div></div><div><span class="puja-status-dot payment-dot"></span><div><small>Payment Status</small><strong>{{ ucfirst($paymentStatus) }}</strong></div></div></div>
                <div class="puja-timeline"><div class="puja-timeline-step {{ in_array($bookingStatus, ['confirmed','completed']) ? 'is-done' : 'is-current' }}"><span><i class="fas fa-check"></i></span><strong>Booked</strong><small>{{ $pujaBooking->created_at?->format('d M Y') ?? '-' }}</small></div><div class="puja-timeline-step {{ in_array($paymentStatus, ['paid','success','captured']) ? 'is-done' : '' }}"><span><i class="fas fa-check"></i></span><strong>Payment {{ in_array($paymentStatus, ['paid','success','captured']) ? 'Confirmed' : 'Pending' }}</strong><small>{{ $pujaBooking->updated_at?->format('d M Y') ?? '-' }}</small></div><div class="puja-timeline-step {{ $bookingStatus === 'completed' ? 'is-done' : '' }}"><span><i class="fas fa-check"></i></span><strong>{{ $bookingStatus === 'completed' ? 'Puja Completed' : 'Puja Scheduled' }}</strong><small>{{ $pujaBooking->schedule_date?->format('d M Y') ?? '-' }}</small></div></div>
            </div>
            <div class="row puja-booking-extra">
                <div class="col-md-4 mb-3">
                    <strong>Booking Number</strong>
                    <div>{{ $pujaBooking->booking_number ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Booking Status</strong>
                    <div>
                        <span class="badge badge-{{ $bookingBadge }}">
                            {{ ucfirst($bookingStatus) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Payment Status</strong>
                    <div>
                        <span class="badge badge-{{ $paymentBadge }}">
                            {{ ucfirst($paymentStatus) }}
                        </span>
                    </div>
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
    <div class="card mb-4 puja-customer-card">
        <div class="card-header">
            <h5 class="mb-0">Customer Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-user"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Name</strong>
                        <div>{{ $pujaBooking->customer_name ?: ($pujaBooking->user?->name ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-envelope"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Email</strong>
                        <div>{{ $pujaBooking->customer_email ?: ($pujaBooking->user?->email ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-phone"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Mobile</strong>
                        <div>{{ $pujaBooking->customer_mobile ?: ($pujaBooking->user?->phone ?? $pujaBooking->user?->mobile ?? '-') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 puja-info-card">
        <div class="card-header">
            <h5 class="mb-0">Puja Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-place-of-worship"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Temple</strong>
                        <div>{{ $pujaBooking->temple?->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-om"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Deity</strong>
                        <div>{{ $pujaBooking->deity?->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-fire-alt"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Puja</strong>
                        <div>{{ $pujaBooking->puja?->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-box-open"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Package</strong>
                        <div>{{ $pujaBooking->package_name ?: ($pujaBooking->pujaPackage?->name ?? '-') }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="far fa-calendar-alt"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Schedule Date</strong>
                        <div>{{ $pujaBooking->schedule_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="far fa-clock"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Schedule Time</strong>
                        <div>{{ $pujaBooking->schedule_time ? \Illuminate\Support\Carbon::parse($pujaBooking->schedule_time)->format('h:i A') : '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 puja-detail-item">
                    <span class="puja-detail-icon"><i class="fas fa-users"></i></span>
                    <div class="puja-detail-copy">
                        <strong>Person Count</strong>
                        <div>{{ $pujaBooking->person_count ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 puja-products-card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-shopping-basket"></i> Selected Products
            </h5>
        </div>
        <div class="card-body table-responsive">
            @if($selectedProducts->isNotEmpty())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $productsTotal = 0;
                        @endphp
                        @foreach($selectedProducts as $index => $product)
                            @php
                                $productName = data_get($product, 'product_name')
                                    ?? data_get($product, 'name')
                                    ?? data_get($product, 'title')
                                    ?? 'Product';
                                $productId = data_get($product, 'product_id')
                                    ?? data_get($product, 'id')
                                    ?? '-';
                                $quantity = max(0, (float) (
                                    data_get($product, 'quantity')
                                    ?? data_get($product, 'qty')
                                    ?? 1
                                ));
                                $unit = data_get($product, 'unit') ?? data_get($product, 'unit_name') ?? '-';
                                $unitPrice = (float) (
                                    data_get($product, 'unit_price')
                                    ?? data_get($product, 'price')
                                    ?? data_get($product, 'amount')
                                    ?? 0
                                );
                                $lineTotal = (float) (
                                    data_get($product, 'total')
                                    ?? data_get($product, 'subtotal')
                                    ?? data_get($product, 'line_total')
                                    ?? ($unitPrice * $quantity)
                                );
                                $productsTotal += $lineTotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $productName }}</td>
                                <td>{{ $productId }}</td>
                                <td>{{ $quantity }}</td>
                                <td>{{ $unit }}</td>
                                <td>₹{{ number_format($unitPrice, 2) }}</td>
                                <td>₹{{ number_format($lineTotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Selected Products Total</th>
                            <th>₹{{ number_format($productsTotal, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="alert alert-light border mb-0">
                    <i class="fas fa-info-circle"></i>
                    No selected products were found for this booking.
                </div>
            @endif
        </div>
    </div>
    <div class="card mb-4 puja-amount-card">
        <div class="card-header">
            <h5 class="mb-0">Amount Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach([
                    'Base Amount' => $pujaBooking->base_amount,
                    'Discount Amount' => $pujaBooking->discount_amount,                
                    'Samgri Amount' => $pujaBooking->addon_amount,       
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
    <div class="card mb-4 puja-payment-card">
        <div class="card-header">
            <h5 class="mb-0">Payment Details</h5>
        </div>
        <div class="card-body table-responsive">
            @if(isset($bookingPayments) && $bookingPayments->isNotEmpty())
                @php
                    $hasFailureDetails = $bookingPayments->contains(function ($payment) {
                        return strtolower($payment->status ?? '') === 'failed' && (!empty($payment->error_description) || !empty($payment->error_reason) || !empty($payment->error_code));
                    });
                @endphp
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
                            @if($hasFailureDetails)
                                <th>Failure Details</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingPayments as $index => $payment)
                            @php
                                $status = strtolower($payment->status ?? 'pending');
                                $paymentRowBadge = in_array(
                                    $status,
                                    ['paid', 'success', 'captured', 'completed']
                                )
                                    ? 'success'
                                    : ($status === 'refunded'
                                        ? 'info'
                                        : ($status === 'failed' ? 'danger' : 'warning'));
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment->razorpay_order_id ?? '-' }}</td>
                                <td>{{ $payment->razorpay_payment_id ?? '-' }}</td>
                                <td>
                                    ₹{{ number_format(((float) ($payment->amount_paise ?? 0)) / 100, 2) }}
                                </td>
                                <td>{{ $payment->currency ?? 'INR' }}</td>
                                <td>
                                    <span class="badge badge-{{ $paymentRowBadge }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                                @if($hasFailureDetails)
                                    <td>
                                        @if($status === 'failed' && !empty($failureDetail))
                                            {{ $failureDetail }}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">
                    No Razorpay payments are linked to this booking.
                </p>
            @endif
        </div>
    </div>
    <div class="card mb-4 puja-pandit-card">
        <div class="card-header">
            <h5 class="mb-0">Assigned Pandit Details</h5>
        </div>
        <div class="card-body">
            @if($pujaBooking->pandit)
                <div class="row">
                    <div class="col-md-4 mb-3 puja-detail-item">
                        <span class="puja-detail-icon"><i class="fas fa-user-tie"></i></span>
                        <div class="puja-detail-copy">
                            <strong>Pandit Name</strong>
                            <div>{{ $pujaBooking->pandit->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 puja-detail-item">
                        <span class="puja-detail-icon"><i class="fas fa-envelope"></i></span>
                        <div class="puja-detail-copy">
                            <strong>Email</strong>
                            <div>{{ $pujaBooking->pandit->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 puja-detail-item">
                        <span class="puja-detail-icon"><i class="fas fa-phone"></i></span>
                        <div class="puja-detail-copy">
                            <strong>Mobile</strong>
                            <div>{{ $pujaBooking->pandit->phone ?? $pujaBooking->pandit->mobile ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">
                    No Pandit assigned to this booking.
                </p>
            @endif
        </div>
    </div>
    <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>
</div>
@if($canRequestCancellation)
    <div class="modal fade"  id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('customer-cancel-booking.store', $pujaBooking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelBookingModalLabel">
                            Request Booking Cancellation
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            Your booking will not be cancelled immediately.
                            The administration must review your request.
                        </div>
                        <div class="form-group">
                            <label for="cancellation_reason">
                                Cancellation Reason <span class="text-danger">*</span>
                            </label>
                            <textarea name="cancellation_reason" id="cancellation_reason"
                                      class="form-control @error('cancellation_reason') is-invalid @enderror"
                                      rows="4"  minlength="5"  maxlength="2000" placeholder="Please explain why you want to cancel this booking..."
                                      required>{{ old('cancellation_reason') }}</textarea>
                            @error('cancellation_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Submit Cancellation Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<style>
.puja-details-page{--puja-navy:#10264a;--puja-orange:#e88708;--puja-muted:#64748b;color:#172b4d;padding-bottom:28px}.puja-details-page .puja-page-heading{padding:8px 2px 18px;border-bottom:1px solid #e8edf5;gap:16px}.puja-heading-title{display:flex;align-items:center;gap:12px}.puja-heading-title h4{font-size:25px;font-weight:800;color:#fff;letter-spacing:-.4px}.puja-heading-title p{font-size:13px;color:#fff}.puja-heading-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;background:#fff0d8;color:var(--puja-orange);font-size:22px}.puja-back-btn{background:linear-gradient(135deg,#f5a623,#dc7900);border:0;color:white!important;border-radius:9px;padding:10px 16px;font-weight:700;box-shadow:0 4px 12px #e887082b}.puja-details-page>.card{border:1px solid #e8edf5;border-radius:13px;overflow:hidden;box-shadow:0 5px 18px rgba(16,38,74,.055);background:#fff;margin-bottom:18px!important}.puja-details-page .card-header{background:linear-gradient(100deg,#fff8ed,#fff);border-bottom:1px solid #f3e8d8;padding:14px 18px}.puja-details-page .card-header h5{font-size:15px;font-weight:800;color:var(--puja-navy);display:flex;align-items:center;gap:9px}.puja-details-page .card-header h5 i{color:var(--puja-orange)}.puja-details-page .card-body{padding:18px}.puja-details-page strong{color:#243b61}.puja-details-page .card-body .row>div>strong,.puja-details-page .card-body .row>div>div>strong{font-size:11px;text-transform:uppercase;letter-spacing:.45px;color:#73829a;display:block;margin-bottom:5px}.puja-details-page .card-body .row>div>div{font-size:13px;color:#142b50;overflow-wrap:anywhere}.puja-details-page .badge{padding:6px 10px;border-radius:20px;font-weight:700;font-size:11px}.puja-booking-card{background:linear-gradient(115deg,#fff 0%,#fffdf9 100%)}.puja-booking-summary{display:grid;grid-template-columns:1.05fr .8fr 1.5fr;gap:20px;align-items:center}.puja-booking-reference{display:flex;flex-direction:column;gap:8px}.puja-field-label{font-size:11px;color:#71819b;font-weight:700;text-transform:uppercase;letter-spacing:.6px}.puja-booking-reference strong{font-size:20px;line-height:1.3;overflow-wrap:anywhere;color:var(--puja-navy)}.puja-booking-reference small{font-size:12px;color:#667893}.puja-booking-reference small i{color:var(--puja-orange);margin-right:5px}.puja-status-panel{display:grid;gap:12px;padding:14px;background:#effbf5;border:1px solid #d2f1df;border-radius:10px}.puja-status-panel>div{display:flex;align-items:center;gap:10px}.puja-status-dot{width:12px;height:12px;flex:none;border-radius:50%;background:#16a34a;box-shadow:0 0 0 4px #d7f6e4}.puja-status-dot.payment-dot{background:#15803d}.puja-status-panel small{display:block;font-size:15px;font-weight:800;color:#64748b}.puja-status-panel strong{font-size:13px;color:#08783b}.puja-timeline{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));position:relative;gap:8px}.puja-timeline:before{content:'';position:absolute;top:13px;left:16%;right:16%;height:2px;background:#dce5ef}.puja-timeline-step{position:relative;z-index:1;display:flex;align-items:center;flex-direction:column;text-align:center;gap:6px}.puja-timeline-step>span{width:27px;height:27px;display:grid;place-items:center;border-radius:50%;background:#e6edf5;color:#8290a5;border:3px solid white;font-size:10px}.puja-timeline-step.is-done>span{background:#16a34a;color:#fff}.puja-timeline-step.is-current>span{background:#fff0d8;color:#d97706;border:2px solid #f4b54d}.puja-timeline-step strong{font-size:11px;line-height:1.3}.puja-timeline-step small{font-size:10px;color:#70809a}.puja-booking-extra{border-top:1px solid #eef1f6;margin-top:16px;padding-top:14px}.puja-details-page .puja-customer-card,.puja-details-page .puja-info-card{height:100%}.puja-details-page .puja-products-card table,.puja-details-page .puja-payment-card table{font-size:12px;margin-bottom:0}.puja-details-page .table thead th{background:#f3f6fa;color:#e40505;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.4px;border-bottom:1px solid #e2e8f0;padding:12px 10px;white-space:nowrap}.puja-details-page .table td{padding:11px 10px;vertical-align:middle;border-color:#edf1f6;color:#233a5d}.puja-details-page .table-striped tbody tr:nth-of-type(odd){background:#fbfcfe}.puja-details-page .table tfoot{background:#fff0d8;color:#8f350a}.puja-details-page .table tfoot th{padding:13px 10px;border-color:#f8e3c1}.puja-details-page .puja-amount-card .card-body>.row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin:0}.puja-details-page .puja-amount-card .card-body>.row>[class*="col-"]{max-width:none;width:auto;padding:12px;border:1px solid #edf1f6;border-radius:9px;margin:0!important;background:#fff}.puja-details-page .puja-amount-card .card-body>.row>[class*="col-"]:last-child{background:#fff1dc;border-color:#ffe0b0}.puja-details-page .puja-amount-card .card-body>.row>div>strong{font-size:10px}.puja-details-page .puja-amount-card .card-body>.row>div>div{font-size:16px;font-weight:800;color:#0f3974}.puja-details-page .puja-amount-card .card-body>.row>[class*="col-"]:last-child>div{font-size:20px;color:#9a3412}.puja-details-page .puja-products-card .card-body,.puja-details-page .puja-payment-card .card-body{overflow-x:auto}.puja-details-page .puja-pandit-card .card-body{background:linear-gradient(110deg,#fff,#fffdf8)}.puja-details-page .puja-cancellation-card{border-color:#f6d7d7}.puja-details-page .puja-cancellation-card .card-header{background:#fff6f6;border-color:#f8dddd}.puja-details-page .modal-content{border:0;border-radius:14px;overflow:hidden}.puja-details-page .btn{font-weight:600}.puja-details-page .alert{border-radius:10px}@media(min-width:992px){.puja-details-page{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:0 18px}.puja-details-page>.puja-page-heading,.puja-details-page>.alert,.puja-details-page>.puja-cancellation-card,.puja-details-page>.puja-booking-card,.puja-details-page>.puja-products-card,.puja-details-page>.puja-pandit-card,.puja-details-page> a.btn{grid-column:1/-1}.puja-details-page>.puja-customer-card{grid-column:span 5}.puja-details-page>.puja-info-card{grid-column:span 7}.puja-details-page>.puja-amount-card{grid-column:span 6}.puja-details-page>.puja-payment-card{grid-column:span 6}.puja-details-page>.puja-customer-card,.puja-details-page>.puja-info-card{align-self:stretch}.puja-details-page>.puja-customer-card .card-body,.puja-details-page>.puja-info-card .card-body{height:calc(100% - 49px)}}@media(max-width:991.98px){.puja-booking-summary{grid-template-columns:1fr 1fr}.puja-timeline{grid-column:1/-1}.puja-details-page .puja-amount-card .card-body>.row{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575.98px){.puja-heading-title h4{font-size:20px}.puja-details-page .card-body{padding:13px}.puja-booking-summary{grid-template-columns:1fr}.puja-timeline{grid-column:auto;gap:3px}.puja-timeline-step strong{font-size:10px}.puja-details-page .puja-amount-card .card-body>.row{grid-template-columns:1fr 1fr}.puja-details-page .table{min-width:650px}.puja-details-page .puja-page-heading{align-items:flex-start!important}}

/* Exact reference-layout refinements */
.puja-details-page{max-width:100%;padding:18px 20px 30px;background:#f5f8fc;grid-template-columns:repeat(12,minmax(0,1fr));column-gap:16px}
.puja-details-page .puja-page-heading{grid-column:1/-1;padding:20px;margin-bottom:0!important;border-bottom:0;align-items:center;background: #ea0505;border-radius: 10px;}
.puja-page-heading{position:relative;padding-top:22px!important}
.puja-heading-title{gap:10px}.puja-heading-icon{width:30px;height:30px;background:transparent;font-size:23px;border-radius:0;color:#e88708}
.puja-heading-title h4{font-size:23px;letter-spacing:-.5px;margin-bottom:2px!important}.puja-heading-title p{font-size:11px;margin:0!important}
.puja-back-btn{font-size:12px;padding:10px 15px}
.puja-details-page>.puja-booking-card{grid-column:1/-1;position:relative;background:linear-gradient(110deg,#fff 0%,#fffdf8 58%,#fff 100%)}
.puja-details-page>.puja-booking-card:before{content:'🛕';position:absolute;left:0;top:0;bottom:0;width:145px;display:flex;align-items:center;justify-content:center;font-size:65px;background:radial-gradient(circle at 55% 60%,#fff0c7 0,#fff8e7 52%,#fff 74%);border-right:1px solid #fff0d8}
.puja-details-page>.puja-booking-card .card-header{display:none}
.puja-details-page>.puja-booking-card .card-body{padding:20px 18px 20px 165px}
.puja-details-page .puja-booking-extra{display:none!important}
.puja-booking-summary{grid-template-columns:minmax(230px,1.1fr) minmax(160px,.75fr) minmax(300px,1.4fr);gap:18px}
.puja-booking-reference strong{font-size:19px}.puja-status-panel{padding:12px}.puja-timeline-step strong{font-size:11px}
.puja-details-page>.puja-customer-card{grid-column:span 5}.puja-details-page>.puja-info-card{grid-column:span 7}
.puja-details-page>.puja-products-card{grid-column:1/-1}
.puja-details-page>.puja-amount-card{grid-column:span 6}.puja-details-page>.puja-payment-card{grid-column:span 6}
.puja-details-page>.puja-pandit-card{grid-column:1/-1}
.puja-details-page .card-header{padding:12px 16px}.puja-details-page .card-header h5{font-size:14px}
.puja-details-page .puja-customer-card .card-header h5:before{content:'\f2bd'}
.puja-details-page .puja-info-card .card-header h5:before{content:'\f06d'}
.puja-details-page .puja-amount-card .card-header h5:before{content:'\f555'}
.puja-details-page .puja-payment-card .card-header h5:before{content:'\f09d'}
.puja-details-page .puja-pandit-card .card-header h5:before{content:'\f2bd'}
.puja-details-page .puja-customer-card .card-header h5:before,.puja-details-page .puja-info-card .card-header h5:before,.puja-details-page .puja-amount-card .card-header h5:before,.puja-details-page .puja-payment-card .card-header h5:before,.puja-details-page .puja-pandit-card .card-header h5:before{font-family:'Font Awesome 5 Free';font-weight:900;color:#e88708;font-size:17px;margin-right:2px}
.puja-details-page .puja-products-card .card-header h5:before{content:'\f07a';font-family:'Font Awesome 5 Free';font-weight:900;color:#e88708;font-size:17px}
.puja-details-page .puja-products-card .card-header h5 i{display:none}
.puja-details-page .puja-customer-card .card-body .row>div,.puja-details-page .puja-info-card .card-body .row>div{border-right:1px solid #edf1f6}
.puja-details-page .puja-amount-card .card-body>.row{grid-template-columns:repeat(4,minmax(0,1fr));gap:8px}
.puja-details-page .puja-amount-card .card-body>.row>[class*="col-"]{padding:10px 8px}
.puja-details-page .puja-payment-card .table{font-size:10px}
.puja-details-page .puja-payment-card .table td{padding:9px 7px}
@media(max-width:1199.98px){.puja-booking-summary{grid-template-columns:minmax(190px,1fr) minmax(150px,.8fr)}.puja-timeline{grid-column:1/-1}.puja-details-page>.puja-customer-card,.puja-details-page>.puja-info-card,.puja-details-page>.puja-amount-card,.puja-details-page>.puja-payment-card{grid-column:1/-1}}
@media(max-width:575.98px){.puja-details-page{padding:14px 10px}.puja-page-heading:before{left:10px}.puja-details-page>.puja-booking-card:before{display:none}.puja-details-page>.puja-booking-card .card-body{padding:14px}.puja-booking-summary{grid-template-columns:1fr}.puja-timeline{grid-column:auto}.puja-details-page .puja-amount-card .card-body>.row{grid-template-columns:repeat(2,minmax(0,1fr))}}

/* Field-level icons for Customer, Puja and Assigned Pandit details */
.puja-details-page .puja-detail-item{
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding-top:5px;
    padding-bottom:8px;
}
.puja-details-page .puja-detail-icon{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    flex:0 0 34px;
    width:34px;
    height:34px;
    border-radius:10px;
    color:#e88708;
    background:#fff2df;
    border:1px solid #ffe6c2;
    font-size:14px;
}
.puja-details-page .puja-detail-copy{
    min-width:0;
    flex:1;
}
.puja-details-page .puja-detail-copy>strong{
    display:block;
    margin:0 0 5px;
    color:#73829a;
    font-size:10px;
    line-height:1.3;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.45px;
}
.puja-details-page .puja-detail-copy>div{
    color:#142b50;
    font-size:12px;
    line-height:1.45;
    overflow-wrap:anywhere;
}
.puja-details-page .puja-customer-card .card-body .row>div,
.puja-details-page .puja-info-card .card-body .row>div{
    border-right:1px solid #edf1f6;
}
.puja-details-page .puja-customer-card .card-body .row>div:last-child,
.puja-details-page .puja-info-card .card-body .row>div:nth-child(3n){
    border-right:0;
}
@media(max-width:575.98px){
    .puja-details-page .puja-detail-icon{flex-basis:30px;width:30px;height:30px}
}


</style>

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