
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Revenue Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 25px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 6px;
            font-size: 19px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }
        .summary h3 {
            margin: 5px 0;
            font-size: 15px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 12px 0 8px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th {
            background: #343a40;
            color: #fff;
            padding: 7px 4px;
            border: 1px solid #343a40;
            text-align: center;
        }
        .report-table td {
            border: 1px solid #ddd;
            padding: 7px 4px;
            text-align: center;
        }
        .report-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        .report-table tfoot td {
            background: #e9ecef;
            font-weight: bold;
        }
        .amount {
            text-align: right !important;
        }
        .total {
            color: #198754;
            font-weight: bold;
        }
        .note {
            margin-top: 12px;
            font-size: 8px;
            color: #666;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 7px;
            text-align: center;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>
@php
    $formatAmount = function ($amount) {
        return '₹ ' . number_format((float) ($amount ?? 0), 2);
    };
@endphp
<div class="header">
    <h2>Daily / Weekly / Monthly / Yearly Revenue Report</h2>
    <p>
        Report Type: {{ ucfirst($period) }}
    </p>
    <p>
        @if($fromDate || $toDate)
            Report Period:
            {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d-m-Y') : 'Beginning' }}
            to
            {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d-m-Y') : 'Today' }}
        @else
            All Records
        @endif
    </p>
    <p>
        Generated On: {{ now()->format('d-m-Y h:i A') }}
    </p>
</div>
<div class="section-title">Revenue Summary</div>
<table class="summary">
    <tr>
        <td>
            Total Bookings
            <h3>{{ number_format($summary['total_bookings']) }}</h3>
        </td>
        <td>
            Ticket Revenue
            <h3>{{ $formatAmount($summary['ticket_revenue']) }}</h3>
            {{ number_format($summary['ticket_bookings']) }} bookings
        </td>
        <td>
            Event Revenue
            <h3>{{ $formatAmount($summary['event_revenue']) }}</h3>
            {{ number_format($summary['event_bookings']) }} bookings
        </td>
        <td>
            Puja Revenue
            <h3>{{ $formatAmount($summary['puja_revenue']) }}</h3>
            {{ number_format($summary['puja_bookings']) }} bookings
        </td>
        <td>
            Total Revenue
            <h3 class="total">
                {{ $formatAmount($summary['total_revenue']) }}
            </h3>
        </td>
    </tr>
</table>
<div class="section-title">
    {{ ucfirst($period) }} Revenue Details
</div>
<table class="report-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Period</th>
            <th>Ticket Bookings</th>
            <th>Ticket Revenue</th>
            <th>Event Bookings</th>
            <th>Event Revenue</th>
            <th>Puja Bookings</th>
            <th>Puja Revenue</th>
            <th>Total Bookings</th>
            <th>Total Revenue</th>
        </tr>
    </thead>
    <tbody>
        @forelse($periods as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['period'] }}</td>
                <td>{{ number_format($item['ticket_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($item['ticket_revenue']) }}
                </td>
                <td>{{ number_format($item['event_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($item['event_revenue']) }}
                </td>
                <td>{{ number_format($item['puja_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($item['puja_revenue']) }}
                </td>
                <td>{{ number_format($item['total_bookings']) }}</td>
                <td class="amount total">
                    {{ $formatAmount($item['total_revenue']) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No revenue records found.</td>
            </tr>
        @endforelse
    </tbody>
    @if(count($periods) > 0)
        <tfoot>
            <tr>
                <td colspan="2">GRAND TOTAL</td>
                <td>{{ number_format($summary['ticket_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($summary['ticket_revenue']) }}
                </td>
                <td>{{ number_format($summary['event_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($summary['event_revenue']) }}
                </td>
                <td>{{ number_format($summary['puja_bookings']) }}</td>
                <td class="amount">
                    {{ $formatAmount($summary['puja_revenue']) }}
                </td>
                <td>{{ number_format($summary['total_bookings']) }}</td>
                <td class="amount total">
                    {{ $formatAmount($summary['total_revenue']) }}
                </td>
            </tr>
        </tfoot>
    @endif
</table>
<div class="note">
    <strong>Note:</strong>
    Revenue includes paid bookings only. Ticket bookings are counted by
    distinct Razorpay order ID, while ticket revenue is calculated from
    paid ticket rows. Event and Puja bookings are counted by their paid records.
</div>
<div class="footer">
    Revenue Report | Generated by the Temple Management System
</div>
</body>
</html>