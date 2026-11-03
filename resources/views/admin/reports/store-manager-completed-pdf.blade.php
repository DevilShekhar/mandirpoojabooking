<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Store Manager Completed Samagri Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
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
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }

        .summary {
            margin-top: 15px;
            font-size: 10px;
            font-weight: bold;
            line-height: 1.8;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Store Manager Completed Samagri Report</h2>

    <div class="details">
        <strong>Temple:</strong>
        {{ $temple->name ?? '' }}
        <br>

        <strong>Store Manager:</strong>
        {{ $storeManager->name ?? 'Not Assigned' }}
        <br>

        <strong>Manager Email:</strong>
        {{ $storeManager->email ?? '' }}
        <br>

        <strong>Verified From:</strong>
        {{ request('from_date') ?: 'All' }}
        &nbsp;&nbsp;

        <strong>Verified To:</strong>
        {{ request('to_date') ?: 'All' }}
        <br>

        <strong>Generated Date:</strong>
        {{ now()->format('d-m-Y h:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Booking Number</th>
                <th>Customer</th>
                <th>Item Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Item Price</th>
                <th>Item Total</th>
                <th>Completed Date</th>
                <th>Verified Date</th>
            </tr>
        </thead>

        <tbody>
            @php $index = 1; @endphp

            @forelse($bookings as $booking)

                @forelse($booking->report_items ?? [] as $item)
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>{{ $booking->booking_number ?? '' }}</td>
                        <td>{{ $booking->customer_name ?? '' }}</td>
                        <td>{{ $item['name'] ?? '' }}</td>
                        <td>{{ $item['unit'] ?? '' }}</td>
                        <td>{{ $item['quantity'] ?? 1 }}</td>
                        <td class="right">
                            ₹{{ number_format((float) ($item['price'] ?? 0), 2) }}
                        </td>
                        <td class="right">
                            ₹{{ number_format((float) ($item['total'] ?? 0), 2) }}
                        </td>
                        <td>
                            {{ !empty($booking->completed_at)
                                ? \Carbon\Carbon::parse($booking->completed_at)->format('d-m-Y h:i A')
                                : '' }}
                        </td>
                        <td>
                            {{ !empty($booking->samgri_otp_verified_at)
                                ? \Carbon\Carbon::parse($booking->samgri_otp_verified_at)->format('d-m-Y h:i A')
                                : '' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>{{ $booking->booking_number ?? '' }}</td>
                        <td>{{ $booking->customer_name ?? '' }}</td>
                        <td colspan="7" class="center">
                            No item details found
                        </td>
                    </tr>
                @endforelse

            @empty
                <tr>
                    <td colspan="10" class="center">
                        No completed and verified bookings found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        Total Completed Bookings:
        {{ $summary['total_bookings'] ?? 0 }}
        <br>

        Total Item Quantity:
        {{ $summary['total_quantity'] ?? 0 }}
        <br>

        Total Item Amount:
        ₹{{ number_format((float) ($summary['total_amount'] ?? 0), 2) }}
    </div>

</body>
</html>