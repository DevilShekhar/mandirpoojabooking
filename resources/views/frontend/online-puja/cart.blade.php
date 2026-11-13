
@extends('frontend.layouts.app')

@section('title', 'Your Puja Cart')

@section('content')
<section class="puja-cart-section">
    <div class="container">
        <div class="puja-cart-header">
            <span class="cart-eyebrow">YOUR DIVINE JOURNEY</span>
            <h1>Review Your Puja Booking</h1>
            <p>Confirm your selected puja and participation package.</p>
        </div>

        @if(session('error'))
            <div class="cart-alert cart-alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="cart-alert cart-alert-success">{{ session('success') }}</div>
        @endif

        <div class="puja-cart-layout">
            <div class="puja-cart-main">
                <div class="puja-cart-card">
                    <div class="cart-card-heading">
                        <h2>Selected Puja</h2>
                        <span class="cart-status">Selected</span>
                    </div>

                    <div class="cart-puja-details">
                        @php
                            $image = $puja->thumbnail_image ?: $puja->banner_image;

                            if (is_array($image)) {
                                $image = $image['image'] ?? $image['path'] ?? $image['url'] ?? null;
                            }

                            $imageUrl = $image
                                ? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                                    ? $image
                                    : asset('storage/' . ltrim($image, '/')))
                                : null;
                        @endphp

                        <div class="cart-puja-image">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $puja->title }}">
                            @else
                                <div class="cart-image-placeholder">
                                    <i class="fa fa-place-of-worship"></i>
                                </div>
                            @endif
                        </div>

                        <div class="cart-puja-info">
                            <span class="cart-category">{{ $puja->category->name ?? 'Sacred Puja' }}</span>
                            <h3>{{ $puja->title }}</h3>

                            @if($puja->temple_name)
                                <p><i class="fa fa-place-of-worship"></i> {{ $puja->temple_name }}</p>
                            @endif

                            @if($puja->puja_datetime)
                                <p><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($puja->puja_datetime)->format('d M Y, h:i A') }}</p>
                            @endif

                            @if($puja->temple_city || $puja->temple_state)
                                <p><i class="fa fa-map-marker-alt"></i> {{ collect([$puja->temple_city, $puja->temple_state])->filter()->implode(', ') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="puja-cart-card">
                    <div class="cart-card-heading">
                        <h2>Participation Package</h2>
                    </div>

                    <div class="cart-package-details">
                        <div class="cart-package-info">
                            <span class="cart-package-label">Selected Package</span>
                            <h3>{{ $package->package_name }}</h3>

                            @if($package->description)
                                <p>{{ $package->description }}</p>
                            @endif

                            @if($package->devotees_count)
                                <span class="cart-devotees">
                                    <i class="fa fa-users"></i>
                                    {{ $package->devotees_count }}
                                    {{ (int) $package->devotees_count === 1 ? 'Devotee' : 'Devotees' }}
                                </span>
                            @endif
                        </div>

                        <div class="cart-package-price">
                            <span>Package Amount</span>
                            <strong>₹{{ number_format($package->price, 2) }}</strong>
                        </div>
                    </div>

                    <div class="cart-change-package">
                        <a href="{{ route('frontend.online-puja.show', $puja->slug) }}">
                            <i class="fa fa-edit"></i> Change Package
                        </a>
                    </div>
                </div>
            </div>

            <aside class="puja-cart-sidebar">
                <div class="cart-summary-card">
                    <h2>Booking Summary</h2>

                    <div class="cart-summary-row">
                        <span>Puja</span>
                        <strong>{{ $puja->title }}</strong>
                    </div>

                    <div class="cart-summary-row">
                        <span>Package</span>
                        <strong>{{ $package->package_name }}</strong>
                    </div>

                    <div class="cart-summary-row">
                        <span>Participation</span>
                        <strong>{{ $package->devotees_count ?? 1 }} {{ (int) ($package->devotees_count ?? 1) === 1 ? 'Devotee' : 'Devotees' }}</strong>
                    </div>

                    <div class="cart-summary-divider"></div>

                    <div class="cart-total-row">
                        <span>Total Amount</span>
                        <strong>₹{{ number_format($package->price, 2) }}</strong>
                    </div>

                    <button type="button" class="cart-checkout-btn" id="continueToPayment">
                        <span>Continue to Payment</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>

                    <p class="cart-payment-note">
                        <i class="fa fa-lock"></i>
                        Secure payment powered by Razorpay
                    </p>

                    <form method="POST" action="{{ route('frontend.online-puja.cart.remove') }}" id="removePackageForm">
                        @csrf
                        <button type="submit" class="cart-remove-btn">
                            <i class="fa fa-trash"></i> Remove Package
                        </button>
                    </form>
                </div>

                <div class="cart-trust-card">
                    <div><i class="fa fa-check-circle"></i> Authentic Vedic Rituals</div>
                    <div><i class="fa fa-user-check"></i> Experienced Priests</div>
                    <div><i class="fa fa-lock"></i> Secure Booking Process</div>
                </div>
            </aside>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentButton = document.getElementById('continueToPayment');
    const removeForm = document.getElementById('removePackageForm');

    if (removeForm) {
        removeForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const result = await Swal.fire({
                icon: 'warning',
                title: 'Remove Package?',
                text: 'Are you sure you want to remove this package from your cart?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Keep Package',
                confirmButtonColor: '#a04435',
                cancelButtonColor: '#817b70',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                removeForm.submit();
            }
        });
    }

    if (!paymentButton) {
        return;
    }

    paymentButton.addEventListener('click', async function () {
        const originalButton = paymentButton.innerHTML;
        const csrfToken = @json(csrf_token());

        paymentButton.disabled = true;
        paymentButton.innerHTML = '<span>Creating Payment...</span>';

        try {
            const checkoutResponse = await fetch(@json(route('frontend.online-puja.cart.checkout')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const checkoutData = await checkoutResponse.json();

            if (!checkoutResponse.ok || !checkoutData.success) {
                throw new Error(checkoutData.message || 'Unable to start payment.');
            }

            if (typeof Razorpay === 'undefined') {
                throw new Error('Payment gateway could not be loaded. Please refresh the page.');
            }

            const options = {
                key: checkoutData.key,
                amount: checkoutData.amount,
                currency: checkoutData.currency,
                name: 'Online Puja',
                description: checkoutData.puja_title + ' - ' + checkoutData.package_name,
                order_id: checkoutData.razorpay_order_id,
                prefill: {
                    name: checkoutData.customer_name || '',
                    email: checkoutData.customer_email || '',
                    contact: checkoutData.customer_phone || ''
                },
                notes: {
                    order_number: checkoutData.order_number
                },
                theme: {
                    color: '#a84e20'
                },
                modal: {
                    ondismiss: function () {
                        paymentButton.disabled = false;
                        paymentButton.innerHTML = originalButton;
                    }
                },
                handler: async function (response) {
                    paymentButton.innerHTML = '<span>Verifying Payment...</span>';

                    try {
                        const verifyResponse = await fetch(@json(route('frontend.online-puja.payment.verify')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                order_number: checkoutData.order_number,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        });

                        const verifyData = await verifyResponse.json();

                        if (!verifyResponse.ok || !verifyData.success) {
                            throw new Error(verifyData.message || 'Payment verification failed.');
                        }

                        await Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful!',
                            html: '<p style="margin:0 0 12px;color:#666;">Your Online Puja booking has been confirmed successfully.</p><div style="padding:12px;background:#f8f5ef;border-radius:8px;"><strong>Order Number</strong><br>' + checkoutData.order_number + '</div>',
                            confirmButtonText: 'Continue',
                            confirmButtonColor: '#a84e20',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                        window.location.href = verifyData.redirect_url;
                    } catch (error) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Payment Verification Issue',
                            text: error.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#a84e20'
                        });

                        paymentButton.disabled = false;
                        paymentButton.innerHTML = originalButton;
                    }
                }
            };

            const razorpay = new Razorpay(options);

            razorpay.on('payment.failed', async function (response) {
                const message = response.error && response.error.description
                    ? response.error.description
                    : 'Payment failed. Please try again.';

                await Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed!',
                    text: message,
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#a84e20'
                });

                paymentButton.disabled = false;
                paymentButton.innerHTML = originalButton;
            });

            razorpay.open();
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Something Went Wrong!',
                text: error.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#a84e20'
            });

            paymentButton.disabled = false;
            paymentButton.innerHTML = originalButton;
        }
    });
});
</script>
@endsection

