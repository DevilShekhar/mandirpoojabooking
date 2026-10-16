@extends('admin.layouts.app')
@section('title', 'Cancellation Request Details')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Cancellation Request Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.customer-cancel-booking.index') }}">Cancellation Requests</a>
                </div>
                <div class="breadcrumb-item active">Details</div>
            </div>
        </div>
        <div class="section-body">
            @php
                $booking = $cancellation->booking;
                $customer = $cancellation->customer ?? $booking?->user;
                $requestStatus = strtolower((string) $cancellation->status);
                $refundStatus = strtolower((string) $cancellation->refund_status);
                $requestBadge = match ($requestStatus) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'secondary',
                };
                $refundBadge = match ($refundStatus) {
                    'processed', 'completed' => 'success',
                    'processing', 'pending' => 'warning',
                    'failed' => 'danger',
                    'not_required' => 'secondary',
                    default => 'light',
                };
            @endphp
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Cancellation Information</h4>
                            <div class="card-header-action">
                                <span class="badge badge-{{ $requestBadge }}">
                                    {{ ucfirst($cancellation->status ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Request ID</strong>
                                    <p>{{ $cancellation->id }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Requested On</strong>
                                    <p>{{ $cancellation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Cancellation Reason</strong>
                                    <div class="border rounded p-3 mt-2">
                                        {{ $cancellation->cancellation_reason ?? 'No reason provided' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Reviewed By</strong>
                                    <p>{{ $cancellation->reviewer?->name ?? 'Not reviewed' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Reviewed On</strong>
                                    <p>{{ $cancellation->reviewed_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Review Notes</strong>
                                    <p>{{ $cancellation->review_notes ?: 'No review notes available.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Booking Details</h4>
                        </div>
                        <div class="card-body">
                            @if ($booking)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Booking Number</th>
                                                <td>{{ $booking->booking_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Booking Status</th>
                                                <td>{{ ucfirst($booking->booking_status ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Status</th>
                                                <td>{{ ucfirst($booking->payment_status ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Temple</th>
                                                <td>{{ $booking->temple?->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Deity</th>
                                                <td>{{ $booking->deity?->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Puja</th>
                                                <td>{{ $booking->puja?->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Package</th>
                                                <td>{{ $booking->pujaPackage?->name ?? $booking->package_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Schedule Date</th>
                                                <td>{{ $booking->schedule_date ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Schedule Time</th>
                                                <td>{{ $booking->schedule_time ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Person Count</th>
                                                <td>{{ $booking->person_count ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount</th>
                                                <td>₹{{ number_format((float) ($booking->total_amount ?? 0), 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Assigned Pandit</th>
                                                <td>{{ $booking->pandit?->name ?? 'Not assigned' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    Booking details could not be found.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Customer Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Name</strong>
                                    <p>{{ $customer?->name ?? $booking?->customer_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email</strong>
                                    <p>{{ $customer?->email ?? $booking?->customer_email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Mobile</strong>
                                    <p>{{ $booking?->customer_mobile ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Customer ID</strong>
                                    <p>{{ $cancellation->customer_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Refund Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Refund Status</strong>
                                <p>
                                    <span class="badge badge-{{ $refundBadge }}">
                                        {{ ucfirst(str_replace('_', ' ', $cancellation->refund_status ?? 'Unknown')) }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Refund Amount</strong>
                                <h5>₹{{ number_format((float) ($cancellation->refund_amount ?? 0), 2) }}</h5>
                            </div>
                            <div class="mb-3">
                                <strong>Razorpay Refund ID</strong>
                                <p class="text-break">{{ $cancellation->razorpay_refund_id ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Refunded On</strong>
                                <p>{{ $cancellation->refunded_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                            </div>
                            @if ($cancellation->refund_error)
                                <div class="alert alert-danger">
                                    <strong>Refund Error</strong>
                                    <p class="mb-0">{{ $cancellation->refund_error }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if ($requestStatus === 'pending')
                        <div class="card">
                            <div class="card-header">
                                <h4>Review Request</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.customer-cancel-booking.approve', $cancellation->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="approve_review_notes">Review Notes (Optional)</label>
                                        <textarea name="review_notes" id="approve_review_notes" class="form-control" rows="3" maxlength="2000">{{ old('review_notes') }}</textarea>
                                        @error('review_notes')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block js-sweet-confirm" data-title="Approve Cancellation?" data-text="This will approve the cancellation request and initiate the refund process." data-icon="warning" data-confirm="Yes, approve and process refund">
                                        <i class="fas fa-check"></i> Approve & Process Refund
                                    </button>
                                </form>
                                <hr>
                                <form action="{{ route('admin.customer-cancel-booking.reject', $cancellation->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="reject_review_notes">Rejection Reason</label>
                                        <textarea name="review_notes" id="reject_review_notes" class="form-control" rows="3" minlength="3" maxlength="2000" required>{{ old('review_notes') }}</textarea>
                                        @error('review_notes')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block js-sweet-confirm" data-title="Reject Cancellation?" data-text="Are you sure you want to reject this cancellation request?" data-icon="warning" data-confirm="Yes, reject request">
                                        <i class="fas fa-times"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    <a href="{{ route('admin.customer-cancel-booking.index') }}" class="btn btn-light btn-block mb-4">
                        <i class="fas fa-arrow-left"></i> Back to Cancellation Requests
                    </a>
                </div>
            </div>
        </div>
    </section>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonText: 'OK'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Please check the form',
                    html: @json(implode('<br>', $errors->all())),
                    confirmButtonText: 'OK'
                });
            @endif

            document.querySelectorAll('.js-sweet-confirm').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    if (!form) return;

                    Swal.fire({
                        title: button.dataset.title || 'Are you sure?',
                        text: button.dataset.text || 'Please confirm this action.',
                        icon: button.dataset.icon || 'warning',
                        showCancelButton: true,
                        confirmButtonColor: button.classList.contains('btn-danger') ? '#d33' : '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: button.dataset.confirm || 'Yes, continue',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusCancel: true
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            button.disabled = true;
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush

@endsection 