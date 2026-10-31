@extends('admin.layouts.app')
@section('title', 'Samagri Booking Details')
@section('content')
@can('samgri-booking.show')
@php
    $totalAmount = 0;
    $totalItems = 0;
    $verification = $booking->samgriVerification;
    $customer = $booking->user;
@endphp
<style>
   .samgri-header{background:linear-gradient(120deg,#3949ab,#6574cd);color:#fff;border-radius:8px;padding:25px;margin-bottom:25px}.samgri-header h4{color:#fff;font-size:21px;margin-bottom:7px}.samgri-header .booking-meta{color:rgba(255,255,255,.85);font-size:13px}.samgri-header-icon{font-size:48px;opacity:.8}.samgri-info-card{background:#fff;border:1px solid #e9ecef;border-radius:8px;padding:17px;height:100%}.samgri-info-icon{width:42px;height:42px;display:inline-flex;align-items:center;justify-content:center;border-radius:9px;font-size:18px;margin-bottom:12px}.samgri-info-label{color:#98a0ad;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}.samgri-info-value{color:#34395e;font-size:16px;font-weight:700;margin-top:5px;word-break:break-word}.samgri-product-icon{width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background:#eef1ff;color:#3949ab;border-radius:8px;margin-right:10px;flex-shrink:0}.samgri-product-table thead th{background:#f7f8fc;color:#6c757d;font-size:11px;text-transform:uppercase;letter-spacing:.4px;border-top:0;padding:15px 12px;white-space:nowrap}.samgri-product-table tbody td{padding:16px 12px;vertical-align:middle}.samgri-product-name{color:#34395e;font-weight:600}.samgri-quantity{display:inline-block;background:#e8f5e9;color:#2e7d32;padding:5px 11px;border-radius:20px;font-size:12px;font-weight:700}.samgri-total-box{background:#f3f4ff;border:1px solid #e2e5ff;border-radius:8px;padding:18px}.samgri-total-label{color:#6c757d;font-size:12px}.samgri-total-value{color:#3949ab;font-size:25px;font-weight:700}.samgri-otp-card{border:1px solid #e5e9f2;border-radius:8px;overflow:hidden;margin-bottom:20px;background:#fff}.samgri-otp-header{background:#fff8e6;border-bottom:1px solid #f5e7bf;padding:17px}.samgri-otp-header h4{color:#9a6700;font-size:15px;margin:0}.samgri-otp-body{padding:20px}.samgri-otp-input{height:48px;font-size:19px;font-weight:700;letter-spacing:5px;text-align:center}.samgri-verified{background:#eaf7ee;border:1px solid #c7e8d0;border-radius:8px;padding:22px 15px;text-align:center}.samgri-verified-icon{color:#28a745;font-size:42px;margin-bottom:12px}.samgri-verified h5{color:#218838;font-weight:700}.samgri-empty{text-align:center;padding:40px 15px;color:#98a0ad}.samgri-empty i{font-size:40px;margin-bottom:12px}.samgri-side-note{background:#f7f8fc;border-radius:7px;padding:13px;font-size:12px;color:#6c757d}.samgri-customer-detail{color:#34395e;font-size:14px;font-weight:600;margin-top:5px;word-break:break-word}.samgri-customer-icon{color:#3949ab;margin-right:5px}@media (min-width:992px){.samgri-sticky{position:sticky;top:20px}}@media (max-width:576px){.samgri-header{padding:18px}.samgri-header-icon{font-size:32px}.samgri-total-value{font-size:21px}}
</style>
<section class="section">
    <div class="section-header">
        <h1>Samagri Booking Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('samgri-booking.index') }}">
                    Samagri Bookings
                </a>
            </div>
            <div class="breadcrumb-item active">
                Booking Details
            </div>
        </div>
    </div>
    <div class="section-body">
        <div class="samgri-header">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="booking-meta mb-2">
                        <i class="fas fa-box-open mr-2"></i>
                        SAMAGRI BOOKING
                    </div>
                    <h4>
                        {{ $booking->booking_number ?? 'Booking #' . $booking->id }}
                    </h4>
                    <div class="booking-meta">
                        Booking ID: #{{ $booking->id }}
                    </div>
                </div>
                <div class="col-md-3 text-md-right mt-3 mt-md-0">
                    <i class="fas fa-shopping-basket samgri-header-icon"></i>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h4>
                    <i class="fas fa-user-circle  mr-2"></i>
                    Customer Details
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="samgri-info-label">
                            <i class="fas fa-user samgri-customer-icon"></i>
                            Customer Name
                        </div>
                        <div class="samgri-customer-detail">
                            {{ $customer->name ?? $booking->customer_name ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="samgri-info-label">
                            <i class="fas fa-envelope samgri-customer-icon"></i>
                            Email Address
                        </div>
                        <div class="samgri-customer-detail">
                            {{ $customer->email ?? $booking->customer_email ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="samgri-info-label">
                           <i class="fas fa-phone samgri-customer-icon"></i>
                            Mobile Number
                        </div>
                        <div class="samgri-customer-detail">
                            {{ $customer->phone ?? $booking->customer_mobile ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="samgri-info-card">
                    <div class="samgri-info-icon"
                        style="background:#e8eaff;color:#3949ab;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="samgri-info-label">
                        Scheduled Date
                    </div>
                    <div class="samgri-info-value">
                        {{ $booking->schedule_date
                            ? \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y')
                            : '-' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="samgri-info-card">
                    <div class="samgri-info-icon"
                        style="background:#fff0e5;color:#f08c27;">
                        <i class="far fa-clock"></i>
                    </div>
                    <div class="samgri-info-label">
                        Scheduled Time
                    </div>
                    <div class="samgri-info-value">
                        {{ $booking->schedule_time
                            ? \Carbon\Carbon::parse($booking->schedule_time)->format('h:i A')
                            : '-' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="samgri-info-card">
                    <div class="samgri-info-icon"
                        style="background:#e5f8ed;color:#28a745;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="samgri-info-label">
                        Verification Status
                    </div>
                    <div class="samgri-info-value">
                        @if($booking->samgri_otp_verified)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i>
                                Verified
                            </span>
                        @else
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-shopping-basket  mr-2"></i>
                            Samagri Product List
                        </h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary">
                                {{ count($products) }} Products
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped samgri-product-table mb-0">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th>Product Name</th>
                                        <th width="15%">Qty</th>
                                        <th width="18%">Price</th>
                                        <th width="20%" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        @php
                                            $productName = is_array($product)
                                                ? ($product['product_name'] ?? $product['name'] ?? $product['title'] ?? 'Samagri Item')
                                                : $product;
                                            $quantity = is_array($product)
                                                ? (float) ($product['quantity'] ?? $product['qty'] ?? 1)
                                                : 1;
                                            $price = is_array($product)
                                                ? (float) ($product['price'] ?? $product['amount'] ?? 0)
                                                : 0;
                                            $itemTotal = $quantity * $price;
                                            $totalAmount += $itemTotal;
                                            $totalItems += $quantity;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="text-muted font-weight-bold">
                                                    {{ sprintf('%02d', $loop->iteration) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="samgri-product-icon">
                                                        <i class="fas fa-box"></i>
                                                    </span>
                                                    <div class="samgri-product-name">
                                                        {{ $productName }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="samgri-quantity">
                                                    {{ $quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                ₹{{ number_format($price, 2) }}
                                            </td>
                                            <td class="text-right">
                                                <strong class="">
                                                    ₹{{ number_format($itemTotal, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="samgri-empty">
                                                    <i class="fas fa-box-open"></i>
                                                    <h6>
                                                        No Samagri Products Found
                                                    </h6>
                                                    <p class="mb-0">
                                                        No products are available for this booking.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($products) > 0)
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-md-7">
                                    <div class="samgri-total-box">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="samgri-total-label">
                                                    <i class="fas fa-receipt mr-1"></i>
                                                    Total Samagri Amount
                                                </div>
                                                <div class="samgri-total-value">
                                                    ₹{{ number_format($totalAmount, 2) }}
                                                </div>
                                            </div>
                                            <i class="fas fa-wallet" style="font-size:30px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="samgri-sticky">
                    <div class="samgri-otp-card">
                        <div class="samgri-otp-header">
                            <h4>
                                <i class="fas fa-shield-alt mr-2"></i>
                                OTP Verification
                            </h4>
                        </div>
                        <div class="samgri-otp-body">
                            @if($booking->samgri_otp_verified)
                                <div class="samgri-verified">
                                    <div class="samgri-verified-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h5>
                                        Successfully Verified
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Samagri verification has been completed.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user-check mr-1"></i>
                                            Verified By
                                        </small>
                                        <div class="font-weight-bold mt-1">
                                            {{ $verification->verifiedBy->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Verified At
                                        </small>
                                        <div class="font-weight-bold mt-1">
                                            {{ $verification && $verification->verified_at
                                                ? $verification->verified_at->format('d M Y h:i A')
                                                : ($booking->samgri_otp_verified_at
                                                    ? \Carbon\Carbon::parse($booking->samgri_otp_verified_at)->format('d M Y h:i A')
                                                    : 'N/A') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="samgri-info-icon mx-auto"
                                        style="background:#fff0e5;color:#f08c27;width:55px;height:55px;font-size:23px;">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <h6 class="font-weight-bold">
                                        Verify Customer OTP
                                    </h6>
                                    <p class="text-muted small">
                                        Enter the OTP provided by the customer to complete Samagri verification.
                                    </p>
                                </div>
                               <form action="{{ route('samgri-booking.verify-otp', $booking->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="otp">
                                            <i class="fas fa-key text-primary mr-1"></i>
                                            Customer OTP
                                        </label>
                                        <input type="text"
                                            name="otp" id="otp" class="form-control samgri-otp-input"
                                            placeholder="Enter OTP" maxlength="10" value="{{ old('otp') }}"  autocomplete="one-time-code"
                                            required >
                                        @error('otp')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verify OTP
                                    </button>
                                </form>
                                <div class="samgri-side-note mt-3">
                                    <i class="fas fa-info-circle  mr-1"></i>
                                    Verify the OTP only after confirming the Samagri handover.
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('samgri-booking.index') }}"
                        class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Samagri Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@else
    @php
        abort(403);
    @endphp
@endcan
@endsection