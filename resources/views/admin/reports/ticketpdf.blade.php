<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Booking Temple Report</title>
    <style>
        @page {
            margin: 20px 15px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }
        .details {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .details td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        .summary {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .summary td {
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-title {
            font-size: 10px;
            font-weight: bold;
        }
        .summary-value {
            font-size: 15px;
            font-weight: bold;
            margin-top: 5px;
        }
        table.report {
            width: 100%;
            border-collapse: collapse;
        }
        table.report th {
            background: #34495e;
            color: #fff;
            padding: 7px 4px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
        }
        table.report td {
            padding: 6px 4px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
        }
        table.report tr:nth-child(even) {
            background: #f5f5f5;
        }
        .paid {
            color: #198754;
            font-weight: bold;
        }
        .pending {
            color: #d39e00;
            font-weight: bold;
        }
        .failed,
        .cancelled {
            color: #dc3545;
            font-weight: bold;
        }
        .confirmed {
            color: #198754;
            font-weight: bold;
        }
        .total-row td {
            font-weight: bold;
            background: #e9ecef;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Ticket Booking Temple Report</h2>
    <div class="subtitle">
        Temple-wise Ticket Booking Details
    </div>
    <table class="details">
        <tr>
            <td>
                <strong>Temple Name:</strong>
                {{ $temple->name ?? 'N/A' }}
            </td>
            <td>
                <strong>From Date:</strong>
                {{ \Carbon\Carbon::parse($request->from_date)->format('d-m-Y') }}
            </td>
            <td>
                <strong>To Date:</strong>
                {{ \Carbon\Carbon::parse($request->to_date)->format('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Payment Status:</strong>
                {{ $request->payment_status ? ucfirst($request->payment_status) : 'All' }}
            </td>
            <td>
                <strong>Booking Status:</strong>
                {{ $request->booking_status ? ucfirst($request->booking_status) : 'All' }}
            </td>
            <td>
                <strong>Generated On:</strong>
                {{ now()->format('d-m-Y h:i A') }}
            </td>
        </tr>
    </table>
    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">Total Bookings</div>
                <div class="summary-value">
                    {{ number_format($totalBookings) }}
                </div>
            </td>
            <td>
                <div class="summary-title">Total Amount</div>
                <div class="summary-value">
                    Rs. {{ number_format($totalAmount, 2) }}
                </div>
            </td>
        </tr>
    </table>
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Booking Reference</th>
                <th>Booking Date</th>
                <th>Member Name</th>
                <th>Ticket Type</th>
                <th>Type</th>
                <th>Age</th>
                <th>Price (Rs.)</th>
                <th>Payment Status</th>
                <th>Booking Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $key => $booking)
                @php
                    $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                    $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $booking->booking_reference }}</td>
                    <td>
                        {{ $booking->booking_date
                            ? \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y')
                            : '' }}
                    </td>
                    <td>{{ $booking->member_name }}</td>
                    <td>{{ $booking->ticket _type }}</td>
                    <td>{{ $booking->type }}</td>
                    <td>{{ $booking->age }}</td>
                    <td>
                        {{ number_format((float) $booking->price, 2) }}
                    </td>
                    <td class="{{ $paymentStatus }}">
                        {{ ucfirst($paymentStatus) }}
                    </td>
                    <td class="{{ $bookingStatus }}">
                        {{ ucfirst($bookingStatus) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        No ticket booking records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($bookings->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="7" style="text-align:right;">
                        Total Amount
                    </td>
                    <td>
                        Rs. {{ number_format($totalAmount, 2) }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        @endif
    </table>
    <div class="footer">
        This is a computer-generated Ticket Booking Temple Report.
   </div>
</body>
</html>