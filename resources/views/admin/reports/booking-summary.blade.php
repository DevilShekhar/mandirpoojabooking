@can('reports.booking-summary')
@extends('admin.layouts.app')
@section('title', 'Booking Summary Report')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1">Booking Summary Report</h4>
            <p class="text-muted mb-0">
                View booking status summary module-wise.
            </p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Report</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.booking-summary.index') }}">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="temple_id">Select Temple</label>
                        <select name="temple_id"  id="temple_id" class="form-control" required>
                            <option value="">Select Temple</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ (string)($filters['temple_id'] ?? '') === (string)$temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('temple_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="from_date">From Date</label>
                        <input type="date" name="from_date" id="from_date"  class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                        @error('from_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="to_date">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                        @error('to_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mr-2 mb-2">
                            Apply Filter
                        </button>
                        <a href="{{ route('reports.booking-summary.index') }}"
                           class="btn btn-secondary mb-2">
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
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">
                            {{ $selectedTemple->name }}
                        </h5>
                        <p class="text-muted mb-0">
                            Booking Summary Report
                            @if($filters['from_date'] || $filters['to_date'])
                                |
                                {{ $filters['from_date'] ?: 'Start' }}
                                to
                                {{ $filters['to_date'] ?: 'Present' }}
                            @else
                                | All Dates
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <span class="badge badge-primary p-2">
                            Total Bookings:
                            {{ number_format($summary['total']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['pending']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Confirmed</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['confirmed']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Completed</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['completed']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Cancelled</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($summary['cancelled']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">Download Report</h5>
                        <p class="text-muted mb-0">
                            Download the filtered booking summary.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <a href="{{ route('reports.booking-summary.pdf', request()->query()) }}"
                           class="btn btn-danger mr-2 mb-2">
                            <i class="fas fa-file-pdf"></i>
                            PDF
                        </a>
                        <a href="{{ route('reports.booking-summary.excel', request()->query()) }}"
                           class="btn btn-success mr-2 mb-2">
                            <i class="fas fa-file-excel"></i>
                            Excel
                        </a>
                        <a href="{{ route('reports.booking-summary.csv', request()->query()) }}"
                           class="btn btn-primary mb-2">
                            <i class="fas fa-file-csv"></i>
                            CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Module-wise Booking Details</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="6%">#</th>
                            <th>Booking Module</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Confirmed</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Cancelled</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $module => $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>
                                        {{ $moduleNames[$module] }}
                                    </strong>
                                    @if($module === 'event')
                                        <br>
                                        <small class="text-muted">
                                            Overall events - not temple-wise
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning">
                                        {{ number_format($report['pending']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">
                                        {{ number_format($report['confirmed']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        {{ number_format($report['completed']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger">
                                        {{ number_format($report['cancelled']) }}
                                    </span>
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ number_format($report['total']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No booking records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">
                                Grand Total
                            </td>
                            <td class="text-center">
                                {{ number_format($summary['pending']) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($summary['confirmed']) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($summary['completed']) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($summary['cancelled']) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($summary['total']) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="alert alert-info mt-3">
            <strong>Note:</strong>
            Puja, Ticket and Samgri counts are filtered by the selected Temple.
            Event Booking counts are overall counts and are not filtered by Temple.
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <h5 class="mb-2">
                    Select Temple to View Booking Summary
                </h5>
                <p class="text-muted mb-0">
                    Select a temple and apply the filter to view
                    module-wise booking status counts.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
@section('styles')
<style>
    .card-statistic-1 {
        min-height: 125px;
        border-radius: 8px;
    }
    .card-statistic-1 .card-icon {
        width: 60px;
        height: 60px;
        line-height: 60px;
        border-radius: 8px;
    }
    .card-statistic-1 .card-wrap .card-header h4 {
        font-size: 14px;
        font-weight: 600;
    }
    .card-statistic-1 .card-wrap .card-body {
        font-size: 24px;
        font-weight: 700;
    }
    .table th,
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 12px;
        padding: 7px 10px;
    }
</style>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan