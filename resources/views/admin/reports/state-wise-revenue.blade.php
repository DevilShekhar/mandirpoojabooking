@can('reports.state-wise-revenue')
@extends('admin.layouts.app')
@section('content')
<style>
    .state-report-header {
        margin-bottom: 20px;
    }

    .state-report-header h4 {
        font-weight: 700;
        color: #343a40;
    }

    .state-report-card,
    .state-filter-card,
    .state-table-card {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        margin-bottom: 20px;
    }

    .state-report-card .card-body,
    .state-filter-card .card-body,
    .state-table-card .card-body {
        padding: 20px;
    }

    .summary-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 23px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
    }

    .state-filter-card label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 7px;
    }

    .state-filter-card .form-control {
        height: 40px;
        border-radius: 5px;
        font-size: 13px;
    }

    .filter-actions,
    .export-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .state-table-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .state-table-card .card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #343a40;
    }

    #stateWiseRevenueTable {
        width: 100% !important;
        font-size: 13px;
        white-space: nowrap;
    }

    #stateWiseRevenueTable thead th {
        background: #f5f6fa;
        color: #343a40;
        font-weight: 700;
        vertical-align: middle;
    }

    #stateWiseRevenueTable tbody td {
        vertical-align: middle;
    }

    .module-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 4px;
        background: #edf2ff;
        color: #4056a1;
        font-size: 11px;
        font-weight: 600;
    }

    .export-buttons .btn {
        font-size: 12px;
        padding: 7px 12px;
    }

    .grand-total-row {
        background: #f5f6fa;
        font-weight: 700;
    }

    .state-table-card .dataTables_length,
    .state-table-card .dataTables_filter {
        margin-bottom: 15px;
        font-size: 12px;
    }

    .state-table-card .dataTables_length select,
    .state-table-card .dataTables_filter input {
        height: 34px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 9px;
    }

    .state-table-card .dataTables_info {
        font-size: 12px;
        color: #6c757d;
        padding-top: 12px;
    }

    .state-table-card .dataTables_paginate {
        padding-top: 10px;
    }

    .state-table-card .dataTables_paginate .pagination {
        justify-content: flex-end;
        margin-bottom: 0;
    }

    .state-table-card .dataTables_paginate .page-link {
        color: #495057;
        font-size: 12px;
        padding: 6px 11px;
        margin-left: 3px;
    }

    .state-table-card .dataTables_paginate .page-item.active .page-link {
        background: #6777ef;
        border-color: #6777ef;
        color: #fff;
    }

    @media (max-width: 767px) {
        .state-table-card .card-header {
            align-items: flex-start;
        }

        .state-table-card .dataTables_paginate .pagination {
            justify-content: flex-start;
        }
    }
