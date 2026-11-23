<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Completed</title>
</head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0"
           style="background:#f5f5f5; padding:30px 10px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:10px; overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#ed741f; padding:30px 20px; color:#ffffff;">
                            <h1 style="margin:0; font-size:26px;">
                                Booking Completed!
                            </h1>
                            <p style="margin:10px 0 0; font-size:15px;">
                                Thank you for choosing our Sanatan Puja service.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px; color:#333333;">
                            <h2 style="font-size:20px; margin-top:0;">
                                Namaste {{ $order->customer_name ?? 'Devotee' }} 🙏
                            </h2>
                            <p style="font-size:15px; line-height:1.7;">
                                We sincerely thank you for using our Sanatan Puja
                                service. Your booking has been successfully
                                completed after OTP verification.
                            </p>
                            <p style="font-size:15px; line-height:1.7;">
                                We hope your experience was peaceful, meaningful
                                and spiritually fulfilling.
                            </p>
                            <!-- Booking Details -->
                            <h3 style="color:#ed741f; margin-top:25px;">
                                Booking Details
                            </h3>
                            <table width="100%" cellpadding="10" cellspacing="0"  style="border-collapse:collapse; border:1px solid #eeeeee;">
                                <tr>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        <strong>Booking ID</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        #{{ $order->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        <strong>Puja Name</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        {{ $order->event_name ?? 'Sanatan Puja' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        <strong>Event Date</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        {{ $order->event_date ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        <strong>Event Time</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        {{ $order->event_time ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        <strong>Amount</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #eeeeee;">
                                        {{ $order->currency ?? 'INR' }}
                                        {{ number_format((float) $order->amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Booking Status</strong>
                                    </td>
                                    <td style="color:#169b62; font-weight:bold;">
                                        Completed
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size:15px; line-height:1.7; margin-top:25px;">
                                Your trust means a lot to us. We look forward
                                to serving you again.
                            </p>
                            <p style="font-size:16px; font-weight:bold;color:#ed741f; text-align:center;margin-top:25px;">
                                Thank you! Visit us again.
                            </p>
                            <p style="font-size:14px; line-height:1.7;">With gratitude,<br>
                                <strong>Sanatan Puja Team</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="background:#fafafa; padding:18px;
                            color:#777777; font-size:12px;">
                            This is an automatically generated email.
                            Please do not reply directly to this email.
                            <br><br>
                            &copy; {{ date('Y') }} Sanatan Puja. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>