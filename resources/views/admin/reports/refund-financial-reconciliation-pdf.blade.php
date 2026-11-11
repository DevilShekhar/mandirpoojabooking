<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Refund & Financial Reconciliation Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 17px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 18px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 13px;
            margin-top: 4px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #343a40;
            color: #fff;
            padding: 6px 4px;
            border: 1px solid #ccc;
            font-size: 8px;
        }

        table.report td {
            padding: 5px 4px;
            border: 1px solid #ccc;
            font-size: 7px;
            word-wrap: break-word;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>

    <h2>Refund & Financial Reconciliation Report</h2>

    <p class="subtitle">
        Generated on {{ now()->format('d-m-Y h:i A') }}
    </p>

    <table class="summary">
        <tr>
            <td>
                Total Refund Requests
                <strong>{{ $refunds->count() }}</strong>
            </td>
            <td>
                Processed Refunds
                <strong>
                    {{ $refunds->where('refund_status', 'processed')->count() }}
                </strong>
            </td>
            <td>
                Pending Refunds
                <strong>
                    {{ $refunds->where('refund_status', 'pending')->count() }}
                </strong>
            </td>
            <td>
                Failed Refunds
                <strong>
                    {{ $refunds->where('refund_status', 'failed')->count() }}
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Processed Refund Amount
                <strong>
                    ₹{{ number_format($refunds->where('refund_status', 'processed')->sum('refund_amount'), 2) }}
                </strong>
            </td>
            <td colspan="2">
                Pending Refund Amount
                <strong>
                    ₹{{ number_format($refunds->where('refund_status', 'pending')->sum('refund_amount'), 2) }}
                </strong>
            </td>
        </tr>
    </table>

    <table class="report">
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
                <th>Status</th>
                <th>Refund Date</th>
                <th>Failure Details</th>
            </tr>
        </thead>

        <tbody>
            @forelse($refunds as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['module'] }}</td>
                    <td>{{ $row['refund_id'] }}</td>
                    <td>{{ $row['payment_id'] }}</td>
                    <td>{{ $row['order_id'] }}</td>
                    <td>{{ $row['booking_id'] }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['temple_name'] }}</td>
                    <td class="text-right">
                        {{ number_format($row['booking_amount'], 2) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($row['refund_amount'], 2) }}
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $row['refund_status'])) }}</td>
                    <td>
                        {{ $row['refund_date']
                            ? \Carbon\Carbon::parse($row['refund_date'])->format('d-m-Y h:i A')
                            : '-' }}
                    </td>
                    <td>{{ $row['failure_details'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">
                        No refund records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Note: Only processed refunds are counted as actual refunds. Pending and failed refunds are shown separately.
    </div>

</body>
</html>