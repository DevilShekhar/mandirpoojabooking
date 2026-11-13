<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Online Puja Report</title>
    <style>
        @page { margin: 22px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #303442; }
        h2 { text-align:center; margin:0 0 6px; font-size:18px; }
        h3 { margin:18px 0 7px; font-size:12px; }
        .meta { text-align:center; color:#666; margin-bottom:15px; }
        table { width:100%; border-collapse:collapse; margin-bottom:12px; }
        th,td { border:1px solid #d8dce4; padding:5px; text-align:left; vertical-align:top; }
        th { background:#f0f2f7; font-weight:bold; }
        .summary td:first-child { width:30%; font-weight:bold; background:#f8f9fb; }
        .small { font-size:8px; }
        .right { text-align:right; }
    </style>
</head>
<body>
    <h2>ONLINE PUJA REPORT</h2>
    <div class="meta">
        {{ $filters['from_input'] }} to {{ $filters['to_input'] }}
        | {{ $reportType === 'pandit' ? 'Pandit Wise Report' : 'Online Puja Wise Report' }}
    </div>

    <h3>Selected Filters</h3>
    <table class="summary">
        <tr><td>Pandit</td><td>{{ $filters['pandit_name'] ?? 'All Pandits' }}</td></tr>
        <tr><td>Customer</td><td>{{ $filters['customer_name'] ?? 'All Customers' }}</td></tr>
        <tr><td>Payment Status</td><td>{{ $filters['payment_status'] !== '' ? ucfirst(str_replace('_', ' ', $filters['payment_status'])) : 'All Payment Statuses' }}</td></tr>
        <tr><td>Booking Status</td><td>{{ $filters['booking_status'] !== '' ? ucfirst($filters['booking_status']) : 'All Booking Statuses' }}</td></tr>
    </table>

    <h3>Report Summary</h3>
    <table class="summary">
        <tr><td>Total Bookings</td><td>{{ number_format($summary['total_bookings']) }}</td></tr>
        <tr><td>Successful Payments</td><td>{{ number_format($summary['successful_bookings']) }}</td></tr>
        <tr><td>Pending Payments</td><td>{{ number_format($summary['pending_bookings']) }}</td></tr>
        <tr><td>Completed Pujas</td><td>{{ number_format($summary['completed_bookings']) }}</td></tr>
        <tr><td>Cancelled Bookings</td><td>{{ number_format($summary['cancelled_bookings']) }}</td></tr>
        <tr><td>Gross Revenue</td><td>{{ number_format($summary['gross_revenue'],2) }}</td></tr>
        <tr><td>Refund Amount</td><td>{{ number_format($summary['refund_amount'],2) }}</td></tr>
        <tr><td>Net Revenue</td><td>{{ number_format($summary['net_revenue'],2) }}</td></tr>
    </table>

    <h3>{{ $reportType === 'pandit' ? 'Pandit Wise Report' : 'Online Puja Wise Report' }}</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                @if($reportType === 'pandit') <th>Total Pujas</th> @else <th>Pandit / Creator</th> @endif
                <th>Bookings</th><th>Successful</th><th>Pending</th><th>Completed</th>
                <th>Cancelled</th><th>Gross</th><th>Refund</th><th>Net</th><th>Currency</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report as $row)
                <tr>
                    <td>{{ $row->group_name }}</td>
                    @if($reportType === 'pandit') <td>{{ $row->total_pujas }}</td> @else <td>{{ $row->pandit_name }}</td> @endif
                    <td>{{ $row->total_bookings }}</td>
                    <td>{{ $row->successful_bookings }}</td>
                    <td>{{ $row->pending_bookings }}</td>
                    <td>{{ $row->completed_bookings }}</td>
                    <td>{{ $row->cancelled_bookings }}</td>
                    <td>{{ number_format((float)$row->gross_revenue,2) }}</td>
                    <td>{{ number_format((float)$row->refund_amount,2) }}</td>
                    <td>{{ number_format((float)$row->net_revenue,2) }}</td>
                    <td>{{ $row->currency }}</td>
                </tr>
            @empty
                <tr><td colspan="11">No report records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Booking Details</h3>
    <table class="small">
        <thead>
            <tr>
                <th>Order No.</th><th>Customer</th><th>Online Puja</th><th>Pandit / Creator</th>
                <th>Package</th><th>Amount</th><th>Payment</th><th>Booking Status</th><th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->order_number }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->puja_title ?? 'N/A' }}</td>
                    <td>{{ $booking->pandit_name ?? 'Unknown Pandit' }}</td>
                    <td>{{ $booking->package_name }}</td>
                    <td>{{ $booking->currency }} {{ number_format((float)$booking->amount,2) }}</td>
                    <td>{{ $booking->payment_status }}</td>
                    <td>{{ $booking->booking_status }}</td>
                    <td>{{ $booking->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="9">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>