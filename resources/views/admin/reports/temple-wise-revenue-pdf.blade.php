<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Temple-wise Revenue Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333333;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #666666;
            margin-bottom: 22px;
        }

        .details {
            margin-bottom: 20px;
            line-height: 2;
        }

        .summary-table,
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table {
            margin-bottom: 25px;
        }

        .summary-table td {
            border: 1px solid #dddddd;
            padding: 14px;
            text-align: center;
        }

        .summary-label {
            color: #666666;
            font-size: 10px;
        }

        .summary-value {
            font-size: 17px;
            font-weight: bold;
            margin-top: 8px;
        }

        .report-table th {
            background: #edf1f7;
            font-weight: bold;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #cccccc;
            padding: 11px;
            text-align: left;
        }

        .report-table th:nth-child(3),
        .report-table th:nth-child(4),
        .report-table td:nth-child(3),
        .report-table td:nth-child(4) {
            text-align: right;
        }

        .grand-total {
            background: #edf1f7;
            font-weight: bold;
        }

        .note {
            margin-top: 20px;
            color: #666666;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <h2>Temple-wise Revenue Report</h2>
    <div class="subtitle">Separate Ticket and Puja Booking Revenue</div>

    <div class="details">
        <strong>Temple:</strong> {{ $selectedTemple->name }}<br>
        <strong>From Date:</strong> {{ $fromDate ?? 'All Dates' }}<br>
        <strong>To Date:</strong> {{ $toDate ?? 'All Dates' }}
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Bookings</div>
                <div class="summary-value">{{ number_format($summary['total_bookings']) }}</div>
            </td>
            <td>
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">Rs. {{ number_format($summary['total_revenue'], 2) }}</div>
            </td>
            <td>
                <div class="summary-label">Ticket Bookings</div>
                <div class="summary-value">{{ number_format($summary['ticket_bookings']) }}</div>
            </td>
            <td>
                <div class="summary-label">Ticket Revenue</div>
                <div class="summary-value">Rs. {{ number_format($summary['ticket_revenue'], 2) }}</div>
            </td>
            <td>
                <div class="summary-label">Puja Bookings</div>
                <div class="summary-value">{{ number_format($summary['puja_bookings']) }}</div>
            </td>
            <td>
                <div class="summary-label">Puja Revenue</div>
                <div class="summary-value">Rs. {{ number_format($summary['puja_revenue'], 2) }}</div>
            </td>
        </tr>
    </table>

    <table class="report-table">
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
                    <td>{{ $row['module'] }}</td>
                    <td>{{ number_format($row['booking_count']) }}</td>
                    <td>Rs. {{ number_format($row['revenue'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="grand-total">
                <td colspan="2">Grand Total</td>
                <td>{{ number_format($summary['total_bookings']) }}</td>
                <td>Rs. {{ number_format($summary['total_revenue'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="note">
        Ticket booking count uses distinct Razorpay order IDs for paid and confirmed tickets. Puja booking count uses paid Puja booking records.
    </div>
</body>
</html>