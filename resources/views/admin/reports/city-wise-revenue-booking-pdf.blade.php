<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>City-wise Revenue & Booking Report</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #253247;
        }

        h2 {
            text-align: center;
            color: #17365d;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }

        .filters {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .filters td {
            padding: 7px;
            border: 1px solid #ddd;
        }

        .summary {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #d8e1ec;
            padding: 10px;
            width: 25%;
        }

        .summary .label {
            font-size: 9px;
            color: #666;
        }

        .summary .value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #17365d;
            color: #fff;
            padding: 9px 6px;
            border: 1px solid #17365d;
            text-align: left;
        }

        .report-table td {
            padding: 8px 6px;
            border: 1px solid #d9e0e8;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f4f7fb;
        }

        .report-table tfoot td {
            background: #e8eff7;
            font-weight: bold;
        }

        .right {
            text-align: right !important;
        }

        .center {
            text-align: center !important;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h2>City-wise Revenue & Booking Report</h2>

    <div class="subtitle">
        Puja & Ticket Booking Performance
    </div>

    <table class="filters">
        <tr>
            <td>
                <strong>State:</strong>
                {{ $state->name }}
            </td>

            <td>
                <strong>City:</strong>
                {{ $city->name }}
            </td>

            <td>
                <strong>From Date:</strong>
                {{ $filters['from_date'] ?? 'All Dates' }}
            </td>

            <td>
                <strong>To Date:</strong>
                {{ $filters['to_date'] ?? 'All Dates' }}
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Bookings</div>
                <div class="value">
                    {{ number_format($summary['total_bookings']) }}
                </div>
            </td>

            <td>
                <div class="label">Paid Bookings</div>
                <div class="value">
                    {{ number_format($summary['paid_bookings']) }}
                </div>
            </td>

            <td>
                <div class="label">Total Temples</div>
                <div class="value">
                    {{ number_format($summary['total_temples']) }}
                </div>
            </td>

            <td>
                <div class="label">Total Revenue</div>
                <div class="value">
                    Rs. {{ number_format($summary['total_revenue'], 2) }}
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="label">Puja Bookings</div>
                <div class="value">
                    {{ number_format($summary['puja_bookings']) }}
                </div>
            </td>

            <td>
                <div class="label">Ticket Bookings</div>
                <div class="value">
                    {{ number_format($summary['ticket_bookings']) }}
                </div>
            </td>

            <td>
                <div class="label">Puja Revenue</div>
                <div class="value">
                    Rs. {{ number_format($summary['puja_revenue'], 2) }}
                </div>
            </td>

            <td>
                <div class="label">Ticket Revenue</div>
                <div class="value">
                    Rs. {{ number_format($summary['ticket_revenue'], 2) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="35%">Temple Name</th>
                <th width="15%">Module</th>
                <th width="15%" class="center">Total Bookings</th>
                <th width="15%" class="center">Paid Bookings</th>
                <th width="15%" class="right">Revenue</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $row['temple_name'] }}</td>

                    <td>{{ $row['module'] }}</td>

                    <td class="center">
                        {{ number_format($row['total_bookings']) }}
                    </td>

                    <td class="center">
                        {{ number_format($row['paid_bookings']) }}
                    </td>

                    <td class="right">
                        {{ number_format($row['revenue'], 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">
                        No booking records found.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td></td>
                <td>GRAND TOTAL</td>
                <td></td>

                <td class="center">
                    {{ number_format($summary['total_bookings']) }}
                </td>

                <td class="center">
                    {{ number_format($summary['paid_bookings']) }}
                </td>

                <td class="right">
                    {{ number_format($summary['total_revenue'], 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d-m-Y h:i A') }}
    </div>

</body>
</html>