</style>

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="state-report-header">
        <h4>State-wise Revenue Report</h4>
        <p class="text-muted mb-0">
            View Puja and Ticket bookings and revenue by State, Temple and Date Range.
        </p>
    </div>

    {{-- Filters --}}
    <div class="card state-filter-card">
        <div class="card-body">

            <form method="GET" action="{{ route('reports.state-wise-revenue.index') }}">

                <div class="row">

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <label>State <span class="text-danger">*</span></label>

                        <select name="state_id" class="form-control" required>
                            <option value="">Select State</option>

                            @foreach($states as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('state_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('state_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <label>From Date</label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                            max="{{ request('to_date') ?: date('Y-m-d') }}"
                        >

                        @error('from_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <label>To Date</label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                            min="{{ request('from_date') }}"
                            max="{{ date('Y-m-d') }}"
                        >

                        @error('to_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                {{-- Buttons Separate Row --}}
                <div class="row">
                    <div class="col-12">
                        <div class="filter-actions">

                            <button type="submit" class="btn btn-primary">
                                <i data-feather="filter"></i>
                                Apply Filter
                            </button>

                            <a href="{{ route('reports.state-wise-revenue.index') }}"
                               class="btn btn-secondary">
                                <i data-feather="refresh-ccw"></i>
                                Reset
                            </a>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if($state)

        {{-- Selected State and Date --}}
        <div class="mb-3">
            <h5 class="font-weight-bold mb-1">
                State: {{ $state->name }}
            </h5>

            <p class="text-muted mb-0">
                From:
                {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d-m-Y') : 'All Dates' }}

                &nbsp; | &nbsp;

                To:
                {{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d-m-Y') : 'All Dates' }}
            </p>
        </div>

        {{-- Summary Cards --}}
        <div class="row">

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Total Bookings</p>
                        <h4 class="summary-value">
                            {{ number_format($summary['total_bookings']) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Paid Bookings</p>
                        <h4 class="summary-value text-success">
                            {{ number_format($summary['paid_bookings']) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Puja Bookings</p>
                        <h4 class="summary-value">
                            {{ number_format($summary['puja_bookings']) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Ticket Bookings</p>
                        <h4 class="summary-value">
                            {{ number_format($summary['ticket_bookings']) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Puja Revenue</p>
                        <h4 class="summary-value">
                            ₹{{ number_format($summary['puja_revenue'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Ticket Revenue</p>
                        <h4 class="summary-value">
                            ₹{{ number_format($summary['ticket_revenue'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card state-report-card">
                    <div class="card-body">
                        <p class="summary-label">Total Revenue</p>
                        <h4 class="summary-value text-success">
                            ₹{{ number_format($summary['total_revenue'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- Revenue Table --}}
        <div class="card state-table-card">

            <div class="card-header">
                <h5>Temple-wise Booking & Revenue Details</h5>

                <div class="export-buttons">

                    <a href="{{ route('reports.state-wise-revenue.pdf', request()->query()) }}"
                       class="btn btn-danger">
                        <i data-feather="file-text"></i>
                        PDF
                    </a>

                    <a href="{{ route('reports.state-wise-revenue.excel', request()->query()) }}"
                       class="btn btn-success">
                        <i data-feather="file"></i>
                        Excel
                    </a>

                    <a href="{{ route('reports.state-wise-revenue.csv', request()->query()) }}"
                       class="btn btn-info">
                        <i data-feather="download"></i>
                        CSV
                    </a>

                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="stateWiseRevenueTable"
                           class="table table-bordered table-striped table-hover">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Temple Name</th>
                                <th>Module</th>
                                <th>Total Bookings</th>
                                <th>Paid Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($rows as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row['temple_name'] }}</td>
                                    <td>
                                        <span class="module-badge">
                                            {{ $row['module'] }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($row['total_bookings']) }}</td>
                                    <td>{{ number_format($row['paid_bookings']) }}</td>
                                    <td class="text-nowrap">
                                        ₹{{ number_format($row['revenue'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="grand-total-row">
                                <th colspan="3" class="text-right">Grand Total</th>
                                <th>{{ number_format($summary['total_bookings']) }}</th>
                                <th>{{ number_format($summary['paid_bookings']) }}</th>
                                <th>₹{{ number_format($summary['total_revenue'], 2) }}</th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>

    @else

        <div class="card state-report-card">
            <div class="card-body text-center py-5">
                <h5 class="mb-2">Select State to View Revenue Report</h5>
                <p class="text-muted mb-0">
                    Select a State and optionally choose From Date and To Date, then click Apply Filter.
                </p>
            </div>
        </div>

    @endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        if ($('#stateWiseRevenueTable').length) {

            if ($.fn.DataTable.isDataTable('#stateWiseRevenueTable')) {
                $('#stateWiseRevenueTable').DataTable().destroy();
            }

            $('#stateWiseRevenueTable').DataTable({
                processing: true,
                serverSide: false,
                pageLength: 10,

                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                ordering: true,
                searching: true,
                paging: true,
                info: true,
                autoWidth: false,
                responsive: false,

                order: [[1, 'asc']],

                columnDefs: [
                    {
                        targets: [0],
                        orderable: false,
                        searchable: false
                    }
                ],

                language: {
                    emptyTable: "No booking records found",
                    zeroRecords: "No matching records found",
                    search: "Search:",
                    searchPlaceholder: "Search temple or module...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "Showing 0 to 0 of 0 records",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },

                drawCallback: function () {
                    var api = this.api();
                    var start = api.page.info().start;

                    api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                }
            });
        }

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan