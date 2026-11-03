<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Completed Event Booking Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 15px;
            line-height: 1.7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        .summary {
            margin-top: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Completed Event Booking Report</h2>

    <div class="info">
        <strong>Event Creator:</strong>
        {{ $eventCreator->name ?? '' }}
        <br>

        <strong>From Date:</strong>
        {{ request('from_date') ?: 'All' }}

        &nbsp;&nbsp;

        <strong>To Date:</strong>
        {{ request('to_date') ?: 'All' }}

        <br>

        <strong>Generated Date:</strong>
        {{ now()->format('d-m-Y h:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Booking ID</th>
                <th>Event</th>
                <th>Customer</th>
                <th>Event Date</th>
                <th>Event Time</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Completed Date</th>
                <th>Completed By</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->event_name ?? '' }}</td>
                    <td>{{ $booking->customer_name ?? '' }}</td>
                    <td>{{ $booking->event_date ?? '' }}</td>
                    <td>{{ $booking->event_time ?? '' }}</td>
                    <td>
                        ₹{{ number_format((float) ($booking->amount ?? 0), 2) }}
                    </td>
                    <td>{{ ucfirst($booking->payment_status ?? '') }}</td>
                    <td>
                        {{ $booking->completed_at
                            ? \Carbon\Carbon::parse($booking->completed_at)->format('d-m-Y h:i A')
                            : '' }}
                    </td>
                    <td>{{ $booking->completed_by_name ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;">
                        No completed bookings found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        Total Completed Bookings: {{ $totalBookings }}
        <br>
        Total Amount: ₹{{ number_format((float) $totalAmount, 2) }}
    </div>

</body>
</html>