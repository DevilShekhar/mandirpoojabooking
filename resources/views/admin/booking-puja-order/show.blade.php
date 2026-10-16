@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Puja Booking Details</h4>
            <p class="text-muted mb-0">
                Booking: {{ $pujaBooking->booking_number ?: ('#' . $pujaBooking->id) }}
            </p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary">Back to Bookings</a>
        </div>
    </div>
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
                    @php
                    $bookingStatus = strtolower($pujaBooking->booking_status ?? 'pending');
                    $bookingBadge = $bookingStatus === 'confirmed' ? 'success' : ($bookingStatus === 'cancelled' ?
                    'danger' : ($bookingStatus === 'completed' ? 'primary' : 'warning'));
                    @endphp
                    <div><span
                            class="badge bg-{{ $bookingBadge }}">{{ ucfirst($pujaBooking->booking_status ?? 'pending') }}</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Payment Status</strong>
                    @php
                    $paymentStatus = strtolower($pujaBooking->payment_status ?? 'pending');
                    $paymentBadge = $paymentStatus === 'paid' ? 'success' : ($paymentStatus === 'failed' ? 'danger' :
                    'warning');
                    @endphp
                    <div><span
                            class="badge bg-{{ $paymentBadge }}">{{ ucfirst($pujaBooking->payment_status ?? 'pending') }}</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3"><strong>Created</strong>
                    <div>{{ $pujaBooking->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Last Updated</strong>
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
                <div class="col-md-4 mb-3"><strong>Name</strong>
                    <div>{{ $pujaBooking->customer_name ?: ($pujaBooking->user?->name ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Email</strong>
                    <div>{{ $pujaBooking->customer_email ?: ($pujaBooking->user?->email ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Mobile</strong>
                    <div>
                        {{ $pujaBooking->customer_mobile ?: ($pujaBooking->user?->phone ?? $pujaBooking->user?->mobile ?? '-') }}
                    </div>
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
                <div class="col-md-4 mb-3"><strong>Temple</strong>
                    <div>{{ $pujaBooking->temple?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Deity</strong>
                    <div>{{ $pujaBooking->deity?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Puja</strong>
                    <div>{{ $pujaBooking->puja?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3"><strong>Package</strong>
                    <div>{{ $pujaBooking->package_name ?: ($pujaBooking->pujaPackage?->name ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Schedule</strong>
                    <div>
                        @if($pujaBooking->pujaSchedule)
                        {{ $pujaBooking->pujaSchedule->name ?? $pujaBooking->pujaSchedule->puja_date ?? $pujaBooking->pujaSchedule->date ?? '-' }}
                        @else
                        -
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-3"><strong>Person Count</strong>
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
                @foreach ([
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
            @if($bookingPayments->isNotEmpty())
            <table class="table table-bordered table-striped">
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
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->razorpay_order_id ?? '-' }}</td>
                        <td>{{ $payment->razorpay_payment_id ?? '-' }}</td>
                        <td>₹{{ number_format(((float) ($payment->amount_paise ?? 0)) / 100, 2) }}</td>
                        <td>{{ $payment->currency ?? 'INR' }}</td>
                        <td>
                            @php
                            $status = strtolower($payment->status ?? 'pending');
                            $badge = in_array($status, ['paid', 'success', 'captured', 'completed']) ? 'success' :
                            ($status === 'failed' ? 'danger' : 'warning');
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td>{{ $payment->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                        <td>{{ $payment->error_description ?? $payment->error_reason ?? $payment->error_code ?? '-' }}
                        </td>
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
                        <div>{{ $pujaBooking->pandit->phone ?? '-' }}</div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No Pandit assigned to this booking.</p>
            @endif
        </div>
    </div>
    <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary mb-4">Back to Bookings</a>
</div>
@endsection