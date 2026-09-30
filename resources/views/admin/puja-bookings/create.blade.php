@can('puja-bookings.create')
@extends('admin.layouts.app')

@section('title', 'Add Puja Booking')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Puja Booking</h4>
                        <div class="card-header-action">
                            <a href="{{ route('puja-bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('puja-bookings.store') }}">
                        @csrf
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="section-title mt-0">
                                Booking Information
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Customer/User</label>
                                    <select name="user_id"
                                            id="user_id"
                                            class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-phone="{{ $user->phone }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Customer Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="customer_name"
                                           id="customer_name"
                                           class="form-control @error('customer_name') is-invalid @enderror"
                                           value="{{ old('customer_name') }}"
                                           placeholder="Enter Customer Name"
                                           required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Customer Email</label>
                                    <input type="email"
                                           name="customer_email"
                                           id="customer_email"
                                           class="form-control @error('customer_email') is-invalid @enderror"
                                           value="{{ old('customer_email') }}"
                                           placeholder="Enter Customer Email">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Customer Mobile <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="customer_mobile"
                                           id="customer_mobile"
                                           class="form-control @error('customer_mobile') is-invalid @enderror"
                                           value="{{ old('customer_mobile') }}"
                                           placeholder="Enter Customer Mobile"
                                           required>
                                    @error('customer_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Temple <span class="text-danger">*</span></label>
                                    <select name="temple_id"
                                            class="form-control @error('temple_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Temple</option>
                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Deity <span class="text-danger">*</span></label>
                                    <select name="deity_id"
                                            class="form-control @error('deity_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Deity</option>
                                        @foreach($deities as $deity)
                                            <option value="{{ $deity->id }}"
                                                {{ old('deity_id') == $deity->id ? 'selected' : '' }}>
                                                {{ $deity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('deity_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Puja <span class="text-danger">*</span></label>
                                    <select name="puja_id"
                                            class="form-control @error('puja_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Puja</option>
                                        @foreach($pujas as $puja)
                                            <option value="{{ $puja->id }}"
                                                {{ old('puja_id') == $puja->id ? 'selected' : '' }}>
                                                {{ $puja->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('puja_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Puja Package <span class="text-danger">*</span></label>
                                    <select name="puja_package_id"
                                            id="puja_package_id"
                                            class="form-control @error('puja_package_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Package</option>
                                        @foreach($pujaPackages as $package)
                                            <option value="{{ $package->id }}"
                                                    data-puja-id="{{ $package->puja_id }}"
                                                    data-package-name="{{ $package->name }}"
                                                    data-person-count="{{ $package->person_count }}"
                                                    data-price="{{ $package->price }}"
                                                    data-discount-price="{{ $package->discount_price }}"
                                                    {{ old('puja_package_id') == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('puja_package_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Package Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="package_name"
                                        id="package_name"
                                        class="form-control @error('package_name') is-invalid @enderror"
                                        value="{{ old('package_name') }}"
                                        placeholder="Select Puja Package"
                                        readonly
                                        required>
                                    @error('package_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Person Count <span class="text-danger">*</span></label>
                                    <input type="number"
                                           name="person_count"
                                           class="form-control @error('person_count') is-invalid @enderror"
                                           value="{{ old('person_count', 1) }}"
                                           min="1"
                                           required>
                                    @error('person_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Puja Schedule <span class="text-danger">*</span></label>
                                    <select name="puja_schedule_id"
                                            class="form-control @error('puja_schedule_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Schedule</option>
                                        @foreach($pujaSchedules as $schedule)
                                            <option value="{{ $schedule->id }}"
                                                {{ old('puja_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                                {{ $schedule->puja_date }}
                                                -
                                                {{ $schedule->start_time }}
                                                to
                                                {{ $schedule->end_time }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('puja_schedule_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="section-title">
                                Pricing
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Base Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="base_amount"
                                           class="form-control @error('base_amount') is-invalid @enderror"
                                           value="{{ old('base_amount', 0) }}"
                                           required>
                                    @error('base_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Discount Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="discount_amount"
                                           class="form-control @error('discount_amount') is-invalid @enderror"
                                           value="{{ old('discount_amount', 0) }}">
                                    @error('discount_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Coupon Discount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="coupon_discount"
                                           class="form-control @error('coupon_discount') is-invalid @enderror"
                                           value="{{ old('coupon_discount', 0) }}">
                                    @error('coupon_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Addon Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="addon_amount"
                                           class="form-control @error('addon_amount') is-invalid @enderror"
                                           value="{{ old('addon_amount', 0) }}">
                                    @error('addon_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Shipping Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="shipping_amount"
                                           class="form-control @error('shipping_amount') is-invalid @enderror"
                                           value="{{ old('shipping_amount', 0) }}">
                                    @error('shipping_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tax Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="tax_amount"
                                           class="form-control @error('tax_amount') is-invalid @enderror"
                                           value="{{ old('tax_amount', 0) }}">
                                    @error('tax_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Total Amount <span class="text-danger">*</span></label>
                                    <input type="number"
                                           step="0.01"
                                           name="total_amount"
                                           class="form-control @error('total_amount') is-invalid @enderror"
                                           value="{{ old('total_amount', 0) }}"
                                           required>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="section-title">
                                Booking Status
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Payment Status</label>
                                    <select name="payment_status"
                                            class="form-control @error('payment_status') is-invalid @enderror">
                                        <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Booking Status</label>
                                    <select name="booking_status"
                                            class="form-control @error('booking_status') is-invalid @enderror">
                                        <option value="new" {{ old('booking_status', 'new') == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="payment_verified" {{ old('booking_status') == 'payment_verified' ? 'selected' : '' }}>Payment Verified</option>
                                        <option value="confirmed" {{ old('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="cancelled" {{ old('booking_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="completed" {{ old('booking_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('booking_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Sankalp Status</label>
                                    <select name="sankalp_status"
                                            class="form-control @error('sankalp_status') is-invalid @enderror">
                                        <option value="pending" {{ old('sankalp_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ old('sankalp_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('sankalp_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Puja Status</label>
                                    <select name="puja_status"
                                            class="form-control @error('puja_status') is-invalid @enderror">
                                        <option value="pending" {{ old('puja_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="scheduled" {{ old('puja_status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="completed" {{ old('puja_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('puja_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Video Status</label>
                                    <select name="video_status"
                                            class="form-control @error('video_status') is-invalid @enderror">
                                        <option value="pending" {{ old('video_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="available" {{ old('video_status') == 'available' ? 'selected' : '' }}>Available</option>
                                    </select>
                                    @error('video_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Prasad Status</label>
                                    <select name="prasad_status"
                                            class="form-control @error('prasad_status') is-invalid @enderror">
                                        <option value="pending" {{ old('prasad_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="packed" {{ old('prasad_status') == 'packed' ? 'selected' : '' }}>Packed</option>
                                        <option value="shipped" {{ old('prasad_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ old('prasad_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                    @error('prasad_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('puja-bookings.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>
                            <button type="reset"
                                    class="btn btn-light mr-1">
                                Reset
                            </button>
                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('user_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        if (!this.value) {
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_email').value = '';
            document.getElementById('customer_mobile').value = '';
            return;
        }

        document.getElementById('customer_name').value = selectedOption.dataset.name || '';
        document.getElementById('customer_email').value = selectedOption.dataset.email || '';
        document.getElementById('customer_mobile').value = selectedOption.dataset.phone || '';
    });

    document.getElementById('user_id').dispatchEvent(new Event('change'));
</script>
<script>
    document.getElementById('puja_package_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        document.getElementById('package_name').value =
            selectedOption.dataset.packageName || '';
    });
</script>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
