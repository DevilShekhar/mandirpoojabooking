@can('reports.overall-business')
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
    .obp-filter-card .form-control {
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
    }
    .obp-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px;
        min-height: 155px;
        margin-bottom: 22px;
        height: calc(100% - 22px);
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
    .obp-table-card .table tbody td,
    .obp-table-card .table tfoot td {
        padding: 15px 12px;
        border-color: #e9edf3;
        font-size: 12px;
        color: #4c5b70;
        vertical-align: middle;
    }
    .obp-table-card .table tfoot td {
        background: #f0f5fb;
        font-weight: 750;
        color: #25334a;
    }
    .obp-module-name {
        font-weight: 750;
        color: #25334a;
        white-space: nowrap;
    }
    .obp-module-name i {
        margin-right: 7px;
        color: #5279bd;
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
</style>
@php
    $reportCards = [
        [
            'label' => 'Total Bookings',
            'value' => number_format($summary['total_bookings']),
            'note' => 'Ticket, Event and Puja bookings',
            'icon' => 'fa-calendar-check',
            'accent' => '#3975c6',
            'soft' => '#eaf2ff'
        ],
        [
            'label' => 'Successful Paid Bookings',
            'value' => number_format($summary['paid_count']),
            'note' => 'Successfully paid booking orders',
            'icon' => 'fa-check-circle',
            'accent' => '#15966a',
            'soft' => '#e6f7ef'
        ],
        [
            'label' => 'Total Paid Amount',
            'value' => '₹' . number_format($summary['paid_amount'], 2),
            'note' => 'Gross paid booking collections',
            'icon' => 'fa-wallet',
            'accent' => '#15966a',
            'soft' => '#e6f7ef'
        ],
        [
            'label' => 'Cancelled Bookings',
            'value' => number_format($summary['cancelled_count']),
            'note' => 'Total cancelled booking orders',
            'icon' => 'fa-times-circle',
            'accent' => '#dc4c55',
            'soft' => '#fff0f0'
        ],
        [
            'label' => 'Cancelled Booking Amount',
            'value' => '₹' . number_format($summary['cancelled_amount'], 2),
            'note' => 'Value of cancelled bookings',
            'icon' => 'fa-ban',
            'accent' => '#dc4c55',
            'soft' => '#fff0f0'
        ],
        [
            'label' => 'Processed Refunds',
            'value' => number_format($summary['refund_count']),
            'note' => 'Event and Puja refunds',
            'icon' => 'fa-undo-alt',
            'accent' => '#d58a25',
            'soft' => '#fff4e5'
        ],
        [
            'label' => 'Total Refund Amount',
            'value' => '₹' . number_format($summary['refund_amount'], 2),
            'note' => 'Total processed refund amount',
            'icon' => 'fa-money-bill-wave',
            'accent' => '#d58a25',
            'soft' => '#fff4e5'
        ],
        [
            'label' => 'Net Collection',
            'value' => '₹' . number_format($summary['net_collection'], 2),
            'note' => 'Paid amount minus processed refunds',
            'icon' => 'fa-chart-line',
            'accent' => '#7655b7',
            'soft' => '#f1ecfb'
        ],
    ];
@endphp
<div class="container-fluid obp-page">
    <div class="obp-header">
        <div>
            <h4>Overall Business Performance Report</h4>
            <p>
                Complete business overview of bookings, collections,
                cancellations and processed refunds
            </p>
        </div>
        <div class="obp-export">
            <a href="{{ route('reports.overall-business-performance.pdf', request()->only(['from_date', 'to_date'])) }}"
               class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('reports.overall-business-performance.excel', request()->only(['from_date', 'to_date'])) }}"
               class="btn btn-success">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
            <a href="{{ route('reports.overall-business-performance.csv', request()->only(['from_date', 'to_date'])) }}"
               class="btn btn-primary">
                <i class="fas fa-file-csv"></i> Download CSV
            </a>
        </div>
    </div>
    <div class="obp-filter-card">
        <div class="obp-section-heading">
            <div>
                <h5>
                    <i class="fas fa-filter "></i>
                    Filter Business Report
                </h5>
                <p>Select a date range to view business performance</p>
            </div>
        </div>
        <form method="GET" action="{{ route('reports.overall-business-performance.index') }}">
            <div class="row align-items-end">
                <div class="col-lg-4 col-md-4 mb-3">
                    <label for="from_date">From Date</label>
                    <input type="date"
                           id="from_date"
                           name="from_date"
                           class="form-control @error('from_date') is-invalid @enderror"
                           value="{{ old('from_date', $fromDate) }}">
                    @error('from_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-4 mb-3">
                    <label for="to_date">To Date</label>
                    <input type="date"
                           id="to_date"
                           name="to_date"
                           class="form-control @error('to_date') is-invalid @enderror"
                           value="{{ old('to_date', $toDate) }}">
                    @error('to_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="obp-filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filter
                        </button>
                        <a href="{{ route('reports.overall-business-performance.index') }}"
                           class="btn btn-light border">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @if($fromDate || $toDate)
            <div class="alert alert-info mt-2 mb-0 py-2">
                <strong>Selected Period:</strong>
                {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'All Dates' }}
                -
                {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'All Dates' }}
            </div>
        @endif
    </div>
    <div class="obp-section-heading">
        <div>
            <h5>
                <i class="fas fa-chart-pie "></i>
                Business Performance Overview
            </h5>
            <p>Overall booking and financial statistics</p>
        </div>
    </div>
    <div class="row">
        @foreach($reportCards as $card)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <div class="obp-stat-card"
                     style="--obp-accent:{{ $card['accent'] }};--obp-soft:{{ $card['soft'] }};">
                    <div class="obp-stat-top">
                        <div class="obp-stat-label">
                            {{ $card['label'] }}
                        </div>
                        <div class="obp-stat-icon">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="obp-stat-value">
                        {{ $card['value'] }}
                    </div>
                    <div class="obp-stat-note">
                        {{ $card['note'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="obp-table-card">
        <div class="obp-table-header">
            <h5>
                <i class="fas fa-table "></i>
                Booking Module Performance
            </h5>
            <p>
                Comparison of Ticket Booking, Event Booking
                and Mandir Puja Booking
            </p>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking Module</th>
                        <th>Total Bookings</th>
                        <th>Paid Bookings</th>
                        <th>Paid Amount</th>
                        <th>Cancelled Bookings</th>
                        <th>Cancelled Amount</th>
                        <th>Processed Refunds</th>
                        <th>Refund Amount</th>
                        <th>Net Collection</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $index => $module)
                        @php
                            $moduleIcon = match ($module['module']) {
                                'Ticket Booking' => 'fa-ticket-alt',
                                'Event Booking' => 'fa-calendar-alt',
                                'Puja Booking' => 'fa-praying-hands',
                                default => 'fa-list'
                            };
                            $moduleNetAmount =  $module['paid_amount'] - $module['refund_amount'];
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="obp-module-name">
                                    <i class="fas {{ $moduleIcon }}"></i>
                                    {{ $module['module'] }}
                                </span>
                            </td>
                            <td>
                                <strong>
                                    {{ number_format($module['total_bookings']) }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    {{ number_format($module['paid_count']) }}
                                </span>
                            </td>
                            <td>
                                <span class="obp-amount text-success">
                                    ₹{{ number_format($module['paid_amount'], 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-danger">
                                    {{ number_format($module['cancelled_count']) }}
                                </span>
                            </td>
                            <td class="obp-amount">
                                ₹{{ number_format($module['cancelled_amount'], 2) }}
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ number_format($module['refund_count']) }}
                                </span>
                            </td>
                            <td class="obp-amount">
                                ₹{{ number_format($module['refund_amount'], 2) }}
                            </td>
                            <td>
                                <span class="obp-amount ">
                                    ₹{{ number_format($moduleNetAmount, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No business report data available.
                            </td>
                        </tr>
                    @endforelse
               </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>{{ number_format($summary['total_bookings']) }}</td>
                        <td>{{ number_format($summary['paid_count']) }}</td>
                        <td>
                            ₹{{ number_format($summary['paid_amount'], 2) }}
                        </td>
                        <td>{{ number_format($summary['cancelled_count']) }}</td>
                        <td>
                            ₹{{ number_format($summary['cancelled_amount'], 2) }}
                        </td>
                        <td>{{ number_format($summary['refund_count']) }}</td>
                        <td>
                            ₹{{ number_format($summary['refund_amount'], 2) }}
                        </td>
                        <td class="">
                            ₹{{ number_format($summary['net_collection'], 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <p class="obp-note">
        Note: Net collection is calculated as total paid amount minus
        processed refunds included in the report. Cancelled booking
        amounts are displayed separately and are not deducted again.
        Ticket refunds are not included in the current controller.
    </p>
</div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan