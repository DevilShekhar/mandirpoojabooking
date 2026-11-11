
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>State-wise Revenue Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 18px;
        }

        .filters {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .filters td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            border: 1px solid #ccc;
            padding: 9px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 14px;
            margin-top: 5px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #343a40;
            color: #fff;
            padding: 8px;
            border: 1px solid #ccc;
        }

        table.report td {
            padding: 7px;
            border: 1px solid #ccc;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background: #f1f3f5;
        }

        .footer {
            margin-top: 15px;
            color: #666;
            font-size: 8px;
        }
    </style>
</head>
<body>

    <h2>State-wise Revenue Report</h2>

    <p class="subtitle">
        Generated on {{ now()->format('d-m-Y h:i A') }}
    </p>

    <table class="filters">
        <tr>
            <td>
                <strong>State:</strong> {{ $state->name }}
            </td>
            <td>
                <strong>From Date:</strong>
                {{ !empty($filters['from_date']) ? \Carbon\Carbon::parse($filters['from_date'])->format('d-m-Y') : 'All Dates' }}
            </td>
            <td>
                <strong>To Date:</strong>
                {{ !empty($filters['to_date']) ? \Carbon\Carbon::parse($filters['to_date'])->format('d-m-Y') : 'All Dates' }}
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td>
                Total Bookings
                <strong>{{ number_format($summary['total_bookings']) }}</strong>
            </td>
            <td>
                Paid Bookings
                <strong>{{ number_format($summary['paid_bookings']) }}</strong>
            </td>
            <td>
                Puja Bookings
                <strong>{{ number_format($summary['puja_bookings']) }}</strong>
            </td>
            <td>
                Ticket Bookings
                <strong>{{ number_format($summary['ticket_bookings']) }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                Puja Revenue
                <strong>₹{{ number_format($summary['puja_revenue'], 2) }}</strong>
            </td>
            <td>
                Ticket Revenue
                <strong>₹{{ number_format($summary['ticket_revenue'], 2) }}</strong>
            </td>
            <td colspan="2">
                Total Revenue
                <strong>₹{{ number_format($summary['total_revenue'], 2) }}</strong>
            </td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Temple</th>
                <th>Module</th>
                <th>Total Bookings</th>
                <th>Paid Bookings</th>
                <th>Revenue (₹)</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $row['temple_name'] }}</td>
                    <td>{{ $row['module'] }}</td>
                    <td class="center">{{ number_format($row['total_bookings']) }}</td>
                    <td class="center">{{ number_format($row['paid_bookings']) }}</td>
                    <td class="right">{{ number_format($row['revenue'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">No booking records found.</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="3" class="right">Grand Total</td>
                <td class="center">{{ number_format($summary['total_bookings']) }}</td>
                <td class="center">{{ number_format($summary['paid_bookings']) }}</td>
                <td class="right">{{ number_format($summary['total_revenue'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Total Bookings includes all booking statuses. Paid Bookings and Revenue include only payment_status = paid.
        Ticket bookings are counted by distinct Razorpay Order ID.
    </div>

</body>
</html>