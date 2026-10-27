
@extends('frontend.layouts.app')

@section('title', 'Booking Successful')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm mx-auto text-center" style="max-width: 700px;">
        <div class="card-body p-5">
            <h2 class="text-success mb-3">Booking Successful!</h2>

            <p>{{ session('success', 'Your tickets have been booked successfully.') }}</p>

            @if($bookings->isNotEmpty())
                <div class="table-responsive mt-4">
                    <table class="table table-bordered text-start">
                        <thead class="table-light">
                            <tr>
                                <th>Booking Reference</th>
                                <th>Temple</th>
                                <th>Member</th>
                                <th>Visit Date</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_reference }}</td>
                                    <td>{{ $booking->temple->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->member_name }}</td>
                                    <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td>₹{{ number_format((float) $booking->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <a href="{{ route('frontend.our-temples') }}" class="btn btn-danger mt-3">
                Browse Temples
            </a>
        </div>
    </div>
</div>
@endsection