@extends('frontend.layouts.app')
@section('title', 'Puja Checkout')
@section('content')
    @php
        $temple = $puja->temple;

        $location = collect([
            $temple?->city?->name,
            $temple?->district?->name,
            $temple?->state?->name,
        ])->filter()->unique()->implode(', ');

        $image = $puja->banner_image ?: $puja->image;
        $imageUrl = null;

        if ($image) {
            $image = ltrim($image, '/');

            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                $imageUrl = $image;
            } else {
                $image = preg_replace('#^(storage/|public/)#', '', $image);
                $imageUrl = asset('storage/' . $image);
            }
        }

        $personCount = (int) ($package->person_count ?? 1);
    @endphp

    <section class="checkout-section">
        <div class="container">
            <div class="checkout-header">
                <div>
                    <span class="checkout-subtitle">BOOK YOUR PUJA</span>
                    <h1>Puja Checkout</h1>
                    <p>Review your puja and package details before proceeding.</p>
                </div>

                <a href="{{ url()->previous() }}" class="back-button">
                    <i class="fa fa-arrow-left"></i>
                    Back
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-om"></i>
                            </div>

                            <div>
                                <h3>Puja Details</h3>
                                <p>Information about your selected puja</p>
                            </div>
                        </div>

                        <div class="puja-information">
                            @if($imageUrl)
                                <div class="puja-image-wrapper">
                                    <img src="{{ $imageUrl }}" alt="{{ $puja->name }}" class="puja-image">
                                </div>
                            @endif

                            <div class="puja-information-content">
                                @if($puja->pujaCategory)
                                    <span class="category-badge">
                                        {{ $puja->pujaCategory->name }}
                                    </span>
                                @endif

                                <h2>{{ $puja->name }}</h2>

                                @if($puja->puja_code)
                                    <p>
                                        <i class="fa fa-hashtag"></i>
                                        <strong>Puja Code:</strong>
                                        {{ $puja->puja_code }}
                                    </p>
                                @endif

                                @if($puja->deity)
                                    <p>
                                        <i class="fa fa-om"></i>
                                        <strong>Deity:</strong>
                                        {{ $puja->deity->name }}
                                    </p>
                                @endif

                                @if($puja->duration)
                                    <p>
                                        <i class="fa fa-clock-o"></i>
                                        <strong>Duration:</strong>
                                        {{ $puja->duration }}
                                    </p>
                                @endif

                                @if($puja->is_online)
                                    <span class="mode-badge">
                                        <i class="fa fa-video-camera"></i>
                                        Online Puja
                                    </span>
                                @endif

                                @if($puja->is_offline)
                                    <span class="mode-badge">
                                        <i class="fa fa-home"></i>
                                        Offline Puja
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($puja->description)
                            <div class="checkout-detail-section">
                                <h4>About Puja</h4>

                                <div class="description-content">
                                    {!! $puja->description !!}
                                </div>
                            </div>
                        @endif

                        @if($puja->benefits)
                            <div class="checkout-detail-section">
                                <h4>Benefits of Puja</h4>

                                <div class="description-content">
                                    {!! nl2br(e($puja->benefits)) !!}
                                </div>
                            </div>
                        @endif

                        @if($puja->ritual_details)
                            <div class="checkout-detail-section">
                                <h4>Puja Process</h4>

                                <div class="description-content">
                                    {!! nl2br(e($puja->ritual_details)) !!}
                                </div>
                            </div>
                        @endif

                        @if($puja->samagri_details)
                            <div class="checkout-detail-section">
                                <h4>Puja Samagri</h4>

                                <div class="description-content">
                                    {!! nl2br(e($puja->samagri_details)) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-shopping-basket"></i>
                            </div>
                            <div>
                                <h3>Selected Puja Samagri</h3>
                                <p>Products selected with your package</p>
                            </div>
                        </div>

                        @if($selectedProducts->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table checkout-products-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Unit Price</th>
                                            <th>Qty</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedProducts as $product)
                                            <tr>
                                                <td>{{ $product['name'] }}</td>
                                                <td>{{ $product['unit'] ?: '—' }}</td>
                                                <td>₹{{ number_format($product['price'], 2) }}</td>
                                                <td>{{ $product['quantity'] }}</td>
                                                <td class="text-right">₹{{ number_format($product['line_total'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Samagri Subtotal</th>
                                            <th class="text-right">₹{{ number_format($selectedProductsTotal, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="checkout-empty-samagri">
                                <i class="fa fa-check-circle"></i>
                                <div>
                                    <strong>Package only</strong>
                                    <p>You have not selected any additional samagri.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-university"></i>
                            </div>

                            <div>
                                <h3>Temple Details</h3>
                                <p>Temple information and location</p>
                            </div>
                        </div>

                        @if($temple)
                            <div class="temple-information-grid">
                                <div class="temple-information-item">
                                    <span>Temple Name</span>
                                    <strong>{{ $temple->name }}</strong>
                                </div>

                                <div class="temple-information-item">
                                    <span>City</span>
                                    <strong>{{ $temple->city?->name ?? 'N/A' }}</strong>
                                </div>

                                <div class="temple-information-item">
                                    <span>District</span>
                                    <strong>{{ $temple->district?->name ?? 'N/A' }}</strong>
                                </div>

                                <div class="temple-information-item">
                                    <span>State</span>
                                    <strong>{{ $temple->state?->name ?? 'N/A' }}</strong>
                                </div>

                                <div class="temple-information-item full-width">
                                    <span>Location</span>
                                    <strong>{{ $location ?: 'N/A' }}</strong>
                                </div>

                                @if($temple->address)
                                    <div class="temple-information-item full-width">
                                        <span>Full Address</span>
                                        <strong>{{ $temple->address }}</strong>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="empty-message">
                                Temple details are not available.
                            </div>
                        @endif
                    </div>

                    <div class="checkout-card">
                        <div class="checkout-card-heading">
                            <div class="heading-icon">
                                <i class="fa fa-user"></i>
                            </div>

                            <div>
                                <h3>Devotee Information</h3>
                                <p>Logged-in account information</p>
                            </div>
                        </div>

                        <div class="devotee-information">
                            <div class="devotee-information-item">
                                <span>Name</span>
                                <strong>{{ auth()->user()->name }}</strong>
                            </div>

                            <div class="devotee-information-item">
                                <span>Email</span>
                                <strong>{{ auth()->user()->email }}</strong>
                            </div>

                            @if(auth()->user()->phone)
                                <div class="devotee-information-item">
                                    <span>Mobile Number</span>
                                    <strong>{{ auth()->user()->phone }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="package-summary-card">
                        <div class="package-summary-header">
                            <i class="fa fa-shopping-bag"></i>
                            <h3>Booking Summary</h3>
                        </div>

                        <div class="package-summary-body">
                            <span class="summary-label">Selected Puja</span>
                            <h4>{{ $puja->name }}</h4>

                            <div class="summary-divider"></div>

                            <span class="summary-label">Selected Package</span>
                            <h4>{{ $package->name }}</h4>

                            @if($package->short_description)
                                <p class="package-description">
                                    {{ $package->short_description }}
                                </p>
                            @endif
                            <div class="summary-divider"></div>
                            <div class="booking-schedule-fields">
                                <div class="form-group">
                                    <label for="booking_date">
                                        <i class="fa fa-calendar"></i> Select Puja Date
                                    </label>
                                    <input type="date" id="booking_date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="booking_time">
                                        <i class="fa fa-clock-o"></i> Select Puja Time
                                    </label>
                                    <input type="time" id="booking_time" name="booking_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="summary-detail">
                                <span>
                                    <i class="fa fa-users"></i>
                                    Devotees
                                </span>

                                <strong>
                                    {{ $personCount }}
                                    {{ $personCount === 1 ? 'Person' : 'Persons' }}
                                </strong>
                            </div>

                            <div class="summary-divider"></div>

                            <div class="summary-detail">
                                <span>Puja Package</span>
                                <strong>₹{{ number_format($finalPackagePrice, 2) }}</strong>
                            </div>

                            <div class="summary-detail">
                                <span>Selected Samagri</span>
                                <strong>₹{{ number_format($selectedProductsTotal, 2) }}</strong>
                            </div>

                            <div class="summary-total">
                                <span>Total Amount</span>
                                <strong>₹{{ number_format($total, 2) }}</strong>
                            </div>

                            <p class="price-note">
                                Total for the selected package and samagri.
                            </p>

                            <button
                                type="button"
                                id="checkoutPaymentButton"
                                class="checkout-payment-button"
                            >
                                Continue to Payment
                                <i class="fa fa-arrow-right"></i>
                            </button>

                            <p class="payment-note" id="paymentNote">
                                <i class="fa fa-lock"></i>
                                Secure payment powered by Razorpay.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .checkout-section {
        padding: 45px 0 70px;
        background: #fffaf5;
        color: #29211c;
    }

    .checkout-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .checkout-subtitle {
        color: #d71968;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .checkout-header h1 {
        margin: 8px 0;
        font-size: 32px;
        font-weight: 700;
    }

    .checkout-header p {
        margin: 0;
        color: #777;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid #eadfd5;
        border-radius: 8px;
        color: #29211c;
        background: #fff;
        text-decoration: none;
        white-space: nowrap;
    }

    .back-button:hover {
        color: #d71968;
        border-color: #d71968;
        text-decoration: none;
    }

    .checkout-card,
    .package-summary-card {
        margin-bottom: 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f0e6dd;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
    }

    .package-summary-card {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    .checkout-card {
        padding: 25px;
    }

    .checkout-card-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #eee;
    }

    .heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        color: #d71968;
        background: #fff0f5;
        font-size: 20px;
    }

    .checkout-card-heading h3 {
        margin: 0 0 5px;
        font-size: 21px;
        font-weight: 700;
    }

    .checkout-card-heading p {
        margin: 0;
        color: #888;
        font-size: 13px;
    }

    .puja-information {
        display: flex;
        align-items: flex-start;
        gap: 22px;
    }

    .puja-image-wrapper {
        flex: 0 0 42%;
        max-width: 42%;
    }

    .puja-image {
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 10px;
    }

    .puja-information-content {
        flex: 1;
        min-width: 0;
    }

    .puja-information-content h2 {
        margin: 12px 0 18px;
        font-size: 25px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .puja-information-content p {
        margin-bottom: 12px;
        color: #666;
        font-size: 14px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .puja-information-content p i {
        width: 20px;
        color: #d71968;
    }

    .category-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        color: #d71968;
        background: #fff0f5;
        font-size: 12px;
        font-weight: 700;
    }

    .mode-badge {
        display: inline-block;
        margin: 4px 5px 0 0;
        padding: 6px 10px;
        border-radius: 20px;
        color: #34743b;
        background: #f0f8ed;
        font-size: 12px;
        font-weight: 600;
    }

    .checkout-detail-section {
        margin-top: 25px;
        padding-top: 22px;
        border-top: 1px solid #eee;
    }

    .checkout-detail-section h4 {
        margin-bottom: 12px;
        font-size: 17px;
        font-weight: 700;
    }

    .description-content {
        color: #666;
        font-size: 14px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }

    .description-content img {
        max-width: 100%;
        height: auto;
    }

    .temple-information-grid,
    .devotee-information {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .temple-information-item,
    .devotee-information-item {
        min-width: 0;
        padding: 15px;
        border: 1px solid #f0e6dd;
        border-radius: 9px;
        background: #fffaf5;
    }

    .temple-information-item span,
    .devotee-information-item span {
        display: block;
        margin-bottom: 7px;
        color: #888;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .temple-information-item strong,
    .devotee-information-item strong {
        display: block;
        color: #29211c;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .package-summary-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 22px;
        color: #fff;
        background: #d71968;
    }

    .package-summary-header i {
        font-size: 22px;
    }

    .package-summary-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }

    .package-summary-body {
        padding: 24px;
    }

    .summary-label {
        display: block;
        margin-bottom: 7px;
        color: #888;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .package-summary-body h4 {
        margin-bottom: 10px;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .package-description {
        margin: 8px 0 15px;
        color: #777;
        font-size: 13px;
        line-height: 1.7;
    }

    .booking-schedule-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .booking-schedule-fields .form-group {
        margin: 0;
        min-width: 0;
    }

    .booking-schedule-fields label {
        display: block;
        margin-bottom: 7px;
        color: #555;
        font-size: 13px;
        font-weight: 600;
    }

    .booking-schedule-fields label i {
        margin-right: 5px;
        color: #d71968;
    }

    .booking-schedule-fields .form-control {
        width: 100%;
        height: 44px;
        padding: 8px 10px;
        border: 1px solid #eadfd5;
        border-radius: 8px;
        background: #fff;
        color: #29211c;
        font-size: 13px;
    }

    .booking-schedule-fields .form-control:focus {
        border-color: #d71968;
        box-shadow: 0 0 0 2px rgba(215, 25, 104, .08);
        outline: none;
    }

    .summary-divider {
        margin: 20px 0;
        border-top: 1px solid #eee;
    }

    .summary-detail {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 13px;
    }

    .summary-detail > span:first-child {
        color: #777;
    }

    .summary-detail strong {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .summary-detail i {
        margin-right: 5px;
        color: #d71968;
    }

    .original-price {
        color: #999;
        text-decoration: line-through;
    }

    .discount-text {
        color: #198754;
    }

    .summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid #eee;
    }

    .summary-total span {
        font-size: 15px;
        font-weight: 600;
    }

    .summary-total strong {
        color: #d71968;
        font-size: 25px;
        font-weight: 700;
        white-space: nowrap;
    }

    .price-note,
    .payment-note {
        margin-top: 12px;
        color: #888;
        font-size: 12px;
        line-height: 1.6;
    }

    .checkout-payment-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        margin-top: 22px;
        padding: 14px 16px;
        border: 0;
        border-radius: 8px;
        color: #fff;
        background: #198754;
        font-size: 15px;
        font-weight: 700;
    }

    .checkout-payment-button:disabled {
        cursor: not-allowed;
        opacity: .65;
    }

    .payment-note {
        text-align: center;
        overflow-wrap: anywhere;
    }

    .payment-note i {
        margin-right: 5px;
    }

    .checkout-products-table {
        margin-bottom: 0;
        font-size: 13px;
    }

    .checkout-products-table thead th {
        background: #fff6eb;
        color: #59412d;
        border-top: 0;
        white-space: nowrap;
    }

    .checkout-products-table td,
    .checkout-products-table th {
        padding: 12px 9px;
        vertical-align: middle;
    }

    .checkout-products-table tfoot th {
        border-top: 1px solid #eadfd5;
    }

    .checkout-empty-samagri {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #d9ecdf;
        border-radius: 9px;
        background: #f0f8f2;
    }

    .checkout-empty-samagri i {
        color: #198754;
        font-size: 23px;
    }

    .checkout-empty-samagri strong {
        color: #24573a;
    }

    .checkout-empty-samagri p {
        margin: 4px 0 0;
        color: #777;
        font-size: 13px;
    }

    .empty-message {
        color: #777;
        font-size: 14px;
    }

    @media (max-width: 991px) {
        .checkout-section {
            padding: 30px 0 45px;
        }

        .puja-image {
            height: 200px;
        }

        .package-summary-card {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .checkout-header {
            align-items: flex-start;
        }

        .checkout-header h1 {
            font-size: 26px;
        }

        .checkout-header p {
            font-size: 13px;
        }

        .back-button {
            padding: 9px 11px;
            font-size: 12px;
        }

        .checkout-card {
            padding: 18px;
        }

        .puja-information {
            flex-direction: column;
        }

        .puja-image-wrapper {
            flex: auto;
            max-width: 100%;
            width: 100%;
        }

        .puja-image {
            height: 220px;
        }

        .puja-information-content h2 {
            font-size: 22px;
        }

        .temple-information-grid,
        .devotee-information {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }

        .package-summary-body {
            padding: 20px;
        }

        .booking-schedule-fields {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentButton = document.getElementById('checkoutPaymentButton');
        const paymentNote = document.getElementById('paymentNote');

        if (!paymentButton) {
            return;
        }

        const originalButtonHtml = paymentButton.innerHTML;
        const csrfToken = '{{ csrf_token() }}';

        const createOrderUrl = @json(route('razorpay.create-order'));
        const verifyPaymentUrl = @json(route('razorpay.verify'));
        const failedUrl = @json(route('razorpay.failed'));
        const packageId = @json($package->id);
        const selectedProductIds = @json($selectedIds->values()->all());
        const bookingDateInput = document.getElementById('booking_date');
        const bookingTimeInput = document.getElementById('booking_time');

        let paymentInProgress = false;

        function setLoading(isLoading, message = '') {
            paymentButton.disabled = isLoading;

            paymentButton.innerHTML = isLoading
                ? 'Please wait... <i class="fa fa-spinner fa-spin"></i>'
                : originalButtonHtml;

            if (message && paymentNote) {
                paymentNote.textContent = message;
            }
        }

        async function postJson(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            let data;

            try {
                data = await response.json();
            } catch (e) {
                throw new Error('Invalid server response. Please try again.');
            }

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to process your payment. Please try again.'
                );
            }

            return data;
        }

        async function handlePaymentFailure(response, order) {
            const error = response.error || {};
            const metadata = error.metadata || {};

            const description = error.description ||
                'Payment failed. Please try again.';

            setLoading(true, 'Processing payment failure...');

            try {
                const result = await postJson(failedUrl, {
                    razorpay_order_id: metadata.order_id || order.order_id,
                    error_code: error.code || null,
                    error_description: description,
                    error_source: error.source || null,
                    error_step: error.step || null,
                    error_reason: error.reason || null
                });

                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                    return;
                }

                setLoading(false, 'Payment failed. Please try again.');

            } catch (error) {
                console.error('Payment failure handling error:', error);

                setLoading(
                    false,
                    'Payment failed, but we could not open the failure page. Please check your payment status.'
                );
            }
        }

        paymentButton.addEventListener('click', async function () {
            if (paymentInProgress) {
                return;
            }

            const bookingDate = bookingDateInput ? bookingDateInput.value : '';
            const bookingTime = bookingTimeInput ? bookingTimeInput.value : '';

            if (!bookingDate || !bookingTime) {
                if (!bookingDate && bookingDateInput) {
                    bookingDateInput.focus();
                } else if (bookingTimeInput) {
                    bookingTimeInput.focus();
                }
                setLoading(false, 'Please select the puja date and time before payment.');
                return;
            }

            const selectedSchedule = `${bookingDate} ${bookingTime}`;

            paymentInProgress = true;
            setLoading(true, 'Creating payment order...');

            try {
                const order = await postJson(createOrderUrl, {
                    package_id: packageId,
                    product_ids: selectedProductIds,
                    booking_date: bookingDate,
                    booking_time: bookingTime,
                    schedule: selectedSchedule
                });

                if (!order.order_id || !order.key) {
                    throw new Error('Unable to create payment order.');
                }
                const options = {
                    key: order.key,
                    amount: order.amount,
                    currency: order.currency || 'INR',
                    name: order.name || 'Puja Booking',
                    description: order.description || 'Puja Booking Payment',
                    order_id: order.order_id,
                    prefill: order.prefill || {},

                    theme: {
                        color: '#d71968'
                    },
                    handler: async function (response) {
                        setLoading(true, 'Verifying payment...');

                        try {
                            const result = await postJson(verifyPaymentUrl, {
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                booking_date: bookingDate,
                                booking_time: bookingTime,
                                schedule: selectedSchedule,
                                package_id: packageId,
                                product_ids: selectedProductIds
                            });

                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                                return;
                            }

                            throw new Error(
                                result.message ||
                                'Payment verification could not be completed.'
                            );

                        } catch (error) {
                            console.error('Payment verification error:', error);

                            paymentInProgress = false;
                            setLoading(false, error.message);
                        }
                    },
                    modal: {
                        ondismiss: function () {
                            paymentInProgress = false;

                            setLoading(
                                false,
                                'Payment was cancelled or the checkout was closed. You can try again.'
                            );
                        }
                    }
                };
                const razorpay = new Razorpay(options);
                razorpay.on('payment.failed', function (response) {
                    handlePaymentFailure(response, order);
                });
                razorpay.open();

            } catch (error) {
                console.error('Checkout error:', error);

                paymentInProgress = false;
                setLoading(false, error.message);
            }
        });
    });
</script>

@endpush