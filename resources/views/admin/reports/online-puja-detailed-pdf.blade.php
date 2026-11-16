<!DOCTYPE html>
<html>
<head>
    <title>Online Puja Detailed Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Online Puja Detailed Report</h2>
    <div class="summary">
        <p>Total Bookings: {{ $totalBookings }}</p>
        <p>Total Revenue: {{ number_format($totalRevenue, 2) }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Puja</th>
                <th>Package</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $row)
            <tr>
                <td>{{ $row->order_number }}</td>
                <td>{{ $row->customer_name ?? ($row->customer->name ?? 'N/A') }}</td>
                <td>{{ $row->puja->title ?? 'N/A' }}</td>
                <td>{{ $row->package_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($row->booking_status) }}</td>
                <td>{{ number_format($row->amount, 2) }}</td>
                <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>