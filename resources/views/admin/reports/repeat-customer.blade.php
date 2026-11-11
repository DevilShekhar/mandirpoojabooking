@can('reports.repeat-customer')
@extends('admin.layouts.app')
@section('title', 'Repeat Customer Report')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Repeat Customer Report</h4>
            <p class="text-muted">
                Customer-wise repeat booking details with Temple, City and State.
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.repeat-customer.index') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>From Date</label>
                        <input type="date" name="from_date"  class="form-control" value="{{ $filters['from_date'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control"  value="{{ $filters['to_date'] }}">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            Apply Filter
                        </button>
                        <a href="{{ route('reports.repeat-customer.index') }}"  class="btn btn-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Repeat Customer Entries</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($summary['repeat_customer_entries']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Unique Repeat Customers</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($summary['unique_customers']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Repeat Bookings</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($summary['total_bookings']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Customer-wise Booking Details</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('reports.repeat-customer.pdf', request()->query()) }}"
                   class="btn btn-danger btn-sm">
                    Download PDF
                </a>
                <a href="{{ route('reports.repeat-customer.excel', request()->query()) }}"
                   class="btn btn-success btn-sm">
                    Download Excel
                </a>
                <a href="{{ route('reports.repeat-customer.csv', request()->query()) }}"
                   class="btn btn-primary btn-sm">
                    Download CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Booking Type</th>
                            <th>Temple Name</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Booking Count</th>
                            <th>Last Booking Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['customer_name'] }}</td>
                                <td>{{ $row['booking_type'] }}</td>
                                <td>{{ $row['temple_name'] }}</td>
                                <td>{{ $row['city_name'] }}</td>
                                <td>{{ $row['state_name'] }}</td>
                                <td>
                                    <strong>{{ number_format($row['booking_count']) }}</strong>
                                </td>
                                <td>
                                    {{ $row['last_booking'] ? \Carbon\Carbon::parse($row['last_booking'])->format('d-m-Y h:i A') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    No repeat customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($rows) > 0)
                        <tfoot>
                            <tr class="font-weight-bold">
                                <th colspan="6">Total</th>
                                <th>{{ number_format($summary['total_bookings']) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="alert alert-info mt-3">
        <strong>Note:</strong>
        Only customers with at least two bookings in the same module and temple are displayed.
        Event bookings are independent and are not associated with a Temple in the current database structure.
        Samgri verification is excluded to avoid duplicate Puja booking counts.
    </div>
</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan