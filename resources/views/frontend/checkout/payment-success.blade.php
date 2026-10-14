@extends('frontend.layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="payment-success-page">
    <div class="payment-success-card">
        <div class="success-icon">
            <i class="fa fa-check"></i>
        </div>

        <h1>Payment Successful!</h1>

        <p class="success-message">
            Your payment has been received successfully.
            Your puja booking is confirmed.
        </p>

        @if (isset($booking) && $booking)
            <div class="booking-details">
                <h2>Booking Details</h2>

                <div class="detail-row">
                    <span>Booking Number</span>
                    <strong>{{ $booking->booking_number }}</strong>
                </div>

                <div class="detail-row">
                    <span>Booking Status</span>
                    <strong>{{ ucfirst($booking->booking_status ?? 'confirmed') }}</strong>
                </div>

                <div class="detail-row">
                    <span>Payment Status</span>
                    <strong>{{ ucfirst($booking->payment_status ?? 'paid') }}</strong>
                </div>

                <div class="detail-row">
                    <span>Customer Name</span>
                    <strong>{{ $booking->customer_name }}</strong>
                </div>

                <div class="detail-row">
                    <span>Puja</span>
                    <strong>{{ $puja->name ?? 'Puja' }}</strong>
                </div>

                <div class="detail-row">
                    <span>Package</span>
                    <strong>{{ $booking->package_name ?? ($package->name ?? 'Selected Package') }}</strong>
                </div>

                <div class="detail-row">
                    <span>Persons</span>
                    <strong>{{ $booking->person_count ?? 1 }}</strong>
                </div>

                <div class="detail-row total-row">
                    <span>Total Amount</span>
                    <strong>₹{{ number_format((float) ($booking->total_amount ?? 0), 2) }}</strong>
                </div>
            </div>
        @endif

        @if (isset($payment) && $payment)
            <div class="payment-reference">
                <span>Payment Reference</span>
                <strong>{{ $payment->razorpay_payment_id }}</strong>
            </div>
        @endif

        <div class="success-actions">
            <a href="{{ url('/') }}" class="btn-home">
                <i class="fa fa-home"></i>
                Back to Home
            </a>
        </div>

        <p class="thank-you-message">
            Thank you for choosing our puja booking service.
        </p>
    </div>
</div>

<style>
    .payment-success-page {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
        background: #f8f9fa;
    }

    .payment-success-card {
        width: 100%;
        max-width: 650px;
        padding: 35px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .success-icon {
        width: 75px;
        height: 75px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f8ee;
        color: #198754;
        font-size: 35px;
    }

    .payment-success-card h1 {
        margin-bottom: 12px;
        color: #198754;
        font-size: 30px;
        font-weight: 700;
    }

    .success-message {
        margin-bottom: 28px;
        color: #666;
        line-height: 1.7;
    }

    .booking-details {
        padding: 22px;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        text-align: left;
    }

    .booking-details h2 {
        margin-bottom: 18px;
        font-size: 20px;
        font-weight: 700;
        color: #222;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 12px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row span {
        color: #666;
    }

    .detail-row strong {
        color: #222;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .total-row {
        margin-top: 5px;
        padding-top: 18px;
        border-top: 1px solid #dddddd;
    }

    .total-row span,
    .total-row strong {
        color: #198754;
        font-size: 18px;
        font-weight: 700;
    }

    .payment-reference {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: left;
    }

    .payment-reference span {
        display: block;
        margin-bottom: 7px;
        color: #666;
        font-size: 13px;
    }

    .payment-reference strong {
        display: block;
        overflow-wrap: anywhere;
        color: #222;
    }

    .success-actions {
        margin-top: 25px;
    }

    .btn-home {
        display: inline-block;
        padding: 12px 25px;
        border-radius: 8px;
        background: #198754;
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .btn-home:hover {
        background: #146c43;
        color: #ffffff;
    }

    .btn-home i {
        margin-right: 7px;
    }

    .thank-you-message {
        margin: 22px 0 0;
        color: #777;
        font-size: 14px;
    }

    @media (max-width: 576px) {
        .payment-success-card {
            padding: 22px 16px;
        }

        .payment-success-card h1 {
            font-size: 25px;
        }

        .booking-details {
            padding: 15px;
        }

        .detail-row {
            gap: 12px;
            font-size: 14px;
        }
    }
</style>
@endsection