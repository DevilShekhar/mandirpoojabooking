
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Overall Business Performance Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px;
            font-size: 20px;
            color: #222;
        }

        .header p {
            margin: 3px 0;
            color: #666;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 15px;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 15px;
            font-weight: bold;
            color: #222;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 15px 0 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .report-table th {
            background: #343a40;
            color: #fff;
            padding: 8px 5px;
            border: 1px solid #343a40;
            text-align: center;
        }

        .report-table td {
            padding: 7px 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        .report-table tfoot td {
            background: #e9ecef;
            font-weight: bold;
        }

        .text-left {
            text-align: left !important;
        }

        .amount {
            text-align: right !important;
        }

        .net-collection {
            color: #198754;
            font-weight: bold;
        }

        .note {
            margin-top: 12px;
            font-size: 9px;
            color: #666;
        }

        .footer {
            margin-top: 20px;
            padding-top: 7px;
            border-top: 1px solid #ddd;
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

    <h2>Overall Business Performance Report</h2>

    <p>
        @if(!empty($fromDate) || !empty($toDate))

            Report Period:

            {{ !empty($fromDate) ? \Carbon\Carbon::parse($fromDate)->format('d-m-Y') : 'Beginning' }}

            to

            {{ !empty($toDate) ? \Carbon\Carbon::parse($toDate)->format('d-m-Y') : 'Today' }}

        @else

            All Records

        @endif
    </p>

    <p>
        Generated On: {{ now()->format('d-m-Y h:i A') }}
    </p>

</div>


{{-- BUSINESS SUMMARY --}}

<div class="section-title">
    Business Summary
</div>

<table class="summary-table">

    <tr>

        <td>
            <div class="summary-label">Total Bookings</div>
            <div class="summary-value">
                {{ number_format($summary['total_bookings'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Paid Bookings</div>
            <div class="summary-value">
                {{ number_format($summary['paid_count'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Paid Amount</div>
            <div class="summary-value">
                {{ $formatAmount($summary['paid_amount'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Cancelled Bookings</div>
            <div class="summary-value">
                {{ number_format($summary['cancelled_count'] ?? 0) }}
            </div>
        </td>

    </tr>

    <tr>

        <td>
            <div class="summary-label">Cancelled Booking Amount</div>
            <div class="summary-value">
                {{ $formatAmount($summary['cancelled_amount'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Processed Refunds</div>
            <div class="summary-value">
                {{ number_format($summary['refund_count'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Refund Amount</div>
            <div class="summary-value">
                {{ $formatAmount($summary['refund_amount'] ?? 0) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Net Collection</div>
            <div class="summary-value net-collection">
                {{ $formatAmount($summary['net_collection'] ?? 0) }}
            </div>
        </td>

    </tr>

</table>


{{-- MODULE WISE REPORT --}}

<div class="section-title">
    Module-wise Business Performance
</div>

<table class="report-table">

    <thead>
        <tr>
            <th>#</th>
            <th>Module</th>
            <th>Total Bookings</th>
            <th>Paid Bookings</th>
            <th>Paid Amount</th>
            <th>Cancelled Bookings</th>
            <th>Cancelled Amount</th>
            <th>Processed Refunds</th>
            <th>Refund Amount</th>
            <th>Net Collection</th>
        </tr>
    </thead>

    <tbody>

        @forelse($modules as $index => $module)

            <tr>

                <td>{{ $index + 1 }}</td>

                <td class="text-left">
                    {{ $module['module'] ?? 'N/A' }}
                </td>

                <td>
                    {{ number_format($module['total_bookings'] ?? 0) }}
                </td>

                <td>
                    {{ number_format($module['paid_count'] ?? 0) }}
                </td>

                <td class="amount">
                    {{ $formatAmount($module['paid_amount'] ?? 0) }}
                </td>

                <td>
                    {{ number_format($module['cancelled_count'] ?? 0) }}
                </td>

                <td class="amount">
                    {{ $formatAmount($module['cancelled_amount'] ?? 0) }}
                </td>

                <td>
                    {{ number_format($module['refund_count'] ?? 0) }}
                </td>

                <td class="amount">
                    {{ $formatAmount($module['refund_amount'] ?? 0) }}
                </td>

                <td class="amount net-collection">
                    {{ $formatAmount(
                        ($module['paid_amount'] ?? 0) -
                        ($module['refund_amount'] ?? 0)
                    ) }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="10">No records found.</td>
            </tr>

        @endforelse

    </tbody>

    {{-- GRAND TOTAL --}}

    <tfoot>

        <tr>

            <td colspan="2">GRAND TOTAL</td>

            <td>
                {{ number_format($summary['total_bookings'] ?? 0) }}
            </td>

            <td>
                {{ number_format($summary['paid_count'] ?? 0) }}
            </td>

            <td class="amount">
                {{ $formatAmount($summary['paid_amount'] ?? 0) }}
            </td>

            <td>
                {{ number_format($summary['cancelled_count'] ?? 0) }}
            </td>

            <td class="amount">
                {{ $formatAmount($summary['cancelled_amount'] ?? 0) }}
            </td>

            <td>
                {{ number_format($summary['refund_count'] ?? 0) }}
            </td>

            <td class="amount">
                {{ $formatAmount($summary['refund_amount'] ?? 0) }}
            </td>

            <td class="amount net-collection">
                {{ $formatAmount($summary['net_collection'] ?? 0) }}
            </td>

        </tr>

    </tfoot>

</table>


<div class="note">

    <strong>Note:</strong>

    Net Collection = Paid Amount - Processed Refund Amount.

    Ticket booking refunds are not included in the current report calculation.
    Cancelled booking amounts are displayed separately and are not deducted again.

</div>


<div class="footer">

    Overall Business Performance Report |
    Generated by the Temple Management System

</div>

</body>
</html>