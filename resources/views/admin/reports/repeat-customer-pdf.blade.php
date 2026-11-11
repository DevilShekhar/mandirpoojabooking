<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Repeat Customer Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 18px;
        }
        .details {
            margin-bottom: 15px;
            line-height: 1.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #777;
            padding: 7px 5px;
            text-align: center;
        }
        th {
            background-color: #eeeeee;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #eeeeee;
        }
        .note {
            margin-top: 15px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <h2>Repeat Customer Report</h2>
    <div class="details">
        <strong>From Date:</strong> {{ $filters['from_date'] ?: 'All' }}<br>
        <strong>To Date:</strong> {{ $filters['to_date'] ?: 'All' }}<br>
        <strong>Generated On:</strong> {{ now()->format('d-m-Y h:i A') }}<br>
        <strong>Unique Repeat Customers:</strong> {{ $summary['unique_customers'] }}<br>
        <strong>Total Repeat Bookings:</strong> {{ $summary['total_bookings'] }}
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Booking Type</th>
                <th>Temple Name</th>
                <th>City</th>
                <th>State</th>
                <th>Booking Count</th>
                <th>Last Booking Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['booking_type'] }}</td>
                    <td>{{ $row['temple_name'] }}</td>
                    <td>{{ $row['city_name'] }}</td>
                    <td>{{ $row['state_name'] }}</td>
                    <td>{{ $row['booking_count'] }}</td>
                    <td>
                        {{ $row['last_booking'] ? \Carbon\Carbon::parse($row['last_booking'])->format('d-m-Y h:i A') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No repeat customers found.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="6">Grand Total</td>
                <td>{{ $summary['total_bookings'] }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p class="note">
        Only customers with two or more bookings in the same module and temple are included.
        Event bookings are independent and are not associated with a Temple in the current database structure.
        Samgri verification is excluded to avoid duplicate Puja booking counts.
    </p>
</body>
</html>