
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Summary Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 9px;
            text-align: center;
        }

        th {
            background-color: #eeeeee;
            font-weight: bold;
        }

        td:first-child {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background-color: #eeeeee;
        }

        .note {
            margin-top: 15px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <h2>Booking Summary Report</h2>

    <div class="details">
        <strong>Temple:</strong>
        {{ $selectedTemple->name }}
        <br>

        <strong>From Date:</strong>
        {{ $filters['from_date'] ?: 'All' }}
        <br>

        <strong>To Date:</strong>
        {{ $filters['to_date'] ?: 'All' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Booking Module</th>
                <th>Pending</th>
                <th>Confirmed</th>
                <th>Completed</th>
                <th>Cancelled</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($reports as $module => $report)
                <tr>
                    <td>
                        {{ $moduleNames[$module] }}

                        @if($module === 'event')
                            <br>
                            <small>Overall counts</small>
                        @endif
                    </td>

                    <td>{{ $report['pending'] }}</td>
                    <td>{{ $report['confirmed'] }}</td>
                    <td>{{ $report['completed'] }}</td>
                    <td>{{ $report['cancelled'] }}</td>
                    <td>{{ $report['total'] }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td>Grand Total</td>
                <td>{{ $summary['pending'] }}</td>
                <td>{{ $summary['confirmed'] }}</td>
                <td>{{ $summary['completed'] }}</td>
                <td>{{ $summary['cancelled'] }}</td>
                <td>{{ $summary['total'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="note">
        Note: Puja, Ticket and Samgri counts are filtered by the selected
        Temple. Event Booking counts are overall and are not filtered by Temple.
    </div>

</body>
</html>