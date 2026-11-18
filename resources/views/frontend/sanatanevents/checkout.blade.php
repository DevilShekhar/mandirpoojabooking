@extends('frontend.layouts.app')
@section('title', 'Checkout - Sanatan Events')
@section('content')
@php
$user = auth()->user();
$eventName = $sanatan->name ?? 'Sanatan Event';
$price = (float) ($sanatan->price ?? 0);
$languages = $sanatan->languages ?? ($languages ?? []);
$image = $sanatan->image ?? null;
$imageUrl = $image
? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
? $image
: asset('storage/' . ltrim($image, '/')))
: null;
$creatorNames = $creatorNames ?? [];
$creatorName = $creatorNames[$sanatan->created_by] ?? 'Sanatan Team';
@endphp
<section class="se-checkout">
    <div class="container">
        <div class="se-checkout-header">
            <div>
                <div class="se-eyebrow"><span style="font-size: 35px;">ॐ</span>
 Sanatan Events</div>
                <h1 class="se-title">Checkout</h1>
                <p class="se-subtitle">Review your booking and select your preferred payment method.</p>
            </div>
            <div class="se-secure"><i class="fa fa-shield-alt"></i> Secure checkout</div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger se-alert mb-4">
            <strong>Please review the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success se-alert mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger se-alert mb-4">{{ session('error') }}</div>
        @endif
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="se-panel">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon">
                            <i class="fa fa-calendar" aria-hidden="true"></i></span> Selected event</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-event-cover">
                            @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $eventName }}" class="se-event-image"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="se-event-placeholder" style="display:none;"><span style="font-size: 35px;">ॐ</span></div>
                            @else
                            <div class="se-event-placeholder"><span style="font-size: 35px;">ॐ</span></div>
                            @endif
                            <span class="se-event-badge"><i class="fa fa-check-circle"></i> Your selection</span>
                        </div>
                        <h3 class="se-event-name">{{ $eventName }}</h3>
                        <p class="se-event-description">
                            {{ $sanatan->short_description ?? 'Review the details of your selected spiritual event before payment.' }}
                        </p>
                        <div class="se-detail-grid">
                            <div class="se-detail">
                                <span class="se-detail-label">Program type</span>
                                <span class="se-detail-value">{{ $sanatan->program_type ?? 'Not specified' }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Duration</span>
                                <span class="se-detail-value">{{ $sanatan->duration ?? 'Not specified' }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Organizer</span>
                                <span class="se-detail-value">{{ $creatorName }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Language</span>
                                <span
                                    class="se-detail-value">{{ count($languages) ? implode(', ', $languages) : 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="se-panel">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon"><i class="fa fa-user"></i></span>
                            Customer details</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-account-grid">
                            <div>
                                <label class="se-field-label">Full name</label>
                                <div class="se-field-value"><i
                                        class="fa fa-user"></i>{{ $user->name ?? 'Not available' }}</div>
                            </div>
                            <div>
                                <label class="se-field-label">Email address</label>
                                <div class="se-field-value"><i
                                        class="fa fa-envelope"></i>{{ $user->email ?? 'Not available' }}</div>
                            </div>
                            @if (!empty($user->phone))
                            <div>
                                <label class="se-field-label">Phone number</label>
                                <div class="se-field-value"><i class="fa fa-phone" aria-hidden="true"></i>{{ $user->phone }}</div>
                            </div>
                            @endif
                        </div>
                        <p class="se-note"><i class="fa fa-info-circle me-1"></i> Customer information is taken from
                            your account profile.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <aside class="se-panel se-summary">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                            Order summary</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-summary-event">
                            @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $eventName }}" class="se-thumb"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="se-thumb-placeholder" style="display:none;"><span style="font-size: 35px;">ॐ</span>
</div>
                            @else
                            <div class="se-thumb-placeholder"><span style="font-size: 35px;">ॐ</span></div>
                            @endif
                            <div>
                                <p class="se-summary-name">{{ $eventName }}</p>
                                <p class="se-muted-small">Sanatan event booking</p>
                            </div>
                        </div>
                        <div class="se-price-row">
                            <span>Event price</span>
                            <strong>₹{{ number_format($price, 2) }}</strong>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="se-field-label">Select event date</label>
                            <input type="date" id="event_date" name="event_date"
                                class="form-control" min="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_time" class="se-field-label">Select event time</label>
                            <input type="time" id="event_time" name="event_time"
                                class="form-control" required>
                        </div>
                        <div class="se-total">
                            <span class="se-total-label">Total payable</span>
                            <span class="se-total-amount">₹{{ number_format($price, 2) }}</span>
                        </div>
                        <p class="se-price-note">Review the event and total amount, then confirm your selected payment
                            method.</p>
                        <button type="button" class="se-primary-btn mt-3" id="confirm-booking-btn">
                            Confirm booking <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('confirm-booking-btn');
    const dateInput = document.getElementById('event_date');
    const timeInput = document.getElementById('event_time');
    if (!button || !dateInput || !timeInput) return;
    const csrfToken = @json(csrf_token());
    const orderUrl = @json(route('frontend.sanatanevents.payment.order', $sanatan->slug));
    const verifyUrl = @json(route('frontend.sanatanevents.payment.verify'));
    function resetButton() {
        button.disabled = false;
        button.innerHTML = 'Confirm booking <i class="fa fa-arrow-right"></i>';
    }
    button.addEventListener('click', async function () {
        if (!dateInput.value || !timeInput.value) {
            alert('Please select the event date and time.');
            return;
        }
        if (dateInput.value < dateInput.min) {
            alert('Please select a valid event date.');
            return;
        }
        if (typeof Razorpay === 'undefined') {
            alert('Payment checkout could not load. Please refresh and try again.');
            return;
        }
        button.disabled = true;
        button.innerHTML = 'Preparing payment...';
        try {
            const response = await fetch(orderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    event_date: dateInput.value,
                    event_time: timeInput.value
                })
            });
            const order = await response.json();
            if (!response.ok) {
                const validationErrors = Object.values(order.errors || {}).flat().join('\n');
                throw new Error(order.message || validationErrors || 'Unable to create payment order.');
            }
            const options = {
                key: order.key,
                amount: order.amount,
                currency: order.currency,
                name: 'Sanatan Events',
                description: order.event_name,
                order_id: order.order_id,
                prefill: {
                    name: order.customer_name || '',
                    email: order.customer_email || ''
                },
                theme: { color: '#5b45e0' },
                handler: async function (paymentResponse) {
                    button.innerHTML = 'Verifying payment...';
                    try {
                        const verifyResponse = await fetch(verifyUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(paymentResponse)
                        });
                        const result = await verifyResponse.json();
                        if (result.redirect_url) {
                            window.location.href = result.redirect_url;
                            return;
                        }
                        if (!verifyResponse.ok) {
                            throw new Error(result.message || 'Payment verification failed.');
                        }
                        throw new Error(result.message || 'Unexpected payment response.');
                    } catch (error) {
                        alert(error.message || 'Payment verification failed. If money was deducted, contact support.');
                        resetButton();
                    }
                },
                modal: {
                    ondismiss: resetButton
                }
            };
            const razorpay = new Razorpay(options);
            razorpay.on('payment.failed', function (paymentError) {
                alert(
                    paymentError.error && paymentError.error.description
                        ? paymentError.error.description
                        : 'Payment failed. Please try again.'
                );
                resetButton();
            });
            razorpay.open();
        } catch (error) {
            alert(error.message || 'Unable to start payment.');
            resetButton();
        }
    });
});
</script>
@endsection