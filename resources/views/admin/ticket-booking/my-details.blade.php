
@extends('admin.layouts.app')
@section('title', 'My Booking Details')
@section('content')
@can('my-tickit-booking.details')
@php
    $firstBooking = $bookings->first();
    $cancellationStatus = $existingCancellation->cancellation_status ?? null;
    $actorType = $cancellationPolicy['actor_type'] ?? 'customer';
    $statusClass = 'status-secondary';
    if ($cancellationStatus === 'pending') {
        $statusClass = 'status-pending';
    } elseif (in_array($cancellationStatus, ['approved', 'cancelled'], true)) {
        $statusClass = 'status-success';
    } elseif ($cancellationStatus === 'rejected') {
        $statusClass = 'status-danger';
    }
    $isCustomer = $actorType === 'customer';
    $isAdminCancellation = in_array(
        $actorType,
        ['admin', 'super_admin', 'temple_manager'],
        true
    );
@endphp
<style>
    .booking-details-page {
        color: #343a40;
    }
    .booking-details-page .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        margin-bottom: 24px;
        background: #fff;
    }
    .booking-details-page .card-header {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 20px;
    }
    .booking-details-page .card-header h5 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    .booking-details-page .card-body {
        padding: 22px;
    }
    .detail-label {
        display: block;
        color: #777f8b;
        font-size: 12px;
        margin-bottom: 6px;
        font-weight: 500;
        text-transform: uppercase;
    }
    .detail-value {
        color: #252b36;
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }
    .booking-details-page .table thead th {
        background: #f8f9fa;
        color: #424b57;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
        padding: 13px 12px;
    }
    .booking-details-page .table tbody td,
    .booking-details-page .table tfoot th {
        padding: 13px 12px;
        vertical-align: middle;
        font-size: 13px;
    }
    .booking-details-page .table tfoot {
        background: #f8f9fa;
    }
    .booking-details-page .badge {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-success {
        background: #e8f5ed;
        color: #21834a;
    }
    .status-pending {
        background: #fff4dc;
        color: #9b6a00;
    }
    .status-danger {
        background: #fdebec;
        color: #c43d45;
    }
    .status-secondary {
        background: #f0f1f3;
        color: #555e6b;
    }
    .policy-box {
        border: 1px solid #e5e7eb;
        background: #fff;
        border-radius: 7px;
        padding: 20px;
    }
    .policy-value {
        font-size: 17px;
        font-weight: 600;
        color: #303744;
        margin-top: 5px;
    }

    .refund-value {
        color: #21834a;
    }

    .booking-alert {
        border: 1px solid #e5e7eb;
        background: #f8f9fa;
        color: #424b57;
        border-radius: 6px;
        padding: 15px 18px;
    }

    .booking-alert-success {
        background: #f5faf6;
        border-color: #dcecdf;
    }

    .booking-alert-danger {
        background: #fff7f7;
        border-color: #f1dada;
    }

    .booking-alert-info {
        background: #f7f9fc;
        border-color: #e0e5ec;
    }

    .policy-note {
        color: #777f8b;
        font-size: 12px;
    }

    .aadhaar-thumbnail {
        width: 65px;
        height: 55px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #dedede;
        cursor: pointer;
    }

    .aadhaar-view-button {
        border: none;
        padding: 0;
        background: transparent;
        cursor: pointer;
    }

    .aadhaar-modal {
        display: none;
        position: fixed;
        z-index: 99999;
        inset: 0;
        background: rgba(0,0,0,.82);
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .aadhaar-modal.show {
        display: flex;
    }

    .aadhaar-modal-content {
        position: relative;
        max-width: 850px;
        width: 100%;
        max-height: 90vh;
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }

    .aadhaar-modal-content img {
        max-width: 100%;
        max-height: 75vh;
        object-fit: contain;
        display: block;
        margin: auto;
    }

    .aadhaar-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .aadhaar-modal-close {
        border: none;
        background: transparent;
        font-size: 27px;
        cursor: pointer;
    }

    .booking-details-page .form-control {
        border-radius: 5px;
        border-color: #dfe3e8;
    }

    .booking-details-page .btn {
        border-radius: 5px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 500;
    }

    @media(max-width: 767px) {
        .booking-details-page .card-body {
            padding: 15px;
        }
    }
</style>

<div class="container-fluid py-4 booking-details-page">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h2 class="mb-1">Booking Details</h2>

            <p class="text-muted mb-0">
                View ticket booking and member information.
            </p>
        </div>

        <a href="{{ route('admin.my-tickit-booking') }}"
           class="btn btn-secondary">
            Back to My Bookings
        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>
    @endif

    {{-- Booking Summary --}}
    <div class="card">

        <div class="card-header">
            <h5>Booking Summary</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Booking Reference</span>

                    <div class="detail-value">
                        {{ $firstBooking->booking_reference }}
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Razorpay Order ID</span>

                    <div class="detail-value">
                        {{ $firstBooking->razorpay_order_id }}
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Temple</span>

                    <div class="detail-value">
                        {{ optional($firstBooking->temple)->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Visit Date</span>

                    <div class="detail-value">
                        {{ $firstBooking->booking_date
                            ? \Illuminate\Support\Carbon::parse($firstBooking->booking_date)->format('d-m-Y')
                            : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Booking Created At</span>

                    <div class="detail-value">
                        {{ $firstBooking->created_at
                            ? \Illuminate\Support\Carbon::parse($firstBooking->created_at)->format('d-m-Y h:i A')
                            : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <span class="detail-label">Total Members</span>

                    <div class="detail-value">
                        {{ $bookings->count() }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="detail-label">Total Amount</span>

                    <div class="detail-value refund-value">
                        ₹{{ number_format($totalAmount, 2) }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="detail-label">Payment Status</span>

                    @php
                        $paymentClass = in_array(
                            $firstBooking->payment_status,
                            ['refunded', 'partially_refunded'],
                            true
                        ) ? 'status-pending' : 'status-success';
                    @endphp

                    <span class="badge {{ $paymentClass }}">
                        {{ ucfirst(str_replace('_', ' ', $firstBooking->payment_status)) }}
                    </span>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="detail-label">Booking Status</span>

                    <span class="badge
                        {{ $firstBooking->booking_status === 'cancel_requested'
                            ? 'status-pending'
                            : ($firstBooking->booking_status === 'cancelled'
                                ? 'status-danger'
                                : 'status-success') }}">

                        {{ ucfirst(str_replace('_', ' ', $firstBooking->booking_status)) }}

                    </span>
                </div>

            </div>

        </div>
    </div>

    {{-- Ticket Cancellation --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5>Ticket Cancellation</h5>

            @if($existingCancellation)
                <span class="badge {{ $statusClass }}">
                    {{ in_array($cancellationStatus, ['approved', 'cancelled'], true)
                        ? 'Cancelled'
                        : ucfirst($cancellationStatus) }}
                </span>
            @endif

        </div>

        <div class="card-body">

            {{-- Existing Cancellation Details --}}
            @if($existingCancellation)

                <div class="booking-alert booking-alert-info">

                    <h6 class="font-weight-bold mb-3">
                        Cancellation Request Details
                    </h6>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Cancellation Status</span>

                            <div class="detail-value">
                                {{ in_array($existingCancellation->cancellation_status, ['approved', 'cancelled'], true)
                                    ? 'Cancelled'
                                    : ucfirst($existingCancellation->cancellation_status) }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Total Amount</span>

                            <div class="detail-value">
                                ₹{{ number_format((float) $existingCancellation->total_amount, 2) }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Deduction Percentage</span>

                            <div class="detail-value">
                                {{ number_format((float) $existingCancellation->deduction_percentage, 2) }}%
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Refund Percentage</span>

                            <div class="detail-value">
                                {{ number_format((float) $existingCancellation->refund_percentage, 2) }}%
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Refund Amount</span>

                            <div class="detail-value refund-value">
                                ₹{{ number_format((float) $existingCancellation->refund_amount, 2) }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Refund Status</span>

                            <div class="detail-value">
                                {{ ucfirst(str_replace('_', ' ', $existingCancellation->refund_status ?? 'N/A')) }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="detail-label">Request Date</span>

                            <div class="detail-value">
                                {{ $existingCancellation->requested_at
                                    ? \Illuminate\Support\Carbon::parse($existingCancellation->requested_at)->format('d-m-Y h:i A')
                                    : 'N/A' }}
                            </div>
                        </div>

                        @if(!empty($existingCancellation->razorpay_refund_id))

                            <div class="col-md-12 mb-3">

                                <span class="detail-label">Razorpay Refund ID</span>

                                <div class="detail-value">
                                    {{ $existingCancellation->razorpay_refund_id }}
                                </div>

                            </div>

                        @endif

                    </div>

                    @if(!empty($existingCancellation->refund_error))

                        <div class="booking-alert booking-alert-danger mb-3">
                            {{ $existingCancellation->refund_error }}
                        </div>

                    @endif

                    @if($existingCancellation->cancellation_reason)

                        <hr>

                        <span class="detail-label">Cancellation Reason</span>

                        <div class="detail-value">
                            {{ $existingCancellation->cancellation_reason }}
                        </div>

                    @endif

                </div>

                @if($cancellationStatus === 'pending')

                    <div class="booking-alert mt-3">
                        Your cancellation request is waiting for approval.
                    </div>

                @elseif($cancellationStatus === 'approved')

                    <div class="booking-alert booking-alert-success mt-3">

                        Your ticket booking has been cancelled.

                        Refund Status:

                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $existingCancellation->refund_status ?? 'processing')) }}
                        </strong>

                    </div>

                @elseif($cancellationStatus === 'cancelled')

                    <div class="booking-alert booking-alert-success mt-3">
                        Your ticket booking has been cancelled.
                    </div>

                @elseif($cancellationStatus === 'rejected')

                    <div class="booking-alert booking-alert-danger mt-3">
                        Your cancellation request was rejected.
                    </div>

                @endif

            @else

                {{-- Cancellation Policy --}}
                <div class="policy-box mb-4">

                    <h6 class="font-weight-bold mb-3">
                        Cancellation & Refund Policy
                    </h6>

                    {{-- Role-Based Policy --}}
                    <div class="booking-alert booking-alert-info mb-4">

                        @if($isCustomer)

                            <strong>Customer Cancellation Policy</strong>

                            <p class="mb-0 mt-2">
                                Cancellation is allowed only within 72 hours
                                of booking creation. Refund is calculated
                                according to the applicable refund slab.
                            </p>

                        @elseif($isAdminCancellation)

                            <strong>Administrative Cancellation Policy</strong>

                            <p class="mb-0 mt-2">
                                Admin, Super Admin and Temple Manager are
                                eligible for a 100% refund when cancelling
                                a booking.
                            </p>

                        @endif

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Booking Created At
                            </span>

                            <div class="policy-value">
                                {{ $cancellationPolicy['booking_created_at'] }}
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Time Since Booking Created
                            </span>

                            <div class="policy-value">
                                {{ number_format($cancellationPolicy['elapsed_hours'], 2) }}
                                Hours
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Applicable Policy Slab
                            </span>

                            <div class="policy-value">
                                {{ $cancellationPolicy['policy_slab'] ?: 'Not Applicable' }}
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Deduction Percentage
                            </span>

                            <div class="policy-value">
                                {{ number_format((float) $cancellationPolicy['deduction_percentage'], 2) }}%
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Refund Percentage
                            </span>

                            <div class="policy-value refund-value">
                                {{ number_format((float) $cancellationPolicy['refund_percentage'], 2) }}%
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Refund Amount
                            </span>

                            <div class="policy-value refund-value">
                                ₹{{ number_format((float) $cancellationPolicy['refund_amount'], 2) }}
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <span class="detail-label">
                                Deduction Amount
                            </span>

                            <div class="policy-value">
                                ₹{{ number_format((float) $cancellationPolicy['deduction_amount'], 2) }}
                            </div>

                        </div>

                    </div>

                    @if($isCustomer)

                       
                    @else

                        <div class="policy-note mt-3">
                            Administrative cancellations are eligible
                            for a full refund, regardless of booking age.
                        </div>

                    @endif

                </div>

                {{-- Cancellation Button --}}
                @if($canCancel)

                    <form
                        id="ticketCancellationForm"
                        action="{{ route(
                            'admin.my-tickit-booking.cancel-request',
                            $firstBooking->razorpay_order_id
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="cancellation_reason"
                            id="cancellation_reason"
                            value="{{ old('cancellation_reason') }}"
                        >

                        <button type="submit" class="btn btn-danger">

                            @if($isCustomer)

                                Request Cancellation
                                ({{ $cancellationPolicy['refund_percentage'] }}% Refund)

                            @else

                                Cancel Booking (100% Refund)

                            @endif

                        </button>

                    </form>

                @else

                    <div class="booking-alert booking-alert-danger">

                        <strong>Cancellation Not Available</strong>

                        <p class="mb-0 mt-2">
                            {{ $cancellationPolicy['message'] }}
                        </p>

                    </div>

                @endif

            @endif

        </div>
    </div>

    {{-- Member Details --}}
    <div class="card">

        <div class="card-header">
            <h5>Member Details</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member Name</th>
                            <th>Aadhaar Number</th>
                            <th>Aadhaar Photo</th>
                            <th>Ticket Type</th>
                            <th>Visitor Category</th>
                            <th>Age</th>
                            <th>Visit Date</th>
                            <th>Price</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($bookings as $booking)

                            @php

                                $aadhaarPhotoUrl = null;

                                if (!empty($booking->aadhaar_photo)) {

                                    $photoPath = $booking->aadhaar_photo;

                                    if (\Illuminate\Support\Str::startsWith(
                                        $photoPath,
                                        ['http://', 'https://']
                                    )) {

                                        $aadhaarPhotoUrl = $photoPath;

                                    } elseif (\Illuminate\Support\Str::startsWith(
                                        $photoPath,
                                        'storage/'
                                    )) {

                                        $aadhaarPhotoUrl = asset($photoPath);

                                    } else {

                                        $aadhaarPhotoUrl =
                                            \Illuminate\Support\Facades\Storage::url($photoPath);
                                    }
                                }

                            @endphp

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>
                                        {{ $booking->member_name ?? 'N/A' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $booking->aadhaar_number ?: 'N/A' }}
                                </td>

                                <td>

                                    @if($aadhaarPhotoUrl)

                                        <button
                                            type="button"
                                            class="aadhaar-view-button"
                                            onclick="openAadhaarModal(
                                                @js($aadhaarPhotoUrl),
                                                @js($booking->member_name)
                                            )"
                                        >

                                            <img
                                                src="{{ $aadhaarPhotoUrl }}"
                                                alt="Aadhaar Photo"
                                                class="aadhaar-thumbnail"
                                                loading="lazy"
                                            >

                                        </button>

                                    @else

                                        <span class="text-muted">
                                            Not Uploaded
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ strtoupper($booking->ticket_type ?? 'N/A') }}
                                </td>

                                <td>
                                    {{ $booking->type ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $booking->age ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $booking->booking_date
                                        ? \Illuminate\Support\Carbon::parse($booking->booking_date)->format('d-m-Y')
                                        : 'N/A' }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $booking->price, 2) }}
                                </td>

                                <td>

                                    <span class="badge
                                        {{ in_array($booking->payment_status, ['refunded', 'partially_refunded'], true)
                                            ? 'status-pending'
                                            : 'status-success' }}">

                                        {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}

                                    </span>

                                </td>

                                <td>

                                    <span class="badge
                                        {{ $booking->booking_status === 'cancelled'
                                            ? 'status-danger'
                                            : ($booking->booking_status === 'cancel_requested'
                                                ? 'status-pending'
                                                : 'status-success') }}">

                                        {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="11" class="text-center">
                                    No booking members found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="8" class="text-right">
                                Total Amount
                            </th>

                            <th>
                                ₹{{ number_format($totalAmount, 2) }}
                            </th>

                            <th colspan="2"></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>
    </div>

</div>

{{-- Aadhaar Image Modal --}}
<div
    id="aadhaarImageModal"
    class="aadhaar-modal"
    role="dialog"
    aria-modal="true"
    onclick="closeAadhaarModalOnBackdrop(event)"
>

    <div class="aadhaar-modal-content">

        <div class="aadhaar-modal-header">

            <h5 id="aadhaarModalTitle">
                Aadhaar Photo
            </h5>

            <button
                type="button"
                class="aadhaar-modal-close"
                onclick="closeAadhaarModal()"
                aria-label="Close"
            >
                &times;
            </button>

        </div>

        <img
            id="aadhaarFullImage"
            src=""
            alt="Full Aadhaar Photo"
        >

    </div>

</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    // Cancellation Confirmation
    document.getElementById('ticketCancellationForm')
        ?.addEventListener('submit', function(event) {

            event.preventDefault();

            const form = this;

            Swal.fire({

                title: 'Request Ticket Cancellation',

                text: 'Please enter your cancellation reason.',

                input: 'textarea',

                inputPlaceholder: 'Enter cancellation reason...',

                inputAttributes: {
                    'aria-label': 'Cancellation reason',
                    'maxlength': 1000
                },

                showCancelButton: true,

                confirmButtonText: 'Submit Request',

                cancelButtonText: 'Cancel',

                confirmButtonColor: '#dc3545',

                inputValidator: (value) => {

                    if (!value || !value.trim()) {
                        return 'Please enter your cancellation reason.';
                    }

                    if (value.trim().length < 3) {
                        return 'Please enter at least 3 characters.';
                    }
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('cancellation_reason').value =
                        result.value.trim();

                    form.submit();
                }

            });

        });


    // Open Aadhaar Image
    function openAadhaarModal(imageUrl, memberName) {

        const modal = document.getElementById('aadhaarImageModal');

        const image = document.getElementById('aadhaarFullImage');

        const title = document.getElementById('aadhaarModalTitle');

        image.src = imageUrl;

        title.textContent = memberName
            ? 'Aadhaar Photo - ' + memberName
            : 'Aadhaar Photo';

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';
    }


    // Close Aadhaar Image
    function closeAadhaarModal() {

        const modal = document.getElementById('aadhaarImageModal');

        const image = document.getElementById('aadhaarFullImage');

        modal.classList.remove('show');

        image.src = '';

        document.body.style.overflow = '';
    }


    // Close Modal On Backdrop
    function closeAadhaarModalOnBackdrop(event) {

        if (event.target.id === 'aadhaarImageModal') {

            closeAadhaarModal();
        }
    }


    // Escape Key
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeAadhaarModal();
        }

    });

</script>

@else

    @php abort(403); @endphp

@endcan

@endsection
