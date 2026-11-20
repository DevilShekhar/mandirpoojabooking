
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Booking Confirmed</title>
</head>

<body style="margin:0; padding:0; background:#f3f5f9; font-family:Arial, Helvetica, sans-serif; color:#333;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#f3f5f9; padding:30px 10px;">

        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0" border="0"
                       style="max-width:650px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="background:#198754; padding:35px 20px; color:#ffffff;">

                            <div style="font-size:42px; margin-bottom:10px;">
                                &#10004;
                            </div>

                            <h1 style="margin:0; font-size:25px; font-weight:700;">
                                Booking Confirmed!
                            </h1>

                            <p style="margin:10px 0 0; font-size:14px; color:#e9fff2;">
                                Your temple ticket payment was successful.
                            </p>

                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding:30px 25px;">

                            <p style="font-size:15px; margin-top:0;">
                                Dear Customer,
                            </p>

                            <p style="font-size:14px; line-height:1.7; color:#555;">
                                Thank you for booking your temple tickets with us.
                                Your booking has been successfully confirmed.
                                Please find your booking details below.
                            </p>

                            <!-- Booking Reference -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background:#f8f9fa; border:1px solid #e5e7eb; border-radius:8px;">

                                <tr>
                                    <td align="center" style="padding:20px;">

                                        <p style="margin:0; font-size:12px; color:#777; text-transform:uppercase; letter-spacing:1px;">
                                            Booking Reference
                                        </p>

                                        <h2 style="margin:10px 0 0; color:#198754; font-size:23px;">
                                            {{ $bookingReference }}
                                        </h2>

                                    </td>
                                </tr>

                            </table>

                            <!-- OTP -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="margin-top:20px; background:#fff8ed; border:1px solid #f3d6a8; border-radius:8px;">

                                <tr>
                                    <td align="center" style="padding:22px 15px;">

                                        <p style="margin:0; font-size:13px; color:#8a5a15; font-weight:bold;">
                                            YOUR TICKET VERIFICATION OTP
                                        </p>

                                        <h1 style="margin:12px 0; font-size:36px; letter-spacing:10px; color:#d97706;">
                                            {{ $ticketOtp }}
                                        </h1>

                                        <p style="margin:0; font-size:12px; line-height:1.6; color:#795b30;">
                                            Keep this OTP confidential and share it only
                                            with authorized temple verification staff.
                                        </p>

                                    </td>
                                </tr>

                            </table>

                            <!-- Ticket Details -->
                            <h3 style="font-size:17px; color:#222; margin:30px 0 15px;">
                                Ticket Booking Details
                            </h3>

                            <table width="100%" cellpadding="10" cellspacing="0" border="0"
                                   style="border-collapse:collapse; font-size:13px;">

                                <thead>
                                    <tr style="background:#198754; color:#ffffff;">

                                        <th align="left" style="padding:12px 8px;">
                                            #
                                        </th>

                                        <th align="left" style="padding:12px 8px;">
                                            Member Details
                                        </th>

                                        <th align="left" style="padding:12px 8px;">
                                            Booking Date
                                        </th>

                                        <th align="left" style="padding:12px 8px;">
                                            Ticket Type
                                        </th>

                                        <th align="right" style="padding:12px 8px;">
                                            Amount
                                        </th>

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($bookings as $index => $booking)

                                        <tr style="background:{{ $index % 2 == 0 ? '#ffffff' : '#f8f9fa' }};">

                                            <td style="padding:12px 8px; border-bottom:1px solid #e5e7eb;">
                                                {{ $index + 1 }}
                                            </td>

                                            <td style="padding:12px 8px; border-bottom:1px solid #e5e7eb;">
                                                <strong>
                                                    {{ $booking->member_name }}
                                                </strong>

                                                <br>

                                                <span style="font-size:11px; color:#777;">
                                                    {{ $booking->temple->name ?? 'N/A' }}
                                                </span>
                                            </td>

                                            <td style="padding:12px 8px; border-bottom:1px solid #e5e7eb;">
                                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}

                                                @if($booking->booking_time)
                                                    <br>
                                                    <span style="font-size:11px; color:#777;">
                                                        {{ $booking->booking_time }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td style="padding:12px 8px; border-bottom:1px solid #e5e7eb;">
                                                {{ ucfirst($booking->ticket_type) }}
                                            </td>

                                            <td align="right" style="padding:12px 8px; border-bottom:1px solid #e5e7eb; white-space:nowrap;">
                                                ₹{{ number_format($booking->price, 2) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                                <!-- Total -->
                                <tfoot>

                                    <tr style="background:#f1f7f3;">

                                        <td colspan="4" align="right"
                                            style="padding:15px 8px; font-weight:bold; border-top:2px solid #198754;">

                                            Total Amount

                                        </td>

                                        <td align="right"
                                            style="padding:15px 8px; font-size:16px; font-weight:bold; color:#198754; border-top:2px solid #198754; white-space:nowrap;">

                                            ₹{{ number_format($bookings->sum('price'), 2) }}

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                            <!-- Booking Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="margin-top:20px; border:1px solid #e5e7eb; border-radius:8px;">

                                <tr>

                                    <td width="50%" align="center" style="padding:15px; border-right:1px solid #e5e7eb;">

                                        <div style="font-size:12px; color:#777;">
                                            Total Tickets
                                        </div>

                                        <strong style="font-size:21px; color:#222;">
                                            {{ $bookings->count() }}
                                        </strong>

                                    </td>

                                    <td width="50%" align="center" style="padding:15px;">

                                        <div style="font-size:12px; color:#777;">
                                            Payment Status
                                        </div>

                                        <strong style="font-size:16px; color:#198754;">
                                            PAID
                                        </strong>

                                    </td>

                                </tr>

                            </table>

                            <!-- Important Note -->
                            <div style="margin-top:25px; padding:15px; background:#f8f9fa; border-left:4px solid #198754;">

                                <strong style="font-size:13px; color:#333;">
                                    Important Information
                                </strong>

                                <p style="font-size:12px; color:#666; line-height:1.7; margin:8px 0 0;">
                                    Please carry your booking reference when visiting the temple.
                                    Keep your ticket OTP safe for verification by authorized temple staff.
                                    Contact our support team if you need assistance.
                                </p>

                            </div>

                            <p style="font-size:14px; margin-top:25px; line-height:1.6;">
                                Thank you for choosing our temple ticket booking service.
                                We wish you a peaceful and blessed visit.
                            </p>

                            <p style="font-size:14px; margin-bottom:0;">
                                Regards,<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background:#202c36; padding:22px 15px; color:#ffffff;">

                            <p style="font-size:13px; margin:0;">
                                Thank you for booking with {{ config('app.name') }}
                            </p>

                            <p style="font-size:11px; color:#b8c2cc; margin:10px 0 0;">
                                This is an automatically generated confirmation email.
                                Please do not reply to this email.
                            </p>

                            <p style="font-size:11px; color:#b8c2cc; margin:8px 0 0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}.
                                All rights reserved.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>
</html>
