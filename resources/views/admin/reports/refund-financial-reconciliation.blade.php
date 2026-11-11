@can('reports.refund-financial-reconciliation')
@extends('admin.layouts.app')
@section('content')
<style>
    .refund-header { margin-bottom: 20px; }
    .refund-header h4 { font-weight: 700; color: #343a40; }
    .refund-card, .refund-filter-card, .refund-table-card {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        margin-bottom: 20px;
    }
    .refund-card .card-body, .refund-filter-card .card-body,
    .refund-table-card .card-body { padding: 20px; }
    .refund-label { color: #6c757d; font-size: 13px; margin-bottom: 8px; }
    .refund-value { font-size: 23px; font-weight: 700; color: #343a40; margin: 0; }
    .refund-filter-card label { font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 7px; }
    .refund-filter-card .form-control { height: 40px; border-radius: 5px; font-size: 13px; }
    .refund-actions, .refund-export-buttons { display: flex; flex-wrap: wrap; gap: 8px; }
    .refund-table-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .refund-table-card .card-header h5 { margin: 0; font-size: 16px; font-weight: 700; color: #343a40; }
    #refundReconciliationTable { width: 100% !important; font-size: 12px; white-space: nowrap; }
    #refundReconciliationTable thead th { background: #f5f6fa; color: #343a40; font-weight: 700; vertical-align: middle; }
    #refundReconciliationTable tbody td { vertical-align: middle; }
    .refund-module {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 4px;
        background: #edf2ff;
        color: #4056a1;
        font-size: 11px;
        font-weight: 600;
    }
    .refund-status {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .refund-processed { background: #e6f4ea; color: #188038; }
    .refund-pending { background: #fff4df; color: #9a6700; }
    .refund-failed { background: #fce8e6; color: #c5221f; }
    .refund-other { background: #e9ecef; color: #495057; }
    .refund-export-buttons .btn { font-size: 12px; padding: 7px 12px; }
    .refund-table-card .dataTables_length,
    .refund-table-card .dataTables_filter { margin-bottom: 15px; font-size: 12px; }
    .refund-table-card .dataTables_length select,
    .refund-table-card .dataTables_filter input {
        height: 34px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 9px;
    }
    .refund-table-card .dataTables_info { font-size: 12px; color: #6c757d; padding-top: 12px; }
    .refund-table-card .dataTables_paginate { padding-top: 10px; }
    .refund-table-card .dataTables_paginate .pagination { justify-content: flex-end; margin-bottom: 0; }
    .refund-table-card .dataTables_paginate .page-link {
        color: #495057;
        font-size: 12px;
        padding: 6px 11px;
        margin-left: 3px;
    }
    .refund-table-card .dataTables_paginate .page-item.active .page-link {
        background: #6777ef;
        border-color: #6777ef;
        color: #fff;
    }
    @media (max-width: 767px) {
        .refund-table-card .card-header { align-items: flex-start; }
        .refund-table-card .dataTables_paginate .pagination { justify-content: flex-start; }
    }
</style>
<div class="container-fluid">
    <div class="refund-header">
        <h4>Refund & Financial Reconciliation Report</h4>
        <p class="text-muted mb-0">
            Track Puja, Ticket and Event refund requests, processed refunds and Razorpay reconciliation.
        </p>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Total Refund Requests</p>
                    <h4 class="refund-value">{{ number_format($summary['total_requests']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Processed Refunds</p>
                    <h4 class="refund-value text-success">{{ number_format($summary['processed_count']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Pending Refunds</p>
                    <h4 class="refund-value text-warning">{{ number_format($summary['pending_count']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Failed Refunds</p>
                    <h4 class="refund-value text-danger">{{ number_format($summary['failed_count']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Processed Refund Amount</p>
                    <h4 class="refund-value text-success">
                        ₹{{ number_format($summary['processed_amount'], 2) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Pending Refund Amount</p>
                    <h4 class="refund-value">
                        ₹{{ number_format($summary['pending_amount'], 2) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card refund-card">
                <div class="card-body">
                    <p class="refund-label">Failed Refund Amount</p>
                    <h4 class="refund-value">
                        ₹{{ number_format($summary['failed_amount'], 2) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card refund-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.refund-financial-reconciliation.index') }}">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>Module</label>
                        <select name="module" class="form-control">
                            <option value="">All Modules</option>
                            <option value="puja" {{ request('module') == 'puja' ? 'selected' : '' }}>Puja</option>
                            <option value="ticket" {{ request('module') == 'ticket' ? 'selected' : '' }}>Ticket</option>
                            <option value="event" {{ request('module') == 'event' ? 'selected' : '' }}>Event</option>
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
                        <label>Refund Status</label>
                        <select name="refund_status" class="form-control">
                            <option value="">All Refund Status</option>
                            <option value="processed" {{ request('refund_status') == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="pending" {{ request('refund_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('refund_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="refund-actions">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="filter"></i>
                                Apply Filter
                            </button>
                            <a href="{{ route('reports.refund-financial-reconciliation.index') }}"
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
    <div class="card refund-table-card">
        <div class="card-header">
            <h5>Refund Reconciliation Details</h5>
            <div class="refund-export-buttons">
                <a href="{{ route('reports.refund-financial-reconciliation.pdf', request()->query()) }}"
                   class="btn btn-danger">
                    <i data-feather="file-text"></i> PDF
                </a>
                <a href="{{ route('reports.refund-financial-reconciliation.excel', request()->query()) }}"
                   class="btn btn-success">
                    <i data-feather="file"></i> Excel
                </a>
                <a href="{{ route('reports.refund-financial-reconciliation.csv', request()->query()) }}"
                   class="btn btn-info">
                    <i data-feather="download"></i> CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="refundReconciliationTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Refund ID</th>
                            <th>Payment ID</th>
                            <th>Order ID</th>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Temple</th>
                            <th>Booking Amount</th>
                            <th>Refund Amount</th>
                            <th>Refund Status</th>
                            <th>Refund Date</th>
                            <th>Failure Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refunds as $index => $row)
                            @php
                                $status = strtolower($row['refund_status']);
                                $statusClass = match($status) {
                                    'processed' => 'refund-processed',
                                    'pending' => 'refund-pending',
                                    'failed' => 'refund-failed',
                                    default => 'refund-other',
                                };
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="refund-module">{{ $row['module'] }}</span></td>
                                <td>{{ $row['refund_id'] }}</td>
                                <td>{{ $row['payment_id'] }}</td>
                                <td>{{ $row['order_id'] }}</td>
                                <td>{{ $row['booking_id'] }}</td>
                                <td>{{ $row['customer_name'] }}</td>
                                <td>{{ $row['temple_name'] }}</td>
                                <td class="text-nowrap">₹{{ number_format($row['booking_amount'], 2) }}</td>
                                <td class="text-nowrap">₹{{ number_format($row['refund_amount'], 2) }}</td>
                                <td>
                                    <span class="refund-status {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    {{ $row['refund_date']
                                        ? \Carbon\Carbon::parse($row['refund_date'])->format('d-m-Y h:i A')
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
            Event refunds are excluded when a Temple filter is selected because Event orders do not have a temple_id.
        </p>
    @endif
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#refundReconciliationTable')) {
            $('#refundReconciliationTable').DataTable().destroy();
        }
        $('#refundReconciliationTable').DataTable({
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
            order: [[11, 'desc']],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                emptyTable: "No refund records found",
                zeroRecords: "No matching refund records found",
                search: "Search:",
                searchPlaceholder: "Search refund records...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ refunds",
                infoEmpty: "Showing 0 to 0 of 0 refunds",
                infoFiltered: "(filtered from _MAX_ total refunds)",
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