@can('reports.daily-to-yearly-booking-revenue')
@extends('admin.layouts.app')
@section('title', 'Daily / Weekly / Monthly / Yearly Booking Report')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Daily / Weekly / Monthly / Yearly Booking Report</h4>
            <p class="text-muted">View booking counts by selected period and date range.</p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.booking-period.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Report Type</label>
                        <select name="period" class="form-control">
                            <option value="daily" {{ ($filters['period'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ ($filters['period'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ ($filters['period'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ ($filters['period'] ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="datewise" {{ ($filters['period'] ?? '') == 'datewise' ? 'selected' : '' }}>Date-wise</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>From Date</label>
                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>To Date</label>
                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            Apply Filter
                        </button>
                        <a href="{{ route('reports.booking-period.index') }}"
                           class="btn btn-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(count($rows) > 0)
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Bookings</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['total']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending</h4>
                        </div>
                        <div class="card-body text-warning">
                            {{ number_format($summary['pending']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Completed</h4>
                        </div>
                        <div class="card-body text-success">
                            {{ number_format($summary['completed']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-statistic-1">
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Cancelled</h4>
                        </div>
                        <div class="card-body text-danger">
                            {{ number_format($summary['cancelled']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0">Booking Report</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.booking-period.pdf', request()->query()) }}"
                       class="btn btn-danger btn-sm">
                        Download PDF
                    </a>
                    <a href="{{ route('reports.booking-period.excel', request()->query()) }}"
                       class="btn btn-success btn-sm">
                        Download Excel
                    </a>
                    <a href="{{ route('reports.booking-period.csv', request()->query()) }}"
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
                                <th>Period</th>
                                <th>Booking Module</th>
                                <th>Pending</th>
                                <th>Confirmed</th>
                                <th>Completed</th>
                                <th>Cancelled</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row['period'] }}</td>
                                    <td>{{ $row['module'] }}</td>
                                    <td>{{ number_format($row['pending']) }}</td>
                                    <td>{{ number_format($row['confirmed']) }}</td>
                                    <td>{{ number_format($row['completed']) }}</td>
                                    <td>{{ number_format($row['cancelled']) }}</td>
                                    <td><strong>{{ number_format($row['total']) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <th colspan="3">Grand Total</th>
                                <th>{{ number_format($summary['pending']) }}</th>
                                <th>{{ number_format($summary['confirmed']) }}</th>
                                <th>{{ number_format($summary['completed']) }}</th>
                                <th>{{ number_format($summary['cancelled']) }}</th>
                                <th>{{ number_format($summary['total']) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Module-wise Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Booking Module</th>
                                <th>Pending</th>
                                <th>Confirmed</th>
                                <th>Completed</th>
                                <th>Cancelled</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moduleNames as $module => $name)
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td>{{ number_format($moduleSummary[$module]['pending']) }}</td>
                                    <td>{{ number_format($moduleSummary[$module]['confirmed']) }}</td>
                                    <td>{{ number_format($moduleSummary[$module]['completed']) }}</td>
                                    <td>{{ number_format($moduleSummary[$module]['cancelled']) }}</td>
                                    <td><strong>{{ number_format($moduleSummary[$module]['total']) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <h5>No Booking Data Found</h5>
                <p class="text-muted mb-0">
                    Select a report type and date range, then click Apply Filter.
                </p>
            </div>
        </div>
    @endif
    <div class="alert alert-info mt-3">
        <strong>Note:</strong> Event bookings are independent and are included in the overall report.
        Samgri verification dates are based on the related Puja booking's creation date.
        Ticket bookings are counted by distinct Razorpay order ID.
    </div>
</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan