@can('puja-bookings.edit')
@extends('admin.layouts.app')

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h2>Edit Booking</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="zmdi zmdi-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('puja-bookings.index') }}">Bookings</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Edit</strong> Booking</h2>
            </div>

            <div class="body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('puja-bookings.update', $pujaBooking->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Booking Number --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Booking Number</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $pujaBooking->booking_number }}"
                                       readonly>
                            </div>
                        </div>

                        {{-- Customer --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer</label>
                                <select name="user_id" class="form-control">
                                    <option value="">Select Customer</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $pujaBooking->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Temple --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Temple</label>
                                <select name="temple_id" class="form-control">
                                    <option value="">Select Temple</option>
                                    @foreach($temples as $temple)
                                        <option value="{{ $temple->id }}"
                                            {{ old('temple_id', $pujaBooking->temple_id) == $temple->id ? 'selected' : '' }}>
                                            {{ $temple->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Deity --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deity</label>
                                <select name="deity_id" class="form-control">
                                    <option value="">Select Deity</option>
                                    @foreach($deities as $deity)
                                        <option value="{{ $deity->id }}"
                                            {{ old('deity_id', $pujaBooking->deity_id) == $deity->id ? 'selected' : '' }}>
                                            {{ $deity->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Puja --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Puja</label>
                                <select name="puja_id" class="form-control">
                                    <option value="">Select Puja</option>
                                    @foreach($pujas as $puja)
                                        <option value="{{ $puja->id }}"
                                            {{ old('puja_id', $pujaBooking->puja_id) == $puja->id ? 'selected' : '' }}>
                                            {{ $puja->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Puja Package --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Puja Package</label>
                                <select name="puja_package_id" class="form-control">
                                    <option value="">Select Package</option>
                                    @foreach($pujaPackages as $package)
                                        <option value="{{ $package->id }}"
                                            {{ old('puja_package_id', $pujaBooking->puja_package_id) == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Schedule --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Puja Schedule</label>
                                <select name="puja_schedule_id" class="form-control">
                                    <option value="">Select Schedule</option>
                                    @foreach($pujaSchedules as $schedule)
                                        <option value="{{ $schedule->id }}"
                                            {{ old('puja_schedule_id', $pujaBooking->puja_schedule_id) == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->puja_date }} |
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Customer Name --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text"
                                       name="customer_name"
                                       class="form-control"
                                       value="{{ old('customer_name', $pujaBooking->customer_name) }}">
                            </div>
                        </div>

                        {{-- Customer Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Email</label>
                                <input type="email"
                                       name="customer_email"
                                       class="form-control"
                                       value="{{ old('customer_email', $pujaBooking->customer_email) }}">
                            </div>
                        </div>

                        {{-- Customer Mobile --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Mobile</label>
                                <input type="text"
                                       name="customer_mobile"
                                       class="form-control"
                                       value="{{ old('customer_mobile', $pujaBooking->customer_mobile) }}">
                            </div>
                        </div>

                        {{-- Package Name Snapshot --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Package Name</label>
                                <input type="text"
                                       name="package_name"
                                       class="form-control"
                                       value="{{ old('package_name', $pujaBooking->package_name) }}">
                            </div>
                        </div>

                        {{-- Person Count --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Person Count</label>
                                <input type="number"
                                       name="person_count"
                                       class="form-control"
                                       min="1"
                                       value="{{ old('person_count', $pujaBooking->person_count) }}">
                            </div>
                        </div>

                        {{-- Base Amount --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Base Amount</label>
                                <input type="number"
                                       name="base_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('base_amount', $pujaBooking->base_amount) }}">
                            </div>
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Discount Amount</label>
                                <input type="number"
                                       name="discount_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('discount_amount', $pujaBooking->discount_amount) }}">
                            </div>
                        </div>

                        {{-- Coupon Discount --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Coupon Discount</label>
                                <input type="number"
                                       name="coupon_discount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('coupon_discount', $pujaBooking->coupon_discount) }}">
                            </div>
                        </div>

                        {{-- Addon Amount --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Addon Amount</label>
                                <input type="number"
                                       name="addon_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('addon_amount', $pujaBooking->addon_amount) }}">
                            </div>
                        </div>

                        {{-- Shipping --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Shipping Amount</label>
                                <input type="number"
                                       name="shipping_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('shipping_amount', $pujaBooking->shipping_amount) }}">
                            </div>
                        </div>

                        {{-- Tax --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tax Amount</label>
                                <input type="number"
                                       name="tax_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('tax_amount', $pujaBooking->tax_amount) }}">
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input type="number"
                                       name="total_amount"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old('total_amount', $pujaBooking->total_amount) }}">
                            </div>
                        </div>

                        {{-- Payment Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Payment Status</label>
                                <select name="payment_status" class="form-control">
                                    @foreach([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                        'refunded' => 'Refunded',
                                        'partially_refunded' => 'Partially Refunded'
                                    ] as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('payment_status', $pujaBooking->payment_status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-control">
                                    <option value="">Select Method</option>
                                    <option value="razorpay" {{ old('payment_method', $pujaBooking->payment_method ?? '') == 'razorpay' ? 'selected' : '' }}>
                                        Razorpay
                                    </option>
                                    <option value="cod" {{ old('payment_method', $pujaBooking->payment_method ?? '') == 'cod' ? 'selected' : '' }}>
                                        Cash / COD
                                    </option>
                                    <option value="upi" {{ old('payment_method', $pujaBooking->payment_method ?? '') == 'upi' ? 'selected' : '' }}>
                                        UPI
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Booking Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Booking Status</label>
                                <select name="booking_status" class="form-control">
                                    @foreach([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'pandit_assigned' => 'Pandit Assigned',
                                        'sankalp_pending' => 'Sankalp Pending',
                                        'sankalp_completed' => 'Sankalp Completed',
                                        'puja_scheduled' => 'Puja Scheduled',
                                        'puja_in_progress' => 'Puja In Progress',
                                        'puja_completed' => 'Puja Completed',
                                        'video_uploaded' => 'Video Uploaded',
                                        'prasad_packed' => 'Prasad Packed',
                                        'prasad_shipped' => 'Prasad Shipped',
                                        'delivered' => 'Delivered',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled'
                                    ] as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('booking_status', $pujaBooking->booking_status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Sankalp Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sankalp Status</label>
                                <select name="sankalp_status" class="form-control">
                                    <option value="pending" {{ old('sankalp_status', $pujaBooking->sankalp_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('sankalp_status', $pujaBooking->sankalp_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('sankalp_status', $pujaBooking->sankalp_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        {{-- Puja Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Puja Status</label>
                                <select name="puja_status" class="form-control">
                                    <option value="pending" {{ old('puja_status', $pujaBooking->puja_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="scheduled" {{ old('puja_status', $pujaBooking->puja_status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ old('puja_status', $pujaBooking->puja_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('puja_status', $pujaBooking->puja_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        {{-- Video Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Video Status</label>
                                <select name="video_status" class="form-control">
                                    <option value="pending" {{ old('video_status', $pujaBooking->video_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="uploaded" {{ old('video_status', $pujaBooking->video_status) == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
                                    <option value="shared" {{ old('video_status', $pujaBooking->video_status) == 'shared' ? 'selected' : '' }}>Shared</option>
                                </select>
                            </div>
                        </div>

                        {{-- Prasad Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Prasad Status</label>
                                <select name="prasad_status" class="form-control">
                                    <option value="pending" {{ old('prasad_status', $pujaBooking->prasad_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="packed" {{ old('prasad_status', $pujaBooking->prasad_status) == 'packed' ? 'selected' : '' }}>Packed</option>
                                    <option value="shipped" {{ old('prasad_status', $pujaBooking->prasad_status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ old('prasad_status', $pujaBooking->prasad_status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Special Instructions</label>
                        <textarea name="special_instructions"
                                  class="form-control"
                                  rows="4">{{ old('special_instructions', $pujaBooking->special_instructions ?? '') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="zmdi zmdi-check"></i> Update Booking
                        </button>

                        <a href="{{ route('puja-bookings.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
