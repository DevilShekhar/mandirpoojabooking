@extends('admin.layouts.app')

@section('content')

<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Puja Bookings</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">Puja Bookings</li>
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Puja Bookings</h2>

                <ul class="header-dropdown">
                    <li>
                        <a href="{{ route('puja-bookings.create') }}"
                           class="btn btn-primary">
                            <i data-feather="plus"></i> Add Booking
                        </a>
                    </li>
                </ul>
            </div>

            <div class="body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Number</th>
                                <th>Customer</th>
                                <th>Temple</th>
                                <th>Puja</th>
                                <th>Package</th>
                                <th>Schedule</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $booking->booking_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $booking->customer_name }}

                                        @if($booking->customer_mobile)
                                            <br>
                                            <small>
                                                {{ $booking->customer_mobile }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $booking->temple?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $booking->puja?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $booking->package_name ?? $booking->pujaPackage?->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if($booking->pujaSchedule)
                                            {{ $booking->pujaSchedule->puja_date }}
                                            <br>
                                            <small>
                                                {{ $booking->pujaSchedule->start_time }}
                                                -
                                                {{ $booking->pujaSchedule->end_time }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        ₹{{ number_format($booking->total_amount, 2) }}
                                    </td>

                                    <td>
                                        @if($booking->payment_status == 'paid')
                                            <span class="badge badge-success">
                                                Paid
                                            </span>
                                        @elseif($booking->payment_status == 'failed')
                                            <span class="badge badge-danger">
                                                Failed
                                            </span>
                                        @elseif($booking->payment_status == 'refunded')
                                            <span class="badge badge-warning">
                                                Refunded
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($booking->booking_status == 'confirmed')
                                            <span class="badge badge-success">
                                                Confirmed
                                            </span>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <span class="badge badge-danger">
                                                Cancelled
                                            </span>
                                        @elseif($booking->booking_status == 'completed')
                                            <span class="badge badge-primary">
                                                Completed
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                {{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('puja-bookings.show', $booking) }}"
                                           class="btn btn-sm btn-info">
                                            <i data-feather="eye"></i>
                                        </a>

                                        <a href="{{ route('puja-bookings.edit', $booking) }}"
                                           class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>
                                        </a>

                                        @if($booking->booking_status != 'cancelled')
                                            <form action="{{ route('puja-bookings.destroy', $booking) }}"
                                                  method="POST"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
