@extends('admin.layouts.app')

@section('content')

<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Booking Details</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('puja-bookings.index') }}">Puja Bookings</a>
                </li>
                <li class="breadcrumb-item active">Details</li>
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">

    <div class="col-lg-12">

        <div class="card">
            <div class="header">
                <h2>
                    Booking #{{ $pujaBooking->booking_number }}
                </h2>

                <ul class="header-dropdown">
                    <li>
                        <a href="{{ route('puja-bookings.edit', $pujaBooking) }}"
                           class="btn btn-warning">
                            <i data-feather="edit"></i> Edit
                        </a>
                    </li>
                </ul>
            </div>

            <div class="body">

                <div class="row">

                    <div class="col-md-6">
                        <strong>Customer Name</strong>
                        <p>{{ $pujaBooking->customer_name }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Mobile</strong>
                        <p>{{ $pujaBooking->customer_mobile }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Email</strong>
                        <p>{{ $pujaBooking->customer_email ?? '-' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Temple</strong>
                        <p>{{ $pujaBooking->temple?->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Deity</strong>
                        <p>{{ $pujaBooking->deity?->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Puja</strong>
                        <p>{{ $pujaBooking->puja?->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Package</strong>
                        <p>{{ $pujaBooking->package_name }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Person Count</strong>
                        <p>{{ $pujaBooking->person_count }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Schedule</strong>

                        @if($pujaBooking->pujaSchedule)
                            <p>
                                {{ $pujaBooking->pujaSchedule->puja_date }}
                                <br>
                                {{ $pujaBooking->pujaSchedule->start_time }}
                                -
                                {{ $pujaBooking->pujaSchedule->end_time }}
                            </p>
                        @else
                            <p>-</p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <strong>Total Amount</strong>
                        <p>
                            ₹{{ number_format($pujaBooking->total_amount, 2) }}
                        </p>
                    </div>

                </div>

                <hr>

                <h5>Pricing</h5>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Base Amount</th>
                            <td>₹{{ number_format($pujaBooking->base_amount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Discount</th>
                            <td>₹{{ number_format($pujaBooking->discount_amount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Coupon Discount</th>
                            <td>₹{{ number_format($pujaBooking->coupon_discount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Addon Amount</th>
                            <td>₹{{ number_format($pujaBooking->addon_amount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Shipping</th>
                            <td>₹{{ number_format($pujaBooking->shipping_amount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Tax</th>
                            <td>₹{{ number_format($pujaBooking->tax_amount, 2) }}</td>
                        </tr>

                        <tr>
                            <th>Total</th>
                            <th>₹{{ number_format($pujaBooking->total_amount, 2) }}</th>
                        </tr>
                    </table>
                </div>

                <hr>

                <h5>Workflow Status</h5>

                <div class="row">

                    <div class="col-md-4">
                        <strong>Payment</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->payment_status)) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Booking</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->booking_status)) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Sankalp</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->sankalp_status)) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Puja</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->puja_status)) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Video</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->video_status)) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Prasad</strong>
                        <p>{{ ucwords(str_replace('_', ' ', $pujaBooking->prasad_status)) }}</p>
                    </div>

                </div>

                <hr>

                <h5>Devotees</h5>

                @if($pujaBooking->devotees->count())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Relation</th>
                                    <th>Gender</th>
                                    <th>Gotra</th>
                                    <th>Rashi</th>
                                    <th>Nakshatra</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($pujaBooking->devotees as $devotee)
                                    <tr>
                                        <td>{{ $devotee->name }}</td>
                                        <td>{{ $devotee->relation ?? '-' }}</td>
                                        <td>{{ $devotee->gender ?? '-' }}</td>
                                        <td>{{ $devotee->gotra ?? '-' }}</td>
                                        <td>{{ $devotee->rashi ?? '-' }}</td>
                                        <td>{{ $devotee->nakshatra ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No devotees added.</p>
                @endif

                <a href="{{ route('puja-bookings.index') }}"
                   class="btn btn-secondary mt-3">
                    Back
                </a>

            </div>
        </div>

    </div>

</div>

@endsection
