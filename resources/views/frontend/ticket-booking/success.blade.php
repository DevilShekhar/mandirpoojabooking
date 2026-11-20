@extends('frontend.layouts.app')
@section('title', 'Booking Successful')
@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto text-center" style="max-width: 650px; border-radius: 12px;">
        <div class="card-body p-5">
            <div class="mb-4">
                <i class="fa fa-check-circle text-success"
                   style="font-size: 75px;"></i>
            </div>
            <h2 class="text-success mb-3">
                Booking Successful!
            </h2>
            <h5 class="mb-3">
                Hello, {{ $user->name ?? 'Customer' }}!
            </h5>
            <p class="text-muted mb-4">
                Thank you for booking with us.
                Your temple ticket booking has been completed successfully.
                We look forward to your visit!
            </p>
            <a href="{{ route('frontend.our-temples') }}"
               class="btn btn-danger px-4">
                <i class="fas fa-place-of-worship mr-1"></i>
                Browse Temples
            </a>
        </div>
    </div>
</div>
@endsection