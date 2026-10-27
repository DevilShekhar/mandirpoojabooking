@extends('frontend.layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm mx-auto text-center" style="max-width: 600px;">
        <div class="card-body p-5">
            <div class="mb-3">
                <i data-feather="x-circle" class="text-danger" style="width: 60px; height: 60px;"></i>
            </div>

            <h2 class="text-danger mb-3">Payment Failed</h2>

            <p class="text-muted">
                Your payment could not be completed. Please try again.
                Your ticket cart items have not been removed.
            </p>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="d-flex justify-content-center gap-2 mt-4">
                <a href="{{ route('frontend.ticket-cart.index') }}"
                   class="btn btn-danger">
                    Return to Cart
                </a>

                <a href="{{ route('frontend.our-temples') }}"
                   class="btn btn-outline-secondary">
                    Browse Temples
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endsection