@push('styles')
<style>
.puja-cart-section{padding:55px 0 80px;background:#fbf8f3;min-height:65vh;color:#28251f}
.puja-cart-header{text-align:center;margin-bottom:35px}
.cart-eyebrow{color:#a84e20;font-size:12px;font-weight:700;letter-spacing:2px}
.puja-cart-header h1{font-size:34px;font-weight:700;margin:10px 0;color:#28251f}
.puja-cart-header p{color:#817b70;margin:0}
.puja-cart-layout{display:grid;grid-template-columns:minmax(0,1.7fr) minmax(300px,.9fr);gap:25px;align-items:start}
.puja-cart-main{display:flex;flex-direction:column;gap:20px}
.puja-cart-card,.cart-summary-card,.cart-trust-card{background:#fff;border:1px solid #eee6d9;border-radius:14px;padding:25px;box-shadow:0 12px 35px rgba(65,42,18,.04)}
.cart-card-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.cart-card-heading h2,.cart-summary-card h2{font-size:19px;font-weight:700;margin:0}
.cart-status{background:#eaf6ee;color:#38835c;padding:6px 12px;border-radius:30px;font-size:12px;font-weight:600}
.cart-puja-details{display:flex;gap:20px;align-items:center}
.cart-puja-image{width:145px;height:125px;flex-shrink:0;border-radius:10px;overflow:hidden;background:#f5eee2}
.cart-puja-image img{width:100%;height:100%;object-fit:cover}
.cart-image-placeholder{height:100%;display:flex;align-items:center;justify-content:center;color:#a84e20;font-size:35px}
.cart-category{font-size:12px;color:#a84e20;font-weight:700;text-transform:uppercase;letter-spacing:1px}
.cart-puja-info h3{font-size:21px;font-weight:700;margin:7px 0 12px}
.cart-puja-info p{font-size:13px;color:#817b70;margin:6px 0}
.cart-puja-info p i{width:18px;color:#a84e20}
.cart-package-details{display:flex;justify-content:space-between;gap:20px;align-items:center}
.cart-package-label,.cart-package-price span{font-size:12px;color:#817b70}
.cart-package-details h3{font-size:19px;margin:7px 0;font-weight:700}
.cart-package-details p{font-size:13px;color:#817b70;margin:5px 0}
.cart-devotees{display:inline-block;margin-top:8px;font-size:13px;color:#817b70}
.cart-devotees i{color:#a84e20;margin-right:5px}
.cart-package-price{text-align:right;white-space:nowrap}
.cart-package-price strong{display:block;color:#a84e20;font-size:22px;margin-top:6px}
.cart-change-package{border-top:1px solid #eee6d9;margin-top:20px;padding-top:15px}
.cart-change-package a{color:#a84e20;font-size:13px;font-weight:600;text-decoration:none}
.puja-cart-sidebar{display:flex;flex-direction:column;gap:18px}
.cart-summary-card h2{margin-bottom:22px}
.cart-summary-row{display:flex;justify-content:space-between;gap:15px;margin:15px 0;font-size:13px;color:#817b70}
.cart-summary-row strong{color:#28251f;text-align:right;max-width:60%;overflow-wrap:anywhere}
.cart-summary-divider{height:1px;background:#eee6d9;margin:20px 0}
.cart-total-row{display:flex;justify-content:space-between;align-items:center;font-weight:700}
.cart-total-row strong{font-size:23px;color:#a84e20}
.cart-checkout-btn{width:100%;border:0;border-radius:8px;background:#a84e20;color:#fff;padding:15px 18px;font-size:14px;font-weight:700;margin-top:22px;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer;transition:.2s}
.cart-checkout-btn:hover{background:#783514}
.cart-checkout-btn:disabled{opacity:.65;cursor:not-allowed}
.cart-payment-note{font-size:11px;color:#817b70;text-align:center;margin:12px 0 18px}
.cart-payment-note i{color:#38835c;margin-right:5px}
.cart-remove-btn{width:100%;background:#fff;border:1px solid #e8d8d2;color:#a04435;border-radius:8px;padding:11px;font-size:13px;font-weight:600;cursor:pointer}
.cart-trust-card{display:flex;flex-direction:column;gap:13px;padding:20px}
.cart-trust-card div{font-size:13px;color:#625d54}
.cart-trust-card i{color:#38835c;margin-right:8px}
.cart-alert{padding:13px 16px;border-radius:8px;margin-bottom:20px}
.cart-alert-error{background:#fff0ef;color:#a04435}
.cart-alert-success{background:#eaf6ee;color:#28754c}
@media(max-width:767px){.puja-cart-section{padding:35px 0 55px}.puja-cart-header h1{font-size:26px}.puja-cart-layout{grid-template-columns:1fr}.puja-cart-card,.cart-summary-card{padding:18px}.cart-puja-details{align-items:flex-start;gap:13px}.cart-puja-image{width:105px;height:105px}.cart-puja-info h3{font-size:17px}.cart-package-details{align-items:flex-start;flex-direction:column}.cart-package-price{text-align:left}.cart-package-price strong{font-size:20px}}
</style>
@endpush