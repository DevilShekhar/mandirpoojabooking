
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Temple Performance Report</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
        }
        .header h2 {
            font-size: 20px;
            margin: 0 0 5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .temple-info {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f7f7f7;
        }
        .temple-info table {
            width: 100%;
        }
        .temple-info td {
            padding: 4px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 18px 0 8px;
            padding: 7px;
            background: #eaeaea;
            border-left: 4px solid #333;
        }
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .report-table th,
        .report-table td {
            border: 1px solid #bbb;
            padding: 7px 5px;
            text-align: left;
            vertical-align: middle;
        }
        .report-table th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }
        .report-table td.center {
            text-align: center;
        }
        .report-table td.amount {
            text-align: right;
            white-space: nowrap;
        }
        .report-table tfoot th,
        .report-table tfoot td {
            background: #ededed;
            font-weight: bold;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-table td {
            width: 20%;
            border: 1px solid #bbb;
            padding: 10px 5px;
            text-align: center;
        }
        .summary-label {
            font-size: 9px;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 15px;
            font-weight: bold;
        }
        .status {
            font-weight: bold;
        }
        .status-paid,
        .status-completed {
            color: #16803c;
        }
        .status-confirmed {
            color: #1769aa;
        }
        .status-cancelled {
            color: #c62828;
        }
        .status-cancel_requested {
            color: #a66a00;
        }
        .status-pending {
            color: #666;
        }
        .note {
            margin-top: 15px;
            padding: 9px;
            border: 1px solid #ccc;
            background: #f7f7f7;
            font-size: 9px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
        .text-right {
            text-align: right;
        }
        .no-records {
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Temple Performance Report</h2>
        <p>Temple-wise Booking and Revenue Details</p>
        <p>
            Generated On:
            {{ now()->format('d-m-Y h:i A') }}
        </p>
    </div>
    @if($selectedTemple)
        <div class="temple-info">
            <table>
                <tr>
                    <td>
                        <strong>Temple Name:</strong>
                        {{ $selectedTemple->name }}
                    </td>
                    <td>
                        <strong>City:</strong>
                        {{ $selectedTemple->city_name ?? '-' }}
                    </td>
                    <td>
                        <strong>State:</strong>
                        {{ $selectedTemple->state_name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>From Date:</strong>
                        {{ $filters['from_date'] ?: 'All Dates' }}
                    </td>
                    <td>
                        <strong>To Date:</strong>
                        {{ $filters['to_date'] ?: 'All Dates' }}
                    </td>
                    <td>
                        <strong>Selected Status:</strong>
                        @php
                            $statusLabels = [
                                'all' => 'All Bookings',
                                'paid' => 'Paid',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'cancel_requested' => 'Cancel Requested',
                            ];
                        @endphp
                        {{ $statusLabels[$filters['status'] ?? 'all'] ?? 'All Bookings' }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="section-title">
            Overall Performance Summary
        </div>
        <table class="summary-table">
            <tr>
                <td>
                    <span class="summary-label">Total Bookings</span>
                    <span class="summary-value">
                        {{ number_format($summary['total_bookings']) }}
                    </span>
                </td>
                <td>
                    <span class="summary-label">Paid Bookings</span>
                    <span class="summary-value">
                        {{ number_format($summary['paid_bookings']) }}
                    </span>
                </td>
                <td>
                    <span class="summary-label">Completed Bookings</span>
                    <span class="summary-value">
                        {{ number_format($summary['completed_bookings']) }}
                    </span>
                </td>
                <td>
                    <span class="summary-label">Cancelled Bookings</span>
                    <span class="summary-value">
                        {{ number_format($summary['cancelled_bookings']) }}
                    </span>
                </td>
                <td>
                    <span class="summary-label">Total Revenue</span>
                    <span class="summary-value">
                        ₹{{ number_format($summary['total_revenue'], 2) }}
                    </span>
                </td>
            </tr>
        </table>
        <div class="section-title">
            Temple Performance Summary
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">Booking Module</th>
                    <th width="13%">Total Bookings</th>
                    <th width="13%">Paid Bookings</th>
                    <th width="15%">Completed</th>
                    <th width="15%">Cancelled</th>
                    <th width="19%">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $index => $report)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $report['module'] }}</td>
                        <td class="center">
                            {{ number_format($report['total_bookings']) }}
                        </td>
                        <td class="center">
                            {{ number_format($report['paid_bookings']) }}
                        </td>
                        <td class="center">
                            {{ number_format($report['completed_bookings']) }}
                        </td>
                        <td class="center">
                            {{ number_format($report['cancelled_bookings']) }}
                        </td>
                        <td class="amount">
                            ₹{{ number_format($report['revenue'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-records">
                            No performance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Grand Total</th>
                    <th class="center">
                        {{ number_format($summary['total_bookings']) }}
                    </th>
                    <th class="center">
                        {{ number_format($summary['paid_bookings']) }}
                    </th>
                    <th class="center">
                        {{ number_format($summary['completed_bookings']) }}
                    </th>
                    <th class="center">
                        {{ number_format($summary['cancelled_bookings']) }}
                    </th>
                    <th class="amount">
                        ₹{{ number_format($summary['total_revenue'], 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="section-title">
            Customer-wise Booking Details
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="23%">Customer Name</th>
                    <th width="15%">Booking Type</th>
                    <th width="17%">Booking Status</th>
                    <th width="15%">Payment Status</th>
                    <th width="12%">Amount</th>
                    <th width="13%">Booking Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customerBookings as $index => $booking)
                    @php
                        $bookingStatus = strtolower($booking['booking_status'] ?? '');
                        $paymentStatus = strtolower($booking['payment_status'] ?? '');
                        $statusClass = in_array($bookingStatus, [
                            'confirmed',
                            'completed',
                            'cancelled',
                            'cancel_requested',
                        ], true)
                            ? 'status-' . $bookingStatus
                            : 'status-pending';
                    @endphp
                    <tr>
                        <td class="center">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            {{ $booking['customer_name'] }}
                        </td>
                        <td>
                            {{ $booking['booking_type'] }}
                        </td>
                        <td>
                            <span class="status {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $bookingStatus ?: 'Pending')) }}
                            </span>
                        </td>
                        <td>
                            <span class="status {{ $paymentStatus === 'paid' ? 'status-paid' : 'status-pending' }}">
                                {{ ucfirst($paymentStatus ?: '-') }}
                            </span>
                        </td>
                        <td class="amount">
                            ₹{{ number_format($booking['amount'], 2) }}
                        </td>
                        <td>
                            {{ $booking['created_at']
                                ? \Carbon\Carbon::parse($booking['created_at'])->format('d-m-Y h:i A')
                                : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-records">
                            No customer bookings found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($customerBookings) > 0)
                <tfoot>
                    <tr>
                        <th colspan="5">Customer Booking Total</th>
                        <th class="amount">
                            ₹{{ number_format(collect($customerBookings)->sum('amount'), 2) }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            @endif
        </table>
        <div class="note">
            <strong>Note:</strong>
            Performance summary figures show all booking statuses for the selected temple
            and date range. The selected booking status filters only the customer-wise
            booking details.
            Paid is based on payment_status = paid. Confirmed, Completed, Cancelled
            and Cancel Requested are based on booking_status.
            Event bookings are excluded because they are not linked to a temple
            in the current report structure.
        </div>
    @endif
    <div class="footer">
        This is a computer-generated Temple Performance Report.
    </div>
</body>
</html>