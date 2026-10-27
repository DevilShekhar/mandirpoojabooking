
@extends('frontend.layouts.app')

@section('title', 'My Ticket Cart')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2>My Ticket Cart</h2>
            <p class="text-muted mb-0">Review your temple ticket members before payment.</p>
        </div>

        <a href="{{ route('frontend.our-temples') }}" class="btn btn-outline-secondary">
            Browse Temples
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <h4>Your cart is empty</h4>
                <p class="text-muted">
                    Choose a temple and add ticket members to continue.
                </p>

                <a href="{{ route('frontend.our-temples') }}" class="btn btn-danger">
                    Browse Temples
                </a>
            </div>
        </div>
    @else

        <div class="alert alert-info" role="status">
            <strong>Important:</strong>
            Ticket availability is checked again before payment.
            Your booking is confirmed only after payment verification succeeds.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Temple</th>
                        <th>Visit Date</th>
                        <th>Member</th>
                        <th>Ticket Type</th>
                        <th>Visitor Category</th>
                        <th>Age</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item->temple->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->booking_date)->format('d M Y') }}
                            </td>

                            <td>{{ $item->member_name }}</td>

                            <td>
                                <span class="badge {{ strtolower($item->ticket_type) === 'vip' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                    {{ strtoupper($item->ticket_type) }}
                                </span>
                            </td>

                            <td>{{ $item->type }}</td>

                            <td>{{ $item->age }}</td>

                            <td>
                                ₹{{ number_format((float) $item->price, 2) }}
                            </td>

                            <td>
                                <form
                                    action="{{ route('frontend.ticket-cart.remove', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Remove this member from your cart?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="7" class="text-end">Total Amount</th>
                        <th colspan="2">
                            ₹{{ number_format((float) $totalAmount, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Booking Summary</h5>
                        <p class="text-muted mb-1">
                            Total members: {{ $cartItems->count() }}
                        </p>
                        <h4 class="text-danger mb-0">
                            ₹{{ number_format((float) $totalAmount, 2) }}
                        </h4>
                    </div>

                    <button
                        type="button"
                        id="proceed-payment"
                        class="btn btn-danger btn-lg"
                        @disabled((float) $totalAmount <= 0)
                    >
                        <i class="fa fa-lock me-1"></i>
                        Proceed to Payment
                    </button>
                </div>

                <div id="payment-message" class="mt-3" role="status" aria-live="polite"></div>
            </div>
        </div>

    @endif
</div>

@if(!$cartItems->isEmpty())
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('proceed-payment');
            const messageBox = document.getElementById('payment-message');

            if (!button || !messageBox) {
                return;
            }

            const createOrderUrl = @json(route('frontend.ticket-payment.create-order'));
            const verifyPaymentUrl = @json(route('frontend.ticket-payment.verify'));
            const csrfToken = @json(csrf_token());
            const appName = @json(config('app.name'));

            const originalButtonHtml = button.innerHTML;
            let paymentInProgress = false;

            function showMessage(message, type = 'danger') {
                messageBox.className = 'alert alert-' + type + ' mt-3';
                messageBox.textContent = message;
            }

            function clearMessage() {
                messageBox.className = 'mt-3';
                messageBox.textContent = '';
            }

            function resetButton() {
                paymentInProgress = false;
                button.disabled = false;
                button.innerHTML = originalButtonHtml;
            }

            async function postJson(url, payload = {}) {
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
                } catch (error) {
                    throw new Error('The server returned an invalid response. Please try again.');
                }

                if (!response.ok || !data.success) {
                    const error = new Error(
                        data.message || 'The request could not be completed.'
                    );

                    error.paymentCaptured = data.payment_captured === true;
                    error.refundRequired = data.refund_required === true;

                    throw error;
                }

                return data;
            }

            button.addEventListener('click', async function () {
                if (paymentInProgress) {
                    return;
                }

                if (typeof Razorpay === 'undefined') {
                    showMessage('Razorpay checkout could not load. Please refresh and try again.');
                    return;
                }

                clearMessage();

                paymentInProgress = true;
                button.disabled = true;
                button.textContent = 'Checking Availability...';

                try {
                    /*
                     * The server must check daily ticket limits before
                     * creating the Razorpay order. Never trust client totals.
                     */
                    const order = await postJson(createOrderUrl);

                    const options = {
                        key: order.key,
                        amount: order.amount,
                        currency: order.currency,
                        name: appName,
                        description: 'Temple Ticket Booking',
                        order_id: order.order_id,

                        handler: async function (paymentResponse) {
                            button.disabled = true;
                            button.textContent = 'Verifying Payment...';

                            try {
                                /*
                                 * The server verifies the signature, fetches
                                 * payment status and rechecks availability
                                 * before confirming any bookings.
                                 */
                                const result = await postJson(verifyPaymentUrl, {
                                    razorpay_order_id: paymentResponse.razorpay_order_id,
                                    razorpay_payment_id: paymentResponse.razorpay_payment_id,
                                    razorpay_signature: paymentResponse.razorpay_signature
                                });

                                showMessage(
                                    result.message || 'Payment successful. Your tickets are booked.',
                                    'success'
                                );

                                if (result.redirect_url) {
                                    window.location.assign(result.redirect_url);
                                    return;
                                }

                                resetButton();
                            } catch (error) {
                                if (error.paymentCaptured || error.refundRequired) {
                                    showMessage(
                                        error.message +
                                        ' Do not retry payment immediately. Contact support to reconcile the captured payment and refund if required.',
                                        'warning'
                                    );
                                } else {
                                    showMessage(error.message);
                                }

                                resetButton();
                            }
                        },

                        modal: {
                            ondismiss: function () {
                                if (paymentInProgress) {
                                    resetButton();
                                    showMessage(
                                        'Checkout was closed. Your cart has not been cleared. Check your payment status before trying again.',
                                        'info'
                                    );
                                }
                            },
                            escape: true,
                            confirm_close: true
                        },

                        theme: {
                            color: '#dc3545'
                        }
                    };

                    const razorpay = new Razorpay(options);

                    razorpay.on('payment.failed', function (response) {
                        resetButton();

                        const description =
                            response.error && response.error.description
                                ? response.error.description
                                : 'Payment was not completed.';

                        showMessage(
                            description +
                            ' Your cart has not been cleared. If money was deducted, check the payment status before retrying.'
                        );
                    });

                    razorpay.open();

                } catch (error) {
                    showMessage(error.message || 'Unable to start payment. Please try again.');
                    resetButton();
                }
            });
        });
    </script>
@endif
@endsection