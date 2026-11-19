<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Puja Booking Completed</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0"
       style="background:#f5f5f5; padding:30px 10px;">

    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                   style="max-width:600px; background:#ffffff; border-radius:10px; overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center"
                        style="background:#ed741f; padding:30px 20px; color:#ffffff;">

                        <h1 style="margin:0; font-size:26px;">
                            Puja Successfully Completed
                        </h1>

                        <p style="margin:10px 0 0;">
                            Divine blessings and best wishes!
                        </p>

                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:30px; color:#444444;">

                        <h2 style="color:#222222;">
                            Namaste {{ $booking->customer_name ?? 'Devotee' }},
                        </h2>

                        <p style="font-size:15px; line-height:25px;">
                            We are pleased to inform you that your Puja
                            booking has been successfully completed.
                        </p>

                        <table width="100%" cellpadding="10"
                               cellspacing="0"
                               style="background:#fff8f2; border:1px solid #f5dfcd; border-radius:6px;">

                            <tr>
                                <td><strong>Booking ID</strong></td>
                                <td>
                                    #{{ $booking->id }}
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Puja Name</strong></td>
                                <td>
                                    {{ $booking->puja->title ?? $booking->puja->name ?? 'Puja' }}
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Booking Status</strong></td>
                                <td style="color:#28a745; font-weight:bold;">
                                    COMPLETED
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Completed At</strong></td>
                                <td>
                                    {{ optional($booking->completed_at)->format('d M Y, h:i A') ?? now()->format('d M Y, h:i A') }}
                                </td>
                            </tr>

                        </table>

                        <p style="font-size:15px; line-height:25px; margin-top:25px;">
                            Thank you for choosing our Puja services.
                            We are grateful for the opportunity to be part
                            of your spiritual journey.
                        </p>

                        <p style="font-size:15px; line-height:25px;">
                            May God bless you and your family with happiness,
                            peace, prosperity, and good health.
                        </p>

                        <p style="margin-top:30px;">
                            With Regards,<br>
                            <strong>Online Puja Booking Team</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center"
                        style="background:#f8f8f8; padding:20px; color:#888888; font-size:12px;">

                        This is an automated booking completion notification.
                        Please do not reply to this email.

                        <p style="margin-bottom:0;">
                            &copy; {{ date('Y') }} Online Puja Booking.
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