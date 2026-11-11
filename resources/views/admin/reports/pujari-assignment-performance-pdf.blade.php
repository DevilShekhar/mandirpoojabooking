
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pujari Assignment & Performance Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px 22px 35px 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #344054;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #596fe8;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header-title {
            font-size: 19px;
            font-weight: bold;
            color: #263445;
            margin: 0 0 5px;
        }

        .header-subtitle {
            font-size: 9px;
            color: #7b8794;
            margin: 0;
        }

        .report-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 17px;
        }

        .report-meta td {
            width: 20%;
            padding: 9px;
            border: 1px solid #e5eaf0;
            background: #f7f9fc;
            vertical-align: top;
        }

        .meta-label {
            display: block;
            color: #8491a3;
            font-size: 8px;
            margin-bottom: 5px;
        }

        .meta-value {
            display: block;
            color: #263445;
            font-size: 10px;
            font-weight: bold;
        }

        .section-title {
            color: #263445;
            font-size: 12px;
            font-weight: bold;
            margin: 17px 0 9px;
            padding-bottom: 7px;
            border-bottom: 1px solid #e5eaf0;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #e5eaf0;
            padding: 11px 10px;
            vertical-align: middle;
        }

        .summary-label {
            display: block;
            font-size: 8px;
            color: #8491a3;
            margin-bottom: 7px;
        }

        .summary-value {
            display: block;
            font-size: 17px;
            font-weight: bold;
            color: #263445;
        }

        .blue { color: #596fe8; }
        .green { color: #198754; }
        .orange { color: #c17a1b; }
        .red { color: #c43d49; }
        .purple { color: #8558c9; }
        .cyan { color: #1683a5; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table th {
            background: #eef1f6;
            color: #344054;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            padding: 8px 6px;
            border: 1px solid #dfe5ed;
        }

        .data-table td {
            padding: 7px 6px;
            border: 1px solid #e5eaf0;
            font-size: 8px;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f9fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 4px 6px;
            font-size: 7px;
            font-weight: bold;
            border-radius: 3px;
            white-space: nowrap;
        }

        .badge-confirmed,
        .badge-completed,
        .badge-paid,
        .badge-processed {
            background: #e8f6ed;
            color: #198754;
        }

        .badge-pending {
            background: #fff5df;
            color: #a76b0c;
        }

        .badge-cancelled,
        .badge-failed {
            background: #fdeced;
            color: #c43d49;
        }

        .badge-default {
            background: #eef1f5;
            color: #64748b;
        }

        .empty-message {
            text-align: center;
            padding: 16px;
            color: #8491a3;
            font-size: 9px;
        }

        .note {
            font-size: 8px;
            color: #7b8794;
            margin-top: 10px;
            line-height: 1.5;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            border-top: 1px solid #e5eaf0;
            padding-top: 7px;
            font-size: 8px;
            color: #8792a2;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .page-number:after {
            content: counter(page);
        }

        .page-count:after {
            content: counter(pages);
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1 class="header-title">
            Pujari Assignment & Performance Report
        </h1>

        <p class="header-subtitle">
            Temple-wise Puja Assignment, Pujari Performance, Booking and Refund Details
        </p>
    </div>

    {{-- Report Information --}}
    <table class="report-meta">
        <tr>
            <td>
                <span class="meta-label">Temple Name</span>
                <span class="meta-value">
                    {{ $selectedTemple->name ?? 'N/A' }}
                </span>
            </td>

            <td>
                <span class="meta-label">Selected Pujari</span>
                <span class="meta-value">
                    {{ $selectedPandit->name ?? 'All Pujaris' }}
                </span>
            </td>

            <td>
                <span class="meta-label">Selected Status</span>
                <span class="meta-value">
                    {{ $selectedStatus ?? 'All' }}
                </span>
            </td>

            <td>
                <span class="meta-label">From Date</span>
                <span class="meta-value">
                    {{ !empty($filters['from_date'])
                        ? \Carbon\Carbon::parse($filters['from_date'])->format('d-m-Y')
                        : 'All Dates' }}
                </span>
            </td>

            <td>
                <span class="meta-label">To Date</span>
                <span class="meta-value">
                    {{ !empty($filters['to_date'])
                        ? \Carbon\Carbon::parse($filters['to_date'])->format('d-m-Y')
                        : 'All Dates' }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Performance Summary --}}
    <h3 class="section-title">Performance Summary</h3>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Total Assignments</span>
                <span class="summary-value blue">
                    {{ number_format($summary['total_assignments']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Confirmed</span>
                <span class="summary-value green">
                    {{ number_format($summary['confirmed']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Completed</span>
                <span class="summary-value green">
                    {{ number_format($summary['completed']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Cancelled</span>
                <span class="summary-value red">
                    {{ number_format($summary['cancelled']) }}
                </span>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Pending</span>
                <span class="summary-value orange">
                    {{ number_format($summary['pending']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Paid Bookings</span>
                <span class="summary-value purple">
                    {{ number_format($summary['paid_bookings']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Refund Bookings</span>
                <span class="summary-value red">
                    {{ number_format($summary['refund_bookings']) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Refund Amount</span>
                <span class="summary-value orange">
                    Rs. {{ number_format($summary['refund_amount'], 2) }}
                </span>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Total Revenue</span>
                <span class="summary-value green">
                    Rs. {{ number_format($summary['revenue'], 2) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Completion Percentage</span>
                <span class="summary-value cyan">
                    {{ number_format($summary['completion_percentage'], 2) }}%
                </span>
            </td>

            <td>
                <span class="summary-label">Booking Records</span>
                <span class="summary-value blue">
                    {{ number_format($bookingRows->count()) }}
                </span>
            </td>

            <td>
                <span class="summary-label">Report Status</span>
                <span class="summary-value blue">
                    {{ $selectedStatus ?? 'All' }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Pujari-wise Performance --}}
    <h3 class="section-title">
        Pujari-wise Performance
    </h3>

    <table class="data-table">

        <thead>
            <tr>
                <th width="4%" class="text-center">#</th>
                <th width="17%">Pujari Name</th>
                <th width="17%">Email</th>
                <th width="10%" class="text-center">Assignments</th>
                <th width="9%" class="text-center">Confirmed</th>
                <th width="9%" class="text-center">Completed</th>
                <th width="8%" class="text-center">Pending</th>
                <th width="8%" class="text-center">Cancelled</th>
                <th width="9%" class="text-center">Paid</th>
                <th width="10%" class="text-right">Revenue</th>
                <th width="9%" class="text-center">Completion %</th>
            </tr>
        </thead>

        <tbody>

            @forelse($performanceRows as $index => $row)

                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="text-bold">
                        {{ $row->pandit_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $row->pandit_email ?? 'N/A' }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->total_assignments) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->confirmed) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->completed) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->pending) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->cancelled) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->paid_bookings) }}
                    </td>

                    <td class="text-right">
                        Rs. {{ number_format($row->revenue, 2) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->completion_percentage, 2) }}%
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="11" class="empty-message">
                        No Pujari performance records found.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

    {{-- Puja Assignment Details --}}
    <h3 class="section-title">
        Puja Assignment Details
    </h3>

    <table class="data-table">

        <thead>
            <tr>
                <th width="3%" class="text-center">#</th>
                <th width="9%">Booking Number</th>
                <th width="11%">Temple</th>
                <th width="10%">Puja</th>
                <th width="10%">Pujari</th>
                <th width="11%">Customer</th>
                <th width="8%">Mobile</th>
                <th width="8%">Schedule Date</th>
                <th width="7%">Time</th>
                <th width="8%" class="text-right">Amount</th>
                <th width="7%">Payment</th>
                <th width="8%">Booking Status</th>
                <th width="8%">Refund Status</th>
                <th width="8%" class="text-right">Refund Amount</th>
            </tr>
        </thead>

        <tbody>

            @forelse($bookingRows as $index => $booking)

                @php
                    $paymentStatus = strtolower($booking->payment_status ?? '');
                    $bookingStatus = strtolower($booking->booking_status ?? '');
                    $refundStatus = strtolower($booking->refund_status ?? '');

                    $paymentClass = match($paymentStatus) {
                        'paid' => 'badge-paid',
                        'failed' => 'badge-failed',
                        'pending' => 'badge-pending',
                        default => 'badge-default',
                    };

                    $bookingClass = match($bookingStatus) {
                        'completed' => 'badge-completed',
                        'cancelled' => 'badge-cancelled',
                        'confirmed' => 'badge-confirmed',
                        'pending' => 'badge-pending',
                        default => 'badge-default',
                    };

                    $refundClass = $refundStatus === 'processed'
                        ? 'badge-processed'
                        : 'badge-default';
                @endphp

                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="text-bold">
                        {{ $booking->booking_number ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->temple_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->puja_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->pandit_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->customer_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->customer_mobile ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->schedule_date
                            ? \Carbon\Carbon::parse($booking->schedule_date)->format('d-m-Y')
                            : 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->schedule_time ?? 'N/A' }}
                    </td>

                    <td class="text-right">
                        Rs. {{ number_format((float)$booking->total_amount, 2) }}
                    </td>

                    <td>
                        <span class="badge {{ $paymentClass }}">
                            {{ ucfirst($paymentStatus ?: 'N/A') }}
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $bookingClass }}">
                            {{ ucfirst(str_replace('_', ' ', $bookingStatus ?: 'N/A')) }}
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $refundClass }}">
                            {{ ucfirst($refundStatus ?: 'N/A') }}
                        </span>
                    </td>

                    <td class="text-right">
                        Rs. {{ number_format((float)($booking->refund_amount ?? 0), 2) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="14" class="empty-message">
                        No Puja assignment records found for the selected filters.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

    {{-- Report Note --}}
    <p class="note">
        Note: This report includes assigned Puja bookings for the selected Temple.
        Confirmed and Cancelled records are based on booking status.
        Refund records are based on processed refund entries.
        Revenue is calculated from bookings whose payment status is Paid.
    </p>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">
            Pujari Assignment & Performance Report
        </div>

        <div class="footer-right">
            Page <span class="page-number"></span>
            of <span class="page-count"></span>
        </div>

        <div class="clear"></div>
    </div>

</body>
</html>