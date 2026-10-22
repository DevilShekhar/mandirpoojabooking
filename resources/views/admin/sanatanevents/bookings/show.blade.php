@extends('admin.layouts.app')

@section('title', 'Booking Details')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Booking Details</h1>
        <div class="section-header-button ml-auto">
            <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Information</h4>
                        <div class="card-header-action">
                            @if($booking->booking_status === 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->booking_status === 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @elseif($booking->booking_status === 'cancelled')
                                <span class="badge badge-secondary">Cancelled</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($booking->booking_status ?? 'Pending') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="mb-3">Event Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Booking ID</th>
                                        <td>{{ $booking->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Name</th>
                                        <td>{{ $booking->event_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Description</th>
                                        <td>
                                            @if(!empty($booking->event_description))
                                                {!! nl2br(e(strip_tags($booking->event_description))) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Event Date</th>
                                        <td>{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Time</th>
                                        <td>{{ $booking->event_time ? \Carbon\Carbon::parse($booking->event_time)->format('h:i A') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Created By</th>
                                        <td>{{ $booking->event_creator_name ?? ($booking->event_created_by ?? '-') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Customer Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Customer Name</th>
                                        <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $booking->customer_email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $booking->customer_phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer ID</th>
                                        <td>{{ $booking->user_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Booking Created By ID</th>
                                        <td>{{ $booking->created_by ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Payment and Refund Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Amount</th>
                                        <td>{{ $booking->currency ?? 'INR' }} {{ number_format((float) $booking->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td>
                                            @if($booking->payment_status === 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($booking->payment_status === 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-warning">{{ ucfirst($booking->payment_status ?? 'Pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Razorpay Order ID</th>
                                        <td>{{ $booking->razorpay_order_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Razorpay Payment ID</th>
                                        <td>{{ $booking->razorpay_payment_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Refund Status</th>
                                        <td>
                                            @php
                                                $refundStatus = $booking->refund_status ?? 'not_requested';
                                            @endphp

                                            @if($refundStatus === 'processed')
                                                <span class="badge badge-success">Processed</span>
                                            @elseif(in_array($refundStatus, ['pending', 'processing', 'created'], true))
                                                <span class="badge badge-warning">{{ ucfirst($refundStatus) }}</span>
                                            @elseif($refundStatus === 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @elseif($refundStatus === 'not_requested')
                                                <span class="badge badge-secondary">Not Requested</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $refundStatus)) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Refund Amount</th>
                                        <td>
                                            @if($booking->refund_amount !== null)
                                                {{ $booking->currency ?? 'INR' }} {{ number_format((float) $booking->refund_amount, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Razorpay Refund ID</th>
                                        <td>{{ $booking->razorpay_refund_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cancellation Reason</th>
                                        <td>{{ $booking->cancellation_reason ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cancelled By</th>
                                        <td>
                                            @if(!empty($booking->cancelled_at))
                                                {{ $booking->cancelled_by_name ?? 'User ID: ' . ($booking->cancelled_by ?? 'N/A') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cancelled At</th>
                                        <td>{{ $booking->cancelled_at ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Refund Processed At</th>
                                        <td>{{ $booking->refunded_at ? \Carbon\Carbon::parse($booking->refunded_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Booking Created At</th>
                                        <td>{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated At</th>
                                        <td>{{ $booking->updated_at ? \Carbon\Carbon::parse($booking->updated_at)->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @php
                            $currentUser = auth()->user();
                            $canCancelBooking = $currentUser && (
                                ($currentUser->hasRole('Customer') && (int) $currentUser->id === (int) $booking->user_id) ||
                                ($currentUser->hasRole('Event Creator') && (int) $currentUser->id === (int) $booking->event_created_by)
                            );
                            $canShowCancelForm = $canCancelBooking
                                && $booking->booking_status === 'confirmed'
                                && $booking->payment_status === 'paid'
                                && empty($booking->cancelled_at)
                                && ($booking->refund_status ?? 'not_requested') === 'not_requested'
                                && empty($booking->razorpay_refund_id);
                        @endphp

                        @if($canShowCancelForm)
                            <div class="card mt-4 border-danger">
                                <div class="card-header">
                                    <h4 class="text-danger mb-0">Cancel Booking</h4>
                                </div>
                                <div class="card-body">
                                    <p>Enter the reason for cancellation. The booking will be marked as cancelled and a refund request will be submitted to Razorpay.</p>

                                    <form id="cancelBookingForm" action="{{ route('customer.sanatan-event-bookings.cancel', $booking->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="cancellation_reason">
                                                Cancellation Reason <span class="text-danger">*</span>
                                            </label>
                                            <textarea
                                                name="cancellation_reason"
                                                id="cancellation_reason"
                                                class="form-control @error('cancellation_reason') is-invalid @enderror"
                                                rows="4"
                                                minlength="5"
                                                maxlength="1000"
                                                placeholder="Please enter your reason for cancelling this booking"
                                                required
                                            >{{ old('cancellation_reason') }}</textarea>
                                            @error('cancellation_reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" id="cancelBookingButton" class="btn btn-danger">
                                            <i class="fas fa-times-circle"></i> Cancel Booking &amp; Request Refund
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($booking->booking_status === 'cancelled')
                            <div class="alert alert-secondary mt-4">
                                <i class="fas fa-info-circle"></i> This booking has been cancelled.
                                @if(!empty($booking->cancellation_reason))
                                    <div class="mt-2">
                                        <strong>Reason:</strong> {{ $booking->cancellation_reason }}
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <strong>Cancelled By:</strong>
                                    {{ $booking->cancelled_by_name ?? ($booking->cancelled_by ? 'User ID: ' . $booking->cancelled_by : '-') }}
                                </div>
                                <div class="mt-2">
                                    <strong>Cancelled At:</strong>
                                    {{ $booking->cancelled_at ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d-m-Y h:i A') : '-' }}
                                </div>
                                <div class="mt-2">
                                    <strong>Refund Status:</strong>
                                    {{ ucfirst(str_replace('_', ' ', $booking->refund_status ?? 'not_requested')) }}
                                </div>
                                @if(!empty($booking->razorpay_refund_id))
                                    <div class="mt-2">
                                        <strong>Refund ID:</strong> {{ $booking->razorpay_refund_id }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(in_array($booking->refund_status ?? '', ['pending', 'processing', 'created'], true) && !empty($booking->razorpay_refund_id))
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-sync-alt"></i>
                                Refund status is checked with Razorpay whenever this page is opened. Refresh this page to check again.
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('customer.sanatan-event-bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cancelForm = document.getElementById('cancelBookingForm');

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            confirmButtonText: 'OK'
        });
    @endif

    @if($errors->has('booking'))
        Swal.fire({
            icon: 'error',
            title: 'Unable to Process',
            text: @json($errors->first('booking')),
            confirmButtonText: 'OK'
        });
    @endif

    @if($errors->has('cancellation_reason'))
        Swal.fire({
            icon: 'error',
            title: 'Invalid Cancellation Reason',
            text: @json($errors->first('cancellation_reason')),
            confirmButtonText: 'OK'
        });
    @endif

    if (cancelForm) {
        cancelForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const reasonField = document.getElementById('cancellation_reason');
            const reason = reasonField.value.trim();

            if (reason.length < 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cancellation Reason Required',
                    text: 'Please enter a cancellation reason containing at least 5 characters.',
                    confirmButtonText: 'OK'
                });
                reasonField.focus();
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Cancel This Booking?',
                html: 'Are you sure you want to cancel this booking and request a refund of <strong>{{ $booking->currency ?? 'INR' }} {{ number_format((float) $booking->amount, 2) }}</strong>?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel Booking',
                cancelButtonText: 'Keep Booking',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    const button = document.getElementById('cancelBookingButton');

                    if (button) {
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    }

                    cancelForm.submit();
                }
            });
        });
    }
});
</script>
@endpush
