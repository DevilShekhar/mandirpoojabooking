@can('reports.payment-transactions')
@extends('admin.layouts.app')
@section('content')
<style>
    .payment-report-header {
        margin-bottom: 20px;
    }

    .payment-report-header h4 {
        font-weight: 700;
        color: #343a40;
        margin-bottom: 5px;
    }

    .payment-report-card,
    .payment-filter-card,
    .payment-table-card {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        margin-bottom: 20px;
    }

    .payment-report-card {
        height: 100%;
    }

    .payment-report-card .card-body {
        padding: 20px;
    }

    .summary-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .summary-value {
        color: #343a40;
        font-size: 23px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .payment-filter-card .card-body {
        padding: 20px;
    }

    .payment-filter-card label {
        color: #495057;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .payment-filter-card .form-control {
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

    .payment-table-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .payment-table-card .card-header h5 {
        color: #343a40;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .payment-table-card .card-body {
        padding: 20px;
    }

    #paymentTransactionsTable {
        width: 100% !important;
        font-size: 12px;
        white-space: nowrap;
    }

    #paymentTransactionsTable thead th {
        background: #f5f6fa;
        color: #343a40;
        font-weight: 700;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    #paymentTransactionsTable tbody td {
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

    .payment-status-badge,
    .booking-status-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-paid {
        background: #e6f4ea;
        color: #188038;
    }

    .status-pending {
        background: #fff4df;
        color: #9a6700;
    }

    .status-failed {
        background: #fce8e6;
        color: #c5221f;
    }

    .status-refunded {
        background: #e8eaf6;
        color: #3949ab;
    }

    .status-partial {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .status-other {
        background: #e9ecef;
        color: #495057;
    }

    .booking-status-badge {
        background: #edf2ff;
        color: #4056a1;
    }

    .export-buttons .btn {
        font-size: 12px;
        padding: 7px 12px;
    }

    .payment-table-card .dataTables_wrapper {
        width: 100%;
    }

    .payment-table-card .dataTables_length,
    .payment-table-card .dataTables_filter {
        margin-bottom: 15px;
        font-size: 12px;
    }

    .payment-table-card .dataTables_length select {
        height: 34px;
        min-width: 65px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
        margin: 0 5px;
    }

    .payment-table-card .dataTables_filter input {
        height: 34px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 10px;
        margin-left: 5px;
    }

    .payment-table-card .dataTables_info {
        padding-top: 12px;
        color: #6c757d;
        font-size: 12px;
    }

    .payment-table-card .dataTables_paginate {
        padding-top: 10px;
    }

    .payment-table-card .dataTables_paginate .pagination {
        justify-content: flex-end;
        margin-bottom: 0;
    }

    .payment-table-card .dataTables_paginate .page-link {
        color: #495057;
        font-size: 12px;
        padding: 6px 11px;
        border-radius: 4px;
        margin-left: 3px;
    }

    .payment-table-card .dataTables_paginate .page-item.active .page-link {
        background: #6777ef;
        border-color: #6777ef;
        color: #fff;
    }

    .payment-table-card .dataTables_paginate .page-item.disabled .page-link {
        color: #adb5bd;
    }

    @media (max-width: 767px) {
        .payment-table-card .card-header {
            align-items: flex-start;
        }

        .payment-table-card .dataTables_filter {
            text-align: left;
        }

        .payment-table-card .dataTables_filter input {
            width: 150px;
        }

        .payment-table-card .dataTables_paginate .pagination {
            justify-content: flex-start;
        }
    }
</style>
<div class="container-fluid">
    <div class="payment-report-header">
        <h4>Payment Transaction Report</h4>
        <p class="text-muted mb-0">
            View and manage Puja, Ticket and Event payment transactions.
        </p>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Total Transactions</p>
                    <h4 class="summary-value">
                        {{ number_format($summary['total_transactions']) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Paid Transactions</p>
                    <h4 class="summary-value text-success">
                        {{ number_format($summary['paid']) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Pending Transactions</p>
                    <h4 class="summary-value text-warning">
                        {{ number_format($summary['pending']) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Paid Amount</p>
                    <h4 class="summary-value">
                        ₹{{ number_format($summary['paid_amount'], 2) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Failed Transactions</p>
                    <h4 class="summary-value text-danger">
                        {{ number_format($summary['failed']) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Refunded Transactions</p>
                    <h4 class="summary-value">
                        {{ number_format($summary['refunded']) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card payment-report-card">
                <div class="card-body">
                    <p class="summary-label">Partially Refunded</p>
                    <h4 class="summary-value">
                        {{ number_format($summary['partially_refunded']) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card payment-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.payment-transactions.index') }}">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Module</label>
                        <select name="module" class="form-control">
                            <option value="">All Modules</option>
                            <option value="puja" {{ request('module') == 'puja' ? 'selected' : '' }}>
                                Puja
                            </option>
                            <option value="ticket" {{ request('module') == 'ticket' ? 'selected' : '' }}>
                                Ticket
                            </option>
                            <option value="event" {{ request('module') == 'event' ? 'selected' : '' }}>
                                Event
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Temple</label>
                        <select name="temple_id" class="form-control">
                            <option value="">All Temples</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ request('temple_id') == $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="">All Payment Status</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                Failed
                            </option>
                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                Refunded
                            </option>
                            <option value="partially_refunded" {{ request('payment_status') == 'partially_refunded' ? 'selected' : '' }}>
                                Partially Refunded
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Booking Status</label>
                        <select name="booking_status" class="form-control">
                            <option value="">All Booking Status</option>
                            <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>
                            <option value="completed" {{ request('booking_status') == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                            <option value="cancel_requested" {{ request('booking_status') == 'cancel_requested' ? 'selected' : '' }}>
                                Cancel Requested
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Gateway</label>
                        <select name="gateway" class="form-control">
                            <option value="">All Gateways</option>
                            <option value="razorpay" {{ request('gateway') == 'razorpay' ? 'selected' : '' }}>
                                Razorpay
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>From Date</label>
                        <input type="date" name="from_date"  class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="filter"></i>
                                Apply Filter
                            </button>
                            <a href="{{ route('reports.payment-transactions.index') }}" class="btn btn-secondary">
                                <i data-feather="refresh-ccw"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card payment-table-card">
        <div class="card-header">
            <h5>Transaction Details</h5>
            <div class="export-buttons">
                <a href="{{ route('reports.payment-transactions.pdf', request()->query()) }}" class="btn btn-danger">
                    <i data-feather="file-text"></i>
                    PDF
                </a>
                <a href="{{ route('reports.payment-transactions.excel', request()->query()) }}" class="btn btn-success">
                    <i data-feather="file"></i>
                    Excel
                </a>
                <a href="{{ route('reports.payment-transactions.csv', request()->query()) }}" class="btn btn-info">
                    <i data-feather="download"></i>
                    CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="paymentTransactionsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Temple</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                            <th>Gateway</th>
                            <th>Date</th>
                            <th>Failure Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $index => $row)
                            @php
                                $paymentStatus = strtolower($row['payment_status']);
                                $paymentBadge = match($paymentStatus) {
                                    'paid' => 'status-paid',
                                    'pending' => 'status-pending',
                                    'failed' => 'status-failed',
                                    'refunded' => 'status-refunded',
                                    'partially_refunded' => 'status-partial',
                                    default => 'status-other',
                                };
                                $bookingStatus = strtolower($row['booking_status']);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="module-badge">
                                        {{ $row['module'] }}
                                    </span>
                                </td>
                                <td>{{ $row['transaction_id'] }}</td>
                                <td>{{ $row['order_id'] }}</td>
                                <td>{{ $row['booking_id'] }}</td>
                                <td>{{ $row['customer_name'] }}</td>
                                <td>{{ $row['temple_name'] }}</td>
                                <td>{{ $row['service_name'] }}</td>
                                <td class="text-nowrap">
                                    {{ $row['currency'] }}
                                    {{ number_format($row['amount'], 2) }}
                                </td>
                                <td>
                                    <span class="payment-status-badge {{ $paymentBadge }}">
                                        {{ str_replace('_', ' ', ucfirst($paymentStatus)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="booking-status-badge">
                                        {{ str_replace('_', ' ', ucfirst($bookingStatus)) }}
                                    </span>
                                </td>
                                <td>{{ $row['gateway'] }}</td>
                                <td class="text-nowrap">
                                    {{ $row['transaction_date']
                                        ? \Carbon\Carbon::parse($row['transaction_date'])->format('d-m-Y h:i A')
                                        : '-' }}
                                </td>
                                <td>{{ $row['failure_details'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(request('temple_id'))
        <p class="text-muted small">
            Note: Event transactions are excluded when a Temple filter is selected because Event orders do not have a temple_id.
        </p>
    @endif
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#paymentTransactionsTable')) {
            $('#paymentTransactionsTable').DataTable().destroy();
        }
        $('#paymentTransactionsTable').DataTable({
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
            order: [[12, 'desc']],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                emptyTable: "No payment transactions found",
                zeroRecords: "No matching transactions found",
                search: "Search:",
                searchPlaceholder: "Search transactions...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                infoEmpty: "Showing 0 to 0 of 0 transactions",
                infoFiltered: "(filtered from _MAX_ total transactions)",
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