@can('reports-event.index')
@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-1">Completed Event Booking Report</h4>
            <p class="text-muted mb-0">
                View and download completed event booking details.
            </p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Report</h5>
        </div>

        <div class="card-body">
            <form method="GET"
                  action="{{ route('reports.event-booking-completed.index') }}">

                <div class="row">

                    {{-- Event Creator --}}
                    <div class="col-md-4 mb-3">
                        <label for="event_creator_id" class="form-label">
                            Event Creator <span class="text-danger">*</span>
                        </label>

                        <select name="event_creator_id"
                                id="event_creator_id"
                                class="form-control">
                            <option value="">Select Event Creator</option>

                            @foreach($eventCreators as $creator)
                                <option value="{{ $creator->id }}"
                                    {{ (string) request('event_creator_id') === (string) $creator->id ? 'selected' : '' }}>
                                    {{ $creator->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('event_creator_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-3 mb-3">
                        <label for="from_date" class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               id="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                        @error('from_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-3 mb-3">
                        <label for="to_date" class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               id="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                        @error('to_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            Search
                        </button>

                        <a href="{{ route('reports.event-booking-completed.index') }}"
                           class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @if(request()->filled('event_creator_id'))

        {{-- Summary Cards --}}
        <div class="row mb-4">

            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-left-primary h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Completed Bookings
                        </h6>

                        <h3 class="mb-0">
                            {{ number_format($totalBookings ?? 0) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-left-success h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Amount
                        </h6>

                        <h3 class="mb-0">
                            ₹{{ number_format((float) ($totalAmount ?? 0), 2) }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        {{-- Export Buttons --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div class="mb-2">
                        <h5 class="mb-0">Completed Booking Details</h5>
                        <small class="text-muted">
                            Download the filtered report.
                        </small>
                    </div>

                    <div class="mb-2">

                        <a href="{{ route('reports.event-booking-completed.pdf', request()->query()) }}"
                           class="btn btn-danger mr-2">
                            Download PDF
                        </a>

                        <a href="{{ route('reports.event-booking-completed.csv', request()->query()) }}"
                           class="btn btn-success mr-2">
                            Download CSV
                        </a>

                        <a href="{{ route('reports.event-booking-completed.excel', request()->query()) }}"
                           class="btn btn-primary">
                            Download Excel
                        </a>

                    </div>

                </div>
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">

                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Booking ID</th>
                                <th>Event Name</th>
                                <th>Customer Name</th>
                                <th>Event Creator</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Completed Date</th>
                                <th>Completed By</th>
                                <th>Booking Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($bookings as $index => $booking)
                                <tr>
                                    <td>
                                        {{ $bookings instanceof \Illuminate\Pagination\AbstractPaginator
                                            ? $bookings->firstItem() + $index
                                            : $index + 1 }}
                                    </td>

                                    <td>
                                        {{ $booking->id }}
                                    </td>

                                    <td>
                                        {{ $booking->event_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->customer_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->event_creator_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->event_date
                                            ? \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y')
                                            : 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->event_time ?? 'N/A' }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) ($booking->amount ?? 0), 2) }}
                                    </td>

                                    <td>
                                        @php
                                            $paymentStatus = strtolower($booking->payment_status ?? 'pending');

                                            $paymentBadge = match ($paymentStatus) {
                                                'paid', 'success', 'captured', 'completed' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'info',
                                                default => 'warning',
                                            };
                                        @endphp

                                        <span class="badge badge-{{ $paymentBadge }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $booking->completed_at
                                            ? \Carbon\Carbon::parse($booking->completed_at)->format('d-m-Y h:i A')
                                            : 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->completed_by_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <span class="badge badge-success">
                                            {{ ucfirst($booking->booking_status ?? 'completed') }}
                                        </span>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        No completed event bookings found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                        @if($bookings->count() > 0)
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td colspan="7" class="text-right">
                                        Total Amount
                                    </td>
                                    <td colspan="5">
                                        ₹{{ number_format((float) ($totalAmount ?? 0), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif

                    </table>
                </div>

                {{-- Pagination --}}
                @if($bookings instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                        <div class="text-muted mb-2">
                            Showing
                            {{ $bookings->firstItem() ?? 0 }}
                            to
                            {{ $bookings->lastItem() ?? 0 }}
                            of
                            {{ $bookings->total() }}
                            bookings
                        </div>

                        <div class="mb-2">
                            {{ $bookings->links() }}
                        </div>

                    </div>
                @endif

            </div>
        </div>

    @else

        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h5 class="text-muted">
                    Please select an Event Creator to view completed bookings.
                </h5>
            </div>
        </div>

    @endif

</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan