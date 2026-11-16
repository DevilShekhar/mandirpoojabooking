@can('reports.online-puja-details')
@extends('admin.layouts.app')

@section('content')
<style>
    .obp-page {
        padding: 22px 0;
        color: #263449;
    }
    .obp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .obp-header h4 {
        font-size: 23px;
        font-weight: 750;
        color: #25334a;
        margin-bottom: 6px;
    }
    .obp-header p {
        color: #8390a2;
        font-size: 13px;
        margin: 0;
    }
    .obp-export {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }
    .obp-export .btn {
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .obp-filter-card,
    .obp-stat-card,
    .obp-table-card {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 13px;
        box-shadow: 0 4px 18px rgba(30, 50, 80, .045);
    }
    .obp-filter-card {
        padding: 22px;
        margin-bottom: 24px;
    }
    .obp-section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 19px;
    }
    .obp-section-heading h5 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: #263449;
    }
    .obp-section-heading p {
        font-size: 12px;
        color: #8b96a6;
        margin: 4px 0 0;
    }
    .obp-filter-card label {
        font-size: 12px;
        font-weight: 700;
        color: #56647a;
        margin-bottom: 8px;
    }
    .obp-filter-card .form-control,
    .obp-filter-card .form-select {
        height: 43px;
        border-radius: 8px;
        border-color: #dfe5ed;
        font-size: 13px;
    }
    .obp-filter-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .obp-filter-actions .btn {
        height: 43px;
        padding: 10px 17px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .obp-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px;
        min-height: 155px;
        margin-bottom: 22px;
    }
    .obp-stat-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--obp-accent);
    }
    .obp-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 17px;
    }
    .obp-stat-label {
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #77849a;
    }
    .obp-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--obp-soft);
        color: var(--obp-accent);
        font-size: 17px;
        flex-shrink: 0;
    }
    .obp-stat-value {
        font-size: 25px;
        line-height: 1.2;
        font-weight: 750;
        color: #25334a;
        overflow-wrap: anywhere;
        margin-bottom: 8px;
    }
    .obp-stat-note {
        font-size: 12px;
        color: #8995a5;
    }
    .obp-table-card {
        overflow: hidden;
        margin-top: 3px;
    }
    .obp-table-header {
        padding: 22px;
        border-bottom: 1px solid #e9eef5;
    }
    .obp-table-header h5 {
        font-size: 16px;
        font-weight: 750;
        color: #263449;
        margin-bottom: 5px;
    }
    .obp-table-header p {
        color: #8a95a6;
        font-size: 12px;
        margin: 0;
    }
    .obp-table-card .table {
        margin-bottom: 0;
    }
    .obp-table-card .table thead th {
        background: #f5f8fc;
        color: #617087;
        border-color: #e8edf3;
        padding: 14px 12px;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        white-space: nowrap;
        vertical-align: middle;
    }
    .obp-table-card .table tbody td {
        padding: 15px 12px;
        border-color: #e9edf3;
        font-size: 12px;
        color: #4c5b70;
        vertical-align: middle;
    }
    .obp-amount {
        font-weight: 700;
        white-space: nowrap;
    }
    .obp-table-card .badge {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11px;
    }
    .obp-note {
        margin-top: 17px;
        font-size: 12px;
        color: #8995a5;
    }
    @media(max-width: 767px) {
        .obp-header h4 {
            font-size: 19px;
        }
        .obp-stat-value {
            font-size: 22px;
        }
        .obp-filter-card {
            padding: 16px;
        }
    }
    .selectoption {
        display: grid;
    }
</style>

@php
    $fromDate = request('from_date');
    $toDate = request('to_date');

    $totalCount = isset($totalBookings) ? $totalBookings : (method_exists($reports, 'total') ? $reports->total() : count($reports));
    $revenueAmount = isset($totalRevenue) ? $totalRevenue : 0;
    
    $confirmedCount = 0;
    $cancelledCount = 0;
    $pendingCount = 0;
    $confirmedAmount = 0;
    $cancelledAmount = 0;

    $targetReports = method_exists($reports, 'items') ? $reports->items() : $reports;
    foreach($targetReports as $rep) {
        $bStatus = strtolower($rep->booking_status ?? '');
        if($bStatus == 'confirmed') {
            $confirmedCount++;
            $confirmedAmount += ($rep->amount ?? 0);
        } elseif($bStatus == 'cancelled') {
            $cancelledCount++;
            $cancelledAmount += ($rep->amount ?? 0);
        } elseif($bStatus == 'pending') {
            $pendingCount++;
        }
    }

    $reportCards = [
        [
            'label' => 'Total Bookings',
            'value' => number_format($totalCount),
            'note' => 'All filtered puja booking orders',
            'icon' => 'fa-shopping-cart',
            'accent' => '#3975c6',
            'soft' => '#eaf2ff'
        ],
        [
            'label' => 'Total Revenue',
            'value' => '₹ ' . number_format($revenueAmount, 2),
            'note' => 'Gross online puja collections',
            'icon' => 'fa-rupee-sign',
            'accent' => '#15966a',
            'soft' => '#e6f7ef'
        ],
        [
            'label' => 'Confirmed Bookings',
            'value' => number_format($confirmedCount),
            'note' => 'Successfully confirmed orders',
            'icon' => 'fa-check-circle',
            'accent' => '#15966a',
            'soft' => '#e6f7ef'
        ],
        [
            'label' => 'Cancelled Bookings',
            'value' => number_format($cancelledCount),
            'note' => 'Total cancelled bookings count',
            'icon' => 'fa-times-circle',
            'accent' => '#dc4c55',
            'soft' => '#fff0f0'
        ],
        [
            'label' => 'Pending Bookings',
            'value' => number_format($pendingCount),
            'note' => 'Awaiting confirmation orders',
            'icon' => 'fa-clock',
            'accent' => '#d58a25',
            'soft' => '#fff4e5'
        ],
        [
            'label' => 'Confirmed Revenue',
            'value' => '₹ ' . number_format($confirmedAmount, 2),
            'note' => 'Revenue from confirmed pujas',
            'icon' => 'fa-wallet',
            'accent' => '#7655b7',
            'soft' => '#f1ecfb'
        ]
    ];
@endphp

<div class="container-fluid obp-page">
    <!-- Header with 3 Individual Export Buttons -->
    <div class="obp-header">
        <div>
            <h4>Online Puja Detailed Report</h4>
            <p>Monitor transactions, booking statuses, and revenue analytics for all online pujas.</p>
        </div>
        <div class="obp-export">
            <a href="{{ route('reports.online-puja-detailed.pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('reports.online-puja-detailed.excel', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
            <a href="{{ route('reports.online-puja-detailed.csv', request()->all()) }}" class="btn btn-primary">
                <i class="fas fa-file-csv"></i> Download CSV
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="obp-filter-card">
        <div class="obp-section-heading">
            <div>
                <h5>
                    <i class="fas fa-filter "></i>
                    Advanced Filters
                </h5>
                <p>Filter records by date range, specific puja, category, booking status, or puja status</p>
            </div>
        </div>
        <form method="GET" action="{{ route('reports.online-puja-detailed.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <label for="from_date">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="to_date">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-4 selectoption">
                    <label for="online_puja_id">Online Puja</label>
                    <select name="online_puja_id" id="online_puja_id" class="form-select">
                        <option value="">All Pujas</option>
                        @foreach($onlinePujas as $puja)
                            <option value="{{ $puja->id }}" {{ request('online_puja_id') == $puja->id ? 'selected' : '' }}>
                                {{ $puja->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 selectoption mt-2">
                    <label for="online_puja_cat_id">Category</label>
                    <select name="online_puja_cat_id" id="online_puja_cat_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('online_puja_cat_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name ?? $category->title ?? 'Category #'.$category->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 selectoption mt-2">
                    <label for="booking_status">Booking Status</label>
                    <select name="booking_status" id="booking_status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <!-- Status Filter Options (0: Inactive, 1: Active, 2: Completed) -->
                <div class="col-md-3 selectoption mt-2">
                    <label for="status">Puja Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status Options</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive </option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Completed</option>
                       
                    </select>
                </div>

                <div class="col-lg-9 col-md-6 mt-2">
                    <div class="obp-filter-actions justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filter
                        </button>
                        <a href="{{ route('reports.online-puja-detailed.index') }}" class="btn btn-light border">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @if($fromDate || $toDate || request('online_puja_id') || request('online_puja_cat_id') || request('booking_status') || request('status') !== null)
            <div class="alert alert-info mt-3 mb-0 py-2 fs-12">
                <strong>Active Filters Applied</strong> (Showing customized report results)
            </div>
        @endif
    </div>

    <!-- Summary Statistics Section Heading -->
    <div class="obp-section-heading">
        <div>
            <h5>
                <i class="fas fa-chart-pie "></i>
                Summary Metrics Cards
            </h5>
            <p>Overall transactional aggregates in 3-column grid layouts</p>
        </div>
    </div>

    <!-- 6 Cards Grid -->
    <div class="row">
        @foreach($reportCards as $card)
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <div class="obp-stat-card" style="--obp-accent:{{ $card['accent'] }};--obp-soft:{{ $card['soft'] }};">
                    <div class="obp-stat-top">
                        <div class="obp-stat-label">{{ $card['label'] }}</div>
                        <div class="obp-stat-icon">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="obp-stat-value">{{ $card['value'] }}</div>
                    <div class="obp-stat-note">{{ $card['note'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Transactions Table Card -->
    <div class="obp-table-card">
        <div class="obp-table-header">
            <h5>
                <i class="fas fa-table "></i>
                Orders & Transactions List
            </h5>
            <p>Detailed listing of booking transactions and payment logs</p>
        </div>
        <div class="table-responsive">
            <table id="pujaReportTable" class="table table-bordered table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Puja Title</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Booking Status</th>
                        <th>Puja Status</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <span class="fw-bold ">{{ $report->order_number }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $report->customer_name ?? optional($report->customer)->name ?? 'N/A' }}</div>
                                <small class="text-muted"><i class="fas fa-phone-alt fa-xs me-1"></i> {{ $report->customer_phone ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">{{ optional($report->puja)->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ ucfirst($report->package_name ?? optional($report->package)->name ?? 'N/A') }}</span>
                            </td>
                            <td>
                                <span class="obp-amount text-success">₹ {{ number_format($report->amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $payStatus = strtolower($report->payment_status);
                                @endphp
                                <span class="badge bg-{{ $payStatus == 'paid' ? 'success' : ($payStatus == 'failed' ? 'danger' : 'warning text-dark') }}">
                                    {{ ucfirst($report->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $bookStatus = strtolower($report->booking_status);
                                @endphp
                                <span class="badge bg-{{ $bookStatus == 'confirmed' ? 'success' : ($bookStatus == 'cancelled' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($report->booking_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $pujaStatus = optional($report->puja)->status ?? 0;
                                @endphp
                                @if($pujaStatus == 1 || $pujaStatus === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($pujaStatus == 2 || $pujaStatus === 'completed')
                                    <span class="badge bg-info text-dark">Completed </span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">{{ $report->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No online puja transaction records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($reports, 'links') && $reports->hasPages())
            <div class="p-3 bg-white border-top d-flex justify-content-end">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <p class="obp-note">
        Note: Financial statistics and counts reflect real-time filtered queries. Export options generate instant records in PDF, Excel, or CSV formats.
    </p>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            $('#pujaReportTable').DataTable({
                responsive: true,
                pageLength: 10,
                paging: false,
                info: false,
                searching: false,
                columnDefs: [
                    { orderable: false, targets: [1, 5, 6, 7] }
                ]
            });
        }
    });
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan