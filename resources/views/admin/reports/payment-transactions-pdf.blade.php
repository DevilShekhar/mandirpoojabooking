
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Transaction Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #222; }
        h2 { text-align: center; margin-bottom: 5px; }
        .meta { text-align: center; margin-bottom: 15px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background: #eeeeee; font-weight: bold; }
        .amount { text-align: right; white-space: nowrap; }
    </style>
</head>
<body>
    <h2>Payment Transaction Report</h2>
    <div class="meta">
        Generated on: {{ now()->format('d-m-Y h:i A') }}
        @if(!empty($filters['from_date']))
            | From: {{ $filters['from_date'] }}
        @endif
        @if(!empty($filters['to_date']))
            | To: {{ $filters['to_date'] }}
        @endif
        @if(!empty($filters['module']))
            | Module: {{ ucfirst($filters['module']) }}
        @endif
        @if(!empty($filters['payment_status']))
            | Status: {{ ucfirst($filters['payment_status']) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Transaction ID</th>
                <th>Order ID</th>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Temple</th>
                <th>Service</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Gateway</th>
                <th>Date</th>
                <th>Failure Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['module'] }}</td>
                    <td>{{ $row['transaction_id'] }}</td>
                    <td>{{ $row['order_id'] }}</td>
                    <td>{{ $row['booking_id'] }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['temple_name'] }}</td>
                    <td>{{ $row['service_name'] }}</td>
                    <td class="amount">{{ number_format($row['amount'], 2) }}</td>
                    <td>{{ $row['currency'] }}</td>
                    <td>{{ ucfirst($row['payment_status']) }}</td>
                    <td>{{ $row['gateway'] }}</td>
                    <td>{{ $row['transaction_date'] ? \Carbon\Carbon::parse($row['transaction_date'])->format('d-m-Y h:i A') : '-' }}</td>
                    <td>{{ $row['failure_details'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>