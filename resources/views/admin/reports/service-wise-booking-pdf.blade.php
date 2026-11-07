<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Service-wise Booking Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
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
            padding: 7px;
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

    <h2>Service-wise Booking Report</h2>

    <div class="details">
        <strong>From Date:</strong> {{ $filters['from_date'] ?: 'All' }}<br>
        <strong>To Date:</strong> {{ $filters['to_date'] ?: 'All' }}<br>
        <strong>Generated On:</strong> {{ now()->format('d-m-Y h:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Booking Module</th>
                <th>Service Name</th>
                <th>Pending</th>
                <th>Confirmed</th>
                <th>Completed</th>
                <th>Cancelled</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['module'] }}</td>
                    <td>{{ $row['service_name'] }}</td>
                    <td>{{ $row['pending'] }}</td>
                    <td>{{ $row['confirmed'] }}</td>
                    <td>{{ $row['completed'] }}</td>
                    <td>{{ $row['cancelled'] }}</td>
                    <td>{{ $row['total'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No booking records found.</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="3">Grand Total</td>
                <td>{{ $summary['pending'] }}</td>
                <td>{{ $summary['confirmed'] }}</td>
                <td>{{ $summary['completed'] }}</td>
                <td>{{ $summary['cancelled'] }}</td>
                <td>{{ $summary['total'] }}</td>
            </tr>
        </tbody>
    </table>

    <p class="note">
        Ticket bookings are counted by distinct Razorpay order ID within each ticket type.
        Event bookings use the related Sanatan name. Samgri verification uses the related
        Puja name and the Puja booking creation date.
    </p>

</body>
</html>