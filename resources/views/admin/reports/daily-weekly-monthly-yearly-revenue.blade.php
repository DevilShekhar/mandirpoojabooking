@can('reports.daily-to-yearly-revenue')
@extends('admin.layouts.app')
@section('content')
<style>
    .revenue-page {
        padding-bottom: 25px;
    }
    .revenue-page-title {
        color: #647b91;
        font-size: 34px;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .revenue-page-subtitle {
        color: #8b9bad;
        font-size: 18px;
        margin-bottom: 42px;
    }
    .revenue-filter-card,
    .revenue-summary-card,
    .revenue-table-card {
        background: #ffffff;
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }
    .revenue-filter-card {
        margin-bottom: 35px;
    }
    .revenue-filter-card .card-body {
        padding: 30px 35px;
    }
    .revenue-filter-card label {
        color: #647b91;
        font-size: 17px;
        font-weight: 500;
        margin-bottom: 12px;
    }
    .revenue-filter-card .form-control {
        height: 64px;
        border: 1px solid #dfe3ff;
        border-radius: 7px;
        color: #34495e;
        font-size: 17px;
        padding: 12px 18px;
        background-color: #ffffff;
    }
    .revenue-filter-card .form-control:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.10);
    }
    .revenue-filter-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }
    .revenue-filter-actions .btn {
        min-height: 52px;
        padding: 12px 22px;
        border-radius: 7px;
        font-size: 15px;
        font-weight: 600;
    }
    .revenue-filter-actions .btn-primary {
        background: #6777ef;
        border-color: #6777ef;
        box-shadow: 0 5px 12px rgba(103, 119, 239, 0.25);
    }
    .revenue-filter-actions .btn-secondary {
        background: #ced4da;
        border-color: #ced4da;
        color: #ffffff;
    }
    .revenue-summary-card {
        padding: 15px;
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
    }
    .revenue-summary-card .revenue-label {
        color: #111827;
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 18px;
    }
    .revenue-summary-card .revenue-value {
        color: #34495e;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 16px;
        line-height: 1.3;
    }
    .revenue-summary-card .revenue-subtitle {
        color: #111827;
        font-size: 16px;
        margin-bottom: 0;
    }
    .revenue-summary-card .revenue-icon {
        position: absolute;
        right: 32px;
        top: 100px;
        width: 50px;
        height: 50px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }
    .revenue-total-card {
        background: #168052;
    }
    .revenue-total-card .revenue-label,
    .revenue-total-card .revenue-value,
    .revenue-total-card .revenue-subtitle {
        color: #ffffff;
    }
    .revenue-total-card .revenue-icon {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }
    .revenue-booking-icon {
        background: #e8f0ff;
        color: #2865cf;
    }
    .revenue-ticket-icon {
        background: #fff3df;
        color: #ee8b00;
    }
    .revenue-event-icon {
        background: #f0eaff;
        color: #7950c9;
    }
    .revenue-puja-icon {
        background: #e4f8ef;
        color: #168052;
    }
    .revenue-export-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    .revenue-export-buttons .btn {
        border-radius: 7px;
        padding: 11px 18px;
        font-weight: 600;
    }
    .revenue-table-card {
        overflow: hidden;
    }
    .revenue-table-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #eeeeee;
        padding: 22px 25px;
    }
    .revenue-table-card .card-header h5 {
        color: #34495e;
        font-size: 19px;
        font-weight: 600;
        margin-bottom: 0;
    }
    .revenue-table {
        margin-bottom: 0;
        min-width: 1100px;
    }
    .revenue-table thead th {
        background: #f5f7fb;
        color: #526579;
        font-size: 13px;
        font-weight: 700;
        padding: 16px 12px;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 1px solid #e8edf3;
    }
    .revenue-table tbody td,
    .revenue-table tfoot td {
        color: #34495e;
        font-size: 14px;
        padding: 15px 12px;
        vertical-align: middle;
    }
    .revenue-table tbody tr:hover {
        background: #f8faff;
    }
    .revenue-table tfoot td {
        background: #f0f4fa;
        font-weight: 700;
        border-top: 2px solid #dce3ed;
    }
    .revenue-amount {
        color: #168052 !important;
        font-weight: 700;
        white-space: nowrap;
    }
    .revenue-total-amount {
        color: #168052 !important;
        font-weight: 700;
        white-space: nowrap;
    }
    .revenue-note {
        color: #7b8794;
        font-size: 13px;
        margin-top: 18px;
    }
    @media (max-width: 767px) {
        .revenue-page-title {
            font-size: 25px;
        }
        .revenue-page-subtitle {
            font-size: 15px;
            margin-bottom: 25px;
        }
        .revenue-filter-card .card-body {
            padding: 20px;
        }
        .revenue-summary-card {
            padding: 25px;
        }
         .revenue-summary-card .revenue-value {
            font-size: 27px;
        }
        .revenue-summary-card .revenue-icon {
            right: 22px;
            top: 65px;
            width: 58px;
            height: 58px;
            font-size: 24px;
        }
    }
</style>
<div class="section-body revenue-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="revenue-page-title">Daily / Weekly / Monthly / Yearly Revenue Report</h2>
                <p class="revenue-page-subtitle">View paid revenue across Ticket, Event and Puja bookings.</p>
            </div>
        </div>
        <div class="card revenue-filter-card">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.revenue-period.index') }}">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="period">Revenue Period</label>
                            <select name="period" id="period" class="form-control">
                                <option value="daily" {{ request('period', 'monthly') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('period', 'monthly') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('period', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('period', 'monthly') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="from_date">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="revenue-filter-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-2"></i> Apply Filter
                                </button>
                                <a href="{{ route('reports.revenue-period.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="revenue-summary-card">
                    <div class="revenue-label">Total Bookings</div>
                    <div class="revenue-value">{{ number_format($summary['total_bookings'] ?? 0) }}</div>
                    <p class="revenue-subtitle">All booking modules</p>
                    <div class="revenue-icon revenue-booking-icon">
                        <i class="fas fa-clipboard"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="revenue-summary-card revenue-total-card">
                    <div class="revenue-label">Total Revenue</div>
                    <div class="revenue-value">₹ {{ number_format($summary['total_revenue'] ?? 0, 2) }}</div>
                    <p class="revenue-subtitle">Total paid collection</p>
                    <div class="revenue-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="revenue-summary-card">
                    <div class="revenue-label">Ticket Revenue</div>
                    <div class="revenue-value">₹ {{ number_format($summary['ticket_revenue'] ?? 0, 2) }}</div>
                    <p class="revenue-subtitle">{{ number_format($summary['ticket_bookings'] ?? 0) }} Paid Bookings</p>
                    <div class="revenue-icon revenue-ticket-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="revenue-summary-card">
                    <div class="revenue-label">Event Revenue</div>
                    <div class="revenue-value">₹ {{ number_format($summary['event_revenue'] ?? 0, 2) }}</div>
                    <p class="revenue-subtitle">{{ number_format($summary['event_bookings'] ?? 0) }} Paid Bookings</p>
                    <div class="revenue-icon revenue-event-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="revenue-summary-card">
                    <div class="revenue-label">Puja Revenue</div>
                    <div class="revenue-value">₹ {{ number_format($summary['puja_revenue'] ?? 0, 2) }}</div>
                    <p class="revenue-subtitle">{{ number_format($summary['puja_bookings'] ?? 0) }} Paid Bookings</p>
                    <div class="revenue-icon revenue-puja-icon">
                        <i class="fas fa-praying-hands"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h5 class="mb-3">Revenue Details</h5>
            </div>
            <div class="col-md-6">
                <div class="revenue-export-buttons justify-content-md-end">
                    <a href="{{ route('reports.revenue-period.pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                    <a href="{{ route('reports.revenue-period.excel', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                    <a href="{{ route('reports.revenue-period.csv', request()->query()) }}" class="btn btn-info">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>
        <div class="card revenue-table-card">
            <div class="card-header">
                <h5>
                    {{ ucfirst($period ?? 'monthly') }} Revenue Details
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover revenue-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Period</th>
                                <th>Ticket Bookings</th>
                                <th>Ticket Revenue</th>
                                <th>Event Bookings</th>
                                <th>Event Revenue</th>
                                <th>Puja Bookings</th>
                                <th>Puja Revenue</th>
                                <th>Total Bookings</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periods as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row['period_label'] ?? '-' }}</td>
                                    <td>{{ number_format($row['ticket_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($row['ticket_revenue'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($row['event_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($row['event_revenue'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($row['puja_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($row['puja_revenue'] ?? 0, 2) }}</td>
                                    <td><strong>{{ number_format($row['total_bookings'] ?? 0) }}</strong></td>
                                    <td class="revenue-total-amount">₹ {{ number_format($row['total_revenue'] ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        No revenue records found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($periods) > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="2">Grand Total</td>
                                    <td>{{ number_format($summary['ticket_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($summary['ticket_revenue'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($summary['event_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($summary['event_revenue'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($summary['puja_bookings'] ?? 0) }}</td>
                                    <td class="revenue-amount">₹ {{ number_format($summary['puja_revenue'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($summary['total_bookings'] ?? 0) }}</td>
                                    <td class="revenue-total-amount">₹ {{ number_format($summary['total_revenue'] ?? 0, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <p class="revenue-note">
            Note: This report displays paid revenue from Ticket, Event and Puja bookings only. Ticket booking counts are calculated using distinct Razorpay order IDs.
        </p>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan