<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Mandir Puja Pandit Report</title>

    <style>
        @page {
            margin: 25px 20px;
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
            color: #222;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }

        .report-info {
            margin-bottom: 12px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 6px 4px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #eeeeee;
            font-weight: bold;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .summary {
            margin-top: 18px;
            width: 100%;
            font-size: 11px;
        }

        .summary table {
            width: 260px;
            margin-left: auto;
        }

        .summary td {
            padding: 7px;
            border: 1px solid #777;
        }

        .summary-label {
            font-weight: bold;
            background-color: #eeeeee;
        }

        .summary-value {
            text-align: right;
            font-weight: bold;
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

    <h2>Mandir / Puja / Pandit Report</h2>

    <div class="subtitle">
        Completed Puja Booking Report
    </div>

    <div class="report-info">
        <strong>Report Generated:</strong>
        {{ now()->format('d-m-Y h:i A') }}
    </div>

    <table>

        <thead>
            <tr>
                <th>#</th>
                <th>Booking No.</th>
                <th>Customer</th>
                <th>Mobile</th>
                <th>Temple</th>
                <th>Puja</th>
                <th>Pandit</th>
                <th>Schedule</th>
                <th>Completed</th>
                <th>Persons</th>
                <th>Pandit Fee</th>
            </tr>
        </thead>

        <tbody>

            @forelse($bookings as $key => $booking)

                <tr>

                    <td class="text-center">
                        {{ $key + 1 }}
                    </td>

                    <td>
                        {{ $booking->booking_number ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->customer_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->customer_mobile ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->temple->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->puja->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $booking->pandit->name ?? 'N/A' }}
                    </td>

                    <td class="text-center">
                        {{ $booking->schedule_date
                            ? $booking->schedule_date->format('d-m-Y')
                            : 'N/A' }}
                    </td>

                    <td class="text-center">
                        {{ $booking->completed_at
                            ? $booking->completed_at->format('d-m-Y h:i A')
                            : 'N/A' }}
                    </td>

                    <td class="text-center">
                        {{ $booking->person_count ?? 0 }}
                    </td>

                    <td class="amount">
                        ₹{{ number_format((float) ($booking->base_amount ?? 0), 2) }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="11" class="text-center">
                        No completed bookings found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- Summary --}}
    <div class="summary">

        <table>

            <tr>
                <td class="summary-label">
                    Total Bookings
                </td>

                <td class="summary-value">
                    {{ $totalBookings ?? $bookings->count() }}
                </td>
            </tr>

            <tr>
                <td class="summary-label">
                    Total Pandit Fee
                </td>

                <td class="summary-value">
                    ₹{{ number_format(
                        (float) ($totalPanditFee ?? $bookings->sum('base_amount')),
                        2
                    ) }}
                </td>
            </tr>

        </table>

    </div>

    <div class="footer">
        Mandir / Puja / Pandit Completed Booking Report
    </div>

</body>
</html>