@can('reports.temple-performance')
@extends('admin.layouts.app')
@section('title', 'Temple Performance Report')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Temple Performance Report</h4>
            <p class="text-muted">
                View temple performance, booking status and customer details.
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Select Temple and Date Range</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.temple-performance.index') }}">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>Temple <span class="text-danger">*</span></label>
                        <select name="temple_id" class="form-control" required>
                            <option value="">Select Temple</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ (string) ($filters['temple_id'] ?? '') === (string) $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>Booking Status</label>
                        <select name="status" class="form-control">
                            <option value="all"
                                {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>
                                All Bookings
                            </option>
                            <option value="paid"
                                {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                            <option value="confirmed"
                                {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>
                            <option value="completed"
                                {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled"
                                {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex flex-wrap" style="gap:10px;">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="search"></i>
                            Get Performance
                        </button>
                        <a href="{{ route('reports.temple-performance.index') }}"
                           class="btn btn-secondary">
                            <i data-feather="refresh-ccw"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($selectedTemple)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-2">{{ $selectedTemple->name }}</h5>
                <p class="text-muted mb-1">
                    <strong>City:</strong>
                    {{ $selectedTemple->city_name ?? '-' }}
                    <span class="mx-2">|</span>
                    <strong>State:</strong>
                    {{ $selectedTemple->state_name ?? '-' }}
                </p>
                <p class="text-muted mb-0">
                    <strong>From Date:</strong>
                    {{ $filters['from_date'] ?: 'All Dates' }}
                    <span class="mx-2">|</span>
                    <strong>To Date:</strong>
                    {{ $filters['to_date'] ?: 'All Dates' }}
                </p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['total_bookings']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Paid Bookings</h4>
                        </div>
                        <div class="card-body text-success">
                            {{ number_format($summary['paid_bookings']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Revenue</h4>
                        </div>
                        <div class="card-body">
                            ₹{{ number_format($summary['total_revenue'], 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Completed Bookings</h4>
                        </div>
                        <div class="card-body text-success">
                            {{ number_format($summary['completed_bookings']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Cancelled Bookings</h4>
                        </div>
                        <div class="card-body text-danger">
                            {{ number_format($summary['cancelled_bookings']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Temple Performance Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Module</th>
                                <th>Total Bookings</th>
                                <th>Paid Bookings</th>
                                <th>Completed Bookings</th>
                                <th>Cancelled Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $report['module'] }}</td>
                                    <td>{{ number_format($report['total_bookings']) }}</td>
                                    <td>{{ number_format($report['paid_bookings']) }}</td>
                                    <td>{{ number_format($report['completed_bookings']) }}</td>
                                    <td>{{ number_format($report['cancelled_bookings']) }}</td>
                                    <td>
                                        ₹{{ number_format($report['revenue'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        No performance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <th colspan="2">Grand Total</th>
                                <th>{{ number_format($summary['total_bookings']) }}</th>
                                <th>{{ number_format($summary['paid_bookings']) }}</th>
                                <th>{{ number_format($summary['completed_bookings']) }}</th>
                                <th>{{ number_format($summary['cancelled_bookings']) }}</th>
                                <th>
                                    ₹{{ number_format($summary['total_revenue'], 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0">Customer-wise Booking Details</h5>
                <div class="d-flex flex-wrap" style="gap:8px;">
                    <a href="{{ route('reports.temple-performance.pdf', request()->query()) }}"
                       class="btn btn-danger btn-sm">
                        <i data-feather="file-text"></i>
                        PDF
                    </a>
                    <a href="{{ route('reports.temple-performance.excel', request()->query()) }}"
                       class="btn btn-success btn-sm">
                        <i data-feather="file"></i>
                        Excel
                    </a>
                    <a href="{{ route('reports.temple-performance.csv', request()->query()) }}"
                       class="btn btn-primary btn-sm">
                        <i data-feather="download"></i>
                        CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Selected Status:</strong>
                    @php
                        $selectedStatus = $filters['status'] ?? 'all';
                        $statusLabels = [
                            'all' => 'All Bookings',
                            'paid' => 'Paid',
                            'confirmed' => 'Confirmed',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',                           
                        ];
                        $statusColors = [
                            'all' => 'secondary',
                            'paid' => 'success',
                            'confirmed' => 'primary',
                            'completed' => 'success',
                            'cancelled' => 'danger',                            
                        ];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$selectedStatus] ?? 'secondary' }}">
                        {{ $statusLabels[$selectedStatus] ?? 'All Bookings' }}
                    </span>
                    <span class="text-muted ml-2">
                        Total Records: {{ number_format(count($customerBookings)) }}
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Booking Type</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerBookings as $index => $booking)
                                @php
                                    $bookingStatus = strtolower(
                                        $booking['booking_status'] ?? ''
                                    );
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>
                                            {{ $booking['customer_name'] }}
                                        </strong>
                                    </td>
                                    <td>{{ $booking['booking_type'] }}</td>
                                    <td>
                                        @if($bookingStatus === 'completed')
                                            <span class="badge badge-success">
                                                Completed
                                            </span>
                                        @elseif($bookingStatus === 'confirmed')
                                            <span class="badge badge-primary">
                                                Confirmed
                                            </span>
                                        @elseif($bookingStatus === 'cancelled')
                                            <span class="badge badge-danger">
                                                Cancelled
                                            </span>                                        
                                        @else
                                            <span class="badge badge-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $bookingStatus ?: 'Pending')) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $paymentStatus = strtolower(
                                                $booking['payment_status'] ?? ''
                                            );
                                        @endphp
                                        @if($paymentStatus === 'paid')
                                            <span class="badge badge-success">
                                                Paid
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($paymentStatus ?: '-') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        ₹{{ number_format($booking['amount'], 2) }}
                                    </td>
                                    <td>
                                        {{ $booking['created_at']
                                            ? \Carbon\Carbon::parse($booking['created_at'])->format('d-m-Y h:i A')
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <strong>No customer bookings found.</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($customerBookings) > 0)
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <th colspan="5">Total Amount</th>
                                    <th>
                                        ₹{{ number_format(collect($customerBookings)->sum('amount'), 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="alert alert-info">
            <strong>Note:</strong>
            The performance summary shows overall temple figures for the selected date range.
            The Booking Status dropdown filters the customer-wise booking details.
            Paid is determined by payment_status. Confirmed, Completed and Cancelled
            are determined by booking_status. Event bookings are excluded because they are not linked
            to a temple in the current report.
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <h5>Select a Temple to View Performance</h5>
                <p class="text-muted mb-0">
                    Select a temple and date range, then click Get Performance.
                </p>
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