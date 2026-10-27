@extends('frontend.layouts.app')

@section('title', 'My Ticket Cart')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Ticket Cart</h2>
            <p class="text-muted mb-0">Review your temple ticket members.</p>
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

    @if($cartItems->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h4>Your cart is empty</h4>
                <p class="text-muted">Choose a temple and add ticket members to continue.</p>

                <a href="{{ route('frontend.our-temples') }}" class="btn btn-danger">
                    Browse Temples
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Temple</th>
                        <th>Visit Date</th>
                        <th>Member</th>
                        <th>Ticket</th>
                        <th>Visitor Type</th>
                        <th>Age</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->temple->name ?? 'N/A' }}</td>
                            <td>{{ $item->booking_date->format('d M Y') }}</td>
                            <td>{{ $item->member_name }}</td>
                            <td>{{ strtoupper($item->ticket_type) }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->age }}</td>
                            <td>₹{{ number_format((float) $item->price, 2) }}</td>
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

                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Total</th>
                        <th colspan="2">
                            ₹{{ number_format((float) $totalAmount, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="button" id="proceed-payment" class="btn btn-danger">
                Proceed to Payment
            </button>
        </div>

        <p class="text-muted text-end mt-2">
            Complete your ticket booking securely using Razorpay.
        </p>
    @endif
</div>

@if(!$cartItems->isEmpty())
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        document.getElementById('proceed-payment').addEventListener('click', async function () {
            const button = this;
            const originalText = button.textContent;

            button.disabled = true;
            button.textContent = 'Preparing Payment...';

            try {
                const response = await fetch(
                    @json(route('frontend.ticket-payment.create-order')),
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: JSON.stringify({})
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to create payment order.');
                }

                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    name: @json(config('app.name')),
                    description: 'Temple Ticket Booking',
                    order_id: data.order_id,

                    handler: async function (payment) {
                        button.textContent = 'Verifying Payment...';

                        try {
                            const verifyResponse = await fetch(
                                @json(route('frontend.ticket-payment.verify')),
                                {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': @json(csrf_token())
                                    },
                                    body: JSON.stringify({
                                        razorpay_order_id: payment.razorpay_order_id,
                                        razorpay_payment_id: payment.razorpay_payment_id,
                                        razorpay_signature: payment.razorpay_signature
                                    })
                                }
                            );

                            const result = await verifyResponse.json();

                            if (!verifyResponse.ok || !result.success) {
                                throw new Error(
                                    result.message || 'Payment verification failed.'
                                );
                            }

                            window.location.href = result.redirect_url;
                        } catch (error) {
                            alert(error.message);
                            button.disabled = false;
                            button.textContent = originalText;
                        }
                    },

                    modal: {
                        ondismiss: function () {
                            button.disabled = false;
                            button.textContent = originalText;
                        }
                    },

                    theme: {
                        color: '#dc3545'
                    }
                };

                const razorpay = new Razorpay(options);

                razorpay.on('payment.failed', function () {
                    button.disabled = false;
                    button.textContent = originalText;
                    alert('Payment failed. Your cart has not been cleared.');
                });

                razorpay.open();
            } catch (error) {
                alert(error.message);
                button.disabled = false;
                button.textContent = originalText;
            }
        });
    </script>
@endif
@endsection