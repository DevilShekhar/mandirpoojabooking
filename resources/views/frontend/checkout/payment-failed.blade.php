@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-4 p-md-5">

                    {{-- Failure Icon --}}
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10"
                             style="width: 90px; height: 90px;">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="48" height="48"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 class="text-danger"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-danger fw-bold mb-3">
                        Payment Failed
                    </h2>

                    <p class="text-muted mb-4">
                        Sorry, your payment could not be completed.
                        Please try again or choose another payment method.
                    </p>

                    {{-- Puja Details --}}
                    @if(isset($payment))
                        <div class="bg-light rounded-3 p-3 mb-4 text-start">

                            @if($payment->puja)
                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <span class="text-muted">Puja</span>
                                    <strong class="text-end">
                                        {{ $payment->puja->name ?? 'Puja' }}
                                    </strong>
                                </div>
                            @endif

                            @if($payment->package)
                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <span class="text-muted">Package</span>
                                    <strong class="text-end">
                                        {{ $payment->package->name ?? 'Selected Package' }}
                                    </strong>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between gap-3 mb-2">
                                <span class="text-muted">Amount</span>
                                <strong>
                                    ₹{{ number_format(($payment->amount_paise ?? 0) / 100, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between gap-3 mb-2">
                                <span class="text-muted">Payment Status</span>
                                <span class="badge bg-danger">
                                    {{ ucfirst($payment->status ?? 'failed') }}
                                </span>
                            </div>

                            @if(!empty($payment->razorpay_order_id))
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="text-muted">Order ID</span>
                                    <small class="text-break text-end">
                                        {{ $payment->razorpay_order_id }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(isset($payment) && !empty($payment->error_description))
                        <div class="alert alert-warning text-start" role="alert">
                            <strong>Reason:</strong>
                            {{ $payment->error_description }}
                        </div>
                    @else
                        <div class="alert alert-warning text-start" role="alert">
                            No amount should be considered paid unless the payment
                            is confirmed by the payment gateway.
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-2">

                        <a href="{{ url()->previous() }}"
                           class="btn btn-primary btn-lg">
                            Try Again
                        </a>

                        <a href="{{ url('/') }}"
                           class="btn btn-outline-secondary">
                            Back to Home
                        </a>

                    </div>

                    <p class="small text-muted mt-4 mb-0">
                        If money was deducted from your account, please check your
                        payment status before trying again. Contact support if the
                        amount is not refunded or the payment status remains unclear.
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection