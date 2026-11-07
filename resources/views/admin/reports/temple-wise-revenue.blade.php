@can('reports.temple-wise-revenue')
@extends('admin.layouts.app')
@section('content')
<style>
    .temple-revenue-title {
        color: #647b91;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .temple-revenue-subtitle {
        color: #8b9bad;
        font-size: 17px;
        margin-bottom: 35px;
    }
    .temple-revenue-filter,
    .temple-revenue-card,
    .temple-revenue-table-card {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }
    .temple-revenue-filter {
        margin-bottom: 30px;
    }
    .temple-revenue-filter .card-body {
        padding: 28px 32px;
    }
    .temple-revenue-filter label {
        color: #647b91;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .temple-revenue-filter .form-control {
        height: 58px;
        border: 1px solid #dfe3ff;
        border-radius: 7px;
        font-size: 16px;
    }
    .temple-revenue-actions {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }
    .temple-revenue-actions .btn {
        min-height: 48px;
        padding: 11px 22px;
        border-radius: 7px;
        font-weight: 600;
    }
    .temple-revenue-card {
        min-height: 190px;
        padding: 28px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    .temple-revenue-card .card-label {
        color: #53677b;
        font-size: 16px;
        margin-bottom: 16px;
    }
    .temple-revenue-card .card-value {
        color: #34495e;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .temple-revenue-card .card-subtitle {
        color: #7b8794;
        font-size: 14px;
        margin-bottom: 0;
    }
    .temple-revenue-card .card-icon {
        position: absolute;
        right: 25px;
        top: 55px;
        width: 62px;
        height: 62px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }
    .temple-revenue-total {
        background: #168052;
    }
    .temple-revenue-total .card-label,
    .temple-revenue-total .card-value,
    .temple-revenue-total .card-subtitle {
        color: #ffffff;
    }
    .temple-revenue-total .card-icon {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }
    .temple-icon-blue {
        background: #e8f0ff;
        color: #2865cf;
    }
    .temple-icon-orange {
        background: #fff3df;
        color: #ee8b00;
    }
    .temple-icon-green {
        background: #e4f8ef;
        color: #168052;
    }
    .temple-revenue-export {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
        margin-bottom: 18px;
    }
    .temple-revenue-export .btn {
        padding: 10px 17px;
        border-radius: 7px;
        font-weight: 600;
    }
    .temple-revenue-table-card {
        overflow: hidden;
    }
    .temple-revenue-table-card .card-header {
        background: #ffffff;
        padding: 22px 25px;
        border-bottom: 1px solid #eeeeee;
    }
    .temple-revenue-table-card .card-header h5 {
        color: #34495e;
        font-size: 19px;
        font-weight: 600;
        margin-bottom: 0;
    }
    .temple-revenue-table {
        min-width: 650px;
        margin-bottom: 0;
    }
    .temple-revenue-table thead th {
        background: #f5f7fb;
        color: #526579;
        font-size: 13px;
        font-weight: 700;
        padding: 16px 12px;
        white-space: nowrap;
        vertical-align: middle;
    }
    .temple-revenue-table tbody td,
    .temple-revenue-table tfoot td {
        color: #34495e;
        font-size: 14px;
        padding: 15px 12px;
        vertical-align: middle;
    }
    .temple-revenue-table tbody tr:hover {
        background: #f8faff;
    }
    .temple-revenue-table tfoot td {
        background: #f0f4fa;
        font-weight: 700;
        border-top: 2px solid #dce3ed;
    }
    .temple-revenue-amount {
        color: #168052 !important;
        font-weight: 700;
        white-space: nowrap;
    }
    .temple-revenue-note {
        color: #7b8794;
        font-size: 13px;
        margin-top: 18px;
    }
    @media (max-width: 767px) {
        .temple-revenue-title {
            font-size: 25px;
        }
        .temple-revenue-filter .card-body {
            padding: 20px;
        }
        .temple-revenue-card {
            min-height: 175px;
            padding: 23px;
        }
        .temple-revenue-card .card-value {
            font-size: 20px;
        }
        .temple-revenue-card .card-icon {
            right: 18px;
            width: 52px;
            height: 52px;
            font-size: 22px;
        }
        .temple-revenue-export {
            justify-content: flex-start;
        }
    }
</style>
<div class="section-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="temple-revenue-title">Temple-wise Revenue Report</h2>
                <p class="temple-revenue-subtitle">Select a temple to view its paid Ticket and Puja booking revenue.</p>
            </div>
        </div>
        <div class="card temple-revenue-filter">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.temple-wise-revenue.index') }}">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="temple_id">Select Temple</label>
                            <select name="temple_id" id="temple_id" class="form-control" required>
                                <option value="">Select Temple</option>
                                @foreach($temples as $temple)
                                    <option value="{{ $temple->id }}" {{ (string) request('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                        {{ $temple->name }}
                                    </option>
                                @endforeach
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
                            <div class="temple-revenue-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-2"></i> Apply Filter
                                </button>
                                <a href="{{ route('reports.temple-wise-revenue.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if($selectedTemple)
            <div class="row">
                <div class="col-12 mb-3">
                    <h5>Selected Temple: {{ $selectedTemple->name }}</h5>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card">
                        <div class="card-label">Total Bookings</div>
                        <div class="card-value">{{ number_format($summary['total_bookings']) }}</div>
                        <p class="card-subtitle">Ticket + Puja bookings</p>
                        <div class="card-icon temple-icon-blue">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card temple-revenue-total">
                        <div class="card-label">Total Revenue</div>
                        <div class="card-value">₹ {{ number_format($summary['total_revenue'], 2) }}</div>
                        <p class="card-subtitle">Ticket + Puja paid collection</p>
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card">
                        <div class="card-label">Ticket Bookings</div>
                        <div class="card-value">{{ number_format($summary['ticket_bookings']) }}</div>
                        <p class="card-subtitle">Distinct Razorpay orders</p>
                        <div class="card-icon temple-icon-orange">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card">
                        <div class="card-label">Ticket Revenue</div>
                        <div class="card-value">₹ {{ number_format($summary['ticket_revenue'], 2) }}</div>
                        <p class="card-subtitle">Paid and confirmed tickets</p>
                        <div class="card-icon temple-icon-orange">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card">
                        <div class="card-label">Puja Bookings</div>
                        <div class="card-value">{{ number_format($summary['puja_bookings']) }}</div>
                        <p class="card-subtitle">Paid Puja booking records</p>
                        <div class="card-icon temple-icon-green">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="temple-revenue-card">
                        <div class="card-label">Puja Revenue</div>
                        <div class="card-value">₹ {{ number_format($summary['puja_revenue'], 2) }}</div>
                        <p class="card-subtitle">Paid Puja collection</p>
                        <div class="card-icon temple-icon-green">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="mb-3">Revenue Details</h5>
                </div>
                <div class="col-md-6">
                    <div class="temple-revenue-export">
                        <a href="{{ route('reports.temple-wise-revenue.pdf', request()->query()) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a href="{{ route('reports.temple-wise-revenue.excel', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>
                        <a href="{{ route('reports.temple-wise-revenue.csv', request()->query()) }}" class="btn btn-info">
                            <i class="fas fa-file-csv mr-1"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card temple-revenue-table-card">
                <div class="card-header">
                    <h5>{{ $selectedTemple->name }} - Revenue Details</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover temple-revenue-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Booking Module</th>
                                    <th>Booking Count</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $row['module'] }}</strong></td>
                                        <td>{{ number_format($row['booking_count']) }}</td>
                                        <td class="temple-revenue-amount">₹ {{ number_format($row['revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Grand Total</td>
                                    <td>{{ number_format($summary['total_bookings']) }}</td>
                                    <td class="temple-revenue-amount">₹ {{ number_format($summary['total_revenue'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <p class="temple-revenue-note">
                Ticket booking count is calculated using distinct Razorpay order IDs. Puja booking count is calculated using paid Puja booking records.
            </p>
        @else
            <div class="card temple-revenue-table-card">
                <div class="card-body text-center py-5">
                    <h5 class="mb-2">Select a Temple</h5>
                    <p class="text-muted mb-0">Choose a temple and click Apply Filter to view separate Ticket and Puja booking counts and revenue.</p>
                </div>
            </div>
        @endif
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